<?php

namespace Dniccum\NovaWebhooks\Providers;

use Dniccum\NovaWebhooks\Listeners\WebhookFailed;
use Dniccum\NovaWebhooks\Listeners\WebhookSucceeded;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Spatie\WebhookServer\Events\FinalWebhookCallFailedEvent;
use Spatie\WebhookServer\Events\WebhookCallSucceededEvent;

class ToolEventServiceProvider extends ServiceProvider
{
    protected $listen = [
        WebhookCallSucceededEvent::class => [
            WebhookSucceeded::class
        ],
        FinalWebhookCallFailedEvent::class => [
            WebhookFailed::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
