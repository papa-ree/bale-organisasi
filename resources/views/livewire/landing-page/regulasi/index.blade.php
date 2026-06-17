@if($section)
    @php
        $tabs = $section->meta('custom.tabs', []);
        $items = collect($section->items);
    @endphp

    <section id="regulasi" class="py-20 bg-white dark:bg-slate-900 transition-colors duration-300"
        x-data="{ activeTab: '{{ Str::slug($tabs[0] ?? '') }}' }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Header --}}
            <div class="text-center mb-12">
                @if($section->meta('custom.tagline'))
                    <span class="text-xs font-semibold text-teal-600 dark:text-teal-400 uppercase tracking-widest">
                        {{ $section->meta('custom.tagline') }}
                    </span>
                @endif
                <h2 class="text-3xl font-bold text-slate-800 dark:text-white mt-2">{{ $section->meta('title') }}</h2>
                <p class="text-slate-500 dark:text-slate-400 mt-3 max-w-xl mx-auto text-sm leading-relaxed">
                    {{ $section->meta('subtitle') }}</p>
            </div>

            <div>
                {{-- Tab Buttons --}}
                <div class="flex gap-3 mb-10 flex-wrap justify-center">
                    @foreach($tabs as $tab)
                        <button @click="activeTab = '{{ Str::slug($tab) }}'"
                            :class="activeTab === '{{ Str::slug($tab) }}' 
                                    ? 'bg-linear-to-br from-[#0c3a47] via-[#0d6b7a] to-[#075985] text-white shadow-lg shadow-teal-900/20' 
                                    : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-teal-50 dark:hover:bg-teal-900/20'"
                            class="px-6 py-3 text-sm font-bold rounded-2xl transition-all duration-300 active:scale-95">
                            {{ $tab }}
                        </button>
                    @endforeach
                </div>

                {{-- Tab Content Containers --}}
                <div class="relative min-h-[300px]">
                    @foreach($tabs as $tab)
                        <div x-show="activeTab === '{{ Str::slug($tab) }}'" x-cloak x-transition:enter="transition ease-out duration-500"
                            x-transition:enter-start="opacity-0 translate-y-4"
                            x-transition:enter-end="opacity-100 translate-y-0" class="grid sm:grid-cols-2 gap-4">

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
                                    $fileType = $item['uploads'][0]['file_type'] ?? 'document';
                                @endphp
                                <div
                                    class="group flex items-center gap-4 p-4 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-3xl hover:shadow-xl hover:-translate-y-1 transition-all duration-500">
                                    <div
                                        class="w-14 h-14 rounded-2xl bg-slate-50 dark:bg-slate-900/50 flex items-center justify-center text-teal-600 dark:text-teal-400 shrink-0 group-hover:bg-linear-to-br group-hover:from-[#0c3a47] group-hover:to-[#075985] group-hover:text-white transition-all duration-500 shadow-sm">
                                        <x-umpak::icon :name="$icon" class="w-6 h-6" />
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 mb-1">
                                            <h4 class="font-bold text-slate-800 dark:text-white text-sm truncate group-hover:text-teal-600 dark:group-hover:text-teal-400 transition-colors">
                                                {{ $judul }}
                                            </h4>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            @if($tahun)
                                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-tighter">{{ $tahun }}</span>
                                            @endif
                                            <span class="text-[10px] text-slate-400 font-medium italic opacity-70 truncate">{{ $desc ?: 'Dokumen Resmi' }}</span>
                                        </div>
                                    </div>
                                    <a href="{{ $downloadUrl }}" target="_blank"
                                        class="shrink-0 w-10 h-10 flex items-center justify-center rounded-xl text-teal-600 dark:text-teal-400 border border-teal-100 dark:border-teal-800 hover:bg-linear-to-br from-[#0c3a47] via-[#0d6b7a] to-[#075985] hover:text-white dark:hover:text-white transition-all shadow-sm active:scale-90">
                                        <x-umpak::icon name="download" class="w-4 h-4" />
                                    </a>
                                </div>
                            @empty
                                <div class="col-span-2 py-20 text-center border-2 border-dashed border-slate-200 dark:border-slate-800 rounded-3xl">
                                    <x-umpak::icon name="folder-open" class="w-10 h-10 text-slate-200 dark:text-slate-700 mx-auto mb-4" />
                                    <p class="text-slate-400 text-sm font-medium italic">Belum ada dokumen untuk kategori ini.</p>
                                </div>
                            @endforelse
                        </div>
                    @endforeach
                </div>

                {{-- Footer Buttons --}}
                @if(count($section->buttons()) > 0)
                    <div class="mt-12 flex justify-center">
                        @foreach($section->buttons() as $btn)
                            <a href="{{ $btn['url'] }}" wire:navigate
                                class="inline-flex items-center gap-3 px-8 py-3 text-sm font-black text-white bg-linear-to-br from-[#0c3a47] via-[#0d6b7a] to-[#075985] rounded-2xl transition-all shadow-lg shadow-teal-900/20 hover:scale-105 active:scale-95 group">
                                {{ $btn['label'] }}
                                <x-umpak::icon name="move-right" class="w-4 h-4 transition-transform group-hover:translate-x-1" />
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