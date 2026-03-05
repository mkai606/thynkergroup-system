<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\VipMembership;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@sidekickhq.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'handle' => '@admin_hq',
            'status' => 'active',
            'tier' => 'A',
            'sidekick_level' => 'vip',
            'vip_status' => 'active',
            'join_date' => now(),
            'verified_badge' => true,
            'follower_count' => 0,
            'referral_code' => 'ADMIN001',
            'email_verified_at' => now(),
        ]);

        // Create wallet for admin
        Wallet::create([
            'user_id' => $admin->id,
            'balance' => 0,
            'currency' => 'MYR',
        ]);

        // Sample agents
        $agents = [
            ['name' => 'Sarah Ahmad', 'email' => 'sarah@test.com', 'handle' => '@sarah_creates', 'tier' => 'A', 'follower_count' => 15200, 'total_exp' => 2450, 'monthly_exp' => 680, 'rank_position' => 1, 'success_rate' => 94.5, 'verified_badge' => true, 'sidekick_level' => 'vip', 'vip_status' => 'active', 'completed_tasks' => 12, 'referral_count' => 8],
            ['name' => 'Ahmad Rizal', 'email' => 'ahmad@test.com', 'handle' => '@rizal_vibes', 'tier' => 'B', 'follower_count' => 8700, 'total_exp' => 1890, 'monthly_exp' => 520, 'rank_position' => 2, 'success_rate' => 88.2, 'verified_badge' => true, 'sidekick_level' => 'vip', 'vip_status' => 'active', 'completed_tasks' => 9, 'referral_count' => 6],
            ['name' => 'Nurul Aisyah', 'email' => 'nurul@test.com', 'handle' => '@nurul.lifestyle', 'tier' => 'B', 'follower_count' => 6300, 'total_exp' => 1200, 'monthly_exp' => 340, 'rank_position' => 3, 'success_rate' => 91.0, 'verified_badge' => false, 'sidekick_level' => 'premium', 'vip_status' => 'requirements_met', 'completed_tasks' => 7, 'referral_count' => 5],
            ['name' => 'Danial Hakim', 'email' => 'danial@test.com', 'handle' => '@danial.tech', 'tier' => 'C', 'follower_count' => 3500, 'total_exp' => 780, 'monthly_exp' => 210, 'rank_position' => 4, 'success_rate' => 85.0, 'verified_badge' => false, 'sidekick_level' => 'premium', 'vip_status' => 'none', 'completed_tasks' => 4, 'referral_count' => 2],
            ['name' => 'Farah Nadia', 'email' => 'farah@test.com', 'handle' => '@farah.foodie', 'tier' => 'D', 'follower_count' => 2100, 'total_exp' => 320, 'monthly_exp' => 90, 'rank_position' => 5, 'success_rate' => 78.5, 'verified_badge' => false, 'sidekick_level' => 'premium', 'vip_status' => 'none', 'completed_tasks' => 2, 'referral_count' => 1],
            ['name' => 'Zulkifli Omar', 'email' => 'zul@test.com', 'handle' => '@zul_gaming', 'tier' => 'E', 'follower_count' => 980, 'total_exp' => 120, 'monthly_exp' => 45, 'rank_position' => 6, 'success_rate' => 72.0, 'verified_badge' => false, 'sidekick_level' => 'premium', 'vip_status' => 'none', 'completed_tasks' => 1, 'referral_count' => 0, 'flagged' => true, 'flagged_reason' => 'Suspected fake followers and duplicate IP with another account'],
        ];

        foreach ($agents as $data) {
            // Use fixed referral code for Sarah so seeder referral works
            $refCode = $data['email'] === 'sarah@test.com' ? 'SARAH88' : strtoupper(Str::random(8));

            $user = User::create(array_merge($data, [
                'password' => Hash::make('password'),
                'role' => 'agent',
                'status' => 'active',
                'platform_primary' => 'instagram',
                'join_date' => now()->subDays(rand(30, 180)),
                'referral_code' => $refCode,
                'email_verified_at' => now(),
            ]));

            // Create wallet for each agent with sample transactions
            $walletBalance = rand(50, 500) + (rand(0, 99) / 100);
            $wallet = Wallet::create([
                'user_id' => $user->id,
                'balance' => $walletBalance,
                'currency' => 'MYR',
            ]);

            // Sample wallet transactions
            $sampleTxns = [
                ['type' => 'credit', 'reason' => 'Campaign payout — TikTok Raya Promo', 'amount' => rand(20, 80)],
                ['type' => 'credit', 'reason' => 'Campaign payout — Shopee 12.12 Review', 'amount' => rand(15, 60)],
                ['type' => 'credit', 'reason' => 'Referral bonus', 'amount' => 5.00],
            ];

            if ($data['vip_status'] === 'active') {
                $sampleTxns[] = ['type' => 'debit', 'reason' => 'VIP membership fee', 'amount' => 15.00];
            }

            if ($data['completed_tasks'] >= 4) {
                $sampleTxns[] = ['type' => 'credit', 'reason' => 'Campaign payout — Grab Food Challenge', 'amount' => rand(30, 100)];
                $sampleTxns[] = ['type' => 'credit', 'reason' => 'Bonus reward — Top performer', 'amount' => rand(10, 25)];
            }

            foreach ($sampleTxns as $i => $txn) {
                WalletTransaction::create([
                    'wallet_id' => $wallet->id,
                    'type' => $txn['type'],
                    'reason' => $txn['reason'],
                    'amount' => $txn['amount'],
                    'created_at' => now()->subDays(rand(1, 60))->subHours(rand(0, 23)),
                    'updated_at' => now()->subDays(rand(1, 60)),
                ]);
            }

            // Create VIP memberships for eligible agents
            if ($data['vip_status'] === 'active') {
                VipMembership::create([
                    'user_id' => $user->id,
                    'status' => 'active',
                    'approved_at' => now()->subMonths(rand(1, 6)),
                    'expires_at' => now()->addMonths(rand(3, 12)),
                    'receipt_url' => 'receipt_' . strtolower(str_replace(' ', '_', $data['name'])) . '.jpg',
                    'fee_amount' => 15.00,
                ]);
            } elseif ($data['vip_status'] === 'requirements_met') {
                // Nurul — eligible, payment submitted, pending review
                VipMembership::create([
                    'user_id' => $user->id,
                    'status' => 'payment_submitted',
                    'receipt_url' => 'receipt_nurul_tng.jpg',
                    'fee_amount' => 15.00,
                ]);
            } elseif ($data['completed_tasks'] >= 4 && $data['name'] === 'Danial Hakim') {
                // Danial — close to eligible, no payment yet
                VipMembership::create([
                    'user_id' => $user->id,
                    'status' => 'eligible',
                    'fee_amount' => 15.00,
                ]);
            }
        }
    }
}
