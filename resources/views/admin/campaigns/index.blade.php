@extends('layouts.admin')

@section('title', 'CAMPAIGN OPS')

@section('content')
    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-dark-light border border-dark-lighter rounded-xl p-5">
            <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Total Liability</p>
            <p class="text-2xl font-bold text-neon">RM {{ number_format($totalLiability, 2) }}</p>
        </div>
        <div class="bg-dark-light border border-dark-lighter rounded-xl p-5">
            <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Allocated Budget</p>
            <p class="text-2xl font-bold text-white">RM {{ number_format($allocatedBudget, 2) }}</p>
        </div>
        <a href="{{ route('admin.campaigns.create') }}"
           class="bg-dark-light border-2 border-dashed border-dark-lighter rounded-xl p-5 flex items-center justify-center gap-2 hover:border-neon transition-colors group">
            <svg class="w-5 h-5 text-gray-500 group-hover:text-neon transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span class="text-gray-500 group-hover:text-neon font-bold uppercase tracking-wider text-sm transition-colors">Create Mission</span>
        </a>
    </div>

    {{-- Active Operations Table --}}
    <div class="bg-dark-light border border-dark-lighter rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-dark-lighter">
            <h2 class="font-heading text-sm tracking-wider text-gray-400">ACTIVE OPERATIONS</h2>
        </div>

        @if($campaigns->isEmpty())
            <div class="p-12 text-center">
                <p class="text-gray-500 text-sm">No active operations found.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-dark-lighter text-xs text-gray-500 uppercase tracking-wider">
                            <th class="text-left px-6 py-3">Mission Name</th>
                            <th class="text-left px-6 py-3">Platform</th>
                            <th class="text-left px-6 py-3">Reward</th>
                            <th class="text-left px-6 py-3">Cost</th>
                            <th class="text-left px-6 py-3">Status</th>
                            <th class="text-right px-6 py-3">Command</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-dark-lighter">
                        @foreach($campaigns as $campaign)
                            @php
                                $task = $campaign->tasks->first();
                            @endphp
                            <tr class="hover:bg-dark-lighter/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <span class="font-semibold text-white">{{ $campaign->title }}</span>
                                        @if($task && $task->access_level === 'vip_only')
                                            <span class="px-1.5 py-0.5 text-[10px] font-bold uppercase bg-neon/20 text-neon rounded">VIP</span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-gray-500 mt-0.5">{{ $campaign->brand }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    @if($task)
                                        <span class="px-2 py-1 text-xs font-medium bg-info/10 text-info rounded">{{ $task->platform }}</span>
                                    @else
                                        <span class="text-gray-500">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($task)
                                        <div class="flex items-center gap-1 text-neon">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                                            <span class="font-medium">{{ $task->exp_reward }} EXP</span>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-semibold text-white">RM {{ number_format($campaign->spent_budget, 2) }}</p>
                                    <p class="text-xs text-gray-500">MAX {{ number_format($campaign->total_budget, 2) }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs font-medium rounded
                                        {{ $campaign->status === 'active' ? 'bg-success/10 text-success' : 'bg-gray-500/10 text-gray-500' }}">
                                        {{ ucfirst($campaign->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('admin.campaigns.show', $campaign) }}"
                                       class="px-3 py-1.5 text-xs font-bold uppercase tracking-wider bg-info/10 text-info rounded hover:bg-info/20 transition-colors">
                                        Execute
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
