<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Referral;
use App\Models\Registration;
use App\Models\TierRule;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegistrationController extends Controller
{
    public function index(Request $request)
    {
        $query = Registration::with(['socialProfiles'])
            ->where('status', 'pending');

        // Platform filter
        if ($request->filled('platform') && $request->platform !== 'all') {
            $platform = $request->platform;
            $query->whereHas('socialProfiles', function ($q) use ($platform) {
                $q->where('platform', $platform);
            });
        }

        $registrations = $query->latest('submitted_at')->get();

        // Sort
        if ($request->input('sort') === 'platform') {
            // Instagram first, then tiktok, youtube, others
            $platformOrder = ['instagram' => 0, 'tiktok' => 1, 'youtube' => 2];
            $registrations = $registrations->sortBy(function ($reg) use ($platformOrder) {
                $primary = $reg->socialProfiles->sortByDesc('followers')->first();
                return $platformOrder[$primary?->platform ?? ''] ?? 99;
            })->values();
        }

        // Get unique platforms for filter dropdown
        $platforms = \App\Models\RegistrationSocialProfile::whereHas('registration', function ($q) {
            $q->where('status', 'pending');
        })->distinct()->pluck('platform')->sort()->values();

        return view('admin.registrations.index', compact('registrations', 'platforms'));
    }

    public function show(Registration $registration)
    {
        $registration->load(['socialProfiles', 'highlightPosts', 'evidence']);

        // Calculate recommended tier from highest follower count
        $highestFollowers = $registration->socialProfiles->max('followers') ?? 0;
        $totalFollowers = $registration->socialProfiles->sum('followers');
        $recommendedTier = $this->calculateTier($highestFollowers);
        $tierRule = TierRule::first();

        $pendingCount = Registration::where('status', 'pending')->count();

        return view('admin.registrations.show', compact(
            'registration', 'highestFollowers', 'totalFollowers', 'recommendedTier', 'pendingCount', 'tierRule'
        ));
    }

    public function approve(Registration $registration)
    {
        $registration->load('socialProfiles');

        $highestFollowers = $registration->socialProfiles->max('followers') ?? 0;
        $tier = $this->calculateTier($highestFollowers);

        $user = DB::transaction(function () use ($registration, $tier, $highestFollowers) {
            // Create user account
            $handle = '@' . Str::slug($registration->full_name, '_');
            $referralCode = strtoupper(Str::random(8));

            $user = User::create([
                'name' => $registration->full_name,
                'email' => $registration->email,
                'password' => Hash::make('sidekick123'),
                'handle' => $handle,
                'phone' => $registration->phone,
                'role' => 'agent',
                'status' => 'active',
                'tier' => $tier,
                'follower_count' => $highestFollowers,
                'sidekick_level' => 'regular',
                'vip_status' => 'none',
                'total_exp' => 0,
                'monthly_exp' => 0,
                'rank_position' => 0,
                'verified_badge' => false,
                'flagged' => false,
                'referral_code' => $referralCode,
                'referral_count' => 0,
                'email_verified_at' => now(),
            ]);

            // Create wallet
            Wallet::create([
                'user_id' => $user->id,
                'balance' => 0,
            ]);

            // Copy social profiles to user
            foreach ($registration->socialProfiles as $profile) {
                $user->socialProfiles()->create([
                    'platform' => $profile->platform,
                    'handle' => $profile->handle,
                    'followers' => $profile->followers,
                    'profile_url' => $profile->profile_url,
                ]);
            }

            // Track referral if a code was used
            if ($registration->referral_code_used) {
                $referrer = User::where('referral_code', $registration->referral_code_used)->first();

                if ($referrer) {
                    Referral::create([
                        'referrer_user_id' => $referrer->id,
                        'referred_user_id' => $user->id,
                        'referral_code' => $registration->referral_code_used,
                    ]);

                    $referrer->increment('referral_count');
                }
            }

            // Update registration status
            $registration->update([
                'status' => 'approved',
                'verification_status' => 'verified',
            ]);

            return $user;
        });

        return redirect()->route('admin.registrations')
            ->with('success', $registration->full_name . ' approved as Tier ' . $tier . ' agent. Default password: sidekick123');
    }

    public function reject(Registration $registration)
    {
        $registration->update([
            'status' => 'rejected',
        ]);

        return redirect()->route('admin.registrations')
            ->with('success', $registration->full_name . ' registration rejected.');
    }

    private function calculateTier(int $followers): string
    {
        $rule = TierRule::first();

        if (! $rule) {
            return 'E';
        }

        if ($followers >= $rule->tier_a_min_followers) return 'A';
        if ($followers >= $rule->tier_b_min_followers) return 'B';
        if ($followers >= $rule->tier_c_min_followers) return 'C';
        if ($followers >= $rule->tier_d_min_followers) return 'D';

        return 'E';
    }
}
