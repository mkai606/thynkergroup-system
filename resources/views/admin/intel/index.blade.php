@extends('layouts.admin')

@section('title', 'INTELLIGENCE')

@section('content')
    <div class="h-[calc(100vh-8rem)] flex flex-col">
        <div class="flex-1 overflow-hidden">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 h-full">
                {{-- Engagement Velocity (2/3) --}}
                <div class="lg:col-span-2 bg-dark-light border border-dark-lighter rounded-2xl p-6 flex flex-col">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="font-bold text-white uppercase tracking-wider">Engagement Velocity</h3>
                        <span class="px-3 py-1 rounded-full text-[10px] uppercase font-bold tracking-wider {{ $weekChange >= 0 ? 'bg-[#3D4421] text-neon border border-neon/50' : 'bg-red-900/40 text-red-400 border border-red-500/50' }}">{{ $weekChange >= 0 ? '+' : '' }}{{ $weekChange }}% vs last week</span>
                    </div>

                    {{-- SVG Line Chart --}}
                    <div class="flex-1 min-h-0 relative mb-4">
                        @php
                            $chartData = $engagementData;
                            $maxVal = max(1, ...array_column($chartData, 'count'));
                            $chartW = 600;
                            $chartH = 280;
                            $padL = 45;
                            $padR = 20;
                            $padT = 20;
                            $padB = 35;
                            $plotW = $chartW - $padL - $padR;
                            $plotH = $chartH - $padT - $padB;
                            $points = [];
                            foreach ($chartData as $i => $d) {
                                $x = $padL + ($i / max(1, count($chartData) - 1)) * $plotW;
                                $y = $padT + $plotH - (($d['count'] / $maxVal) * $plotH);
                                $points[] = ['x' => round($x, 1), 'y' => round($y, 1), 'label' => $d['day'], 'val' => $d['count']];
                            }
                            $polyline = implode(' ', array_map(fn($p) => "{$p['x']},{$p['y']}", $points));
                            // Grid lines (4 horizontal)
                            $gridLines = [];
                            for ($i = 0; $i <= 4; $i++) {
                                $gy = $padT + ($plotH / 4) * $i;
                                $gv = round($maxVal - ($maxVal / 4) * $i);
                                $gridLines[] = ['y' => round($gy, 1), 'val' => $gv];
                            }
                        @endphp
                        <svg viewBox="0 0 {{ $chartW }} {{ $chartH }}" class="w-full h-full" preserveAspectRatio="xMidYMid meet">
                            {{-- Grid lines --}}
                            @foreach($gridLines as $gl)
                                <line x1="{{ $padL }}" y1="{{ $gl['y'] }}" x2="{{ $chartW - $padR }}" y2="{{ $gl['y'] }}" stroke="#333" stroke-dasharray="3 3" />
                                <text x="{{ $padL - 8 }}" y="{{ $gl['y'] + 3 }}" text-anchor="end" fill="#666" font-size="10" font-family="monospace">{{ $gl['val'] }}</text>
                            @endforeach

                            {{-- Line --}}
                            <polyline points="{{ $polyline }}" fill="none" stroke="#CCFF00" stroke-width="3" stroke-linejoin="round" stroke-linecap="round" />

                            {{-- Dots + X labels --}}
                            @foreach($points as $p)
                                <circle cx="{{ $p['x'] }}" cy="{{ $p['y'] }}" r="4" fill="#1A1A1A" stroke="#CCFF00" stroke-width="2" />
                                <text x="{{ $p['x'] }}" y="{{ $chartH - 8 }}" text-anchor="middle" fill="#666" font-size="10">{{ $p['label'] }}</text>
                            @endforeach
                        </svg>
                    </div>

                    {{-- Explanation --}}
                    <p class="text-[11px] text-gray-500 leading-relaxed">Total task applications received per day of the week. Tracks how active sidekicks are in applying for campaigns — higher peaks indicate days with more engagement.</p>
                </div>

                {{-- Threat Monitor (1/3) --}}
                <div class="bg-dark-light border border-dark-lighter rounded-2xl p-6 overflow-auto">
                    <h3 class="font-bold text-white mb-4 flex items-center uppercase tracking-wider">
                        <svg class="w-[18px] h-[18px] mr-2 text-danger" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.618 5.984A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        Threat Monitor
                    </h3>

                    <div class="space-y-3">
                        @forelse($alerts as $alert)
                            <div class="p-4 rounded border text-xs leading-relaxed
                                @if($alert['type'] === 'error') bg-red-900/10 border-red-900/30 text-red-400
                                @elseif($alert['type'] === 'warning') bg-yellow-900/10 border-yellow-900/30 text-yellow-600
                                @else bg-yellow-900/10 border-yellow-900/30 text-yellow-600
                                @endif
                            ">
                                <span class="font-bold block mb-1 uppercase tracking-wider">
                                    {{ strtoupper($alert['type']) }} DETECTED
                                </span>
                                {{ $alert['message'] }}
                                <div class="flex items-center justify-between mt-2">
                                    <span class="text-[10px] opacity-60 font-mono">{{ $alert['time'] }}</span>
                                    @if(!empty($alert['link']))
                                        <a href="{{ $alert['link'] }}" class="text-[10px] font-bold uppercase tracking-wider text-neon hover:underline">View</a>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <svg class="w-8 h-8 mx-auto text-success/50 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                <p class="text-xs text-gray-600 uppercase tracking-wider">All clear — no threats detected</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
