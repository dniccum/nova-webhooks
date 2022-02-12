<?php

namespace Dniccum\NovaWebhooks\Traits;

use Dniccum\NovaWebhooks\Enums\ModelEvents;
use Dniccum\NovaWebhooks\Library\WebhookUtility;
use Illuminate\Database\Eloquent\Model;

/**
 * Executes a webhook when the extended model is emits a "created" event
 * @package dniccum/nova-webhooks
 */
trait CreatedWebhook
{
    use WebhookModelLabel;

    /**
     * @return void
     */
    public static function bootCreatedWebhook() : void
    {
        static::created(function ($model) {
            /**
             * @param \Illuminate\Database\Eloquent\Model $model
             */
            $payload = self::createdWebhookPayload($model);
            WebhookUtility::executeWebhook($model, ModelEvents::Created, $payload);
        });
    }

    /**
     * @param Model $model
     * @return array|mixed
     */
    protected static function createdWebhookPayload($model)
    {
        return $model->toArray();
    }
}
