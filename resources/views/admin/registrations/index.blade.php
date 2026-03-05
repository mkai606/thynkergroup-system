@extends('layouts.admin')

@section('title', 'RECRUITMENT')

@section('content')
    {{-- Pending Sidekicks Table --}}
    <div class="bg-dark-light border border-dark-lighter rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-dark-lighter flex flex-col md:flex-row md:justify-between md:items-center gap-3">
            <h2 class="font-heading text-sm tracking-wider text-gray-400">PENDING SIDEKICKS ({{ $registrations->count() }})</h2>
            <form method="GET" action="{{ route('admin.registrations') }}" id="filterForm" class="flex items-center gap-3">
                <select name="platform" onchange="document.getElementById('filterForm').submit()"
                        class="bg-dark border border-dark-lighter rounded-lg px-3 py-1.5 text-xs text-white focus:border-neon focus:outline-none">
                    <option value="all" {{ request('platform', 'all') === 'all' ? 'selected' : '' }}>All Platforms</option>
                    @foreach($platforms as $p)
                        <option value="{{ $p }}" {{ request('platform') === $p ? 'selected' : '' }}>{{ ucfirst($p) }}</option>
                    @endforeach
                </select>
                <select name="sort" onchange="document.getElementById('filterForm').submit()"
                        class="bg-dark border border-dark-lighter rounded-lg px-3 py-1.5 text-xs text-white focus:border-neon focus:outline-none">
                    <option value="submitted" {{ request('sort', 'submitted') === 'submitted' ? 'selected' : '' }}>Sort: Submitted</option>
                    <option value="platform" {{ request('sort') === 'platform' ? 'selected' : '' }}>Sort: Platform</option>
                </select>
            </form>
        </div>

        @if($registrations->isEmpty())
            <div class="p-16 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-700 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                <p class="text-gray-500 font-heading tracking-wider">NO PENDING APPLICATIONS</p>
                <p class="text-gray-600 text-sm mt-1">All registrations have been processed.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-dark-lighter text-xs text-gray-500 uppercase tracking-wider">
                            <th class="text-left px-6 py-3">Applicant</th>
                            <th class="text-left px-6 py-3">Contact</th>
                            <th class="text-left px-6 py-3">Platforms</th>
                            <th class="text-left px-6 py-3">Highest Followers</th>
                            <th class="text-left px-6 py-3">Submitted</th>
                            <th class="text-right px-6 py-3">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-dark-lighter">
                        @foreach($registrations as $registration)
                            @php
                                $primary = $registration->socialProfiles->sortByDesc('followers')->first();
                                $highestFollowers = $primary->followers ?? 0;
                            @endphp
                            <tr class="hover:bg-dark-lighter/50 transition-colors">
                                <td class="px-6 py-4">
                                    <p class="font-medium text-white">{{ $registration->full_name }}</p>
                                    @if($primary)
                                        <p class="text-xs text-gray-500 font-mono">{{ $primary->handle }}</p>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-xs text-gray-400 font-mono">{{ $registration->phone }}</p>
                                    <p class="text-xs text-gray-500">{{ $registration->email }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($registration->socialProfiles as $profile)
                                            <span class="px-2 py-0.5 text-[10px] font-medium bg-info/10 text-info rounded">{{ ucfirst($profile->platform) }}</span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-white font-bold">{{ number_format($highestFollowers) }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-xs text-gray-400 font-mono">{{ $registration->submitted_at ? $registration->submitted_at->diffForHumans() : $registration->created_at->diffForHumans() }}</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('admin.registrations.show', $registration) }}"
                                       class="px-3 py-1.5 text-xs font-bold uppercase tracking-wider bg-info/10 text-info rounded hover:bg-info/20 transition-colors">
                                        Review
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
