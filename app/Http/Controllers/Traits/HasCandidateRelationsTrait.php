<?php

declare(strict_types=1);

namespace App\Http\Controllers\Traits;

use App\Models\Candidate;
use App\Models\Company;
use App\Models\JobRole;
use App\Models\Pivot\CandidateJobRole;
use App\Models\Pivot\CandidateSkill;
use App\Models\PreferredLocation;
use App\Models\PreferredSector;
use App\Models\PreferredWorkEnvironment;
use App\Models\Skill;
use App\Models\TelevisionShow;
use Carbon\Carbon;
use Illuminate\Http\Request;

use const true;

trait HasCandidateRelationsTrait
{
    use HasRelationValueIdsTrait;

    protected function handleRelations(Candidate $candidate, Request $request): void
    {
        $alternativeCitizenshipResidencies = $request->get('alternative_citizenship_residencies', []);
        $nationalities = $request->get('nationalities', []);
        $preferredLocations = $request->get('preferred_locations', []);
        $preferredSectors = $request->get('preferred_sectors', []);
        $preferredWorkEnvironments = $request->get('preferred_work_environments', []);
        $televisionShows = $request->get('television_shows', []);

        $candidate->alternativeCitizenshipResidencies()->sync($alternativeCitizenshipResidencies);

        $candidate->nationalities()->sync($nationalities);

        $candidate->preferredLocations()->sync(
            $this->getValueIds($preferredLocations, PreferredLocation::class, 'name'),
        );

        $candidate->televisionShows()->sync(
            $this->getValueIds($televisionShows, TelevisionShow::class, 'name'),
        );

        $this->handleJobRoles(
            $candidate,
            $request->get('current_job_roles', []),
            $request->get('desired_job_roles', []),
            $request->get('next_promotion_job_roles', []),
        );

        $this->handlePreferredSectors(
            $candidate,
            $preferredSectors,
        );

        $this->handlePreferredWorkEnvironments(
            $candidate,
            $preferredWorkEnvironments,
        );

        $this->handleSkills(
            $candidate,
            [
                'skills' => $request->get('skills', []),
                'want_learn_skills' => $request->get('want_learn_skills', []),
                'want_work_with_tools' => $request->get('want_work_with_tools', []),
            ],
        );
    }

    protected function getValidatedInput(array $input): array
    {
        if (isset($input['commercial_experience'])) {
            $input['commercial_experience'] = Carbon::now()->setYear((int) $input['commercial_experience']);
        }

        if (isset($input['company'])) {
            $input['company_id'] = $this->getValueId(
                $input['company'],
                Company::class,
                'name',
            );
        }

        return $input;
    }

    protected function handleJobRoles(
        Candidate $candidate,
        array $currentJobRoles,
        array $desiredJobRoles,
        array $nextPromotionJobRoles,
    ): void {
        if (!$candidate->wasRecentlyCreated) {
            $candidate->jobRoles()->detach();
        }

        $candidate->jobRoles()->attach(
            $this->getValueIds(
                $currentJobRoles,
                JobRole::class,
                'name',
                ['type' => CandidateJobRole::TYPE_CURRENT],
            ),
        );

        $candidate->jobRoles()->attach(
            $this->getValueIds(
                $desiredJobRoles,
                JobRole::class,
                'name',
                ['type' => CandidateJobRole::TYPE_DESIRED],
            ),
        );

        $candidate->jobRoles()->attach(
            $this->getValueIds(
                $nextPromotionJobRoles,
                JobRole::class,
                'name',
                ['type' => CandidateJobRole::TYPE_NEXT_PROMOTION],
            ),
        );
    }

    protected function handlePreferredSectors(
        Candidate $candidate,
        array $preferredSectors,
    ): void {
        $candidate->preferredSectors()->sync(
            $this->getValueIds(
                $preferredSectors,
                PreferredSector::class,
                'name',
            ),
        );
    }

    protected function handlePreferredWorkEnvironments(
        Candidate $candidate,
        array $preferredWorkEnvironments,
    ): void {
        $candidate->preferredWorkEnvironments()->sync(
            $this->getValueIds(
                $preferredWorkEnvironments,
                PreferredWorkEnvironment::class,
                'name',
                additionalField: ['is_other' => true],
            ),
        );
    }

    protected function handleSkills(Candidate $candidate, array $items): void
    {
        if (!$candidate->wasRecentlyCreated) {
            $candidate->skills()->detach();
        }

        $candidate->skills()->attach(
            $this->getValueIds(
                $items['skills'],
                Skill::class,
                'title',
                ['type' => CandidateSkill::TYPE_KEY],
                additionalKeys: ['level'],
            ),
        );

        $candidate->skills()->attach(
            $this->getValueIds(
                $items['want_learn_skills'],
                Skill::class,
                'title',
                ['type' => CandidateSkill::TYPE_WANT_LEARN],
            ),
        );

        $candidate->skills()->attach(
            $this->getValueIds(
                $items['want_work_with_tools'],
                Skill::class,
                'title',
                ['type' => CandidateSkill::TYPE_WANT_WORK_WITH_TOOLS],
            ),
        );
    }
}
