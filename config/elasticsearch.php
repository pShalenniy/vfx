<?php

declare(strict_types=1);

use App\Elasticsearch\Resources\CandidateResource;
use App\Models\Candidate;

return [
    'settings' => [
        'enabled' => env('ELASTICSEARCH_ENABLED', true),
        'hosts' => explode(',', (string) env('ELASTICSEARCH_HOSTS', 'localhost:9200')),
        'sslVerification' => env('ELASTICSEARCH_SSL_VERIFICATION', false),
        'source' => env('ELASTICSEARCH_SOURCE'),
        'defaults' => [
            'index' => env('ELASTICSEARCH_DEFAULT_INDEX'),
            'model' => env('ELASTICSEARCH_DEFAULT_MODEL'),
        ],
    ],
    'resources' => [
        Candidate::class => [
            'resource' => CandidateResource::class,
            'with' => [
                'city' => 'id,name',
                'company' => 'id,name',
                'country' => 'id,name',
                'region' => 'id,name',
                'shortlists' => 'id,title',
                'skills' => 'id,title',
                'job_roles' => 'id,name',
                'televisionShows' => 'id,name',
            ],
            'columns' => [
                'id',
                'first_name',
                'last_name',
                'timezone_id',
                'nationality',
                'current_work',
                'previous_work',
                'professional_interest',
                'next_availability',
            ],
        ],
    ],
];
