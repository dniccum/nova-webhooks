<?php

return [

    /**
     * Whether webhooks should be sent
     */
    'enable' => env('NOVA_WEBHOOKS_ENABLED', true),

    /**
     * All of the available models that should be crawled to compile the
     * available model listing.
     */
    'model_location' => env('NOVA_WEBHOOKS_MODEL_LOCATION', app_path('Models')),

];
