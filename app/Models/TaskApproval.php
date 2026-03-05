<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TaskApproval extends Model
{
    use HasFactory;

    protected $fillable = [
        'submission_id',
        'status',
        'auto_verified',
        'detected_handle',
        'fraud_risk',
        'exp_awarded',
        'reviewed_at',
        'reviewer_id',
    ];

    protected $casts = [
        'auto_verified' => 'boolean',
        'reviewed_at' => 'datetime',
    ];

    public function submission(): BelongsTo
    {
        return $this->belongsTo(TaskSubmission::class, 'submission_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function payoutRequest(): HasOne
    {
        return $this->hasOne(PayoutRequest::class, 'approval_id');
    }
}
