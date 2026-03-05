<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Broadcast;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class BroadcastController extends Controller
{
    public function index()
    {
        $broadcasts = Broadcast::latest()->get();

        return view('admin.broadcasts.index', compact('broadcasts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sender_type' => ['required', 'string'],
            'sender' => ['required', 'string', 'max:255'],
            'audience' => ['required', 'string'],
            'message' => ['required', 'string'],
            'schedule_type' => ['required', 'in:now,schedule'],
            'scheduled_at' => ['nullable', 'required_if:schedule_type,schedule', 'date', 'after:now'],
        ]);

        $isScheduled = $validated['schedule_type'] === 'schedule';

        $broadcast = Broadcast::create([
            'sender_type' => $validated['sender_type'],
            'sender' => $validated['sender'],
            'audience' => $validated['audience'],
            'message' => $validated['message'],
            'scheduled_at' => $isScheduled ? $validated['scheduled_at'] : null,
            'status' => $isScheduled ? 'scheduled' : 'sent',
        ]);

        // Only fan out notifications if sending now
        if (! $isScheduled) {
            $this->sendNotifications($broadcast);

            return redirect()->route('admin.broadcasts')
                ->with('success', 'Broadcast sent successfully.');
        }

        return redirect()->route('admin.broadcasts')
            ->with('success', 'Broadcast scheduled for ' . $broadcast->scheduled_at->format('d M Y, h:i A'));
    }

    private function sendNotifications(Broadcast $broadcast): void
    {
        $query = User::where('role', 'agent')->where('status', 'active');

        $query = match ($broadcast->audience) {
            'vip' => $query->where('sidekick_level', 'vip'),
            'premium' => $query->where('sidekick_level', 'premium'),
            'tier_a' => $query->where('tier', 'A'),
            default => $query,
        };

        foreach ($query->get() as $user) {
            Notification::create([
                'user_id' => $user->id,
                'broadcast_id' => $broadcast->id,
                'type' => 'broadcast',
                'message' => $broadcast->message,
            ]);
        }
    }
}
