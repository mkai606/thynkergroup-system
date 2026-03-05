@extends('layouts.admin')

@section('title', 'VERIFICATION')

@section('content')
    {{-- Status Tabs --}}
    <div class="flex gap-2 mb-6 overflow-x-auto">
        @foreach(['pending' => 'Pending Review', 'approved' => 'Awaiting Payment', 'paid' => 'Paid', 'rejected' => 'Rejected', 'all' => 'All'] as $key => $label)
            <a href="{{ route('admin.approvals', ['status' => $key]) }}"
               class="px-4 py-2 rounded-lg text-xs font-bold uppercase tracking-wider border whitespace-nowrap transition-colors {{ $status === $key ? 'bg-neon text-dark border-neon' : 'bg-dark-light text-gray-400 border-dark-lighter hover:border-gray-500' }}">
                {{ $label }}
                @if($key !== 'all' && ($counts[$key] ?? 0) > 0)
                    <span class="ml-1 px-1.5 py-0.5 rounded-full text-[10px] {{ $status === $key ? 'bg-dark/20' : 'bg-dark-lighter' }}">{{ $counts[$key] }}</span>
                @endif
            </a>
        @endforeach
    </div>

    {{-- Verification Queue Table --}}
    <div class="bg-dark-light border border-dark-lighter rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-dark-lighter flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <h2 class="font-heading text-sm tracking-wider text-gray-400">VERIFICATION QUEUE</h2>
            <div class="flex items-center gap-3">
                {{-- Task Filter --}}
                <form method="GET" action="{{ route('admin.approvals') }}" id="taskFilterForm">
                    <input type="hidden" name="status" value="{{ $status }}">
                    <input type="hidden" name="fraud" value="{{ request('fraud', 'all') }}">
                    <select name="task" onchange="document.getElementById('taskFilterForm').submit()"
                            class="bg-dark border border-dark-lighter rounded-lg px-3 py-1.5 text-xs text-white focus:border-neon focus:outline-none">
                        <option value="all">All Tasks</option>
                        @foreach($tasks as $t)
                            <option value="{{ $t->id }}" {{ request('task') == $t->id ? 'selected' : '' }}>{{ Str::limit($t->title, 25) }}</option>
                        @endforeach
                    </select>
                </form>

                {{-- Fraud Risk Filter --}}
                <form method="GET" action="{{ route('admin.approvals') }}" id="fraudFilterForm">
                    <input type="hidden" name="status" value="{{ $status }}">
                    <input type="hidden" name="task" value="{{ request('task', 'all') }}">
                    <select name="fraud" onchange="document.getElementById('fraudFilterForm').submit()"
                            class="bg-dark border border-dark-lighter rounded-lg px-3 py-1.5 text-xs text-white focus:border-neon focus:outline-none">
                        <option value="all" {{ request('fraud') === 'all' || !request('fraud') ? 'selected' : '' }}>All Risk</option>
                        <option value="High" {{ request('fraud') === 'High' ? 'selected' : '' }}>High Risk</option>
                        <option value="Medium" {{ request('fraud') === 'Medium' ? 'selected' : '' }}>Medium Risk</option>
                        <option value="Low" {{ request('fraud') === 'Low' ? 'selected' : '' }}>Low Risk</option>
                    </select>
                </form>
            </div>
        </div>

        @if($approvals->isEmpty())
            <div class="p-16 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-700 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-gray-500 font-heading tracking-wider">NO ITEMS</p>
                <p class="text-gray-600 text-sm mt-1">Nothing to show for this filter.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-dark-lighter text-xs text-gray-500 uppercase tracking-wider">
                            <th class="text-left px-6 py-3">Sidekick</th>
                            <th class="text-left px-6 py-3">Task</th>
                            <th class="text-left px-6 py-3">Amount</th>
                            <th class="text-left px-6 py-3">Fraud Risk</th>
                            <th class="text-left px-6 py-3">Status</th>
                            <th class="text-right px-6 py-3">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-dark-lighter">
                        @foreach($approvals as $approval)
                            @php
                                $submission = $approval->submission;
                                $application = $submission?->application;
                                $agent = $application?->user;
                                $task = $application?->task;
                                if (!$submission || !$application || !$agent || !$task) continue;
                                $statusColors = [
                                    'pending' => 'bg-warning/10 text-warning',
                                    'approved' => 'bg-info/10 text-info',
                                    'paid' => 'bg-success/10 text-success',
                                    'rejected' => 'bg-danger/10 text-danger',
                                ];
                                $statusLabels = [
                                    'pending' => 'Pending',
                                    'approved' => 'Awaiting Payment',
                                    'paid' => 'Paid',
                                    'rejected' => 'Rejected',
                                ];
                            @endphp
                            <tr class="hover:bg-dark-lighter/50 transition-colors">
                                <td class="px-6 py-4">
                                    <p class="font-medium text-white">{{ $agent->name }}</p>
                                    <p class="text-xs text-gray-500 font-mono">{{ $agent->handle }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-white">{{ $task->title }}</p>
                                    <p class="text-xs text-gray-500">{{ $task->campaign?->title ?? '' }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-neon font-bold">RM {{ number_format($task->reward_amount, 2) }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($approval->fraud_risk && str_contains(strtolower($approval->fraud_risk), 'high'))
                                        <span class="px-2 py-1 text-xs font-medium bg-danger/10 text-danger rounded">{{ $approval->fraud_risk }}</span>
                                    @elseif($approval->fraud_risk && str_contains(strtolower($approval->fraud_risk), 'medium'))
                                        <span class="px-2 py-1 text-xs font-medium bg-warning/10 text-warning rounded">{{ $approval->fraud_risk }}</span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-medium bg-success/10 text-success rounded">{{ $approval->fraud_risk ?? 'Low' }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs font-medium rounded {{ $statusColors[$approval->status] ?? 'bg-gray-500/10 text-gray-500' }}">
                                        {{ $statusLabels[$approval->status] ?? ucfirst($approval->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    @if($approval->status === 'pending')
                                        <a href="{{ route('admin.approvals.show', $approval) }}"
                                           class="px-3 py-1.5 text-xs font-bold uppercase tracking-wider bg-info/10 text-info rounded hover:bg-info/20 transition-colors">
                                            Review
                                        </a>
                                    @elseif($approval->status === 'approved')
                                        <a href="{{ route('admin.approvals.show', $approval) }}"
                                           class="px-3 py-1.5 text-xs font-bold uppercase tracking-wider bg-neon/10 text-neon rounded hover:bg-neon/20 transition-colors">
                                            Pay Now
                                        </a>
                                    @else
                                        <a href="{{ route('admin.approvals.show', $approval) }}"
                                           class="px-3 py-1.5 text-xs font-bold uppercase tracking-wider bg-dark text-gray-400 rounded hover:bg-dark-lighter transition-colors">
                                            View
                                        </a>
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
