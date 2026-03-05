<?php

namespace Database\Seeders;

use App\Models\Broadcast;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Seeder;

class BroadcastSeeder extends Seeder
{
    public function run(): void
    {
        $broadcasts = [
            [
                'sender_type' => 'Sidekick Mentor',
                'sender' => 'SIDEKICK MENTOR',
                'audience' => 'all',
                'message' => '🔥 Keep pushing! You\'re one task away from the leaderboard.',
                'created_at' => now()->subMinutes(2),
            ],
            [
                'sender_type' => 'System Admin',
                'sender' => 'SYSTEM ADMIN',
                'audience' => 'all',
                'message' => '⚠️ Maintenance scheduled for 3AM. Plan your uploads accordingly.',
                'created_at' => now()->subMinutes(10),
            ],
            [
                'sender_type' => 'Admin HQ',
                'sender' => 'ADMIN HQ',
                'audience' => 'vip',
                'message' => '📢 ALERT: New flash campaign available! Limited slots. Check your feed now!',
                'created_at' => now()->subMinutes(30),
            ],
            [
                'sender_type' => 'Sidekick Mentor',
                'sender' => 'SIDEKICK MENTOR',
                'audience' => 'premium',
                'message' => '🚀 Success is not final, failure is not fatal: it is the courage to continue that counts. Keep grinding!',
                'created_at' => now()->subHours(1),
            ],
            [
                'sender_type' => 'Community Mgr',
                'sender' => 'COMMUNITY MGR',
                'audience' => 'all',
                'message' => '☕ I told my computer I needed a break, and now it won\'t stop sending me Kit-Kat ads.',
                'created_at' => now()->subHours(2),
            ],
            [
                'sender_type' => 'System Admin',
                'sender' => 'SYSTEM ADMIN',
                'audience' => 'all',
                'message' => '⚡ Double EXP Weekend starts this Friday! Prepare your content schedule.',
                'created_at' => now()->subHours(5),
            ],
        ];

        $agents = User::where('role', 'agent')->where('status', 'active')->get();

        foreach ($broadcasts as $data) {
            $broadcast = Broadcast::create($data);

            // Fan out notifications to agents
            foreach ($agents as $user) {
                Notification::create([
                    'user_id' => $user->id,
                    'broadcast_id' => $broadcast->id,
                    'type' => 'broadcast',
                    'message' => $broadcast->message,
                ]);
            }
        }
    }
}
