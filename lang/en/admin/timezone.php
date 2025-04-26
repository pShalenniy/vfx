<?php

declare(strict_types=1);

return [
    'table' => [
        'columns' => [
            'id' => 'ID',
            'code' => 'Code',
            'name' => 'Name',
            'offset' => 'Offset',
            'created_at' => 'Created at',
            'actions' => 'Actions',
        ],
        'empty' => 'There are no records',
    ],
    'action' => [
        'create' => [
            'button' => 'Create',
            'success' => 'Timezone has been successfully created',
        ],
        'edit' => [
            'button' => 'Edit',
            'success' => 'Timezone has been successfully updated',
        ],
        'delete' => [
            'button' => 'Delete',
            'success' => 'Timezone has been successfully deleted',
        ],
    ],
    'form' => [
        'code' => [
            'label' => 'Code',
            'placeholder' => 'Code',
        ],
        'name' => [
            'label' => 'Name',
            'placeholder' => 'Name',
        ],
        'offset' => [
            'label' => 'Offset',
            'placeholder' => 'Offset',
        ],
    ],
];
