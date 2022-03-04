<?php

namespace Dniccum\NovaWebhooks\Listeners;

use Dniccum\NovaWebhooks\Models\WebhookLog;
use Spatie\WebhookServer\Events\WebhookCallSucceededEvent;

class WebhookSucceeded
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  WebhookCallSucceededEvent  $event
     * @return void
     */
    public function handle(WebhookCallSucceededEvent $event)
    {
        if (config('nova-webhooks.logging.enabled')) {
            $meta = $event->meta;
            $log = new WebhookLog;
            $log->webhook_id = isset($meta['webhook_id']) ? $meta['webhook_id'] : null;
            $log->save();
        }
    }
}
