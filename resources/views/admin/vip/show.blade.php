@extends('layouts.admin')

@section('title', 'VIP REVIEW')

@section('content')
    {{-- Back --}}
    <a href="{{ route('admin.vip') }}" class="inline-flex items-center gap-1 text-sm text-gray-400 hover:text-neon transition-colors mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Queue ({{ $pendingCount }} pending)
    </a>

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4 mb-8">
        <div>
            <h2 class="font-heading text-3xl tracking-wide">{{ $user->name }}</h2>
            <p class="text-gray-400 mt-1">
                Requesting <span class="text-warning font-bold">VIP Status</span> Upgrade
            </p>
            <p class="text-xs text-gray-500 font-mono mt-1">{{ $user->handle }} &middot; Tier {{ $user->tier }} &middot; {{ number_format($user->follower_count) }} followers</p>
        </div>
        <div class="flex items-center gap-3">
            <form method="POST" action="{{ route('admin.vip.reject', $vip) }}">
                @csrf
                <button type="submit" class="px-6 py-2.5 text-sm font-bold uppercase tracking-wider border border-danger/50 text-danger rounded-full hover:bg-danger/10 transition-colors">
                    Reject
                </button>
            </form>
            <form method="POST" action="{{ route('admin.vip.approve', $vip) }}">
                @csrf
                <button type="submit" class="px-6 py-2.5 text-sm font-bold uppercase tracking-wider bg-neon text-dark rounded-full hover:bg-neon-dim transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3l3.057-3 11.943 12-11.943 12L5 21l9-9-9-9z"/></svg>
                    Approve VIP
                </button>
            </form>
        </div>
    </div>

    {{-- Content Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Left: Eligibility Checklist --}}
        <div class="bg-dark-light border border-dark-lighter rounded-xl p-6">
            <h4 class="text-xs text-gray-500 uppercase tracking-widest mb-6">Eligibility Checklist</h4>

            <div class="space-y-4">
                {{-- Campaigns Completed --}}
                <div class="flex items-center justify-between p-3 bg-dark rounded-lg">
                    <div>
                        <p class="text-white font-medium">Campaigns Completed</p>
                        <p class="text-xs text-gray-500 mt-0.5">Minimum 5 campaigns required</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="font-mono text-sm {{ $campaignsCompleted >= 5 ? 'text-success' : 'text-gray-400' }}">
                            {{ $campaignsCompleted }}/5
                        </span>
                        @if($campaignsCompleted >= 5)
                            <svg class="w-5 h-5 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        @else
                            <svg class="w-5 h-5 text-danger" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        @endif
                    </div>
                </div>

                {{-- Referrals --}}
                <div class="flex items-center justify-between p-3 bg-dark rounded-lg">
                    <div>
                        <p class="text-white font-medium">Referrals</p>
                        <p class="text-xs text-gray-500 mt-0.5">Minimum 5 referrals required</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="font-mono text-sm {{ $referralCount >= 5 ? 'text-success' : 'text-gray-400' }}">
                            {{ $referralCount }}/5
                        </span>
                        @if($referralCount >= 5)
                            <svg class="w-5 h-5 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        @else
                            <svg class="w-5 h-5 text-danger" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        @endif
                    </div>
                </div>

                {{-- Payment Receipt --}}
                <div class="flex items-center justify-between p-3 bg-dark rounded-lg">
                    <div>
                        <p class="text-white font-medium">Payment Receipt (RM {{ number_format($vip->fee_amount, 2) }})</p>
                        <p class="text-xs text-gray-500 mt-0.5">VIP membership fee</p>
                    </div>
                    <div class="flex items-center gap-2">
                        @if($hasReceipt)
                            <svg class="w-5 h-5 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        @else
                            <svg class="w-5 h-5 text-danger" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Summary --}}
            @php
                $checksPassed = ($campaignsCompleted >= 5 ? 1 : 0) + ($referralCount >= 5 ? 1 : 0) + ($hasReceipt ? 1 : 0);
            @endphp
            <div class="mt-6 pt-4 border-t border-dark-lighter flex justify-between items-center">
                <span class="text-xs text-gray-500 uppercase tracking-wider">Eligibility Score</span>
                <span class="font-heading text-xl {{ $checksPassed === 3 ? 'text-success' : ($checksPassed >= 1 ? 'text-warning' : 'text-danger') }}">
                    {{ $checksPassed }}/3
                </span>
            </div>
        </div>

        {{-- Right: Payment Receipt & Agent Profile --}}
        <div class="space-y-6">
            {{-- Payment Receipt --}}
            <div class="bg-dark-light border border-dark-lighter rounded-xl p-6 flex items-center justify-center min-h-[200px]">
                @if($hasReceipt)
                    <div class="text-center">
                        <svg class="w-10 h-10 mx-auto text-gray-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span class="text-xs font-bold uppercase text-gray-400 block">Payment Receipt</span>
                        <span class="text-info text-xs font-mono mt-1 block">{{ $vip->receipt_url }}</span>
                    </div>
                @else
                    <div class="text-center">
                        <svg class="w-10 h-10 mx-auto text-gray-700 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span class="text-xs text-gray-600 uppercase tracking-wider">No Receipt Uploaded</span>
                    </div>
                @endif
            </div>

            {{-- Agent Profile --}}
            <div class="bg-dark-light border border-dark-lighter rounded-xl p-6">
                <h4 class="text-xs text-gray-500 uppercase tracking-widest mb-4">Agent Profile</h4>
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Name</span>
                        <span class="text-white font-medium">{{ $user->name }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Handle</span>
                        <span class="text-white font-mono">{{ $user->handle }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Tier</span>
                        <span class="px-2 py-0.5 text-xs font-bold bg-dark rounded">{{ $user->tier }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Followers</span>
                        <span class="text-white font-medium">{{ number_format($user->follower_count) }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Total EXP</span>
                        <span class="text-neon font-mono font-medium">{{ number_format($user->total_exp) }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Success Rate</span>
                        <span class="text-white font-mono">{{ number_format($user->success_rate ?? 0, 1) }}%</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Current Level</span>
                        <span class="font-medium {{ $user->sidekick_level === 'vip' ? 'text-warning' : 'text-gray-400' }}">{{ ucfirst($user->sidekick_level) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
