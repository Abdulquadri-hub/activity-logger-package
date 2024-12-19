<?php

return [
    'routes' => [
        'enabled' => true,
        'prefix' => 'activity-logger',
        'middleware' => ['web', 'auth'],
        'api_enabled' => true,
        'api_prefix' => 'api/activity-logger',
        'api_middleware' => ['api', 'auth:sanctum'],
    ],

    'middleware' => [
        'global' => false,
    ],

    'observers' => [
        'enabled' => true,
    ],

    'track_page_visits' => true,
    'track_authentication' => true,
    'track_model_changes' => true,
    'retention_days' => 90,

    'activity_types' => [
        'auth' => ['login', 'logout', 'register', 'password_reset'],
        'job' => ['created', 'updated', 'deleted'],
        'subscription' => ['created', 'updated', 'cancelled'],
        'page' => ['visited'],
        'custom' => [],
    ],

    'user_model' => \App\Models\User::class,
];