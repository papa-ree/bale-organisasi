@if(count($years) > 0)
    <script>
        window.yearlyIkmDashboard = ( yearsData ) =>
        {
            return {
                years: yearsData,
                activeYear: yearsData[ 0 ]?.tahun,

                get active ()
                {
                    return this.years.find( y => y.tahun === this.activeYear ) || this.years[ 0 ];
                },
                get predikat ()
                {
                    const s = this.active.avg_ikm;
                    if ( s >= 88.31 ) return { label: 'Sangat Baik', color: 'teal' };
                    if ( s >= 76.61 ) return { label: 'Baik', color: 'blue' };
                    if ( s >= 65.00 ) return { label: 'Cukup', color: 'amber' };
                    return { label: 'Tidak Baik', color: 'rose' };
                },

                drawChart ()
                {
                    const el = this.$refs.yearlyChart;
                    if ( !el ) return;

                    const data = this.active.quarterly;
                    if ( !data || data.length < 2 ) {
                        el.innerHTML = '<text x="50%" y="50%" text-anchor="middle" fill="#94a3b8" font-size="11">Data tidak cukup</text>';
                        return;
                    }

                    const W = el.parentElement.clientWidth || 420;
                    const H = 130;
                    const PAD = { t: 22, r: 16, b: 32, l: 40 };
                    const cW = W - PAD.l - PAD.r;
                    const cH = H - PAD.t - PAD.b;

                    const scores = data.map( d => d.skor );
                    const minY = Math.floor( Math.min( ...scores ) - 4 );
                    const maxY = Math.ceil( Math.max( ...scores ) + 4 );
                    const xP = i => PAD.l + ( i / ( data.length - 1 ) ) * cW;
                    const yP = v => PAD.t + cH - ( ( v - minY ) / ( maxY - minY ) ) * cH;

                    const isDark = document.documentElement.classList.contains( 'dark' );
                    const teal = '#0d9488';
                    const border = isDark ? '#334155' : '#e2e8f0';
                    const muted = isDark ? '#64748b' : '#94a3b8';
                    const bgSurf = isDark ? '#1e293b' : '#ffffff';

                    let gridLines = '';
                    const gridStep = Math.ceil( ( maxY - minY ) / 3 );
                    for ( let v = minY; v <= maxY; v += gridStep ) {
                        const y = yP( v ).toFixed( 1 );
                        gridLines += `<line x1="${ PAD.l }" y1="${ y }" x2="${ W - PAD.r }" y2="${ y }" stroke="${ border }" stroke-width="1" stroke-dasharray="3,3"/>`;
                        gridLines += `<text x="${ PAD.l - 6 }" y="${ y }" fill="${ muted }" font-size="9" text-anchor="end" dominant-baseline="middle" font-family="Plus Jakarta Sans">${ v }</text>`;
                    }

                    const xlabels = data.map( ( d, i ) =>
                        `<text x="${ xP( i ).toFixed( 1 ) }" y="${ ( PAD.t + cH + 16 ).toFixed( 1 ) }" fill="${ muted }" font-size="9" text-anchor="middle" font-family="Plus Jakarta Sans">${ d.label }</text>`
                    ).join( '' );

                    const lineD = data.map( ( d, i ) => `${ i === 0 ? 'M' : 'L' }${ xP( i ).toFixed( 1 ) },${ yP( d.skor ).toFixed( 1 ) }` ).join( ' ' );
                    const areaD = lineD + ` L${ xP( data.length - 1 ).toFixed( 1 ) },${ ( PAD.t + cH ).toFixed( 1 ) } L${ PAD.l },${ ( PAD.t + cH ).toFixed( 1 ) } Z`;

                    const dots = data.map( ( d, i ) =>
                        `<circle cx="${ xP( i ).toFixed( 1 ) }" cy="${ yP( d.skor ).toFixed( 1 ) }" r="5" fill="${ bgSurf }" stroke="${ teal }" stroke-width="2.5"/>
                                                                     <text x="${ xP( i ).toFixed( 1 ) }" y="${ ( yP( d.skor ) - 9 ).toFixed( 1 ) }" fill="${ teal }" font-size="9" font-weight="800" text-anchor="middle" font-family="Plus Jakarta Sans">${ d.skor.toFixed( 1 ) }</text>`
                    ).join( '' );

                    el.setAttribute( 'viewBox', `0 0 ${ W } ${ H }` );
                    el.setAttribute( 'width', '100%' );
                    el.setAttribute( 'height', H );
                    el.innerHTML = `
                            <defs>
                                <linearGradient id="yag" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0%" stop-color="${ teal }" stop-opacity="0.2"/>
                                    <stop offset="100%" stop-color="${ teal }" stop-opacity="0.01"/>
                                </linearGradient>
                            </defs>
                            ${ gridLines }${ xlabels }
                            <path d="${ areaD }" fill="url(#yag)"/>
                            <path d="${ lineD }" fill="none" stroke="${ teal }" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            ${ dots }
                        `;
                },

                init ()
                {
                    this.$nextTick( () => this.drawChart() );
                    window.addEventListener( 'resize', () => this.drawChart() );
                    document.addEventListener( 'dark-mode-changed', () => this.drawChart() );
                }
            };
        };
    </script>

    <div x-data="yearlyIkmDashboard(@js($years))"
        class="mb-8 bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl shadow-sm overflow-hidden">

        <div class="flex flex-col sm:flex-row">

            {{-- ═══ MAIN CARD AREA (kiri) ═══ --}}
            <div class="flex-1 relative overflow-hidden">

                {{-- Top gradient accent --}}
                <div class="h-1 w-full bg-linear-to-r from-teal-500 to-sky-400"></div>

                <div class="p-5 sm:p-6">

                    {{-- Card Header: Tahun + Predikat --}}
                    <div class="flex items-start justify-between mb-5 gap-3 flex-wrap">
                        <div>
                            <p
                                class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1">
                                Rata-rata IKM Kabupaten
                            </p>
                            <p class="text-xs text-slate-400 dark:text-slate-500 mt-1" x-text="'Tahun ' + activeYear"></p>
                        </div>

                        <span class="text-xs font-black px-3 py-1.5 rounded-xl transition-colors" :class="{
                                    'bg-teal-100 dark:bg-teal-900/30 text-teal-700 dark:text-teal-400'   : predikat.color === 'teal',
                                    'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400'   : predikat.color === 'blue',
                                    'bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400': predikat.color === 'amber',
                                    'bg-rose-100 dark:bg-rose-900/30 text-rose-700 dark:text-rose-400'   : predikat.color === 'rose',
                                }" x-text="predikat.label">
                        </span>
                    </div>

                    {{-- Big Score --}}
                    <div class="flex items-end gap-2 mb-5">
                        <span
                            class="text-5xl font-black text-slate-800 dark:text-white tabular-nums leading-none transition-all duration-500"
                            x-text="active.avg_ikm.toFixed(2)"></span>
                        <span class="text-sm text-slate-400 dark:text-slate-500 font-bold mb-1">/ 100</span>
                    </div>

                    {{-- Sparkline Chart --}}
                    <div class="relative rounded-xl overflow-hidden bg-slate-50 dark:bg-slate-700/30 mb-5 p-1">
                        <svg x-ref="yearlyChart" style="overflow: visible; display: block;"></svg>
                    </div>

                    {{-- Footer Stats --}}
                    <div class="grid grid-cols-3 gap-3">
                        <div class="bg-slate-50 dark:bg-slate-700/30 rounded-xl px-3 py-3 text-center">
                            <p class="text-sm font-black text-teal-600 dark:text-teal-400 tabular-nums leading-tight"
                                x-text="parseInt(active.total_sampel).toLocaleString('id-ID')"></p>
                            <p class="text-[9px] text-slate-400 dark:text-slate-500 font-bold uppercase mt-1">Responden</p>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-700/30 rounded-xl px-3 py-3 text-center">
                            <p class="text-sm font-black text-teal-600 dark:text-teal-400 tabular-nums leading-tight"
                                x-text="active.total_periode + ' TW'"></p>
                            <p class="text-[9px] text-slate-400 dark:text-slate-500 font-bold uppercase mt-1">Periode</p>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-700/30 rounded-xl px-3 py-3 text-center">
                            <p class="text-sm font-black tabular-nums leading-tight"
                                :class="active.avg_ikm >= 76.61 ? 'text-teal-600 dark:text-teal-400' : 'text-amber-600'"
                                x-text="active.avg_ikm.toFixed(1) + '%'"></p>
                            <p class="text-[9px] text-slate-400 dark:text-slate-500 font-bold uppercase mt-1">Pencapaian</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ═══ RIGHT SIDEBAR: Pilih Tahun ═══ --}}
            <div
                class="sm:w-48 shrink-0 border-t sm:border-t-0 sm:border-l border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50">

                <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-700">
                    <p class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest">
                        Pilih Tahun
                    </p>
                </div>

                {{-- Navigasi: Horizontal di Mobile, Vertikal di Desktop --}}
                <nav class="p-2 flex sm:flex-col gap-2 overflow-x-auto snap-x snap-mandatory scrollbar-hide">
                    <template x-for="year in years" :key="year.tahun">
                        <button @click="activeYear = year.tahun; $nextTick(() => drawChart())"
                            class="group shrink-0 w-[140px] sm:w-full text-left px-4 py-3 rounded-xl border transition-all duration-300 snap-start"
                            :class="activeYear === year.tahun
                                        ? 'bg-white dark:bg-slate-800 border-teal-200 dark:border-teal-800 text-teal-700 dark:text-teal-400 shadow-md ring-1 ring-teal-500/10'
                                        : 'border-transparent text-slate-500 dark:text-slate-400 hover:bg-white/50 dark:hover:bg-slate-700/50 hover:shadow-sm'">

                            <div class="flex items-center gap-2 mb-2">
                                <span class="w-2 h-2 rounded-full shrink-0 transition-all duration-500"
                                    :class="activeYear === year.tahun ? 'bg-teal-500 scale-110 shadow-[0_0_8px_rgba(20,184,166,0.5)]' : 'bg-slate-300 dark:bg-slate-600 scale-90'">
                                </span>
                                <span class="text-sm font-black tracking-tight" x-text="year.tahun"></span>
                            </div>

                            {{-- Skor kecil + badge --}}
                            <div class="flex gap-1.5 pl-4">
                                <div class="flex items-baseline gap-1">
                                    <span class="text-xs"
                                        :class="activeYear === year.tahun ? 'text-teal-600 dark:text-teal-400' : 'text-slate-600 dark:text-slate-400'"
                                        x-text="year.avg_ikm.toFixed(2)"></span>
                                </div>
                                <span
                                    class="text-[9px] font-extrabold px-2 py-0.5 rounded-lg w-fit transition-colors duration-300"
                                    :class="{
                                                'bg-teal-100 dark:bg-teal-900/40 text-teal-700 dark:text-teal-300'   : year.predikat.color === 'teal',
                                                'bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300'   : year.predikat.color === 'blue',
                                                'bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-300': year.predikat.color === 'amber',
                                                'bg-rose-100 dark:bg-rose-900/40 text-rose-700 dark:text-rose-300'   : year.predikat.color === 'rose',
                                            }" x-text="year.predikat.label">
                                </span>
                            </div>
                        </button>
                    </template>
                </nav>
            </div>


        </div>
    </div>
@endif