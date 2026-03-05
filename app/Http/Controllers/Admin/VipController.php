<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VipMembership;
use Illuminate\Http\Request;

class VipController extends Controller
{
    public function index(Request $request)
    {
        $query = VipMembership::with('user')
            ->whereIn('status', ['payment_submitted', 'eligible']);

        // Status filter
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $requests = $query->latest()->get();

        return view('admin.vip.index', compact('requests'));
    }

    public function show(VipMembership $vip)
    {
        $vip->load('user');

        $user = $vip->user;
        $campaignsCompleted = $user->completed_tasks ?? 0;
        $referralCount = $user->referral_count ?? 0;
        $hasReceipt = ! empty($vip->receipt_url);

        $pendingCount = VipMembership::whereIn('status', ['payment_submitted', 'eligible'])->count();

        return view('admin.vip.show', compact('vip', 'user', 'campaignsCompleted', 'referralCount', 'hasReceipt', 'pendingCount'));
    }

    public function approve(VipMembership $vip)
    {
        $vip->update([
            'status' => 'active',
            'approved_at' => now(),
            'expires_at' => now()->addYear(),
        ]);

        $vip->user->update([
            'sidekick_level' => 'vip',
            'vip_status' => 'active',
            'verified_badge' => true,
        ]);

        return redirect()->route('admin.vip')
            ->with('success', $vip->user->name . ' has been approved as VIP.');
    }

    public function reject(VipMembership $vip)
    {
        $vip->update([
            'status' => 'rejected',
        ]);

        $vip->user->update([
            'vip_status' => 'rejected',
        ]);

        return redirect()->route('admin.vip')
            ->with('success', $vip->user->name . ' VIP request rejected.');
    }
}
