<?php

declare(strict_types=1);

namespace App\Models;

use App\Helpers\CandidateHelper;
use App\Helpers\UrlHelper;
use App\Models\Pivot\AlternativeCitizenshipResidenceCandidate;
use App\Models\Pivot\AwardCandidate;
use App\Models\Pivot\CandidateJobRole;
use App\Models\Pivot\CandidateNationality;
use App\Models\Pivot\CandidatePreferredLocation;
use App\Models\Pivot\CandidatePreferredSector;
use App\Models\Pivot\CandidateShortlist;
use App\Models\Pivot\CandidateSkill;
use App\Models\Pivot\CandidateTelevisionShow;
use App\Models\Traits\HasRelationsWithEvents;
use App\Parsers\UrlParsers\VideoIdParser;
use App\Parsers\UrlParsers\VideoIdParsers\VimeoComParser;
use App\Parsers\UrlParsers\VideoIdParsers\YoutubeComParser;
use App\Parsers\UrlParsers\VideoIdParsers\YoutuBeParser;
use Carbon\Carbon;
use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;

use function base64_encode;
use function json_encode;
use function mb_strlen;
use function mb_strtolower;
use function mb_substr;
use function parse_url;
use function substr_replace;

use const null;

class Candidate extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasRelationsWithEvents;

    public const STORAGE_DISK = 'public';

    public const ALTERNATIVE_TALENT_COUNT = 3;

    public const BUDGET_OF_BIGGEST_SHOW_0_25M = 1;
    public const BUDGET_OF_BIGGEST_SHOW_25M_50M = 2;
    public const BUDGET_OF_BIGGEST_SHOW_50M_100M = 3;
    public const BUDGET_OF_BIGGEST_SHOW_100M_150M = 4;
    public const BUDGET_OF_BIGGEST_SHOW_GT150M = 5;

    public const DEFAULT_PICTURE_PATH = '/images/client/no-image.png';

    public const SALARY_RATE_CURRENCY_USD = 1;
    public const SALARY_RATE_CURRENCY_CAD = 2;
    public const SALARY_RATE_CURRENCY_EURO = 3;
    public const SALARY_RATE_CURRENCY_GBP = 4;
    public const SALARY_RATE_CURRENCY_FRANC = 5;
    public const SALARY_RATE_CURRENCY_ROUBLE = 6;
    public const SALARY_RATE_CURRENCY_KRONE = 7;

    public const SOURCE_DATABASE = 1;
    public const SOURCE_TINSEL_TOWN = 2;

    protected $table = 'candidates';

    protected $fillable = [
        'tinsel_town_id',
        'first_name',
        'last_name',
        'email',
        'password',
        'city_id',
        'region_id',
        'country_id',
        'timezone_id',
        'company_id',
        'budget_of_biggest_show',
        'phone_number',
        'portfolio_url',
        'shortfilm_url',
        'gross_annual_salary',
        'week_rate',
        'day_rate',
        'would_like_work_on',
        'commercial_experience',
        'preferred_sector_id',
        'travel_availability',
        'salary_rate_currency',
        'vfx_notes',
        'picture',
        'imdb_link',
        'linkedin_link',
        'instagram_link',
        'twitter_link',
        'current_work',
        'previous_work',
        'professional_interest',
        'next_availability',
        'source',
        'slug',
        'skill_circles',
    ];

    protected $casts = [
        'tinsel_town_id' => 'int',
        'first_name' => 'string',
        'last_name' => 'string',
        'email' => 'string',
        'password' => 'string',
        'city_id' => 'int',
        'region_id' => 'int',
        'country_id' => 'int',
        'timezone_id' => 'int',
        'company_id' => 'int',
        'budget_of_biggest_show' => 'int',
        'phone_number' => 'int',
        'portfolio_url' => 'json',
        'shortfilm_url' => 'json',
        'gross_annual_salary' => 'float',
        'week_rate' => 'float',
        'day_rate' => 'float',
        'would_like_work_on' => 'string',
        'commercial_experience' => 'datetime',
        'preferred_sector_id' => 'int',
        'travel_availability' => 'bool',
        'salary_rate_currency' => 'int',
        'vfx_notes' => 'string',
        'picture' => 'string',
        'imdb_link' => 'string',
        'linkedin_link' => 'string',
        'instagram_link' => 'string',
        'twitter_link' => 'string',
        'current_work' => 'string',
        'previous_work' => 'string',
        'professional_interest' => 'string',
        'next_availability' => 'datetime',
        'source' => 'int',
        'slug' => 'string',
        'skill_circles' => 'json',
    ];

    public function alternativeCitizenshipResidencies(): BelongsToMany
    {
        return $this
            ->belongsToMany(
                Country::class,
                'alternative_citizenship_residence_candidate',
                'candidate_id',
                'country_id',
                'id',
                'id',
                'alternativeCitizenshipResidencies',
            )
            ->using(AlternativeCitizenshipResidenceCandidate::class);
    }

    public function awards(): BelongsToMany
    {
        return $this
            ->belongsToMany(
                Award::class,
                'award_candidate',
                'candidate_id',
                'award_id',
                'id',
                'id',
                'awards',
            )
            ->withPivot(['television_show_id', 'result'])
            ->using(AwardCandidate::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id', 'id', 'city');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id', 'id', 'company');
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id', 'id', 'country');
    }

    public function filmographies(): HasMany
    {
        return $this->hasMany(CandidateIMDBFilmography::class, 'candidate_id', 'id');
    }

    public function jobRoles(): BelongsToMany
    {
        return $this
            ->belongsToMany(
                JobRole::class,
                'candidate_job_role',
                'candidate_id',
                'job_role_id',
                'id',
                'id',
                'jobRoles',
            )
            ->withPivot('type')
            ->using(CandidateJobRole::class);
    }

    public function linkedinExperiences(): HasMany
    {
        return $this->hasMany(CandidateLinkedinExperience::class, 'candidate_id', 'id');
    }

    public function nationalities(): BelongsToMany
    {
        return $this
            ->belongsToMany(
                Country::class,
                'candidate_nationality',
                'candidate_id',
                'country_id',
                'id',
                'id',
                'nationalities',
            )
            ->using(CandidateNationality::class);
    }

    public function preferredLocations(): BelongsToMany
    {
        return $this
            ->belongsToMany(
                PreferredLocation::class,
                'candidate_preferred_location',
                'candidate_id',
                'preferred_location_id',
                'id',
                'id',
                'preferredLocations',
            )
            ->using(CandidatePreferredLocation::class);
    }

    public function preferredSectors(): BelongsToMany
    {
        return $this
            ->belongsToMany(
                PreferredSector::class,
                'candidate_preferred_sector',
                'candidate_id',
                'preferred_sector_id',
                'id',
                'id',
                'preferredSectors',
            )
            ->using(CandidatePreferredSector::class);
    }

    public function preferredWorkEnvironments(): BelongsToMany
    {
        return $this
            ->belongsToMany(
                PreferredWorkEnvironment::class,
                'candidate_preferred_work_environment',
                'candidate_id',
                'preferred_work_environment_id',
                'id',
                'id',
                'preferredWorkEnvironments',
            )
            ->using(CandidatePreferredLocation::class);
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class, 'region_id', 'id', 'region');
    }

    public function shortlists(): BelongsToMany
    {
        return $this
            ->belongsToManyWithEvents(
                Shortlist::class,
                'candidate_shortlist',
                'candidate_id',
                'shortlist_id',
                'id',
                'id',
                'shortlists',
            )
            ->using(CandidateShortlist::class);
    }

    public function skills(): BelongsToMany
    {
        return $this
            ->belongsToMany(
                Skill::class,
                'candidate_skill',
                'candidate_id',
                'skill_id',
                'id',
                'id',
                'skills',
            )
            ->withPivot(['level', 'type'])
            ->using(CandidateSkill::class);
    }

    public function starred(): HasMany
    {
        return $this->hasMany(StarCandidate::class, 'candidate_id', 'id');
    }

    public function televisionShows(): BelongsToMany
    {
        return $this
            ->belongsToMany(
                TelevisionShow::class,
                'candidate_television_show',
                'candidate_id',
                'television_show_id',
                'id',
                'id',
                'televisionShows',
            )
            ->withPivot('skill_id')
            ->using(CandidateTelevisionShow::class);
    }

    public function timezone(): BelongsTo
    {
        return $this->belongsTo(Timezone::class, 'timezone_id', 'id', 'timezone');
    }

    public function viewedCompanies(): HasMany
    {
        return $this->hasMany(ViewedCandidate::class, 'candidate_id', 'id');
    }

    public function getFullNameAttribute(): string
    {
        return $this->attributes['first_name'].' '.$this->attributes['last_name'];
    }

    /**
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function getPictureEncodePathAttribute(): ?string
    {
        $storageDisk = Storage::disk(self::STORAGE_DISK);

        $file = $storageDisk->get($this->attributes['picture']);

        $type = $storageDisk->mimeType($this->attributes['picture']);

        if (!$type) {
            return null;
        }

        return "data:{$type};base64,".base64_encode($file);
    }

    public function setCommercialExperienceAttribute($value): ?string
    {
        return $this->attributes['commercial_experience'] = null !== $value
            ? Carbon::parse($value)->format('Y-m-d')
            : null;
    }

    public function setImdbLinkAttribute($value): ?string
    {
        return $this->attributes['imdb_link'] = null !== $value
            ? UrlHelper::stripAfterPath($value)
            : null;
    }

    public function setLinkedinLinkAttribute($value): ?string
    {
        return $this->attributes['linkedin_link'] = null !== $value
            ? UrlHelper::stripAfterPath($value)
            : null;
    }

    public function setPasswordAttribute(mixed $password): void
    {
        $this->attributes['password'] = Hash::make($password);
    }

    public function setPortfolioUrlAttribute($value): ?string
    {
        return $this->attributes['portfolio_url'] = null !== $value
            ? json_encode(
                $this->getFormattedPortfolioUrl(
                    $value,
                    [VimeoComParser::KEY, YoutubeComParser::KEY, YoutuBeParser::KEY],
                ),
            )
            : null;
    }

    public function setShortfilmUrlAttribute($value): ?string
    {
        return $this->attributes['shortfilm_url'] = null !== $value
            ? json_encode($this->getFormattedPortfolioUrl($value))
            : null;
    }

    protected function getFormattedPortfolioUrl(string $portfolioUrl, array $keys = []): ?array
    {
        $parseUrl = parse_url($portfolioUrl);

        $urlDomainLength = mb_strlen($parseUrl['scheme']) + mb_strlen($parseUrl['host']);

        $startPosition = 0;

        $portfolioUrl = substr_replace(
            $portfolioUrl,
            mb_strtolower(mb_substr($portfolioUrl, $startPosition, $urlDomainLength)),
            $startPosition,
            $urlDomainLength,
        );

        $result = Container::getInstance()
            ->make(VideoIdParser::class)
            ->parse($portfolioUrl, $keys);

        if (null !== $result) {
            return $result;
        }

        if (CandidateHelper::checkPortfolioUrlImage($portfolioUrl)) {
            return [
                'type' => 'image',
                'key' => 'image',
                'original' => $portfolioUrl,
            ];
        }

        return null;
    }
}
