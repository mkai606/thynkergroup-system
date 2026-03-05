@extends('layouts.admin')

@section('title', 'SIDEKICK PROFILE')

@section('content')
    {{-- Back Button --}}
    <a href="{{ route('admin.sidekicks') }}" class="inline-flex items-center gap-1 text-sm text-gray-400 hover:text-neon transition-colors mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Roster
    </a>

    {{-- Profile Header --}}
    <div class="bg-dark-light border border-dark-lighter rounded-xl p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center gap-6">
            {{-- Avatar --}}
            <div class="w-20 h-20 rounded-full bg-dark border-2 border-dark-lighter flex items-center justify-center flex-shrink-0">
                @if($user->avatar_url)
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-full h-full rounded-full object-cover">
                @else
                    <span class="text-2xl font-heading text-gray-500">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                @endif
            </div>

            {{-- Name & Info --}}
            <div class="flex-1">
                <div class="flex items-center gap-2 mb-1">
                    <h2 class="font-heading text-3xl tracking-wide">{{ $user->name }}</h2>
                    @if($user->verified_badge)
                        <svg class="w-5 h-5 text-neon" fill="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    @endif
                    @if($user->flagged)
                        <span class="px-2 py-0.5 text-xs font-bold bg-danger/10 text-danger rounded">FLAGGED</span>
                    @endif
                </div>
                <p class="text-gray-400 font-mono text-sm">{{ $user->handle }}</p>
                <div class="flex items-center gap-4 mt-2 text-xs text-gray-500">
                    <span>{{ $user->email }}</span>
                    @if($user->phone)
                        <span>{{ $user->phone }}</span>
                    @endif
                    <span>Joined {{ $user->join_date?->format('d M Y') ?? 'N/A' }}</span>
                </div>
            </div>

            {{-- Tier Badge --}}
            <div class="text-center flex-shrink-0">
                <div class="w-16 h-16 rounded-xl bg-dark border border-dark-lighter flex items-center justify-center mb-1">
                    <span class="font-heading text-3xl text-neon">{{ $user->tier }}</span>
                </div>
                <p class="text-xs text-gray-500 uppercase tracking-wider">Tier</p>
            </div>
        </div>

        {{-- Flag Section --}}
        <div class="mt-4" x-data="{ showFlagModal: false }">
            @if($user->flagged)
                <div class="flex items-center gap-3">
                    <span class="text-xs text-danger font-bold uppercase tracking-wider">Flagged: {{ Str::limit($user->flagged_reason ?? 'No reason', 40) }}</span>
                    <form method="POST" action="{{ route('admin.sidekicks.unflag', $user) }}">
                        @csrf
                        <button type="submit" class="px-3 py-1.5 text-xs font-bold uppercase tracking-wider bg-dark border border-dark-lighter text-gray-400 rounded-lg hover:text-success hover:border-success transition-colors whitespace-nowrap">
                            Remove Flag
                        </button>
                    </form>
                </div>
            @else
                <button @click="showFlagModal = true"
                        class="px-3 py-1.5 text-xs font-bold uppercase tracking-wider border border-dark-lighter text-gray-400 rounded-lg hover:text-danger hover:border-danger transition-colors">
                    Flag User
                </button>
            @endif

            {{-- Flag Modal --}}
            <div x-show="showFlagModal" x-cloak
                 class="fixed inset-0 z-50 flex items-center justify-center"
                 @keydown.escape.window="showFlagModal = false">
                <div class="absolute inset-0 bg-black/60" @click="showFlagModal = false"></div>
                <div class="relative bg-dark-light border border-danger/30 rounded-xl p-6 w-full max-w-md mx-4 shadow-2xl"
                     @click.stop>
                    <h3 class="font-heading text-sm tracking-wider text-danger mb-4">FLAG USER</h3>
                    <form method="POST" action="{{ route('admin.sidekicks.flag', $user) }}">
                        @csrf
                        <label class="text-xs text-gray-400 uppercase tracking-wider">Reason</label>
                        <textarea name="flagged_reason" rows="3" required
                                  class="w-full mt-1 bg-dark border border-dark-lighter rounded-lg px-3 py-2 text-sm text-white placeholder-gray-600 focus:border-danger focus:outline-none"
                                  placeholder="State the reason for flagging this user..."></textarea>
                        <div class="flex justify-end gap-2 mt-4">
                            <button type="button" @click="showFlagModal = false"
                                    class="px-3 py-1.5 text-xs font-bold uppercase tracking-wider text-gray-500 hover:text-white transition-colors">
                                Cancel
                            </button>
                            <button type="submit"
                                    class="px-3 py-1.5 text-xs font-bold uppercase tracking-wider bg-danger/10 text-danger border border-danger/30 rounded-lg hover:bg-danger/20 transition-colors">
                                Confirm Flag
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4 mb-6">
        <div class="bg-dark-light border border-dark-lighter rounded-xl p-4">
            <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Level</p>
            @if($user->sidekick_level === 'vip')
                <span class="px-2 py-0.5 text-sm font-bold bg-warning/10 text-warning rounded">VIP</span>
            @else
                <span class="px-2 py-0.5 text-sm font-medium bg-gray-500/10 text-gray-400 rounded">Premium</span>
            @endif
        </div>

        <div class="bg-dark-light border border-dark-lighter rounded-xl p-4">
            <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Total EXP</p>
            <p class="text-xl font-bold text-neon font-mono">{{ number_format($user->total_exp) }}</p>
        </div>

        <div class="bg-dark-light border border-dark-lighter rounded-xl p-4">
            <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Monthly EXP</p>
            <p class="text-xl font-bold text-white font-mono">{{ number_format($user->monthly_exp) }}</p>
        </div>

        <div class="bg-dark-light border border-dark-lighter rounded-xl p-4">
            <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Success Rate</p>
            <p class="text-xl font-bold text-white font-mono">{{ number_format($user->success_rate ?? 0, 1) }}%</p>
        </div>

        <div class="bg-dark-light border border-dark-lighter rounded-xl p-4">
            <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Followers</p>
            <p class="text-xl font-bold text-white font-mono">{{ number_format($user->follower_count) }}</p>
        </div>

        <div class="bg-dark-light border border-dark-lighter rounded-xl p-4">
            <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Rank</p>
            <p class="text-xl font-bold text-white font-mono">#{{ $user->rank_position ?? '-' }}</p>
        </div>
    </div>

    {{-- Two Column Layout --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Left Column: Operations --}}
        <div class="space-y-6">
            {{-- Task Stats --}}
            <div class="bg-dark-light border border-dark-lighter rounded-xl overflow-hidden">
                <div class="px-6 py-4 border-b border-dark-lighter">
                    <h3 class="font-heading text-sm tracking-wider text-gray-400">OPERATIONS</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-400">Completed Tasks</span>
                        <span class="text-sm font-bold text-neon font-mono">{{ $user->completed_tasks ?? $completedTasks }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-400">Active Tasks</span>
                        <span class="text-sm font-bold text-white font-mono">{{ $activeTasks }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-400">Total Applications</span>
                        <span class="text-sm font-bold text-white font-mono">{{ $user->taskApplications->count() }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-400">Referrals Made</span>
                        <span class="text-sm font-bold text-white font-mono">{{ $user->referral_count ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-400">Referral Code</span>
                        <span class="text-sm font-bold text-white font-mono">{{ $user->referral_code ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-400">Platform</span>
                        <span class="text-sm font-bold text-info font-mono">{{ ucfirst($user->platform_primary ?? 'N/A') }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column: Financial --}}
        <div class="space-y-6">
            {{-- Wallet & VIP --}}
            <div class="bg-dark-light border border-dark-lighter rounded-xl overflow-hidden">
                <div class="px-6 py-4 border-b border-dark-lighter">
                    <h3 class="font-heading text-sm tracking-wider text-gray-400">FINANCIAL INTEL</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-400">Wallet Balance</span>
                        <span class="text-sm font-bold text-neon font-mono">RM {{ number_format($walletBalance, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-400">VIP Status</span>
                        @php
                            $vipStatusColors = [
                                'active' => 'text-success',
                                'payment_submitted' => 'text-warning',
                                'eligible' => 'text-info',
                                'none' => 'text-gray-500',
                            ];
                            $vipColor = $vipStatusColors[$user->vip_status] ?? 'text-gray-500';
                        @endphp
                        <span class="text-sm font-bold {{ $vipColor }} font-mono">{{ ucfirst(str_replace('_', ' ', $user->vip_status ?? 'none')) }}</span>
                    </div>
                    @if($vip)
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-400">VIP Since</span>
                            <span class="text-sm text-white font-mono">{{ $vip->approved_at?->format('d M Y') ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-400">VIP Expires</span>
                            <span class="text-sm text-white font-mono">{{ $vip->expires_at?->format('d M Y') ?? 'N/A' }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-400">Account Status</span>
                        <span class="text-sm font-bold {{ $user->status === 'active' ? 'text-success' : 'text-danger' }} font-mono">{{ ucfirst($user->status) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
