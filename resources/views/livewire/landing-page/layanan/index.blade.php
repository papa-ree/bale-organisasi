@if($section)
    <section id="layanan" class="pt-24 pb-16 bg-white dark:bg-slate-900 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Header Section --}}
            <div class="text-center mb-12" data-aos="fade-up">
                <span class="text-xs font-semibold uppercase tracking-widest text-slate-500 dark:text-slate-400">
                    {{ $section->meta('custom.tagline') }}
                </span>

                <h2 class="text-3xl font-bold text-slate-800 dark:text-white mt-2">
                    {{ $section->meta('title') }}
                </h2>

                @if($section->meta('subtitle'))
                    <p class="text-slate-500 dark:text-slate-400 mt-3 max-w-xl mx-auto text-sm leading-relaxed">
                        {{ $section->meta('subtitle') }}
                    </p>
                @endif
            </div>

            {{-- Grid Items --}}
            <div class="grid grid-cols-2 lg:grid-cols-{{ $section->meta('custom.columns', 4) }} gap-4" data-aos="fade-up"
                data-aos-delay="100">
                @foreach($section->items as $item)
                    @php
                        // Memetakan secara aman warna border dan hover yang bisa ditampung agar tercompile oleh Tailwind
                        $allowedColors = [
                            'teal' => [
                                'border-l' => 'border-l-teal-500',
                                'hover-border' => 'hover:border-teal-300 dark:hover:border-teal-600',
                                'bg' => 'bg-teal-50 dark:bg-teal-900/30 text-teal-600 dark:text-teal-400',
                                'hover-text' => 'group-hover:text-teal-600 dark:group-hover:text-teal-400',
                            ],
                            'emerald' => [
                                'border-l' => 'border-l-emerald-500',
                                'hover-border' => 'hover:border-emerald-300 dark:hover:border-emerald-600',
                                'bg' => 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400',
                                'hover-text' => 'group-hover:text-emerald-600 dark:group-hover:text-emerald-400',
                            ],
                            'blue' => [
                                'border-l' => 'border-l-blue-500',
                                'hover-border' => 'hover:border-blue-300 dark:hover:border-blue-600',
                                'bg' => 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400',
                                'hover-text' => 'group-hover:text-blue-600 dark:group-hover:text-blue-400',
                            ],
                            'indigo' => [
                                'border-l' => 'border-l-indigo-500',
                                'hover-border' => 'hover:border-indigo-300 dark:hover:border-indigo-600',
                                'bg' => 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400',
                                'hover-text' => 'group-hover:text-indigo-600 dark:group-hover:text-indigo-400',
                            ],
                            'purple' => [
                                'border-l' => 'border-l-purple-500',
                                'hover-border' => 'hover:border-purple-300 dark:hover:border-purple-600',
                                'bg' => 'bg-purple-50 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400',
                                'hover-text' => 'group-hover:text-purple-600 dark:group-hover:text-purple-400',
                            ],
                            'rose' => [
                                'border-l' => 'border-l-rose-500',
                                'hover-border' => 'hover:border-rose-300 dark:hover:border-rose-600',
                                'bg' => 'bg-rose-50 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400',
                                'hover-text' => 'group-hover:text-rose-600 dark:group-hover:text-rose-400',
                            ],
                            'amber' => [
                                'border-l' => 'border-l-amber-500',
                                'hover-border' => 'hover:border-amber-300 dark:hover:border-amber-600',
                                'bg' => 'bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400',
                                'hover-text' => 'group-hover:text-amber-600 dark:group-hover:text-amber-400',
                            ],
                            'sky' => [
                                'border-l' => 'border-l-sky-500',
                                'hover-border' => 'hover:border-sky-300 dark:hover:border-sky-600',
                                'bg' => 'bg-sky-50 dark:bg-sky-900/30 text-sky-600 dark:text-sky-400',
                                'hover-text' => 'group-hover:text-sky-600 dark:group-hover:text-sky-400',
                            ],
                            'slate' => [
                                'border-l' => 'border-l-slate-500',
                                'hover-border' => 'hover:border-slate-300 dark:hover:border-slate-600',
                                'bg' => 'bg-slate-50 dark:bg-slate-900/30 text-slate-600 dark:text-slate-400',
                                'hover-text' => 'group-hover:text-slate-600 dark:group-hover:text-slate-400',
                            ],
                            'cyan' => [
                                'border-l' => 'border-l-cyan-500',
                                'hover-border' => 'hover:border-cyan-300 dark:hover:border-cyan-600',
                                'bg' => 'bg-cyan-50 dark:bg-cyan-900/30 text-cyan-600 dark:text-cyan-400',
                                'hover-text' => 'group-hover:text-cyan-600 dark:group-hover:text-cyan-400',
                            ],
                            'green' => [
                                'border-l' => 'border-l-green-500',
                                'hover-border' => 'hover:border-green-300 dark:hover:border-green-600',
                                'bg' => 'bg-green-50 dark:bg-green-900/30 text-green-600 dark:text-green-400',
                                'hover-text' => 'group-hover:text-green-600 dark:group-hover:text-green-400',
                            ],
                        ];

                        $hoverColorRaw = $item['hover_color'] ?? 'teal';
                        $borderColorRaw = $item['border_color'] ?? 'teal-500';

                        // Ekstrak nama base color saja (misal: "teal-500" => "teal")
                        $hoverColor = strtolower(explode('-', $hoverColorRaw)[0]);
                        $borderColor = strtolower(explode('-', $borderColorRaw)[0]);

                        // Tentukan theme, fallback ke default 'teal'
                        $hoverTheme = $allowedColors[$hoverColor] ?? $allowedColors['teal'];
                        $borderTheme = $allowedColors[$borderColor] ?? $allowedColors['teal'];
                    @endphp
                    <a href="{{ $item['url'] ?? '#' }}" @class([
                        'group bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-5 hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 border-l-4',
                        $borderTheme['border-l'],
                        $hoverTheme['hover-border']
                    ])>

                        <div @class([
                            'w-12 h-12 rounded-xl flex items-center justify-center mb-4 transition-transform group-hover:scale-110 duration-300',
                            $hoverTheme['bg']
                        ])>
                            <x-umpak::icon :name="$item['icon'][0] ?? 'box'" fallback="box" class="w-6 h-6" />
                        </div>

                        <h3 @class([
                            'font-bold text-slate-800 dark:text-white text-sm mb-1 transition-colors',
                            $hoverTheme['hover-text']
                        ])>
                            {{ $item['judul'][0] ?? '' }}
                        </h3>

                        <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                            {{ $item['deskripsi'][0] ?? '' }}
                        </p>
                    </a>
                @endforeach
            </div>

            {{-- Section Buttons (jika ada) --}}
            @if(count($section->buttons()) > 0)
                <div class="mt-12 text-center" data-aos="fade-up">
                    @foreach($section->buttons() as $btn)
                        <a href="{{ $btn['url'] }}"
                            class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-semibold text-white bg-teal-600 hover:bg-teal-700 rounded-xl transition-all shadow-md {{ $btn['class'] }}">
                            {{ $btn['label'] }}
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
@else
    <x-umpak::section-error name="Layanan" />
@endif