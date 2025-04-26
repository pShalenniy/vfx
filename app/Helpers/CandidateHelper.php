<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Models\Candidate;
use App\Models\JobRole;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use McMatters\Ticl\Client;
use Throwable;

use function in_array;
use function mb_strstr;
use function range;
use function trim;

use const false;
use const null;
use const true;

class CandidateHelper
{
    public static int $maxCommercialExperienceYears = 60;

    public static function getCurrentDate(): Carbon
    {
        return Carbon::now();
    }

    public static function getCommercialExperienceMaxYear(): int
    {
        return (int) self::getCurrentDate()->format('Y');
    }

    public static function getCommercialExperienceMinYear(): int
    {
        return self::getCommercialExperienceMaxYear() - self::$maxCommercialExperienceYears;
    }

    public static function getCommercialExperienceYears(): int
    {
        return self::$maxCommercialExperienceYears;
    }

    public static function getCommercialExperienceYearsList(): array
    {
        return range(1, self::$maxCommercialExperienceYears);
    }

    public static function getCommercialExperienceYearsRangeList(): array
    {
        $commercialExperienceMaxYear = self::getCommercialExperienceMaxYear();
        $commercialExperienceMinYear = self::getCommercialExperienceMinYear();

        return range($commercialExperienceMaxYear, $commercialExperienceMinYear);
    }

    public static function getYearsOfCommercialExperience(?Carbon $commercialExperience): ?int
    {
        if (!$commercialExperience) {
            return null;
        }

        $commercialExperienceYears = self::getCurrentDate()->diffInYears($commercialExperience);

        return 0 !== $commercialExperienceYears ? $commercialExperienceYears : null;
    }

    public static function getJobRoles(Candidate $candidate): array
    {
        return $candidate
            ->getRelationValue('jobRoles')
            ->reduce(static function (array $accumulator, JobRole $jobRole) {
                $type = $jobRole->getRelationValue('pivot')->getAttribute('type');

                $accumulator[$type][] = [
                    'id' => $jobRole->getKey(),
                    'name' => $jobRole->getAttribute('name'),
                ];

                return $accumulator;
            }, []);
    }

    public static function checkPortfolioUrlImage(string $value): bool
    {
        $extensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp'];

        try {
            $response = (new Client())->get($value);

            $header = $response->getHeader('Content-Type');

            if (null === $header) {
                return false;
            }

            $extension = trim(mb_strstr($header, '/') ?: '', '/');

            return in_array($extension, $extensions, true);
        } catch (Throwable $e) {
            Log::error($e->getMessage(), [
                'command' => static::class,
                'value' => $value,
            ]);

            return false;
        }
    }

    public static function getInstagramRegexValidation(): string
    {
        return '~^(https?://)?(www\.)?instagram\.com/~';
    }

    public static function getPhoneNumberRegexValidation(): string
    {
        return '~^\+?\d{5,31}$~';
    }

    public static function getPortfolioRegexValidation(): string
    {
        return '~^(https?://)?(www\.)?(?<content>youtu\.be|(m\.)?youtube\.com|vimeo\.com)/.+~i';
    }

    public static function getTwitterRegexValidation(): string
    {
        return '~^(https?://)?(www\.)?twitter\.com/~';
    }
}
