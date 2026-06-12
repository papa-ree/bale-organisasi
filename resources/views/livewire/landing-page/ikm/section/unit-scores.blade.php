<div class="space-y-4" wire:poll.10s>
    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 flex justify-between items-center">
        <span>Skor per Unit Kerja</span>
        <span class="text-[9px] lowercase font-normal italic opacity-60">live update</span>
    </p>

    @foreach($scores as $opd)
        <div wire:key="sub-score-{{ Str::slug($opd->nama_opd) }}">
            <div class="flex justify-between text-xs mb-1.5">
                <span class="text-slate-700 dark:text-slate-300 font-medium">{{ $opd->nama_opd }}</span>
                <span @class([
                    'font-bold',
                    'text-teal-600 dark:text-teal-400' => $opd->nilai_ikm >= 80,
                    'text-amber-600' => $opd->nilai_ikm < 80,
                ])>{{ number_format($opd->nilai_ikm, 1) }}</span>
            </div>
            <div class="h-2 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
                <div @class([
                    'h-full rounded-full transition-all duration-1000',
                    'bg-linear-to-r from-teal-500 to-sky-400 shadow-sm' => $opd->nilai_ikm >= 80,
                    'bg-linear-to-r from-amber-400 to-amber-500' => $opd->nilai_ikm < 80,
                ]) style="width: {{ $opd->nilai_ikm }}%"></div>
            </div>
        </div>
    @endforeach
</div>