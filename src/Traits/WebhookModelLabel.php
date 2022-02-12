<?php

namespace Dniccum\NovaWebhooks\Traits;

trait WebhookModelLabel
{
    /**
     * The name of the model that will be applied to the webhook
     *
     * @return string
     */
    public static function webhookLabel() : string
    {
        return self::class;
    }
}
