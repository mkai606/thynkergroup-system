<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TierRule extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'tier_a_min_followers',
        'tier_b_min_followers',
        'tier_c_min_followers',
        'tier_d_min_followers',
        'tier_e_min_followers',
        'auto_promotion',
        'auto_downgrade',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'auto_promotion' => 'boolean',
            'auto_downgrade' => 'boolean',
        ];
    }
}
