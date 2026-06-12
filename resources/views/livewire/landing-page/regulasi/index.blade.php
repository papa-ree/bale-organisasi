@if($section)
    @php
        $tabs = $section->meta('custom.tabs', []);
        $items = collect($section->items);
    @endphp

    <section id="regulasi" class="py-20 bg-white dark:bg-slate-900 transition-colors duration-300"
        x-data="{ activeTab: '{{ $tabs[0] ?? '' }}' }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Header --}}
            <div class="text-center mb-10" data-aos="fade-up">
                @if($section->meta('custom.tagline'))
                    <span class="text-xs font-semibold text-teal-600 dark:text-teal-400 uppercase tracking-widest">
                        {{ $section->meta('custom.tagline') }}
                    </span>
                @endif
                <h2 class="text-3xl font-bold text-slate-800 dark:text-white mt-2">{{ $section->meta('title') }}</h2>
                <p class="text-slate-500 dark:text-slate-400 mt-3 max-w-xl mx-auto text-sm leading-relaxed">
                    {{ $section->meta('subtitle') }}</p>
            </div>

            <div data-aos="fade-up">
                {{-- Tab Buttons --}}
                <div class="flex gap-2 mb-8 flex-wrap">
                    @foreach($tabs as $tab)
                        <button @click="activeTab = '{{ $tab }}'"
                            :class="activeTab === '{{ $tab }}' 
                                    ? 'bg-teal-600 text-white shadow-lg shadow-teal-600/20' 
                                    : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-teal-50 dark:hover:bg-teal-900/20'"
                            class="px-4 py-2 text-sm font-bold rounded-xl transition-all duration-300">
                            {{ $tab }}
                        </button>
                    @endforeach
                </div>

                {{-- Tab Content Containers --}}
                <div class="relative min-h-[300px]">
                    @foreach($tabs as $tabIndex => $tab)
                        <div x-show="activeTab === '{{ $tab }}'" x-cloak x-transition:enter="transition ease-out duration-500"
                            x-transition:enter-start="opacity-0 translate-y-4"
                            x-transition:enter-end="opacity-100 translate-y-0" class="space-y-3">

                            @foreach($items->where('category', $tab) as $item)
                                <div
                                    class="group flex items-center gap-4 p-4 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl hover:shadow-md hover:-translate-y-0.5 transition-all duration-300">
                                    <div
                                        class="w-12 h-12 rounded-xl bg-teal-50 dark:bg-teal-900/30 flex items-center justify-center text-2xl flex-shrink-0 group-hover:scale-110 transition-transform">
                                        {{ $item['icon'] ?? '📄' }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p
                                            class="font-bold text-slate-800 dark:text-white text-sm truncate group-hover:text-teal-600 dark:group-hover:text-teal-400 transition-colors">
                                            {{ $item['title'] }}
                                        </p>
                                        <span
                                            class="text-xs text-slate-400 dark:text-slate-500 font-medium">{{ $item['description'] }}</span>
                                    </div>
                                    <a href="{{ $item['url'] ?? '#' }}"
                                        class="flex-shrink-0 px-4 py-2 text-xs font-bold text-teal-600 dark:text-teal-400 border border-teal-200 dark:border-teal-700 rounded-xl hover:bg-teal-600 hover:text-white dark:hover:bg-teal-600 transition-all">
                                        Unduh
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>

                {{-- Footer Buttons --}}
                @if(count($section->buttons()) > 0)
                    <div class="mt-8 flex justify-start">
                        @foreach($section->buttons() as $btn)
                            <a href="{{ $btn['url'] }}"
                                class="inline-flex items-center gap-2 text-sm font-bold text-teal-600 dark:text-teal-400 hover:text-teal-700 dark:hover:text-teal-300 transition-all group">
                                {{ $btn['label'] }}
                                <span class="group-hover:translate-x-1 transition-transform">→</span>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </section>
@else
    <x-umpak::section-error name="Regulasi" />
@endif