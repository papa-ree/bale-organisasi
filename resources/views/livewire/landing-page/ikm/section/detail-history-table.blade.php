{{-- section/detail-history-table.blade.php --}}
{{-- Needs: $histori (Blade collection) --}}
{{-- Alpine: activePeriod, setPeriod(), visibleCount, hasMore, totalHidden, loadMore() --}}

<div class="bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl shadow-sm overflow-hidden">

    <div class="p-5 sm:p-6 border-b border-slate-100 dark:border-slate-700">
        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-1">Arsip Lengkap</p>
        <h3 class="text-base font-black text-slate-800 dark:text-white">Riwayat Semua Periode</h3>
    </div>

    <div class="overflow-x-auto scrollbar-gutter-stable scrollbar-thin scrollbar-track-slate-200 dark:scrollbar-track-slate-800 scrollbar-thumb-teal-500 dark:scrollbar-thumb-teal-600 hover:scrollbar-thumb-teal-600 dark:hover:scrollbar-thumb-teal-500 scrollbar-thumb-rounded-full scrollbar-track-rounded-full">
        <table class="ikm-table">
            <thead>
                <tr>
                    <th class="text-left">Periode</th>
                    <th class="text-right">Responden</th>
                    <th class="text-right">Skor IKM</th>
                    <th class="text-center">Predikat</th>
                    <th class="text-right">Δ Sebelumnya</th>
                </tr>
            </thead>
            <tbody wire:ignore>
                @php
                    $historiOrdered = $histori->reverse()->values();
                @endphp
                @foreach($historiOrdered as $i => $record)
                    @php
                        $pKey       = "{$record->tahun}-{$record->triwulan}";
                        $skor       = (float) $record->nilai_ikm;
                        $prevRecord = $historiOrdered->get($i + 1);
                        $delta      = $prevRecord ? round($skor - (float) $prevRecord->nilai_ikm, 2) : null;

                        if ($skor >= 88.31)      { $bc = 'bg-teal-100 dark:bg-teal-900/30 text-teal-700 dark:text-teal-400'; $bl = 'Sangat Baik'; }
                        elseif ($skor >= 76.61)  { $bc = 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400'; $bl = 'Baik'; }
                        elseif ($skor >= 65)     { $bc = 'bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400'; $bl = 'Cukup'; }
                        else                     { $bc = 'bg-rose-100 dark:bg-rose-900/30 text-rose-700 dark:text-rose-400'; $bl = 'Tidak Baik'; }
                    @endphp
                    <tr
                        x-show="{{ $i }} < visibleCount"
                        @click="setPeriod('{{ $pKey }}')"
                        class="cursor-pointer transition-all border-b border-slate-100 dark:border-slate-700 last:border-0 hover:bg-teal-50/50 dark:hover:bg-teal-900/10"
                        :class="activePeriod === '{{ $pKey }}' ? 'bg-teal-50/80 dark:bg-teal-900/10 [&>td:first-child]:border-l-4 [&>td:first-child]:border-teal-500' : ''">

                        <td class="transition-all">
                            <span class="font-bold text-sm transition-colors"
                                :class="activePeriod === '{{ $pKey }}' ? 'text-teal-600 dark:text-teal-400' : 'text-slate-700 dark:text-slate-300'">
                                TW{{ $record->triwulan }} {{ $record->tahun }}
                            </span>
                            <span x-show="activePeriod === '{{ $pKey }}'"
                                x-transition:enter="transition ease-out duration-150"
                                x-transition:enter-start="opacity-0 scale-90"
                                x-transition:enter-end="opacity-100 scale-100"
                                class="ml-2 text-[9px] font-black px-2 py-0.5 rounded-full bg-teal-100 dark:bg-teal-900/40 text-teal-600 dark:text-teal-400">
                                Aktif
                            </span>
                        </td>

                        <td class="text-right tabular-nums text-slate-500 dark:text-slate-400 text-sm">
                            {{ number_format($record->sampel ?? 0, 0, ',', '.') }}
                        </td>

                        <td class="text-right">
                            <span class="text-base font-black tabular-nums"
                                :class="activePeriod === '{{ $pKey }}' ? 'text-teal-600 dark:text-teal-400' : 'text-slate-700 dark:text-slate-300'">
                                {{ number_format($skor, 2) }}
                            </span>
                        </td>

                        <td class="text-center">
                            <span class="text-[10px] font-black px-2.5 py-1 rounded-lg {{ $bc }}">
                                {{ $bl }}
                            </span>
                        </td>

                        <td class="text-right">
                            @if($delta !== null)
                                <span class="text-sm font-bold tabular-nums {{ $delta > 0.3 ? 'text-emerald-600 dark:text-emerald-400' : ($delta < -0.3 ? 'text-rose-600 dark:text-rose-400' : 'text-slate-400 dark:text-slate-500') }}">
                                    {{ $delta > 0.3 ? '↑ +' : ($delta < -0.3 ? '↓ ' : '') }}{{ $delta }}
                                </span>
                            @else
                                <span class="text-slate-300 dark:text-slate-600 text-sm">—</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Load More Button --}}
    <div x-show="hasMore" class="p-4 border-t border-slate-100 dark:border-slate-700">
        <button
            @click="loadMore()"
            class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl border border-dashed border-slate-300 dark:border-slate-600 text-xs font-bold text-slate-500 dark:text-slate-400 hover:border-teal-400 hover:text-teal-600 dark:hover:text-teal-400 hover:bg-teal-50/50 dark:hover:bg-teal-900/10 transition-all duration-200">
            <x-umpak::icon name="plus-circle" class="w-3.5 h-3.5" />
            Tampilkan lebih banyak
            <span class="text-[10px] font-black px-1.5 py-0.5 rounded-md bg-slate-100 dark:bg-slate-700"
                x-text="'(' + totalHidden + ' tersisa)'"></span>
        </button>
    </div>
</div>
