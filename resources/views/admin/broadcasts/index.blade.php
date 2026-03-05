@extends('layouts.admin')

@section('title', 'BROADCASTS')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 h-[calc(100vh-8rem)]">
        {{-- Left: New Transmission Form --}}
        <div class="bg-dark-light border border-dark-lighter rounded-2xl flex flex-col overflow-hidden">
            <div class="p-6 border-b border-dark-lighter">
                <h3 class="font-heading text-xl tracking-wide">NEW TRANSMISSION</h3>
                <p class="text-gray-500 text-sm mt-1">Broadcast messages to Sidekicks</p>
            </div>

            <form method="POST" action="{{ route('admin.broadcasts.store') }}" class="p-6 flex flex-col flex-1" x-data="{ schedule: 'now' }">
                @csrf

                {{-- Sender Identity & Target Audience --}}
                <div class="grid grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-xs text-gray-500 uppercase tracking-widest mb-2">Sender Identity</label>
                        <select name="sender_type" class="w-full bg-dark border border-dark-lighter rounded-lg px-4 py-3 text-white text-sm focus:border-neon focus:outline-none">
                            <option value="Admin HQ">Admin HQ</option>
                            <option value="System Admin">System Admin</option>
                            <option value="Sidekick Mentor">Sidekick Mentor</option>
                            <option value="Community Mgr">Community Mgr</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 uppercase tracking-widest mb-2">Target Audience</label>
                        <select name="audience" class="w-full bg-dark border border-dark-lighter rounded-lg px-4 py-3 text-white text-sm focus:border-neon focus:outline-none">
                            <option value="all">All Sidekicks</option>
                            <option value="vip">VIP Only</option>
                            <option value="premium">Premium Only</option>
                            <option value="tier_a">Tier A Only</option>
                        </select>
                    </div>
                </div>

                {{-- Sender Name (auto-set based on type) --}}
                <input type="hidden" name="sender" x-ref="senderName" value="ADMIN HQ">

                {{-- Message --}}
                <div class="mb-6">
                    <label class="block text-xs text-gray-500 uppercase tracking-widest mb-2">Message Content</label>
                    <textarea name="message" rows="4" placeholder="Enter broadcast message..." class="w-full bg-dark border border-dark-lighter rounded-lg px-4 py-3 text-white text-sm focus:border-neon focus:outline-none resize-none" required></textarea>
                </div>

                {{-- Deployment Schedule --}}
                <div class="mb-6">
                    <label class="block text-xs text-gray-500 uppercase tracking-widest mb-2">Deployment Schedule</label>
                    <div class="border border-dark-lighter rounded-xl p-4 bg-dark space-y-3">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="radio" name="schedule_type" value="now" checked x-model="schedule" class="accent-neon">
                            <span class="text-sm text-white">Send Now</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="radio" name="schedule_type" value="schedule" x-model="schedule" class="accent-neon">
                            <span class="text-sm text-white">Schedule</span>
                        </label>
                        <div x-show="schedule === 'schedule'" x-cloak class="ml-7">
                            <input type="datetime-local" name="scheduled_at" class="bg-dark-light border border-dark-lighter rounded-lg px-4 py-2 text-white text-sm focus:border-neon focus:outline-none">
                        </div>
                    </div>
                </div>

                <div class="mt-auto">
                    <button type="submit" class="w-full bg-neon text-dark font-bold uppercase tracking-wider py-3 rounded-xl hover:bg-neon-dim transition-all hover:scale-[1.01] shadow-lg flex items-center justify-center gap-2">
                        <template x-if="schedule === 'now'">
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                BROADCAST NOW
                            </span>
                        </template>
                        <template x-if="schedule === 'schedule'">
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                SCHEDULE TRANSMISSION
                            </span>
                        </template>
                    </button>
                </div>
            </form>
        </div>

        {{-- Right: HQ Broadcasts Feed --}}
        <div class="bg-[#111] border border-white/10 rounded-2xl flex flex-col overflow-hidden">
            {{-- Feed Header --}}
            <div class="p-6 border-b border-dark-lighter bg-[#1A1A1A] flex items-center gap-3">
                <svg class="w-6 h-6 text-neon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                <div>
                    <h3 class="font-bold text-white uppercase text-lg">HQ Broadcasts</h3>
                    <p class="text-gray-400 text-[10px] uppercase tracking-widest">Official Comms & Motivation</p>
                </div>
            </div>

            {{-- Feed Messages --}}
            <div class="flex-1 overflow-y-auto p-4 space-y-4">
                @forelse($broadcasts as $broadcast)
                    <div>
                        {{-- Meta --}}
                        <div class="flex items-center justify-between mb-1">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full border border-white/20 bg-[#1A1A1A] flex items-center justify-center">
                                    @if($broadcast->sender_type === 'Sidekick Mentor')
                                        <svg class="w-3.5 h-3.5 text-neon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                    @elseif($broadcast->sender_type === 'System Admin')
                                        <svg class="w-3.5 h-3.5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                    @elseif($broadcast->sender_type === 'Admin HQ')
                                        <svg class="w-3.5 h-3.5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                                    @elseif($broadcast->sender_type === 'Community Mgr')
                                        <svg class="w-3.5 h-3.5 text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                    @else
                                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.636 18.364a9 9 0 010-12.728m12.728 0a9 9 0 010 12.728m-9.9-2.829a5 5 0 010-7.07m7.072 0a5 5 0 010 7.07M13 12a1 1 0 11-2 0 1 1 0 012 0z"/></svg>
                                    @endif
                                </div>
                                <span class="text-[10px] uppercase tracking-wider font-bold
                                    @if($broadcast->sender_type === 'Sidekick Mentor') text-neon
                                    @elseif($broadcast->sender_type === 'System Admin' || $broadcast->sender_type === 'Admin HQ') text-blue-400
                                    @elseif($broadcast->sender_type === 'Community Mgr') text-pink-400
                                    @else text-gray-400
                                    @endif
                                ">{{ strtoupper($broadcast->sender_type) }}</span>
                            </div>
                            <span class="text-[9px] text-gray-600 font-mono uppercase">{{ $broadcast->created_at->diffForHumans() }}</span>
                        </div>

                        {{-- Message Bubble --}}
                        <div class="ml-3 mt-1 p-4 rounded-xl border bg-[#1A1A1A] shadow-lg
                            {{ $broadcast->sender_type === 'Community Mgr' ? 'border-pink-500/30' : 'border-neon/30' }}
                        ">
                            <p class="text-sm text-gray-200 leading-relaxed">{{ $broadcast->message }}</p>
                            <div class="flex items-center gap-2 mt-2">
                                <span class="text-[9px] text-gray-600 uppercase tracking-wider">{{ ucfirst($broadcast->audience) }}</span>
                                <span class="text-[9px] text-gray-700">&middot;</span>
                                @if($broadcast->status === 'scheduled')
                                    <span class="text-[9px] text-warning uppercase tracking-wider">Scheduled {{ $broadcast->scheduled_at?->format('d M, h:i A') ?? '—' }}</span>
                                @else
                                    <span class="text-[9px] text-success uppercase tracking-wider">Sent</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="flex items-center justify-center h-full text-gray-600">
                        <div class="text-center">
                            <svg class="w-10 h-10 mx-auto mb-2 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.636 18.364a9 9 0 010-12.728m12.728 0a9 9 0 010 12.728m-9.9-2.829a5 5 0 010-7.07m7.072 0a5 5 0 010 7.07M13 12a1 1 0 11-2 0 1 1 0 012 0z"/></svg>
                            <p class="text-xs uppercase tracking-wider">No transmissions yet</p>
                        </div>
                    </div>
                @endforelse

                @if($broadcasts->count() > 0)
                    <div class="text-center pt-8 pb-4">
                        <span class="text-[10px] text-gray-600 uppercase tracking-[0.2em] font-bold">END OF TRANSMISSION</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Auto-set sender name based on sender_type --}}
    <script>
        document.querySelector('select[name="sender_type"]').addEventListener('change', function() {
            document.querySelector('input[name="sender"]').value = this.value.toUpperCase();
        });
    </script>
@endsection
