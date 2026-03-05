<?php

namespace Database\Seeders;

use App\Models\Campaign;
use App\Models\Task;
use App\Models\TaskApplication;
use App\Models\TaskApproval;
use App\Models\TaskInstruction;
use App\Models\TaskHashtag;
use App\Models\TaskSubmission;
use App\Models\User;
use Illuminate\Database\Seeder;

class CampaignSeeder extends Seeder
{
    public function run(): void
    {
        $campaigns = [
            [
                'title' => 'Raya Fashion 2026',
                'brand' => 'FashionValet',
                'status' => 'active',
                'total_budget' => 5000.00,
                'spent_budget' => 1250.00,
                'start_at' => now()->subDays(7),
                'end_at' => now()->addDays(23),
                'tasks' => [
                    [
                        'title' => 'Raya OOTD Post',
                        'platform' => 'Instagram',
                        'type' => 'post',
                        'description' => 'Create an Instagram post showcasing your Raya outfit from FashionValet. Must include brand tag and hashtags.',
                        'access_level' => 'public',
                        'min_followers' => 2000,
                        'exp_reward' => 50,
                        'reward_amount' => 45.00,
                        'slots_total' => 20,
                        'slots_taken' => 5,
                        'deadline' => now()->addDays(14)->toDateString(),
                        'status' => 'open',
                        'instructions' => [
                            'Purchase or style a FashionValet Raya outfit',
                            'Take a high-quality photo in the outfit',
                            'Post to Instagram with required tags',
                            'Keep post live for minimum 48 hours',
                        ],
                        'hashtags' => ['#FashionValetRaya', '#RayaOOTD', '#SidekickMission'],
                    ],
                ],
            ],
            [
                'title' => 'TikTok Energy Challenge',
                'brand' => 'VoltEnergy',
                'status' => 'active',
                'total_budget' => 3000.00,
                'spent_budget' => 600.00,
                'start_at' => now()->subDays(3),
                'end_at' => now()->addDays(27),
                'tasks' => [
                    [
                        'title' => 'Energy Boost Dance',
                        'platform' => 'TikTok',
                        'type' => 'video',
                        'description' => 'Create a TikTok video with the VoltEnergy product. Show your energy transformation!',
                        'access_level' => 'public',
                        'min_followers' => 1000,
                        'exp_reward' => 35,
                        'reward_amount' => 30.00,
                        'slots_total' => 30,
                        'slots_taken' => 8,
                        'deadline' => now()->addDays(20)->toDateString(),
                        'status' => 'open',
                        'instructions' => [
                            'Film a before/after energy transformation',
                            'Feature VoltEnergy product visibly',
                            'Use the trending sound provided',
                            'Tag @VoltEnergy and use hashtags',
                        ],
                        'hashtags' => ['#VoltEnergy', '#EnergyBoost', '#SidekickChallenge'],
                    ],
                ],
            ],
            [
                'title' => 'Premium Beauty Campaign',
                'brand' => 'GlowLab',
                'status' => 'active',
                'total_budget' => 8000.00,
                'spent_budget' => 0.00,
                'start_at' => now(),
                'end_at' => now()->addDays(30),
                'tasks' => [
                    [
                        'title' => 'Skincare Routine Reel',
                        'platform' => 'Instagram',
                        'type' => 'reel',
                        'description' => 'Create an Instagram Reel showing your morning skincare routine featuring GlowLab products.',
                        'access_level' => 'vip_only',
                        'min_followers' => 5000,
                        'exp_reward' => 80,
                        'reward_amount' => 75.00,
                        'slots_total' => 10,
                        'slots_taken' => 0,
                        'deadline' => now()->addDays(25)->toDateString(),
                        'status' => 'open',
                        'instructions' => [
                            'Film morning skincare routine with GlowLab products',
                            'Show product details clearly',
                            'Add honest review in caption',
                            'Must get minimum 500 views within 48hrs',
                        ],
                        'hashtags' => ['#GlowLab', '#SkincareRoutine', '#VIPMission'],
                    ],
                ],
            ],
        ];

        foreach ($campaigns as $campaignData) {
            $tasks = $campaignData['tasks'];
            unset($campaignData['tasks']);

            $campaign = Campaign::create($campaignData);

            foreach ($tasks as $taskData) {
                $instructions = $taskData['instructions'];
                $hashtags = $taskData['hashtags'];
                unset($taskData['instructions'], $taskData['hashtags']);

                $task = $campaign->tasks()->create($taskData);

                foreach ($instructions as $i => $instruction) {
                    $task->instructions()->create([
                        'step_no' => $i + 1,
                        'instruction' => $instruction,
                    ]);
                }

                foreach ($hashtags as $hashtag) {
                    $task->hashtags()->create(['hashtag' => $hashtag]);
                }
            }
        }

        // Seed sample task applications
        $agents = User::where('role', 'agent')->get();
        $tasks = Task::all();

        if ($agents->isNotEmpty() && $tasks->isNotEmpty()) {
            // Raya Fashion task — 5 applications (mix of statuses)
            $rayaTask = $tasks->where('title', 'Raya OOTD Post')->first();
            if ($rayaTask) {
                foreach ($agents->take(5) as $i => $agent) {
                    $status = match ($i) {
                        0, 1 => 'accepted',
                        2 => 'rejected',
                        default => 'applied',
                    };
                    TaskApplication::create([
                        'task_id' => $rayaTask->id,
                        'user_id' => $agent->id,
                        'status' => $status,
                        'applied_at' => now()->subDays(rand(1, 7)),
                        'accepted_at' => $status === 'accepted' ? now()->subDays(rand(0, 2)) : null,
                    ]);
                }
            }

            // TikTok Energy task — 4 applications
            $tiktokTask = $tasks->where('title', 'Energy Boost Dance')->first();
            if ($tiktokTask) {
                foreach ($agents->take(4) as $i => $agent) {
                    $status = match ($i) {
                        0 => 'accepted',
                        default => 'applied',
                    };
                    TaskApplication::create([
                        'task_id' => $tiktokTask->id,
                        'user_id' => $agent->id,
                        'status' => $status,
                        'applied_at' => now()->subDays(rand(1, 3)),
                        'accepted_at' => $status === 'accepted' ? now()->subDay() : null,
                    ]);
                }
            }

            // Premium Beauty task — 2 VIP applications
            $beautyTask = $tasks->where('title', 'Skincare Routine Reel')->first();
            if ($beautyTask) {
                $vipAgents = $agents->where('sidekick_level', 'vip')->take(2);
                foreach ($vipAgents as $agent) {
                    TaskApplication::create([
                        'task_id' => $beautyTask->id,
                        'user_id' => $agent->id,
                        'status' => 'applied',
                        'applied_at' => now()->subDay(),
                    ]);
                }
            }

            // Seed sample submissions + pending approvals for accepted applications
            $acceptedApps = TaskApplication::where('status', 'accepted')->with('task')->get();

            $proofUrls = [
                'https://instagram.com/p/CxExample1',
                'https://instagram.com/p/CxExample2',
                'https://tiktok.com/@agent/video/12345',
            ];

            foreach ($acceptedApps as $i => $app) {
                $submission = TaskSubmission::create([
                    'application_id' => $app->id,
                    'submission_type' => 'link',
                    'proof_url' => $proofUrls[$i % count($proofUrls)],
                    'submitted_at' => now()->subHours(rand(1, 24)),
                ]);

                TaskApproval::create([
                    'submission_id' => $submission->id,
                    'status' => 'pending',
                    'auto_verified' => false,
                    'detected_handle' => null,
                    'fraud_risk' => null,
                    'exp_awarded' => $app->task->exp_reward,
                ]);
            }
        }
    }
}
