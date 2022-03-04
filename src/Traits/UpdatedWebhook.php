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
            self::updatedWebhook($model);
        });
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param boolean $isTest If the webhook is running as a test through the testing action
     * @return void
     * @throws \Exception
     */
    public static function updatedWebhook($model, bool $isTest = false)
    {
        $payload = self::updatedWebhookPayload($model);
        WebhookUtility::executeWebhook($model, ModelEvents::Updated, $payload, $isTest);
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
