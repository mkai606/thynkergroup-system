<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            TierRuleSeeder::class,
            SystemSettingsSeeder::class,
            UserSeeder::class,
            CampaignSeeder::class,
            RegistrationSeeder::class,
            BroadcastSeeder::class,
        ]);
    }
}
