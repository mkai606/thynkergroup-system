@extends('layouts.admin')

@section('title', 'SYSTEM SETTINGS')

@section('content')
    @php
        $tier = $settings->tier_config ?? [];
        $vip = $settings->vip_config ?? [];
        $campaign = $settings->campaign_config ?? [];
        $payment = $settings->payment_config ?? [];
    @endphp

    {{-- TNG QR Upload (Payment Method for VIP) --}}
    <div class="bg-dark-light border-2 border-neon/30 rounded-xl p-6 mb-8">
        <div class="flex items-center gap-2 mb-4">
            <svg class="w-5 h-5 text-neon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
            <h2 class="font-heading text-lg tracking-wider text-neon">ADMIN TNG QR CODE</h2>
        </div>
        <p class="text-gray-400 text-sm mb-4">Upload your TNG eWallet QR code. Agents will scan this to pay the VIP upgrade fee (RM{{ number_format($vip['vipFee'] ?? 15, 2) }}).</p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Current QR --}}
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-3">Current QR Code</p>
                @if(!empty($payment['tng_qr_url']))
                    <div class="bg-white rounded-lg p-3 inline-block">
                        <img src="{{ asset('storage/' . $payment['tng_qr_url']) }}" alt="Admin TNG QR" style="width: 250px; height: 250px; object-fit: contain;">
                    </div>
                    <p class="text-xs text-success mt-2 font-medium">QR Active — Agents can see this on VIP upgrade page</p>
                @else
                    <div class="bg-dark rounded-lg p-8 text-center max-w-[250px]">
                        <svg class="w-12 h-12 mx-auto text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                        <p class="text-danger text-sm font-bold">No QR uploaded</p>
                        <p class="text-gray-500 text-xs mt-1">Upload your TNG QR to enable VIP payments</p>
                    </div>
                @endif
            </div>

            {{-- Upload Form --}}
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-3">Upload New QR</p>
                <form method="POST" action="{{ route('admin.settings.uploadTngQr') }}" enctype="multipart/form-data"
                      x-data="{ fileName: '' }">
                    @csrf
                    <div class="space-y-4">
                        <label class="block">
                            <div class="bg-dark border-2 border-dashed border-dark-lighter rounded-xl p-6 text-center cursor-pointer hover:border-neon/50 transition-colors"
                                 @dragover.prevent="$el.classList.add('border-neon')"
                                 @dragleave.prevent="$el.classList.remove('border-neon')"
                                 @drop.prevent="$el.classList.remove('border-neon'); fileName = $event.dataTransfer.files[0]?.name || ''">
                                <svg class="w-8 h-8 mx-auto text-gray-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                <p class="text-gray-400 text-sm" x-show="!fileName">Click or drag QR image here</p>
                                <p class="text-neon text-sm font-medium" x-show="fileName" x-text="fileName"></p>
                                <p class="text-gray-600 text-xs mt-1">JPG, PNG or WebP — Max 2MB</p>
                            </div>
                            <input type="file" name="tng_qr" accept="image/jpeg,image/png,image/webp" class="hidden"
                                   @change="fileName = $event.target.files[0]?.name || ''">
                        </label>
                        @error('tng_qr')
                            <p class="text-danger text-xs">{{ $message }}</p>
                        @enderror
                        <button type="submit" class="px-6 py-2.5 text-sm font-bold uppercase tracking-wider bg-neon text-dark rounded-lg hover:bg-neon-dim transition-colors">
                            Upload QR
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Settings Form --}}
    <form method="POST" action="{{ route('admin.settings.update') }}">
        @csrf
        @method('PUT')

        <div class="space-y-6">

            {{-- 01: Tier Configuration --}}
            <div class="bg-dark-light border border-dark-lighter rounded-xl p-6">
                <div class="flex items-center gap-2 mb-4">
                    <span class="text-xs font-mono text-neon/60">01</span>
                    <h3 class="font-heading text-sm tracking-wider text-gray-400">TIER CONFIGURATION</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-4">
                    @foreach(['A' => 'tierA_minFollowers', 'B' => 'tierB_minFollowers', 'C' => 'tierC_minFollowers', 'D' => 'tierD_minFollowers', 'E' => 'tierE_minFollowers'] as $label => $key)
                        <div>
                            <label class="text-xs text-gray-500 block mb-1">Tier {{ $label }} Min Followers</label>
                            <input type="number" name="{{ $key }}" value="{{ $tier[$key] ?? 0 }}"
                                   class="w-full bg-dark border border-dark-lighter rounded-lg px-3 py-2 text-sm text-white focus:border-neon focus:outline-none">
                        </div>
                    @endforeach
                </div>
                <div class="flex gap-6">
                    <label class="flex items-center gap-2 text-sm text-gray-400 cursor-pointer">
                        <input type="checkbox" name="autoPromotion" value="1" {{ ($tier['autoPromotion'] ?? false) ? 'checked' : '' }}
                               class="w-4 h-4 rounded border-dark-lighter bg-dark text-neon focus:ring-neon">
                        Auto Promotion
                    </label>
                    <label class="flex items-center gap-2 text-sm text-gray-400 cursor-pointer">
                        <input type="checkbox" name="autoDowngrade" value="1" {{ ($tier['autoDowngrade'] ?? false) ? 'checked' : '' }}
                               class="w-4 h-4 rounded border-dark-lighter bg-dark text-neon focus:ring-neon">
                        Auto Downgrade
                    </label>
                </div>
            </div>

            {{-- 02: VIP Configuration --}}
            <div class="bg-dark-light border border-dark-lighter rounded-xl p-6">
                <div class="flex items-center gap-2 mb-4">
                    <span class="text-xs font-mono text-neon/60">02</span>
                    <h3 class="font-heading text-sm tracking-wider text-gray-400">VIP CONFIGURATION</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                    <div>
                        <label class="text-xs text-gray-500 block mb-1">VIP Fee (RM)</label>
                        <input type="number" step="0.01" name="vipFee" value="{{ $vip['vipFee'] ?? 15 }}"
                               class="w-full bg-dark border border-dark-lighter rounded-lg px-3 py-2 text-sm text-white focus:border-neon focus:outline-none">
                    </div>
                    <div>
                        <label class="text-xs text-gray-500 block mb-1">Min Campaigns</label>
                        <input type="number" name="minCampaigns" value="{{ $vip['minCampaigns'] ?? 5 }}"
                               class="w-full bg-dark border border-dark-lighter rounded-lg px-3 py-2 text-sm text-white focus:border-neon focus:outline-none">
                    </div>
                    <div>
                        <label class="text-xs text-gray-500 block mb-1">Min Referrals</label>
                        <input type="number" name="minReferrals" value="{{ $vip['minReferrals'] ?? 5 }}"
                               class="w-full bg-dark border border-dark-lighter rounded-lg px-3 py-2 text-sm text-white focus:border-neon focus:outline-none">
                    </div>
                    <div>
                        <label class="text-xs text-gray-500 block mb-1">VIP Duration (days)</label>
                        <input type="number" name="vipDuration" value="{{ $vip['vipDuration'] ?? 365 }}"
                               class="w-full bg-dark border border-dark-lighter rounded-lg px-3 py-2 text-sm text-white focus:border-neon focus:outline-none">
                    </div>
                </div>
                <label class="flex items-center gap-2 text-sm text-gray-400 cursor-pointer">
                    <input type="checkbox" name="autoRenewal" value="1" {{ ($vip['autoRenewal'] ?? false) ? 'checked' : '' }}
                           class="w-4 h-4 rounded border-dark-lighter bg-dark text-neon focus:ring-neon">
                    Auto Renewal
                </label>
            </div>

            {{-- 03: Campaign Configuration --}}
            <div class="bg-dark-light border border-dark-lighter rounded-xl p-6">
                <div class="flex items-center gap-2 mb-4">
                    <span class="text-xs font-mono text-neon/60">03</span>
                    <h3 class="font-heading text-sm tracking-wider text-gray-400">CAMPAIGN CONFIGURATION</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="text-xs text-gray-500 block mb-1">Max Slots per Task</label>
                        <input type="number" name="maxSlots" value="{{ $campaign['maxSlots'] ?? 50 }}"
                               class="w-full bg-dark border border-dark-lighter rounded-lg px-3 py-2 text-sm text-white focus:border-neon focus:outline-none">
                    </div>
                    <div>
                        <label class="text-xs text-gray-500 block mb-1">Default Platform</label>
                        <select name="defaultPlatform" class="w-full bg-dark border border-dark-lighter rounded-lg px-3 py-2 text-sm text-white focus:border-neon focus:outline-none">
                            @foreach(['Instagram', 'TikTok', 'Facebook', 'YouTube', 'Twitter'] as $p)
                                <option value="{{ $p }}" {{ ($campaign['defaultPlatform'] ?? '') === $p ? 'selected' : '' }}>{{ $p }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="flex gap-6">
                    <label class="flex items-center gap-2 text-sm text-gray-400 cursor-pointer">
                        <input type="checkbox" name="allowMultiApply" value="1" {{ ($campaign['allowMultiApply'] ?? false) ? 'checked' : '' }}
                               class="w-4 h-4 rounded border-dark-lighter bg-dark text-neon focus:ring-neon">
                        Allow Multi-Apply
                    </label>
                    <label class="flex items-center gap-2 text-sm text-gray-400 cursor-pointer">
                        <input type="checkbox" name="autoCloseExpired" value="1" {{ ($campaign['autoCloseExpired'] ?? false) ? 'checked' : '' }}
                               class="w-4 h-4 rounded border-dark-lighter bg-dark text-neon focus:ring-neon">
                        Auto Close Expired
                    </label>
                </div>
            </div>

        </div>

        {{-- Save Button --}}
        <div class="mt-8 flex justify-end">
            <button type="submit" class="px-8 py-3 text-sm font-bold uppercase tracking-wider bg-neon text-dark rounded-xl hover:bg-neon-dim transition-colors shadow-[0_0_15px_rgba(170,255,0,0.2)] flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Save Settings
            </button>
        </div>
    </form>
@endsection
