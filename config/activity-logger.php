<?php

return [
    'track_page_visits' => true,
    'track_authentication' => true,
    'track_model_changes' => true,
    'retention_days' => 90,
    
    // Custom activity types
    'types' => [
        'auth' => [
            'login',
            'logout',
            'register',
            'password_reset'
        ],
        'job' => [
            'created',
            'updated',
            'deleted'
        ],
        'subscription' => [
            'created',
            'updated',
            'cancelled'
        ]
    ]
];