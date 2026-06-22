{{-- section/detail-metric-cards.blade.php --}}
{{-- Needs: $skorAktif, $predikat, $sampel, $tren --}}
{{-- Alpine: skor, predikat, sampel, tren (reactive) --}}

<div wire:ignore
    class="relative z-10 -mt-10 px-5 sm:px-8 max-w-7xl mx-auto mb-8">
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">

        {{-- Skor IKM --}}
        <div class="col-span-2 sm:col-span-1 bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-xl shadow-slate-900/10 border border-slate-100 dark:border-slate-700">
            <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-2">Skor IKM</p>
            <p class="text-4xl font-black text-slate-800 dark:text-white tabular-nums leading-none"
                x-text="skor.toFixed(2)">{{ number_format($skorAktif, 2) }}</p>
            <p class="text-xs text-slate-400 dark:text-slate-500 mt-1.5">dari 100 poin</p>
        </div>

        {{-- Predikat --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-xl shadow-slate-900/10 border border-slate-100 dark:border-slate-700 flex flex-col justify-between">
            <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-2">Predikat</p>
            <div>
                <p class="text-sm font-black text-slate-700 dark:text-slate-200 mb-2" x-text="predikat.label">
                    {{ $predikat['label'] }}
                </p>
                <span class="text-[10px] font-black px-2.5 py-1 rounded-lg"
                    :class="{
                        'bg-teal-100 dark:bg-teal-900/30 text-teal-700 dark:text-teal-400'   : predikat.color === 'teal',
                        'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400'   : predikat.color === 'blue',
                        'bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400': predikat.color === 'amber',
                        'bg-rose-100 dark:bg-rose-900/30 text-rose-700 dark:text-rose-400'   : predikat.color === 'rose',
                    }"
                    x-text="predikat.label">
                </span>
            </div>
        </div>

        {{-- Responden --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-xl shadow-slate-900/10 border border-slate-100 dark:border-slate-700">
            <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-2">Responden</p>
            <p class="text-2xl font-black text-slate-800 dark:text-white tabular-nums leading-none"
                x-text="sampel.toLocaleString('id-ID')">
                {{ number_format($sampel, 0, ',', '.') }}
            </p>
            <p class="text-xs text-slate-400 dark:text-slate-500 mt-1.5">orang</p>
        </div>

        {{-- Tren --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-xl shadow-slate-900/10 border border-slate-100 dark:border-slate-700">
            <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-2">Tren vs Sebelumnya</p>
            <p class="text-2xl font-black tabular-nums leading-none"
                :class="tren === null ? 'text-slate-400' : tren > 0.3 ? 'text-emerald-600 dark:text-emerald-400' : tren < -0.3 ? 'text-rose-600 dark:text-rose-400' : 'text-slate-600 dark:text-slate-300'"
                x-text="tren === null ? 'N/A' : (tren > 0 ? '+' : '') + tren.toFixed(2)">
                {{ $tren !== null ? ($tren > 0 ? '+' : '') . $tren : 'N/A' }}
            </p>
            <p class="text-xs text-slate-400 dark:text-slate-500 mt-1.5"
                x-text="tren === null ? 'Periode pertama' : tren > 0.3 ? '↑ meningkat' : tren < -0.3 ? '↓ menurun' : '→ stabil'">
            </p>
        </div>

    </div>
</div>
