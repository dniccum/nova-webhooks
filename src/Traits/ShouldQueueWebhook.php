<?php

namespace Dniccum\NovaWebhooks\Traits;

use Dniccum\NovaWebhooks\Jobs\DispatchWebhook;

trait ShouldQueueWebhook
{
    /**
     * The job class that should be used to
     *
     * @var string
     */
    public static $job = DispatchWebhook::class;

    /**
     * @return bool
     */
    public static function queueWebhook() : bool
    {
        return true;
    }
}
