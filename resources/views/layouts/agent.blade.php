<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#121212">

    <title>Thynker Groups — @yield('title', 'Agent')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#121212] text-white font-sans antialiased">
    <div class="flex justify-center min-h-screen">
        <div class="w-full max-w-md min-h-screen border-x border-gray-800 relative overflow-hidden">

            {{-- Toast Notification --}}
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-cloak
                     x-init="setTimeout(() => show = false, 3000)"
                     x-transition:leave="transition ease-in duration-300"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-4"
                     class="absolute left-4 right-4 z-50 bg-neon text-dark px-5 py-4 rounded-lg shadow-2xl border-2 border-white flex items-center gap-3" style="top: calc(1.5rem + env(safe-area-inset-top, 0px));">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="text-sm font-bold uppercase">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div x-data="{ show: true }" x-show="show" x-cloak
                     x-init="setTimeout(() => show = false, 3000)"
                     class="absolute left-4 right-4 z-50 bg-danger text-white px-5 py-4 rounded-lg shadow-2xl flex items-center gap-3" style="top: calc(1.5rem + env(safe-area-inset-top, 0px));">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="text-sm font-bold uppercase">{{ session('error') }}</span>
                </div>
            @endif

            {{-- Page Content --}}
            <main class="h-full overflow-y-auto px-5 pb-24" style="padding-top: calc(1.5rem + env(safe-area-inset-top, 0px)); padding-bottom: calc(6rem + env(safe-area-inset-bottom, 0px));">
                @yield('content')
            </main>

            {{-- Bottom Navigation --}}
            @php $currentRoute = Route::currentRouteName(); @endphp
            <nav class="absolute bottom-0 left-0 right-0 bg-[#1A1A1A] border-t border-gray-800 py-4 px-6 z-40" style="padding-bottom: calc(0.75rem + env(safe-area-inset-bottom, 0px));">
                <div class="flex justify-between items-center">
                    <a href="{{ route('agent.dashboard') }}" class="flex flex-col items-center space-y-1 transition-colors {{ $currentRoute === 'agent.dashboard' ? 'text-neon' : 'text-gray-500 hover:text-gray-300' }}">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="{{ $currentRoute === 'agent.dashboard' ? '2.5' : '2' }}"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        <span class="text-[9px] font-bold uppercase tracking-widest">Feed</span>
                    </a>

                    <a href="{{ route('agent.ops') }}" class="flex flex-col items-center space-y-1 transition-colors {{ $currentRoute === 'agent.ops' ? 'text-neon' : 'text-gray-500 hover:text-gray-300' }}">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="{{ $currentRoute === 'agent.ops' ? '2.5' : '2' }}"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <span class="text-[9px] font-bold uppercase tracking-widest">Ops</span>
                    </a>

                    <a href="{{ route('agent.wallet') }}" class="flex flex-col items-center space-y-1 transition-colors {{ $currentRoute === 'agent.wallet' ? 'text-neon' : 'text-gray-500 hover:text-gray-300' }}">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="{{ $currentRoute === 'agent.wallet' ? '2.5' : '2' }}"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        <span class="text-[9px] font-bold uppercase tracking-widest">Funds</span>
                    </a>

                    <a href="{{ route('agent.leaderboard') }}" class="flex flex-col items-center space-y-1 transition-colors {{ $currentRoute === 'agent.leaderboard' ? 'text-neon' : 'text-gray-500 hover:text-gray-300' }}">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="{{ $currentRoute === 'agent.leaderboard' ? '2.5' : '2' }}"><path stroke-linecap="round" stroke-linejoin="round" d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/></svg>
                        <span class="text-[9px] font-bold uppercase tracking-widest">Rank</span>
                    </a>

                    <a href="{{ route('agent.profile') }}" class="flex flex-col items-center space-y-1 transition-colors {{ $currentRoute === 'agent.profile' ? 'text-neon' : 'text-gray-500 hover:text-gray-300' }}">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="{{ $currentRoute === 'agent.profile' ? '2.5' : '2' }}"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        <span class="text-[9px] font-bold uppercase tracking-widest">ID</span>
                    </a>
                </div>
            </nav>
        </div>
    </div>
</body>
</html>
