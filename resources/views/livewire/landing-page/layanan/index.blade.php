@if($section)
    <section id="layanan" class="pt-24 pb-16 bg-white dark:bg-slate-900 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Header Section --}}
            <div class="text-center mb-12" data-aos="fade-up">
                <span class="text-xs font-semibold ... uppercase tracking-widest">
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
                        $hoverColor = $item['hover_color'] ?? 'teal';
                        $borderColor = $item['border_color'] ?? 'teal-500';
                    @endphp
                    <a href="{{ $item['url'] ?? '#' }}" @class([
                        'group bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-5 hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 border-l-4',
                        "border-l-{$borderColor} hover:border-{$hoverColor}-300 dark:hover:border-{$hoverColor}-600"
                    ])>

                        <div @class([
                            'w-12 h-12 rounded-xl flex items-center justify-center text-2xl mb-4 transition-transform group-hover:scale-110 duration-300',
                            "bg-{$hoverColor}-50 dark:bg-{$hoverColor}-900/30"
                        ])>
                            {{ $item['icon'] ?? '📊' }}
                        </div>

                        <h3 @class([
                            'font-bold text-slate-800 dark:text-white text-sm mb-1 transition-colors',
                            "group-hover:text-{$hoverColor}-600 dark:group-hover:text-{$hoverColor}-400"
                        ])>
                            {{ $item['title'] }}
                        </h3>

                        <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                            {{ $item['description'] }}
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