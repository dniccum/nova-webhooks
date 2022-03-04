<?php

namespace Dniccum\NovaWebhooks\Models;

use Dniccum\NovaWebhooks\Database\Factories\WebhookLogFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebhookLog extends Model
{
    use HasFactory;

    public $guarded = [];

    public $timestamps = false;

    public $table = 'webhook_logs';

    protected $casts = [
        'successful' => 'boolean',
    ];

    protected $attributes = [
        'successful' => true,
    ];

    /**
     * @inheritDoc
     */
    protected static function booted() : void
    {
        parent::boot();

        static::creating(function(WebhookLog $model) {
            $model->created_at = now();
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function webhook()
    {
        return $this->belongsTo(Webhook::class, 'webhook_id', 'id');
    }

    protected static function newFactory()
    {
        return WebhookLogFactory::new();
    }
}
