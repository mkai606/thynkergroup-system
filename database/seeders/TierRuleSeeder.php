<?php

namespace Database\Seeders;

use App\Models\TierRule;
use Illuminate\Database\Seeder;

class TierRuleSeeder extends Seeder
{
    public function run(): void
    {
        TierRule::create([
            'tier_a_min_followers' => 10000,
            'tier_b_min_followers' => 5000,
            'tier_c_min_followers' => 3000,
            'tier_d_min_followers' => 2000,
            'tier_e_min_followers' => 0,
            'auto_promotion' => true,
            'auto_downgrade' => false,
        ]);
    }
}
