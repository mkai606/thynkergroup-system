@extends('layouts.admin')

@section('title', 'REVIEW APPLICANT')

@section('content')
    {{-- Back --}}
    <a href="{{ route('admin.registrations') }}" class="inline-flex items-center gap-1 text-sm text-gray-400 hover:text-neon transition-colors mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Queue ({{ $pendingCount }} pending)
    </a>

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4 mb-6">
        <div>
            <h2 class="font-heading text-3xl tracking-wide">{{ $registration->full_name }}</h2>
            <p class="text-neon text-sm mt-1">Applying for Sidekick Account</p>
            <div class="flex items-center gap-2 mt-2">
                <span class="w-7 h-7 rounded bg-neon/10 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5 text-neon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                </span>
                <span class="text-gray-400 font-mono text-sm">{{ $registration->phone }}</span>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <form method="POST" action="{{ route('admin.registrations.reject', $registration) }}">
                @csrf
                <button type="submit" class="px-8 py-2.5 text-sm font-bold uppercase tracking-wider border border-danger/50 text-danger rounded-full hover:bg-danger/10 transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Reject
                </button>
            </form>
            <form method="POST" action="{{ route('admin.registrations.approve', $registration) }}">
                @csrf
                <button type="submit" class="px-8 py-2.5 text-sm font-bold uppercase tracking-wider bg-neon text-dark rounded-full hover:bg-neon-dim transition-colors shadow-lg flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Approve
                </button>
            </form>
        </div>
    </div>

    {{-- Content Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Left: Social Profiles --}}
        <div>
            <div class="flex items-center gap-2 mb-4">
                <h3 class="font-heading text-sm tracking-wider text-gray-400">SOCIAL PROFILES</h3>
                <span class="px-2 py-0.5 text-[10px] font-bold bg-neon/10 text-neon rounded">{{ $registration->socialProfiles->count() }} LINKED</span>
            </div>
            <div class="space-y-4">
                @foreach($registration->socialProfiles as $profile)
                    <div class="bg-dark-light border border-dark-lighter rounded-xl p-5">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-2">
                                @if($profile->platform === 'instagram')
                                    <svg class="w-4 h-4 text-pink-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                                @elseif($profile->platform === 'tiktok')
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1v-3.5a6.37 6.37 0 00-.79-.05A6.34 6.34 0 003.15 15.2a6.34 6.34 0 0010.86 4.46V13a8.28 8.28 0 005.58 2.15V11.7a4.79 4.79 0 01-3.77-1.24V6.69h3.77z"/></svg>
                                @elseif($profile->platform === 'youtube')
                                    <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                                @else
                                    <svg class="w-3.5 h-3.5 text-neon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke-width="2"/><line x1="2" y1="12" x2="22" y2="12" stroke-width="2"/><path d="M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z" stroke-width="2"/></svg>
                                @endif
                                <span class="text-xs font-bold uppercase tracking-wider text-white">{{ ucfirst($profile->platform) }}</span>
                            </div>
                            @if($profile->profile_url)
                                <a href="{{ $profile->profile_url }}" target="_blank" rel="noopener"
                                   class="inline-flex items-center gap-1 px-3 py-1 text-xs font-bold uppercase tracking-wider border border-dark-lighter text-gray-400 rounded-lg hover:text-neon hover:border-neon transition-colors">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    View Profile
                                </a>
                            @endif
                        </div>

                        {{-- Handle --}}
                        <div class="bg-dark rounded-lg px-4 py-2.5 mb-3 border border-dark-lighter">
                            <span class="text-white text-sm font-mono">{{ $profile->handle }}</span>
                        </div>

                        {{-- Followers --}}
                        <p class="font-heading text-3xl text-white">{{ number_format($profile->followers) }}</p>
                        <p class="text-[10px] text-gray-500 uppercase tracking-widest">Followers</p>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Right: System Analysis & Details --}}
        <div class="space-y-6">
            {{-- System Analysis --}}
            <div class="bg-dark-light border border-dark-lighter rounded-xl p-6">
                <p class="text-neon text-xs font-bold uppercase tracking-widest mb-4">System Analysis</p>

                <div class="mb-4">
                    <p class="text-xs font-bold uppercase text-gray-400 mb-1">Recommended Tier</p>
                    <p class="font-heading text-6xl text-neon">TIER {{ $recommendedTier }}</p>
                </div>

                <div class="border-t border-dark-lighter pt-4 space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Highest Reach</span>
                        <span class="text-white font-medium">{{ number_format($highestFollowers) }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Total Reach</span>
                        <span class="text-white font-medium">{{ number_format($totalFollowers) }}</span>
                    </div>
                </div>
            </div>

            {{-- Eligibility Checklist --}}
            <div class="bg-dark-light border border-dark-lighter rounded-xl p-6">
                <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-4">Eligibility Check</p>
                @php
                    $hasProfile = $registration->socialProfiles->count() > 0;
                    $hasMinFollowers = $highestFollowers >= ($tierRule?->tier_e_min_followers ?? 0);
                    $hasHighlightPost = $registration->highlightPosts->count() > 0;
                    $allPassed = $hasProfile && $hasMinFollowers && $hasHighlightPost;
                @endphp
                <div class="space-y-3">
                    <div class="flex items-center gap-3">
                        @if($hasProfile)
                            <svg class="w-4 h-4 text-success flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        @else
                            <svg class="w-4 h-4 text-danger flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                        @endif
                        <span class="text-sm {{ $hasProfile ? 'text-white' : 'text-gray-500' }}">Social profile linked ({{ $registration->socialProfiles->count() }})</span>
                    </div>
                    <div class="flex items-center gap-3">
                        @if($hasMinFollowers)
                            <svg class="w-4 h-4 text-success flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        @else
                            <svg class="w-4 h-4 text-danger flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                        @endif
                        <span class="text-sm {{ $hasMinFollowers ? 'text-white' : 'text-gray-500' }}">Minimum followers met ({{ number_format($highestFollowers) }})</span>
                    </div>
                    <div class="flex items-center gap-3">
                        @if($hasHighlightPost)
                            <svg class="w-4 h-4 text-success flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        @else
                            <svg class="w-4 h-4 text-danger flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                        @endif
                        <span class="text-sm {{ $hasHighlightPost ? 'text-white' : 'text-gray-500' }}">Highlight post submitted ({{ $registration->highlightPosts->count() }})</span>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-dark-lighter">
                    @if($allPassed)
                        <span class="text-neon font-bold uppercase text-xs">All checks passed</span>
                    @else
                        <span class="text-warning font-bold uppercase text-xs">Incomplete requirements</span>
                    @endif
                </div>
            </div>

            {{-- Highlight Posts --}}
            @if($registration->highlightPosts->isNotEmpty())
                <div class="bg-dark-light border border-dark-lighter rounded-xl p-6">
                    <div class="flex items-center gap-2 mb-4">
                        <svg class="w-3.5 h-3.5 text-neon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        <h3 class="text-xs font-bold uppercase tracking-widest text-gray-400">Highlight Posts</h3>
                        <span class="px-2 py-0.5 text-[10px] font-bold bg-info/10 text-info rounded">{{ $registration->highlightPosts->count() }}</span>
                    </div>
                    <div class="space-y-3">
                        @foreach($registration->highlightPosts as $post)
                            @php
                                $isUpload = str_starts_with($post->post_url, 'registrations/');
                                $fileUrl = $isUpload ? asset('storage/' . $post->post_url) : $post->post_url;
                            @endphp
                            <div class="p-3 -mx-3 rounded-lg">
                                @if($isUpload && $post->post_type === 'image')
                                    <a href="{{ $fileUrl }}" target="_blank" rel="noopener" class="block">
                                        <img src="{{ $fileUrl }}" alt="Sample content" class="w-full max-h-64 object-contain rounded-lg border border-dark-lighter bg-dark">
                                    </a>
                                @elseif($isUpload && $post->post_type === 'video')
                                    <video controls class="w-full max-h-64 rounded-lg border border-dark-lighter bg-dark">
                                        <source src="{{ $fileUrl }}">
                                    </video>
                                @else
                                    <a href="{{ $post->post_url }}" target="_blank" rel="noopener" class="flex items-center gap-3 group hover:bg-dark/50 rounded-lg p-2 transition-colors">
                                        <div class="w-10 h-10 bg-dark rounded-lg flex items-center justify-center border border-dark-lighter flex-shrink-0">
                                            <svg class="w-5 h-5 text-gray-600 group-hover:text-neon transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                        </div>
                                        <span class="text-sm text-gray-400 group-hover:text-neon truncate transition-colors">{{ $post->post_url }}</span>
                                    </a>
                                @endif
                                <div class="flex items-center gap-3 mt-2">
                                    <span class="px-1.5 py-0.5 text-[10px] font-medium bg-info/10 text-info rounded">{{ ucfirst($post->post_type) }}</span>
                                    @if($post->likes > 0)
                                        <span class="flex items-center gap-1 text-xs text-danger">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"/></svg>
                                            {{ number_format($post->likes) }}
                                        </span>
                                    @endif
                                    @if($post->comments > 0)
                                        <span class="flex items-center gap-1 text-xs text-info">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                            {{ number_format($post->comments) }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Contact Info --}}
            <div class="bg-dark-light border border-dark-lighter rounded-xl p-6">
                <h3 class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-4">Contact Details</h3>
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Email</span>
                        <span class="text-white">{{ $registration->email }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Phone</span>
                        <span class="text-white font-mono">{{ $registration->phone }}</span>
                    </div>
                    @if($registration->referral_code_used)
                        @php $referrer = \App\Models\User::where('referral_code', $registration->referral_code_used)->first(); @endphp
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Referral Code</span>
                            <span class="text-neon font-mono">{{ $registration->referral_code_used }}</span>
                        </div>
                        @if($referrer)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Referred By</span>
                                <span class="text-white">{{ $referrer->name }} <span class="text-gray-500 font-mono text-xs">{{ $referrer->handle }}</span></span>
                            </div>
                        @endif
                    @endif
                    @if($registration->notes)
                        <div class="pt-2 border-t border-dark-lighter">
                            <span class="text-gray-500 text-xs uppercase tracking-wider block mb-2">Additional Info</span>
                            @foreach(explode(' | ', $registration->notes) as $note)
                                @php [$label, $value] = array_pad(explode(': ', $note, 2), 2, ''); @endphp
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="text-gray-500">{{ $label }}</span>
                                    <span class="text-gray-300">{{ $value }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
