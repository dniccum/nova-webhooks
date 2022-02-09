<?php

namespace Dniccum\NovaWebhooks\Tests\Resources;

class PageLikeResource extends \Illuminate\Http\Resources\Json\JsonResource
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
            'page' => 'Page Like',
            'created' => 'recently'
        ];
    }
}
