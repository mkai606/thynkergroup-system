<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Thynker Groups — @yield('title', 'Admin')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        if (localStorage.getItem('theme') === 'light') document.documentElement.classList.add('light');
    </script>
</head>
<body class="bg-dark text-text-primary font-sans antialiased" x-data="{ sidebarOpen: false, dark: !document.documentElement.classList.contains('light') }">
    <div class="flex h-screen overflow-hidden">
        {{-- Mobile Overlay --}}
        <div x-show="sidebarOpen" x-cloak
             class="fixed inset-0 z-40 bg-black/60 lg:hidden"
             @click="sidebarOpen = false"></div>

        {{-- Sidebar --}}
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
               class="fixed inset-y-0 left-0 z-50 w-64 bg-dark-light border-r border-dark-lighter flex-shrink-0 flex flex-col transition-transform duration-200 lg:translate-x-0 lg:static lg:z-auto">
            {{-- Logo --}}
            <div class="h-16 flex items-center justify-between px-6 border-b border-dark-lighter">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-neon rounded flex items-center justify-center shadow-[0_0_12px_rgba(170,255,0,0.3)] flex-shrink-0">
                        <svg class="w-5 h-5 text-dark" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-heading text-xl text-text-primary tracking-wider leading-none">THYNKER GROUPS</span>
                        <span class="text-[10px] font-bold text-neon uppercase tracking-widest leading-none mt-0.5">Admin Command</span>
                    </div>
                </div>
                <button @click="sidebarOpen = false" class="lg:hidden text-gray-400 hover:text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 py-4 px-3 overflow-y-auto" @click.self="sidebarOpen = false">
                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-3 px-3">Mission Control</p>
                <div class="space-y-1 mb-6">
                    <x-admin-nav-link route="admin.dashboard" icon="grid" label="Overview" />
                    <x-admin-nav-link route="admin.campaigns" icon="crosshair" label="Campaign Ops" />
                    <x-admin-nav-link route="admin.approvals" icon="check-circle" label="Approvals" />
                    <x-admin-nav-link route="admin.registrations" icon="user-plus" label="Registrations" />
                    <x-admin-nav-link route="admin.hot-leads" icon="zap" label="Hot Leads" />
                </div>

                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-3 px-3">Network Assets</p>
                <div class="space-y-1 mb-6">
                    <x-admin-nav-link route="admin.sidekicks" icon="users" label="Sidekick Hub" />
                    <x-admin-nav-link route="admin.vip" icon="star" label="VIP Requests" />
                    <x-admin-nav-link route="admin.leaderboard" icon="trophy" label="Leaderboard" />
                    <x-admin-nav-link route="admin.broadcasts" icon="radio" label="Broadcasts" />
                </div>

                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-3 px-3">System</p>
                <div class="space-y-1">
                    <x-admin-nav-link route="admin.intel" icon="bar-chart" label="Intel" />
                    <x-admin-nav-link route="admin.settings" icon="settings" label="Settings" />
                </div>
            </nav>

            {{-- User --}}
            <div class="p-4 border-t border-dark-lighter">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-neon/20 flex items-center justify-center text-neon text-sm font-bold">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium truncate">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500">Admin</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-gray-500 hover:text-neon transition-colors" title="Logout">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        {{-- Main Content --}}
        <div class="flex-1 flex flex-col overflow-hidden">
            {{-- Top Header --}}
            <header class="h-16 bg-dark-light border-b border-dark-lighter flex items-center justify-between px-4 md:px-6 flex-shrink-0">
                <div class="flex items-center gap-3">
                    <button @click="sidebarOpen = true" class="lg:hidden text-gray-400 hover:text-neon transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <h1 class="font-heading text-lg md:text-xl tracking-wide uppercase">@yield('title', 'COMMAND CENTER')</h1>
                </div>
                <div class="flex items-center gap-4">
                    <span class="text-xs text-muted hidden sm:inline">{{ now()->format('d M Y H:i') }}</span>
                    <button @click="dark = !dark; document.documentElement.classList.toggle('light'); localStorage.setItem('theme', dark ? 'dark' : 'light')"
                            class="w-8 h-8 rounded-lg flex items-center justify-center border border-dark-lighter hover:border-neon transition-colors"
                            :title="dark ? 'Switch to light mode' : 'Switch to dark mode'">
                        <svg x-show="dark" class="w-4 h-4 text-neon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        <svg x-show="!dark" x-cloak class="w-4 h-4 text-neon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                    </button>
                </div>
            </header>

            {{-- Page Content --}}
            <main class="flex-1 overflow-y-auto p-4 md:p-6">
                @if(session('success'))
                    <div class="mb-4 px-4 py-3 bg-success/10 border border-success/30 text-success rounded text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 px-4 py-3 bg-danger/10 border border-danger/30 text-danger rounded text-sm">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
