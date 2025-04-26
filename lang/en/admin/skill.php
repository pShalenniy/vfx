<?php

declare(strict_types=1);

return [
    'table' => [
        'columns' => [
            'id' => 'ID',
            'title' => 'Title',
            'created_at' => 'Created at',
            'actions' => 'Actions',
        ],
        'empty' => 'There are no records',
    ],
    'action' => [
        'create' => [
            'button' => 'Create',
            'success' => 'Skill has been successfully created',
        ],
        'edit' => [
            'success' => 'Skill has been successfully updated',
        ],
        'delete' => [
            'success' => 'Skill has been successfully deleted',
        ],
    ],
    'modals' => [
        'label' => 'Skill',
        'placeholder' => 'Enter skill',
    ],
];
