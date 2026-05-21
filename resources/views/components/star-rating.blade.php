{{-- Componente: <x-star-rating :rating="4.3" :count="12" size="sm|md" /> --}}
@props(['rating' => null, 'count' => 0, 'size' => 'md'])

@php
    $svgClass = $size === 'sm' ? 'w-4 h-4' : 'w-5 h-5';
    $full  = $rating ? (int) floor($rating) : 0;
    $half  = $rating && ($rating - $full) >= 0.4 ? 1 : 0;
    $empty = 5 - $full - $half;
@endphp

<div class="flex items-center gap-1.5">
    <div class="flex items-center gap-0.5">
        @for ($i = 0; $i < $full; $i++)
            <svg class="{{ $svgClass }} text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
            </svg>
        @endfor
        @if ($half)
            {{-- Media estrella con clip --}}
            <svg class="{{ $svgClass }}" viewBox="0 0 20 20">
                <defs>
                    <linearGradient id="halfGrad">
                        <stop offset="50%" stop-color="#facc15"/>
                        <stop offset="50%" stop-color="#d1d5db"/>
                    </linearGradient>
                </defs>
                <path fill="url(#halfGrad)" d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
            </svg>
        @endif
        @for ($i = 0; $i < $empty; $i++)
            <svg class="{{ $svgClass }} text-gray-200" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
            </svg>
        @endfor
    </div>
    @if ($rating)
        <span class="font-semibold text-gray-800 {{ $size === 'sm' ? 'text-sm' : 'text-base' }}">{{ number_format($rating, 1) }}</span>
        @if ($count > 0)
            <span class="text-gray-400 {{ $size === 'sm' ? 'text-xs' : 'text-sm' }}">({{ $count }} {{ $count === 1 ? 'valoración' : 'valoraciones' }})</span>
        @endif
    @else
        <span class="text-gray-400 {{ $size === 'sm' ? 'text-xs' : 'text-sm' }}">Sin valoraciones aún</span>
    @endif
</div>
