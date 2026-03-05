<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class SidekickController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'agent');

        // Tier filter
        if ($request->filled('tier') && $request->tier !== 'All') {
            $query->where('tier', $request->tier);
        }

        // Level filter
        if ($request->filled('level') && $request->level !== 'all') {
            $query->where('sidekick_level', $request->level);
        }

        // Platform filter
        if ($request->filled('platform') && $request->platform !== 'all') {
            $query->where('platform_primary', $request->platform);
        }

        // Flagged filter
        if ($request->input('flagged') === '1') {
            $query->where('flagged', true);
        }

        // Search (used by Intel threat monitor links)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('handle', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sort = $request->input('sort', 'total_exp');
        $sidekicks = match ($sort) {
            'success_rate' => $query->orderByDesc('success_rate')->get(),
            'tier' => $query->orderByRaw("FIELD(tier, 'A', 'B', 'C', 'D', 'E')")->get(),
            default => $query->orderByDesc('total_exp')->get(),
        };

        return view('admin.sidekicks.index', compact('sidekicks'));
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
