{{-- section/detail-hero.blade.php --}}
{{-- Needs: $instansi, $periodeAktif --}}

<div class="relative overflow-hidden bg-linear-to-br from-[#0c3a47] via-[#0d6b7a] to-[#075985] pb-16">

    {{-- Grid Pattern Overlay --}}
    <div class="absolute inset-0 opacity-10 pointer-events-none">
        <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="detail-grid" width="40" height="40" patternUnits="userSpaceOnUse">
                    <path d="M 40 0 L 0 0 0 40" fill="none" stroke="white" stroke-width="1"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#detail-grid)"/>
        </svg>
    </div>

    {{-- Decorative blur orbs --}}
    <div class="absolute -top-20 -right-20 w-64 h-64 rounded-full bg-teal-400/10 blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-0 left-1/4 w-48 h-48 rounded-full bg-sky-400/10 blur-3xl pointer-events-none"></div>

    <div class="relative px-5 sm:px-8 pt-6 max-w-7xl mx-auto">

        {{-- Back Navigation --}}
        <a href="{{ route('bale-organisasi.ikm.index') }}" wire:navigate.hover
            class="group inline-flex items-center gap-2 text-white/60 hover:text-white text-xs font-semibold mb-7 transition-all">
            <x-umpak::icon name="arrow-left" class="w-3.5 h-3.5 transition-transform group-hover:-translate-x-1" />
            Kembali ke Daftar IKM
        </a>

        {{-- Identity Card --}}
        <div class="flex items-start gap-4 sm:gap-5">

            {{-- Avatar --}}
            <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl flex items-center justify-center text-lg font-black shrink-0"
                style="background:rgba(255,255,255,.18);color:#fff;backdrop-filter:blur(6px);border:1.5px solid rgba(255,255,255,.25)">
                {{ mb_strtoupper(mb_substr($instansi->nama_opd, 0, 2)) }}
            </div>

            {{-- Meta --}}
            <div class="min-w-0 flex-1">

                {{-- Tags --}}
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <span class="text-xs px-2.5 py-1 rounded-full font-semibold"
                        style="background:rgba(255,255,255,.2);color:#fff">
                        {{ explode(' ', trim($instansi->nama_opd))[0] }}
                    </span>
                    <span class="text-xs font-bold text-teal-300 flex items-center gap-1">
                        <x-umpak::icon name="calendar" class="w-3 h-3" />
                        TW{{ $periodeAktif?->triwulan }} {{ $periodeAktif?->tahun }}
                    </span>
                </div>

                {{-- Name --}}
                <h1 class="text-xl sm:text-2xl lg:text-3xl font-black text-white leading-tight">
                    {{ $instansi->nama_opd }}
                </h1>
            </div>
        </div>
    </div>
</div>
