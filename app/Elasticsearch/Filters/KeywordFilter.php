<?php

declare(strict_types=1);

namespace App\Elasticsearch\Filters;

use Illuminate\Http\Request;

use function array_keys;
use function trim;

use const false;

class KeywordFilter implements ElasticsearchFilter
{
    public function apply(Request $request, array $body): array
    {
        if (!($keyword = trim((string) $request->get('keyword', '')))) {
            return $body;
        }

        $filteredFields = [];

        $fields = [
            'city.name' => [
                'boost' => 4,
            ],
            'region.name' => [
                'boost' => 4,
            ],
            'country.name' => [
                'boost' => 5,
            ],
            'preferred_sectors.name' => [
                'boost' => 4,
                'nested' => [
                    'path' => 'preferred_sectors',
                ],
            ],
            'timezone.name' => [
                'boost' => 4,
            ],
            'timezone.offset' => [
                'boost' => 2,
            ],
            'company.name' => [
                'boost' => 4,
            ],
            'first_name' => [
                'boost' => 2,
            ],
            'last_name' => [
                'boost' => 3,
            ],
            'email' => [
                'boost' => 2,
            ],
            'nationalities.name' => [
                'boost' => 2,
                'nested' => [
                    'path' => 'nationalities',
                ],
            ],
            'current_work' => [
                'boost' => 2,
            ],
            'previous_work' => [
                'boost' => 2,
            ],
            'professional_interest' => [
                'boost' => 2,
            ],
            'would_like_work_on' => [
                'boost' => 2,
            ],
            'alternative_citizenship_residencies.name' => [
                'boost' => 5,
                'nested' => [
                    'path' => 'alternative_citizenship_residencies',
                ],
            ],
            'awards.name' => [
                'boost' => 9,
                'nested' => [
                    'path' => 'awards',
                ],
            ],
            'skills.title' => [
                'boost' => 10,
                'nested' => [
                    'path' => 'skills',
                ],
            ],
            'current_job_roles.name' => [
                'boost' => 10,
                'nested' => [
                    'path' => 'current_job_roles',
                ],
            ],
            'desired_job_roles.name' => [
                'boost' => 10,
                'nested' => [
                    'path' => 'current_job_roles',
                ],
            ],
            'next_promotion_job_roles.name' => [
                'boost' => 10,
                'nested' => [
                    'path' => 'current_job_roles',
                ],
            ],
            'television_shows.name' => [
                'boost' => 9,
                'nested' => [
                    'path' => 'television_shows',
                ],
            ],
            'preferred_locations.name' => [
                'boost' => 8,
                'nested' => [
                    'path' => 'preferred_locations',
                ],
            ],
            'filmographies.title' => [
                'boost' => 6,
                'nested' => [
                    'path' => 'filmographies',
                ],
            ],
            'filmographies.role' => [
                'boost' => 6,
                'nested' => [
                    'path' => 'filmographies',
                ],
            ],
            'filmographies.role_type' => [
                'boost' => 6,
                'nested' => [
                    'path' => 'filmographies',
                ],
            ],
            'linkedin_experiences.company' => [
                'boost' => 10,
                'nested' => [
                    'path' => 'linkedin_experiences',
                ],
            ],
            'linkedin_experiences.details.title' => [
                'boost' => 6,
                'nested' => [
                    'path' => 'linkedin_experiences',
                    'sub_path' => 'linkedin_experiences.details',
                ],
            ],
            'linkedin_experiences.details.description' => [
                'boost' => 6,
                'nested' => [
                    'path' => 'linkedin_experiences',
                    'sub_path' => 'linkedin_experiences.details',
                ],
            ],
            'linkedin_experiences.details.location' => [
                'boost' => 6,
                'nested' => [
                    'path' => 'linkedin_experiences',
                    'sub_path' => 'linkedin_experiences.details',
                ],
            ],
        ];

        foreach ($fields as $field => $fieldDetail) {
            if (isset($fieldDetail['nested'])) {
                $nestedData = $fieldDetail['nested'];
                $query = $this->getQueryData($field, $keyword, $fieldDetail['boost']);

                if (isset($nestedData['sub_path'])) {
                    $filteredFields[] = [
                        'nested' => [
                            'path' => $nestedData['path'],
                            'query' => [
                                'nested' => [
                                    'path' => $nestedData['sub_path'],
                                    'query' => $query,
                                ],
                            ],
                        ],
                    ];
                } else {
                    $filteredFields[] = [
                        'nested' => [
                            'path' => $nestedData['path'],
                            'query' => $query,
                        ],
                    ];
                }
            } else {
                $filteredFields[] = $this->getQueryData($field, $keyword, $fieldDetail['boost']);
            }
        }

        $body['query']['bool']['must'][]['bool']['should'] = [
            [
                'bool' => [
                    'must' => [
                        'query_string' => [
                            'fields' => array_keys($fields),
                            'query' => $keyword,
                            'auto_generate_synonyms_phrase_query' => false,
                            'fuzzy_transpositions' => false,
                        ],
                    ],
                ],
            ],
            ...$filteredFields,
        ];

        return $body;
    }

    protected function getQueryData(string $field, string $keyword, int $boost): array
    {
        return [
            'bool' => [
                'must' => [
                    'query_string' => [
                        'default_field' => "{$field}.keyword",
                        'query' => $keyword,
                        'auto_generate_synonyms_phrase_query' => false,
                        'fuzzy_transpositions' => false,
                        'boost' => $boost,
                    ],
                ],
            ],
        ];
    }
}
