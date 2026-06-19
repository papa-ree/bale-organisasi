@props([
    'variant' => 'primary', // primary, secondary
    'size'    => 'md',      // sm, md, lg
    'href'    => null,
])

@php
    $baseClasses = 'inline-flex items-center justify-center gap-3 font-bold transition-all duration-300 active:scale-95 shrink-0 disabled:opacity-50 disabled:cursor-not-allowed';
    
    $variants = [
        'primary' => 'bg-teal-600 hover:bg-teal-700 text-white shadow-lg shadow-teal-600/20 hover:shadow-teal-600/30 hover:scale-[1.02]',
        'secondary' => 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-teal-50 dark:hover:bg-teal-900/20',
        'ghost' => 'bg-transparent text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800',
    ];

    $sizes = [
        'sm' => 'px-4 py-2 text-xs rounded-xl',
        'md' => 'px-6 py-3 text-sm rounded-2xl',
        'lg' => 'px-8 py-4 text-base rounded-2xl font-black',
    ];

    $classes = "{$baseClasses} {$variants[$variant]} {$sizes[$size]}";
@endphp

@if($href)
    <a {{ $attributes->merge(['class' => $classes, 'href' => $href]) }}>
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['class' => $classes, 'type' => 'button']) }}>
        {{ $slot }}
    </button>
@endif
