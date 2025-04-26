<!DOCTYPE html>
<html dir="ltr" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- todo: replace it into styles -->
    <style>body { font-family: DejaVu Sans; }</style>
</head>
<body>
    <div>
        <div>
            @if ($candidate->getAttribute('picture'))
                <img src="{{ $candidate->getPictureEncodePathAttribute() }}" width="170" height="200">
            @endif
        </div>
        @php
            $country = $candidate->getRelationvalue('country')?->getAttribute('name');
            $region = $candidate->getRelationvalue('region')?->getAttribute('name');
            $city = $candidate->getRelationvalue('city')?->getAttribute('name');
            $nationalities = $candidate->getRelationValue('nationalities');
            $alternativeCitizenshipResidencies = $candidate->getRelationValue('alternativeCitizenshipResidencies');
            $preferredLocations = $candidate->getRelationValue('preferredLocations');
            $timezone = $candidate->getRelationvalue('timezone');
            $nextAvailability = $candidate->getAttribute('next_availability')?->format('Y-m-d');
        @endphp
        <div>
            <h3>
                @lang('client/candidate.content_section.personal.title')
            </h3>
            <p>
                <b>
                    {{ $candidate->getAttribute('full_name') }}
                </b>
            </p>
            @if ($country)
                <p>
                    @lang('client/candidate.content_section.personal.country')
                    {{ $country }}
                </p>
            @endif
            @if ($region)
                <p>
                    @lang('client/candidate.content_section.personal.region')
                    {{ $region }}
                </p>
            @endif
            @if ($city)
                <p>
                    @lang('client/candidate.content_section.personal.city')
                    {{ $city }}
                </p>
            @endif
            @if ($nationalities && $nationalities->isNotEmpty())
                @lang('client/candidate.content_section.personal.nationalities')
                <ul>
                    @foreach ($nationalities as $nationality)
                        <li>
                            {{ $nationality->getAttribute('name') }}
                        </li>
                    @endforeach
                </ul>
            @endif
            @if ($timezone)
                <p>
                    @lang('client/candidate.content_section.personal.timezone')
                    {{ $timezone->getAttribute('name') }} - {{ $timezone->getAttribute('offset') }}
                </p>
            @endif
            @if ($nextAvailability)
                <p>
                    @lang('client/candidate.content_section.personal.next_availability')
                    {{ $nextAvailability }}
                </p>
            @endif
            @if ($preferredLocations && $preferredLocations->isNotEmpty())
                @lang('client/candidate.content_section.personal.preferred_location')
                <ul>
                    @foreach ($preferredLocations as $preferredLocation)
                        <li>
                            {{ $preferredLocation->getAttribute('name') }}
                        </li>
                    @endforeach
                </ul>
            @endif
            @if ($alternativeCitizenshipResidencies && $alternativeCitizenshipResidencies->isNotEmpty())
                <p>
                    @lang('client/candidate.content_section.personal.alternative_citizenship_residencies')
                    <ul>
                        @foreach ($alternativeCitizenshipResidencies as $alternativeCitizenshipResidence)
                            <li>
                                {{ $alternativeCitizenshipResidence->getAttribute('name') }}
                            </li>
                        @endforeach
                    </ul>
                </p>
            @endif
        </div>
        <div>
            @php
                $travelAvailability = $candidate->getAttribute('travel_availability');
                $commercialExperience = $candidate->getAttribute('commercial_experience');
                $preferredSectors = $candidate->getRelationvalue('preferredSectors');
                $preferredWorkEnvironments = $candidate->getRelationValue('preferredWorkEnvironments');
            @endphp
            @if (
                !empty($skills) ||
                !empty($jobRoles[\App\Models\Pivot\CandidateJobRole::TYPE_CURRENT]) ||
                !empty($jobRoles[\App\Models\Pivot\CandidateJobRole::TYPE_DESIRED]) ||
                !empty($jobRoles[\App\Models\Pivot\CandidateJobRole::TYPE_NEXT_PROMOTION]) ||
                $preferredWorkEnvironments->isNotEmpty() ||
                $preferredSectors->isNotEmpty() ||
                $travelAvailability ||
                $televisionShows ||
                $budgetOfBiggestShow ||
                $commercialExperience
            )
               <h3>
                   @lang('client/candidate.content_section.professional.title')
               </h3>
                @if (!empty($skills[\App\Models\Pivot\CandidateSkill::TYPE_KEY]))
                    @lang('client/candidate.content_section.professional.skills')
                    <ul>
                        @foreach ($skills[\App\Models\Pivot\CandidateSkill::TYPE_KEY] as $skill)
                            <li>
                                {{ $skill['label'] }}
                            </li>
                        @endforeach
                    </ul>
                @endif
                @if (!empty($skills[\App\Models\Pivot\CandidateSkill::TYPE_WANT_LEARN]))
                    @lang('client/candidate.content_section.professional.want_learn_skills')
                    <ul>
                        @foreach ($skills[\App\Models\Pivot\CandidateSkill::TYPE_WANT_LEARN] as $wantLearnSkill)
                            <li>
                                {{ $wantLearnSkill['label'] }}
                            </li>
                        @endforeach
                    </ul>
                @endif
                @if (!empty($skills[\App\Models\Pivot\CandidateSkill::TYPE_WANT_WORK_WITH_TOOLS]))
                    @lang('client/candidate.content_section.professional.want_work_with_tools')
                    <ul>
                        @foreach ($skills[\App\Models\Pivot\CandidateSkill::TYPE_WANT_WORK_WITH_TOOLS] as $wantWorkWithTool)
                            <li>
                                {{ $wantWorkWithTool['label'] }}
                            </li>
                        @endforeach
                    </ul>
                @endif
                @if (!empty($jobRoles[\App\Models\Pivot\CandidateJobRole::TYPE_CURRENT]))
                    @lang('client/candidate.content_section.professional.current_job_roles')
                    <ul>
                        @foreach ($jobRoles[\App\Models\Pivot\CandidateJobRole::TYPE_CURRENT] as $currentJobRole)
                            <li>
                                {{ $currentJobRole['name'] }}
                            </li>
                        @endforeach
                    </ul>
                @endif
                @if (!empty($jobRoles[\App\Models\Pivot\CandidateJobRole::TYPE_DESIRED]))
                    @lang('client/candidate.content_section.professional.desired_job_roles')
                    <ul>
                        @foreach ($jobRoles[\App\Models\Pivot\CandidateJobRole::TYPE_DESIRED] as $desiredJobRole)
                            <li>
                                {{ $desiredJobRole['name'] }}
                            </li>
                        @endforeach
                    </ul>
                @endif
                @if (!empty($jobRoles[\App\Models\Pivot\CandidateJobRole::TYPE_NEXT_PROMOTION]))
                    @lang('client/candidate.content_section.professional.next_promotion_job_roles')
                    <ul>
                        @foreach ($jobRoles[\App\Models\Pivot\CandidateJobRole::TYPE_NEXT_PROMOTION] as $nextPromotionJobRole)
                            <li>
                                {{ $nextPromotionJobRole['name'] }}
                            </li>
                        @endforeach
                    </ul>
                @endif
                @if ($preferredSectors->isNotEmpty())
                    @lang('client/candidate.content_section.professional.preferred_sectors')
                    <ul>
                        @foreach ($preferredSectors as $preferredSector)
                            <li>
                                {{ $preferredSector['name'] }}
                            </li>
                        @endforeach
                    </ul>
                @endif
                @if ($preferredWorkEnvironments->isNotEmpty())
                    @lang('client/candidate.content_section.professional.preferred_work_environments')
                    <ul>
                        @foreach ($preferredWorkEnvironments as $preferredWorkEnvironment)
                            <li>
                                {{ $preferredWorkEnvironment['name'] }}
                            </li>
                        @endforeach
                    </ul>
                @endif
                <p>
                    @lang('client/candidate.content_section.professional.travel_availability.title')
                    {{
                        $travelAvailability
                         ? \Illuminate\Support\Facades\Lang::get('client/candidate.content_section.professional.travel_availability.yes')
                         : \Illuminate\Support\Facades\Lang::get('client/candidate.content_section.professional.travel_availability.no')
                    }}
                </p>
                @if ($company = $candidate->getAttribute('company'))
                    <p>
                        @lang('client/candidate.content_section.professional.company')
                        {{ $company->getAttribute('name') }}
                    </p>
                @endif
                @if ($commercialExperience)
                    <p>
                        @lang('client/candidate.content_section.professional.commercial_experience')
                        {{ $commercialExperience }}
                    </p>
                @endif
                @if ($televisionShows && $televisionShows->isNotEmpty())
                    <div>
                        @lang('client/candidate.content_section.professional.television_shows')
                        <ul>
                            @foreach ($televisionShows as $televisionShow)
                                <li>
                                    {{ $televisionShow['name'] }}
                                    {{ $televisionShow['skill'] ? " - {$televisionShow['skill']}" : '' }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if (!empty($budgetOfBiggestShow))
                    <p>
                        @lang('client/candidate.content_section.professional.budget_of_biggest_show')
                        {{ $budgetOfBiggestShow }}
                    </p>
                @endif
            @endif
        </div>
        <div>
            @php
                $grossAnnualSalary = $candidate->getAttribute('gross_annual_salary');
                $weekRate = $candidate->getAttribute('week_rate');
                $dayRate = $candidate->getAttribute('day_rate');
            @endphp
            @if ($grossAnnualSalary || $weekRate || $dayRate || $salaryRateCurrency)
                <h3>@lang('client/candidate.content_section.renumeration.title')</h3>
                @if ($grossAnnualSalary)
                    <p>
                        @lang('client/candidate.content_section.renumeration.gross_annual_salary')
                        {{ $grossAnnualSalary }}
                    </p>
                @endif
                @if ($weekRate)
                    <p>
                        @lang('client/candidate.content_section.renumeration.week_rate')
                        {{ $weekRate }}
                    </p>
                @endif
                @if ($dayRate)
                    <p>
                        @lang('client/candidate.content_section.renumeration.day_rate')
                        {{ $dayRate }}
                    </p>
                @endif
                @if ($salaryRateCurrency)
                    <p>
                        @lang('client/candidate.content_section.renumeration.salary_rate_currency')
                        {{ $salaryRateCurrency }}
                    </p>
                @endif
            @endif
        </div>
        <div>
            @php
                $instagramLink = $candidate->getAttribute('instagram_link');
                $linkedinLink = $candidate->getAttribute('linkedin_link');
                $twitterLink = $candidate->getAttribute('twitter_link');
                $portfolioUrl = $candidate->getAttribute('portfolio_url');
                $phoneNumber = $candidate->getAttribute('phone_number')
            @endphp
            @if ($instagramLink || $linkedinLink || $twitterLink || $portfolioUrl || $phoneNumber)
               <h3>
                   @lang('client/candidate.content_section.contact_social_media.title')
               </h3>
                @if ($instagramLink)
                    <div>
                        <a :href="{{ $instagramLink }}" target="_blank">
                            {{ $instagramLink }}
                        </a>
                    </div>
                @endif
                @if ($linkedinLink)
                    <div>
                        <a :href="{{ $linkedinLink }}" target="_blank">
                            {{ $linkedinLink }}
                        </a>
                    </div>
                @endif
                @if ($twitterLink)
                    <div>
                        <a :href="{{ $twitterLink }}" target="_blank">
                            {{ $twitterLink }}
                        </a>
                    </div>
                @endif
                @if ($portfolioUrl)
                    <div>
                        <a :href="{{ $portfolioUrl }}" target="_blank">
                            {{ $portfolioUrl }}
                        </a>
                    </div>
                @endif
                <p>
                    {{ $candidate->getAttribute('email') }}
                </p>
                @if ($phoneNumber)
                    <p>
                        @lang('client/candidate.content_section.contact_social_media.phone_number')
                        +{{ $phoneNumber }}
                    </p>
                @endif
            @endif
        </div>
        <div>
            @if ($currentWork = $candidate->getAttribute('current_work'))
                <div>
                    <h3>
                        @lang('client/candidate.content_section.current_work')
                    </h3>
                    <hr>
                    {{ $currentWork }}
                </div>
            @endif
            @if ($previousWork = $candidate->getAttribute('previous_work'))
                <div>
                    <h3>
                        @lang('client/candidate.content_section.previous_work')
                    </h3>
                    <hr>
                    {{ $previousWork }}
                </div>
            @endif
            @if ($awards && $awards->isNotEmpty())
                <div>
                    <h3>
                        @lang('client/candidate.content_section.awards')
                    </h3>
                    <hr>
                    <ul>
                        @foreach ($awards as $award)
                            <li>
                                {{ $award['name'] }}
                                @if ($award['television_show'])
                                    - {{ $award['television_show'] }}
                                @endif
                                @if ($award['result'])
                                    - {{ $award['result'] }}
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if ($professionalInterest = $candidate->getAttribute('professional_interest'))
                <div>
                    <h3>
                        @lang('client/candidate.content_section.professional_interest')
                    </h3>
                    <hr>
                    {{ $professionalInterest }}
                </div>
            @endif
            @if ($wouldLikeWorkOn = $candidate->getAttribute('would_like_work_on'))
                <div>
                    <h3>
                        @lang('client/candidate.content_section.would_like_work_on')
                     </h3>
                    {{ $wouldLikeWorkOn }}
                </div>
            @endif
        </div>
        @php
            $linkedinExperiences = $candidate->getRelationvalue('linkedinExperiences');
        @endphp
        @if ($linkedinExperiences && $linkedinExperiences->isNotEmpty())
            <h3>@lang('client/candidate.content_section.linkedin_experience.title')</h3>
            <hr>
            @foreach ($linkedinExperiences as $linkedinExperience)
                @php
                    $company = $linkedinExperience->getAttribute('company');
                    $companyImage = $linkedinExperience->getAttribute('image');
                @endphp
                <div>
                    <div>
                        @if ($companyImage)
                            <img
                                src="{{ $companyImage }}"
                                alt=""
                            >
                        @else
                            <img
                                src="{{ URL::asset('images/client/linkedin-no-image.jpg') }}"
                                alt=""
                            >
                        @endif
                        @if ($company)
                            <p>{{ $company }}</p>
                        @endif
                        @php
                           $linkedinExperienceDetails = $linkedinExperience->getRelationValue('details');
                        @endphp
                        @if ($linkedinExperienceDetails && $linkedinExperienceDetails->isNotEmpty())
                            @foreach ($linkedinExperienceDetails as $linkedinExperienceDetail)
                                @php
                                    $dates = $linkedinExperienceDetail->getAttribute('dates');
                                    $title = $linkedinExperienceDetail->getAttribute('title');
                                    $location = $linkedinExperienceDetail->getAttribute('location');
                                    $employment = $linkedinExperienceDetail->getAttribute('employment');
                                    $description = $linkedinExperienceDetail->getAttribute('description');
                                    $skills = $linkedinExperienceDetail->getAttribute('skills');
                                @endphp
                                @if ($title)
                                    <p>
                                        {{ $title }}
                                    </p>
                                @endif
                                @if ($location)
                                    <p>
                                        @lang('client/candidate.content_section.linkedin_experience.location')
                                        {{ $location }}
                                    </p>
                                @endif
                                @if ($employment)
                                    <p>{{ $employment }}</p>
                                @endif
                                @if ($dates)
                                    <p>
                                        @lang('client/candidate.content_section.linkedin_experience.dates')
                                        {{ $dates }}
                                    </p>
                                @endif
                                @if ($description)
                                    <p>
                                        @lang('client/candidate.content_section.linkedin_experience.description')
                                    </p>
                                    <p>{{ $description }}</p>
                                @endif
                                @if ($skills)
                                    <p>
                                        @lang('client/candidate.content_section.linkedin_experience.skills')
                                        {{ $skills }}
                                    </p>
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>
            @endforeach
        @endif
        @php
            $filmography = $candidate->getRelationvalue('filmographies')->groupBy('role_type');
        @endphp
        @if ($filmography && $filmography->isNotEmpty())
            <h3>@lang('client/candidate.content_section.filmography')</h3>
            <hr>
            <ol>
                @foreach ($filmography as $title => $groupMovies)
                    <h3>
                        @lang("client/candidate.role_types.{$title}")
                    </h3>
                    @foreach ($groupMovies as $movie)
                        <li>
                            {{ "{$movie->getAttribute('title')} {$movie->getAttribute('role')} {$movie->getAttribute('year')}" }}
                            @php
                                $filmographyEpisodes = $movie->getRelationValue('filmographyEpisodes');
                            @endphp
                            @if ($filmographyEpisodes && $filmographyEpisodes->isNotEmpty())
                                <br>
                                <b>@lang('client/candidate.content_section.episodes')</b>
                                <ul>
                                    @foreach ($filmographyEpisodes as $episodes)
                                        <li>
                                            {{ $episodes->getAttribute('title') }}
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endforeach
                @endforeach
            </ol>
        @endif
    </div>
</body>
</html>
