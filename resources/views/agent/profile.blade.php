@extends('layouts.agent')

@section('title', 'Profile')

@section('content')
    {{-- Avatar + Identity --}}
    <div class="flex flex-col items-center mb-6">
        <div class="relative mb-4" x-data="avatarUpload()">
            <div class="w-28 h-28 rounded-full border-2 {{ $user->sidekick_level === 'vip' ? 'border-neon shadow-[0_0_20px_rgba(204,255,0,0.2)]' : 'border-gray-700' }} bg-[#262626] flex items-center justify-center overflow-hidden">
                @if($user->avatar_url)
                    <img src="{{ asset('storage/' . $user->avatar_url) }}" alt="" class="w-full h-full object-cover">
                @else
                    <span class="text-4xl font-heading text-gray-500">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                @endif
            </div>
            @if($user->sidekick_level === 'vip')
                <div class="absolute bottom-0 left-0 w-7 h-7 bg-neon rounded-full flex items-center justify-center shadow-[0_0_10px_rgba(204,255,0,0.3)]">
                    <svg class="w-4 h-4 text-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                </div>
            @endif

            {{-- Camera Button --}}
            <button @click="$refs.fileInput.click()" class="absolute bottom-0 right-0 w-7 h-7 bg-[#262626] border border-gray-600 rounded-full flex items-center justify-center hover:border-neon hover:bg-[#1A1A1A] transition-colors">
                <svg class="w-3.5 h-3.5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><circle cx="12" cy="13" r="3" stroke-width="2"/></svg>
            </button>

            {{-- Hidden file input --}}
            <input type="file" x-ref="fileInput" accept="image/jpeg,image/png,image/webp" class="hidden" @change="handleFile($event)">
        </div>

        <div class="flex items-center gap-2">
            <h2 class="font-heading text-3xl tracking-wide text-center">{{ strtoupper($user->name) }}</h2>
            @if($user->verified_badge)
                <svg class="w-5 h-5 text-neon" fill="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            @endif
        </div>
        <p class="text-xs text-gray-500 font-mono mt-1">{{ $user->handle }}</p>

        <div class="flex flex-wrap items-center justify-center gap-2 mt-3">
            <span class="px-3 py-1 rounded-full text-[10px] uppercase font-bold tracking-wider {{ $user->sidekick_level === 'vip' ? 'bg-[#3D4421] text-neon border border-neon/30' : 'bg-gray-800 text-gray-400 border border-gray-700' }}">
                Sidekick {{ strtoupper($user->sidekick_level) }}
            </span>
            <span class="px-3 py-1 rounded-full text-[10px] uppercase font-bold tracking-wider bg-[#1A1A1A] text-gray-400 border border-gray-800">
                Tier {{ $user->tier }}
            </span>
            @if($user->rank_position)
                <span class="px-3 py-1 rounded-full text-[10px] uppercase font-bold tracking-wider bg-[#1A1A1A] text-gray-400 border border-gray-800">
                    Rank #{{ $user->rank_position }}
                </span>
            @endif
        </div>

        {{-- Member since --}}
        <p class="text-[10px] text-gray-600 mt-2">
            Member since {{ $user->join_date?->format('M Y') ?? $user->created_at->format('M Y') }}
            @if($user->platform_primary)
                &middot; {{ ucfirst($user->platform_primary) }}
            @endif
        </p>
    </div>

    {{-- VIP Membership Info (for active VIP) --}}
    @if($user->sidekick_level === 'vip' && $vip)
        <div class="bg-[#3D4421]/30 border border-neon/20 rounded-xl p-4 mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-neon" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/></svg>
                    <p class="text-xs text-neon font-bold uppercase tracking-wider">VIP Active</p>
                </div>
                @if($vip->expires_at)
                    <p class="text-[10px] text-gray-400">Expires {{ $vip->expires_at->format('d M Y') }}</p>
                @endif
            </div>
        </div>
    @endif

    {{-- Stats Grid --}}
    <div class="grid grid-cols-2 gap-3 mb-3">
        <div class="bg-[#262626] border border-gray-700 rounded-xl p-4 text-center">
            <svg class="w-5 h-5 mx-auto text-neon mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3h14l-1.5 6.5a5.5 5.5 0 01-11 0L5 3zm7 13v4m-4 0h8"/></svg>
            <p class="font-heading text-2xl text-neon">{{ number_format($user->total_exp) }}</p>
            <p class="text-[10px] text-gray-500 uppercase tracking-wider">Total EXP</p>
        </div>
        <div class="bg-[#262626] border border-gray-700 rounded-xl p-4 text-center">
            <svg class="w-5 h-5 mx-auto text-neon mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
            <p class="font-heading text-2xl text-neon">{{ number_format($user->monthly_exp) }}</p>
            <p class="text-[10px] text-gray-500 uppercase tracking-wider">Monthly EXP</p>
        </div>
    </div>
    <div class="grid grid-cols-3 gap-3 mb-6">
        <div class="bg-[#262626] border border-gray-700 rounded-xl p-3 text-center">
            <p class="font-heading text-xl text-white">{{ number_format($user->success_rate ?? 0, 1) }}%</p>
            <p class="text-[9px] text-gray-500 uppercase tracking-wider">Success</p>
        </div>
        <div class="bg-[#262626] border border-gray-700 rounded-xl p-3 text-center">
            <p class="font-heading text-xl text-white">{{ $user->completed_tasks ?? 0 }}</p>
            <p class="text-[9px] text-gray-500 uppercase tracking-wider">Missions</p>
        </div>
        <div class="bg-[#262626] border border-gray-700 rounded-xl p-3 text-center">
            <p class="font-heading text-xl text-white">{{ number_format($user->follower_count) }}</p>
            <p class="text-[9px] text-gray-500 uppercase tracking-wider">Followers</p>
        </div>
    </div>

    {{-- Earnings Quick View --}}
    <a href="{{ route('agent.wallet') }}" class="block bg-[#262626] border border-gray-700 rounded-xl p-4 mb-6 hover:border-gray-500 transition-colors active:scale-[0.98]">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-[10px] text-gray-500 uppercase tracking-widest font-bold">Total Earned</p>
                <p class="font-heading text-2xl text-neon mt-0.5">RM {{ number_format($totalEarned, 2) }}</p>
            </div>
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </div>
    </a>

    {{-- TNG QR Code --}}
    <div class="bg-[#1A1A1A] border border-gray-700 rounded-xl p-4 mb-6" x-data="tngUpload()">
        <div class="flex items-center justify-between mb-3">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                <p class="text-xs text-white font-bold uppercase tracking-wider">TNG eWallet QR</p>
            </div>
            @if($user->tng_qr_url)
                <span class="text-[9px] text-green-400 uppercase font-bold">Uploaded</span>
            @else
                <span class="text-[9px] text-yellow-400 uppercase font-bold">Required for payout</span>
            @endif
        </div>

        @if($user->tng_qr_url)
            <div class="bg-white rounded-lg p-2 mb-3">
                <img src="{{ asset('storage/' . $user->tng_qr_url) }}" alt="TNG QR" class="w-full max-h-48 object-contain mx-auto">
            </div>
        @endif

        <button @click="$refs.tngInput.click()" class="w-full py-2.5 bg-[#262626] border border-gray-600 text-gray-300 rounded-xl text-xs font-bold uppercase tracking-wider hover:border-neon hover:text-neon transition-colors">
            {{ $user->tng_qr_url ? 'Update QR' : 'Upload QR Code' }}
        </button>
        <input type="file" x-ref="tngInput" accept="image/jpeg,image/png,image/webp" class="hidden" @change="uploadQr($event)">
        <p class="text-[10px] text-gray-600 mt-2 text-center">Admin will use this to transfer your payout</p>
    </div>

    {{-- Referral Code --}}
    @if($user->referral_code)
        <div class="bg-[#1A1A1A] border border-dashed border-gray-700 rounded-xl p-4 mb-6" x-data="{ copied: false }">
            <p class="text-[10px] text-gray-500 uppercase tracking-widest font-bold mb-2">Your Referral Code</p>
            <div class="flex items-center justify-between bg-[#121212] rounded-lg px-4 py-3">
                <span class="font-mono text-lg text-white font-bold tracking-wider">{{ $user->referral_code }}</span>
                <button @click="navigator.clipboard.writeText('{{ $user->referral_code }}'); copied = true; setTimeout(() => copied = false, 2000)"
                        class="text-xs font-bold uppercase tracking-wider transition-colors"
                        :class="copied ? 'text-green-400' : 'text-neon hover:text-neon-dim'">
                    <span x-show="!copied">Copy</span>
                    <span x-show="copied" x-cloak class="flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Copied!
                    </span>
                </button>
            </div>
            <p class="text-[10px] text-gray-500 mt-2">{{ $user->referral_count ?? 0 }} referrals made</p>
        </div>
    @endif

    {{-- VIP Upgrade Card (for non-VIP users) --}}
    @if($user->sidekick_level === 'premium')
        @php
            $completedCampaigns = $user->completed_tasks ?? 0;
            $referrals = $user->referral_count ?? 0;
            $campaignsMet = $completedCampaigns >= 5;
            $referralsMet = $referrals >= 5;
            $allMet = $campaignsMet && $referralsMet;
        @endphp
        <div class="bg-gradient-to-br from-[#121212] to-[#1A1A1A] border {{ $allMet ? 'border-neon' : 'border-gray-700' }} rounded-xl p-5 mb-6" x-data="{ showVipModal: false }">
            <div class="flex items-center gap-2 mb-4">
                <svg class="w-5 h-5 text-neon" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/></svg>
                <p class="font-heading text-sm tracking-wider text-white">VIP UPGRADE</p>
            </div>

            {{-- Progress: Campaigns --}}
            <div class="mb-3">
                <div class="flex justify-between text-[10px] mb-1">
                    <span class="text-gray-400 uppercase tracking-wider">Completed Campaigns</span>
                    <span class="{{ $campaignsMet ? 'text-neon' : 'text-white' }} font-bold">{{ $completedCampaigns }}/5</span>
                </div>
                <div class="h-1.5 bg-gray-800 rounded-full overflow-hidden">
                    <div class="h-full {{ $campaignsMet ? 'bg-neon' : 'bg-gray-600' }} rounded-full transition-all" style="width: {{ min(100, ($completedCampaigns / 5) * 100) }}%"></div>
                </div>
            </div>

            {{-- Progress: Referrals --}}
            <div class="mb-4">
                <div class="flex justify-between text-[10px] mb-1">
                    <span class="text-gray-400 uppercase tracking-wider">Referrals</span>
                    <span class="{{ $referralsMet ? 'text-neon' : 'text-white' }} font-bold">{{ $referrals }}/5</span>
                </div>
                <div class="h-1.5 bg-gray-800 rounded-full overflow-hidden">
                    <div class="h-full {{ $referralsMet ? 'bg-neon' : 'bg-gray-600' }} rounded-full transition-all" style="width: {{ min(100, ($referrals / 5) * 100) }}%"></div>
                </div>
            </div>

            @if($pendingVip)
                <div class="bg-yellow-900/20 border border-yellow-700/30 rounded-xl p-3 text-center">
                    <p class="text-xs text-yellow-300 font-bold uppercase tracking-wider">Payment Submitted</p>
                    <p class="text-[10px] text-gray-500 mt-1">Awaiting admin verification</p>
                </div>
            @elseif($allMet)
                <p class="text-[10px] text-neon mb-3 text-center font-bold uppercase tracking-wider">All requirements met!</p>
                <button @click="showVipModal = true"
                        class="w-full py-3 rounded-xl text-sm font-bold uppercase tracking-wider bg-neon text-dark hover:bg-neon-dim shadow-[0_0_15px_rgba(204,255,0,0.3)] transition-colors">
                    Pay RM15 & Upgrade
                </button>
            @else
                <p class="text-[10px] text-gray-500 mb-3 text-center">Complete both requirements to unlock VIP</p>
                <button disabled class="w-full py-3 rounded-xl text-sm font-bold uppercase tracking-wider bg-gray-800 text-gray-600 cursor-not-allowed">
                    Pay RM15 & Upgrade
                </button>
            @endif

            {{-- VIP Payment Modal --}}
            <template x-teleport="body">
                <div x-show="showVipModal" x-cloak class="fixed inset-0 z-50 flex items-end justify-center">
                    <div x-show="showVipModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                         class="absolute inset-0 bg-black/70" @click="showVipModal = false"></div>

                    <div x-show="showVipModal" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0" x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="translate-y-0" x-transition:leave-end="translate-y-full"
                         class="relative w-full max-w-md bg-[#1A1A1A] border-t border-neon/30 rounded-t-2xl max-h-[85vh] overflow-y-auto" style="padding-bottom: calc(1rem + env(safe-area-inset-bottom, 0px));">

                        <div class="sticky top-0 bg-[#1A1A1A] px-5 pt-4 pb-3 border-b border-gray-800 z-10">
                            <div class="w-10 h-1 bg-neon/50 rounded-full mx-auto mb-3"></div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-neon" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/></svg>
                                    <h3 class="font-heading text-lg tracking-wide text-neon">VIP UPGRADE</h3>
                                </div>
                                <button @click="showVipModal = false" class="text-gray-500 hover:text-white">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        </div>

                        <div class="px-5 py-4 space-y-5">
                            {{-- Amount Due --}}
                            <div class="text-center py-3">
                                <p class="text-[10px] text-gray-500 uppercase tracking-widest">Amount Due</p>
                                <p class="font-heading text-5xl text-neon mt-1">RM 15</p>
                            </div>

                            {{-- Step 1: Scan QR --}}
                            <div>
                                <p class="text-xs text-gray-400 uppercase tracking-wider font-bold mb-3 flex items-center gap-2">
                                    <span class="w-5 h-5 bg-neon text-dark rounded-full flex items-center justify-center text-[10px] font-bold">1</span>
                                    Scan & Pay via TNG
                                </p>
                                @if($adminQrUrl)
                                    <div class="bg-white rounded-xl p-4 flex items-center justify-center">
                                        <img src="{{ asset('storage/' . $adminQrUrl) }}" alt="Admin TNG QR" style="width: 200px; height: 200px; object-fit: contain;">
                                    </div>
                                    <p class="text-[10px] text-gray-500 text-center mt-2">Scan this QR with your TNG eWallet app</p>
                                @else
                                    <div class="bg-[#262626] rounded-xl p-6 text-center">
                                        <svg class="w-10 h-10 mx-auto text-gray-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                        <p class="text-xs text-gray-500">Admin QR not available yet</p>
                                    </div>
                                @endif
                            </div>

                            {{-- Step 2: Upload Receipt --}}
                            <div x-data="{ receiptName: '' }">
                                <p class="text-xs text-gray-400 uppercase tracking-wider font-bold mb-3 flex items-center gap-2">
                                    <span class="w-5 h-5 bg-neon text-dark rounded-full flex items-center justify-center text-[10px] font-bold">2</span>
                                    Upload Payment Receipt
                                </p>
                                <form method="POST" action="{{ route('agent.vip.request') }}" enctype="multipart/form-data">
                                    @csrf
                                    <label class="block mb-3">
                                        <div class="bg-[#262626] border-2 border-dashed border-gray-700 rounded-xl p-5 text-center cursor-pointer hover:border-neon/50 transition-colors">
                                            <svg class="w-8 h-8 mx-auto text-gray-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                            <p class="text-gray-400 text-xs" x-show="!receiptName">Tap to upload screenshot</p>
                                            <p class="text-neon text-xs font-medium" x-show="receiptName" x-text="receiptName"></p>
                                            <p class="text-gray-600 text-[10px] mt-1">JPG, PNG or WebP — Max 2MB</p>
                                        </div>
                                        <input type="file" name="receipt" accept="image/jpeg,image/png,image/webp" class="hidden"
                                               @change="receiptName = $event.target.files[0]?.name || ''" required>
                                    </label>
                                    @error('receipt')
                                        <p class="text-red-400 text-xs mb-2">{{ $message }}</p>
                                    @enderror
                                    <button type="submit"
                                            class="w-full py-3.5 rounded-xl text-sm font-bold uppercase tracking-wider bg-neon text-dark hover:bg-neon-dim shadow-[0_0_15px_rgba(204,255,0,0.3)] transition-colors flex items-center justify-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Submit Payment
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    @endif

    {{-- Logout --}}
    <form method="POST" action="{{ route('agent.logout') }}" class="mt-4 mb-4">
        @csrf
        <button type="submit" class="w-full bg-red-900/20 border border-red-900/50 text-red-400 py-3 rounded-xl text-sm font-bold uppercase tracking-wider hover:bg-red-900/30 transition-colors">
            Deactivate Session
        </button>
    </form>

    <p class="text-center text-[10px] text-gray-700 mb-2">{{ $user->email }}</p>

    <script>
    function tngUpload() {
        return {
            uploadQr(event) {
                const file = event.target.files[0];
                if (!file) return;

                const formData = new FormData();
                formData.append('tng_qr', file);

                fetch('{{ route('agent.profile.tng-qr') }}', {
                    method: 'POST',
                    body: formData,
                    credentials: 'same-origin',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    }
                }).then(r => r.json().then(data => ({ ok: r.ok, data })))
                .then(({ ok, data }) => {
                    if (ok) window.location.reload();
                    else alert(data.errors?.tng_qr?.[0] || data.message || 'Upload gagal');
                }).catch(() => alert('Upload gagal. Sila cuba lagi.'));
            }
        }
    }

    function avatarUpload() {
        return {
            handleFile(event) {
                const file = event.target.files[0];
                if (!file) return;

                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d');
                const img = new Image();

                img.onload = () => {
                    const size = Math.min(img.width, img.height);
                    const x = (img.width - size) / 2;
                    const y = (img.height - size) / 2;

                    canvas.width = 400;
                    canvas.height = 400;
                    ctx.drawImage(img, x, y, size, size, 0, 0, 400, 400);

                    canvas.toBlob((blob) => {
                        const formData = new FormData();
                        formData.append('avatar', new File([blob], 'avatar.jpg', { type: 'image/jpeg' }));

                        fetch('{{ route('agent.profile.avatar') }}', {
                            method: 'POST',
                            body: formData,
                            credentials: 'same-origin',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                            }
                        }).then(response => response.json().then(data => ({ ok: response.ok, status: response.status, data })))
                        .then(({ ok, status, data }) => {
                            if (ok) {
                                window.location.reload();
                            } else {
                                console.error('Upload failed:', status, data);
                                const msg = data.errors?.avatar?.[0] || data.message || 'Upload gagal';
                                alert(msg);
                            }
                        }).catch(err => {
                            console.error('Upload error:', err);
                            alert('Upload gagal. Sila cuba lagi.');
                        });
                    }, 'image/jpeg', 0.9);
                };

                img.src = URL.createObjectURL(file);
            }
        }
    }
    </script>
@endsection
