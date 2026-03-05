@extends('layouts.admin')

@section('title', 'MISSION BRIEF')

@section('content')
    {{-- Back Button --}}
    <a href="{{ route('admin.campaigns') }}" class="inline-flex items-center gap-1 text-sm text-gray-400 hover:text-neon transition-colors mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Command
    </a>

    {{-- Campaign Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <div class="flex items-center gap-3">
                <h2 class="font-heading text-3xl tracking-wide">{{ $campaign->title }}</h2>
                <span class="px-2 py-1 text-xs font-medium rounded
                    {{ $campaign->status === 'active' ? 'bg-success/10 text-success' : 'bg-gray-500/10 text-gray-500' }}">
                    {{ ucfirst($campaign->status) }}
                </span>
            </div>
            <p class="text-gray-400 mt-1">
                {{ $campaign->brand }}
                @if($task)
                    &middot; <span class="text-info">{{ $task->platform }}</span>
                    &middot; Tier {{ $task->min_followers >= 10000 ? 'A' : ($task->min_followers >= 5000 ? 'B' : ($task->min_followers >= 3000 ? 'C' : ($task->min_followers >= 2000 ? 'D' : 'E'))) }}
                @endif
            </p>
        </div>
        <div class="text-right">
            <p class="text-3xl font-bold text-neon">RM {{ number_format($campaign->total_budget, 2) }}</p>
            <p class="text-xs text-gray-500 uppercase tracking-wider">Total Liability</p>
        </div>
    </div>

    {{-- Stat Cards --}}
    @if($task)
        @php
            $participation = $task->slots_total > 0 ? round(($task->slots_taken / $task->slots_total) * 100) : 0;
            $applicationCount = $task->applications->count();
            $pendingCount = $task->applications->where('status', 'applied')->count();
            $remaining = $campaign->total_budget - $campaign->spent_budget;
        @endphp
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            {{-- Participation --}}
            <div class="bg-dark-light border border-dark-lighter rounded-xl p-5">
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-2">Participation</p>
                <p class="text-2xl font-bold">
                    <span class="text-neon">{{ $task->slots_taken }}</span>
                    <span class="text-gray-500">/</span>
                    <span class="text-white">{{ $task->slots_total }}</span>
                </p>
                <div class="mt-2 h-1.5 bg-dark rounded-full overflow-hidden">
                    <div class="h-full bg-neon rounded-full transition-all" style="width: {{ $participation }}%"></div>
                </div>
                <p class="text-xs text-gray-500 mt-1">{{ $participation }}% FILLED</p>
            </div>

            {{-- Applications --}}
            <div class="bg-dark-light border border-dark-lighter rounded-xl p-5">
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-2">Applications</p>
                <p class="text-2xl font-bold text-white">{{ $applicationCount }}</p>
                <p class="text-xs text-warning mt-1">{{ $pendingCount }} PENDING REVIEW</p>
            </div>

            {{-- Budget Burn --}}
            <div class="bg-dark-light border border-dark-lighter rounded-xl p-5">
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-2">Budget Burn</p>
                <p class="text-2xl font-bold text-white">RM {{ number_format($campaign->spent_budget, 2) }}</p>
                <p class="text-xs text-gray-500 mt-1">RM {{ number_format($remaining, 2) }} REMAINING</p>
            </div>

            {{-- Security Protocol --}}
            <div class="bg-dark-light border border-dark-lighter rounded-xl p-5">
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-2">Instructions</p>
                <form method="POST" action="{{ route('admin.campaigns.toggleLock', [$campaign, $task]) }}">
                    @csrf
                    @if($task->instructions_locked)
                        <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium bg-warning/10 text-warning rounded hover:bg-warning/20 transition-colors cursor-pointer">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            Hidden — Click to unlock
                        </button>
                        <p class="text-[10px] text-gray-600 mt-2">Sidekick must be accepted before viewing instructions</p>
                    @else
                        <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium bg-neon/10 text-neon rounded hover:bg-neon/20 transition-colors cursor-pointer">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
                            Public — Click to hide
                        </button>
                        <p class="text-[10px] text-gray-600 mt-2">All sidekicks can view instructions without applying</p>
                    @endif
                </form>
            </div>
        </div>
    @endif

    {{-- Main Content Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left: Applications Table --}}
        <div class="lg:col-span-2">
            <div class="bg-dark-light border border-dark-lighter rounded-xl overflow-hidden">
                <div class="px-6 py-4 border-b border-dark-lighter flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                    <h3 class="font-heading text-sm tracking-wider text-gray-400">SIDEKICK APPLICATIONS</h3>
                    <div class="flex items-center gap-3">
                        {{-- Status Filter --}}
                        <form method="GET" action="{{ route('admin.campaigns.show', $campaign) }}" id="statusFilterForm">
                            <select name="status" onchange="document.getElementById('statusFilterForm').submit()"
                                    class="bg-dark border border-dark-lighter rounded-lg px-3 py-1.5 text-xs text-white focus:border-neon focus:outline-none">
                                <option value="all" {{ request('status', 'all') === 'all' ? 'selected' : '' }}>All Status</option>
                                <option value="applied" {{ request('status') === 'applied' ? 'selected' : '' }}>Pending</option>
                                <option value="accepted" {{ request('status') === 'accepted' ? 'selected' : '' }}>Accepted</option>
                                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </form>

                        {{-- Auto Accept All --}}
                        @if($task && $task->applications->where('status', 'applied')->count() > 0)
                            <form method="POST" action="{{ route('admin.campaigns.autoAccept', $campaign) }}"
                                  x-data
                                  @submit.prevent="if(confirm('Accept all {{ $task->applications->where('status', 'applied')->count() }} pending applications?')) $el.submit()">
                                @csrf
                                <button type="submit"
                                        class="px-3 py-1.5 text-xs font-bold uppercase tracking-wider bg-success/10 text-success border border-success/30 rounded-lg hover:bg-success/20 transition-colors whitespace-nowrap">
                                    Auto Accept All
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                @php
                    $filteredApps = $task ? $task->applications : collect();
                    if(request('status') && request('status') !== 'all') {
                        $filteredApps = $filteredApps->where('status', request('status'));
                    }
                @endphp

                @if($task && $filteredApps->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-dark-lighter text-xs text-gray-500 uppercase tracking-wider">
                                    <th class="text-left px-6 py-3">Sidekick</th>
                                    <th class="text-left px-6 py-3">Tier</th>
                                    <th class="text-left px-6 py-3">Timestamp</th>
                                    <th class="text-left px-6 py-3">Status</th>
                                    <th class="text-right px-6 py-3">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-dark-lighter">
                                @foreach($filteredApps as $application)
                                    <tr class="hover:bg-dark-lighter/50 transition-colors">
                                        <td class="px-6 py-4">
                                            <p class="font-medium text-white">{{ $application->user->name }}</p>
                                            <p class="text-xs text-gray-500 font-mono">{{ $application->user->handle }}</p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-0.5 text-xs font-bold bg-dark rounded">{{ $application->user->tier }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-xs text-gray-400 font-mono">{{ $application->applied_at ? $application->applied_at->format('Y-m-d H:i') : $application->created_at->format('Y-m-d H:i') }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 text-xs font-medium rounded
                                                {{ $application->status === 'accepted' ? 'bg-success/10 text-success' :
                                                   ($application->status === 'rejected' ? 'bg-danger/10 text-danger' :
                                                   'bg-warning/10 text-warning') }}">
                                                {{ ucfirst($application->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            @if($application->status === 'applied')
                                                <div class="flex items-center justify-end gap-2">
                                                    <form method="POST" action="{{ route('admin.campaigns.applications.accept', [$campaign, $application]) }}">
                                                        @csrf
                                                        <button type="submit" class="px-2.5 py-1 text-xs font-bold uppercase bg-success/10 text-success rounded hover:bg-success/20 transition-colors">Accept</button>
                                                    </form>
                                                    <form method="POST" action="{{ route('admin.campaigns.applications.reject', [$campaign, $application]) }}">
                                                        @csrf
                                                        <button type="submit" class="px-2.5 py-1 text-xs font-bold uppercase bg-danger/10 text-danger rounded hover:bg-danger/20 transition-colors">Reject</button>
                                                    </form>
                                                </div>
                                            @else
                                                <span class="text-gray-600">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-12 text-center">
                        <p class="text-gray-500 text-sm">No applications found.</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Right: Task Protocol --}}
        <div class="lg:col-span-1">
            @if($task)
                <div class="bg-dark-light border border-dark-lighter rounded-xl p-6">
                    <div class="flex items-center gap-2 mb-4">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <h3 class="font-heading text-sm tracking-wider text-gray-400">TASK PROTOCOL</h3>
                    </div>

                    {{-- Description --}}
                    <div class="mb-4">
                        <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Scope</p>
                        <p class="text-sm text-gray-300">{{ $task->description }}</p>
                    </div>

                    {{-- Instructions --}}
                    @if($task->instructions->isNotEmpty())
                        <div class="mb-4">
                            <p class="text-xs text-gray-500 uppercase tracking-wider mb-2">Instructions</p>
                            <ol class="space-y-2">
                                @foreach($task->instructions->sortBy('step_no') as $instruction)
                                    <li class="flex gap-2 text-sm">
                                        <span class="text-neon font-mono text-xs mt-0.5">{{ str_pad($instruction->step_no, 2, '0', STR_PAD_LEFT) }}</span>
                                        <span class="text-gray-300">{{ $instruction->instruction }}</span>
                                    </li>
                                @endforeach
                            </ol>
                        </div>
                    @endif

                    {{-- Hashtags --}}
                    @if($task->hashtags->isNotEmpty())
                        <div class="mb-4">
                            <p class="text-xs text-gray-500 uppercase tracking-wider mb-2">Hashtags</p>
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($task->hashtags as $hashtag)
                                    <span class="px-2 py-0.5 text-xs bg-info/10 text-info rounded">{{ $hashtag->hashtag }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Hidden Details --}}
                    @if($task->hidden_details)
                        <div class="mb-4">
                            <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Encrypted Intel</p>
                            <p class="text-sm text-gray-400 font-mono bg-dark rounded p-3">{{ $task->hidden_details }}</p>
                            <p class="text-[10px] text-gray-600 mt-1">Encrypted until acceptance</p>
                        </div>
                    @endif

                    {{-- Task Meta --}}
                    <div class="border-t border-dark-lighter pt-4 mt-4 space-y-2">
                        <div class="flex justify-between text-xs">
                            <span class="text-gray-500">Reward</span>
                            <span class="text-white font-medium">RM {{ number_format($task->reward_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span class="text-gray-500">EXP</span>
                            <span class="text-neon font-medium">{{ $task->exp_reward }} EXP</span>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span class="text-gray-500">Min Followers</span>
                            <span class="text-white font-medium">{{ number_format($task->min_followers) }}</span>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span class="text-gray-500">Deadline</span>
                            <span class="text-white font-medium">{{ $task->deadline ? $task->deadline->format('d M Y') : '—' }}</span>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span class="text-gray-500">Access</span>
                            <span class="font-medium {{ $task->access_level === 'vip_only' ? 'text-neon' : 'text-white' }}">
                                {{ $task->access_level === 'vip_only' ? 'VIP Only' : 'Public' }}
                            </span>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
