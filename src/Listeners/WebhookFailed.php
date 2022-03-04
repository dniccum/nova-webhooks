<?php

namespace Dniccum\NovaWebhooks\Listeners;

use Dniccum\NovaWebhooks\Models\WebhookLog;
use Spatie\WebhookServer\Events\FinalWebhookCallFailedEvent;

class WebhookFailed
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
     * @param  FinalWebhookCallFailedEvent  $event
     * @return void
     */
    public function handle(FinalWebhookCallFailedEvent $event)
    {
        $message = $event->errorMessage;
        $errorCode = optional($event->response)->getStatusCode();

        if (!$message || strlen($message) === 0) {
            if ($errorCode == 404) {
                $message = __('nova-webhooks.logging.not_found');
            }
        }

        if (config('nova-webhooks.logging.enabled')) {
            $meta = $event->meta;
            $log = new WebhookLog([
                'successful' => false,
                'error_code' => $errorCode,
                'error_message' => $message,
                'webhook_id' => isset($meta['webhook_id']) ? $meta['webhook_id'] : null,
            ]);
            $log->save();
        }
    }
}
