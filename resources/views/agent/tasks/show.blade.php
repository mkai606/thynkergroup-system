@extends('layouts.agent')

@section('title', $task->title)

@section('content')
    {{-- Back --}}
    <a href="{{ url()->previous() }}" class="inline-flex items-center gap-1 text-sm text-gray-400 hover:text-neon transition-colors mb-4">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back
    </a>

    {{-- Task Header --}}
    <div class="mb-6">
        <div class="flex items-center gap-2 mb-2">
            {{-- Platform badge --}}
            <span class="px-2 py-0.5 rounded text-[10px] uppercase font-bold tracking-wider
                @if($task->platform === 'Instagram') bg-pink-900/40 text-pink-300 border border-pink-700
                @elseif($task->platform === 'TikTok') bg-gray-700 text-white border border-gray-600
                @elseif($task->platform === 'Facebook') bg-blue-900/40 text-blue-300 border border-blue-700
                @elseif($task->platform === 'YouTube') bg-red-900/40 text-red-400 border border-red-700
                @else bg-gray-800 text-gray-300 border border-gray-700
                @endif">
                {{ $task->platform }}
            </span>
            @if($task->access_level === 'vip_only')
                <span class="px-2 py-0.5 rounded text-[10px] uppercase font-bold tracking-wider bg-[#3D4421] text-neon border border-neon/30">VIP</span>
            @endif
            {{-- Task status --}}
            @if($task->status !== 'open')
                <span class="px-2 py-0.5 rounded text-[10px] uppercase font-bold tracking-wider bg-gray-800 text-gray-400 border border-gray-700">{{ ucfirst($task->status) }}</span>
            @endif
        </div>
        <h1 class="font-heading text-3xl tracking-wide">{{ strtoupper($task->title) }}</h1>
        <p class="text-sm text-neon mt-1">{{ $task->campaign?->brand ?? '' }}</p>
    </div>

    {{-- Reward Card --}}
    <div class="bg-[#262626] border border-gray-700 rounded-xl p-5 mb-4">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-[10px] text-gray-500 uppercase tracking-wider">Bounty</p>
                <p class="font-heading text-4xl text-neon">RM {{ number_format($task->reward_amount, 0) }}</p>
            </div>
            <div class="text-right">
                <p class="text-[10px] text-gray-500 uppercase tracking-wider">EXP Reward</p>
                <p class="font-heading text-2xl text-white">{{ $task->exp_reward }}</p>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-4 mt-3 pt-3 border-t border-gray-700">
            <div class="flex items-center gap-1 text-xs text-gray-400">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ $task->deadline ? $task->deadline->format('d M Y') : 'No deadline' }}
            </div>
            <div class="flex items-center gap-1 text-xs text-gray-400">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                {{ $task->slots_total - $task->slots_taken }} / {{ $task->slots_total }} Slots
            </div>
            @if($task->min_followers > 0)
                <div class="flex items-center gap-1 text-xs text-gray-400">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Min {{ number_format($task->min_followers) }}
                </div>
            @endif
        </div>
    </div>

    {{-- Description --}}
    @if($task->description)
        <div class="mb-4">
            <p class="text-[10px] text-gray-500 uppercase tracking-wider mb-2">Mission Scope</p>
            <p class="text-sm text-gray-300 leading-relaxed">{{ $task->description }}</p>
        </div>
    @endif

    {{-- Instructions (show only if not locked, or if user is accepted) --}}
    @php
        $canViewInstructions = !$task->instructions_locked || ($application && in_array($application->status, ['accepted', 'submitted', 'approved', 'paid']));
    @endphp

    @if($canViewInstructions)
        @if($task->instructions->isNotEmpty())
            <div class="mb-4">
                <p class="text-[10px] text-gray-500 uppercase tracking-wider mb-2">Instructions</p>
                <div class="bg-[#1A1A1A] border border-gray-800 rounded-xl p-4 space-y-3">
                    @foreach($task->instructions->sortBy('step_no') as $instruction)
                        <div class="flex gap-3">
                            <span class="text-neon font-heading text-sm mt-0.5">{{ str_pad($instruction->step_no, 2, '0', STR_PAD_LEFT) }}</span>
                            <span class="text-sm text-gray-300">{{ $instruction->instruction }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    @else
        <div class="mb-4">
            <p class="text-[10px] text-gray-500 uppercase tracking-wider mb-2">Instructions</p>
            <div class="bg-[#1A1A1A] border border-gray-800 rounded-xl p-4 text-center">
                <svg class="w-6 h-6 mx-auto text-gray-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                <p class="text-xs text-gray-500">Encrypted — Accept mission to unlock</p>
            </div>
        </div>
    @endif

    {{-- Hidden Details (only for accepted agents) --}}
    @if($task->hidden_details && $application && in_array($application->status, ['accepted', 'submitted', 'approved', 'paid']))
        <div class="mb-4">
            <p class="text-[10px] text-gray-500 uppercase tracking-wider mb-2">Classified Intel</p>
            <div class="bg-[#1A1A1A] border border-neon/20 rounded-xl p-4">
                <div class="flex items-start gap-2">
                    <svg class="w-4 h-4 text-neon mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-sm text-gray-300 leading-relaxed">{{ $task->hidden_details }}</p>
                </div>
            </div>
        </div>
    @elseif($task->hidden_details)
        <div class="mb-4">
            <p class="text-[10px] text-gray-500 uppercase tracking-wider mb-2">Classified Intel</p>
            <div class="bg-[#1A1A1A] border border-gray-800 rounded-xl p-4 text-center">
                <svg class="w-6 h-6 mx-auto text-gray-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                <p class="text-xs text-gray-500">Classified — Accept mission to unlock</p>
            </div>
        </div>
    @endif

    {{-- Hashtags --}}
    @if($task->hashtags->isNotEmpty())
        <div class="mb-6">
            <p class="text-[10px] text-gray-500 uppercase tracking-wider mb-2">Required Hashtags</p>
            <div class="flex flex-wrap gap-1.5">
                @foreach($task->hashtags as $hashtag)
                    <span class="px-2 py-0.5 text-xs bg-blue-900/40 text-blue-300 border border-blue-700 rounded">{{ $hashtag->hashtag }}</span>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Submission History (if user has submitted) --}}
    @if($application && $application->submissions->isNotEmpty())
        <div class="mb-6">
            <p class="text-[10px] text-gray-500 uppercase tracking-wider mb-2">Your Submissions</p>
            <div class="space-y-2">
                @foreach($application->submissions->sortByDesc('submitted_at') as $submission)
                    <div class="bg-[#1A1A1A] border border-gray-800 rounded-xl p-3 flex items-center justify-between">
                        <div class="flex items-center gap-2 min-w-0">
                            <svg class="w-4 h-4 text-gray-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                            <a href="{{ $submission->proof_url }}" target="_blank" class="text-xs text-blue-300 hover:text-blue-200 truncate transition-colors">{{ $submission->proof_url }}</a>
                        </div>
                        <span class="text-[10px] text-gray-500 shrink-0 ml-2">{{ $submission->submitted_at?->format('d M Y') }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Mission Progress Tracker --}}
    @if($application && in_array($application->status, ['accepted', 'submitted', 'approved', 'paid']))
        @php
            $steps = ['accepted', 'submitted', 'approved', 'paid'];
            $currentStep = array_search($application->status, $steps);
        @endphp
        <div class="mb-6">
            <p class="text-[10px] text-gray-500 uppercase tracking-wider mb-3">Mission Progress</p>
            <div class="flex items-center justify-between relative">
                {{-- Progress line background --}}
                <div class="absolute top-4 left-6 right-6 h-0.5 bg-gray-800"></div>
                {{-- Progress line filled --}}
                <div class="absolute top-4 left-6 h-0.5 bg-neon transition-all" style="width: {{ $currentStep > 0 ? min(100, ($currentStep / (count($steps) - 1)) * 100) : 0 }}%; max-width: calc(100% - 3rem);"></div>

                @foreach($steps as $i => $step)
                    @php
                        $isActive = $i <= $currentStep;
                        $isCurrent = $i === $currentStep;
                        $labels = ['Accepted', 'Submitted', 'Approved', 'Paid'];
                    @endphp
                    <div class="flex flex-col items-center relative z-10" style="width: 25%;">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold border-2 transition-all
                            {{ $isCurrent ? 'bg-neon text-dark border-neon shadow-[0_0_10px_rgba(170,255,0,0.3)]' : ($isActive ? 'bg-neon/20 text-neon border-neon/50' : 'bg-[#1A1A1A] text-gray-600 border-gray-700') }}">
                            @if($isActive && !$isCurrent)
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            @else
                                {{ $i + 1 }}
                            @endif
                        </div>
                        <span class="text-[9px] mt-1.5 uppercase tracking-wider font-bold {{ $isCurrent ? 'text-neon' : ($isActive ? 'text-gray-400' : 'text-gray-600') }}">{{ $labels[$i] }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Action Button --}}
    <div class="mt-6 mb-2">
        @if(!$application)
            {{-- Check if slots full --}}
            @if($task->slots_taken >= $task->slots_total)
                <div class="w-full bg-gray-800 text-gray-500 font-bold py-4 rounded-xl uppercase tracking-wider text-sm text-center">
                    Slots Full
                </div>
            @elseif($task->status !== 'open')
                <div class="w-full bg-gray-800 text-gray-500 font-bold py-4 rounded-xl uppercase tracking-wider text-sm text-center">
                    Mission Closed
                </div>
            @elseif($task->access_level === 'vip_only' && $user->sidekick_level !== 'vip')
                <div class="w-full bg-gray-800 text-gray-500 font-bold py-4 rounded-xl uppercase tracking-wider text-sm text-center flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    VIP Only Mission
                </div>
            @else
                <form method="POST" action="{{ route('agent.tasks.apply', $task) }}">
                    @csrf
                    <button type="submit"
                            class="w-full bg-neon text-dark font-bold py-4 rounded-xl uppercase tracking-wider text-sm hover:bg-neon-dim transition-colors shadow-[0_0_20px_rgba(204,255,0,0.2)]">
                        Accept Mission
                    </button>
                </form>
            @endif
        @elseif($application->status === 'applied')
            <div class="w-full bg-blue-900/40 text-blue-300 border border-blue-700 font-bold py-4 rounded-xl uppercase tracking-wider text-sm text-center flex items-center justify-center gap-2">
                <svg class="w-4 h-4 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Application Pending
            </div>
        @elseif($application->status === 'accepted')
            <form method="POST" action="{{ route('agent.tasks.submit', $task) }}" class="space-y-3">
                @csrf
                <input type="url" name="proof_url" required
                       class="w-full bg-[#1A1A1A] border border-gray-700 rounded-xl px-4 py-3 text-sm text-white placeholder-gray-600 focus:border-neon focus:outline-none"
                       placeholder="Paste your proof link here...">
                @error('proof_url')
                    <p class="text-red-400 text-xs">{{ $message }}</p>
                @enderror
                <button type="submit"
                        class="w-full bg-neon text-dark font-bold py-4 rounded-xl uppercase tracking-wider text-sm hover:bg-neon-dim transition-colors">
                    Submit Intel
                </button>
            </form>
        @elseif($application->status === 'submitted')
            <div class="w-full bg-yellow-900/40 text-yellow-300 border border-yellow-700 font-bold py-4 rounded-xl uppercase tracking-wider text-sm text-center flex items-center justify-center gap-2">
                <svg class="w-4 h-4 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                Under Review
            </div>
        @elseif($application->status === 'approved')
            <div class="w-full bg-[#3D4421] text-neon border border-neon font-bold py-4 rounded-xl uppercase tracking-wider text-sm text-center shadow-[0_0_20px_rgba(204,255,0,0.2)] flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Mission Complete — Awaiting Payout
            </div>
        @elseif($application->status === 'paid')
            <div class="w-full bg-gray-800 text-gray-400 border border-gray-700 font-bold py-4 rounded-xl uppercase tracking-wider text-sm text-center flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Funds Transferred
            </div>
        @elseif($application->status === 'rejected')
            <div class="w-full bg-red-900/40 text-red-300 border border-red-700 font-bold py-4 rounded-xl uppercase tracking-wider text-sm text-center flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                Rejected
            </div>
        @endif
    </div>

    {{-- Applied date info --}}
    @if($application)
        <p class="text-center text-[10px] text-gray-600">
            Applied {{ $application->applied_at?->format('d M Y, h:i A') }}
            @if($application->accepted_at)
                &middot; Accepted {{ $application->accepted_at->format('d M Y') }}
            @endif
        </p>
    @endif
@endsection
