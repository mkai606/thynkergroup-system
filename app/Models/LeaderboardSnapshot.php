<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LeaderboardSnapshot extends Model
{
    use HasFactory;

    protected $fillable = [
        'period',
        'period_start',
        'period_end',
        'generated_at',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'generated_at' => 'datetime',
    ];

    public function entries(): HasMany
    {
        return $this->hasMany(LeaderboardEntry::class, 'snapshot_id');
    }
}
