<?php

namespace Dniccum\NovaWebhooks\Http\Controllers;

use Dniccum\NovaWebhooks\Http\Resources\WebhookResource;
use Dniccum\NovaWebhooks\Models\Webhook;
use Illuminate\Http\Request;

class NovaWebhooksController
{

    public function store(Request $request) // TODO add validation
    {
        $webhook = new Webhook($request->validated());
        $webhook->save();

        return new WebhookResource($webhook);
    }

}
