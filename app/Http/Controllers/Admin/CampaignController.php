<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Task;
use App\Models\TaskApplication;
use App\Models\TaskHashtag;
use App\Models\TaskInstruction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CampaignController extends Controller
{
    public function index()
    {
        $campaigns = Campaign::with(['tasks'])->latest()->get();

        $totalLiability = $campaigns->sum('total_budget');
        $allocatedBudget = $campaigns->sum('spent_budget');

        return view('admin.campaigns.index', compact('campaigns', 'totalLiability', 'allocatedBudget'));
    }

    public function show(Campaign $campaign)
    {
        $campaign->load([
            'tasks.applications.user',
            'tasks.instructions',
            'tasks.hashtags',
        ]);

        $task = $campaign->tasks->first();

        return view('admin.campaigns.show', compact('campaign', 'task'));
    }

    public function create()
    {
        return view('admin.campaigns.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'brand' => ['required', 'string', 'max:255'],
            'platform' => ['required', 'string'],
            'tier' => ['nullable', 'string', 'in:A,B,C,D,E'],
            'min_followers' => ['nullable', 'integer', 'min:0'],
            'description' => ['required', 'string'],
            'hidden_details' => ['nullable', 'string'],
            'hashtags' => ['nullable', 'string'],
            'slots_total' => ['required', 'integer', 'min:1'],
            'reward_amount' => ['required', 'numeric', 'min:1'],
            'exp_reward' => ['required', 'integer', 'in:25,50,75,100'],
            'vip_only' => ['nullable', 'boolean'],
            'instructions' => ['nullable', 'string'],
            'deadline_days' => ['nullable', 'integer', 'min:1', 'max:90'],
            'tng_qr' => ['nullable', 'file', 'mimetypes:image/jpeg,image/png,image/webp', 'max:2048'],
        ]);

        $totalBudget = $validated['slots_total'] * $validated['reward_amount'];
        $deadlineDays = (int) ($validated['deadline_days'] ?? 30);

        // Instagram & Target: use tier to determine min_followers
        // Other platforms: use min_followers directly
        $tierPlatforms = ['Instagram', 'Target'];
        if (in_array($validated['platform'], $tierPlatforms)) {
            $tier = $validated['tier'] ?? 'C';
            $minFollowers = match ($tier) {
                'A' => 10000,
                'B' => 5000,
                'C' => 3000,
                'D' => 2000,
                'E' => 0,
            };
        } else {
            $minFollowers = $validated['min_followers'] ?? 0;
        }

        $campaign = DB::transaction(function () use ($validated, $totalBudget, $deadlineDays, $minFollowers) {
            $campaign = Campaign::create([
                'title' => $validated['title'],
                'brand' => $validated['brand'],
                'status' => 'active',
                'total_budget' => $totalBudget,
                'spent_budget' => 0,
                'start_at' => now(),
                'end_at' => now()->addDays($deadlineDays),
            ]);

            $task = Task::create([
                'campaign_id' => $campaign->id,
                'title' => $validated['title'],
                'platform' => $validated['platform'],
                'type' => 'post',
                'description' => $validated['description'],
                'access_level' => ($validated['vip_only'] ?? false) ? 'vip_only' : 'public',
                'min_followers' => $minFollowers,
                'exp_reward' => $validated['exp_reward'],
                'reward_amount' => $validated['reward_amount'],
                'slots_total' => $validated['slots_total'],
                'slots_taken' => 0,
                'deadline' => now()->addDays($deadlineDays),
                'instructions_locked' => true,
                'hidden_details' => $validated['hidden_details'] ?? null,
                'status' => 'open',
            ]);

            // Parse instructions (one per line)
            if (! empty($validated['instructions'])) {
                $lines = array_filter(array_map('trim', explode("\n", $validated['instructions'])));
                foreach ($lines as $index => $line) {
                    TaskInstruction::create([
                        'task_id' => $task->id,
                        'step_no' => $index + 1,
                        'instruction' => $line,
                    ]);
                }
            }

            // Parse hashtags (space or comma separated)
            if (! empty($validated['hashtags'])) {
                $tags = preg_split('/[\s,]+/', $validated['hashtags']);
                foreach (array_filter($tags) as $tag) {
                    $tag = ltrim(trim($tag), '#');
                    if ($tag) {
                        TaskHashtag::create([
                            'task_id' => $task->id,
                            'hashtag' => '#' . $tag,
                        ]);
                    }
                }
            }

            return $campaign;
        });

        // Handle TNG QR upload (outside transaction — needs campaign ID)
        if ($request->hasFile('tng_qr')) {
            $file = $request->file('tng_qr');
            $filename = 'campaign_qr/campaign_' . $campaign->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            Storage::disk('public')->put($filename, file_get_contents($file->getPathname()));
            $campaign->update(['tng_qr_url' => $filename]);
        }

        return redirect()->route('admin.campaigns.show', $campaign)
            ->with('success', 'Campaign "' . $campaign->title . '" created. Liability: RM ' . number_format($totalBudget, 2));
    }

    public function toggleLock(Campaign $campaign, Task $task)
    {
        $task->update([
            'instructions_locked' => ! $task->instructions_locked,
        ]);

        $status = $task->instructions_locked ? 'hidden' : 'public';

        return back()->with('success', "Instructions are now {$status}.");
    }

    public function autoAccept(Campaign $campaign)
    {
        $task = $campaign->tasks()->first();

        if (! $task) {
            return back()->with('error', 'No task found.');
        }

        $pendingApps = $task->applications()->where('status', 'applied')->get();
        $count = 0;

        foreach ($pendingApps as $app) {
            $app->update([
                'status' => 'accepted',
                'accepted_at' => now(),
            ]);
            $task->increment('slots_taken');
            $count++;
        }

        return back()->with('success', "{$count} applications auto-accepted.");
    }

    public function acceptApplication(Campaign $campaign, TaskApplication $application)
    {
        $application->update([
            'status' => 'accepted',
            'accepted_at' => now(),
        ]);

        $application->task->increment('slots_taken');

        return back()->with('success', 'Application accepted.');
    }

    public function rejectApplication(Campaign $campaign, TaskApplication $application)
    {
        $application->update([
            'status' => 'rejected',
        ]);

        return back()->with('success', 'Application rejected.');
    }
}
