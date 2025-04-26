<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Enums\CommercialExperience;
use App\Http\Resources\Common\TelevisionShowResource;
use App\Models\Candidate;
use App\Models\City;
use App\Models\Company;
use App\Models\Country;
use App\Models\Department;
use App\Models\JobRole;
use App\Models\Permission;
use App\Models\PreferredLocation;
use App\Models\PreferredSector;
use App\Models\PreferredWorkEnvironment;
use App\Models\Role;
use App\Models\Skill;
use App\Models\Subscription;
use App\Models\TelevisionShow;
use App\Models\Timezone;
use Illuminate\Container\Container;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

use function array_merge;
use function array_merge_recursive;
use function array_replace_recursive;
use function in_array;
use function json_encode;
use function preg_match;

use const false;
use const JSON_HEX_AMP;
use const JSON_HEX_APOS;
use const JSON_HEX_QUOT;
use const JSON_HEX_TAG;
use const JSON_THROW_ON_ERROR;
use const null;
use const true;

class JsHelper
{
    protected static array $config = [];

    protected static array $jsChunks = [
        'admin',
        'client',
    ];

    protected static string $key = 'vfx';

    public static function getChunkedJsFiles(string $chunk): array
    {
        if (!in_array($chunk, static::$jsChunks, true)) {
            return [];
        }

        $publicPath = Container::getInstance()->make('app')?->publicPath();

        $iterator = (new Finder())->name('/~vendor(?:\.[a-z0-9]{20})?\.js$/')
            ->in("{$publicPath}/js")
            ->ignoreDotFiles(true)
            ->filter(static fn (SplFileInfo $file) => (bool) preg_match("#{$chunk}#", $file->getBasename()))
            ->ignoreVCS(true);

        $chunkFiles = [];

        /** @var \Symfony\Component\Finder\SplFileInfo $file */
        foreach ($iterator as $file) {
            $chunkFiles[] = URL::mix("js/{$file->getFilename()}");
        }

        return $chunkFiles;
    }

    public static function getKey(): string
    {
        return self::$key;
    }

    public static function push(array $data): void
    {
        self::$config = array_replace_recursive(self::$config, $data);
    }

    public static function toArray(): array
    {
        return array_merge_recursive(self::defaultConfigs(), self::$config);
    }

    /**
     * @throws \JsonException
     */
    public static function toString(): string
    {
        return json_encode(
            self::toArray(),
            JSON_THROW_ON_ERROR | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT,
        );
    }

    /**
     * @throws \JsonException
     */
    public static function toJson(): string
    {
        return self::toString();
    }

    protected static function adminDefaultConfigs(): array
    {
        $timezoneData = Timezone::query()
            ->select(['offset', 'code'])
            ->get()
            ->reduce(static function (array $accumulator, Timezone $timezone) {
                $accumulator['offsets'][] = $timezone->getAttribute('offset');
                $accumulator['codes'][] = $timezone->getAttribute('code');

                return $accumulator;
            }, []);

        return [
            'commercialExperiences' => [
                'values' => CandidateHelper::getCommercialExperienceYearsRangeList(),
                'maxYear' => CandidateHelper::getCommercialExperienceMaxYear(),
                'minYear' => CandidateHelper::getCommercialExperienceMinYear(),
            ],
            'subscriptionExpiringDaysPeriod' => Subscription::EXPIRING_DAYS_PERIOD,
            'departments' => Department::query()->toBase()->get(['id', 'name']),
            'permissions' => Permission::query()
                ->toBase()
                ->get(['id', 'description']),
            'preferredSectors' => PreferredSector::query()
                ->orderBy('name')
                ->toBase()
                ->get(['id', 'name']),
            'preferredWorkEnvironments' => PreferredWorkEnvironment::query()
                ->where('is_other', false)
                ->limit(100)
                ->toBase()
                ->get(['id', 'name']),
            'roles' => Role::query()->toBase()->get(['id', 'name']),
            'skills' => Skill::query()
                ->orderBy('title')
                ->limit(100)
                ->toBase()
                ->get(['id', 'title']),
            'televisionShows' => TelevisionShowResource::collection(
                TelevisionShow::query()
                    ->orderBy('name')
                    ->limit(100)
                    ->get(['id', 'name', 'season']),
            ),
            'timezones' => TimezoneHelper::getTimezonesList(),
            'timezonesList' => [
                'offsets' => $timezoneData['offsets'] ?? [],
                'codes' => $timezoneData['codes'] ?? [],
            ],
        ];
    }

    protected static function clientDefaultConfigs(): array
    {
        return [
            'subscriptionPauseCount' => Subscription::PAUSE_COUNT,
            'commercialExperienceValues' => CommercialExperience::asSelectOptions(),
        ];
    }

    protected static function commonDefaultConfigs(Request $request): array
    {
        $user = self::getUser($request);

        $config = [
            'app' => [
                'name' => Config::get('app.name'),
                'url' => Config::get('app.url'),
            ],
            'sentry' => [
                'dsn' => Config::get('sentry.dsn'),
                'environment' => Config::get('sentry.environment'),
                'release' => Config::get('sentry.release'),
            ],
            'extensions' => [
                'image' => Config::get('cms.files.extensions.image'),
                'video' => Config::get('cms.files.extensions.video'),
            ],
            'candidateDefaultPicturePath' => Candidate::DEFAULT_PICTURE_PATH,
            'user' => $user,
        ];

        if (null !== $user || $request->routeIs('sign-up.view')) {
            $config['cities'] = City::query()
                ->orderBy('name')
                ->limit(100)
                ->toBase()
                ->get(['id', 'name']);

            $config['companies'] = Company::query()
                ->orderBy('name')
                ->limit(100)
                ->toBase()
                ->get(['id', 'name']);

            $config['countries'] = Country::query()
                ->orderBy('name')
                ->toBase()
                ->get(['id', 'name']);

            $config['jobRoles'] = JobRole::query()
                ->orderBy('name')
                ->limit(100)
                ->toBase()
                ->get(['id', 'name']);

            $config['preferredLocations'] = PreferredLocation::query()
                ->orderBy('name')
                ->limit(100)
                ->toBase()
                ->get(['id', 'name']);
        }

        return $config;
    }

    protected static function defaultConfigs(): array
    {
        /** @var \Illuminate\Http\Request $request */
        $request = Container::getInstance()->make('request');

        if ($request->is(['admin', 'admin/*'])) {
            return array_merge(
                self::adminDefaultConfigs(),
                self::commonDefaultConfigs($request),
            );
        }

        return array_merge(
            self::clientDefaultConfigs(),
            self::commonDefaultConfigs($request),
        );
    }

    protected static function getUser(Request $request): ?array
    {
        /** @var \App\Models\Candidate|\App\Models\User $user */
        $user = $request->user();

        if (null === $user) {
            return null;
        }

        return [
            'id' => $user->getKey(),
            'first_name' => $user->getAttribute('first_name'),
            'last_name' => $user->getAttribute('last_name'),
            'email' => $user->getAttribute('email'),
            'company' => $user->getAttribute('company'),
            'city' => $user->getRelationValue('city')?->only(['id', 'name']),
            'region' => $user->getRelationValue('region')?->only(['id', 'name']),
            'country' => $user->getRelationValue('country')?->only(['id', 'name']),
            'job_title' => $user->getAttribute('job_title'),
            'phone_number' => $user->getAttribute('phone_number'),
            'role_id' => $user->getAttribute('role_id'),
        ];
    }
}
