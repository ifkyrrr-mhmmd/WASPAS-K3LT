@props([
    'user' => null,
    'size' => 'w-12 h-12',
    'avatar' => null
])

@php
    $selectedAvatar = $avatar;
    if (!$selectedAvatar) {
        $selectedAvatar = $user ? $user->avatar : (auth()->check() ? auth()->user()->avatar : 'Safety Officer');
    }
@endphp

<div {{ $attributes->merge(['class' => 'rounded-2xl flex items-center justify-center ' . $size . ' flex-shrink-0 transition-transform duration-300 shadow-sm border border-white/10']) }}
     title="{{ $selectedAvatar }}">
    
    @if($selectedAvatar === 'Engineering')
        <!-- Engineering: Blue background, gears icon -->
        <div class="w-full h-full bg-blue-600 rounded-2xl flex items-center justify-center text-white">
            <svg class="w-[60%] h-[60%]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
        </div>

    @elseif($selectedAvatar === 'Construction')
        <!-- Construction: Orange/Amber background, hardhat/crane icon -->
        <div class="w-full h-full bg-amber-500 rounded-2xl flex items-center justify-center text-white">
            <svg class="w-[60%] h-[60%]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
        </div>

    @elseif($selectedAvatar === 'Security')
        <!-- Security: Dark Slate background, shield + key lock icon -->
        <div class="w-full h-full bg-slate-700 rounded-2xl flex items-center justify-center text-white">
            <svg class="w-[60%] h-[60%]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
            </svg>
        </div>

    @elseif($selectedAvatar === 'Logistics')
        <!-- Logistics: Teal/Orange background, box icon -->
        <div class="w-full h-full bg-teal-600 rounded-2xl flex items-center justify-center text-white">
            <svg class="w-[60%] h-[60%]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-14L4 7m8 4v10M4 7v10l8 4"></path>
            </svg>
        </div>

    @elseif($selectedAvatar === 'Environment Officer')
        <!-- Environment Officer: Emerald background, green leaf/recycle icon -->
        <div class="w-full h-full bg-emerald-600 rounded-2xl flex items-center justify-center text-white">
            <svg class="w-[60%] h-[60%]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
            </svg>
        </div>

    @else
        <!-- Safety Officer (Default): Deep Purple background, K3 cross shield icon -->
        <div class="w-full h-full bg-[#281C59] rounded-2xl flex items-center justify-center text-white">
            <svg class="w-[60%] h-[60%]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-3-3v6m-9-6h.01M5.071 5.071a9 9 0 1112.728 0m-12.728 0a9 9 0 0112.728 0M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
        </div>
    @endif

</div>
