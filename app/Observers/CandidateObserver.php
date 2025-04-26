<?php

declare(strict_types=1);

namespace App\Observers;

use App\Jobs\Client\Candidate\ScrapeCandidateDataJob;
use App\Models\Candidate;
use ElasticsearchService;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Str;

use function preg_match;
use function preg_quote;

use const false;
use const null;

class CandidateObserver
{
    public function creating(Candidate $candidate): void
    {
        $candidate->setAttribute('slug', $this->getUniqueSlug($candidate));
    }

    public function created(Candidate $candidate): void
    {
        $this->scrapeCandidateData($candidate);

        ElasticsearchService::index($candidate);
    }

    public function updating(Candidate $candidate): void
    {
        $candidate->setAttribute('slug', $this->getUniqueSlug($candidate));
    }

    public function updated(Candidate $candidate): void
    {
        $this->scrapeCandidateData($candidate);

        ElasticsearchService::update($candidate);
    }

    public function deleted(Candidate $candidate): void
    {
        ElasticsearchService::delete($candidate);
    }

    protected function getUniqueSlug(Candidate $candidate): string
    {
        $createdSlug = null;

        if (!$candidate->wasRecentlyCreated) {
            $createdSlug = $candidate->getAttribute('slug');
        }

        $firstName = $candidate->getAttribute('first_name');
        $lastName = $candidate->getAttribute('last_name');

        $candidateSkill = $candidate
            ->getRelationValue('skills')
            ->first();

        if ($candidateSkill) {
            $slug = Str::slug("{$firstName} {$lastName} {$candidateSkill->getAttribute('title')}");
        } else {
            $slug = Str::slug("{$firstName} {$lastName}");
        }

        if ($slug === $createdSlug) {
            return $slug;
        }

        $query = $candidate->newQuery()
            ->withoutGlobalScopes()
            ->select(['slug'])
            ->when(!$candidate->wasRecentlyCreated, static function ($q) use ($candidate) {
                $q->where('id', '!=', $candidate->getKey());
            });

        $slugExists = null !== (clone $query)
                ->where('slug', $slug)
                ->toBase()
                ->first();

        if (!$slugExists) {
            return $slug;
        }

        $slugs = (clone $query)
            ->where('slug', 'like', "{$slug}-%")
            ->toBase()
            ->pluck('slug')
            ->all();

        if (empty($slugs)) {
            return "{$slug}-1";
        }

        $quoted = preg_quote($slug, '-');
        $number = 0;

        foreach ($slugs as $existSlug) {
            if (
                preg_match("~^{$quoted}(-(?<number>\d+))?$~", $existSlug, $match) &&
                ($match['number'] ?? '') &&
                ((int) $match['number']) > $number
            ) {
                $number = (int) $match['number'];
            }
        }

        if (0 !== $number) {
            $number++;

            return "{$slug}-{$number}";
        }

        return "{$slug}-1";
    }

    protected function scrapeCandidateData(Candidate $candidate): void
    {
        $changes = $candidate->getChanges();

        if ($candidate->wasRecentlyCreated) {
            Bus::dispatch(new ScrapeCandidateDataJob($candidate->getKey(), 'imdb'));
        } elseif (isset($changes['imdb_link'])) {
            Bus::dispatch(new ScrapeCandidateDataJob($candidate->getKey(), 'imdb'));
        } elseif (null === $candidate->getAttribute('imdb_link')) {
            $candidate->filmographies()->delete();
        }

        if ($candidate->wasRecentlyCreated) {
            Bus::dispatch(new ScrapeCandidateDataJob($candidate->getKey(), 'linkedin'));
        } elseif (isset($changes['linkedin_link'])) {
            Bus::dispatch(new ScrapeCandidateDataJob($candidate->getKey(), 'linkedin'));
        } elseif (null === $candidate->getAttribute('linkedin_link')) {
            $candidate->linkedinExperiences()->delete();
        }
    }
}
