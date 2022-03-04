<?php

namespace Dniccum\NovaWebhooks\Nova;

use Laravel\Nova\Nova;

trait UsesWebhookResource
{
    protected function resources()
    {
        Nova::resourcesIn(app_path('Nova'));

        Nova::resources([
            Webhook::class,
            WebhookLog::class,
        ]);
    }
}
