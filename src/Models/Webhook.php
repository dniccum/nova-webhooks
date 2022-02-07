<?php

namespace Dniccum\NovaWebhooks\Models;

use Dniccum\NovaWebhooks\Database\Factories\WebhookFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Webhook extends Model
{
    use HasFactory;

    public $guarded = [];

    protected $casts = [
        'settings' => 'array',
    ];

    /**
     * @inheritDoc
     */
    protected static function booted() : void
    {
        parent::boot();

        static::creating(function(\Dniccum\NovaWebhooks\Models\Webhook $webhook) {
            $webhook->modified_by = optional(\Auth::user())->primaryKey;
            if (!$webhook->secret) {
                $webhook->secret = self::newSecret();
            }
        });
        static::updating(function(\Dniccum\NovaWebhooks\Models\Webhook $webhook) {
            $webhook->modified_by = optional(\Auth::user())->primaryKey;
        });
    }

    /**
     * @return string
     */
    public static function newSecret() : string
    {
        return md5(uniqid(rand(), true));
    }

    protected static function newFactory()
    {
        return WebhookFactory::new();
    }
}
