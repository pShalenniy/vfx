<?php

declare(strict_types=1);

return [
    'table' => [
        'columns' => [
            'id' => 'ID',
            'name' => 'Name',
            'permissions' => 'Permissions',
            'actions' => 'Actions',
        ],
        'empty' => 'There are no records',
    ],
    'action' => [
        'create' => [
            'button' => 'Create',
            'success' => 'Role has been successfully created',
        ],
        'edit' => [
            'button' => 'Edit',
            'success' => 'Role has been successfully updated',
        ],
        'delete' => [
            'button' => 'Delete',
            'success' => 'Role has been successfully deleted',
            'failed' => 'Something went wrong, please try again.'
        ],
    ],
    'form' => [
        'name' => 'Name',
        'permissions' => 'Permissions',
    ],
];
