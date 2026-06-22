{{-- section/detail-unsur-grid.blade.php --}}
{{-- Alpine: unsur (reactive getter array of {label, nilai}) --}}

<div wire:ignore
    class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl p-4 sm:p-6 shadow-sm">

    {{-- Header --}}
    <div class="flex items-start justify-between mb-5 gap-3 flex-wrap">
        <div>
            <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-1">Breakdown</p>
            <h3 class="text-base font-black text-slate-800 dark:text-white">Nilai per Unsur Layanan</h3>
        </div>
        <span class="text-[10px] font-bold px-2.5 py-1 rounded-full bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400">
            PermenPAN-RB No. 14/2017
        </span>
    </div>

    {{-- 9 Unsur Grid --}}
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-3">
        <template x-for="(u, idx) in unsur" :key="idx">
            <div class="rounded-xl p-4 border transition-all duration-300"
                :class="{
                    'bg-teal-50/60 dark:bg-teal-900/10 border-teal-100 dark:border-teal-800/30' : u.nilai >= 3.5324,
                    'bg-blue-50/60 dark:bg-blue-900/10 border-blue-100 dark:border-blue-800/30' : u.nilai >= 3.0644 && u.nilai < 3.5324,
                    'bg-amber-50/60 dark:bg-amber-900/10 border-amber-100 dark:border-amber-800/30': u.nilai >= 2.60 && u.nilai < 3.0644,
                    'bg-rose-50/60 dark:bg-rose-900/10 border-rose-100 dark:border-rose-800/30'  : u.nilai < 2.60,
                }">

                {{-- Card Header --}}
                <div class="flex items-start justify-between gap-2 mb-3">
                    <div class="flex items-start gap-2 min-w-0">
                        <span class="w-6 h-6 rounded-lg flex items-center justify-center text-[10px] font-black shrink-0 tabular-nums"
                            :class="{
                                'bg-teal-100 dark:bg-teal-900/30 text-teal-700 dark:text-teal-400' : u.nilai >= 3.5324,
                                'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400' : u.nilai >= 3.0644 && u.nilai < 3.5324,
                                'bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400': u.nilai >= 2.60 && u.nilai < 3.0644,
                                'bg-rose-100 dark:bg-rose-900/30 text-rose-700 dark:text-rose-400'  : u.nilai < 2.60,
                            }"
                            x-text="idx + 1">
                        </span>
                        <p class="text-xs font-semibold leading-snug text-slate-700 dark:text-slate-200" x-text="u.label"></p>
                    </div>
                    <span class="text-sm font-black tabular-nums shrink-0"
                        :class="{
                            'text-teal-600 dark:text-teal-400' : u.nilai >= 3.5324,
                            'text-blue-600 dark:text-blue-400' : u.nilai >= 3.0644 && u.nilai < 3.5324,
                            'text-amber-600 dark:text-amber-400': u.nilai >= 2.60 && u.nilai < 3.0644,
                            'text-rose-600 dark:text-rose-400'  : u.nilai < 2.60,
                        }"
                        x-text="u.nilai.toFixed(2)">
                    </span>
                </div>

                {{-- Progress Bar --}}
                <div class="h-1.5 rounded-full overflow-hidden bg-slate-100/80 dark:bg-slate-700/50">
                    <div class="h-full rounded-full transition-all duration-700"
                        :class="{
                            'bg-teal-500' : u.nilai >= 3.5324,
                            'bg-blue-500' : u.nilai >= 3.0644 && u.nilai < 3.5324,
                            'bg-amber-400': u.nilai >= 2.60 && u.nilai < 3.0644,
                            'bg-rose-500' : u.nilai < 2.60,
                        }"
                        :style="`width:${(u.nilai / 4) * 100}%`">
                    </div>
                </div>
            </div>
        </template>
    </div>

    {{-- Legend --}}
    <div class="flex items-center gap-4 mt-4 pt-4 border-t border-slate-100 dark:border-slate-700 flex-wrap">
        <span class="flex items-center gap-1.5 text-[9px] font-bold text-slate-400 uppercase">
            <span class="w-2 h-2 rounded-full bg-teal-500 inline-block"></span> Sangat Baik ≥ 3.53
        </span>
        <span class="flex items-center gap-1.5 text-[9px] font-bold text-slate-400 uppercase">
            <span class="w-2 h-2 rounded-full bg-blue-500 inline-block"></span> Baik ≥ 3.06
        </span>
        <span class="flex items-center gap-1.5 text-[9px] font-bold text-slate-400 uppercase">
            <span class="w-2 h-2 rounded-full bg-amber-400 inline-block"></span> Cukup ≥ 2.60
        </span>
        <span class="flex items-center gap-1.5 text-[9px] font-bold text-slate-400 uppercase">
            <span class="w-2 h-2 rounded-full bg-rose-500 inline-block"></span> Tidak Baik
        </span>
    </div>
</div>
