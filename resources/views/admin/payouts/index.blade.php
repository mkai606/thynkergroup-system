@extends('layouts.admin')

@section('title', 'PAYMENT MANAGEMENT')

@section('content')
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <h2 class="font-heading text-3xl tracking-wide">PAYMENT MANAGEMENT</h2>
    </div>

    {{-- Status Tabs --}}
    <div class="flex gap-2 mb-6 overflow-x-auto">
        @foreach(['requested' => 'Pending', 'processing' => 'Processing', 'completed' => 'Completed', 'rejected' => 'Rejected', 'all' => 'All'] as $key => $label)
            <a href="{{ route('admin.payouts', ['status' => $key]) }}"
               class="px-4 py-2 rounded-lg text-xs font-bold uppercase tracking-wider border whitespace-nowrap transition-colors {{ $status === $key ? 'bg-neon text-dark border-neon' : 'bg-dark-light text-gray-400 border-dark-lighter hover:border-gray-500' }}">
                {{ $label }}
                @if($key !== 'all' && ($counts[$key] ?? 0) > 0)
                    <span class="ml-1 px-1.5 py-0.5 rounded-full text-[10px] {{ $status === $key ? 'bg-dark/20' : 'bg-dark-lighter' }}">{{ $counts[$key] }}</span>
                @endif
            </a>
        @endforeach
    </div>

    {{-- Payouts Table --}}
    <div class="bg-dark-light border border-dark-lighter rounded-xl overflow-hidden">
        @if($payouts->isEmpty())
            <div class="p-12 text-center">
                <p class="text-gray-500 text-sm">No payout requests found.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-dark-lighter text-xs text-gray-500 uppercase tracking-wider">
                            <th class="text-left px-6 py-3">Agent</th>
                            <th class="text-left px-6 py-3">Amount</th>
                            <th class="text-left px-6 py-3">Provider</th>
                            <th class="text-left px-6 py-3">QR</th>
                            <th class="text-left px-6 py-3">Status</th>
                            <th class="text-left px-6 py-3">Requested</th>
                            <th class="text-right px-6 py-3">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-dark-lighter">
                        @foreach($payouts as $payout)
                            <tr class="hover:bg-dark-lighter/50 transition-colors">
                                <td class="px-6 py-4">
                                    <p class="font-medium text-white">{{ $payout->user->name }}</p>
                                    <p class="text-xs text-gray-500 font-mono">{{ $payout->user->handle }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-neon font-bold">RM {{ number_format($payout->amount, 2) }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs font-medium bg-info/10 text-info rounded">{{ $payout->provider }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($payout->user->tng_qr_url)
                                        <a href="{{ route('admin.payouts.show', $payout) }}" class="px-2 py-1 text-xs font-medium bg-success/10 text-success rounded">View QR</a>
                                    @else
                                        <span class="px-2 py-1 text-xs font-medium bg-danger/10 text-danger rounded">No QR</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusColors = [
                                            'requested' => 'bg-warning/10 text-warning',
                                            'processing' => 'bg-info/10 text-info',
                                            'completed' => 'bg-success/10 text-success',
                                            'rejected' => 'bg-danger/10 text-danger',
                                        ];
                                    @endphp
                                    <span class="px-2 py-1 text-xs font-medium rounded {{ $statusColors[$payout->status] ?? 'bg-gray-500/10 text-gray-500' }}">
                                        {{ ucfirst($payout->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-xs text-gray-400 font-mono">{{ $payout->created_at->format('d M Y H:i') }}</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    @if($payout->status === 'requested')
                                        <a href="{{ route('admin.payouts.show', $payout) }}" class="px-3 py-1.5 text-xs font-bold uppercase bg-neon/10 text-neon rounded hover:bg-neon/20 transition-colors">
                                            Process
                                        </a>
                                    @elseif($payout->status === 'processing')
                                        <a href="{{ route('admin.payouts.show', $payout) }}" class="px-3 py-1.5 text-xs font-bold uppercase bg-info/10 text-info rounded hover:bg-info/20 transition-colors">
                                            Complete
                                        </a>
                                    @else
                                        <span class="text-gray-600">—</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
