<?php

namespace Dniccum\NovaWebhooks\Library;

use Dniccum\NovaWebhooks\Enums\ModelEvents;
use Dniccum\NovaWebhooks\Models\Webhook;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\PendingDispatch;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Spatie\WebhookServer\WebhookCall;

class WebhookUtility
{
    /**
     * @param Model $model
     * @param array|JsonResource $payload
     * @param string $action
     * @param bool $isTest If the webhook is running as a test through the testing action
     * @return void
     * @throws \Exception
     */
    public static function executeWebhook($model, string $action = ModelEvents::Created, $payload = [], bool $isTest = false) : void
    {
        if (!config('nova-webhooks.enabled')) {
            return;
        }

        if ($payload instanceof JsonResource) {
            $request = new Request();
            $payload = $payload->toArray($request);
        } elseif (!is_array($payload)) {
            throw new \Exception(
                __('nova-webhooks::nova.resource_validation_error'),
                500
            );
        }

        $shouldQueue = method_exists($model, 'queueWebhook') && $model::queueWebhook();
        if ($shouldQueue) {
            $jobToUse = $model::$job;
            dispatch(new $jobToUse($model, $action, $payload, $isTest));
        } else {
            self::processWebhooks($model, $action, $payload, $isTest);
        }
    }

    /**
     * @param Model $model
     * @param string $action
     * @param array $payload
     * @param bool $isTest If the webhook is running as a test through the testing action
     * @return void
     */
    public static function processWebhooks($model, $action, array $payload = [], bool $isTest = false)
    {
        /**
         * Retrieves the name of the model class with namespacing
         * @var string $className
         */
        $className  = get_class($model);

        $hooks = self::getWebhooks($className.':'.$action);
        $hooks->each(function(Webhook $webhook) use ($model, $payload, $isTest) {
            self::compileWebhook($webhook, $payload, $isTest);
        });
    }

    /**
     * @param Webhook $webhook
     * @param array $payload
     * @param bool $isTest If the webhook is being tested, a successful log entry will not be saved.
     * @return WebhookCall
     */
    public static function compileWebhook(Webhook $webhook, array $payload = [], bool $isTest = false) : PendingDispatch
    {
        return WebhookCall::create()
            ->url($webhook->url)
            ->meta([
                'webhook_id' => $webhook->id,
                'test' => $isTest,
            ])
            ->withTags([
                'nova-webhooks',
                \Str::slug($webhook->name).'-webhook',
            ])
            ->payload($payload)
            ->useSecret($webhook->secret)
            ->dispatch();
    }

    /**
     * @param $query
     * @return Collection|\Illuminate\Database\Eloquent\Collection
     */
    public static function getWebhooks($query)
    {
        if (DB::getDriverName() !== 'sqlite') {
            return Webhook::whereJsonContains('settings', [ '\\'.$query => true ])
                ->orWhereJsonContains('settings', [ $query => true ])
                ->get();
        } else {
            return Webhook::all()->filter(function(Webhook $webhook) use ($query) {
                return in_array($query, $webhook->settings) || in_array('\\'.$query, $webhook->settings);
            })->values();
        }
    }
}
