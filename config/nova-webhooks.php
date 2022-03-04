<?php

return [

    /**
     * Whether webhooks should be sent
     */
    'enabled' => env('NOVA_WEBHOOKS_ENABLED', true),

    /**
     * If logging should be enabled for each successful/failed request
     */
    'logging' => [

        'enabled' => env('NOVA_WEBHOOKS_LOGGING_ENABLED', true),
    ],

    /**
     * Enter the desired formatting for timestamps that are attached to logging.
     * See the official PHP documentation for more information: https://www.php.net/manual/en/datetime.format.php
     */
    'date_format' => 'Y-m-d @ G:i',

    /**
     * The Laravel Nova resource that manages your authenticated users.
     */
    'users' => [

        'resource' => App\Nova\User::class
    ]

];
