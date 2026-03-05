@extends('layouts.guest')

@section('title', 'Login')

@section('content')
    <div class="w-full max-w-sm">
        {{-- Logo --}}
        <div class="text-center mb-8">
            <h1 class="font-heading text-4xl tracking-wider">
                <span class="text-text-primary">Thynker</span> <span class="text-neon">Groups</span>
            </h1>
            <p class="text-muted text-sm mt-2 uppercase tracking-widest">Sidekicks Marketing Platform</p>
        </div>

        {{-- Login Card --}}
        <div class="bg-dark-light border border-dark-lighter rounded-xl p-6">
            <h2 class="font-heading text-lg tracking-wide mb-6 text-center">LOGIN</h2>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                {{-- Email --}}
                <div class="mb-4">
                    <label for="email" class="block text-xs text-gray-400 uppercase tracking-wider mb-2">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                           class="w-full bg-dark border border-dark-lighter rounded-lg px-4 py-3 text-sm text-white placeholder-gray-600 focus:border-neon focus:outline-none transition-colors"
                           placeholder="your@email.com">
                    @error('email')
                        <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="mb-4">
                    <label for="password" class="block text-xs text-gray-400 uppercase tracking-wider mb-2">Password</label>
                    <input type="password" id="password" name="password" required
                           class="w-full bg-dark border border-dark-lighter rounded-lg px-4 py-3 text-sm text-white placeholder-gray-600 focus:border-neon focus:outline-none transition-colors"
                           placeholder="Enter password">
                    @error('password')
                        <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Remember Me --}}
                <div class="mb-6 flex items-center">
                    <input type="checkbox" id="remember" name="remember"
                           class="w-4 h-4 rounded border-dark-lighter bg-dark text-neon focus:ring-neon focus:ring-offset-0">
                    <label for="remember" class="ml-2 text-sm text-gray-400">Remember me</label>
                </div>

                {{-- Submit --}}
                <button type="submit"
                        class="w-full bg-neon text-dark font-bold py-3 rounded-lg uppercase tracking-wider text-sm hover:bg-neon-dim transition-colors">
                    Login
                </button>
            </form>
        </div>

        {{-- Footer --}}
        <p class="text-center text-muted/50 text-xs mt-6">
            Thynker Groups &mdash; Sidekicks Marketing v1.0
        </p>
    </div>
@endsection
