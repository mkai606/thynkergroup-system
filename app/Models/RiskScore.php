<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiskScore extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'risk_score',
        'duplicate_ip_score',
        'fake_follower_score',
        'behavior_score',
    ];

    protected $casts = [
        'risk_score' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
