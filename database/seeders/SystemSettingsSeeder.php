<?php

namespace Database\Seeders;

use App\Models\SystemSetting;
use Illuminate\Database\Seeder;

class SystemSettingsSeeder extends Seeder
{
    public function run(): void
    {
        SystemSetting::create([
            'tier_config' => [
                'tierA_minFollowers' => 10000,
                'tierB_minFollowers' => 5000,
                'tierC_minFollowers' => 3000,
                'tierD_minFollowers' => 2000,
                'tierE_minFollowers' => 0,
                'autoPromotion' => true,
                'autoDowngrade' => false,
            ],
            'vip_config' => [
                'vipFee' => 15.00,
                'minCampaigns' => 5,
                'minReferrals' => 5,
                'vipDuration' => 365,
                'autoRenewal' => true,
            ],
            'campaign_config' => [
                'maxSlots' => 50,
                'defaultPlatform' => 'Instagram',
                'allowMultiApply' => false,
                'autoCloseExpired' => true,
            ],
            'reward_config' => [
                'baseExpMultiplier' => 1.0,
                'weekendBonus' => 1.5,
                'vipExpBonus' => 2.0,
                'minPayoutAmount' => 10.00,
                'payoutDelay' => 3,
            ],
            'fraud_config' => [
                'duplicateIpThreshold' => 3,
                'fakeFollowerThreshold' => 30,
                'autoFlagScore' => 70,
                'enableAutoVerification' => true,
            ],
            'payment_config' => [
                'enableTNG' => true,
                'enableDuitNow' => true,
                'enableBank' => true,
                'defaultProvider' => 'TNG',
                'paymentDelayDays' => 3,
            ],
            'ai_engine_config' => [
                'performanceWeight' => 40,
                'roiWeight' => 30,
                'riskWeight' => 30,
                'autoAssignThreshold' => 80,
                'enableSuggestions' => true,
            ],
        ]);
    }
}
