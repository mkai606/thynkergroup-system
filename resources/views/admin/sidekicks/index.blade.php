@extends('layouts.admin')

@section('title', 'SIDEKICK HUB')

@section('content')
    {{-- Roster Table --}}
    <div class="bg-dark-light border border-dark-lighter rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-dark-lighter flex flex-col md:flex-row md:justify-between md:items-center gap-3">
            <h2 class="font-heading text-sm tracking-wider text-gray-400">
                SIDEKICK HUB ROSTER ({{ $sidekicks->count() }})
            </h2>
            <a href="{{ route('admin.sidekicks.export', request()->query()) }}"
               class="px-3 py-1.5 text-xs font-bold uppercase tracking-wider bg-neon/10 text-neon border border-neon/30 rounded-lg hover:bg-neon/20 transition-colors whitespace-nowrap">
                Export CSV
            </a>
            <form method="GET" action="{{ route('admin.sidekicks') }}" id="skFilterForm" class="flex items-center gap-2 flex-wrap">
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif

                {{-- Tier --}}
                <select name="tier" onchange="document.getElementById('skFilterForm').submit()"
                        class="bg-dark border border-dark-lighter rounded-lg px-3 py-1.5 text-xs text-white focus:border-neon focus:outline-none">
                    <option value="All" {{ request('tier', 'All') === 'All' ? 'selected' : '' }}>All Tiers</option>
                    @foreach(['A', 'B', 'C', 'D', 'E'] as $t)
                        <option value="{{ $t }}" {{ request('tier') === $t ? 'selected' : '' }}>Tier {{ $t }}</option>
                    @endforeach
                </select>

                {{-- Level --}}
                <select name="level" onchange="document.getElementById('skFilterForm').submit()"
                        class="bg-dark border border-dark-lighter rounded-lg px-3 py-1.5 text-xs text-white focus:border-neon focus:outline-none">
                    <option value="all" {{ request('level', 'all') === 'all' ? 'selected' : '' }}>All Levels</option>
                    <option value="vip" {{ request('level') === 'vip' ? 'selected' : '' }}>VIP</option>
                    <option value="premium" {{ request('level') === 'premium' ? 'selected' : '' }}>Premium</option>
                </select>

                {{-- Platform --}}
                <select name="platform" onchange="document.getElementById('skFilterForm').submit()"
                        class="bg-dark border border-dark-lighter rounded-lg px-3 py-1.5 text-xs text-white focus:border-neon focus:outline-none">
                    <option value="all" {{ request('platform', 'all') === 'all' ? 'selected' : '' }}>All Platforms</option>
                    <option value="instagram" {{ request('platform') === 'instagram' ? 'selected' : '' }}>Instagram</option>
                    <option value="tiktok" {{ request('platform') === 'tiktok' ? 'selected' : '' }}>TikTok</option>
                    <option value="youtube" {{ request('platform') === 'youtube' ? 'selected' : '' }}>YouTube</option>
                </select>

                {{-- Sort --}}
                <select name="sort" onchange="document.getElementById('skFilterForm').submit()"
                        class="bg-dark border border-dark-lighter rounded-lg px-3 py-1.5 text-xs text-white focus:border-neon focus:outline-none">
                    <option value="total_exp" {{ request('sort', 'total_exp') === 'total_exp' ? 'selected' : '' }}>Sort: Total EXP</option>
                    <option value="success_rate" {{ request('sort') === 'success_rate' ? 'selected' : '' }}>Sort: Success Rate</option>
                    <option value="tier" {{ request('sort') === 'tier' ? 'selected' : '' }}>Sort: Tier</option>
                </select>

                {{-- Flagged --}}
                <label class="flex items-center gap-1.5 cursor-pointer px-3 py-1.5 bg-dark border border-dark-lighter rounded-lg text-xs {{ request('flagged') === '1' ? 'border-danger/50 text-danger' : 'text-gray-400' }}">
                    <input type="checkbox" name="flagged" value="1" {{ request('flagged') === '1' ? 'checked' : '' }}
                           onchange="document.getElementById('skFilterForm').submit()" class="accent-red-500 w-3 h-3">
                    Flagged
                </label>
            </form>
        </div>

        @if($sidekicks->isEmpty())
            <div class="p-16 text-center">
                <p class="text-gray-500 text-sm">No sidekicks found matching search.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-dark-lighter text-xs text-gray-500 uppercase tracking-wider">
                            <th class="text-left px-6 py-3">Sidekick Name</th>
                            <th class="text-left px-6 py-3">Tier</th>
                            <th class="text-left px-6 py-3">Level</th>
                            <th class="text-right px-6 py-3">Success Rate</th>
                            <th class="text-right px-6 py-3">Total EXP</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-dark-lighter">
                        @foreach($sidekicks as $sk)
                            <tr class="hover:bg-dark-lighter/50 transition-colors cursor-pointer" onclick="window.location='{{ route('admin.sidekicks.show', $sk) }}'">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <span class="font-medium text-white">{{ $sk->name }}</span>
                                        @if($sk->verified_badge)
                                            <svg class="w-3.5 h-3.5 text-neon" fill="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        @endif
                                        @if($sk->flagged)
                                            <svg class="w-4 h-4 text-danger" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                        @endif
                                    </div>
                                    <p class="text-xs text-gray-500 font-mono">{{ $sk->handle }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-0.5 text-xs font-bold bg-dark rounded">{{ $sk->tier }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($sk->sidekick_level === 'vip')
                                        <span class="px-2 py-0.5 text-xs font-bold bg-warning/10 text-warning rounded">VIP</span>
                                    @else
                                        <span class="px-2 py-0.5 text-xs font-medium bg-gray-500/10 text-gray-400 rounded">Premium</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="text-gray-400 font-mono">{{ number_format($sk->success_rate ?? 0, 1) }}%</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="text-neon font-bold font-mono">{{ number_format($sk->total_exp) }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
