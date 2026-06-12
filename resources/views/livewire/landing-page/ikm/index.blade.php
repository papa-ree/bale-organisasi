@if($section)
    <section id="ikm" class="py-20 bg-slate-50 dark:bg-slate-800/50 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Header --}}
            <div class="text-center mb-12" data-aos="fade-up">
                <span
                    class="text-xs font-semibold text-teal-600 dark:text-teal-400 uppercase tracking-widest">{{ $tagline }}</span>
                <h2 class="text-3xl font-bold text-slate-800 dark:text-white mt-2">{{ $section->meta('title') }}</h2>
                <p class="text-slate-500 dark:text-slate-400 mt-3 max-w-xl mx-auto text-sm leading-relaxed">
                    {{ $section->meta('subtitle') }}
                </p>
            </div>

            <div class="grid lg:grid-cols-2 gap-8">
                {{-- Left Column: Chart & Unit Scores --}}
                <div data-aos="fade-up"
                    class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm p-6 border border-slate-100 dark:border-slate-700">
                    <h3 class="font-bold text-slate-700 dark:text-slate-200 mb-4 text-sm">Indeks Kepuasan Keseluruhan</h3>

                    <div class="flex items-center justify-center mb-8 relative">
                        @php
                            $percentage = (float) $avgScore;
                            $radius = 75;
                            $circumference = 2 * pi() * $radius;
                            $offset = $circumference - ($percentage / 100) * $circumference;
                        @endphp
                        <svg width="180" height="180" viewBox="0 0 200 200" class="transform -rotate-90">
                            <circle cx="100" cy="100" r="{{ $radius }}" fill="none" stroke="currentColor" stroke-width="16"
                                class="text-slate-100 dark:text-slate-700" />
                            <circle cx="100" cy="100" r="{{ $radius }}" fill="none" stroke="url(#donutGrad)"
                                stroke-width="16" stroke-dasharray="{{ $circumference }}" stroke-dashoffset="{{ $offset }}"
                                stroke-linecap="round" class="transition-all duration-1000 ease-out" />
                            <defs>
                                <linearGradient id="donutGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" stop-color="#0d9488" />
                                    <stop offset="100%" stop-color="#38bdf8" />
                                </linearGradient>
                            </defs>
                        </svg>
                        <div class="absolute flex flex-col items-center justify-center text-center">
                            <span class="text-3xl font-black text-slate-800 dark:text-white">{{ $avgScore }}</span>
                            <span class="text-[10px] text-slate-400 uppercase font-bold tracking-tighter">Skor Indeks</span>
                        </div>
                    </div>

                    <div class="space-y-4" x-data="{ 
                                    allData: {{ $allScoresJson }},
                                    visibleData: [],
                                    shuffle() {
                                        // Ambil 5 data acak
                                        this.visibleData = this.allData
                                            .sort(() => 0.5 - Math.random())
                                            .slice(0, 5);
                                    }
                                }" x-init="shuffle(); setInterval(() => shuffle(), 8000)">

                        <p
                            class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 flex justify-between items-center">
                            <span>Skor per Unit Kerja</span>
                            <span class="text-[9px] lowercase font-normal italic opacity-60">smart transition</span>
                        </p>

                        {{-- Kuncinya ada pada :key='index' agar elemen DOM tidak di-destroy --}}
                        <template x-for="(item, index) in visibleData" :key="index">
                            <div class="relative py-2">
                                {{-- Pembungkus Teks dengan Transisi Fade --}}
                                <div :key="item.name" {{-- Key unik memicu re-render elemen ini saja --}}
                                    x-transition:enter="transition ease-out duration-500"
                                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                    x-transition:leave="transition ease-in duration-300"
                                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                    class="flex justify-between text-xs mb-1.5 pt-2">

                                    {{-- Nama Instansi --}}
                                    <span class="text-slate-700 dark:text-slate-300 font-medium" x-text="item.name"></span>

                                    {{-- Angka Skor --}}
                                    <span class="font-bold tabular-nums"
                                        :class="item.score >= 80 ? 'text-teal-600 dark:text-teal-400' : 'text-amber-600'"
                                        x-text="item.score.toFixed(1)"></span>
                                </div>

                                {{-- Garis Progress: Tetap stabil dan sliding --}}
                                <div class="h-2 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden shadow-inner">
                                    <div class="h-full rounded-full transition-all duration-1000 ease-in-out shadow-sm"
                                        :class="item.score >= 80 ? 'bg-linear-to-r from-teal-500 to-sky-400 shadow-teal-500/20' : 'bg-linear-to-r from-amber-400 to-amber-500 shadow-amber-500/20'"
                                        :style="`width: ${item.score}%`"></div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Right Column: Metrics & Unsur Table --}}
                <div class="space-y-6" data-aos="fade-up" data-aos>
                    <div class="grid grid-cols-3 gap-4">
                        <div
                            class="bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-slate-100 dark:border-slate-700 text-center">
                            <div class="text-2xl font-black text-teal-600 dark:text-teal-400">{{ $totalResponden }}</div>
                            <div class="text-[10px] text-slate-500 dark:text-slate-400 font-bold uppercase mt-1">Responden
                            </div>
                        </div>
                        <div
                            class="bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-slate-100 dark:border-slate-700 text-center">
                            <div class="text-xs font-black text-teal-600 dark:text-teal-400 leading-tight">{{ $period }}
                            </div>
                            <div class="text-[10px] text-slate-500 dark:text-slate-400 font-bold uppercase mt-1">Periode
                            </div>
                        </div>
                        <div
                            class="bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-slate-100 dark:border-slate-700 text-center flex flex-col items-center justify-center">
                            <div
                                class="px-2 py-1 bg-teal-100 dark:bg-teal-900/30 text-teal-700 dark:text-teal-400 text-[10px] font-black rounded-lg">
                                {{ (float) $avgScore >= 80 ? 'BAIK' : 'CUKUP' }}
                            </div>
                            <div class="text-[10px] text-slate-500 dark:text-slate-400 font-bold uppercase mt-2">Predikat
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
                        <div
                            class="px-5 py-4 border-b border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-700/30 font-bold text-slate-700 dark:text-slate-200 text-sm">
                            Nilai per Unsur Layanan
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-xs">
                                <thead>
                                    <tr
                                        class="text-slate-400 dark:text-slate-500 border-b border-slate-100 dark:border-slate-700 text-left">
                                        <th class="px-5 py-3 font-bold uppercase tracking-tighter">Unsur</th>
                                        <th class="px-5 py-3 text-center font-bold uppercase tracking-tighter">Nilai</th>
                                        <th class="px-5 py-3 text-center font-bold uppercase tracking-tighter">Mutu</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50 dark:divide-slate-700">
                                    @foreach($unsurList as $u)
                                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors">
                                            <td class="px-5 py-3 text-slate-700 dark:text-slate-300">{{ $u['label'] }}</td>
                                            <td @class([
                                                'px-5 py-3 text-center font-black',
                                                'text-teal-600 dark:text-teal-400' => $u['nilai'] >= 3.2,
                                                'text-amber-600' => $u['nilai'] < 3.2,
                                            ])>{{ number_format($u['nilai'], 2) }}</td>
                                            <td class="px-5 py-3 text-center">
                                                <span @class([
                                                    'px-2 py-0.5 rounded-md text-[10px] font-black',
                                                    'bg-teal-100 dark:bg-teal-900/30 text-teal-700 dark:text-teal-400' => $u['nilai'] >= 3.2,
                                                    'bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400' => $u['nilai'] < 3.2,
                                                ])>
                                                    {{ $u['nilai'] >= 3.2 ? 'B' : 'C' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="flex items-center justify-between px-1">
                        <p class="text-[10px] text-slate-400 italic">*Data Terkini.</p>
                        @foreach($section->buttons() as $btn)
                            <a href="{{ $btn['url'] }}" wire:navigate.hover @class([
                                'inline-flex items-center gap-1.5 text-xs font-bold transition-all',
                                $btn['class'] ?: 'text-teal-600 dark:text-teal-400 hover:text-teal-700'
                            ])>
                                {{ $btn['label'] }} <x-umpak::icon name="arrow-right" class="w-3 h-3" />
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
@else
    <x-umpak::section-error name="IKM" />
@endif