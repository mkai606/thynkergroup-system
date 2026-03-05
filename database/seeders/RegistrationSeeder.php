<?php

namespace Database\Seeders;

use App\Models\Registration;
use Illuminate\Database\Seeder;

class RegistrationSeeder extends Seeder
{
    public function run(): void
    {
        $registrations = [
            [
                'full_name' => 'Aiman Hafiz',
                'phone' => '60123456789',
                'email' => 'aiman@gmail.com',
                'status' => 'pending',
                'verification_status' => 'unverified',
                'submitted_at' => now()->subDays(2),
                'social_profiles' => [
                    ['platform' => 'instagram', 'handle' => '@aiman.hz', 'followers' => 4500, 'profile_url' => 'https://instagram.com/aiman.hz'],
                    ['platform' => 'tiktok', 'handle' => '@aiman_hz', 'followers' => 2200, 'profile_url' => 'https://tiktok.com/@aiman_hz'],
                ],
                'highlight_posts' => [
                    ['post_url' => 'https://instagram.com/p/highlight_aiman1', 'post_type' => 'image', 'likes' => 1240, 'comments' => 45],
                ],
                'evidence' => [
                    ['evidence_type' => 'screenshot', 'file_url' => 'screenshot_aiman_001.jpg'],
                ],
            ],
            [
                'full_name' => 'Siti Aminah',
                'phone' => '60198765432',
                'email' => 'siti.aminah@gmail.com',
                'status' => 'pending',
                'verification_status' => 'unverified',
                'submitted_at' => now()->subDays(1),
                'social_profiles' => [
                    ['platform' => 'instagram', 'handle' => '@sitiaminah_', 'followers' => 8900, 'profile_url' => 'https://instagram.com/sitiaminah_'],
                ],
                'highlight_posts' => [
                    ['post_url' => 'https://instagram.com/p/highlight_siti1', 'post_type' => 'image', 'likes' => 3200, 'comments' => 89],
                    ['post_url' => 'https://instagram.com/reel/siti_reel1', 'post_type' => 'video', 'likes' => 5600, 'comments' => 120],
                ],
                'evidence' => [
                    ['evidence_type' => 'screenshot', 'file_url' => 'screenshot_siti_001.jpg'],
                    ['evidence_type' => 'analytics', 'file_url' => 'analytics_siti_001.png'],
                ],
            ],
            [
                'full_name' => 'Hafiz Rahman',
                'phone' => '60171234567',
                'email' => 'hafiz.r@gmail.com',
                'referral_code_used' => 'SARAH88',
                'status' => 'pending',
                'verification_status' => 'unverified',
                'submitted_at' => now()->subHours(6),
                'social_profiles' => [
                    ['platform' => 'tiktok', 'handle' => '@hafiz_creates', 'followers' => 12000, 'profile_url' => 'https://tiktok.com/@hafiz_creates'],
                    ['platform' => 'youtube', 'handle' => 'HafizCreates', 'followers' => 3400, 'profile_url' => 'https://youtube.com/@HafizCreates'],
                ],
                'highlight_posts' => [
                    ['post_url' => 'https://tiktok.com/@hafiz_creates/video/highlight1', 'post_type' => 'video', 'likes' => 15000, 'comments' => 340],
                ],
                'evidence' => [
                    ['evidence_type' => 'screenshot', 'file_url' => 'screenshot_hafiz_001.jpg'],
                ],
            ],
        ];

        foreach ($registrations as $data) {
            $profiles = $data['social_profiles'];
            $highlights = $data['highlight_posts'] ?? [];
            $evidences = $data['evidence'] ?? [];
            unset($data['social_profiles'], $data['highlight_posts'], $data['evidence']);

            $registration = Registration::create($data);

            foreach ($profiles as $profile) {
                $registration->socialProfiles()->create($profile);
            }

            foreach ($highlights as $highlight) {
                $registration->highlightPosts()->create($highlight);
            }

            foreach ($evidences as $evidence) {
                $registration->evidence()->create($evidence);
            }
        }
    }
}
