<?php

declare(strict_types=1);

return [
    'table' => [
        'columns' => [
            'id' => 'ID',
            'logo' => 'Logo',
            'created_at' => 'Created at',
            'actions' => 'Actions',
        ],
        'empty' => 'There are no records',
    ],
    'action' => [
        'delete' => [
            'button' => 'Delete',
            'success' => "'Our partner' has been successfully deleted",
        ],
        'edit' => [
            'button' => 'Edit',
            'success' => "'Our partner' has been successfully updated",
        ],
        'create' => [
            'button' => 'Create',
            'success' => "'Our partner' has been successfully created",
        ],
    ],
    'modals' => [
        'label' => 'Our partner',
        'placeholder' => 'Choose a file or drop it here...',
        'drop_placeholder' => 'Drop file here...',
    ],
];
