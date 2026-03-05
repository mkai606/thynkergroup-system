<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TaskSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'submission_type',
        'proof_url',
        'submitted_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(TaskApplication::class, 'application_id');
    }

    public function approvals(): HasMany
    {
        return $this->hasMany(TaskApproval::class, 'submission_id');
    }

    public function fraudEvents(): HasMany
    {
        return $this->hasMany(FraudEvent::class, 'submission_id');
    }
}
