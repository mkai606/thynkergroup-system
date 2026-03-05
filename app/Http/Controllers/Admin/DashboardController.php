<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\FraudEvent;
use App\Models\Registration;
use App\Models\Task;
use App\Models\TaskApproval;
use App\Models\TaskApplication;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        // Stats
        $activeSidekicks = User::where('role', 'agent')->count();
        $pendingRecruitment = Registration::where('status', 'pending')->count();
        $pendingReview = TaskApproval::where('status', 'pending')->count();
        $monthlyLiability = Campaign::where('status', 'active')->sum('total_budget');
        $activeCampaigns = Campaign::where('status', 'active')->count();
        $vipMembers = User::where('sidekick_level', 'vip')->count();

        // Engagement data (last 7 days)
        $engagementData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $engagementData[] = [
                'day' => $date->format('D'),
                'submissions' => TaskApproval::whereDate('created_at', $date)->count(),
                'applications' => TaskApplication::whereDate('created_at', $date)->count(),
            ];
        }

        // Threat monitor — recent fraud events + system alerts
        $threats = FraudEvent::latest()->limit(5)->get();

        // Recent activity
        $recentApprovals = TaskApproval::with('submission.application.user', 'submission.application.task')
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'activeSidekicks',
            'pendingRecruitment',
            'pendingReview',
            'monthlyLiability',
            'activeCampaigns',
            'vipMembers',
            'engagementData',
            'threats',
            'recentApprovals',
        ));
    }
}
