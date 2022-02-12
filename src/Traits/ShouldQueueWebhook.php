<?php

namespace Dniccum\NovaWebhooks\Traits;

use Dniccum\NovaWebhooks\Jobs\DispatchWebhook;

/**
 * When a webhook extends this trait, any webhooks will be dispatched to a job which will then be executed via queue
 * @package dniccum/nova-webhooks
 */
trait ShouldQueueWebhook
{
    /**
     * The job class that should be used to dispatch this model's webhook
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
