@extends('layouts.agent')

@section('title', 'My Ops')

@section('content')
    {{-- Header --}}
    <h1 class="font-heading text-4xl tracking-wide mb-6">MY OPS</h1>

    {{-- Active Ops --}}
    @if($activeApps->isEmpty())
        <div class="bg-[#262626] border border-gray-700 rounded-xl p-8 text-center mb-6">
            <svg class="w-12 h-12 mx-auto text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            <p class="text-gray-500 text-sm mb-2">No active operations</p>
            <a href="{{ route('agent.dashboard') }}" class="text-neon text-sm font-bold uppercase tracking-wider">Initiate New Task</a>
        </div>
    @else
        <div class="space-y-3 mb-6">
            @foreach($activeApps as $app)
                @php
                    $task = $app->task;
                    $statusBadges = [
                        'applied' => ['bg-blue-900/40 text-blue-300 border-blue-700', 'Pending'],
                        'accepted' => ['bg-purple-900/40 text-purple-300 border-purple-700', 'In Progress'],
                        'submitted' => ['bg-yellow-900/40 text-yellow-300 border-yellow-700', 'Under Review'],
                    ];
                    $badge = $statusBadges[$app->status] ?? ['bg-gray-800 text-gray-400 border-gray-700', ucfirst($app->status)];

                    $platformColors = [
                        'Instagram' => 'text-pink-400',
                        'TikTok' => 'text-white',
                        'Facebook' => 'text-blue-400',
                        'YouTube' => 'text-red-500',
                    ];
                    $platformColor = $platformColors[$task->platform] ?? 'text-gray-400';
                @endphp
                <a href="{{ route('agent.tasks.show', $task) }}" class="block bg-[#262626] border border-gray-700 hover:border-gray-500 rounded-xl p-4 transition-colors active:scale-[0.98]">
                    {{-- Row 1: Platform icon + Task info + Reward --}}
                    <div class="flex items-start justify-between">
                        <div class="flex items-start gap-3">
                            <div class="bg-[#1A1A1A] p-2 rounded-lg border border-gray-800">
                                @if($task->platform === 'Instagram')
                                    <svg class="w-5 h-5 {{ $platformColor }}" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                                @elseif($task->platform === 'TikTok')
                                    <span class="w-5 h-5 flex items-center justify-center text-xs font-bold text-white">T</span>
                                @elseif($task->platform === 'Facebook')
                                    <svg class="w-5 h-5 {{ $platformColor }}" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                @elseif($task->platform === 'YouTube')
                                    <svg class="w-5 h-5 {{ $platformColor }}" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                                @else
                                    <svg class="w-5 h-5 {{ $platformColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                @endif
                            </div>
                            <div>
                                <p class="text-sm text-white font-bold uppercase">{{ $task->title }}</p>
                                <p class="text-[10px] text-gray-400">{{ $task->campaign?->brand ?? '' }}</p>
                            </div>
                        </div>
                        <span class="font-heading text-xl text-neon">RM{{ number_format($task->reward_amount, 0) }}</span>
                    </div>

                    {{-- Row 2: Status + Meta --}}
                    <div class="flex items-center justify-between mt-3 pt-3 border-t border-gray-700/50">
                        <div class="flex items-center gap-3">
                            <span class="px-2 py-0.5 rounded text-[10px] uppercase font-bold tracking-wider border {{ $badge[0] }}">{{ $badge[1] }}</span>
                            <span class="px-2 py-0.5 rounded text-[10px] uppercase font-bold tracking-wider
                                @if($task->platform === 'Instagram') bg-pink-900/40 text-pink-300 border border-pink-700
                                @elseif($task->platform === 'TikTok') bg-gray-700 text-white border border-gray-600
                                @elseif($task->platform === 'Facebook') bg-blue-900/40 text-blue-300 border border-blue-700
                                @elseif($task->platform === 'YouTube') bg-red-900/40 text-red-400 border border-red-700
                                @else bg-gray-800 text-gray-300 border border-gray-700
                                @endif">{{ $task->platform }}</span>
                        </div>
                        <div class="flex items-center gap-3 text-[10px] text-gray-500">
                            @if($task->deadline)
                                <div class="flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ $task->deadline->format('d M') }}
                                </div>
                            @endif
                            <div class="flex items-center gap-1 text-neon">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3h14l-1.5 6.5a5.5 5.5 0 01-11 0L5 3zm7 13v4m-4 0h8"/></svg>
                                {{ $task->exp_reward }}
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif

    {{-- Mission Log --}}
    @if($historyApps->isNotEmpty())
        <div class="flex items-center gap-2 mb-4">
            <div class="h-4 w-1 bg-gray-600"></div>
            <h3 class="font-heading text-xl tracking-wide text-gray-400">MISSION LOG</h3>
        </div>

        <div class="space-y-3">
            @foreach($historyApps as $app)
                @php
                    $task = $app->task;
                    $statusBadges = [
                        'approved' => ['bg-green-900/40 text-green-300 border-green-700', 'Approved'],
                        'paid' => ['bg-gray-800 text-gray-400 border-gray-700', 'Settled'],
                        'rejected' => ['bg-red-900/40 text-red-300 border-red-700', 'Rejected'],
                    ];
                    $badge = $statusBadges[$app->status] ?? ['bg-gray-800 text-gray-400 border-gray-700', ucfirst($app->status)];
                @endphp
                <a href="{{ route('agent.tasks.show', $task) }}" class="block bg-[#1A1A1A] border border-gray-800 hover:border-gray-700 rounded-xl p-4 transition-colors opacity-80 hover:opacity-100 active:scale-[0.98]">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm text-white font-bold uppercase">{{ $task->title }}</p>
                            <div class="flex items-center gap-2 mt-1.5">
                                <span class="px-2 py-0.5 rounded text-[10px] uppercase font-bold tracking-wider border {{ $badge[0] }}">{{ $badge[1] }}</span>
                                <span class="px-2 py-0.5 rounded text-[10px] uppercase font-bold tracking-wider
                                    @if($task->platform === 'Instagram') bg-pink-900/40 text-pink-300 border border-pink-700
                                    @elseif($task->platform === 'TikTok') bg-gray-700 text-white border border-gray-600
                                    @elseif($task->platform === 'Facebook') bg-blue-900/40 text-blue-300 border border-blue-700
                                    @elseif($task->platform === 'YouTube') bg-red-900/40 text-red-400 border border-red-700
                                    @else bg-gray-800 text-gray-300 border border-gray-700
                                    @endif">{{ $task->platform }}</span>
                                <span class="text-[10px] text-gray-600">{{ $app->applied_at?->format('d M Y') }}</span>
                            </div>
                        </div>
                        <span class="font-heading text-lg {{ $app->status === 'rejected' ? 'text-gray-600 line-through' : 'text-neon' }}">RM{{ number_format($task->reward_amount, 0) }}</span>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
@endsection
