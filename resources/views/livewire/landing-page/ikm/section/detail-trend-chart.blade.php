{{-- section/detail-trend-chart.blade.php --}}
{{-- Alpine: drawChart(), chart.tooltip, visibleChartData, activePeriod --}}

<div wire:ignore
    class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl p-4 sm:p-6 shadow-sm overflow-hidden w-full max-w-full">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 sm:mb-5 gap-3">
        <div>
            <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-1">Riwayat Skor</p>
            <h3 class="text-base font-black text-slate-800 dark:text-white">Tren IKM per Triwulan</h3>
        </div>
        <div class="flex items-center gap-2 text-[10px] text-slate-400 dark:text-slate-500 font-bold">
            <span class="flex items-center gap-1.5">
                <svg width="18" height="4" class="inline">
                    <line x1="0" y1="2" x2="18" y2="2" stroke="#0d9488" stroke-width="2.5" stroke-linecap="round"/>
                </svg>
                Skor IKM
            </span>
            <span class="text-slate-300 dark:text-slate-600">|</span>
            <span>Klik titik untuk pilih periode</span>
        </div>
    </div>

    {{-- Chart Container --}}
    <div class="relative">
        <svg x-ref="chartSvg" style="overflow:visible;display:block;"></svg>

        {{-- Tooltip --}}
        <div x-show="chart.tooltip" x-cloak
            class="absolute pointer-events-none top-2 right-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-xl px-3.5 py-2.5 shadow-lg text-xs min-w-[130px] z-10">
            <p class="font-bold text-slate-400 dark:text-slate-500 mb-1" x-text="chart.tooltip?.period"></p>
            <p class="text-2xl font-black text-teal-600 dark:text-teal-400 tabular-nums leading-tight"
                x-text="parseFloat(chart.tooltip?.skor).toFixed(2)"></p>
            <p class="text-slate-400 dark:text-slate-500 mt-1"
                x-text="parseInt(chart.tooltip?.sampel || 0).toLocaleString('id-ID') + ' responden'"></p>
        </div>
    </div>

    {{-- Visible period indicator --}}
    <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-3 text-right"
        x-text="'Menampilkan ' + visibleCount + ' dari ' + chartData.length + ' periode'">
    </p>
</div>
