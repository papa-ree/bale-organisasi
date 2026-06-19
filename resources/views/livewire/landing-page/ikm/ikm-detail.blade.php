<div>

    <script>
        window.ikmChartData = {!! $chartJson !!};
        window.ikmKabStatsByPeriod = {!! $kabStatsJson !!};
    </script>
    <div x-data="{
            chartData: window.ikmChartData || [],
            kabStatsMap: window.ikmKabStatsByPeriod || {},
            activePeriod: '{{ $activePeriod }}',
    
            get activeRecord() {
                return this.chartData.find(d => d.key === this.activePeriod) || this.chartData[this.chartData.length - 1] || null;
            },
            get kabStats() {
                return this.kabStatsMap[this.activePeriod] || { avg: 0, rank: 0, total: 0 };
            },
            get skor() { return this.activeRecord ? this.activeRecord.skor : 0; },
            get kabAvg() { return this.kabStats.avg; },
            get kabRank() { return this.kabStats.rank; },
            get kabTotal() { return this.kabStats.total; },
            get sampel() { return this.activeRecord ? this.activeRecord.sampel : 0; },
            get unsur() { return this.activeRecord ? this.activeRecord.unsur : []; },
            get predikat() {
                const s = this.skor;
                if (s >= 88.31) return { label: 'Sangat Baik', cls: 'badge-sb' };
                if (s >= 76.61) return { label: 'Baik',        cls: 'badge-b'  };
                if (s >= 65.00) return { label: 'Cukup',       cls: 'badge-c'  };
                return { label: 'Tidak Baik', cls: 'badge-kb' };
            },
            get tren() {
                const idx = this.chartData.findIndex(d => d.key === this.activePeriod);
                if (idx <= 0) return null;
                return +(this.skor - this.chartData[idx - 1].skor).toFixed(2);
            },
    
            chart: { tooltip: null },
            drawChart() {
                const el = this.$refs.chartSvg;
                if (!el || this.chartData.length < 2) return;
                const W = el.parentElement.clientWidth || 560;
                const H = Math.min(200, Math.max(140, W * 0.28));
                const PAD = { t:18, r:16, b:34, l:38 };
                const cW = W - PAD.l - PAD.r;
                const cH = H - PAD.t - PAD.b;
                const scores = this.chartData.map(d => d.skor);
                const minY = Math.floor(Math.min(...scores) - 5);
                const maxY = Math.ceil(Math.max(...scores) + 5);
                const xP = i => PAD.l + (i / (this.chartData.length - 1)) * cW;
                const yP = v => PAD.t + cH - ((v - minY) / (maxY - minY)) * cH;
    
                const cs = getComputedStyle(document.documentElement);
                const teal    = cs.getPropertyValue('--teal-primary').trim();
                const border  = cs.getPropertyValue('--border').trim();
                const muted   = cs.getPropertyValue('--text-muted').trim();
                const bgSurf  = cs.getPropertyValue('--bg-surface').trim();
    
                const lineD = this.chartData.map((d,i) => `${i===0?'M':'L'}${xP(i).toFixed(1)},${yP(d.skor).toFixed(1)}`).join(' ');
                const areaD = lineD + ` L${xP(this.chartData.length-1).toFixed(1)},${(PAD.t+cH).toFixed(1)} L${PAD.l},${(PAD.t+cH).toFixed(1)} Z`;
    
                const gridStep = Math.ceil((maxY - minY) / 4);
                let gridLines = '';
                for (let v = minY; v <= maxY; v += gridStep) {
                    const y = yP(v).toFixed(1);
                    gridLines += `<line x1='${PAD.l}' y1='${y}' x2='${W-PAD.r}' y2='${y}' stroke='${border}' stroke-width='1'/>`;
                    gridLines += `<text x='${PAD.l-6}' y='${y}' fill='${muted}' font-size='10' text-anchor='end' dominant-baseline='middle' font-family='Plus Jakarta Sans'>${v}</text>`;
                }
                const xlabels = this.chartData.map((d,i) =>
                    `<text x='${xP(i).toFixed(1)}' y='${(PAD.t+cH+16).toFixed(1)}' fill='${muted}' font-size='10' text-anchor='middle' font-family='Plus Jakarta Sans'>${d.period}</text>`
                ).join('');
                const dots = this.chartData.map((d,i) => {
                    const isAct = d.key === this.activePeriod;
                    return `<circle cx='${xP(i).toFixed(1)}' cy='${yP(d.skor).toFixed(1)}' r='${isAct?7:4.5}'
                        fill='${isAct ? bgSurf : teal}' stroke='${teal}' stroke-width='${isAct?2.5:0}'
                        data-key='${d.key}' data-skor='${d.skor}' data-sampel='${d.sampel}'
                        class='chart-dot' style='cursor:pointer;transition:r .15s'/>`;
                }).join('');
    
                el.setAttribute('viewBox', `0 0 ${W} ${H}`);
                el.setAttribute('height', H);
                el.setAttribute('width', '100%');
                el.innerHTML = `
                    <defs>
                        <linearGradient id='ag' x1='0' y1='0' x2='0' y2='1'>
                            <stop offset='0%' stop-color='${teal}' stop-opacity='0.16'/>
                            <stop offset='100%' stop-color='${teal}' stop-opacity='0.01'/>
                        </linearGradient>
                    </defs>
                    ${gridLines}${xlabels}
                    <path d='${areaD}' fill='url(#ag)'/>
                    <path d='${lineD}' fill='none' stroke='${teal}' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'/>
                    ${dots}`;
    
                el.querySelectorAll('.chart-dot').forEach(dot => {
                    dot.addEventListener('click', () => {
                        const key = dot.getAttribute('data-key');
                        this.activePeriod = key;
                        this.$wire.setPeriod(key);
                        this.$nextTick(() => this.drawChart());
                    });
                    dot.addEventListener('mouseenter', e => {
                        this.chart.tooltip = {
                            period: dot.getAttribute('data-key'),
                            skor: dot.getAttribute('data-skor'),
                            sampel: dot.getAttribute('data-sampel'),
                        };
                    });
                    dot.addEventListener('mouseleave', () => { this.chart.tooltip = null; });
                });
            },
    
            init() {
                this.$nextTick(() => this.drawChart());
                window.addEventListener('resize', () => this.drawChart());
            }
        }" class="font-jakarta">
        @if($notFound)
            <div class="flex flex-col items-center justify-center py-32 gap-4">
                <x-umpak::icon name="search-x" class="w-14 h-14 text-(--text-muted)" />
                <p class="text-lg text-(--text-secondary)">Data instansi tidak ditemukan.</p>
                <a href="javascript:history.back()" class="text-sm font-semibold text-(--teal-primary) hover:underline">←
                    Kembali</a>
            </div>
        @else

            {{-- ══ HERO HEADER ══ --}}
            <div class="relative overflow-hidden bg-linear-to-br from-[#0c3a47] via-[#0d6b7a] to-[#075985]">
                <div class="absolute inset-0 opacity-10">
                    <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <pattern id="grid-page" width="40" height="40" patternUnits="userSpaceOnUse">
                                <path d="M 40 0 L 0 0 0 40" fill="none" stroke="white" stroke-width="1" />
                            </pattern>
                        </defs>
                        <rect width="100%" height="100%" fill="url(#grid-page)" />
                    </svg>
                </div>
                <div class="relative px-5 sm:px-8 py-8 max-w-7xl mx-auto">
                    {{-- Navigation --}}
                    <a href="{{ route('bale-organisasi.ikm.index') }}" wire:navigate.hover
                        class="group inline-flex items-center gap-2 text-white/70 hover:text-white text-xs font-semibold mb-6 transition-colors">
                        <x-umpak::icon name="arrow-left" class="w-4 h-4 transition-transform group-hover:-translate-x-1" />
                        Kembali ke Daftar IKM
                    </a>

                    {{-- Identity --}}
                    <div class="flex items-start gap-4 mb-7">
                        <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl flex items-center justify-center text-xl shrink-0"
                            style="background:rgba(255,255,255,.18); color:#fff; backdrop-filter:blur(6px); border:1.5px solid rgba(255,255,255,.25)">
                            {{ mb_strtoupper(mb_substr($instansi->nama_opd, 0, 2)) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-2 mb-1.5">
                                <span class="text-xs px-2.5 py-1 rounded-full"
                                    style="background:rgba(255,255,255,.2); color:#fff">
                                    {{ explode(' ', trim($instansi->nama_opd))[0] }}
                                </span>
                                <span class="text-xs font-semibold text-teal-300">
                                    TW{{ $periodeAktif?->triwulan }} {{ $periodeAktif?->tahun }}
                                </span>
                            </div>
                            <h1 class="text-xl sm:text-2xl lg:text-3xl text-white leading-tight">
                                {{ $instansi->nama_opd }}
                            </h1>
                        </div>
                    </div>

                    {{-- Score Metrics --}}
                    <div wire:ignore class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <div class="col-span-2 sm:col-span-1 rounded-2xl p-5"
                            style="background:rgba(255,255,255,.12); backdrop-filter:blur(8px); border:1px solid rgba(255,255,255,.2)">
                            <p class="text-xs font-semibold text-white/60 mb-1">Skor IKM</p>
                            <p class="text-5xl text-white tabular-nums" x-text="skor.toFixed(2)">
                                {{ number_format($skorAktif, 2) }}
                            </p>
                            <p class="text-xs text-white/60 mt-1">/ 100</p>
                        </div>
                        <div class="rounded-2xl p-4"
                            style="background:rgba(255,255,255,.1); backdrop-filter:blur(8px); border:1px solid rgba(255,255,255,.15)">
                            <p class="text-xs font-semibold text-white/60 mb-1">Predikat</p>
                            <p class="text-base text-white" x-text="predikat.label">{{ $predikat['label'] }}
                            </p>
                            <span :class="predikat.cls" class="mt-1 inline-block text-[10px] px-2 py-0.5 rounded-full"
                                x-text="predikat.label">{{ $predikat['label'] }}</span>
                        </div>
                        <div class="rounded-2xl p-4"
                            style="background:rgba(255,255,255,.1); backdrop-filter:blur(8px); border:1px solid rgba(255,255,255,.15)">
                            <p class="text-xs font-semibold text-white/60 mb-1">Responden</p>
                            <p class="text-base text-white tabular-nums" x-text="sampel.toLocaleString('id-ID')">
                                {{ number_format($sampel, 0, ',', '.') }}
                            </p>
                            <p class="text-xs text-white/60 mt-1">orang</p>
                        </div>
                        <div class="rounded-2xl p-4"
                            style="background:rgba(255,255,255,.1); backdrop-filter:blur(8px); border:1px solid rgba(255,255,255,.15)">
                            <p class="text-xs font-semibold text-white/60 mb-1">Tren vs Sebelumnya</p>
                            <p class="text-base text-white tabular-nums"
                                :class="tren === null ? '' : tren > 0.3 ? 'text-emerald-300' : tren < -0.3 ? 'text-rose-300' : ''"
                                x-text="tren === null ? 'N/A' : (tren > 0 ? '+' : '') + tren.toFixed(2)">
                                {{ $tren !== null ? ($tren > 0 ? '+' : '') . $tren : 'N/A' }}
                            </p>
                            <p class="text-xs text-white/60 mt-1"
                                x-text="tren === null ? 'Periode pertama' : tren > 0.3 ? '↑ meningkat' : tren < -0.3 ? '↓ menurun' : '→ stabil'">
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-5 sm:px-8 py-8 max-w-7xl mx-auto">
                {{-- ══ PERIOD SELECTOR ══ --}}
                <div wire:ignore class="flex items-center gap-2 px-5 sm:px-8 overflow-x-auto pb-2 mb-6">
                    @foreach($periodeList as $pKey)
                        @php [$py, $pt] = explode('-', $pKey); @endphp
                        <button
                            @click="activePeriod = '{{ $pKey }}'; $wire.setPeriod('{{ $pKey }}'); $nextTick(() => drawChart())"
                            :class="activePeriod === '{{ $pKey }}' ? 'bg-(--teal-primary) border-(--teal-primary) text-white' : 'bg-(--bg-elevated) border-(--border) text-(--text-secondary) hover:border-(--teal-primary) hover:text-(--teal-primary)'"
                            class="text-xs px-4 py-2 rounded-xl border-[1.5px] whitespace-nowrap transition-all">
                            TW{{ $pt }} {{ $py }}
                        </button>
                    @endforeach
                </div>

                <div class="grid lg:grid-cols-3 gap-5 mb-5">

                    {{-- ══ TREN CHART ══ --}}
                    <div wire:ignore
                        class="lg:col-span-2 bg-(--bg-surface) border border-(--border) rounded-2xl p-5 sm:p-6 shadow-(--shadow-sm)">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider mb-1 text-(--text-muted)">Riwayat
                                    Skor
                                </p>
                                <h2 class="text-base text-(--text-primary)">Tren IKM Semua Periode</h2>
                            </div>
                            <div class="flex items-center gap-3 text-xs text-(--text-muted)">
                                <span class="flex items-center gap-1.5">
                                    <svg width="16" height="4">
                                        <line x1="0" y1="2" x2="16" y2="2" stroke="var(--teal-primary)" stroke-width="2.5"
                                            stroke-linecap="round" />
                                    </svg>
                                    Skor IKM
                                </span>
                            </div>
                        </div>
                        <div class="relative">
                            <svg x-ref="chartSvg" style="overflow:visible"></svg>
                            {{-- Tooltip --}}
                            <div x-show="chart.tooltip" x-cloak
                                class="absolute pointer-events-none bg-(--bg-surface) border border-(--border) rounded-xl px-3 py-2 shadow-(--shadow-md) text-xs min-w-[120px]"
                                style="top: 10px; right: 10px">
                                <p class="font-semibold text-(--text-muted)" x-text="chart.tooltip?.period"></p>
                                <p class="text-xl text-(--teal-primary) mt-0.5" x-text="chart.tooltip?.skor">
                                </p>
                                <p class="text-(--text-muted) mt-0.5"
                                    x-text="parseInt(chart.tooltip?.sampel || 0).toLocaleString('id-ID') + ' responden'">
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- ══ PERBANDINGAN ══ --}}
                    <div
                        class="bg-(--bg-surface) border border-(--border) rounded-2xl p-5 sm:p-6 shadow-(--shadow-sm) flex flex-col gap-4">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider mb-1 text-(--text-muted)">Perbandingan
                            </p>
                            <h2 class="text-base text-(--text-primary)">vs IKM Kabupaten</h2>
                        </div>

                        {{-- Gauge instansi — wire:ignore agar Alpine tidak direset Livewire --}}
                        <div wire:ignore>
                            <div class="flex justify-between text-xs mb-1.5 font-semibold">
                                <span class="truncate text-(--text-primary)">IKM Periode saat ini</span>
                                <span class="text-(--teal-primary)" x-text="skor.toFixed(2)"></span>
                            </div>
                            <div class="h-3 rounded-full overflow-hidden bg-(--bg-elevated)">
                                <div class="h-full rounded-full transition-all duration-1000"
                                    :style="`background:linear-gradient(90deg,#0d9488,#0ea5e9); width:${skor}%`">
                                </div>
                            </div>
                        </div>

                        {{-- Gauge rata-rata — reaktif via kabAvg (entangled dari $wire) --}}
                        <div>
                            <div class="flex justify-between text-xs mb-1.5 font-semibold">
                                <span class="text-(--text-secondary)">IKM Kabupaten Ponorogo</span>
                                <span class="text-(--text-secondary)" x-text="kabAvg.toFixed(2)"></span>
                            </div>
                            <div class="h-3 rounded-full overflow-hidden bg-(--bg-elevated)">
                                <div class="h-full rounded-full bg-(--border-strong) transition-all duration-1000"
                                    :style="`width:${kabAvg}%`">
                                </div>
                            </div>
                        </div>

                        {{-- Delta — reaktif Alpine menggunakan kabAvg (entangled dari $wire) --}}
                        <div class="rounded-xl p-3 text-center bg-(--bg-elevated)">
                            <p class="text-2xl tabular-nums"
                                :class="(skor - kabAvg) >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400'"
                                x-text="((skor - kabAvg) >= 0 ? '+' : '') + (skor - kabAvg).toFixed(2)">
                            </p>
                            <p class="text-xs mt-1 text-(--text-secondary)"
                                x-text="(skor - kabAvg) >= 0 ? 'poin di atas rata-rata' : 'poin di bawah rata-rata'">
                            </p>
                        </div>

                        {{-- Rank — reaktif via kabRank & kabTotal (entangled dari $wire) --}}
                        <div class="rounded-xl p-3 text-center bg-(--teal-light)">
                            <p class="text-xs font-semibold mb-1 text-(--text-secondary)">Peringkat</p>
                            <p class="text-2xl text-(--teal-primary)" x-text="'#' + kabRank"></p>
                            <p class="text-xs text-(--text-muted)" x-text="`dari ${kabTotal} OPP`"></p>
                        </div>
                    </div>
                </div>

                {{-- ══ 9 UNSUR LAYANAN ══ --}}
                <div class="bg-(--bg-surface) border border-(--border) rounded-2xl p-5 sm:p-6 shadow-(--shadow-sm) mb-5">
                    <div class="flex items-center justify-between mb-5 flex-wrap gap-2">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider mb-1 text-(--text-muted)">Breakdown</p>
                            <h2 class="text-base text-(--text-primary)">Nilai per Unsur Layanan (9 Unsur)</h2>
                        </div>
                        <span
                            class="text-xs font-semibold px-2.5 py-1 rounded-full bg-(--bg-elevated) text-(--text-secondary)">
                            PermenPAN-RB No. 14/2017
                        </span>
                    </div>
                    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4" id="unsur-grid">
                        <template x-for="(u, idx) in unsur" :key="idx">
                            <div class="rounded-xl p-4 bg-(--bg-elevated)">
                                <div class="flex items-start justify-between gap-2 mb-2.5">
                                    <div class="flex items-center gap-2 min-w-0">
                                        <span
                                            class="w-5 h-5 rounded-md flex items-center justify-center text-[10px] shrink-0 bg-(--teal-light) text-(--teal-primary)"
                                            x-text="idx + 1"></span>
                                        <p class="text-xs font-semibold leading-tight text-(--text-primary)"
                                            x-text="u.label">
                                        </p>
                                    </div>
                                    <span
                                        :class="{
                                                                                                                                                                                                                        'badge-sb': u.nilai >= 3.5324,
                                                                                                                                                                                                                        'badge-b': u.nilai >= 3.0644 && u.nilai < 3.5324,
                                                                                                                                                                                                                        'badge-c': u.nilai >= 2.60 && u.nilai < 3.0644,
                                                                                                                                                                                                                        'badge-kb': u.nilai < 2.60
                                                                                                                                                                                                                    }"
                                        class="text-[10px] px-2 py-0.5 rounded-full shrink-0"
                                        x-text="u.nilai.toFixed(2)"></span>
                                </div>
                                <div class="h-2 rounded-full overflow-hidden bg-(--bg-muted)">
                                    <div class="h-full rounded-full transition-all duration-700" :class="{
                                                                        'bg-teal-500': u.nilai >= 3.5324,
                                                                        'bg-blue-500': u.nilai >= 3.0644 && u.nilai < 3.5324,
                                                                        'bg-amber-500': u.nilai >= 2.60 && u.nilai < 3.0644,
                                                                        'bg-rose-500': u.nilai < 2.60
                                                                    }" :style="`width:${(u.nilai / 4) * 100}%` ">
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- ══ RIWAYAT LENGKAP ══ --}}
                <div
                    class="bg-(--bg-surface) border border-(--border) rounded-2xl shadow-(--shadow-sm) overflow-hidden mb-5">
                    <div class="p-5 sm:p-6 border-b border-(--border)">
                        <p class="text-xs font-semibold uppercase tracking-wider mb-1 text-(--text-muted)">Arsip Lengkap</p>
                        <h2 class="text-base text-(--text-primary)">Riwayat Semua Periode</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="ikm-table">
                            <thead>
                                <tr>
                                    <th class="text-left">Periode</th>
                                    <th class="text-right">Responden</th>
                                    <th class="text-right">Skor IKM</th>
                                    <th class="text-center">Predikat</th>
                                    <th class="text-right">Δ vs Sebelumnya</th>
                                </tr>
                            </thead>
                            <tbody wire:ignore>
                                @php
                                    $historiOrdered = $histori->reverse()->values();
                                @endphp

                                @foreach($historiOrdered as $i => $record)
                                    @php
                                        $pKey = "{$record->tahun}-{$record->triwulan}";
                                        $skor = (float) $record->nilai_ikm;
                                        $prevRecord = $historiOrdered->get($i + 1);
                                        $delta = $prevRecord ? round($skor - (float) $prevRecord->nilai_ikm, 2) : null;

                                        // Predikat Badge (Server-side calculation remains)
                                        if ($skor >= 88.31) {
                                            $bc = 'badge-sb';
                                            $bl = 'Sangat Baik';
                                        } elseif ($skor >= 76.61) {
                                            $bc = 'badge-b';
                                            $bl = 'Baik';
                                        } elseif ($skor >= 65) {
                                            $bc = 'badge-c';
                                            $bl = 'Cukup';
                                        } else {
                                            $bc = 'badge-kb';
                                            $bl = 'Tidak Baik';
                                        }
                                    @endphp
                                    <tr wire:key="hist-row-{{ $pKey }}"
                                        @click="activePeriod = '{{ $pKey }}'; $wire.setPeriod('{{ $pKey }}'); $nextTick(() => drawChart())"
                                        class="cursor-pointer transition-all border-b border-(--border) last:border-0 hover:bg-(--teal-light)"
                                        :class="activePeriod === '{{ $pKey }}' ? 'bg-(--teal-light) [&>td:first-child]:border-l-4 [&>td:first-child]:border-(--teal-primary)' : ''">
                                        <td class="transition-all">
                                            <span class="font-semibold text-sm transition-colors"
                                                :class="activePeriod === '{{ $pKey }}' ? 'text-(--teal-primary)' : 'text-(--text-primary)'">
                                                TW{{ $record->triwulan }} {{ $record->tahun }}
                                            </span>
                                            <span x-show="activePeriod === '{{ $pKey }}'"
                                                x-transition:enter="transition ease-out duration-200"
                                                x-transition:enter-start="opacity-0 scale-95"
                                                x-transition:enter-end="opacity-100 scale-100"
                                                class="ml-2 text-[10px] px-2 py-0.5 rounded-full bg-(--teal-light) text-(--teal-primary) font-bold">
                                                Aktif
                                            </span>
                                        </td>
                                        <td class="text-right tabular-nums text-(--text-secondary)">
                                            {{ number_format($record->sampel ?? 0, 0, ',', '.') }}
                                        </td>
                                        <td class="text-right">
                                            <span class="text-base tabular-nums font-bold"
                                                :class="activePeriod === '{{ $pKey }}' ? 'text-(--teal-primary)' : 'text-(--text-secondary)'">
                                                {{ number_format($skor, 2) }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="px-3 py-1 bg-brand-teal text-white text-[10px] font-black rounded-lg">
                                                {{ $bl }}
                                            </div>
                                        </td>
                                        <td class="text-right">
                                            @if($delta !== null)
                                                <span
                                                    class="text-sm tabular-nums {{ $delta > 0.3 ? 'text-emerald-600 dark:text-emerald-400' : ($delta < -0.3 ? 'text-rose-600 dark:text-rose-400' : 'text-(--text-muted)') }}">
                                                    {{ $delta > 0.3 ? '↑ +' : ($delta < -0.3 ? '↓ ' : '') }}{{ $delta }}
                                                </span>
                                            @else
                                                <span class="text-(--text-muted) text-sm">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- ══ ANALISIS DETAIL (COMPARATIVE MATRIX) ══ --}}
                <div
                    class="bg-(--bg-surface) border border-(--border) rounded-2xl shadow-(--shadow-sm) overflow-hidden mb-8">
                    <div class="p-5 sm:p-6 border-b border-(--border)">
                        <p class="text-xs font-semibold uppercase tracking-wider mb-1 text-(--text-muted)">Analisis Riwayat
                            Detail</p>
                        <h2 class="font-bold text-base text-(--text-primary)">Perbandingan 9 Unsur Antar Periode</h2>
                        <p class="text-xs mt-1 text-(--text-muted)">Menarik ke samping untuk melihat riwayat lebih lengkap
                        </p>
                    </div>

                    <div
                        class="overflow-x-auto scrollbar-thin scrollbar-thumb-(--border-strong) scrollbar-track-transparent">
                        <table class="w-full border-collapse">
                            <thead>
                                <tr class="bg-(--bg-elevated) border-b border-(--border)">
                                    {{-- Sticky Header Unsur --}}
                                    <th
                                        class="sticky left-0 z-10 bg-(--bg-elevated) py-4 px-5 text-left text-xs font-bold text-(--text-muted) border-r border-(--border) min-w-[200px]">
                                        9 UNSUR PELAYANAN
                                    </th>
                                    {{-- Dynamic Columns for Periods (Newest to Oldest) --}}
                                    <template x-for="d in [...chartData].reverse()" :key="d.key">
                                        <th class="py-4 px-4 text-center min-w-[120px] transition-colors"
                                            :class="activePeriod === d.key ? 'bg-(--teal-light) text-(--teal-primary)' : 'text-(--text-secondary)'">
                                            <p class="text-[10px] font-bold uppercase tracking-tight"
                                                x-text="d.period.split(' ')[1]"></p>
                                            <p class="text-xs font-bold" x-text="d.period.split(' ')[0]"></p>
                                        </th>
                                    </template>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Loop through 9 Unsur --}}
                                <template x-for="idx in 9" :key="idx">
                                    <tr
                                        class="border-b border-(--border) last:border-0 hover:bg-(--bg-elevated)/50 transition-colors">
                                        {{-- Sticky Column Unsur Label --}}
                                        <td
                                            class="sticky left-0 z-10 bg-(--bg-surface) py-3.5 px-5 border-r border-(--border) shadow-[2px_0_5px_-2px_rgba(0,0,0,0.05)]">
                                            <div class="flex items-center gap-2.5">
                                                <span
                                                    class="w-5 h-5 rounded-md flex items-center justify-center text-[10px] font-bold shrink-0 bg-(--bg-elevated) text-(--text-muted)"
                                                    x-text="idx"></span>
                                                <p class="text-xs font-bold text-(--text-primary) truncate max-w-[150px]"
                                                    x-text="chartData[0]?.unsur[idx-1]?.label || 'Unsur ' + idx"></p>
                                            </div>
                                        </td>
                                        {{-- Data Cells --}}
                                        <template x-for="d in [...chartData].reverse()" :key="d.key">
                                            <td class="py-3.5 px-4 text-center tabular-nums transition-colors"
                                                :class="activePeriod === d.key ? 'bg-(--teal-light)' : ''">
                                                <div class="flex flex-col items-center gap-1">
                                                    <span class="text-sm font-extrabold"
                                                        :class="activePeriod === d.key ? 'text-(--teal-primary)' : 'text-(--text-secondary)'"
                                                        x-text="d.unsur[idx-1]?.nilai.toFixed(2)">
                                                    </span>
                                                    {{-- Visual indicator (dot) --}}
                                                    <div class="w-1.5 h-1.5 rounded-full" :class="{
                                                                                        'bg-teal-500': d.unsur[idx-1]?.nilai >= 3.5324,
                                                                                        'bg-blue-500': d.unsur[idx-1]?.nilai >= 3.0644 && d.unsur[idx-1]?.nilai < 3.5324,
                                                                                        'bg-amber-500': d.unsur[idx-1]?.nilai >= 2.60 && d.unsur[idx-1]?.nilai < 3.0644,
                                                                                        'bg-rose-500': d.unsur[idx-1]?.nilai < 2.60
                                                                                    }">
                                                    </div>
                                                </div>
                                            </td>
                                        </template>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    <div
                        class="p-4 bg-(--bg-elevated) border-t border-(--border) flex gap-5 overflow-x-auto whitespace-nowrap">
                        <div class="flex items-center gap-1.5 text-[10px] font-bold text-(--text-muted)">
                            <div class="w-2 h-2 rounded-full bg-teal-500"></div> SANGAT BAIK
                        </div>
                        <div class="flex items-center gap-1.5 text-[10px] font-bold text-(--text-muted)">
                            <div class="w-2 h-2 rounded-full bg-blue-500"></div> BAIK
                        </div>
                        <div class="flex items-center gap-1.5 text-[10px] font-bold text-(--text-muted)">
                            <div class="w-2 h-2 rounded-full bg-amber-500"></div> CUKUP
                        </div>
                        <div class="flex items-center gap-1.5 text-[10px] font-bold text-(--text-muted)">
                            <div class="w-2 h-2 rounded-full bg-rose-500"></div> KURANG
                        </div>
                    </div>
                </div>

                {{-- ══ FOOTER NOTE ══ --}}
                <p class="text-[10px] text-center italic text-(--text-muted) pb-2">
                    * Data IKM berdasarkan hasil survei yang telah diverifikasi. Diperbarui per Triwulan.
                </p>
            </div>

        @endif
    </div>
</div>