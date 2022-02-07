<?php

namespace Dniccum\NovaWebhooks\Http\Resources;

use App\Models\User;

class WebhookResource extends \Illuminate\Http\Resources\Json\JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'url' => $this->url,
            'settings' => $this->settings,
            'secret' => $this->secret,
            'modified_by' => User::firstWhere('id', $this->modified_by) ?? $this->modified_by,
            'created_at' => $this->created_at->timestamp,
            'updated_at' => $this->created_at->updated_at,
        ];
    }
}
