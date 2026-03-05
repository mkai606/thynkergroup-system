@extends('layouts.admin')

@section('title', 'LEADERBOARD')

@section('content')
    <div class="bg-dark-light border border-dark-lighter rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-dark-lighter flex items-center justify-between">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-warning" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/></svg>
                <div>
                    <h2 class="font-heading text-sm tracking-wider text-white">TOP 50 SIDEKICKS</h2>
                    <p class="text-[10px] text-gray-500">Ranked by Monthly EXP</p>
                </div>
            </div>
        </div>

        @if($sidekicks->isEmpty())
            <div class="p-16 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-700 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/></svg>
                <p class="text-gray-500 font-heading tracking-wider">NO RANKINGS YET</p>
                <p class="text-gray-600 text-sm mt-1">Sidekicks will appear here once they earn EXP.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-dark-lighter text-xs text-gray-500 uppercase tracking-wider">
                            <th class="text-left px-6 py-3">Rank</th>
                            <th class="text-left px-6 py-3">Sidekick</th>
                            <th class="text-left px-6 py-3">Level</th>
                            <th class="text-right px-6 py-3">Monthly EXP</th>
                            <th class="text-right px-6 py-3">Success Rate</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-dark-lighter">
                        @foreach($sidekicks as $index => $sidekick)
                            <tr class="{{ $index < 3 ? 'bg-warning/5' : '' }} hover:bg-dark-lighter/50 transition-colors">
                                <td class="px-6 py-4">
                                    <span class="font-heading text-lg {{ $index < 3 ? 'text-neon' : 'text-gray-500' }}">#{{ $index + 1 }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-dark border border-dark-lighter flex items-center justify-center flex-shrink-0">
                                            @if($sidekick->avatar_url)
                                                <img src="{{ $sidekick->avatar_url }}" alt="" class="w-full h-full rounded-full object-cover">
                                            @else
                                                <span class="text-xs font-bold text-gray-500">{{ strtoupper(substr($sidekick->name, 0, 1)) }}</span>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-1.5">
                                                <p class="font-medium text-white">{{ $sidekick->name }}</p>
                                                @if($sidekick->verified_badge)
                                                    <svg class="w-3.5 h-3.5 text-neon" fill="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                @endif
                                            </div>
                                            <p class="text-[10px] text-gray-500 font-mono">{{ $sidekick->handle }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($sidekick->sidekick_level === 'vip')
                                        <span class="px-2 py-0.5 text-xs font-bold bg-warning/10 text-warning rounded">VIP</span>
                                    @else
                                        <span class="px-2 py-0.5 text-xs font-medium bg-gray-500/10 text-gray-400 rounded">Standard</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="text-neon font-mono font-medium">{{ number_format($sidekick->monthly_exp) }}</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="text-gray-400 font-mono">{{ number_format($sidekick->success_rate ?? 0, 1) }}%</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
