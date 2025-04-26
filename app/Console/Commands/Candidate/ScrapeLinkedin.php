<?php

declare(strict_types=1);

namespace App\Console\Commands\Candidate;

use App\Models\Candidate;
use App\Models\CandidateLinkedinExperience;
use App\Models\CandidateLinkedinExperienceDetail;
use App\Services\ScrapingService;
use Carbon\Carbon;
use ElasticsearchService;
use Illuminate\Console\Command;

use function array_filter;
use function intdiv;

use const null;

class ScrapeLinkedin extends Command
{
    protected $signature = 'candidate:scrape:linkedin {candidate}';

    protected $description = "Scrape candidate's Linkedin profile";

    public function handle(ScrapingService $scrapingService): int
    {
        $candidate = Candidate::query()
            ->select(['id', 'linkedin_link'])
            ->where('id', $this->argument('candidate'))
            ->whereNotNull('linkedin_link')
            ->first();

        if (!$candidate instanceof Candidate) {
            return self::FAILURE;
        }

        $duplicatedCandidate = $this->getDuplicatedCandidate($candidate);

        if ($duplicatedCandidate) {
            $experiences = $this->getCandidateExperiences($duplicatedCandidate);
        } else {
            $experiences = $scrapingService->scrapeFromLinkedin(
                $candidate->getAttribute('linkedin_link'),
            );

            if (null === $experiences) {
                return self::FAILURE;
            }
        }

        $candidate->linkedinExperiences()->delete();

        $this->syncCandidateExperiences($candidate, $experiences);

        ElasticsearchService::update($candidate);

        return self::SUCCESS;
    }

    protected function getCandidateExperiences(Candidate $candidate): array
    {
        $candidateExperiences = $candidate->getRelationValue('linkedinExperiences');

        $experiences = [];

        if (!$candidateExperiences && !$candidateExperiences->isEmpty()) {
            return $experiences;
        }

        return $candidateExperiences
            ->map(static function (CandidateLinkedinExperience $experience) {
                $attributes = [];

                $attributes += $experience->only(['id', 'image', 'company']);

                $attributes += [
                    'positions' => $experience
                        ->getRelationValue('details')
                        ->map(
                            static fn (CandidateLinkedinExperienceDetail $detail) => $detail
                                ->only([
                                    'experience_id',
                                    'title',
                                    'description',
                                    'location',
                                    'dates',
                                    'employment',
                                ]),
                        ),
                ];

                return $attributes;
            })
            ->all();
    }

    protected function getDuplicatedCandidate(Candidate $candidate): ?Candidate
    {
        return Candidate::query()
            ->select(['candidates.*', 'candidate_linkedin_experiences.updated_at'])
            ->join(
                'candidate_linkedin_experiences',
                'candidate_linkedin_experiences.candidate_id',
                '=',
                'candidates.id',
            )
            ->where('linkedin_link', $candidate->getAttribute('linkedin_link'))
            ->where('candidates.id', '!=', $candidate->getKey())
            ->where('candidate_linkedin_experiences.updated_at', '<=', Carbon::now())
            ->orderByDesc('candidate_linkedin_experiences.updated_at')
            ->first();
    }

    protected function syncCandidateExperiences(Candidate $candidate, array $experiences): void
    {
        foreach ($experiences as $experience) {
            $details = [];
            $months = 0;

            foreach ($experience['positions'] as $detail) {
                $months += (int) ($detail['months'] ?? 0);

                $details[] = [
                    'title' => $detail['title'],
                    'description' => $detail['description'],
                    'location' => $detail['location'],
                    'dates' => $detail['dates'],
                    'employment' => $detail['employment'],
                ];
            }

            /** @var \App\Models\CandidateLinkedinExperience $experienceData */
            $experienceData = $candidate->linkedinExperiences()->create([
                'image' => $experience['image'],
                'company' => $experience['company'],
                'working_period' => $this->getCandidateWorkingPeriod($months),
            ]);

            $experienceData->details()->createMany($details);
        }
    }

    protected function getCandidateWorkingPeriod(int $months): ?array
    {
        $monthsInYear = 12;

        $years = intdiv($months, $monthsInYear);

        $months %= $monthsInYear;

        $period = array_filter([
            'years' => $years,
            'months' => $months,
        ]);

        return !empty($period) ? $period : null;
    }
}
