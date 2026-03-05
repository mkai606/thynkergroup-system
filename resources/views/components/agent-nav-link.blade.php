@props(['route', 'icon', 'label'])

@php
    $isActive = request()->routeIs($route . '*');
@endphp

<a href="{{ Route::has($route) ? route($route) : '#' }}"
   class="flex flex-col items-center gap-1 px-3 py-1 text-xs transition-colors
          {{ $isActive ? 'text-neon' : 'text-gray-500 hover:text-gray-300' }}">
    <x-icon :name="$icon" class="w-5 h-5" />
    <span>{{ $label }}</span>
</a>
