<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Candidate\Traits;

use App\Helpers\IMDBHelper;

use function array_key_exists;
use function preg_match;

use const null;

trait HasPreparedAttributesTrait
{
    protected function prepareForValidation(): void
    {
        $fields = [
            'city_id' => null,
            'region_id' => null,
            'country_id' => null,
            'timezone_id' => null,
            'company' => null,
            'budget_of_biggest_show' => null,
            'phone_number' => null,
            'portfolio_url' => null,
            'gross_annual_salary' => null,
            'week_rate' => null,
            'day_rate' => null,
            'commercial_experience' => null,
            'preferred_sector' => null,
            'salary_rate_currency' => null,
            'vfx_notes' => null,
            'picture' => null,
            'imdb_link' => null,
            'linkedin_link' => null,
            'instagram_link' => null,
            'twitter_link' => null,
        ];

        $linkFields = [
            'imdb_link',
            'linkedin_link',
            'instagram_link',
            'twitter_link',
            'portfolio_url',
        ];

        $data = $this->all();

        foreach ($linkFields as $linkField) {
            $link = $data[$linkField] ?? [];

            if (!empty($link) && !preg_match('#^https?://#', $link)) {
                $data[$linkField] = "https://{$link}";
            }
        }

        if (
            !empty($data['imdb_link']) &&
            null !== ($imdbLink = IMDBHelper::sanitizeLink($data['imdb_link']))
        ) {
            $data['imdb_link'] = $imdbLink;
        }

        // todo: make it better
        foreach ($fields as $key => $value) {
            if (!array_key_exists($key, $data)) {
                if ('company' === $key) {
                    $data["{$key}_id"] = $value;
                } else {
                    $data[$key] = $value;
                }
            }
        }

        $this->merge($data);
    }
}
