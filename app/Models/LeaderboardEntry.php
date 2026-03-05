<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaderboardEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'snapshot_id',
        'user_id',
        'exp',
        'rank',
        'vip',
    ];

    protected $casts = [
        'vip' => 'boolean',
    ];

    public function snapshot(): BelongsTo
    {
        return $this->belongsTo(LeaderboardSnapshot::class, 'snapshot_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
