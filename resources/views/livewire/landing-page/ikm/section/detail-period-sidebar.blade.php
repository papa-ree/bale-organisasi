{{-- section/detail-period-sidebar.blade.php --}}
{{-- Alpine: timelinePeriods, hasMore, totalHidden, setPeriod(), loadMore() --}}
{{-- Alpine: skor, kabAvg, kabRank, kabTotal, delta --}}

<div class="space-y-4">

    {{-- ══ Period Timeline Selector ══ --}}
    <div wire:ignore
        class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl overflow-hidden shadow-sm">

        <div class="px-4 py-3.5 border-b border-slate-100 dark:border-slate-700">
            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500">Pilih Periode</p>
        </div>

        {{-- Timeline list --}}
        <div class="p-2 space-y-1 max-h-[420px] overflow-y-auto">
            <template x-for="(d, i) in timelinePeriods" :key="d.key">
                <button
                    @click="setPeriod(d.key)"
                    class="w-full text-left flex items-center gap-3 px-3.5 py-3 rounded-xl border transition-all duration-200 group"
                    :class="activePeriod === d.key
                        ? 'bg-teal-50 dark:bg-teal-900/20 border-teal-200 dark:border-teal-800 shadow-sm'
                        : 'border-transparent hover:bg-slate-50 dark:hover:bg-slate-700/40'">

                    {{-- Timeline dot + line --}}
                    <div class="flex flex-col items-center gap-0.5 shrink-0 relative" style="width:16px">
                        <span class="w-3 h-3 rounded-full border-2 transition-all duration-300 shrink-0"
                            :class="activePeriod === d.key
                                ? 'border-teal-500 bg-teal-500 scale-110'
                                : 'border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 group-hover:border-teal-400'">
                        </span>
                        {{-- Connector line (not for last item) --}}
                        <template x-if="i < timelinePeriods.length - 1">
                            <div class="w-px h-4 bg-slate-200 dark:bg-slate-700 mt-0.5"></div>
                        </template>
                    </div>

                    {{-- Period info --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-sm font-black"
                                :class="activePeriod === d.key ? 'text-teal-700 dark:text-teal-400' : 'text-slate-700 dark:text-slate-300'"
                                x-text="d.period"></span>
                            <span class="text-sm font-black tabular-nums shrink-0"
                                :class="activePeriod === d.key ? 'text-teal-600 dark:text-teal-400' : 'text-slate-500 dark:text-slate-400'"
                                x-text="d.skor.toFixed(2)"></span>
                        </div>
                        <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5 tabular-nums"
                            x-text="parseInt(d.sampel).toLocaleString('id-ID') + ' responden'"></p>
                    </div>
                </button>
            </template>
        </div>

        {{-- Load More Button --}}
        <div x-show="hasMore"
            class="px-3 pb-3">
            <button
                @click="loadMore()"
                class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl border border-dashed border-slate-300 dark:border-slate-600 text-xs font-bold text-slate-500 dark:text-slate-400 hover:border-teal-400 hover:text-teal-600 dark:hover:text-teal-400 hover:bg-teal-50/50 dark:hover:bg-teal-900/10 transition-all duration-200">
                <x-umpak::icon name="plus-circle" class="w-3.5 h-3.5" />
                <span>Tampilkan lebih banyak</span>
                <span class="text-[10px] font-black px-1.5 py-0.5 rounded-md bg-slate-100 dark:bg-slate-700"
                    x-text="'(' + totalHidden + ')'"></span>
            </button>
        </div>
    </div>

    {{-- ══ Kabupaten Comparison Card ══ --}}
    <div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl p-5 shadow-sm space-y-4">

        <div>
            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-1">Perbandingan</p>
            <h3 class="text-sm font-black text-slate-800 dark:text-white">vs IKM Kabupaten</h3>
        </div>

        {{-- Gauge: Instansi (wire:ignore — Alpine driven) --}}
        <div wire:ignore>
            <div class="flex justify-between text-xs font-bold mb-1.5">
                <span class="text-slate-600 dark:text-slate-300 truncate">Instansi ini</span>
                <span class="text-teal-600 dark:text-teal-400 tabular-nums" x-text="skor.toFixed(2)"></span>
            </div>
            <div class="h-2.5 rounded-full overflow-hidden bg-slate-100 dark:bg-slate-700">
                <div class="h-full rounded-full transition-all duration-1000 bg-linear-to-r from-teal-500 to-sky-400"
                    :style="`width:${skor}%`"></div>
            </div>
        </div>

        {{-- Gauge: Kabupaten --}}
        <div>
            <div class="flex justify-between text-xs font-bold mb-1.5">
                <span class="text-slate-400 dark:text-slate-500 truncate">IKM Kabupaten</span>
                <span class="text-slate-400 dark:text-slate-500 tabular-nums" x-text="kabAvg.toFixed(2)"></span>
            </div>
            <div class="h-2.5 rounded-full overflow-hidden bg-slate-100 dark:bg-slate-700">
                <div class="h-full rounded-full bg-slate-300 dark:bg-slate-600 transition-all duration-1000"
                    :style="`width:${kabAvg}%`"></div>
            </div>
        </div>

        {{-- Delta Badge --}}
        <div class="rounded-xl px-4 py-3 text-center"
            :class="delta >= 0 ? 'bg-emerald-50 dark:bg-emerald-900/20' : 'bg-rose-50 dark:bg-rose-900/20'">
            <p class="text-2xl font-black tabular-nums"
                :class="delta >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400'"
                x-text="(delta >= 0 ? '+' : '') + delta.toFixed(2)">
            </p>
            <p class="text-[10px] font-bold mt-1"
                :class="delta >= 0 ? 'text-emerald-500 dark:text-emerald-500' : 'text-rose-500'"
                x-text="delta >= 0 ? 'poin di atas rata-rata' : 'poin di bawah rata-rata'">
            </p>
        </div>

        {{-- Rank --}}
        <div class="rounded-xl px-4 py-3 text-center bg-teal-50 dark:bg-teal-900/20">
            <p class="text-[10px] font-bold text-teal-600 dark:text-teal-500 uppercase tracking-widest mb-1">Peringkat</p>
            <p class="text-3xl font-black text-teal-600 dark:text-teal-400 tabular-nums"
                x-text="'#' + kabRank"></p>
            <p class="text-[10px] text-teal-500/70 dark:text-teal-500 mt-0.5"
                x-text="`dari ${kabTotal} instansi`"></p>
        </div>
    </div>
</div>
