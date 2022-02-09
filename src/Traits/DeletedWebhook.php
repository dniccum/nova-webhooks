<?php

namespace Dniccum\NovaWebhooks\Traits;

use Dniccum\NovaWebhooks\Enums\ModelEvents;
use Dniccum\NovaWebhooks\Library\WebhookUtility;
use Illuminate\Database\Eloquent\Model;

trait DeletedWebhook
{
    /**
     * @return void
     */
    public static function bootDeletedWebhook() : void
    {
        static::deleted(function ($model) {
            /**
             * @param \Illuminate\Database\Eloquent\Model $model
             */
            $payload = self::deletedWebhookPayload($model);
            WebhookUtility::executeWebhook($model, ModelEvents::Deleted, $payload);
        });
    }

    /**
     * @param Model $model
     * @return array|mixed
     */
    protected static function deletedWebhookPayload($model)
    {
        return $model->toArray();
    }
}
