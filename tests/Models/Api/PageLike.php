<?php

namespace Dniccum\NovaWebhooks\Tests\Models\Api;

use Dniccum\NovaWebhooks\Tests\Database\Factories\Api\PageLikeFactory;
use Dniccum\NovaWebhooks\Tests\Resources\PageLikeResource;
use Dniccum\NovaWebhooks\Traits\DeletedWebhook;
use Dniccum\NovaWebhooks\Traits\UpdatedWebhook;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PageLike extends \Illuminate\Database\Eloquent\Model
{
    use HasFactory;
    use UpdatedWebhook;
    use DeletedWebhook;

    protected $fillable = [
        'page',
    ];

    public static function boot()
    {
        parent::boot();
    }

    protected static function newFactory()
    {
        return PageLikeFactory::new();
    }

    protected static function updatedWebhookPayload($model)
    {
        return new PageLikeResource($model);
    }

    protected static function deletedWebhookPayload($model)
    {
        return new PageLikeResource($model);
    }
}
