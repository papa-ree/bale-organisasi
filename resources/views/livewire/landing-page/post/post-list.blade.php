<div class="min-h-screen bg-slate-50 dark:bg-slate-900 transition-colors duration-300">

    {{-- Page Header --}}
    <div class="bg-linear-to-br from-[#0c3a47] via-[#0d6b7a] to-[#075985] py-16 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="grid-post" width="40" height="40" patternUnits="userSpaceOnUse">
                        <path d="M 40 0 L 0 0 0 40" fill="none" stroke="white" stroke-width="1" />
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#grid-post)" />
            </svg>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <x-umpak::breadcrumb :items="[
        ['label' => 'Beranda', 'url' => '/'],
        ['label' => 'Berita'],
    ]" class="mb-4 text-white/60 [&_a]:text-white/60 [&_a:hover]:text-white [&_.current]:text-white" />
            <h1 class="text-3xl sm:text-4xl font-black text-white leading-tight">Berita & Informasi</h1>
            <p class="text-white/70 mt-2 text-sm">Informasi terkini dari Bagian Organisasi Setda Kab. Ponorogo.</p>
        </div>
    </div>

    {{-- Search & Filter Bar --}}
    <div
        class="bg-slate-50/95 dark:bg-slate-900/95 backdrop-blur-md border-b border-slate-200 dark:border-slate-800 py-5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-100 dark:border-slate-700">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">

                    {{-- Search Input --}}
                    <div class="lg:col-span-5">
                        <label
                            class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-widest">Cari
                            Berita</label>
                        <div class="relative">
                            <x-umpak::icon name="search"
                                class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none" />
                            <input type="text" wire:model.live.debounce.400ms="search"
                                placeholder="Judul atau isi berita..."
                                class="w-full pl-10 pr-4 py-2.5 text-sm bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 outline-none transition-all dark:text-white placeholder:text-slate-400">
                        </div>
                    </div>

                    {{-- Date Range Picker --}}
                    <div class="lg:col-span-5" wire:ignore x-data="{
                        picker: null,
                        init() {
                            this.picker = flatpickr(this.$refs.picker, {
                                mode: 'range',
                                dateFormat: 'Y-m-d',
                                locale: { rangeSeparator: ' to ' },
                                defaultDate: @js($date ?: null),
                                onChange: (selectedDates, dateStr) => {
                                    this.$refs.dateInput.value = dateStr;
                                    this.$refs.dateInput.dispatchEvent(new Event('input'));
                                }
                            });
                            this.$watch('$wire.date', value => {
                                if (!value && this.picker) this.picker.clear(false);
                            });
                        }
                    }">
                        <label
                            class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-widest">Filter
                            Tanggal</label>
                        <div class="relative">
                            <x-umpak::icon name="calendar"
                                class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none" />
                            <input type="hidden" x-ref="dateInput" wire:model="date">
                            <input x-ref="picker" type="text" placeholder="Rentang tanggal..." readonly
                                class="w-full pl-10 pr-10 py-2.5 text-sm bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 outline-none transition-all dark:text-white cursor-pointer">
                            <button type="button" x-show="$wire.date"
                                @click="if(picker) picker.clear(); $wire.set('date', '')"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-red-500 transition-colors">
                                <x-umpak::icon name="x" class="w-4 h-4" />
                            </button>
                        </div>
                    </div>

                    {{-- Action --}}
                    <div class="lg:col-span-2 flex items-end gap-2">
                        <button wire:click="$refresh" type="button"
                            class="flex-1 px-4 py-2.5 bg-teal-600 cursor-pointer hover:bg-teal-700 text-white text-sm font-bold rounded-xl transition-colors shadow-lg shadow-teal-600/20 active:scale-95">
                            <span wire:loading.remove wire:target="$refresh">Segarkan</span>
                            <span wire:loading wire:target="$refresh">...</span>
                        </button>
                        @if($search || $date)
                            <button wire:click="clearFilters" type="button" title="Reset Filter"
                                class="px-3 py-2.5 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-xl hover:bg-red-50 hover:text-red-500 dark:hover:bg-red-900/20 transition-all">
                                <x-umpak::icon name="filter-x" class="w-5 h-5" />
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Posts Section --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        {{-- Skeleton Loading --}}
        <div wire:loading.grid wire:target="search, date, $refresh, clearFilters"
            class="grid md:grid-cols-3 gap-8 mb-12">
            @for($i = 0; $i < 9; $i++)
                <div
                    class="bg-white dark:bg-slate-800 rounded-3xl overflow-hidden border border-slate-100 dark:border-slate-700 animate-pulse">
                    <div class="aspect-video bg-slate-200 dark:bg-slate-700"></div>
                    <div class="p-6 space-y-3">
                        <div class="h-4 bg-slate-200 dark:bg-slate-700 rounded-lg w-3/4"></div>
                        <div class="h-4 bg-slate-200 dark:bg-slate-700 rounded-lg w-full"></div>
                        <div class="h-3 bg-slate-100 dark:bg-slate-700/50 rounded-lg w-2/3"></div>
                        <div class="h-3 bg-slate-100 dark:bg-slate-700/50 rounded-lg w-5/6"></div>
                        <div class="pt-4 border-t border-slate-100 dark:border-slate-700/50 flex justify-between">
                            <div class="h-3 bg-slate-200 dark:bg-slate-700 rounded w-24"></div>
                            <div class="h-3 bg-teal-100 dark:bg-teal-900/30 rounded w-12"></div>
                        </div>
                    </div>
                </div>
            @endfor
        </div>

        {{-- Posts Grid --}}
        <div wire:loading.remove wire:target="search, date, $refresh, clearFilters">
            <div class="grid md:grid-cols-3 gap-8 mb-12">
                @forelse($this->posts as $post)
                    <article wire:key="{{ $post->id }}"
                        class="group bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-3xl overflow-hidden hover:shadow-2xl hover:shadow-teal-500/10 hover:-translate-y-2 transition-all duration-500 flex flex-col">
                        <a href="{{ route('bale-organisasi.post.show', $post->slug) }}" wire:navigate.hover>

                            {{-- Thumbnail --}}
                            <div class="relative overflow-hidden aspect-video bg-slate-100 dark:bg-slate-700">
                                @if($post->hasThumbnail())
                                    <img src="{{ $post->thumbnail }}"
                                        alt="{{ $post->title }}"
                                        class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" />
                                @else
                                    <div class="w-full h-full flex items-center justify-center opacity-30">
                                        <x-umpak::icon name="image" class="w-12 h-12" />
                                    </div>
                                @endif
                                <div
                                    class="absolute inset-0 bg-linear-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                                </div>
                                @if($post->categorySlug)
                                    <div class="absolute top-4 left-4">
                                        <span
                                            class="px-3 py-1 text-[10px] font-bold rounded-lg uppercase tracking-tight shadow-sm bg-teal-500 text-white">
                                            {{ Str::headline($post->categorySlug) }}
                                        </span>
                                    </div>
                                @endif
                            </div>

                            {{-- Content --}}
                            <div class="p-6 flex flex-col flex-1">
                                <h2
                                    class="font-bold text-slate-800 dark:text-white text-base leading-snug mb-3 group-hover:text-teal-600 dark:group-hover:text-teal-400 transition-colors line-clamp-2">
                                    {{ $post->title }}
                                </h2>

                                @if($post->excerpt)
                                    <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-3 mb-6 leading-relaxed">
                                        {{ $post->excerpt }}
                                    </p>
                                @endif

                                <div
                                    class="mt-auto flex items-center justify-between pt-5 border-t border-slate-50 dark:border-slate-700/50">
                                    <div class="flex items-center gap-2">
                                        <x-umpak::icon name="calendar" class="w-3.5 h-3.5 text-slate-400" />
                                        <span class="text-[11px] font-medium text-slate-400 dark:text-slate-500 uppercase">
                                            {{ $post->formattedDate() }}
                                        </span>
                                    </div>
                                    <div
                                        class="text-xs font-bold text-teal-600 dark:text-teal-400 flex items-center gap-1.5 hover:gap-2 transition-all">
                                        Baca <span class="tracking-widest">→</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </article>
                @empty
                    <div
                        class="col-span-3 py-24 text-center border-2 border-dashed border-slate-200 dark:border-slate-800 rounded-3xl">
                        <x-umpak::icon name="search-x" class="w-12 h-12 text-slate-300 dark:text-slate-700 mx-auto mb-4" />
                        <p class="text-slate-600 dark:text-slate-400 font-bold mb-1">Tidak ada berita ditemukan</p>
                        <p class="text-slate-400 dark:text-slate-600 text-sm">Coba sesuaikan kata kunci atau filter tanggal
                            Anda.</p>
                    </div>
                @endforelse
            </div>

            {{-- Load More Button --}}
            @if($this->hasMore)
                <div class="text-center">
                    <button wire:click="loadMore" wire:loading.attr="disabled"
                        class="px-10 py-4 bg-teal-600 hover:bg-teal-700 text-white text-sm font-black rounded-2xl transition-all shadow-lg shadow-teal-600/20 active:scale-95 inline-flex items-center gap-3 group disabled:opacity-70">
                        <span wire:loading.remove wire:target="loadMore">Muat Lebih Banyak</span>
                        <span wire:loading wire:target="loadMore">Memuat...</span>
                        <x-umpak::icon name="chevron-down" class="w-4 h-4 group-hover:translate-y-1 transition-transform"
                            wire:loading.remove wire:target="loadMore" />
                        <svg wire:loading wire:target="loadMore" class="animate-spin w-4 h-4" fill="none"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>