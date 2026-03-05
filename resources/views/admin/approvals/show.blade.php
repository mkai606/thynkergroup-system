@extends('layouts.admin')

@section('title', 'REVIEW SUBMISSION')

@section('content')
    @php
        $submission = $approval->submission;
        $application = $submission?->application;
        $agent = $application?->user;
        $task = $application?->task;
        $campaign = $task?->campaign;
    @endphp

    @if(!$submission || !$application || !$agent || !$task || !$campaign)
        <div class="p-8 text-center">
            <p class="text-danger font-bold">Missing data — submission chain incomplete.</p>
            <a href="{{ route('admin.approvals') }}" class="text-neon hover:underline mt-2 inline-block">← Back to Queue</a>
        </div>
        @php return; @endphp
    @endif

    {{-- Back --}}
    <a href="{{ route('admin.approvals') }}" class="inline-flex items-center gap-1 text-sm text-gray-400 hover:text-neon transition-colors mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Queue
    </a>

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4 mb-6">
        <div>
            <h2 class="font-heading text-3xl tracking-wide">{{ $task->title }}</h2>
            <p class="text-gray-400 mt-1">
                Submitted by <span class="text-info">{{ $agent->name }}</span>
                &middot; <span class="text-neon font-mono">+{{ $approval->exp_awarded }} EXP</span>
                &middot; <span class="text-neon font-bold">RM {{ number_format($task->reward_amount, 2) }}</span>
            </p>
        </div>
        <div>
            @if($approval->status === 'pending')
                <div class="flex items-center gap-3">
                    <form method="POST" action="{{ route('admin.approvals.reject', $approval) }}">
                        @csrf
                        <button type="submit" class="px-6 py-2.5 text-sm font-bold uppercase tracking-wider border border-danger/40 text-danger rounded-full hover:bg-danger/10 transition-colors">
                            Reject
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.approvals.approve', $approval) }}">
                        @csrf
                        <button type="submit" class="px-6 py-2.5 text-sm font-bold uppercase tracking-wider bg-neon text-dark rounded-full hover:bg-neon-dim transition-colors flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Approve & Pay
                        </button>
                    </form>
                </div>
            @elseif($approval->status === 'approved')
                <span class="px-4 py-2 text-sm font-bold uppercase tracking-wider rounded-full bg-info/10 text-info border border-info/30">
                    Awaiting Payment
                </span>
            @elseif($approval->status === 'paid')
                <span class="px-4 py-2 text-sm font-bold uppercase tracking-wider rounded-full bg-success/10 text-success border border-success/30">
                    Paid
                </span>
            @elseif($approval->status === 'rejected')
                <span class="px-4 py-2 text-sm font-bold uppercase tracking-wider rounded-full bg-danger/10 text-danger border border-danger/30">
                    Rejected
                </span>
            @endif
        </div>
    </div>

    {{-- Payment Section (show after approved) --}}
    @if($approval->status === 'approved')
        <div class="bg-dark-light border-2 border-neon/30 rounded-xl p-6 mb-6">
            <div class="flex items-center gap-2 mb-4">
                <svg class="w-5 h-5 text-neon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                <h3 class="font-heading text-lg tracking-wider text-neon">TRANSFER PAYMENT</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Agent QR --}}
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider mb-3">Agent's TNG QR Code</p>
                    @if($agent->tng_qr_url)
                        <div class="bg-white rounded-lg p-4">
                            <img src="{{ asset('storage/' . $agent->tng_qr_url) }}" alt="TNG QR" class="w-full max-h-[300px] object-contain mx-auto">
                        </div>
                        <p class="text-xs text-gray-500 text-center mt-2">Scan to transfer RM {{ number_format($task->reward_amount, 2) }}</p>
                    @else
                        <div class="bg-dark rounded-lg p-8 text-center">
                            <svg class="w-12 h-12 mx-auto text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4.5c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                            <p class="text-danger text-sm font-bold">No QR uploaded</p>
                            <p class="text-gray-500 text-xs mt-1">Agent needs to upload TNG QR on their profile</p>
                        </div>
                    @endif
                </div>

                {{-- Payment Info --}}
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider mb-3">Payment Details</p>
                    <div class="bg-dark rounded-xl p-5 space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Agent</span>
                            <span class="text-white font-medium">{{ $agent->name }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Handle</span>
                            <span class="text-white font-mono">{{ $agent->handle }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Amount</span>
                            <span class="text-neon font-bold text-lg">RM {{ number_format($task->reward_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Provider</span>
                            <span class="text-white">TNG eWallet</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Task</span>
                            <span class="text-white">{{ $task->title }}</span>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('admin.approvals.markPaid', $approval) }}" class="mt-4"
                          x-data @submit.prevent="if(confirm('Confirm RM{{ number_format($task->reward_amount, 2) }} has been transferred to {{ $agent->name }}?')) $el.submit()">
                        @csrf
                        <button type="submit" class="w-full px-6 py-3 text-sm font-bold uppercase tracking-wider bg-neon text-dark rounded-xl hover:bg-neon-dim transition-colors shadow-[0_0_15px_rgba(170,255,0,0.2)] flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Mark as Paid
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- Content Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Left: Proof --}}
        <div>
            <div class="bg-dark-light border border-dark-lighter rounded-xl p-6">
                <h3 class="font-heading text-sm tracking-wider text-gray-400 mb-4">SUBMISSION PROOF</h3>

                {{-- Screenshot Placeholder --}}
                <div class="aspect-[9/16] max-h-[400px] bg-dark rounded-lg flex items-center justify-center mb-4">
                    <div class="text-center">
                        <svg class="w-12 h-12 mx-auto text-gray-700 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        <p class="text-gray-600 text-xs uppercase tracking-wider">Screenshot Preview</p>
                    </div>
                </div>

                {{-- Link Check --}}
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Proof Link</p>
                    <a href="{{ $submission->proof_url }}" target="_blank"
                       class="text-info text-sm font-mono truncate block hover:underline">
                        {{ $submission->proof_url }}
                    </a>
                </div>
            </div>
        </div>

        {{-- Right: Agent & Risk Info --}}
        <div class="space-y-6">
            {{-- Agent Info --}}
            <div class="bg-dark-light border border-dark-lighter rounded-xl p-6">
                <h3 class="font-heading text-sm tracking-wider text-gray-400 mb-4">AGENT PROFILE</h3>
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Name</span>
                        <span class="text-white font-medium">{{ $agent->name }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Handle</span>
                        <span class="text-white font-mono">{{ $agent->handle }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Tier</span>
                        <span class="px-2 py-0.5 text-xs font-bold bg-dark rounded">{{ $agent->tier }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Followers</span>
                        <span class="text-white font-medium">{{ number_format($agent->follower_count) }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Total EXP</span>
                        <span class="text-neon font-mono font-medium">{{ number_format($agent->total_exp) }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">TNG QR</span>
                        @if($agent->tng_qr_url)
                            <span class="px-2 py-1 text-xs font-medium bg-success/10 text-success rounded">Uploaded</span>
                        @else
                            <span class="px-2 py-1 text-xs font-medium bg-danger/10 text-danger rounded">Not uploaded</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Risk Assessment --}}
            <div class="bg-dark-light border border-dark-lighter rounded-xl p-6">
                <h3 class="font-heading text-sm tracking-wider text-gray-400 mb-4">RISK ASSESSMENT</h3>
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Fraud Risk</span>
                        @if($approval->fraud_risk && str_contains(strtolower($approval->fraud_risk), 'high'))
                            <span class="px-2 py-1 text-xs font-medium bg-danger/10 text-danger rounded">{{ $approval->fraud_risk }}</span>
                        @elseif($approval->fraud_risk && str_contains(strtolower($approval->fraud_risk), 'medium'))
                            <span class="px-2 py-1 text-xs font-medium bg-warning/10 text-warning rounded">{{ $approval->fraud_risk }}</span>
                        @else
                            <span class="px-2 py-1 text-xs font-medium bg-success/10 text-success rounded">{{ $approval->fraud_risk ?? 'Low' }}</span>
                        @endif
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Detected Handle</span>
                        <span class="text-white font-mono">{{ $approval->detected_handle ?? '—' }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Verification</span>
                        @if($approval->auto_verified)
                            <span class="px-2 py-1 text-xs font-medium bg-success/10 text-success rounded">Auto-Verified</span>
                        @else
                            <span class="px-2 py-1 text-xs font-medium bg-warning/10 text-warning rounded">Manual Check</span>
                        @endif
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Submitted At</span>
                        <span class="text-white font-mono text-xs">{{ $submission->submitted_at ? $submission->submitted_at->format('d M Y H:i') : $submission->created_at->format('d M Y H:i') }}</span>
                    </div>
                </div>
            </div>

            {{-- Campaign Context --}}
            <div class="bg-dark-light border border-dark-lighter rounded-xl p-6">
                <h3 class="font-heading text-sm tracking-wider text-gray-400 mb-4">CAMPAIGN CONTEXT</h3>
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Campaign</span>
                        <span class="text-white font-medium">{{ $campaign->title }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Platform</span>
                        <span class="px-2 py-1 text-xs font-medium bg-info/10 text-info rounded">{{ $task->platform }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Reward</span>
                        <span class="text-neon font-bold">RM {{ number_format($task->reward_amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">EXP Award</span>
                        <span class="text-neon font-mono">{{ $approval->exp_awarded }} EXP</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
