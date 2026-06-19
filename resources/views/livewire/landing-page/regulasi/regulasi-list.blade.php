<div x-data="{ 
    documents: {{ $allDocuments->toJson() }},
    search: '',
    category: 'all',
    format: 'all',
    year: 'all',
    expandedId: null, // Melacak ID yang sedang terbuka
    selectedDoc: null,
    
    // Pagination State
    page: 1,
    perPage: 10,
    
    // 1. Logika Filter & Sort (Berdasarkan updated_at/uploaded)
    get filteredDocuments() {
        let filtered = this.documents.filter(doc => {
            const matchSearch = this.search === '' || 
                doc.title.toLowerCase().includes(this.search.toLowerCase());
            const matchCategory = this.category === 'all' || doc.cat_id === this.category;
            const matchFormat = this.format === 'all' || doc.fmt === this.format;
            const matchYear = this.year === 'all' || doc.tahun.toString() === this.year;
            
            return matchSearch && matchCategory && matchFormat && matchYear;
        });

        // Sorting: Terbaru berdasarkan uploaded_at
        return filtered.sort((a, b) => new Date(b.uploaded) - new Date(a.uploaded));
    },

    // 2. Logika Paginasi
    get paginatedDocuments() {
        const start = (this.page - 1) * this.perPage;
        const end = start + this.perPage;
        return this.filteredDocuments.slice(start, end);
    },

    get totalPages() {
        return Math.ceil(this.filteredDocuments.length / this.perPage);
    },

     toggle(id) {
        this.expandedId = (this.expandedId === id) ? null : id;
    }
}" x-init="$watch('search', () => page = 1); $watch('category', () => page = 1); $watch('format', () => page = 1); $watch('year', () => page = 1);"
    x-cloak class="min-h-screen bg-slate-50 dark:bg-slate-950 transition-colors duration-300">



    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-10">
        <div class="flex flex-col lg:flex-row gap-8 items-start">

            {{-- Sidebar --}}
            <aside class="w-full lg:w-72 shrink-0 lg:sticky lg:top-24 space-y-5">
                {{-- Side Header --}}
                <div class="relative overflow-hidden bg-linear-to-br from-[#0c3a47] via-[#0d6b7a] to-[#075985] rounded-2xl shadow-lg border border-white/10">
                    <div class="absolute inset-0 opacity-[0.08]"
                        style="background-image:url('data:image/svg+xml,%3Csvg width=32 height=32 viewBox=\'0 0 32 32\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cpath d=\'M0 0h32v32H0z\' fill=\'none\' stroke=\'%23fff\' stroke-width=\'0.5\'/%3E%3C/svg%3E')">
                    </div>
                    <div class="relative p-6">
                        <p class="text-[10px] font-bold tracking-widest uppercase text-teal-300 mb-2">Repositori Publik</p>
                        <h1 class="text-xl font-black text-white mb-3 leading-tight">Dokumen & Regulasi</h1>
                        <p class="text-xs text-white/70 leading-relaxed mb-6">Akses dokumen resmi Bagian Organisasi.</p>
                        
                        <div class="flex items-center gap-3 bg-white/10 backdrop-blur-md border border-white/20 rounded-xl px-4 py-3">
                            <span class="text-2xl font-black text-white" x-text="filteredDocuments.length"></span>
                            <span class="text-[9px] text-white/50 font-bold uppercase leading-tight">Dokumen<br/>Ditemukan</span>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden shadow-sm">
                    <div class="p-5 border-b border-slate-100 dark:border-slate-800">
                        <h3 class="font-black text-slate-900 dark:text-white uppercase text-xs">Filter Kategori</h3>
                    </div>
                    <nav class="p-3 space-y-1">
                        <button @click="category = 'all'"
                            :class="category === 'all' ? 'bg-teal-50 text-teal-600 dark:bg-teal-900/20 dark:text-teal-400' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50'"
                            class="w-full text-left px-4 py-3 rounded-2xl flex items-center gap-3 transition-all">
                            <span class="text-lg">📂</span><span class="font-bold text-sm">Semua</span>
                        </button>
                        @foreach($categories as $cat)
                            <button @click="category = '{{ $cat['id'] }}'"
                                :class="category === '{{ $cat['id'] }}' ? 'bg-teal-50 text-teal-600 dark:bg-teal-900/20 dark:text-teal-400' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/50'"
                                class="w-full text-left px-4 py-3 rounded-2xl flex items-center gap-3 transition-all">
                                <span class="text-lg">{{ $cat['icon'] }}</span><span
                                    class="font-bold text-sm flex-1">{{ $cat['label'] }}</span>
                            </button>
                        @endforeach
                    </nav>
                </div>
            </aside>

            {{-- Main Section --}}
            <main class="flex-1">
                <div
                    class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 mb-8 shadow-sm">
                    <div class="flex flex-col md:flex-row gap-4 mb-4">
                        <div class="relative flex-1">
                            <x-umpak::icon name="search"
                                class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400 dark:text-slate-500" />
                            <input x-model="search" type="search" placeholder="Cari judul..."
                                class="w-full pl-12 pr-4 py-3 rounded-2xl bg-slate-50 dark:bg-slate-800 border-transparent text-sm font-medium focus:ring-2 focus:ring-teal-500 dark:text-white placeholder:text-slate-500">
                        </div>
                        <select x-model="year"
                            class="md:w-48 pl-4 pr-10 py-3 rounded-2xl bg-slate-50 dark:bg-slate-800 border-transparent text-sm font-bold appearance-none dark:text-white">
                            <option value="all">Semua Tahun</option>
                            @foreach($years as $y) <option value="{{ $y }}">{{ $y }}</option> @endforeach
                        </select>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        <template x-for="f in ['pdf', 'xlsx', 'docx', 'pptx', 'zip']">
                            <button @click="format = (format === f ? 'all' : f)"
                                :class="format === f ? 'bg-teal-600 text-white shadow-lg shadow-teal-600/20' : 'bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-700'"
                                class="px-4 py-1.5 rounded-full text-xs font-bold transition-all"
                                x-text="f.toUpperCase()"></button>
                        </template>
                    </div>
                </div>

                {{-- List Items --}}
                {{-- <div class="space-y-4">
                    <template x-for="doc in paginatedDocuments" :key="doc.id">
                        <div class="group bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 hover:border-teal-500 hover:shadow-xl transition-all cursor-pointer"
                            @click="openModal(doc)">
                            <div class="flex items-start gap-5">
                                <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-[10px] font-black flex-shrink-0"
                                    :class="['pdf','docx'].includes(doc.fmt) ? 'bg-rose-50 text-rose-600' : (doc.fmt === 'xlsx' ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-100 text-slate-600')"
                                    x-text="doc.fmt.toUpperCase()"></div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-sm font-black text-slate-900 dark:text-white group-hover:text-teal-600 transition-colors"
                                        x-text="doc.title"></h3>
                                    <div class="flex gap-4 text-[10px] font-bold text-slate-400 uppercase mt-4">
                                        <span class="flex items-center gap-1.5"><x-umpak::icon name="calendar"
                                                class="w-3 h-3" /> <span x-text="doc.tahun"></span></span>
                                        <span class="flex items-center gap-1.5"><x-umpak::icon name="hard-drive"
                                                class="w-3 h-3" /> <span x-text="doc.size"></span></span>
                                        <span class="text-teal-600" x-text="doc.cat"></span>
                                    </div>
                                </div>
                                <x-umpak::icon name="chevron-right"
                                    class="w-5 h-5 text-slate-300 opacity-0 group-hover:opacity-100" />
                            </div>
                        </div>
                    </template> --}}

                    {{-- Empty State --}}
                    {{-- <div x-show="filteredDocuments.length === 0" class="py-20 text-center">
                        <p class="text-slate-500 text-sm font-bold">Dokumen tidak ditemukan.</p>
                    </div>
                </div> --}}

                <div class="space-y-4">
                    <template x-for="doc in paginatedDocuments" :key="doc.id">
                        <div class="group bg-white dark:bg-slate-900 border transition-all duration-300 rounded-2xl overflow-hidden shadow-sm"
                            :class="expandedId === doc.id ? 'border-teal-500 ring-4 ring-teal-500/5' : 'border-slate-200 dark:border-slate-800 hover:border-teal-300'">

                            {{-- Clickable Area (Header) --}}
                            <div class="p-6 cursor-pointer flex items-start gap-5" @click="toggle(doc.id)">
                                <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-[10px] font-black shrink-0 transition-transform group-hover:scale-105"
                                    :class="['pdf','docx'].includes(doc.fmt) ? 'bg-rose-50 text-rose-600 dark:bg-rose-950/30 dark:text-rose-400' : (doc.fmt === 'xlsx' ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/30 dark:text-emerald-400' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400')"
                                    x-text="doc.fmt.toUpperCase()">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between gap-4">
                                        <h3 class="text-sm font-bold text-slate-900 dark:text-white leading-tight"
                                            :class="expandedId === doc.id ? 'text-teal-600' : 'group-hover:text-teal-600'"
                                            x-text="doc.title"></h3>
                                        <x-umpak::icon name="chevron-down"
                                            class="w-5 h-5 text-slate-400 transition-transform duration-300"
                                            ::class="expandedId === doc.id ? 'rotate-180 text-teal-600' : ''" />
                                    </div>
                                    <div class="flex gap-4 text-[10px] font-bold text-slate-400 uppercase mt-4">
                                        <span class="flex items-center gap-1.5"><x-umpak::icon name="calendar"
                                                class="w-3 h-3" /> <span x-text="doc.tahun"></span></span>
                                        <span class="flex items-center gap-1.5"><x-umpak::icon name="hard-drive"
                                                class="w-3 h-3" /> <span x-text="doc.size"></span></span>
                                        <span class="text-teal-600/70" x-text="doc.cat"></span>
                                    </div>
                                </div>
                            </div>
                            {{-- Expanded Content (Detail) --}}
                            <div x-show="expandedId === doc.id" x-collapse
                                x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 -translate-y-4"
                                x-transition:enter-end="opacity-100 translate-y-0">
                                <div class="px-6 pb-8 pt-2 border-t border-slate-50 dark:border-slate-800">
                                    <div class="bg-slate-50/50 dark:bg-slate-800/50 rounded-2xl p-5 mb-6">
                                        <h4
                                            class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">
                                            Informasi Dokumen:</h4>
                                        <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed"
                                            x-text="doc.desc || 'Tidak ada deskripsi tambahan untuk dokumen ini.'"></p>
                                    </div>
                                    <div class="flex flex-col sm:flex-row gap-3">
                                        <a :href="doc.url" target="_blank"
                                            class="flex-1 px-6 py-3.5 rounded-xl bg-teal-600 text-white font-bold text-sm shadow-lg shadow-teal-600/20 hover:bg-teal-700 transition-all flex items-center justify-center gap-2">
                                            <x-umpak::icon name="download" class="w-4 h-4" />
                                            Unduh Dokumen (<span x-text="doc.fmt.toUpperCase()"></span>)
                                        </a>
                                        <button x-on:click="navigator.clipboard.writeText(doc.url)" type="button"
                                            class="px-6 py-3.5 rounded-xl cursor-pointer bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold text-sm hover:bg-slate-200 dark:hover:bg-slate-700 transition-all flex items-center justify-center gap-2">
                                            <x-umpak::icon name="link" class="w-4 h-4" />
                                            Salin Tautan
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- Alpine Pagination Controls --}}
                <div x-show="totalPages > 1" class="mt-10 flex items-center justify-center gap-2">
                    <button @click="page--" :disabled="page === 1"
                        class="w-10 h-10 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 flex items-center justify-center text-slate-400 disabled:opacity-30 disabled:cursor-not-allowed hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors shadow-sm">
                        <x-umpak::icon name="chevron-left" class="w-4 h-4" />
                    </button>

                    <div class="flex items-center gap-1">
                        <template x-for="p in totalPages" :key="p">
                            <button @click="page = p"
                                :class="page === p ? 'bg-teal-600 text-white border-teal-600 shadow-lg shadow-teal-600/20' : 'bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-400 border-slate-200 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800'"
                                class="w-10 h-10 rounded-xl border font-bold text-sm transition-all"
                                x-text="p"></button>
                        </template>
                    </div>

                    <button @click="page++" :disabled="page === totalPages"
                        class="w-10 h-10 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 flex items-center justify-center text-slate-400 disabled:opacity-30 disabled:cursor-not-allowed hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors shadow-sm">
                        <x-umpak::icon name="chevron-right" class="w-4 h-4" />
                    </button>
                </div>
            </main>
        </div>
    </div>
</div>