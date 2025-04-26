<?php

declare(strict_types=1);

namespace App\Console\Commands\Candidate;

use App\Helpers\IMDBHelper;
use App\Models\Candidate;
use App\Models\CandidateIMDBFilmography;
use App\Models\CandidateIMDBFilmographyEpisode;
use App\Models\TelevisionShow;
use App\Services\ScrapingService;
use Carbon\Carbon;
use ElasticsearchService;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

use function is_int;

use const null;

class ScrapeIMDB extends Command
{
    protected $signature = 'candidate:scrape:imdb {candidate}';

    protected $description = "Scrape candidate's IMDB profile";

    public function handle(ScrapingService $scrapingService): int
    {
        $candidate = Candidate::query()
            ->select(['id', 'imdb_link'])
            ->where('id', $this->argument('candidate'))
            ->first();

        if (!$candidate instanceof Candidate) {
            return self::FAILURE;
        }

        if (!$candidate->getAttribute('imdb_link')) {
            return self::SUCCESS;
        }

        $duplicatedCandidate = $this->getCandidateDuplicate($candidate);

        if ($duplicatedCandidate) {
            $filmography = $this->getDuplicatedCandidateFilmographies($duplicatedCandidate);
        } else {
            $filmography = $this->getFilmographiesFromIMDB($candidate, $scrapingService);

            if (is_int($filmography)) {
                return $filmography;
            }
        }

        $candidate->filmographies()->delete();

        $this->syncFilmographies($candidate, $filmography);

        ElasticsearchService::update($candidate);

        return self::SUCCESS;
    }

    protected function getCandidateDuplicate(Candidate $candidate): ?Candidate
    {
        return Candidate::query()
            ->select(['candidates.*', 'candidate_imdb_filmographies.updated_at'])
            ->join('candidate_imdb_filmographies', 'candidate_imdb_filmographies.candidate_id', '=', 'candidates.id')
            ->where('imdb_link', $candidate->getAttribute('imdb_link'))
            ->where('candidates.id', '!=', $candidate->getKey())
            ->where('candidate_imdb_filmographies.updated_at', '<=', Carbon::now())
            ->orderByDesc('candidate_imdb_filmographies.updated_at')
            ->first();
    }

    protected function getDuplicatedCandidateFilmographies(Candidate $candidate): array
    {
        $candidateFilmographies = $candidate->getRelationValue('filmographies');

        $filmographies = [];

        if (!$candidateFilmographies && !$candidateFilmographies->isEmpty()) {
            return $filmographies;
        }

        return $candidateFilmographies
            ->map(static function (CandidateIMDBFilmography $filmography) {
                $attributes = [];

                $attributes += $filmography->only([
                    'id',
                    'role_type',
                    'title',
                    'role',
                    'year',
                    'imdb_id',
                ]);

                $attributes += [
                    'episodes' => $filmography
                        ->getRelationValue('filmographyEpisodes')
                        ->map(
                            static fn (CandidateIMDBFilmographyEpisode $episode) => $episode->only([
                                'filmography_id',
                                'title',
                                'imdb_id',
                            ]),
                        ),
                ];

                return $attributes;
            })
            ->all();
    }

    protected function getFilmographiesFromIMDB(
        Candidate $candidate,
        ScrapingService $scrapingService,
    ): array|int {
        $works = $scrapingService->scrapeFromIMDB($candidate->getAttribute('imdb_link'));

        if (null === $works) {
            return self::FAILURE;
        }

        $filmography = [];

        foreach ($works as $roleTypeWorks) {
            $roleType = Str::snake($roleTypeWorks['title']);

            foreach ($roleTypeWorks['filmography'] ?? [] as $roleTypeWork) {
                $filmography[] = $roleTypeWork + ['role_type' => $roleType];
            }
        }

        return $filmography;
    }

    protected function syncFilmographies(
        Candidate $candidate,
        array $filmographies,
    ): void {
        foreach ($filmographies as $filmography) {
            /** @var \App\Models\CandidateIMDBFilmography $filmographyData */
            $filmographyData = $candidate->filmographies()->create([
                'role_type' => $filmography['role_type'] ?? null,
                'title' => $filmography['title'],
                'role' => $filmography['role'] ?: null,
                'year' => $filmography['year'],
                'imdb_id' => $filmography['imdb_id'] ?? IMDBHelper::getIdFromLink($filmography['link']),
                'poster_url' => $filmography['poster'] ?? null,
            ]);

            TelevisionShow::query()->firstOrCreate(
                ['imdb_id' => $filmography['imdb_id'] ?? IMDBHelper::getIdFromLink($filmography['link'])],
                [
                    'name' => $filmography['title'],
                    'end_year' => $filmography['year'],
                ],
            );

            foreach ($filmography['episodes'] ?? [] as $episode) {
                $filmographyData->filmographyEpisodes()->create([
                    'title' => $episode['title'],
                    'year' => $episode['year'] ?? null,
                    'details' => $episode['details'] ?? null,
                    'imdb_id' => $episode['imdb_id'] ?? IMDBHelper::getIdFromLink($episode['link']),
                ]);
            }
        }
    }
}
