@extends('layouts.admin')

@section('title', 'INITIATE MISSION')

@section('content')
    {{-- Back Button --}}
    <a href="{{ route('admin.campaigns') }}" class="inline-flex items-center gap-1 text-sm text-gray-400 hover:text-neon transition-colors mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Command
    </a>

    <div class="max-w-3xl">
        <div class="mb-6">
            <h2 class="font-heading text-2xl tracking-wide">INITIATE CAMPAIGN</h2>
            <p class="text-gray-500 text-sm mt-1">Define parameters & allocate budget</p>
        </div>

        <form method="POST" action="{{ route('admin.campaigns.store') }}">
            @csrf

            {{-- Section 01: Mission Identity --}}
            <div class="mb-8">
                <p class="text-neon text-xs font-bold tracking-widest mb-4">01 // MISSION IDENTITY</p>
                <div class="space-y-4">
                    <div>
                        <label for="title" class="block text-xs text-gray-400 uppercase tracking-wider mb-2">Campaign Designation *</label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}" required
                               class="w-full bg-dark border border-dark-lighter rounded-lg px-4 py-3 text-sm text-white placeholder-gray-600 focus:border-neon focus:outline-none uppercase"
                               placeholder="E.G. OPERATION SUMMER STORM">
                        @error('title') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="brand" class="block text-xs text-gray-400 uppercase tracking-wider mb-2">Brand *</label>
                        <input type="text" id="brand" name="brand" value="{{ old('brand') }}" required
                               class="w-full bg-dark border border-dark-lighter rounded-lg px-4 py-3 text-sm text-white placeholder-gray-600 focus:border-neon focus:outline-none"
                               placeholder="Brand name">
                        @error('brand') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4" x-data="{ platform: '{{ old('platform', 'Instagram') }}', usesTier(p) { return ['Instagram', 'Target'].includes(p) } }">
                        <div>
                            <label for="platform" class="block text-xs text-gray-400 uppercase tracking-wider mb-2">Target Platform *</label>
                            <select id="platform" name="platform" required x-model="platform"
                                    class="w-full bg-dark border border-dark-lighter rounded-lg px-4 py-3 text-sm text-white focus:border-neon focus:outline-none">
                                <option value="Instagram">Instagram</option>
                                <option value="TikTok">TikTok</option>
                                <option value="Facebook">Facebook</option>
                                <option value="X (Twitter)">X (Twitter)</option>
                                <option value="Threads">Threads</option>
                                <option value="Target">Target</option>
                            </select>
                        </div>
                        {{-- Minimum Clearance: for Instagram & Target --}}
                        <div x-show="usesTier(platform)" x-cloak>
                            <label for="tier" class="block text-xs text-gray-400 uppercase tracking-wider mb-2">Minimum Clearance *</label>
                            <select id="tier" name="tier"
                                    :required="usesTier(platform)"
                                    :disabled="!usesTier(platform)"
                                    class="w-full bg-dark border border-dark-lighter rounded-lg px-4 py-3 text-sm text-white focus:border-neon focus:outline-none">
                                <option value="A" {{ old('tier') === 'A' ? 'selected' : '' }}>Tier A (&gt;10k)</option>
                                <option value="B" {{ old('tier') === 'B' ? 'selected' : '' }}>Tier B (&gt;5k)</option>
                                <option value="C" {{ old('tier', 'C') === 'C' ? 'selected' : '' }}>Tier C (&gt;3k)</option>
                                <option value="D" {{ old('tier') === 'D' ? 'selected' : '' }}>Tier D (&gt;2k)</option>
                                <option value="E" {{ old('tier') === 'E' ? 'selected' : '' }}>Tier E (Micro)</option>
                            </select>
                        </div>
                        {{-- Min Followers: for other platforms --}}
                        <div x-show="!usesTier(platform)">
                            <label for="min_followers" class="block text-xs text-gray-400 uppercase tracking-wider mb-2">Min Followers *</label>
                            <input type="number" id="min_followers" name="min_followers" value="{{ old('min_followers') }}" min="0"
                                   :required="!usesTier(platform)"
                                   :disabled="usesTier(platform)"
                                   class="w-full bg-dark border border-dark-lighter rounded-lg px-4 py-3 text-sm text-white placeholder-gray-600 focus:border-neon focus:outline-none"
                                   placeholder="e.g. 1500">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Section 02: Operational Intel --}}
            <div class="mb-8">
                <p class="text-neon text-xs font-bold tracking-widest mb-4">02 // OPERATIONAL INTEL</p>
                <div class="space-y-4">
                    <div>
                        <label for="hashtags" class="block text-xs text-gray-400 uppercase tracking-wider mb-2">Required Hashtags</label>
                        <input type="text" id="hashtags" name="hashtags" value="{{ old('hashtags') }}"
                               class="w-full bg-dark border border-dark-lighter rounded-lg px-4 py-3 text-sm text-white placeholder-gray-600 focus:border-neon focus:outline-none"
                               placeholder="#CAMPAIGN #AD #BRAND">
                        @error('hashtags') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="description" class="block text-xs text-gray-400 uppercase tracking-wider mb-2">Mission Scope *</label>
                        <textarea id="description" name="description" required rows="2"
                                  class="w-full bg-dark border border-dark-lighter rounded-lg px-4 py-3 text-sm text-white placeholder-gray-600 focus:border-neon focus:outline-none resize-none"
                                  placeholder="DEFINE HIGH-LEVEL OBJECTIVES...">{{ old('description') }}</textarea>
                        @error('description') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="hidden_details" class="block text-xs text-gray-400 uppercase tracking-wider mb-2">Operational Directives</label>
                        <textarea id="hidden_details" name="hidden_details" rows="2"
                                  class="w-full bg-dark border border-dark-lighter rounded-lg px-4 py-3 text-sm text-white placeholder-gray-600 focus:border-neon focus:outline-none resize-none"
                                  placeholder="SPECIFIC ACTIONS: LIKE, COMMENT, SHARE TO STORY...">{{ old('hidden_details') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Section 03: Financial Protocol --}}
            <div class="mb-8">
                <p class="text-neon text-xs font-bold tracking-widest mb-4">03 // FINANCIAL PROTOCOL</p>
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="slots_total" class="block text-xs text-gray-400 uppercase tracking-wider mb-2">Sidekick Count *</label>
                            <input type="number" id="slots_total" name="slots_total" value="{{ old('slots_total', 50) }}" required min="1"
                                   class="w-full bg-dark border border-dark-lighter rounded-lg px-4 py-3 text-sm text-white focus:border-neon focus:outline-none"
                                   oninput="calcLiability()">
                            @error('slots_total') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="reward_amount" class="block text-xs text-gray-400 uppercase tracking-wider mb-2">Bounty Per Sidekick (RM) *</label>
                            <input type="number" id="reward_amount" name="reward_amount" value="{{ old('reward_amount', 30) }}" required min="1" step="0.01"
                                   class="w-full bg-dark border border-dark-lighter rounded-lg px-4 py-3 text-sm text-white focus:border-neon focus:outline-none"
                                   oninput="calcLiability()">
                            @error('reward_amount') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="exp_reward" class="block text-xs text-gray-400 uppercase tracking-wider mb-2">EXP Reward *</label>
                            <select id="exp_reward" name="exp_reward" required
                                    class="w-full bg-dark border border-dark-lighter rounded-lg px-4 py-3 text-sm text-white focus:border-neon focus:outline-none">
                                <option value="25" {{ old('exp_reward', 50) == 25 ? 'selected' : '' }}>25 EXP (Standard)</option>
                                <option value="50" {{ old('exp_reward', 50) == 50 ? 'selected' : '' }}>50 EXP (Boosted)</option>
                                <option value="75" {{ old('exp_reward', 50) == 75 ? 'selected' : '' }}>75 EXP (High Value)</option>
                                <option value="100" {{ old('exp_reward', 50) == 100 ? 'selected' : '' }}>100 EXP (Elite)</option>
                            </select>
                        </div>
                        <div>
                            <label for="deadline_days" class="block text-xs text-gray-400 uppercase tracking-wider mb-2">Duration (Days)</label>
                            <input type="number" id="deadline_days" name="deadline_days" value="{{ old('deadline_days', 30) }}" min="1" max="90"
                                   class="w-full bg-dark border border-dark-lighter rounded-lg px-4 py-3 text-sm text-white focus:border-neon focus:outline-none">
                        </div>
                        <div class="flex items-end pb-1">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="vip_only" value="1" {{ old('vip_only') ? 'checked' : '' }}
                                       class="w-4 h-4 rounded border-dark-lighter bg-dark text-neon focus:ring-neon focus:ring-offset-0">
                                <span class="text-sm text-gray-400">VIP Sidekicks Only</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Encrypted Instructions --}}
            <div class="mb-8">
                <p class="text-neon text-xs font-bold tracking-widest mb-4">04 // ENCRYPTED INSTRUCTIONS</p>
                <div>
                    <label for="instructions" class="block text-xs text-gray-400 uppercase tracking-wider mb-2">Step-by-step Instructions (one per line)</label>
                    <textarea id="instructions" name="instructions" rows="4"
                              class="w-full bg-dark border border-dark-lighter rounded-lg px-4 py-3 text-sm text-white placeholder-gray-600 focus:border-neon focus:outline-none resize-none font-mono"
                              placeholder="Step 1: Purchase product from link&#10;Step 2: Take photo/video&#10;Step 3: Post on platform&#10;Step 4: Maintain post for 7 days">{{ old('instructions') }}</textarea>
                    <p class="text-[10px] text-gray-600 mt-1">Hidden until agent accepts the task</p>
                    <div class="mt-3 p-3 bg-dark rounded-lg border border-dashed border-dark-lighter">
                        <p class="text-[10px] text-gray-500 uppercase tracking-wider mb-2">Sample</p>
                        <pre class="text-[11px] text-gray-500 font-mono leading-relaxed whitespace-pre-wrap">Beli produk dari link yang diberikan
Buat video unboxing / review (minimum 30 saat)
Post di Instagram Reels dengan hashtag yang ditetapkan
Tag akaun brand di caption
Screenshot insight post selepas 48 jam dan submit sebagai bukti</pre>
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <div class="flex items-center justify-between border-t border-dark-lighter pt-6">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider">Estimated Liability</p>
                    <p class="text-2xl font-bold text-neon" id="liability">RM {{ number_format(old('slots_total', 50) * old('reward_amount', 30), 2) }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.campaigns') }}"
                       class="px-5 py-2.5 text-sm font-bold uppercase tracking-wider border border-dark-lighter text-gray-400 rounded-lg hover:border-gray-500 transition-colors">
                        Cancel
                    </a>
                    <button type="submit"
                            class="px-5 py-2.5 text-sm font-bold uppercase tracking-wider bg-neon text-dark rounded-lg hover:bg-neon-dim transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                        Broadcast Order
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        function calcLiability() {
            const slots = parseFloat(document.getElementById('slots_total').value) || 0;
            const reward = parseFloat(document.getElementById('reward_amount').value) || 0;
            const total = slots * reward;
            document.getElementById('liability').textContent = 'RM ' + total.toLocaleString('en-MY', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }
    </script>
@endsection
