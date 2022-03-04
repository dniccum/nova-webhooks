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
            $webhook->modified_by = \Auth::id();
            if (!$webhook->secret) {
                $webhook->secret = self::newSecret();
            }
        });
        static::updating(function(\Dniccum\NovaWebhooks\Models\Webhook $webhook) {
            $webhook->modified_by = \Auth::id();
        });
        static::deleting(function(\Dniccum\NovaWebhooks\Models\Webhook $webhook) {
            \DB::table('webhook_logs')
                ->where('webhook_id', $webhook->id)
                ->delete();
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function modifiedBy()
    {
        return $this->belongsTo(config('auth.providers.users.model'), 'modified_by', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function logs()
    {
        return $this->hasMany(WebhookLog::class, 'webhook_id', 'id');
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
