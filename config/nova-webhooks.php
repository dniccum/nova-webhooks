<?php

return [

    /**
     * Whether webhooks should be sent
     */
    'enabled' => env('NOVA_WEBHOOKS_ENABLED', true),

    /**
     * The Laravel Nova resource that manages your authenticated users.
     */
    'users' => [

        'resource' => App\Nova\User::class
    ]

];
