<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_id',
        'title',
        'platform',
        'type',
        'description',
        'access_level',
        'min_followers',
        'exp_reward',
        'reward_amount',
        'slots_total',
        'slots_taken',
        'deadline',
        'instructions_locked',
        'hidden_details',
        'status',
    ];

    protected $casts = [
        'reward_amount' => 'decimal:2',
        'deadline' => 'date',
        'instructions_locked' => 'boolean',
    ];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function instructions(): HasMany
    {
        return $this->hasMany(TaskInstruction::class);
    }

    public function hashtags(): HasMany
    {
        return $this->hasMany(TaskHashtag::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(TaskApplication::class);
    }

    public function aiAssignments(): HasMany
    {
        return $this->hasMany(AiAssignment::class);
    }
}
