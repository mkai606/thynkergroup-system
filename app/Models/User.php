<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'handle',
        'phone',
        'avatar_url',
        'tng_qr_url',
        'role',
        'status',
        'tier',
        'follower_count',
        'rating',
        'success_rate',
        'platform_primary',
        'sidekick_level',
        'vip_status',
        'join_date',
        'total_exp',
        'monthly_exp',
        'rank_position',
        'verified_badge',
        'flagged',
        'flagged_reason',
        'referral_code',
        'referral_count',
        'completed_tasks',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'join_date' => 'datetime',
            'verified_badge' => 'boolean',
            'flagged' => 'boolean',
            'rating' => 'decimal:2',
            'success_rate' => 'decimal:2',
        ];
    }

    // ──────────────────────────────────────────────
    // Relationships
    // ──────────────────────────────────────────────

    public function socialProfiles(): HasMany
    {
        return $this->hasMany(UserSocialProfile::class);
    }

    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class);
    }

    public function expEvents(): HasMany
    {
        return $this->hasMany(ExpEvent::class);
    }

    public function leaderboardEntries(): HasMany
    {
        return $this->hasMany(LeaderboardEntry::class);
    }

    public function vipMemberships(): HasMany
    {
        return $this->hasMany(VipMembership::class);
    }

    public function referralsMade(): HasMany
    {
        return $this->hasMany(Referral::class, 'referrer_user_id');
    }

    public function referralsReceived(): HasMany
    {
        return $this->hasMany(Referral::class, 'referred_user_id');
    }

    public function taskApplications(): HasMany
    {
        return $this->hasMany(TaskApplication::class);
    }

    public function appNotifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function aiAssignments(): HasMany
    {
        return $this->hasMany(AiAssignment::class);
    }

    public function riskScore(): HasOne
    {
        return $this->hasOne(RiskScore::class);
    }

    public function fraudEvents(): HasMany
    {
        return $this->hasMany(FraudEvent::class);
    }

    public function payoutRequests(): HasMany
    {
        return $this->hasMany(PayoutRequest::class);
    }

    // ──────────────────────────────────────────────
    // Helper Methods
    // ──────────────────────────────────────────────

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isVip(): bool
    {
        return $this->sidekick_level === 'vip';
    }
}
