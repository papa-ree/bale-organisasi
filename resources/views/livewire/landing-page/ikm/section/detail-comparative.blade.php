{{-- section/detail-comparative.blade.php --}}
{{-- Alpine: visibleChartData, chartData, activePeriod, hasMore, totalHidden, loadMore() --}}

<div
    class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl shadow-sm mb-4">

    <div class="p-5 sm:p-6 border-b border-slate-100 dark:border-slate-700">
        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-1">Analisis
            Riwayat Detail</p>
        <h3 class="text-base font-black text-slate-800 dark:text-white">Perbandingan 9 Unsur Antar Periode</h3>
        <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">Geser ke samping untuk melihat riwayat lebih lengkap
        </p>
    </div>

    <div class="overflow-x-auto rounded-b-none scrollbar-gutter-stable scrollbar-thin scrollbar-track-slate-200 dark:scrollbar-track-slate-800 scrollbar-thumb-teal-500 dark:scrollbar-thumb-teal-600 hover:scrollbar-thumb-teal-600 dark:hover:scrollbar-thumb-teal-500 scrollbar-thumb-rounded-full scrollbar-track-rounded-full">
        <table class="min-w-full border-collapse">
            <thead>
                <tr class="bg-slate-50 dark:bg-slate-700/50 border-b border-slate-100 dark:border-slate-700">
                    {{-- Sticky unsur header --}}
                    <th
                        class="sticky left-0 z-10 bg-slate-50 dark:bg-slate-700/50 py-3 px-3 sm:px-5 text-left text-[9px] sm:text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 border-r border-slate-100 dark:border-slate-700 min-w-[140px] sm:min-w-[180px]">
                        9 Unsur Pelayanan
                    </th>
                    {{-- Dynamic period columns (newest-first, limited by visibleCount) --}}
                    <template x-for="d in [...visibleChartData].reverse()" :key="d.key">
                        <th class="py-3 px-2 sm:px-4 text-center min-w-[80px] sm:min-w-[110px] transition-colors"
                            :class="activePeriod === d.key
                                ? 'bg-teal-50 dark:bg-teal-900/20 text-teal-700 dark:text-teal-400'
                                : 'text-slate-500 dark:text-slate-400'">
                            <p class="text-[10px] font-bold uppercase tracking-tight" x-text="d.period.split(' ')[1]">
                            </p>
                            <p class="text-xs font-black" x-text="d.period.split(' ')[0]"></p>
                        </th>
                    </template>
                </tr>
            </thead>
            <tbody>
                <template x-for="idx in 9" :key="idx">
                    <tr
                        class="border-b border-slate-100 dark:border-slate-700 last:border-0 hover:bg-slate-50/50 dark:hover:bg-slate-700/20 transition-colors">
                        {{-- Sticky unsur label --}}
                        <td
                            class="sticky left-0 z-10 bg-white dark:bg-slate-800 py-3 px-3 sm:px-5 border-r border-slate-100 dark:border-slate-700">
                            <div class="flex items-center gap-2 sm:gap-2.5">
                                <span
                                    class="w-4 h-4 sm:w-5 sm:h-5 rounded-md flex items-center justify-center text-[9px] sm:text-[10px] font-black shrink-0 bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 tabular-nums"
                                    x-text="idx"></span>
                                <p class="text-[10px] sm:text-xs font-semibold text-slate-700 dark:text-slate-300 truncate max-w-[90px] sm:max-w-[130px]"
                                    x-text="chartData[0]?.unsur[idx-1]?.label || ('Unsur ' + idx)"></p>
                            </div>
                        </td>
                        {{-- Data cells --}}
                        <template x-for="d in [...visibleChartData].reverse()" :key="d.key">
                            <td class="py-3 px-2 sm:px-4 text-center tabular-nums transition-colors"
                                :class="activePeriod === d.key ? 'bg-teal-50 dark:bg-teal-900/10' : ''">
                                <div class="flex flex-col items-center gap-1">
                                    <span class="text-sm font-black"
                                        :class="activePeriod === d.key ? 'text-teal-600 dark:text-teal-400' : 'text-slate-600 dark:text-slate-400'"
                                        x-text="d.unsur[idx-1]?.nilai.toFixed(2)">
                                    </span>
                                    <div class="w-2 h-2 rounded-full" :class="{
                                        'bg-teal-500' : d.unsur[idx-1]?.nilai >= 3.5324,
                                        'bg-blue-500' : d.unsur[idx-1]?.nilai >= 3.0644 && d.unsur[idx-1]?.nilai < 3.5324,
                                        'bg-amber-400': d.unsur[idx-1]?.nilai >= 2.60   && d.unsur[idx-1]?.nilai < 3.0644,
                                        'bg-rose-500' : d.unsur[idx-1]?.nilai < 2.60,
                                    }"></div>
                                </div>
                            </td>
                        </template>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>

    {{-- Legend --}}
    <div
        class="p-4 bg-slate-50 dark:bg-slate-700/30 border-t border-slate-100 dark:border-slate-700 flex gap-5 flex-wrap">
        <span class="flex items-center gap-1.5 text-[9px] font-black uppercase text-slate-400">
            <div class="w-2 h-2 rounded-full bg-teal-500"></div> Sangat Baik
        </span>
        <span class="flex items-center gap-1.5 text-[9px] font-black uppercase text-slate-400">
            <div class="w-2 h-2 rounded-full bg-blue-500"></div> Baik
        </span>
        <span class="flex items-center gap-1.5 text-[9px] font-black uppercase text-slate-400">
            <div class="w-2 h-2 rounded-full bg-amber-400"></div> Cukup
        </span>
        <span class="flex items-center gap-1.5 text-[9px] font-black uppercase text-slate-400">
            <div class="w-2 h-2 rounded-full bg-rose-500"></div> Kurang
        </span>
    </div>

    {{-- Load More Button --}}
    <div x-show="hasMore" class="px-5 pb-5">
        <button @click="loadMore()"
            class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl border border-dashed border-slate-300 dark:border-slate-600 text-xs font-bold text-slate-500 dark:text-slate-400 hover:border-teal-400 hover:text-teal-600 dark:hover:text-teal-400 hover:bg-teal-50/50 dark:hover:bg-teal-900/10 transition-all duration-200">
            <x-umpak::icon name="plus-circle" class="w-3.5 h-3.5" />
            Tampilkan kolom periode lebih banyak
            <span class="text-[10px] font-black px-1.5 py-0.5 rounded-md bg-slate-100 dark:bg-slate-700"
                x-text="'(' + totalHidden + ' tersisa)'"></span>
        </button>
    </div>
</div>