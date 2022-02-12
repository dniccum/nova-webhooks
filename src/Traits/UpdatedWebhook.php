<?php

namespace Dniccum\NovaWebhooks\Traits;

use Dniccum\NovaWebhooks\Enums\ModelEvents;
use Dniccum\NovaWebhooks\Library\WebhookUtility;
use Illuminate\Database\Eloquent\Model;

/**
 * Executes a webhook when the extended model is emits an "updated" event
 * @package dniccum/nova-webhooks
 */
trait UpdatedWebhook
{
    use WebhookModelLabel;

    /**
     * @return void
     */
    public static function bootUpdatedWebhook() : void
    {
        static::updated(function ($model) {
            /**
             * @param \Illuminate\Database\Eloquent\Model $model
             */
            $payload = self::updatedWebhookPayload($model);
            WebhookUtility::executeWebhook($model, ModelEvents::Updated, $payload);
        });
    }

    /**
     * @param Model $model
     * @return array|mixed
     */
    protected static function updatedWebhookPayload($model)
    {
        return $model->toArray();
    }
}
