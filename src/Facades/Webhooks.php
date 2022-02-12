<?php

namespace Dniccum\NovaWebhooks\Facades;

class Webhooks extends \Illuminate\Support\Facades\Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() {
        return 'webhooks';
    }
}
