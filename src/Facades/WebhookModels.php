<?php

namespace Dniccum\NovaWebhooks\Facades;

class WebhookModels extends \Illuminate\Support\Facades\Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() {
        return 'webhook-models';
    }
}
