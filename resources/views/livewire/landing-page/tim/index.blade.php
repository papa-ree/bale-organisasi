@if($section)
    <section id="tim" class="py-20 bg-slate-50 dark:bg-slate-800/50 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Header --}}
            <div class="text-center mb-12" data-aos="fade-up">
                @if($section->meta('custom.tagline'))
                    <span class="text-xs font-semibold text-teal-600 dark:text-teal-400 uppercase tracking-widest">
                        {{ $section->meta('custom.tagline') }}
                    </span>
                @endif
                <h2 class="text-3xl font-bold text-slate-800 dark:text-white mt-2">{{ $section->meta('title') }}</h2>
                <p class="text-slate-500 dark:text-slate-400 mt-3 text-sm leading-relaxed max-w-xl mx-auto">
                    {{ $section->meta('subtitle') }}
                </p>
            </div>

            {{-- Team Grid --}}
            <div class="grid grid-cols-2 md:grid-cols-{{ $section->meta('custom.columns', 4) }} gap-5" data-aos="fade-up"
                data-aos-delay="100">
                @foreach($section->items as $member)
                    @php
                        $color = $member['color'] ?? 'teal';
                        $gradient = $member['gradient'] ?? 'from-teal-500 to-sky-400';
                    @endphp
                    <div
                        class="group bg-white dark:bg-slate-800 rounded-3xl p-6 text-center shadow-sm hover:shadow-xl transition-all duration-500 border border-slate-100 dark:border-slate-700 hover:-translate-y-2">
                        {{-- Initials Avatar --}}
                        <div @class([
                            'w-20 h-20 mx-auto mb-4 rounded-2xl bg-linear-to-br flex items-center justify-center text-white font-black text-2xl shadow-lg ring-4 ring-white dark:ring-slate-700 transition-transform group-hover:scale-110 duration-500',
                            $gradient
                        ])>
                            {{ $member['initials'] ?? '??' }}
                        </div>

                        {{-- Info --}}
                        <h4
                            class="font-bold text-slate-800 dark:text-white text-sm leading-tight group-hover:text-teal-600 transition-colors">
                            {{ $member['title'] }}
                        </h4>
                        <p class="text-[10px] sm:text-xs text-slate-500 dark:text-slate-400 mt-1.5 font-medium">
                            {{ $member['description'] }}
                        </p>

                        {{-- Badge --}}
                        @if(!empty($member['badge']))
                            <span @class([
                                'inline-block mt-3 px-3 py-1 text-[10px] font-bold rounded-lg uppercase tracking-tighter',
                                "text-{$color}-700 dark:text-{$color}-400 bg-{$color}-50 dark:bg-{$color}-900/30"
                            ])>
                                {{ $member['badge'] }}
                            </span>
                        @endif
                    </div>
                @endforeach
            </div>

            {{-- Footer Buttons (jika ada) --}}
            @if(count($section->buttons()) > 0)
                <div class="mt-12 text-center" data-aos="fade-up">
                    @foreach($section->buttons() as $btn)
                        <a href="{{ $btn['url'] }}"
                            class="inline-flex items-center gap-2 px-8 py-3 text-sm font-bold text-white bg-teal-600 hover:bg-teal-700 rounded-2xl transition-all shadow-lg shadow-teal-600/20 active:scale-95 {{ $btn['class'] }}">
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