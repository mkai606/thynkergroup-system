@extends('layouts.agent')

@section('title', 'Leaderboard')

@section('content')
    {{-- Header --}}
    <h1 class="font-heading text-4xl tracking-wide mb-6">LEADERBOARD</h1>

    {{-- User's Rank Card --}}
    <div class="bg-[#262626] border border-gray-700 rounded-xl p-5 mb-6 relative overflow-hidden">
        <div class="absolute top-4 right-4 w-20 h-20 bg-neon/10 rounded-full blur-xl"></div>
        <div class="flex items-center justify-between relative">
            <div>
                <p class="text-[10px] text-gray-500 uppercase tracking-widest font-bold mb-1">Your Monthly Rank</p>
                <p class="font-heading text-6xl text-neon">#{{ $user->rank_position ?? '-' }}</p>
                <p class="text-xs text-gray-400 mt-1">
                    @if($user->rank_position && $user->rank_position <= 3)
                        Top 3 — Elite Sidekick
                    @elseif($user->rank_position && $user->rank_position <= 10)
                        Top 10 — Rising Star
                    @elseif($user->rank_position && $user->rank_position <= 25)
                        Top 25 — Keep pushing
                    @else
                        Keep earning EXP to climb
                    @endif
                </p>
            </div>
            <div class="text-right">
                <p class="font-heading text-2xl text-white">{{ number_format($user->monthly_exp) }}</p>
                <p class="text-[9px] text-neon font-bold uppercase tracking-widest">Monthly EXP</p>
                <div class="flex items-center gap-2 mt-2 justify-end">
                    <span class="text-[10px] text-gray-500">{{ number_format($user->success_rate ?? 0, 1) }}% Win</span>
                    <span class="text-[10px] text-gray-600">&middot;</span>
                    <span class="text-[10px] text-gray-500">{{ number_format($user->total_exp) }} Total</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Top 3 Podium --}}
    @if($topAgents->count() >= 3)
        <div class="flex items-end justify-center gap-2 mb-6">
            {{-- 2nd Place --}}
            @php $agent2 = $topAgents[1]; @endphp
            <div class="flex flex-col items-center w-1/3">
                <div class="relative mb-2">
                    <div class="w-14 h-14 rounded-full bg-[#262626] border-2 border-gray-500 flex items-center justify-center overflow-hidden">
                        @if($agent2->avatar_url)
                            <img src="{{ asset('storage/' . $agent2->avatar_url) }}" alt="" class="w-full h-full object-cover">
                        @else
                            <span class="text-lg font-bold text-gray-500">{{ strtoupper(substr($agent2->name, 0, 1)) }}</span>
                        @endif
                    </div>
                    @if($agent2->sidekick_level === 'vip')
                        <div class="absolute -bottom-0.5 -right-0.5 w-4 h-4 bg-neon rounded-full flex items-center justify-center">
                            <svg class="w-2.5 h-2.5 text-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="4"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        </div>
                    @endif
                </div>
                <div class="bg-[#1A1A1A] border border-gray-700 rounded-xl w-full p-3 text-center" style="min-height: 90px;">
                    <p class="font-heading text-xl text-gray-400">#2</p>
                    <p class="text-[10px] text-white font-bold uppercase truncate mt-1">{{ $agent2->name }}</p>
                    <p class="text-[9px] text-gray-500 truncate">{{ $agent2->handle }}</p>
                    <p class="font-heading text-sm text-white mt-1">{{ number_format($agent2->monthly_exp) }} <span class="text-[8px] text-neon">EXP</span></p>
                </div>
            </div>

            {{-- 1st Place --}}
            @php $agent1 = $topAgents[0]; @endphp
            <div class="flex flex-col items-center w-1/3 -mt-4">
                <div class="relative mb-2">
                    <div class="w-18 h-18 w-[72px] h-[72px] rounded-full bg-[#262626] border-2 border-neon shadow-[0_0_15px_rgba(204,255,0,0.3)] flex items-center justify-center overflow-hidden">
                        @if($agent1->avatar_url)
                            <img src="{{ asset('storage/' . $agent1->avatar_url) }}" alt="" class="w-full h-full object-cover">
                        @else
                            <span class="text-2xl font-bold text-gray-500">{{ strtoupper(substr($agent1->name, 0, 1)) }}</span>
                        @endif
                    </div>
                    @if($agent1->sidekick_level === 'vip')
                        <div class="absolute -bottom-0.5 -right-0.5 w-4 h-4 bg-neon rounded-full flex items-center justify-center shadow-[0_0_8px_#CCFF00]">
                            <svg class="w-2.5 h-2.5 text-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="4"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        </div>
                    @endif
                    {{-- Crown --}}
                    <div class="absolute -top-3 left-1/2 -translate-x-1/2">
                        <svg class="w-5 h-5 text-neon" fill="currentColor" viewBox="0 0 24 24"><path d="M5 16L3 5l5.5 5L12 4l3.5 6L21 5l-2 11H5z"/></svg>
                    </div>
                </div>
                <div class="bg-[#3D4421]/50 border border-neon/30 rounded-xl w-full p-3 text-center" style="min-height: 100px;">
                    <p class="font-heading text-2xl text-neon">#1</p>
                    <p class="text-[10px] text-white font-bold uppercase truncate mt-1">{{ $agent1->name }}</p>
                    <p class="text-[9px] text-gray-500 truncate">{{ $agent1->handle }}</p>
                    <p class="font-heading text-sm text-neon mt-1">{{ number_format($agent1->monthly_exp) }} <span class="text-[8px]">EXP</span></p>
                </div>
            </div>

            {{-- 3rd Place --}}
            @php $agent3 = $topAgents[2]; @endphp
            <div class="flex flex-col items-center w-1/3">
                <div class="relative mb-2">
                    <div class="w-14 h-14 rounded-full bg-[#262626] border-2 border-orange-700 flex items-center justify-center overflow-hidden">
                        @if($agent3->avatar_url)
                            <img src="{{ asset('storage/' . $agent3->avatar_url) }}" alt="" class="w-full h-full object-cover">
                        @else
                            <span class="text-lg font-bold text-gray-500">{{ strtoupper(substr($agent3->name, 0, 1)) }}</span>
                        @endif
                    </div>
                    @if($agent3->sidekick_level === 'vip')
                        <div class="absolute -bottom-0.5 -right-0.5 w-4 h-4 bg-neon rounded-full flex items-center justify-center">
                            <svg class="w-2.5 h-2.5 text-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="4"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        </div>
                    @endif
                </div>
                <div class="bg-[#1A1A1A] border border-gray-700 rounded-xl w-full p-3 text-center" style="min-height: 90px;">
                    <p class="font-heading text-xl text-orange-400">#3</p>
                    <p class="text-[10px] text-white font-bold uppercase truncate mt-1">{{ $agent3->name }}</p>
                    <p class="text-[9px] text-gray-500 truncate">{{ $agent3->handle }}</p>
                    <p class="font-heading text-sm text-white mt-1">{{ number_format($agent3->monthly_exp) }} <span class="text-[8px] text-neon">EXP</span></p>
                </div>
            </div>
        </div>
    @endif

    {{-- Rest of Top 50 (from #4 onwards) --}}
    @if($topAgents->count() > 3)
        <div class="flex items-center gap-2 mb-4">
            <div class="h-4 w-1 bg-neon"></div>
            <h3 class="font-heading text-xl tracking-wide">TOP 50 AGENTS</h3>
        </div>

        <div class="space-y-2">
            @foreach($topAgents->slice(3) as $index => $agent)
                @php $isCurrentUser = $agent->id === $user->id; @endphp
                <div class="flex items-center justify-between {{ $isCurrentUser ? 'bg-[#3D4421] border-neon' : 'bg-[#1A1A1A] border-gray-800' }} border rounded-xl p-3 transition-colors">
                    <div class="flex items-center gap-3">
                        {{-- Rank --}}
                        <span class="font-heading text-lg w-7 text-center text-gray-600">#{{ $index + 4 }}</span>

                        {{-- Avatar --}}
                        <div class="relative">
                            <div class="w-9 h-9 rounded-full bg-[#262626] border border-gray-700 flex items-center justify-center flex-shrink-0 overflow-hidden">
                                @if($agent->avatar_url)
                                    <img src="{{ asset('storage/' . $agent->avatar_url) }}" alt="" class="w-full h-full object-cover">
                                @else
                                    <span class="text-xs font-bold text-gray-500">{{ strtoupper(substr($agent->name, 0, 1)) }}</span>
                                @endif
                            </div>
                            @if($agent->sidekick_level === 'vip')
                                <div class="absolute -bottom-0.5 -right-0.5 w-3.5 h-3.5 bg-neon rounded-full flex items-center justify-center">
                                    <svg class="w-2 h-2 text-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="4"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                </div>
                            @endif
                        </div>

                        {{-- Name --}}
                        <div>
                            <p class="text-sm text-white font-bold uppercase">
                                {{ $agent->name }}
                                @if($isCurrentUser)
                                    <span class="text-neon">(You)</span>
                                @endif
                            </p>
                            <div class="flex items-center gap-2">
                                <p class="text-[10px] text-gray-500">{{ $agent->handle }}</p>
                                <span class="text-[9px] text-gray-600">Tier {{ $agent->tier }}</span>
                                <span class="text-[9px] text-gray-600">{{ number_format($agent->success_rate ?? 0, 0) }}%</span>
                            </div>
                        </div>
                    </div>

                    {{-- EXP --}}
                    <div class="text-right">
                        <p class="font-heading text-lg text-white">{{ number_format($agent->monthly_exp) }}</p>
                        <p class="text-[9px] text-neon font-bold uppercase tracking-widest">EXP</p>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Show user's position if not in top 50 --}}
    @if(!$topAgents->contains('id', $user->id))
        <div class="flex items-center justify-center py-3">
            <div class="flex gap-1">
                <span class="w-1.5 h-1.5 bg-gray-600 rounded-full"></span>
                <span class="w-1.5 h-1.5 bg-gray-600 rounded-full"></span>
                <span class="w-1.5 h-1.5 bg-gray-600 rounded-full"></span>
            </div>
        </div>
        <div class="flex items-center justify-between bg-[#3D4421] border border-neon rounded-xl p-3">
            <div class="flex items-center gap-3">
                <span class="font-heading text-lg w-7 text-center text-neon">#{{ $user->rank_position ?? '?' }}</span>
                <div class="relative">
                    <div class="w-9 h-9 rounded-full bg-[#262626] border border-gray-700 flex items-center justify-center flex-shrink-0 overflow-hidden">
                        @if($user->avatar_url)
                            <img src="{{ asset('storage/' . $user->avatar_url) }}" alt="" class="w-full h-full object-cover">
                        @else
                            <span class="text-xs font-bold text-gray-500">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                        @endif
                    </div>
                    @if($user->sidekick_level === 'vip')
                        <div class="absolute -bottom-0.5 -right-0.5 w-3.5 h-3.5 bg-neon rounded-full flex items-center justify-center">
                            <svg class="w-2 h-2 text-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="4"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        </div>
                    @endif
                </div>
                <div>
                    <p class="text-sm text-white font-bold uppercase">{{ $user->name }} <span class="text-neon">(You)</span></p>
                    <div class="flex items-center gap-2">
                        <p class="text-[10px] text-gray-500">{{ $user->handle }}</p>
                        <span class="text-[9px] text-gray-600">Tier {{ $user->tier }}</span>
                    </div>
                </div>
            </div>
            <div class="text-right">
                <p class="font-heading text-lg text-white">{{ number_format($user->monthly_exp) }}</p>
                <p class="text-[9px] text-neon font-bold uppercase tracking-widest">EXP</p>
            </div>
        </div>
    @endif
@endsection
