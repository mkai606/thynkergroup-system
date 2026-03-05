<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SystemSettingsAuditLog extends Model
{
    use HasFactory;

    protected $table = 'system_settings_audit_log';

    protected $fillable = [
        'settings_id',
        'changed_by_user_id',
        'diff',
    ];

    protected $casts = [
        'diff' => 'array',
    ];

    public function setting(): BelongsTo
    {
        return $this->belongsTo(SystemSetting::class, 'settings_id');
    }

    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by_user_id');
    }
}
