<?php

declare(strict_types=1);

namespace App\Http\Resources\Traits;

use App\Models\Award;
use App\Models\Candidate;
use App\Models\Skill;
use App\Models\TelevisionShow;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Lang;

use function array_key_exists;

use const null;

trait GetCandidateRelationValues
{
    protected array $skillsCache = [];

    protected array $televisionShowsCache = [];

    protected function getAwardResultValue(?int $awardResult): ?string
    {
        if (!$awardResult) {
            return null;
        }

        return Lang::get("client/candidate.constants.award_result.{$awardResult}");
    }

    protected function getAwards(Candidate $candidate): Collection
    {
        return $candidate
            ->getRelationValue('awards')
            ->map(function (Award $award) {
                $attributes = $award->only(['id', 'name']);
                $attributes += [
                    'television_show' => $this->getTelevisionShowName(
                        $award->getRelationValue('pivot')->getAttribute('television_show_id'),
                    ),
                    'result' => $this->getAwardResultValue($award->getRelationValue('pivot')->getAttribute('result')),
                ];

                return $attributes;
            });
    }

    protected function getBudgetOfBiggestShowValue(?int $budgetOfBiggestShowId): ?string
    {
        if (!$budgetOfBiggestShowId) {
            return null;
        }

        return Lang::get("common.constants.candidate.budget_of_biggest_show.{$budgetOfBiggestShowId}");
    }

    protected function getTelevisionShows(Candidate $candidate): Collection
    {
        return $candidate
            ->getRelationValue('televisionShows')
            ->map(function (TelevisionShow $televisionShow) {
                $attributes = $televisionShow->only(['id', 'name']);
                $attributes += [
                    'skill' => $this->getSkillTitle(
                        $televisionShow->getRelationValue('pivot')->getAttribute('skill_id'),
                    ),
                ];

                return $attributes;
            });
    }

    protected function getSalaryRateCurrencyValue(?int $salaryRateCurrencyId): ?string
    {
        if (!$salaryRateCurrencyId) {
            return null;
        }

        return Lang::get("common.constants.candidate.salary_rate_currency.{$salaryRateCurrencyId}");
    }

    protected function getSkillTitle(?int $skillId): ?string
    {
        if (!$skillId) {
            return null;
        }

        if (!array_key_exists($skillId, $this->skillsCache)) {
            $this->skillsCache[$skillId] = Skill::query()
                ->where('tinsel_town_id', $skillId)
                ->value('title');
        }

        return $this->skillsCache[$skillId];
    }

    protected function getSkills(Candidate $candidate): array
    {
        return $candidate
            ->getRelationValue('skills')
            ->reduce(static function (array $accumulator, Skill $skill) {
                $type = $skill->getRelationValue('pivot')->getAttribute('type');

                $level = $skill->getRelationValue('pivot')->getAttribute('level');
                $levelLabel = null !== $level
                    ? Lang::get("common.constants.candidate_skill.level.{$level}")
                    : null;

                $accumulator[$type][] = [
                    'value' => $skill->getKey(),
                    'level' => [
                        'value' => $level,
                        'label' => $levelLabel,
                    ],
                    'label' => "{$skill->getAttribute('title')} {$levelLabel}",
                ];

                return $accumulator;
            }, []);
    }

    protected function getTelevisionShowName(?int $televisionShowId): ?string
    {
        if (!$televisionShowId) {
            return null;
        }

        if (!array_key_exists($televisionShowId, $this->televisionShowsCache)) {
            $this->televisionShowsCache[$televisionShowId] = TelevisionShow::query()
                ->where('tinsel_town_id', $televisionShowId)
                ->value('name');
        }

        return $this->televisionShowsCache[$televisionShowId];
    }
}
