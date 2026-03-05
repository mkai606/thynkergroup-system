<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\FraudEvent;
use App\Models\TaskApplication;
use App\Models\TaskApproval;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class IntelController extends Controller
{
    public function index()
    {
        // Engagement data — task applications per day (this week vs last week)
        $thisWeekStart = now()->startOfWeek();
        $lastWeekStart = now()->subWeek()->startOfWeek();
        $lastWeekEnd = now()->subWeek()->endOfWeek();

        $thisWeekApps = TaskApplication::whereNotNull('applied_at')
            ->where('applied_at', '>=', $thisWeekStart)
            ->count();

        $lastWeekApps = TaskApplication::whereNotNull('applied_at')
            ->whereBetween('applied_at', [$lastWeekStart, $lastWeekEnd])
            ->count();

        $weekChange = $lastWeekApps > 0
            ? round((($thisWeekApps - $lastWeekApps) / $lastWeekApps) * 100)
            : ($thisWeekApps > 0 ? 100 : 0);

        $weeklyApps = TaskApplication::select(
            DB::raw('DAYNAME(applied_at) as day'),
            DB::raw('COUNT(*) as count')
        )
            ->whereNotNull('applied_at')
            ->groupBy('day')
            ->pluck('count', 'day')
            ->toArray();

        $engagementData = [];
        foreach (['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] as $day) {
            $fullDay = match ($day) {
                'Mon' => 'Monday', 'Tue' => 'Tuesday', 'Wed' => 'Wednesday',
                'Thu' => 'Thursday', 'Fri' => 'Friday', 'Sat' => 'Saturday', 'Sun' => 'Sunday',
            };
            $engagementData[] = [
                'day' => $day,
                'count' => $weeklyApps[$fullDay] ?? 0,
            ];
        }

        // System alerts
        $alerts = collect();

        $pendingApprovals = TaskApproval::where('status', 'pending')->count();
        if ($pendingApprovals > 3) {
            $alerts->push([
                'type' => 'warning',
                'message' => "High verification load ({$pendingApprovals} pending)",
                'time' => 'Now',
            ]);
        }

        $fraudEvents = FraudEvent::with('user')->latest()->limit(5)->get();
        foreach ($fraudEvents as $event) {
            $alerts->push([
                'type' => 'error',
                'message' => "Potential Fraud: {$event->type} detected for " . ($event->user?->name ?? 'Unknown') . ". {$event->details}",
                'time' => $event->created_at->diffForHumans(),
            ]);
        }

        $nearBudgetCampaigns = Campaign::where('status', 'active')
            ->whereRaw('spent_budget >= total_budget * 0.8')
            ->get();

        foreach ($nearBudgetCampaigns as $campaign) {
            $pct = $campaign->total_budget > 0 ? round(($campaign->spent_budget / $campaign->total_budget) * 100) : 0;
            $alerts->push([
                'type' => 'info',
                'message' => "Campaign \"{$campaign->title}\" reached {$pct}% budget",
                'time' => $campaign->updated_at->diffForHumans(),
            ]);
        }

        $flaggedUsers = User::where('flagged', true)->get();
        foreach ($flaggedUsers as $flagged) {
            $alerts->push([
                'type' => 'warning',
                'message' => "Flagged user: {$flagged->name} ({$flagged->handle})" . ($flagged->flagged_reason ? " — {$flagged->flagged_reason}" : ''),
                'time' => 'Now',
                'link' => route('admin.sidekicks', ['search' => $flagged->name]),
            ]);
        }

        return view('admin.intel.index', compact('engagementData', 'weekChange', 'alerts'));
    }
}
