<?php

namespace Dniccum\NovaWebhooks\Tests\Models;

use Dniccum\NovaWebhooks\Tests\Database\Factories\PageViewFactory;
use Dniccum\NovaWebhooks\Traits\AllWebhooks;
use Dniccum\NovaWebhooks\Traits\ShouldQueueWebhook;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PageView extends \Illuminate\Database\Eloquent\Model
{
    use HasFactory;
    use AllWebhooks;
    use ShouldQueueWebhook;

    protected $fillable = [
        'name',
        'number_of_views',
    ];

    public static function boot()
    {
        parent::boot();
    }

    protected static function newFactory()
    {
        return PageViewFactory::new();
    }
}
