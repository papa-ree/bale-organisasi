@if($section)
    <section id="tim" class="py-20 bg-slate-50 dark:bg-slate-900 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="text-center mb-14" data-aos="fade-up">
                @if($section->meta('custom.badge'))
                    <span
                        class="inline-flex items-center gap-1.5 text-xs font-semibold text-teal-600 dark:text-teal-400 uppercase tracking-widest mb-3">
                        <span class="w-4 h-px bg-teal-400 inline-block"></span>
                        {{ $section->meta('custom.badge') }}
                        <span class="w-4 h-px bg-teal-400 inline-block"></span>
                    </span>
                @endif

                <h2 class="text-3xl font-bold text-slate-800 dark:text-white mt-1">
                    {{ $section->meta('title') }}
                </h2>

                @if($section->meta('subtitle'))
                    <p class="text-slate-500 dark:text-slate-400 mt-3 text-sm leading-relaxed max-w-xl mx-auto">
                        {{ $section->meta('subtitle') }}
                    </p>
                @endif
            </div>

            {{-- Unit Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-{{ $section->meta('custom.columns', 3) }} gap-6" data-aos="fade-up"
                data-aos-delay="100">
                @foreach($section->items as $index => $unit)
                    @php
                        $colors = ['teal'];
                        $color = $colors[$index % count($colors)];
                        $url = $unit['url'][0] ?? '#';
                        $icon = $unit['icon'][0] ?? 'briefcase';
                        $judul = $unit['judul'][0] ?? '';
                        $deskripsi = $unit['deskripsi'][0] ?? '';
                    @endphp

                    <a href="{{ $url }}"
                        class="group relative bg-white dark:bg-slate-800 rounded-3xl p-7 shadow-sm hover:shadow-xl transition-all duration-500 border border-slate-100 dark:border-slate-700 hover:-translate-y-1.5 overflow-hidden flex flex-col gap-4">

                        {{-- Decorative blob --}}
                        <div
                            class="absolute -top-8 -right-8 w-32 h-32 rounded-full opacity-0 group-hover:opacity-10 transition-opacity duration-500 bg-{{ $color }}-500 pointer-events-none">
                        </div>

                        {{-- Icon Badge --}}
                        <div
                            class="w-14 h-14 rounded-2xl flex items-center justify-center shadow-sm transition-transform duration-500 group-hover:scale-110 bg-{{ $color }}-50 dark:bg-{{ $color }}-900/30 text-{{ $color }}-600 dark:text-{{ $color }}-400">
                            <x-umpak::icon :name="$icon" fallback="briefcase" class="w-7 h-7" />
                        </div>

                        {{-- Content --}}
                        <div class="flex flex-col gap-2 flex-1">
                            <h3
                                class="font-bold text-slate-800 dark:text-white text-base leading-snug group-hover:text-{{ $color }}-600 dark:group-hover:text-{{ $color }}-400 transition-colors duration-300">
                                {{ $judul }}
                            </h3>
                            <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed">
                                {{ $deskripsi }}
                            </p>
                        </div>

                        {{-- Arrow indicator --}}
                        <div
                            class="flex items-center gap-1 text-xs font-semibold text-{{ $color }}-600 dark:text-{{ $color }}-400 opacity-0 group-hover:opacity-100 transition-all duration-300 -mb-1">
                            <span>Lihat Detail</span>
                            <x-umpak::icon name="arrow-right" class="w-3.5 h-3.5" />
                        </div>
                    </a>
                @endforeach
            </div>

            {{-- Footer Buttons --}}
            @if(count($section->buttons()) > 0)
                <div class="mt-12 text-center" data-aos="fade-up">
                    @foreach($section->buttons() as $btn)
                        <a href="{{ $btn['url'] }}"
                            class="inline-flex items-center gap-2 px-8 py-3 text-sm font-bold text-white bg-teal-600 hover:bg-teal-700 rounded-2xl transition-all shadow-lg shadow-teal-600/20 active:scale-95 {{ $btn['class'] ?? '' }}">
                            {{ $btn['label'] }}
                        </a>
                    @endforeach
                </div>
            @endif

        </div>
    </section>
@else
    <x-umpak::section-error name="Tim Kerja" />
@endif