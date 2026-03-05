<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Broadcast;
use App\Models\Notification;
use App\Models\Task;
use App\Models\TaskApplication;
use App\Models\TaskSubmission;
use Illuminate\Http\Request;

class FeedController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $user->load('wallet');

        // Quick stats
        $activeMissions = $user->taskApplications()
            ->whereIn('status', ['accepted', 'submitted', 'applied'])
            ->count();

        $walletBalance = $user->wallet?->balance ?? 0;

        // Notifications
        $notifications = Notification::where('user_id', $user->id)
            ->latest()
            ->limit(20)
            ->get();
        $unreadCount = $notifications->where('is_read', false)->count();

        // Recent broadcasts (sent only)
        $broadcasts = Broadcast::where('status', 'sent')
            ->whereIn('audience', ['all', $user->sidekick_level])
            ->latest()
            ->limit(10)
            ->get();

        // Get IDs of tasks user already applied to
        $appliedTaskIds = $user->taskApplications()->pluck('task_id')->toArray();

        // Platform filter
        $platform = $request->input('platform');

        // Featured op: highest reward open task user hasn't applied to
        $featuredTask = Task::with('campaign')
            ->where('status', 'open')
            ->where('slots_taken', '<', \DB::raw('slots_total'))
            ->whereNotIn('id', $appliedTaskIds)
            ->when($platform, fn ($q) => $q->where('platform', $platform))
            ->where(function ($q) use ($user) {
                $q->where('access_level', 'public')
                  ->orWhere(function ($q2) use ($user) {
                      $q2->where('access_level', 'vip_only')
                          ->where(fn ($q3) => $user->sidekick_level === 'vip' ? $q3 : $q3->whereRaw('1=0'));
                  });
            })
            ->orderByDesc('reward_amount')
            ->first();

        // Available ops: open tasks with slots, excluding featured
        $tasks = Task::with('campaign')
            ->where('status', 'open')
            ->where('slots_taken', '<', \DB::raw('slots_total'))
            ->when($featuredTask, fn ($q) => $q->where('id', '!=', $featuredTask->id))
            ->when($platform, fn ($q) => $q->where('platform', $platform))
            ->latest()
            ->get();

        // Map user's application status per task
        $applicationStatuses = $user->taskApplications()
            ->whereIn('task_id', $tasks->pluck('id')->merge($appliedTaskIds))
            ->pluck('status', 'task_id')
            ->toArray();

        return view('agent.dashboard', compact(
            'user', 'featuredTask', 'tasks', 'applicationStatuses',
            'activeMissions', 'walletBalance', 'unreadCount', 'notifications', 'broadcasts', 'platform'
        ));
    }

    public function markNotificationsRead()
    {
        Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    public function show(Task $task)
    {
        $task->load(['campaign', 'instructions', 'hashtags']);

        $user = auth()->user();
        $application = $user->taskApplications()
            ->where('task_id', $task->id)
            ->with('submissions')
            ->first();

        return view('agent.tasks.show', compact('task', 'user', 'application'));
    }

    public function apply(Task $task)
    {
        $user = auth()->user();

        // Check if already applied
        if ($user->taskApplications()->where('task_id', $task->id)->exists()) {
            return back()->with('error', 'Already applied.');
        }

        // Check slots
        if ($task->slots_taken >= $task->slots_total) {
            return back()->with('error', 'No slots available.');
        }

        // Check VIP access
        if ($task->access_level === 'vip_only' && $user->sidekick_level !== 'vip') {
            return back()->with('error', 'VIP only mission.');
        }

        TaskApplication::create([
            'task_id' => $task->id,
            'user_id' => $user->id,
            'status' => 'applied',
            'applied_at' => now(),
        ]);

        return back()->with('success', 'Mission application sent.');
    }

    public function submit(Request $request, Task $task)
    {
        $user = auth()->user();
        $application = $user->taskApplications()
            ->where('task_id', $task->id)
            ->where('status', 'accepted')
            ->firstOrFail();

        $validated = $request->validate([
            'proof_url' => ['required', 'url'],
        ]);

        TaskSubmission::create([
            'application_id' => $application->id,
            'submission_type' => 'link',
            'proof_url' => $validated['proof_url'],
            'submitted_at' => now(),
        ]);

        $application->update(['status' => 'submitted']);

        return back()->with('success', 'Proof submitted for review.');
    }
}
