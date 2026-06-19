@if($section)
    @php
        $tabs = $section->meta('custom.tabs', []);
        $items = collect($section->items);
    @endphp

    <section id="regulasi" class="py-12 sm:py-20 bg-white dark:bg-slate-900 transition-colors duration-300"
        x-data="{ activeTab: '{{ Str::slug($tabs[0] ?? '') }}' }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Header --}}
            <div class="text-center mb-8 sm:mb-12">
                @if($section->meta('custom.tagline'))
                    <span class="text-[10px] sm:text-xs font-semibold text-teal-600 dark:text-teal-400 uppercase tracking-widest bg-teal-50 dark:bg-teal-900/30 px-3 py-1 rounded-full">
                        {{ $section->meta('custom.tagline') }}
                    </span>
                @endif
                <h2 class="text-2xl sm:text-3xl font-black text-slate-800 dark:text-white mt-3 sm:mt-4">{{ $section->meta('title') }}</h2>
                <p class="text-slate-500 dark:text-slate-400 mt-3 max-w-xl mx-auto text-xs sm:text-sm leading-relaxed px-2">
                    {{ $section->meta('subtitle') }}</p>
            </div>

            <div>
                {{-- Tab Buttons: Horizontal Scroll on Mobile, Wrapped on Desktop --}}
                <div class="flex sm:justify-center items-center gap-2 sm:gap-3 mb-8 sm:mb-10 overflow-x-auto no-scrollbar pb-4 sm:pb-0 -mx-4 px-4 sm:mx-0 sm:px-0">
                    @foreach($tabs as $tab)
                        <x-bale-organisasi::button 
                            @click="activeTab = '{{ Str::slug($tab) }}'"
                            variant="ghost"
                            ::class="activeTab === '{{ Str::slug($tab) }}' ? '!bg-teal-600 !text-white !shadow-lg !shadow-teal-600/20' : 'bg-slate-100/50 dark:bg-slate-800/50'"
                            class="whitespace-nowrap sm:px-8 border-transparent"
                        >
                            {{ $tab }}
                        </x-bale-organisasi::button>
                    @endforeach
                </div>

                {{-- Tab Content Containers --}}
                <div class="relative min-h-[300px]">
                    @foreach($tabs as $tab)
                        <div x-show="activeTab === '{{ Str::slug($tab) }}'" x-cloak x-transition:enter="transition ease-out duration-500"
                            x-transition:enter-start="opacity-0 translate-y-4"
                            x-transition:enter-end="opacity-100 translate-y-0" class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4 lg:gap-5">

                            @php
                                $filteredItems = $items->filter(fn($i) => ($i['kategori'][0] ?? '') === $tab)->take(4);
                            @endphp

                            @forelse($filteredItems as $item)
                                @php
                                    $judul = $item['judul'][0] ?? 'Dokumen Tanpa Judul';
                                    $icon = $item['icon'][0] ?? 'file-text';
                                    $desc = $item['deskripsi'][0] ?? '';
                                    $tahun = $item['tahun'][0] ?? '';
                                    $downloadUrl = $item['uploads'][0]['url'] ?? $item['url'][0] ?? '#';
                                @endphp
                                <div
                                    class="group flex items-center gap-3 sm:gap-5 p-4 sm:p-5 bg-white dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800 rounded-[24px] sm:rounded-3xl hover:shadow-2xl hover:shadow-teal-900/5 hover:-translate-y-1 transition-all duration-500 hover:border-teal-500/30">
                                    <div
                                        class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-slate-50 dark:bg-slate-900/50 flex items-center justify-center text-teal-600 dark:text-teal-400 shrink-0 group-hover:bg-linear-to-br group-hover:from-[#0c3a47] group-hover:to-[#075985] group-hover:text-white transition-all duration-500 shadow-sm border border-slate-100 dark:border-slate-700/50">
                                        <x-umpak::icon :name="$icon" class="w-5 h-5 sm:w-6 sm:h-6" />
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="mb-1">
                                            <h4 class="font-bold text-slate-800 dark:text-white text-xs sm:text-base line-clamp-2 leading-snug sm:leading-relaxed group-hover:text-teal-600 dark:group-hover:text-teal-400 transition-colors">
                                                {{ $judul }}
                                            </h4>
                                        </div>
                                        <div class="flex items-center gap-2 sm:gap-3">
                                            @if($tahun)
                                                <span class="text-[9px] sm:text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-tighter">{{ $tahun }}</span>
                                            @endif
                                            <span class="text-[9px] sm:text-[10px] text-slate-400 dark:text-slate-500 font-medium italic opacity-70 truncate">{{ $desc ?: 'Dokumen Resmi' }}</span>
                                        </div>
                                    </div>
                                    <a href="{{ $downloadUrl }}" target="_blank"
                                        class="shrink-0 w-9 h-9 sm:w-11 sm:h-11 flex items-center justify-center rounded-xl sm:rounded-2xl text-teal-600 dark:text-teal-400 border border-teal-100 dark:border-teal-800 hover:bg-linear-to-br from-[#0c3a47] via-[#0d6b7a] to-[#075985] hover:text-white dark:hover:text-white transition-all shadow-sm active:scale-90 bg-teal-50/30 dark:bg-transparent">
                                        <x-umpak::icon name="download" class="w-4 h-4" />
                                    </a>
                                </div>
                            @empty
                                <div class="col-span-1 md:col-span-2 py-16 text-center border-2 border-dashed border-slate-100 dark:border-slate-800/50 rounded-3xl">
                                    <x-umpak::icon name="folder-open" class="w-10 h-10 text-slate-200 dark:text-slate-800 mx-auto mb-4" />
                                    <p class="text-slate-400 text-sm font-medium italic">Belum ada dokumen untuk kategori ini.</p>
                                </div>
                            @endforelse
                        </div>
                    @endforeach
                </div>

                {{-- Footer Buttons --}}
                @if(count($section->buttons()) > 0)
                    <div class="mt-10 sm:mt-14 flex justify-center px-4 sm:px-0">
                        @foreach($section->buttons() as $btn)
                            <x-bale-organisasi::button 
                                :href="$btn['url']" 
                                variant="primary" 
                                size="lg" 
                                wire:navigate
                                class="w-full sm:w-auto group"
                            >
                                {{ $btn['label'] }}
                                <x-umpak::icon name="move-right" class="w-4 h-4 transition-transform group-hover:translate-x-1" />
                            </x-bale-organisasi::button>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </section>
@else
    <x-umpak::section-error name="Regulasi" />
@endif