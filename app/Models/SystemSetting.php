<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SystemSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'tier_config',
        'vip_config',
        'campaign_config',
        'reward_config',
        'fraud_config',
        'payment_config',
        'ai_engine_config',
    ];

    protected $casts = [
        'tier_config' => 'array',
        'vip_config' => 'array',
        'campaign_config' => 'array',
        'reward_config' => 'array',
        'fraud_config' => 'array',
        'payment_config' => 'array',
        'ai_engine_config' => 'array',
    ];

    public function auditLogs(): HasMany
    {
        return $this->hasMany(SystemSettingsAuditLog::class, 'settings_id');
    }
}
