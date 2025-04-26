<?php

declare(strict_types=1);

namespace App\Console\Commands\Elasticsearch;

use ElasticsearchService;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Throwable;

class Setup extends Command
{
    protected $signature = 'elasticsearch:setup {--index=} {--replicas=} {--shards=}';

    protected $description = 'Setup elasticsearch';

    public function handle(): int
    {
        $config = Config::get('elasticsearch.settings');

        $index = $this->option('index') ?? Arr::get($config, 'defaults.index');

        try {
            ElasticsearchService::deleteIndex($index);
        } catch (Throwable) {
            // It means index doesn't exist.
        }

        ElasticsearchService::createIndex($this->getBody(), $index);

        $this->info('Elasticsearch setup is completed.');

        return self::SUCCESS;
    }

    protected function getBody(): array
    {
        $body = [
            'mappings' => [
                'properties' => [
                    'first_name' => [
                        'type' => 'text',
                        'fields' => [
                            'keyword' => [
                                'type' => 'keyword',
                                'ignore_above' => 255,
                            ],
                        ],
                    ],
                    'last_name' => [
                        'type' => 'text',
                        'fields' => [
                            'keyword' => [
                                'type' => 'keyword',
                                'ignore_above' => 255,
                            ],
                        ],
                    ],
                    'would_like_work_on' => [
                        'type' => 'text',
                        'fields' => [
                            'keyword' => [
                                'type' => 'keyword',
                                'ignore_above' => 10922,
                            ],
                        ],
                    ],
                    'departments' => [
                        'type' => 'integer',
                    ],
                    'nationalities' => [
                        'type' => 'nested',
                        'properties' => [
                            'id' => [
                                'type' => 'integer',
                            ],
                            'name' => [
                                'type' => 'text',
                                'fields' => [
                                    'keyword' => [
                                        'type' => 'keyword',
                                        'ignore_above' => 255,
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'current_work' => [
                        'type' => 'text',
                        'fields' => [
                            'keyword' => [
                                'type' => 'keyword',
                                'ignore_above' => 10922,
                            ],
                        ],
                    ],
                    'previous_work' => [
                        'type' => 'text',
                        'fields' => [
                            'keyword' => [
                                'type' => 'keyword',
                                'ignore_above' => 10922,
                            ],
                        ],
                    ],
                    'professional_interest' => [
                        'type' => 'text',
                        'fields' => [
                            'keyword' => [
                                'type' => 'keyword',
                                'ignore_above' => 10922,
                            ],
                        ],
                    ],
                    'email' => [
                        'type' => 'text',
                        'fields' => [
                            'keyword' => [
                                'type' => 'keyword',
                                'ignore_above' => 255,
                            ],
                        ],
                    ],
                    'next_availability' => [
                        'type' => 'date',
                        'format' => 'yyyy-MM-dd H:m:s',
                    ],
                    'commercial_experience' => [
                        'type' => 'integer',
                    ],
                    'timezone' => [
                        'properties' => [
                            'id' => [
                                'type' => 'integer',
                            ],
                            'name' => [
                                'type' => 'text',
                                'fields' => [
                                    'keyword' => [
                                        'type' => 'keyword',
                                        'ignore_above' => 255,
                                    ],
                                ],
                            ],
                            'offset' => [
                                'type' => 'text',
                                'fields' => [
                                    'keyword' => [
                                        'type' => 'keyword',
                                        'ignore_above' => 255,
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'city' => [
                        'properties' => [
                            'id' => [
                                'type' => 'integer',
                            ],
                            'name' => [
                                'type' => 'text',
                                'fields' => [
                                    'keyword' => [
                                        'type' => 'keyword',
                                        'ignore_above' => 255,
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'region' => [
                        'properties' => [
                            'id' => [
                                'type' => 'integer',
                            ],
                            'name' => [
                                'type' => 'text',
                                'fields' => [
                                    'keyword' => [
                                        'type' => 'keyword',
                                        'ignore_above' => 255,
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'country' => [
                        'properties' => [
                            'id' => [
                                'type' => 'integer',
                            ],
                            'name' => [
                                'type' => 'text',
                                'fields' => [
                                    'keyword' => [
                                        'type' => 'keyword',
                                        'ignore_above' => 255,
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'company' => [
                        'properties' => [
                            'id' => [
                                'type' => 'integer',
                            ],
                            'name' => [
                                'type' => 'text',
                                'fields' => [
                                    'keyword' => [
                                        'type' => 'keyword',
                                        'ignore_above' => 255,
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'preferred_sectors' => [
                        'type' => 'nested',
                        'properties' => [
                            'id' => [
                                'type' => 'integer',
                            ],
                            'name' => [
                                'type' => 'text',
                                'fields' => [
                                    'keyword' => [
                                        'type' => 'keyword',
                                        'ignore_above' => 255,
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'alternative_citizenship_residencies' => [
                        'type' => 'nested',
                        'properties' => [
                            'id' => [
                                'type' => 'integer',
                            ],
                            'name' => [
                                'type' => 'text',
                                'fields' => [
                                    'keyword' => [
                                        'type' => 'keyword',
                                        'ignore_above' => 255,
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'skills' => [
                        'type' => 'nested',
                        'properties' => [
                            'id' => [
                                'type' => 'integer',
                            ],
                            'title' => [
                                'type' => 'text',
                                'fields' => [
                                    'keyword' => [
                                        'type' => 'keyword',
                                        'ignore_above' => 255,
                                    ],
                                ],
                            ],
                            'level' => [
                                'type' => 'integer',
                            ],
                        ],
                    ],
                    'television_shows' => [
                        'type' => 'nested',
                        'properties' => [
                            'id' => [
                                'type' => 'integer',
                            ],
                            'name' => [
                                'type' => 'text',
                                'fields' => [
                                    'keyword' => [
                                        'type' => 'keyword',
                                        'ignore_above' => 255,
                                    ],
                                ],
                            ],
                            'imdb_id' => [
                                'type' => 'text',
                                'fields' => [
                                    'keyword' => [
                                        'type' => 'keyword',
                                        'ignore_above' => 255,
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'preferred_locations' => [
                        'type' => 'nested',
                        'properties' => [
                            'id' => [
                                'type' => 'integer',
                            ],
                            'name' => [
                                'type' => 'text',
                                'fields' => [
                                    'keyword' => [
                                        'type' => 'keyword',
                                        'ignore_above' => 255,
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'preferred_work_environments' => [
                        'type' => 'nested',
                        'properties' => [
                            'id' => [
                                'type' => 'integer',
                            ],
                            'name' => [
                                'type' => 'text',
                                'fields' => [
                                    'keyword' => [
                                        'type' => 'keyword',
                                        'ignore_above' => 255,
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'awards' => [
                        'type' => 'nested',
                        'properties' => [
                            'id' => [
                                'type' => 'integer',
                            ],
                            'name' => [
                                'type' => 'text',
                                'fields' => [
                                    'keyword' => [
                                        'type' => 'keyword',
                                        'ignore_above' => 255,
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'shortlists' => [
                        'type' => 'integer',
                    ],
                    'linkedin_experiences' => [
                        'type' => 'nested',
                        'properties' => [
                            'id' => [
                                'type' => 'integer',
                            ],
                            'company' => [
                                'type' => 'text',
                                'fields' => [
                                    'keyword' => [
                                        'type' => 'keyword',
                                        'ignore_above' => 255,
                                    ],
                                ],
                            ],
                            'details' => [
                                'type' => 'nested',
                                'properties' => [
                                    'title' => [
                                        'type' => 'text',
                                        'fields' => [
                                            'keyword' => [
                                                'type' => 'keyword',
                                                'ignore_above' => 255,
                                            ],
                                        ],
                                    ],
                                    'description' => [
                                        'type' => 'text',
                                        'fields' => [
                                            'keyword' => [
                                                'type' => 'keyword',
                                                'ignore_above' => 10922,
                                            ],
                                        ],
                                    ],
                                    'location' => [
                                        'type' => 'text',
                                        'fields' => [
                                            'keyword' => [
                                                'type' => 'keyword',
                                                'ignore_above' => 255,
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'filmographies' => [
                        'type' => 'nested',
                        'properties' => [
                            'id' => [
                                'type' => 'integer',
                            ],
                            'title' => [
                                'type' => 'text',
                                'fields' => [
                                    'keyword' => [
                                        'type' => 'keyword',
                                        'ignore_above' => 255,
                                    ],
                                ],
                            ],
                            'role' => [
                                'type' => 'text',
                                'fields' => [
                                    'keyword' => [
                                        'type' => 'keyword',
                                        'ignore_above' => 255,
                                    ],
                                ],
                            ],
                            'role_type' => [
                                'type' => 'text',
                                'fields' => [
                                    'keyword' => [
                                        'type' => 'keyword',
                                        'ignore_above' => 255,
                                    ],
                                ],
                            ],
                            'imdb_id' => [
                                'type' => 'text',
                                'fields' => [
                                    'keyword' => [
                                        'type' => 'keyword',
                                        'ignore_above' => 255,
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'current_job_roles' => [
                        'type' => 'nested',
                        'properties' => [
                            'id' => [
                                'type' => 'integer',
                            ],
                            'name' => [
                                'type' => 'text',
                                'fields' => [
                                    'keyword' => [
                                        'type' => 'keyword',
                                        'ignore_above' => 255,
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'desired_job_roles' => [
                        'type' => 'nested',
                        'properties' => [
                            'id' => [
                                'type' => 'integer',
                            ],
                            'name' => [
                                'type' => 'text',
                                'fields' => [
                                    'keyword' => [
                                        'type' => 'keyword',
                                        'ignore_above' => 255,
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'next_promotion_job_roles' => [
                        'type' => 'nested',
                        'properties' => [
                            'id' => [
                                'type' => 'integer',
                            ],
                            'name' => [
                                'type' => 'text',
                                'fields' => [
                                    'keyword' => [
                                        'type' => 'keyword',
                                        'ignore_above' => 255,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        if ($replicas = $this->option('replicas')) {
            $body['settings']['index']['number_of_replicas'] = (int) $replicas;
        }

        if ($shards = $this->option('shards')) {
            $body['settings']['index']['number_of_shards'] = (int) $shards;
        }

        return $body;
    }
}
