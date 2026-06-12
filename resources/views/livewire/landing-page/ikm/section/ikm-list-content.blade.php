<div x-data="{
        allItems: $wire.entangle('itemsList'),
        search: '',
        predikat: 'all',
        kategori: 'all',
        currentPage: 1,
        perPage: 10,
        sortField: 'skor',
        sortDirection: 'desc',
        
        get filteredItems() {
            let items = this.allItems.filter(item => {
                const matchSearch = item.nama.toLowerCase().includes(this.search.toLowerCase());
                const matchPredikat = this.predikat === 'all' || item.predikat === this.predikat;
                const matchKategori = this.kategori === 'all' || item.kategori === this.kategori;
                return matchSearch && matchPredikat && matchKategori;
            });

            // Sorting logic
            return items.sort((a, b) => {
                let valA = a[this.sortField];
                let valB = b[this.sortField];
                
                if (typeof valA === 'string') {
                    valA = valA.toLowerCase();
                    valB = valB.toLowerCase();
                }

                if (valA < valB) return this.sortDirection === 'asc' ? -1 : 1;
                if (valA > valB) return this.sortDirection === 'asc' ? 1 : -1;
                return 0;
            });
        },

        sortBy(field) {
            if (this.sortField === field) {
                this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                this.sortField = field;
                this.sortDirection = 'asc';
            }
            this.currentPage = 1;
        },

        get totalPages() {
            return Math.ceil(this.filteredItems.length / this.perPage);
        },

        get paginatedItems() {
            let start = (this.currentPage - 1) * this.perPage;
            let end = start + this.perPage;
            return this.filteredItems.slice(start, end);
        },

        get stats() {
            const items = this.filteredItems;
            if (items.length === 0) {
                return { total: 0, avg: '0.0', max: 0, max_name: '-', min: 0, min_name: '-' };
            }
            
            const total = items.length;
            const avg = (items.reduce((acc, item) => acc + item.skor, 0) / total).toFixed(1);
            
            // Max/Min logic
            const sortedBySkor = [...items].sort((a, b) => b.skor - a.skor);
            const maxItem = sortedBySkor[0];
            const minItem = sortedBySkor[total - 1];

            return {
                total,
                avg,
                max: maxItem.skor.toFixed(2),
                max_name: maxItem.nama,
                min: minItem.skor.toFixed(2),
                min_name: minItem.nama
            };
        },

        init() {
            this.$watch('search', () => this.currentPage = 1);
            this.$watch('predikat', () => this.currentPage = 1);
            this.$watch('kategori', () => this.currentPage = 1);
            this.$watch('allItems', () => {
                this.currentPage = 1;
            });
            
            this.$wire.on('filters-reset', () => {
                this.search = '';
                this.predikat = 'all';
                this.kategori = 'all';
                this.sortField = 'skor';
                this.sortDirection = 'desc';
                this.currentPage = 1;
            });
        }
    }" x-init="$watch('allItems', () => currentPage = 1)">
    {{-- ══ STAT CARDS ══ --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-8">
        <div class="stat-card">
            <p class="text-xs font-semibold uppercase tracking-wider mb-2 text-(--text-muted)">Total Instansi</p>
            <p class="text-2xl sm:text-3xl text-(--text-primary)" x-text="stats.total"></p>
            <p class="text-xs mt-1 text-(--text-secondary)">unit pelayanan publik</p>
        </div>
        <div class="stat-card">
            <p class="text-xs font-semibold uppercase tracking-wider mb-2 text-(--text-muted)">Rata-rata Skor</p>
            <p class="text-2xl sm:text-3xl text-(--teal-primary)" x-text="stats.avg"></p>
            <p class="text-xs mt-1 text-(--text-secondary)">Data Terfilter</p>
        </div>
        <div class="stat-card">
            <p class="text-xs font-semibold uppercase tracking-wider mb-2 text-(--text-muted)">Skor Tertinggi</p>
            <div class="flex items-end gap-1.5 min-w-0">
                <p class="text-2xl sm:text-3xl text-emerald-600 dark:text-emerald-500 whitespace-nowrap" x-text="stats.max">
                </p>
                <p class="text-xs mb-1 truncate font-semibold text-(--text-secondary)" 
                    :title="stats.max_name" x-text="stats.max_name">
                </p>
            </div>
            <p class="text-xs mt-1 text-(--text-secondary)">Predikat Sangat Baik</p>
        </div>
        <div class="stat-card">
            <p class="text-xs font-semibold uppercase tracking-wider mb-2 text-(--text-muted)">Skor Terendah</p>
            <div class="flex items-end gap-1.5 min-w-0">
                <p class="text-2xl sm:text-3xl text-amber-600 dark:text-amber-500 whitespace-nowrap" x-text="stats.min">
                </p>
                <p class="text-xs mb-1 truncate font-semibold text-(--text-secondary)" 
                    :title="stats.min_name" x-text="stats.min_name">
                </p>
            </div>
            <p class="text-xs mt-1 text-(--text-secondary)">Predikat Cukup</p>
        </div>
    </div>

    {{-- ══ FILTER + SEARCH BAR ══ --}}
    <div class="rounded-2xl p-4 sm:p-5 mb-5 bg-(--bg-surface) border border-(--border) shadow-(--shadow-sm)">
        <div class="flex flex-col sm:flex-row gap-3 mb-4">
            {{-- Search Input (Alpine) --}}
            <div class="relative flex-1">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 pointer-events-none text-(--text-muted)"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8" />
                    <path stroke-linecap="round" d="M21 21l-4.35-4.35" />
                </svg>
                <input x-model="search" type="search" placeholder="Cari nama instansi..."
                    class="search-input w-full pl-9 pr-4 py-2.5 rounded-xl text-sm outline-none" />
                <button x-show="search" @click="search = ''"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-(--text-muted) hover:text-red-500 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            {{-- Period Select (Livewire) --}}
            <div class="relative sm:w-52">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 pointer-events-none text-(--text-muted)"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                    <line x1="16" y1="2" x2="16" y2="6" />
                    <line x1="8" y1="2" x2="8" y2="6" />
                    <line x1="3" y1="10" x2="21" y2="10" />
                </svg>
                <select wire:model.live="period" @change="currentPage = 1"
                    class="filter-select w-full pl-9 pr-8 py-2.5 rounded-xl text-sm font-medium outline-none">
                    @foreach($periods as $p)
                        <option value="{{ $p->tahun }}-{{ $p->triwulan }}">Triwulan {{ $p->triwulan }} {{ $p->tahun }}
                        </option>
                    @endforeach
                </select>
                <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 pointer-events-none text-(--text-muted)"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
        </div>

        {{-- Alpine Quick Filters --}}
        <div class="flex flex-wrap gap-2 items-center">
            <span class="text-xs font-semibold mr-1 text-(--text-muted)">Predikat:</span>
            <button @click="predikat = 'all'" :class="predikat === 'all' ? 'active' : ''" class="chip">Semua</button>
            <button @click="predikat = 'Sangat Baik'" :class="predikat === 'Sangat Baik' ? 'active' : ''"
                class="chip">🟢 Sangat Baik</button>
            <button @click="predikat = 'Baik'" :class="predikat === 'Baik' ? 'active' : ''" class="chip">🔵
                Baik</button>
            <button @click="predikat = 'Cukup'" :class="predikat === 'Cukup' ? 'active' : ''" class="chip">🟡
                Cukup</button>
            <span class="w-px h-4 mx-1 bg-(--border)"></span>
        </div>

        <div class="mt-2 flex flex-wrap gap-2 items-center">
            <span class="text-xs font-semibold mr-1 text-(--text-muted)">Kategori:</span>
            <button @click="kategori = 'all'" :class="kategori === 'all' ? 'active' : ''" class="chip">Semua</button>
            @foreach($categories as $cat)
                <button @click="kategori = '{{ $cat }}'" :class="kategori === '{{ $cat }}' ? 'active' : ''"
                    class="chip">{{ $cat }}</button>
            @endforeach
        </div>
    </div>

    {{-- Result Table --}}
    <div class="flex items-center justify-between mb-3 px-1">
        <p class="text-xs font-medium text-(--text-secondary)">
            Menampilkan <span class="font-bold text-(--text-primary)" x-text="filteredItems.length"></span> instansi
        </p>
    </div>

    <div class="rounded-2xl overflow-hidden bg-(--bg-surface) border border-(--border) shadow-(--shadow-sm)">
        <div class="overflow-x-auto">
            <table class="ikm-table text-left">
                <thead>
                    <tr class="select-none">
                        <th class="w-10">No</th>
                        <th @click="sortBy('nama')" class="min-w-[200px] cursor-pointer hover:bg-(--bg-muted) group">
                            <div class="flex items-center gap-1">
                                Nama Instansi
                                <span class="sort-icon" :class="sortField === 'nama' ? 'sorted' : ''">
                                    <template x-if="sortField === 'nama'">
                                        <x-umpak::icon x-show="sortDirection === 'asc'" name="chevron-up"
                                            class="w-3.5 h-3.5" />
                                        <x-umpak::icon x-show="sortDirection === 'desc'" name="chevron-down"
                                            class="w-3.5 h-3.5" />
                                    </template>
                                    <template x-if="sortField !== 'nama'"><x-umpak::icon name="chevrons-up-down"
                                            class="w-3.5 h-3.5 opacity-30 group-hover:opacity-100" /></template>
                                </span>
                            </div>
                        </th>
                        <th @click="sortBy('kategori')"
                            class="w-32 cursor-pointer hover:bg-(--bg-muted) group text-left">
                            <div class="flex items-center gap-1">
                                Kategori
                                <span class="sort-icon" :class="sortField === 'kategori' ? 'sorted' : ''">
                                    <template x-if="sortField === 'kategori'">
                                        <x-umpak::icon x-show="sortDirection === 'asc'" name="chevron-up"
                                            class="w-3.5 h-3.5" />
                                        <x-umpak::icon x-show="sortDirection === 'desc'" name="chevron-down"
                                            class="w-3.5 h-3.5" />
                                    </template>
                                    <template x-if="sortField !== 'kategori'"><x-umpak::icon name="chevrons-up-down"
                                            class="w-3.5 h-3.5 opacity-30 group-hover:opacity-100" /></template>
                                </span>
                            </div>
                        </th>
                        <th @click="sortBy('sampel')"
                            class="w-28 cursor-pointer hover:bg-(--bg-muted) group text-right">
                            <div class="flex items-center justify-end gap-1">
                                Responden
                                <span class="sort-icon" :class="sortField === 'sampel' ? 'sorted' : ''">
                                    <template x-if="sortField === 'sampel'">
                                        <x-umpak::icon x-show="sortDirection === 'asc'" name="chevron-up"
                                            class="w-3.5 h-3.5" />
                                        <x-umpak::icon x-show="sortDirection === 'desc'" name="chevron-down"
                                            class="w-3.5 h-3.5" />
                                    </template>
                                    <template x-if="sortField !== 'sampel'"><x-umpak::icon name="chevrons-up-down"
                                            class="w-3.5 h-3.5 opacity-30 group-hover:opacity-100" /></template>
                                </span>
                            </div>
                        </th>
                        <th @click="sortBy('skor')" class="w-24 cursor-pointer hover:bg-(--bg-muted) group text-right">
                            <div class="flex items-center justify-end gap-1">
                                Skor
                                <span class="sort-icon" :class="sortField === 'skor' ? 'sorted' : ''">
                                    <template x-if="sortField === 'skor'">
                                        <x-umpak::icon x-show="sortDirection === 'asc'" name="chevron-up"
                                            class="w-3.5 h-3.5" />
                                        <x-umpak::icon x-show="sortDirection === 'desc'" name="chevron-down"
                                            class="w-3.5 h-3.5" />
                                    </template>
                                    <template x-if="sortField !== 'skor'"><x-umpak::icon name="chevrons-up-down"
                                            class="w-3.5 h-3.5 opacity-30 group-hover:opacity-100" /></template>
                                </span>
                            </div>
                        </th>
                        <th class="text-center w-32 border-none">Predikat</th>
                        <th class="text-center w-16">Detail</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="(item, index) in paginatedItems" :key="item.id || index">
                        <tr
                            class="hover:bg-(--teal-light) transition-colors border-b border-(--border) last:border-0 border-none">
                            <td x-text="(currentPage - 1) * perPage + index + 1"></td>
                            <td class="font-semibold text-(--text-primary)" x-text="item.nama"></td>
                            <td>
                                <span
                                    class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-(--bg-elevated) text-(--text-secondary) uppercase tracking-tight"
                                    x-text="item.kategori"></span>
                            </td>
                            <td class="text-right text-(--text-secondary)" x-text="item.sampel.toLocaleString('id-ID')">
                            </td>
                            <td class="text-right font-bold text-(--teal-primary)" x-text="item.skor.toFixed(2)"></td>
                            <td class="text-center">
                                <span :class="{
                                    'badge-sb': item.skor >= 88.31,
                                    'badge-b': item.skor >= 76.61 && item.skor < 88.31,
                                    'badge-c': item.skor >= 65.00 && item.skor < 76.61,
                                    'badge-kb': item.skor < 65.00
                                }" class="px-2 py-0.5 text-[10px] font-bold rounded-full"
                                    x-text="item.predikat"></span>
                            </td>
                            <td class="text-center">
                                <a :href="`{{ route('bale-organisasi.ikm.show', ['id' => ':id']) }}`.replace(':id', item.id)"
                                    wire:navigate.hover
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg transition-all bg-(--bg-elevated) text-(--text-secondary) hover:bg-(--teal-primary) hover:text-white">
                                    <x-umpak::icon name="eye" class="w-4 h-4" />
                                </a>
                            </td>
                        </tr>
                    </template>
                    <tr x-show="filteredItems.length === 0">
                        <td colspan="7" class="py-20">
                            <div class="flex flex-col items-center justify-center gap-3">
                                <x-umpak::icon name="search-x" class="w-12 h-12 text-(--text-muted)" />
                                <p class="font-bold text-(--text-secondary)">Data tidak ditemukan</p>
                                <button @click="$wire.resetFilters()"
                                    class="mt-2 text-sm font-semibold px-4 py-2 rounded-xl text-white bg-(--teal-primary)">Reset
                                    Filter</button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Alpine Pagination --}}
    <div x-show="totalPages > 1" class="flex items-center justify-between mt-6 px-1">
        <p class="text-xs text-(--text-secondary)">
            Halaman <span class="font-bold text-(--text-primary)" x-text="currentPage"></span> dari <span
                class="font-bold text-(--text-primary)" x-text="totalPages"></span>
        </p>
        <div class="flex items-center gap-1.5">
            <button @click="currentPage--" :disabled="currentPage === 1"
                class="page-btn flex items-center justify-center w-8 h-8 p-0">
                <x-umpak::icon name="chevron-left" class="w-4 h-4" />
            </button>

            <template x-for="page in totalPages" :key="page">
                <button
                    x-show="page === 1 || page === totalPages || (page >= currentPage - 1 && page <= currentPage + 1)"
                    @click="currentPage = page" :class="currentPage === page ? 'active' : ''"
                    class="page-btn min-w-[32px] h-8" x-text="page"></button>
            </template>

            <button @click="currentPage++" :disabled="currentPage === totalPages"
                class="page-btn flex items-center justify-center w-8 h-8 p-0">
                <x-umpak::icon name="chevron-right" class="w-4 h-4" />
            </button>
        </div>
    </div>

    <p class="text-[10px] mt-8 text-center italic text-(--text-muted)">
        * Data IKM bersifat ilustratif untuk keperluan prototipe. Data resmi akan ditampilkan sesuai hasil survei yang
        telah diverifikasi.
    </p>
</div>