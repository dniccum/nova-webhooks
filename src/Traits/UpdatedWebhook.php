<?php

namespace Dniccum\NovaWebhooks\Traits;

use Dniccum\NovaWebhooks\Library\WebhookUtility;
use Illuminate\Database\Eloquent\Model;

trait UpdatedWebhook
{
    /**
     * @return void
     */
    public static function bootUpdatedWebhook() : void
    {
        static::updated(function ($model) {
            $payload = self::updatedWebhookPayload($model);

            /**
             * @param \Illuminate\Database\Eloquent\Model $model
             */
            WebhookUtility::executeWebhook($model, 'updated', $payload);
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
