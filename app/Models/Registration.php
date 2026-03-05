<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Registration extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'full_name',
        'phone',
        'email',
        'referral_code_used',
        'status',
        'verification_status',
        'submitted_at',
        'notes',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'submitted_at' => 'datetime',
        ];
    }

    // ──────────────────────────────────────────────
    // Relationships
    // ──────────────────────────────────────────────

    public function socialProfiles(): HasMany
    {
        return $this->hasMany(RegistrationSocialProfile::class);
    }

    public function highlightPosts(): HasMany
    {
        return $this->hasMany(RegistrationHighlightPost::class);
    }

    public function evidence(): HasMany
    {
        return $this->hasMany(RegistrationEvidence::class);
    }
}
