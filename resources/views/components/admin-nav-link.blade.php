@props(['route', 'icon', 'label'])

@php
    $isActive = request()->routeIs($route . '*');
@endphp

<a href="{{ Route::has($route) ? route($route) : '#' }}"
   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all
          {{ $isActive
              ? 'bg-neon/10 text-neon border border-neon/20'
              : 'text-gray-400 hover:text-white hover:bg-dark-lighter' }}">
    <x-icon :name="$icon" class="w-4 h-4" />
    <span class="font-medium">{{ $label }}</span>
</a>
