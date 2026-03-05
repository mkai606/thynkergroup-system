@extends('layouts.admin')

@section('title', 'PROCESS PAYOUT')

@section('content')
    @php $agent = $payout->user; @endphp

    {{-- Back --}}
    <a href="{{ route('admin.payouts') }}" class="inline-flex items-center gap-1 text-sm text-gray-400 hover:text-neon transition-colors mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Payouts
    </a>

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4 mb-6">
        <div>
            <h2 class="font-heading text-3xl tracking-wide">PROCESS PAYOUT</h2>
            <p class="text-gray-400 mt-1">
                <span class="text-neon font-bold">RM {{ number_format($payout->amount, 2) }}</span>
                to <span class="text-info">{{ $agent->name }}</span>
                via {{ $payout->provider }}
            </p>
        </div>
        <div>
            @php
                $statusColors = [
                    'requested' => 'bg-warning/10 text-warning border-warning/30',
                    'processing' => 'bg-info/10 text-info border-info/30',
                    'completed' => 'bg-success/10 text-success border-success/30',
                    'rejected' => 'bg-danger/10 text-danger border-danger/30',
                ];
            @endphp
            <span class="px-4 py-2 text-sm font-bold uppercase tracking-wider rounded-full border {{ $statusColors[$payout->status] ?? '' }}">
                {{ ucfirst($payout->status) }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Left: Agent TNG QR --}}
        <div>
            <div class="bg-dark-light border border-dark-lighter rounded-xl p-6">
                <h3 class="font-heading text-sm tracking-wider text-gray-400 mb-4">AGENT TNG QR CODE</h3>

                @if($agent->tng_qr_url)
                    <div class="bg-white rounded-lg p-4 mb-4">
                        <img src="{{ asset('storage/' . $agent->tng_qr_url) }}" alt="TNG QR" class="w-full max-h-[400px] object-contain mx-auto">
                    </div>
                    <p class="text-xs text-gray-500 text-center">Scan this QR to transfer RM {{ number_format($payout->amount, 2) }} to agent</p>
                @else
                    <div class="bg-dark rounded-lg p-8 text-center">
                        <svg class="w-12 h-12 mx-auto text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4.5c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                        <p class="text-danger text-sm font-bold">No QR uploaded by agent</p>
                        <p class="text-gray-500 text-xs mt-1">Agent needs to upload TNG QR on their profile</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Right: Agent Info + Actions --}}
        <div class="space-y-6">
            {{-- Agent Info --}}
            <div class="bg-dark-light border border-dark-lighter rounded-xl p-6">
                <h3 class="font-heading text-sm tracking-wider text-gray-400 mb-4">AGENT DETAILS</h3>
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Name</span>
                        <span class="text-white font-medium">{{ $agent->name }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Handle</span>
                        <span class="text-white font-mono">{{ $agent->handle }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Email</span>
                        <span class="text-white">{{ $agent->email }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Tier</span>
                        <span class="px-2 py-0.5 text-xs font-bold bg-dark rounded">{{ $agent->tier }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Level</span>
                        <span class="font-medium {{ $agent->sidekick_level === 'vip' ? 'text-neon' : 'text-white' }}">{{ strtoupper($agent->sidekick_level) }}</span>
                    </div>
                </div>
            </div>

            {{-- Payout Details --}}
            <div class="bg-dark-light border border-dark-lighter rounded-xl p-6">
                <h3 class="font-heading text-sm tracking-wider text-gray-400 mb-4">PAYOUT DETAILS</h3>
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Amount</span>
                        <span class="text-neon font-bold text-lg">RM {{ number_format($payout->amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Provider</span>
                        <span class="text-white font-medium">{{ $payout->provider }} eWallet</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Requested</span>
                        <span class="text-white font-mono text-xs">{{ $payout->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    @if($payout->transaction)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Processed</span>
                            <span class="text-white font-mono text-xs">{{ $payout->transaction->processed_at?->format('d M Y, H:i') }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Reference</span>
                            <span class="text-white font-mono text-xs">{{ $payout->transaction->provider_ref }}</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Actions --}}
            @if($payout->status === 'requested')
                <div class="flex gap-3">
                    <form method="POST" action="{{ route('admin.payouts.reject', $payout) }}" class="flex-1"
                          x-data @submit.prevent="if(confirm('Reject and refund RM{{ number_format($payout->amount, 2) }} to wallet?')) $el.submit()">
                        @csrf
                        <button type="submit" class="w-full px-6 py-3 text-sm font-bold uppercase tracking-wider border border-danger/40 text-danger rounded-xl hover:bg-danger/10 transition-colors">
                            Reject & Refund
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.payouts.process', $payout) }}" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full px-6 py-3 text-sm font-bold uppercase tracking-wider bg-info/20 text-info border border-info/30 rounded-xl hover:bg-info/30 transition-colors">
                            Mark Processing
                        </button>
                    </form>
                </div>
            @elseif($payout->status === 'processing')
                <div class="flex gap-3">
                    <form method="POST" action="{{ route('admin.payouts.reject', $payout) }}" class="flex-1"
                          x-data @submit.prevent="if(confirm('Reject and refund RM{{ number_format($payout->amount, 2) }} to wallet?')) $el.submit()">
                        @csrf
                        <button type="submit" class="w-full px-6 py-3 text-sm font-bold uppercase tracking-wider border border-danger/40 text-danger rounded-xl hover:bg-danger/10 transition-colors">
                            Reject & Refund
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.payouts.complete', $payout) }}" class="flex-1"
                          x-data @submit.prevent="if(confirm('Confirm payment of RM{{ number_format($payout->amount, 2) }} completed?')) $el.submit()">
                        @csrf
                        <button type="submit" class="w-full px-6 py-3 text-sm font-bold uppercase tracking-wider bg-neon text-dark rounded-xl hover:bg-neon-dim transition-colors">
                            Mark Completed
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
@endsection
