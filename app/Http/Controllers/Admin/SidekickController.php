<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SidekickController extends Controller
{
    public function index(Request $request)
    {
        $sidekicks = $this->filteredQuery($request);

        return view('admin.sidekicks.index', compact('sidekicks'));
    }

    public function export(Request $request): StreamedResponse
    {
        $sidekicks = $this->filteredQuery($request);

        $headers = ['ID', 'Name', 'Email', 'Phone', 'Handle', 'Platform', 'Tier', 'Level', 'Followers', 'Success Rate', 'Total EXP', 'Monthly EXP', 'Rating', 'Completed Tasks', 'Verified', 'Flagged', 'VIP Status', 'Referral Code', 'Referral Count', 'Join Date'];

        return new StreamedResponse(function () use ($sidekicks, $headers) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $headers);

            foreach ($sidekicks as $sk) {
                fputcsv($handle, [
                    $sk->id,
                    $sk->name,
                    $sk->email,
                    $sk->phone,
                    $sk->handle,
                    $sk->platform_primary,
                    $sk->tier,
                    $sk->sidekick_level,
                    $sk->follower_count,
                    $sk->success_rate,
                    $sk->total_exp,
                    $sk->monthly_exp,
                    $sk->rating,
                    $sk->completed_tasks,
                    $sk->verified_badge ? 'Yes' : 'No',
                    $sk->flagged ? 'Yes' : 'No',
                    $sk->vip_status,
                    $sk->referral_code,
                    $sk->referral_count,
                    $sk->join_date,
                ]);
            }

            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="sidekicks_' . date('Y-m-d') . '.csv"',
        ]);
    }

    private function filteredQuery(Request $request)
    {
        $query = User::where('role', 'agent');

        if ($request->filled('tier') && $request->tier !== 'All') {
            $query->where('tier', $request->tier);
        }

        if ($request->filled('level') && $request->level !== 'all') {
            $query->where('sidekick_level', $request->level);
        }

        if ($request->filled('platform') && $request->platform !== 'all') {
            $query->where('platform_primary', $request->platform);
        }

        if ($request->input('flagged') === '1') {
            $query->where('flagged', true);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('handle', 'like', "%{$search}%");
            });
        }

        $sort = $request->input('sort', 'total_exp');
        return match ($sort) {
            'success_rate' => $query->orderByDesc('success_rate')->get(),
            'tier' => $query->orderByRaw("FIELD(tier, 'A', 'B', 'C', 'D', 'E')")->get(),
            default => $query->orderByDesc('total_exp')->get(),
        };
    }

    public function show(User $user)
    {
        $user->load(['wallet', 'vipMemberships', 'taskApplications']);

        $completedTasks = $user->taskApplications->where('status', 'approved')->count();
        $activeTasks = $user->taskApplications->whereIn('status', ['accepted', 'submitted'])->count();
        $walletBalance = $user->wallet?->balance ?? 0;
        $vip = $user->vipMemberships->where('status', 'active')->first();

        return view('admin.sidekicks.show', compact('user', 'completedTasks', 'activeTasks', 'walletBalance', 'vip'));
    }

    public function flag(Request $request, User $user)
    {
        $request->validate([
            'flagged_reason' => 'required|string|max:500',
        ]);

        $user->update([
            'flagged' => true,
            'flagged_reason' => $request->flagged_reason,
        ]);

        return back()->with('success', "{$user->name} has been flagged.");
    }

    public function unflag(User $user)
    {
        $user->update([
            'flagged' => false,
            'flagged_reason' => null,
        ]);

        return back()->with('success', "Flag removed from {$user->name}.");
    }
}
