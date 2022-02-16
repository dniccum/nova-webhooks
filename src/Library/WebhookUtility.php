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
     * @return void
     * @throws \Exception
     */
    public static function executeWebhook($model, string $action = ModelEvents::Created, $payload = []) : void
    {
        if ($payload instanceof JsonResource) {
            $request = new Request();
            $payload = $payload->toArray($request);
        } elseif (!is_array($payload)) {
            throw new \Exception(
                'Please provide either a valid array or an instance of a JsonResource.', // TODO add translation
                500
            );
        }

        $shouldQueue = method_exists($model, 'queueWebhook') && $model::queueWebhook();
        if ($shouldQueue) {
            $jobToUse = $model::$job;
            dispatch(new $jobToUse($model, $action, $payload));
        } else {
            self::processWebhooks($model, $action, $payload);
        }
    }

    /**
     * @param Model $model
     * @param string $action
     * @param array $payload
     * @return void
     */
    public static function processWebhooks($model, $action, array $payload = [])
    {
        /**
         * Retrieves the name of the model class with namespacing
         * @var string $className
         */
        $className  = get_class($model);

        $hooks = self::getWebhooks($className.':'.$action);
        $hooks->each(function(Webhook $webhook) use ($model, $payload) {
            self::compileWebhook($webhook, $payload);
        });
    }

    /**
     * @param Webhook $webhook
     * @param array $payload
     * @return WebhookCall
     */
    public static function compileWebhook(Webhook $webhook, array $payload = []) : PendingDispatch
    {
        return WebhookCall::create()
            ->url($webhook->url)
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
