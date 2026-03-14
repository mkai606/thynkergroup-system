@extends('layouts.admin')

@section('title', 'HOT LEADS')

@section('content')
    @php
        $statusColors = [
            'new' => 'bg-neon/10 text-neon border-neon/30',
            'contacted' => 'bg-info/10 text-info border-info/30',
            'qualified' => 'bg-warning/10 text-warning border-warning/30',
            'converted' => 'bg-success/10 text-success border-success/30',
            'archived' => 'bg-gray-500/10 text-gray-500 border-gray-500/30',
        ];
    @endphp

    <div class="bg-dark-light border border-dark-lighter rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-dark-lighter flex flex-col md:flex-row md:justify-between md:items-center gap-3">
            <h2 class="font-heading text-sm tracking-wider text-gray-400">
                HOT LEADS ({{ $leads->count() }})
            </h2>
            <form method="GET" action="{{ route('admin.hot-leads') }}" id="leadFilterForm" class="flex items-center gap-2 flex-wrap">
                <select name="status" onchange="document.getElementById('leadFilterForm').submit()"
                        class="bg-dark border border-dark-lighter rounded-lg px-3 py-1.5 text-xs text-white focus:border-neon focus:outline-none">
                    <option value="all" {{ request('status', 'all') === 'all' ? 'selected' : '' }}>All Status</option>
                    <option value="new" {{ request('status') === 'new' ? 'selected' : '' }}>New</option>
                    <option value="contacted" {{ request('status') === 'contacted' ? 'selected' : '' }}>Contacted</option>
                    <option value="qualified" {{ request('status') === 'qualified' ? 'selected' : '' }}>Qualified</option>
                    <option value="converted" {{ request('status') === 'converted' ? 'selected' : '' }}>Converted</option>
                    <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>Archived</option>
                </select>
            </form>
        </div>

        @if($leads->isEmpty())
            <div class="p-16 text-center">
                <p class="text-gray-500 text-sm">No leads found.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-dark-lighter text-xs text-gray-500 uppercase tracking-wider">
                            <th class="text-left px-6 py-3">Brand</th>
                            <th class="text-left px-6 py-3">Contact</th>
                            <th class="text-left px-6 py-3">Category</th>
                            <th class="text-left px-6 py-3">Budget</th>
                            <th class="text-left px-6 py-3">Status</th>
                            <th class="text-right px-6 py-3">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-dark-lighter" x-data="{ open: null }">
                        @foreach($leads as $lead)
                            <tr class="hover:bg-dark-lighter/50 transition-colors cursor-pointer"
                                @click="open = open === {{ $lead->id }} ? null : {{ $lead->id }}">
                                <td class="px-6 py-4">
                                    <span class="font-medium text-white">{{ $lead->brand_name }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div>
                                        <span class="text-white">{{ $lead->name }}</span>
                                        <p class="text-xs text-gray-500">{{ $lead->email }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-400">{{ $lead->product_category ?? '—' }}</td>
                                <td class="px-6 py-4 text-gray-400">{{ $lead->budget_range ?? '—' }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider rounded border {{ $statusColors[$lead->status] ?? $statusColors['new'] }}">
                                        {{ $lead->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right text-gray-500 text-xs">
                                    {{ $lead->created_at->format('d M Y') }}
                                </td>
                            </tr>

                            {{-- Expandable Detail Row --}}
                            <tr x-show="open === {{ $lead->id }}" x-cloak class="bg-dark/50">
                                <td colspan="6" class="px-6 py-4">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-[10px] text-gray-500 uppercase tracking-wider mb-2">Campaign Goal</p>
                                            <p class="text-sm text-gray-300">{{ $lead->campaign_goal ?? 'Not specified' }}</p>

                                            @if($lead->admin_notes)
                                                <p class="text-[10px] text-gray-500 uppercase tracking-wider mt-3 mb-1">Admin Notes</p>
                                                <p class="text-sm text-gray-400">{{ $lead->admin_notes }}</p>
                                            @endif
                                        </div>
                                        <div>
                                            <form method="POST" action="{{ route('admin.hot-leads.updateStatus', $lead) }}" class="space-y-3">
                                                @csrf
                                                <div>
                                                    <label class="block text-[10px] text-gray-500 uppercase tracking-wider mb-1">Update Status</label>
                                                    <select name="status" class="w-full bg-dark border border-dark-lighter rounded-lg px-3 py-2 text-xs text-white focus:border-neon focus:outline-none">
                                                        @foreach(['new', 'contacted', 'qualified', 'converted', 'archived'] as $s)
                                                            <option value="{{ $s }}" {{ $lead->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div>
                                                    <label class="block text-[10px] text-gray-500 uppercase tracking-wider mb-1">Notes</label>
                                                    <textarea name="admin_notes" rows="2" class="w-full bg-dark border border-dark-lighter rounded-lg px-3 py-2 text-xs text-white placeholder-gray-600 focus:border-neon focus:outline-none resize-none" placeholder="Add notes...">{{ $lead->admin_notes }}</textarea>
                                                </div>
                                                <button type="submit" class="px-4 py-1.5 text-xs font-bold uppercase tracking-wider bg-neon text-dark rounded-lg hover:bg-neon-dim transition-colors">
                                                    Update
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
