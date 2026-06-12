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

    {{-- openModal(doc) {
        this.selectedDoc = doc;
        window.scrollTo({ top: 0, behavior: 'smooth' });
    } --}}

     toggle(id) {
        this.expandedId = (this.expandedId === id) ? null : id;
    }
}" x-init="$watch('search', () => page = 1); $watch('category', () => page = 1); $watch('format', () => page = 1); $watch('year', () => page = 1);"
    x-cloak class="min-h-screen bg-slate-50 dark:bg-slate-950 transition-colors duration-300">

    {{-- Header --}}
    <div class="relative overflow-hidden bg-gradient-to-br from-[#0c3a47] via-[#0d6b7a] to-[#075985]">
        <div class="absolute inset-0 opacity-[0.06]"
            style="background-image:url('data:image/svg+xml,%3Csvg width=52 height=52 viewBox=\'0 0 52 52\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cpath d=\'M10 10h32v32H10z\' fill=\'none\' stroke=\'%23fff\' stroke-width=\'1\'/%3E%3Ccircle cx=\'26\' cy=\'26\' r=\'6\' fill=\'none\' stroke=\'%23fff\' stroke-width=\'1\'/%3E%3C/svg%3E')">
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 py-12 sm:py-16">
            <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-8">
                <div class="max-w-2xl">
                    <p class="text-xs font-bold tracking-widest uppercase text-teal-300 mb-3">Repositori Publik</p>
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-white mb-4 leading-tight">Dokumen &
                        Regulasi</h1>
                    <p class="text-sm sm:text-base text-white/70 leading-relaxed">Akses repositori dokumen resmi Bagian
                        Organisasi Setda Kab. Ponorogo secara instan.</p>
                </div>
                <div class="flex gap-4">
                    <div
                        class="flex-1 lg:flex-none min-w-[120px] rounded-2xl px-6 py-4 text-center bg-white/10 backdrop-blur-md border border-white/20">
                        <p class="text-3xl font-black text-white" x-text="filteredDocuments.length"></p>
                        <p class="text-xs text-white/50 font-bold mt-1 uppercase">Hasil Temuan</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-10">
        <div class="flex flex-col lg:flex-row gap-8 items-start">

            {{-- Sidebar --}}
            <aside class="w-full lg:w-72 flex-shrink-0 lg:sticky lg:top-24">
                <div
                    class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden shadow-sm">
                    <div class="p-5 border-b border-slate-100 dark:border-slate-800">
                        <h3 class="font-black text-slate-900 dark:text-white uppercase text-xs">Filter Kategori</h3>
                    </div>
                    <nav class="p-3 space-y-1">
                        <button @click="category = 'all'"
                            :class="category === 'all' ? 'bg-teal-50 text-teal-600' : 'text-slate-600'"
                            class="w-full text-left px-4 py-3 rounded-2xl flex items-center gap-3 transition-all hover:bg-slate-50">
                            <span class="text-lg">📂</span><span class="font-bold text-sm">Semua</span>
                        </button>
                        @foreach($categories as $cat)
                            <button @click="category = '{{ $cat['id'] }}'"
                                :class="category === '{{ $cat['id'] }}' ? 'bg-teal-50 text-teal-600' : 'text-slate-600'"
                                class="w-full text-left px-4 py-3 rounded-2xl flex items-center gap-3 transition-all hover:bg-slate-50">
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
                                class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" />
                            <input x-model="search" type="search" placeholder="Cari judul..."
                                class="w-full pl-12 pr-4 py-3 rounded-2xl bg-slate-50 dark:bg-slate-800 border-transparent text-sm font-medium focus:ring-2 focus:ring-teal-500">
                        </div>
                        <select x-model="year"
                            class="md:w-48 pl-4 pr-10 py-3 rounded-2xl bg-slate-50 dark:bg-slate-800 border-transparent text-sm font-bold appearance-none">
                            <option value="all">Semua Tahun</option>
                            @foreach($years as $y) <option value="{{ $y }}">{{ $y }}</option> @endforeach
                        </select>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        <template x-for="f in ['pdf', 'xlsx', 'docx', 'pptx', 'zip']">
                            <button @click="format = (format === f ? 'all' : f)"
                                :class="format === f ? 'bg-teal-600 text-white' : 'bg-slate-100 text-slate-500'"
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
                                <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-[10px] font-black flex-shrink-0 transition-transform group-hover:scale-105"
                                    :class="['pdf','docx'].includes(doc.fmt) ? 'bg-rose-50 text-rose-600' : (doc.fmt === 'xlsx' ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-100 text-slate-600')"
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
                                            class="px-6 py-3.5 rounded-xl cursor-pointer bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold text-sm hover:bg-slate-200 transition-all flex items-center justify-center gap-2">
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
                        class="w-10 h-10 rounded-xl bg-white border border-slate-200 flex items-center justify-center disabled:opacity-30 disabled:cursor-not-allowed hover:bg-slate-50 transition-colors">
                        <x-umpak::icon name="chevron-left" class="w-4 h-4" />
                    </button>

                    <div class="flex items-center gap-1">
                        <template x-for="p in totalPages" :key="p">
                            <button @click="page = p"
                                :class="page === p ? 'bg-teal-600 text-white border-teal-600' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50'"
                                class="w-10 h-10 rounded-xl border font-bold text-sm transition-all"
                                x-text="p"></button>
                        </template>
                    </div>

                    <button @click="page++" :disabled="page === totalPages"
                        class="w-10 h-10 rounded-xl bg-white border border-slate-200 flex items-center justify-center disabled:opacity-30 disabled:cursor-not-allowed hover:bg-slate-50 transition-colors">
                        <x-umpak::icon name="chevron-right" class="w-4 h-4" />
                    </button>
                </div>
            </main>
        </div>
    </div>

    {{-- Detail Modal --}}
    {{-- <div x-show="selectedDoc" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-md" @click="selectedDoc = null"></div>
        <div class="relative bg-white dark:bg-slate-900 w-full max-w-2xl rounded-2xl shadow-2xl p-8 sm:p-12"
            x-show="selectedDoc" x-transition>
            <button @click="selectedDoc = null"
                class="absolute right-6 top-6 w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-500"><x-umpak::icon
                    name="x" class="w-5 h-5" /></button>
            <div class="flex items-start gap-6 mb-10">
                <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-xs font-black bg-teal-50 text-teal-600"
                    x-text="selectedDoc?.fmt.toUpperCase()"></div>
                <div class="min-w-0 pt-1">
                    <p class="text-[10px] font-black text-teal-600 uppercase tracking-widest mb-1"
                        x-text="selectedDoc?.cat"></p>
                    <h2 class="text-xl font-black text-slate-900 dark:text-white leading-tight"
                        x-text="selectedDoc?.title"></h2>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-4 mb-10">
                <div class="bg-slate-50 p-4 rounded-2xl text-center">
                    <p class="text-[10px] uppercase mb-1">Tahun</p>
                    <p class="text-sm font-black" x-text="selectedDoc?.tahun"></p>
                </div>
                <div class="bg-slate-50 p-4 rounded-2xl text-center">
                    <p class="text-[10px] uppercase mb-1">Ukuran</p>
                    <p class="text-sm font-black" x-text="selectedDoc?.size"></p>
                </div>
                <div class="bg-slate-50 p-4 rounded-2xl text-center">
                    <p class="text-[10px] uppercase mb-1">Format</p>
                    <p class="text-sm font-black" x-text="selectedDoc?.fmt.toUpperCase()"></p>
                </div>
            </div>
            <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed mb-10" x-text="selectedDoc?.desc"></p>
            <div class="flex flex-col sm:flex-row gap-4">
                <a :href="selectedDoc?.url" target="_blank"
                    class="flex-1 px-8 py-4 rounded-2xl bg-teal-600 text-white font-black text-sm shadow-xl flex items-center justify-center gap-3"><x-umpak::icon
                        name="download" class="w-5 h-5" /> Unduh Dokumen</a>
                <button @click="navigator.clipboard.writeText(selectedDoc?.url); alert('Tautan disalin!')"
                    class="px-8 py-4 rounded-2xl bg-slate-50 text-slate-600 font-black text-sm flex items-center justify-center gap-3"><x-umpak::icon
                        name="link" class="w-4 h-4" /> Salin Tautan</button>
            </div>
        </div>
    </div> --}}
</div>