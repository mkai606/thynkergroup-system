@extends('layouts.admin')

@section('title', 'COMMAND CENTER')

@section('content')
    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        {{-- Active Sidekicks --}}
        <div class="bg-dark-light border border-dark-lighter rounded-2xl p-6 relative overflow-hidden group">
            <div class="absolute right-0 top-0 p-3 opacity-5 group-hover:opacity-10 transition-opacity">
                <svg class="w-20 h-20 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <p class="text-xs text-gray-500 uppercase tracking-widest">Active Sidekicks</p>
            <h3 class="text-4xl font-heading text-white mt-2">{{ number_format($activeSidekicks) }}</h3>
            <div class="mt-4 w-12 h-1 bg-neon rounded-full"></div>
        </div>

        {{-- Recruitment --}}
        <div class="bg-dark-light border border-dark-lighter rounded-2xl p-6 relative overflow-hidden group">
            <div class="absolute right-0 top-0 p-3 opacity-5 group-hover:opacity-10 transition-opacity">
                <svg class="w-20 h-20 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
            </div>
            <p class="text-xs text-gray-500 uppercase tracking-widest">Recruitment</p>
            <h3 class="text-4xl font-heading text-white mt-2">{{ number_format($pendingRecruitment) }}</h3>
            <div class="mt-4 w-12 h-1 bg-warning rounded-full"></div>
        </div>

        {{-- Pending Review --}}
        <div class="bg-dark-light border border-dark-lighter rounded-2xl p-6 relative overflow-hidden group">
            <div class="absolute right-0 top-0 p-3 opacity-5 group-hover:opacity-10 transition-opacity">
                <svg class="w-20 h-20 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <p class="text-xs text-gray-500 uppercase tracking-widest">Pending Review</p>
            <h3 class="text-4xl font-heading text-white mt-2">{{ number_format($pendingReview) }}</h3>
            <div class="mt-4 w-12 h-1 bg-success rounded-full"></div>
        </div>

        {{-- Monthly Liability --}}
        <div class="bg-[#2a2f1a] border border-neon/20 rounded-2xl p-6 relative overflow-hidden group">
            <div class="absolute right-0 top-0 p-3 opacity-10 group-hover:opacity-20 transition-opacity">
                <svg class="w-20 h-20 text-neon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
            </div>
            <p class="text-xs text-neon/70 uppercase tracking-widest">Monthly Liability</p>
            <h3 class="text-4xl font-heading text-neon mt-2">RM {{ number_format($monthlyLiability) }}</h3>
            <div class="mt-4 w-12 h-1 bg-neon rounded-full"></div>
        </div>
    </div>

    {{-- Main Grid: Chart + Threat Monitor --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        {{-- Engagement Velocity Chart --}}
        <div class="lg:col-span-2 bg-dark-light border border-dark-lighter rounded-2xl p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-heading text-sm tracking-wider text-white uppercase">Engagement Velocity</h3>
                <span class="text-xs text-gray-500">Last 7 days</span>
            </div>
            <div class="h-64" x-data="engagementChart()" x-init="init()">
                <canvas x-ref="chart" class="w-full h-full"></canvas>
            </div>
        </div>

        {{-- Threat Monitor --}}
        <div class="bg-dark-light border border-dark-lighter rounded-2xl p-6">
            <h3 class="font-heading text-sm tracking-wider text-white mb-4 flex items-center uppercase">
                <svg class="w-4 h-4 mr-2 text-danger" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                Threat Monitor
            </h3>
            <div class="space-y-3">
                @forelse($threats as $threat)
                    @php
                        $sevLevel = $threat->severity_score >= 70 ? 'high' : ($threat->severity_score >= 40 ? 'medium' : 'low');
                    @endphp
                    <div class="p-4 rounded-lg border text-xs leading-relaxed
                        {{ $sevLevel === 'high' ? 'bg-danger/5 border-danger/20 text-danger' : ($sevLevel === 'medium' ? 'bg-warning/5 border-warning/20 text-warning' : 'bg-info/5 border-info/20 text-info') }}">
                        <span class="font-bold block mb-1 uppercase tracking-wider">{{ strtoupper($threat->type ?? 'WARNING') }} DETECTED</span>
                        {{ $threat->details }}
                        <div class="text-[10px] opacity-60 mt-2 font-mono">{{ $threat->created_at->diffForHumans() }}</div>
                    </div>
                @empty
                    <div class="p-4 rounded-lg border bg-success/5 border-success/20 text-success text-xs">
                        <span class="font-bold block mb-1 uppercase tracking-wider">ALL CLEAR</span>
                        No threats detected. System operating normally.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Quick Stats Row --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-dark-light border border-dark-lighter rounded-xl p-4 text-center">
            <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Active Campaigns</p>
            <p class="text-2xl font-heading text-info">{{ $activeCampaigns }}</p>
        </div>
        <div class="bg-dark-light border border-dark-lighter rounded-xl p-4 text-center">
            <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">VIP Members</p>
            <p class="text-2xl font-heading text-warning">{{ $vipMembers }}</p>
        </div>
        <div class="bg-dark-light border border-dark-lighter rounded-xl p-4 text-center">
            <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Approval Rate</p>
            @php
                $totalApprovals = \App\Models\TaskApproval::count();
                $approvedCount = \App\Models\TaskApproval::whereIn('status', ['approved', 'paid'])->count();
                $approvalRate = $totalApprovals > 0 ? round(($approvedCount / $totalApprovals) * 100) : 0;
            @endphp
            <p class="text-2xl font-heading text-success">{{ $approvalRate }}%</p>
        </div>
        <div class="bg-dark-light border border-dark-lighter rounded-xl p-4 text-center">
            <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Total Paid</p>
            @php
                $totalPaid = \App\Models\PayoutRequest::where('status', 'completed')->sum('amount');
            @endphp
            <p class="text-2xl font-heading text-neon">RM {{ number_format($totalPaid) }}</p>
        </div>
    </div>

    {{-- Recent Activity --}}
    <div class="bg-dark-light border border-dark-lighter rounded-2xl overflow-hidden">
        <div class="px-6 py-4 border-b border-dark-lighter flex items-center justify-between">
            <h3 class="font-heading text-sm tracking-wider text-gray-400 uppercase">Recent Activity</h3>
            <a href="{{ route('admin.approvals') }}" class="text-xs text-neon hover:underline uppercase tracking-wider">View All</a>
        </div>
        @if($recentApprovals->isEmpty())
            <div class="p-8 text-center">
                <p class="text-gray-600 text-sm">No recent activity.</p>
            </div>
        @else
            <div class="divide-y divide-dark-lighter">
                @foreach($recentApprovals as $approval)
                    @php
                        $sub = $approval->submission;
                        $app = $sub?->application;
                        $agent = $app?->user;
                        $task = $app?->task;
                        $statusColors = [
                            'pending' => 'bg-warning/10 text-warning',
                            'approved' => 'bg-info/10 text-info',
                            'paid' => 'bg-success/10 text-success',
                            'rejected' => 'bg-danger/10 text-danger',
                        ];
                    @endphp
                    <div class="px-6 py-4 flex items-center justify-between hover:bg-dark-lighter/50 transition-colors">
                        <div class="flex items-center gap-4">
                            <div class="w-8 h-8 rounded-full bg-neon/10 flex items-center justify-center text-neon text-xs font-bold flex-shrink-0">
                                {{ strtoupper(substr($agent->name ?? '?', 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-sm text-white font-medium">{{ $agent->name ?? 'Unknown' }}</p>
                                <p class="text-xs text-gray-500">{{ $task->title ?? 'Unknown Task' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-neon text-xs font-mono font-bold">RM {{ number_format($task->reward_amount ?? 0, 2) }}</span>
                            <span class="px-2 py-1 text-[10px] font-bold uppercase rounded {{ $statusColors[$approval->status] ?? 'bg-gray-500/10 text-gray-500' }}">
                                {{ $approval->status }}
                            </span>
                            <span class="text-[10px] text-gray-600 font-mono hidden md:inline">{{ $approval->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Chart.js CDN + Alpine Component --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
    <script>
        function engagementChart() {
            return {
                init() {
                    const data = @json($engagementData);
                    const ctx = this.$refs.chart.getContext('2d');

                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: data.map(d => d.day),
                            datasets: [
                                {
                                    label: 'Submissions',
                                    data: data.map(d => d.submissions),
                                    borderColor: '#AAFF00',
                                    backgroundColor: 'rgba(170, 255, 0, 0.1)',
                                    borderWidth: 3,
                                    tension: 0.4,
                                    fill: true,
                                    pointRadius: 4,
                                    pointBackgroundColor: '#080E00',
                                    pointBorderColor: '#AAFF00',
                                    pointBorderWidth: 2,
                                },
                                {
                                    label: 'Applications',
                                    data: data.map(d => d.applications),
                                    borderColor: '#00F0FF',
                                    backgroundColor: 'rgba(0, 240, 255, 0.05)',
                                    borderWidth: 2,
                                    tension: 0.4,
                                    fill: true,
                                    pointRadius: 3,
                                    pointBackgroundColor: '#080E00',
                                    pointBorderColor: '#00F0FF',
                                    pointBorderWidth: 2,
                                },
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: true,
                                    position: 'top',
                                    align: 'end',
                                    labels: {
                                        color: '#666',
                                        font: { size: 10, weight: 'bold' },
                                        usePointStyle: true,
                                        pointStyle: 'circle',
                                        padding: 16,
                                    }
                                },
                            },
                            scales: {
                                x: {
                                    grid: { display: false },
                                    ticks: { color: '#555', font: { size: 10 } },
                                    border: { display: false },
                                },
                                y: {
                                    grid: { color: 'rgba(255,255,255,0.05)', drawBorder: false },
                                    ticks: { color: '#555', font: { size: 10 } },
                                    border: { display: false },
                                    beginAtZero: true,
                                }
                            },
                            interaction: {
                                intersect: false,
                                mode: 'index',
                            },
                        }
                    });
                }
            }
        }
    </script>
@endsection
