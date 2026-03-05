@extends('layouts.admin')

@section('title', 'VIP APPROVAL')

@section('content')
    {{-- Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-dark-light border border-dark-lighter rounded-xl p-5">
            <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Pending Requests</p>
            <p class="text-2xl font-bold text-warning">{{ $requests->count() }}</p>
        </div>
        <div class="bg-dark-light border border-dark-lighter rounded-xl p-5">
            <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Payment Submitted</p>
            <p class="text-2xl font-bold text-neon">{{ $requests->where('status', 'payment_submitted')->count() }}</p>
        </div>
        <div class="bg-dark-light border border-dark-lighter rounded-xl p-5">
            <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Eligible (No Payment)</p>
            <p class="text-2xl font-bold text-info">{{ $requests->where('status', 'eligible')->count() }}</p>
        </div>
    </div>

    {{-- VIP Applications Table --}}
    <div class="bg-dark-light border border-dark-lighter rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-dark-lighter flex flex-col md:flex-row md:justify-between md:items-center gap-3">
            <h2 class="font-heading text-sm tracking-wider text-gray-400">VIP APPLICATIONS ({{ $requests->count() }})</h2>
            <form method="GET" action="{{ route('admin.vip') }}" id="vipFilterForm">
                <select name="status" onchange="document.getElementById('vipFilterForm').submit()"
                        class="bg-dark border border-dark-lighter rounded-lg px-3 py-1.5 text-xs text-white focus:border-neon focus:outline-none">
                    <option value="all" {{ request('status', 'all') === 'all' ? 'selected' : '' }}>All Status</option>
                    <option value="payment_submitted" {{ request('status') === 'payment_submitted' ? 'selected' : '' }}>Payment Submitted</option>
                    <option value="eligible" {{ request('status') === 'eligible' ? 'selected' : '' }}>Eligible</option>
                </select>
            </form>
        </div>

        @if($requests->isEmpty())
            <div class="p-16 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-700 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3l3.057-3 11.943 12-11.943 12L5 21l9-9-9-9z"/></svg>
                <p class="text-gray-500 font-heading tracking-wider">NO VIP REQUESTS</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-dark-lighter text-xs text-gray-500 uppercase tracking-wider">
                            <th class="text-left px-6 py-3">Sidekick</th>
                            <th class="text-left px-6 py-3">Status</th>
                            <th class="text-left px-6 py-3">Campaigns</th>
                            <th class="text-left px-6 py-3">Referrals</th>
                            <th class="text-left px-6 py-3">Receipt</th>
                            <th class="text-right px-6 py-3">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-dark-lighter">
                        @foreach($requests as $vip)
                            <tr class="hover:bg-dark-lighter/50 transition-colors">
                                <td class="px-6 py-4">
                                    <p class="font-medium text-white">{{ $vip->user->name }}</p>
                                    <p class="text-xs text-gray-500 font-mono">{{ $vip->user->handle }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs font-medium bg-warning/10 text-warning rounded">
                                        {{ str_replace('_', ' ', ucfirst($vip->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-mono {{ ($vip->user->completed_tasks ?? 0) >= 5 ? 'text-success' : 'text-gray-400' }}">
                                        {{ $vip->user->completed_tasks ?? 0 }}/5
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-mono {{ ($vip->user->referral_count ?? 0) >= 5 ? 'text-success' : 'text-gray-400' }}">
                                        {{ $vip->user->referral_count ?? 0 }}/5
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($vip->receipt_url)
                                        <svg class="w-4 h-4 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    @else
                                        <svg class="w-4 h-4 text-danger" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('admin.vip.show', $vip) }}"
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
