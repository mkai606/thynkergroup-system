@extends('layouts.agent')

@section('title', 'Earnings')

@section('content')
    {{-- Header --}}
    <h1 class="font-heading text-4xl tracking-wide mb-5">EARNINGS</h1>

    {{-- Summary Card --}}
    <div class="bg-[#262626] border border-gray-700 rounded-xl p-5 mb-5">
        <div class="flex items-center justify-between">
            <div class="flex-1 text-center">
                <p class="font-heading text-2xl text-neon">RM {{ number_format($totalPaid, 0) }}</p>
                <p class="text-[9px] text-gray-500 uppercase tracking-widest font-bold mt-0.5">Total Paid</p>
            </div>
            <div class="w-px h-10 bg-gray-700"></div>
            <div class="flex-1 text-center">
                <p class="font-heading text-2xl text-yellow-400">RM {{ number_format($totalPending, 0) }}</p>
                <p class="text-[9px] text-gray-500 uppercase tracking-widest font-bold mt-0.5">Pending</p>
            </div>
            <div class="w-px h-10 bg-gray-700"></div>
            <div class="flex-1 text-center">
                <p class="font-heading text-2xl text-white">{{ number_format($user->total_exp) }}</p>
                <p class="text-[9px] text-neon uppercase tracking-widest font-bold mt-0.5">Total EXP</p>
            </div>
        </div>
    </div>

    {{-- TNG QR Reminder --}}
    @if(!$user->tng_qr_url)
        <a href="{{ route('agent.profile') }}" class="block bg-yellow-900/20 border border-yellow-700/50 rounded-xl p-4 mb-5 hover:border-yellow-600 transition-colors">
            <div class="flex items-center gap-3">
                <svg class="w-8 h-8 text-yellow-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4.5c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                <div>
                    <p class="text-sm text-yellow-300 font-bold">Upload TNG QR Code</p>
                    <p class="text-[10px] text-gray-400">Required for admin to process your payouts. Go to Profile to upload.</p>
                </div>
            </div>
        </a>
    @endif

    {{-- Payout Requests --}}
    @if($payouts->isNotEmpty())
        <div class="flex items-center gap-2 mb-4">
            <div class="h-4 w-1 bg-neon"></div>
            <h3 class="font-heading text-xl tracking-wide">PAYOUTS</h3>
        </div>

        <div class="space-y-2 mb-6">
            @foreach($payouts as $payout)
                @php
                    $statusConfig = [
                        'requested' => ['bg-yellow-900/40 text-yellow-300 border-yellow-700', 'Pending', 'animate-pulse'],
                        'processing' => ['bg-blue-900/40 text-blue-300 border-blue-700', 'Processing', 'animate-pulse'],
                        'completed' => ['bg-green-900/40 text-green-300 border-green-700', 'Paid', ''],
                        'rejected' => ['bg-red-900/40 text-red-300 border-red-700', 'Rejected', ''],
                    ];
                    $config = $statusConfig[$payout->status] ?? ['bg-gray-800 text-gray-400 border-gray-700', ucfirst($payout->status), ''];
                @endphp
                <div class="bg-[#1A1A1A] border border-gray-800 rounded-xl px-4 py-3 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full {{ $payout->status === 'completed' ? 'bg-green-900/30 text-green-400' : ($payout->status === 'rejected' ? 'bg-red-900/30 text-red-400' : 'bg-yellow-900/30 text-yellow-400') }} flex items-center justify-center">
                            @if($payout->status === 'completed')
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            @elseif($payout->status === 'rejected')
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            @else
                                <svg class="w-4 h-4 {{ $config[2] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            @endif
                        </div>
                        <div>
                            <p class="text-sm text-white font-bold">TNG Payout</p>
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-0.5 rounded text-[9px] uppercase font-bold tracking-wider border {{ $config[0] }}">{{ $config[1] }}</span>
                                <span class="text-[10px] text-gray-500">{{ $payout->created_at->format('d M Y') }}</span>
                            </div>
                        </div>
                    </div>
                    <span class="font-heading text-lg {{ $payout->status === 'completed' ? 'text-neon' : ($payout->status === 'rejected' ? 'text-gray-600 line-through' : 'text-white') }}">
                        RM{{ number_format($payout->amount, 2) }}
                    </span>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Transaction Ledger --}}
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-2">
            <div class="h-4 w-1 bg-gray-600"></div>
            <h3 class="font-heading text-xl tracking-wide text-gray-400">TRANSACTIONS</h3>
        </div>
    </div>

    {{-- Filter Pills --}}
    <div class="flex gap-2 mb-4">
        <a href="{{ route('agent.wallet') }}"
           class="px-3 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-wider border transition-colors {{ !$filter ? 'bg-neon text-dark border-neon' : 'bg-[#1A1A1A] text-gray-400 border-gray-700 hover:border-gray-500' }}">
            All
        </a>
        <a href="{{ route('agent.wallet', ['filter' => 'credit']) }}"
           class="px-3 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-wider border transition-colors {{ $filter === 'credit' ? 'bg-neon text-dark border-neon' : 'bg-[#1A1A1A] text-gray-400 border-gray-700 hover:border-gray-500' }}">
            Credits
        </a>
        <a href="{{ route('agent.wallet', ['filter' => 'debit']) }}"
           class="px-3 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-wider border transition-colors {{ $filter === 'debit' ? 'bg-neon text-dark border-neon' : 'bg-[#1A1A1A] text-gray-400 border-gray-700 hover:border-gray-500' }}">
            Debits
        </a>
    </div>

    @if($transactions->isEmpty())
        <div class="bg-[#1A1A1A] border border-gray-800 rounded-xl p-10 text-center">
            <div class="w-16 h-16 mx-auto bg-[#262626] rounded-full flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <p class="text-gray-400 text-sm font-bold uppercase mb-1">No transactions yet</p>
            <p class="text-gray-600 text-xs">Complete missions to start earning rewards</p>
        </div>
    @else
        <div class="space-y-2">
            @foreach($transactions as $tx)
                @php $isCredit = $tx->type === 'credit'; @endphp
                <div class="bg-[#1A1A1A] border border-gray-800 rounded-xl px-4 py-3 flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full {{ $isCredit ? 'bg-green-900/30 text-green-400' : 'bg-red-900/30 text-red-400' }} flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            @if($isCredit)
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m6-6H6"/>
                            @else
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 12H6"/>
                            @endif
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm text-white font-bold truncate">{{ $tx->reason }}</p>
                        <p class="text-[10px] text-gray-500">{{ $tx->created_at->format('d M Y, h:i A') }}</p>
                    </div>
                    <span class="font-heading text-lg flex-shrink-0 {{ $isCredit ? 'text-neon' : 'text-red-400' }}">
                        {{ $isCredit ? '+' : '-' }}RM{{ number_format($tx->amount, 2) }}
                    </span>
                </div>
            @endforeach
        </div>
    @endif
@endsection
