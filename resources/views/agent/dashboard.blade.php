@extends('layouts.agent')

@section('title', 'Task Feed')

@section('content')
    {{-- Header --}}
    <div class="flex items-center justify-between mb-5" x-data>
        <div>
            <p class="text-xs text-neon font-bold uppercase tracking-widest">Thynker Groups</p>
            <h1 class="font-heading text-3xl tracking-wide">{{ strtoupper($user->name) }}</h1>
        </div>
        <div class="flex items-center gap-2">
            {{-- Broadcasts --}}
            <button @click="$dispatch('open-broadcasts')" class="relative w-10 h-10 bg-[#262626] rounded-full border border-gray-700 flex items-center justify-center text-gray-400 hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                @if($broadcasts->isNotEmpty())
                    <span class="absolute -top-0.5 -right-0.5 w-3 h-3 bg-neon rounded-full"></span>
                @endif
            </button>

            {{-- Notifications --}}
            <button @click="$dispatch('open-notifications')" class="relative w-10 h-10 bg-[#262626] rounded-full border border-gray-700 flex items-center justify-center text-gray-400 hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                @if($unreadCount > 0)
                    <span class="absolute -top-0.5 -right-0.5 min-w-[18px] h-[18px] bg-danger rounded-full flex items-center justify-center text-[9px] text-white font-bold px-1">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
                @endif
            </button>
        </div>
    </div>

    {{-- Quick Stats --}}
    <div class="bg-[#262626] border border-gray-700 rounded-xl p-4 mb-5">
        <div class="flex items-center justify-between">
            <div class="flex-1 text-center">
                <p class="font-heading text-2xl text-neon">{{ number_format($user->monthly_exp) }}</p>
                <p class="text-[9px] text-gray-500 uppercase tracking-widest font-bold mt-0.5">EXP</p>
            </div>
            <div class="w-px h-10 bg-gray-700"></div>
            <div class="flex-1 text-center">
                <p class="font-heading text-2xl text-white">#{{ $user->rank_position ?? '-' }}</p>
                <p class="text-[9px] text-gray-500 uppercase tracking-widest font-bold mt-0.5">Rank</p>
            </div>
            <div class="w-px h-10 bg-gray-700"></div>
            <div class="flex-1 text-center">
                <p class="font-heading text-2xl text-white">{{ $activeMissions }}</p>
                <p class="text-[9px] text-gray-500 uppercase tracking-widest font-bold mt-0.5">Active</p>
            </div>
            <div class="w-px h-10 bg-gray-700"></div>
            <div class="flex-1 text-center">
                <p class="font-heading text-2xl text-neon">RM{{ number_format($walletBalance) }}</p>
                <p class="text-[9px] text-gray-500 uppercase tracking-widest font-bold mt-0.5">Balance</p>
            </div>
        </div>
    </div>

    {{-- Featured Op --}}
    @if($featuredTask)
        <a href="{{ route('agent.tasks.show', $featuredTask) }}" class="block mb-5 active:scale-[0.98] transition-transform">
            <div class="bg-[#3D4421] border border-neon rounded-xl overflow-hidden relative">
                <svg class="absolute top-4 right-4 w-24 h-24 text-neon opacity-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                <div class="bg-neon text-dark px-4 py-2 flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider">Featured Op</span>
                    <span class="text-xs font-bold">{{ $featuredTask->exp_reward }} EXP</span>
                </div>
                <div class="p-5">
                    <p class="font-heading text-4xl text-neon">RM {{ number_format($featuredTask->reward_amount, 0) }}</p>
                    <div class="h-px bg-neon/30 my-3"></div>
                    <p class="text-xl font-bold uppercase">{{ $featuredTask->title }}</p>
                    <p class="text-sm text-neon mt-1">{{ $featuredTask->campaign?->brand ?? '' }}</p>
                    <div class="flex items-center gap-3 mt-2">
                        <div class="flex items-center gap-1 text-xs text-gray-400">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>{{ $featuredTask->deadline ? $featuredTask->deadline->format('d M Y') : 'No deadline' }}</span>
                        </div>
                        <span class="text-[10px] text-gray-500 uppercase font-bold">{{ $featuredTask->platform }}</span>
                    </div>
                </div>
            </div>
        </a>
    @endif

    {{-- Platform Filter --}}
    <div class="flex items-center gap-2 mb-4">
        <div class="h-4 w-1 bg-neon"></div>
        <h3 class="font-heading text-xl tracking-wide flex-1">AVAILABLE OPS</h3>
    </div>

    <div class="flex gap-2 mb-4 overflow-x-auto pb-1">
        <a href="{{ route('agent.dashboard') }}"
           class="px-3 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-wider border whitespace-nowrap transition-colors {{ !$platform ? 'bg-neon text-dark border-neon' : 'bg-[#1A1A1A] text-gray-400 border-gray-700 hover:border-gray-500' }}">
            All
        </a>
        @foreach(['Instagram', 'TikTok', 'YouTube', 'Facebook'] as $p)
            <a href="{{ route('agent.dashboard', ['platform' => $p]) }}"
               class="px-3 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-wider border whitespace-nowrap transition-colors {{ $platform === $p ? 'bg-neon text-dark border-neon' : 'bg-[#1A1A1A] text-gray-400 border-gray-700 hover:border-gray-500' }}">
                {{ $p }}
            </a>
        @endforeach
    </div>

    @if($tasks->isEmpty() && !$featuredTask)
        <div class="bg-[#262626] border border-gray-700 rounded-xl p-8 text-center">
            <svg class="w-12 h-12 mx-auto text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
            <p class="text-gray-500 text-sm">No ops available right now.</p>
        </div>
    @else
        <div class="space-y-3">
            @foreach($tasks as $task)
                @php
                    $appStatus = $applicationStatuses[$task->id] ?? null;
                    $isVipLocked = $task->access_level === 'vip_only' && $user->sidekick_level !== 'vip';
                    $isCompleted = in_array($appStatus, ['approved', 'paid']);

                    $platformColors = [
                        'Instagram' => 'text-pink-400',
                        'TikTok' => 'text-white',
                        'Facebook' => 'text-blue-400',
                        'YouTube' => 'text-red-500',
                    ];
                    $platformColor = $platformColors[$task->platform] ?? 'text-gray-400';

                    $statusBadges = [
                        'applied' => ['bg-blue-900/40 text-blue-300 border border-blue-700', 'Applied'],
                        'accepted' => ['bg-purple-900/40 text-purple-300 border border-purple-700', 'In Progress'],
                        'submitted' => ['bg-yellow-900/40 text-yellow-300 border border-yellow-700', 'Under Review'],
                        'approved' => ['bg-green-900/40 text-green-300 border border-green-700', 'Action Required'],
                        'paid' => ['bg-gray-800 text-gray-400 border border-gray-700', 'Settled'],
                        'rejected' => ['bg-red-900/40 text-red-300 border border-red-700', 'Rejected'],
                    ];
                @endphp

                <a href="{{ route('agent.tasks.show', $task) }}"
                   class="block {{ $isCompleted ? 'bg-[#121212]' : 'bg-[#262626]' }} border {{ $isCompleted ? 'border-gray-800' : 'border-gray-700 hover:border-gray-500' }} rounded-xl p-4 transition-colors relative overflow-hidden active:scale-[0.98] transition-transform">

                    @if($isVipLocked)
                        <div class="absolute inset-0 bg-[#121212]/60 z-10"></div>
                    @endif

                    <div class="flex justify-between items-start">
                        <div class="flex items-start gap-3">
                            <div class="bg-[#1A1A1A] p-2 rounded-lg border border-gray-800">
                                @if($task->platform === 'Instagram')
                                    <svg class="w-5 h-5 {{ $platformColor }}" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                                @elseif($task->platform === 'TikTok')
                                    <span class="w-5 h-5 flex items-center justify-center text-xs font-bold text-white">T</span>
                                @elseif($task->platform === 'Facebook')
                                    <svg class="w-5 h-5 {{ $platformColor }}" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                @elseif($task->platform === 'YouTube')
                                    <svg class="w-5 h-5 {{ $platformColor }}" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                                @else
                                    <svg class="w-5 h-5 {{ $platformColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                @endif
                            </div>
                            <div>
                                <p class="text-sm text-white uppercase font-bold">{{ $task->title }}</p>
                                <p class="text-[10px] text-gray-400 uppercase">{{ $task->campaign->brand ?? '' }}</p>
                                @if($task->access_level === 'vip_only')
                                    <div class="flex items-center gap-1 mt-1">
                                        <svg class="w-3 h-3 text-neon" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/></svg>
                                        <span class="text-[10px] text-neon font-bold uppercase">VIP</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <span class="font-heading text-xl {{ $isCompleted ? 'text-gray-600' : 'text-neon' }}">RM{{ number_format($task->reward_amount, 0) }}</span>
                    </div>

                    <div class="flex justify-between items-center mt-4">
                        <div class="flex items-center gap-4">
                            <div class="flex items-center gap-1 text-[10px] text-neon">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3h14l-1.5 6.5a5.5 5.5 0 01-11 0L5 3zm7 13v4m-4 0h8"/></svg>
                                {{ $task->exp_reward }} EXP
                            </div>
                            <div class="flex items-center gap-1 text-[10px] text-gray-500">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                {{ $task->slots_total - $task->slots_taken }} Slots
                            </div>
                            <span class="text-[10px] text-gray-600 uppercase font-bold">{{ $task->platform }}</span>
                        </div>
                        <div class="relative z-20">
                            @if($appStatus && isset($statusBadges[$appStatus]))
                                <span class="px-2 py-0.5 rounded text-[10px] uppercase font-bold tracking-wider {{ $statusBadges[$appStatus][0] }}">
                                    {{ $statusBadges[$appStatus][1] }}
                                </span>
                            @elseif($isVipLocked)
                                <div class="flex items-center gap-1 text-[10px] text-gray-500">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                    VIP Only
                                </div>
                            @else
                                <span class="px-3 py-1 bg-white text-[#1A1A1A] rounded-full text-[10px] font-bold uppercase tracking-wider">View Intel</span>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif

    {{-- Broadcasts Slide-up Panel --}}
    <div x-data="{ open: false }" @open-broadcasts.window="open = true" x-cloak>
        <div x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-black/60 z-50" @click="open = false"></div>

        <div x-show="open" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0" x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="translate-y-0" x-transition:leave-end="translate-y-full"
             class="fixed bottom-0 left-0 right-0 z-50 flex justify-center">
            <div class="w-full max-w-md bg-[#1A1A1A] border-t border-gray-700 rounded-t-2xl max-h-[70vh] overflow-y-auto" style="padding-bottom: calc(1rem + env(safe-area-inset-bottom, 0px));">
                <div class="sticky top-0 bg-[#1A1A1A] px-5 pt-4 pb-3 border-b border-gray-800">
                    <div class="w-10 h-1 bg-gray-600 rounded-full mx-auto mb-3"></div>
                    <div class="flex items-center justify-between">
                        <h3 class="font-heading text-lg tracking-wide">HQ BROADCASTS</h3>
                        <button @click="open = false" class="text-gray-500 hover:text-white">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>

                <div class="px-5 py-3 space-y-3" x-data="{ activeBroadcast: null }">
                    @forelse($broadcasts as $broadcast)
                        @php
                            $senderColors = [
                                'Mentor' => 'bg-neon/20 text-neon',
                                'Admin' => 'bg-blue-900/30 text-blue-400',
                                'HQ' => 'bg-purple-900/30 text-purple-400',
                                'Community' => 'bg-pink-900/30 text-pink-400',
                            ];
                            $senderColor = $senderColors[$broadcast->sender_type] ?? 'bg-gray-800 text-gray-400';
                        @endphp
                        <div @click="activeBroadcast = {{ $broadcast->id }}" class="bg-[#262626] border border-gray-700 rounded-xl p-4 cursor-pointer hover:border-gray-500 active:scale-[0.98] transition-all">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="px-2 py-0.5 {{ $senderColor }} rounded text-[9px] font-bold uppercase">{{ $broadcast->sender_type }}</span>
                                <span class="text-[10px] text-gray-500">{{ $broadcast->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-sm text-gray-300">{{ $broadcast->message }}</p>
                            <p class="text-[10px] text-gray-600 mt-2">— {{ $broadcast->sender }}</p>
                        </div>

                        {{-- Individual Broadcast Pop-out --}}
                        <template x-teleport="body">
                            <div x-show="activeBroadcast === {{ $broadcast->id }}" x-cloak
                                 x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                                 class="fixed inset-0 z-[60] flex items-center justify-center px-6">
                                <div class="absolute inset-0 bg-black/80" @click="activeBroadcast = null"></div>
                                <div class="relative w-full max-w-sm bg-[#1A1A1A] border border-neon/30 rounded-2xl overflow-hidden shadow-[0_0_30px_rgba(170,255,0,0.1)]">
                                    <div class="bg-neon px-4 py-2.5 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                                        <span class="text-xs font-bold text-dark uppercase tracking-wider">Incoming Transmission</span>
                                    </div>
                                    <div class="p-5">
                                        <div class="flex items-center gap-2 mb-3">
                                            <span class="px-2 py-0.5 {{ $senderColor }} rounded text-[9px] font-bold uppercase">{{ $broadcast->sender_type }}</span>
                                            <span class="text-[10px] text-gray-500 font-mono">{{ $broadcast->created_at->format('d M Y, H:i') }}</span>
                                        </div>
                                        <p class="text-sm text-gray-200 leading-relaxed mb-3">{{ $broadcast->message }}</p>
                                        <p class="text-[10px] text-gray-500">— {{ $broadcast->sender }}</p>
                                    </div>
                                    <div class="px-5 pb-4">
                                        <button @click="activeBroadcast = null"
                                                class="w-full py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider bg-neon text-dark hover:bg-neon-dim transition-colors">
                                            Acknowledge
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>
                    @empty
                        <div class="text-center py-8">
                            <svg class="w-10 h-10 mx-auto text-gray-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                            <p class="text-gray-500 text-sm">No broadcasts yet.</p>
                        </div>
                    @endforelse

                    @if($broadcasts->isNotEmpty())
                        <div class="text-center py-4">
                            <p class="text-[10px] text-gray-600 uppercase tracking-widest font-mono">— End of Transmission —</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Notifications Slide-up Panel --}}
    <div x-data="{ open: false }" @open-notifications.window="open = true; markRead()" x-cloak>
        <div x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-black/60 z-50" @click="open = false"></div>

        <div x-show="open" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0" x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="translate-y-0" x-transition:leave-end="translate-y-full"
             class="fixed bottom-0 left-0 right-0 z-50 flex justify-center">
            <div class="w-full max-w-md bg-[#1A1A1A] border-t border-gray-700 rounded-t-2xl max-h-[70vh] overflow-y-auto" style="padding-bottom: calc(1rem + env(safe-area-inset-bottom, 0px));">
                <div class="sticky top-0 bg-[#1A1A1A] px-5 pt-4 pb-3 border-b border-gray-800 z-10">
                    <div class="w-10 h-1 bg-gray-600 rounded-full mx-auto mb-3"></div>
                    <div class="flex items-center justify-between">
                        <h3 class="font-heading text-lg tracking-wide">NOTIFICATIONS</h3>
                        <button @click="open = false" class="text-gray-500 hover:text-white">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>

                <div class="px-5 py-3 space-y-2">
                    @forelse($notifications as $notif)
                        @php
                            $typeIcons = [
                                'task_accepted' => ['bg-green-900/30 text-green-400', '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
                                'task_rejected' => ['bg-red-900/30 text-red-400', '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
                                'submission_approved' => ['bg-neon/20 text-neon', '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3h14l-1.5 6.5a5.5 5.5 0 01-11 0L5 3zm7 13v4m-4 0h8"/>'],
                                'submission_rejected' => ['bg-red-900/30 text-red-400', '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
                                'payment' => ['bg-neon/20 text-neon', '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
                                'broadcast' => ['bg-blue-900/30 text-blue-400', '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>'],
                            ];
                            $iconData = $typeIcons[$notif->type] ?? ['bg-gray-800 text-gray-400', '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>'];
                        @endphp
                        <div class="flex items-start gap-3 p-3 rounded-xl {{ !$notif->is_read ? 'bg-[#262626] border border-gray-700' : 'bg-[#1A1A1A]' }}">
                            <div class="w-9 h-9 rounded-full {{ $iconData[0] }} flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $iconData[1] !!}</svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm {{ !$notif->is_read ? 'text-white' : 'text-gray-400' }}">{{ $notif->message }}</p>
                                <p class="text-[10px] text-gray-600 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                            </div>
                            @if(!$notif->is_read)
                                <div class="w-2 h-2 bg-neon rounded-full flex-shrink-0 mt-1.5"></div>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <svg class="w-10 h-10 mx-auto text-gray-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            <p class="text-gray-500 text-sm">No notifications yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <script>
    function markRead() {
        fetch('{{ route("agent.notifications.read") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            }
        });
    }
    </script>
@endsection
