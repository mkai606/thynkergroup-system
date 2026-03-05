<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $user->load('vipMemberships', 'wallet');

        $vip = $user->vipMemberships()->where('status', 'active')->first();
        $totalEarned = \App\Models\PayoutRequest::where('user_id', $user->id)
            ->where('status', 'completed')
            ->sum('amount');

        // Admin TNG QR for VIP payment
        $settings = \App\Models\SystemSetting::first();
        $adminQrUrl = $settings?->payment_config['tng_qr_url'] ?? null;

        // Check if VIP request already pending
        $pendingVip = \App\Models\VipMembership::where('user_id', $user->id)
            ->where('status', 'payment_submitted')
            ->first();

        return view('agent.profile', compact('user', 'vip', 'totalEarned', 'adminQrUrl', 'pendingVip'));
    }

    public function requestVip(Request $request)
    {
        $request->validate([
            'receipt' => ['required', 'file', 'mimetypes:image/jpeg,image/png,image/webp', 'max:2048'],
        ]);

        $user = auth()->user();

        // Check requirements
        if (($user->completed_tasks ?? 0) < 5 || ($user->referral_count ?? 0) < 5) {
            return back()->with('error', 'VIP requirements not met.');
        }

        // Check if already has pending/active VIP
        $existing = \App\Models\VipMembership::where('user_id', $user->id)
            ->whereIn('status', ['payment_submitted', 'active'])
            ->first();

        if ($existing) {
            return back()->with('error', 'You already have a pending or active VIP membership.');
        }

        $file = $request->file('receipt');
        $filename = 'vip_receipts/receipt_' . $user->id . '_' . time() . '.jpg';
        Storage::disk('public')->put($filename, file_get_contents($file->getPathname()));

        \App\Models\VipMembership::create([
            'user_id' => $user->id,
            'status' => 'payment_submitted',
            'receipt_url' => $filename,
            'fee_amount' => 15.00,
        ]);

        $user->update(['vip_status' => 'payment_submitted']);

        return back()->with('success', 'VIP payment submitted! Awaiting admin verification.');
    }

    public function updateTngQr(Request $request)
    {
        $request->validate([
            'tng_qr' => ['required', 'file', 'mimetypes:image/jpeg,image/png,image/webp', 'max:2048'],
        ]);

        $user = auth()->user();

        if ($user->tng_qr_url && Storage::disk('public')->exists($user->tng_qr_url)) {
            Storage::disk('public')->delete($user->tng_qr_url);
        }

        $file = $request->file('tng_qr');
        $filename = 'tng_qr/tng_' . $user->id . '_' . time() . '.jpg';
        Storage::disk('public')->put($filename, file_get_contents($file->getPathname()));

        $user->update(['tng_qr_url' => $filename]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'path' => $filename]);
        }

        return back()->with('success', 'TNG QR updated.');
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => ['required', 'file', 'mimetypes:image/jpeg,image/png,image/webp', 'max:2048'],
        ]);

        $user = auth()->user();

        // Delete old avatar if exists
        if ($user->avatar_url && Storage::disk('public')->exists($user->avatar_url)) {
            Storage::disk('public')->delete($user->avatar_url);
        }

        $file = $request->file('avatar');
        $filename = 'avatars/avatar_' . $user->id . '_' . time() . '.jpg';

        Storage::disk('public')->put($filename, file_get_contents($file->getPathname()));

        $user->update(['avatar_url' => $filename]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'path' => $filename]);
        }

        return back()->with('success', 'Profile photo updated');
    }
}
