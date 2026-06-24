@if(count($years) > 0)
    <script>
        window.yearlyIkmDashboard = ( yearsData ) =>
        {
            return {
                years: yearsData,
                selectedYear: yearsData[ 0 ]?.tahun ?? null,

                get selectedData ()
                {
                    return this.years.find( y => y.tahun === this.selectedYear ) || this.years[ 0 ];
                },

                get predikat ()
                {
                    if ( !this.selectedData ) return { label: '-', color: 'slate' };
                    const s = this.selectedData.avg_ikm;
                    if ( s >= 88.31 ) return { label: 'Sangat Baik', color: 'teal' };
                    if ( s >= 76.61 ) return { label: 'Baik', color: 'blue' };
                    if ( s >= 65.00 ) return { label: 'Cukup', color: 'amber' };
                    return { label: 'Tidak Baik', color: 'rose' };
                },

                selectYear ( tahun )
                {
                    this.selectedYear = tahun;
                    this.drawChart();
                },

                drawChart ()
                {
                    const el = this.$refs.yearlyChart;
                    if ( !el || !this.years.length ) return;

                    // Balik agar urutan kronologis: terlama → terbaru
                    const data       = [ ...this.years ].reverse();
                    const activeYear = this.selectedYear;

                    const W        = el.parentElement.clientWidth || 480;
                    const H        = 160;
                    const PAD      = { t: 28, r: 20, b: 36, l: 44 };
                    const cW       = W - PAD.l - PAD.r;
                    const cH       = H - PAD.t - PAD.b;

                    const scores   = data.map( d => d.avg_ikm );
                    const minY     = Math.max( 0, Math.floor( Math.min( ...scores ) - 5 ) );
                    const maxY     = Math.ceil( Math.max( ...scores ) + 5 );
                    const yP       = v => PAD.t + cH - ( ( v - minY ) / ( maxY - minY ) ) * cH;

                    const barCount = data.length;
                    const barGap   = cW / barCount;
                    const barW     = Math.min( barGap * 0.55, 48 );
                    const xCenter  = i => PAD.l + barGap * i + barGap / 2;

                    const isDark   = document.documentElement.classList.contains( 'dark' );
                    const teal     = '#0d9488';
                    const tealDim  = isDark ? 'rgba(13,148,136,0.45)' : 'rgba(13,148,136,0.35)';
                    const border   = isDark ? '#334155' : '#e2e8f0';
                    const muted    = isDark ? '#64748b' : '#94a3b8';
                    const currentY = {{ date('Y') }};

                    // Grid lines
                    let gridLines = '';
                    const gridCount = 4;
                    const gridStep  = ( maxY - minY ) / gridCount;
                    for ( let gi = 0; gi <= gridCount; gi++ ) {
                        const v  = minY + gridStep * gi;
                        const gy = yP( v ).toFixed( 1 );
                        gridLines += `<line x1="${ PAD.l }" y1="${ gy }" x2="${ W - PAD.r }" y2="${ gy }" stroke="${ border }" stroke-width="1" stroke-dasharray="3,3"/>`;
                        gridLines += `<text x="${ PAD.l - 6 }" y="${ gy }" fill="${ muted }" font-size="9" text-anchor="end" dominant-baseline="middle" font-family="Plus Jakarta Sans">${ v.toFixed(0) }</text>`;
                    }

                    // Bars
                    let defs = '<defs>';
                    let bars = '';
                    data.forEach( ( d, i ) => {
                        const cx       = xCenter( i );
                        const bx       = cx - barW / 2;
                        const topY     = yP( d.avg_ikm );
                        const bH       = ( PAD.t + cH ) - topY;
                        const isActive = d.tahun === activeYear;
                        const isCur    = d.tahun == currentY;
                        const radius   = 5;

                        // Semua bar solid — bar aktif full opacity, non-aktif sedikit dimmer
                        defs += `
                            <linearGradient id="bg${i}" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0%" stop-color="${ teal }" stop-opacity="${ isActive ? 0.9 : 0.45 }"/>
                                <stop offset="100%" stop-color="${ teal }" stop-opacity="${ isActive ? 0.65 : 0.25 }"/>
                            </linearGradient>`;

                        // Invisible hit area (full height) untuk kemudahan klik
                        bars += `
                            <rect x="${ bx.toFixed(1) }" y="${ PAD.t }" width="${ barW }" height="${ cH }"
                                  fill="transparent" style="cursor:pointer"
                                  onclick="this.closest('svg').dispatchEvent(new CustomEvent('bar-click', { detail: { tahun: ${ d.tahun } }, bubbles: true }))"/>`;

                        // Bar utama
                        bars += `
                            <rect x="${ bx.toFixed(1) }" y="${ topY.toFixed(1) }" width="${ barW }" height="${ bH.toFixed(1) }"
                                  rx="${ radius }" ry="${ radius }" fill="url(#bg${i})"
                                  style="cursor:pointer; transition: opacity 0.2s;"
                                  onclick="this.closest('svg').dispatchEvent(new CustomEvent('bar-click', { detail: { tahun: ${ d.tahun } }, bubbles: true }))"/>`;

                        // Label nilai di atas bar
                        bars += `
                            <text x="${ cx.toFixed(1) }" y="${ ( topY - 7 ).toFixed(1) }"
                                  fill="${ isActive ? teal : muted }" font-size="${ isActive ? 10 : 9 }" font-weight="${ isActive ? 800 : 600 }"
                                  text-anchor="middle" font-family="Plus Jakarta Sans"
                                  style="cursor:pointer"
                                  onclick="this.closest('svg').dispatchEvent(new CustomEvent('bar-click', { detail: { tahun: ${ d.tahun } }, bubbles: true }))">${ d.avg_ikm.toFixed(2) }</text>`;

                        // Label tahun di bawah
                        bars += `
                            <text x="${ cx.toFixed(1) }" y="${ ( PAD.t + cH + 15 ).toFixed(1) }"
                                  fill="${ isActive ? teal : muted }" font-size="9" font-weight="${ isActive ? 800 : 500 }"
                                  text-anchor="middle" font-family="Plus Jakarta Sans"
                                  style="cursor:pointer"
                                  onclick="this.closest('svg').dispatchEvent(new CustomEvent('bar-click', { detail: { tahun: ${ d.tahun } }, bubbles: true }))">${ d.tahun }</text>`;

                        // Ring highlight untuk bar yang aktif
                        if ( isActive ) {
                            bars += `
                                <rect x="${ ( bx - 2.5 ).toFixed(1) }" y="${ ( topY - 2.5 ).toFixed(1) }"
                                      width="${ barW + 5 }" height="${ ( bH + 5 ).toFixed(1) }"
                                      rx="${ radius + 2 }" ry="${ radius + 2 }"
                                      fill="none" stroke="${ teal }" stroke-width="2" opacity="0.7"/>`;
                        }

                        // Dashed ring untuk tahun berjalan (jika bukan yang sedang aktif)
                        if ( isCur && !isActive ) {
                            bars += `
                                <rect x="${ ( bx - 2 ).toFixed(1) }" y="${ ( topY - 2 ).toFixed(1) }"
                                      width="${ barW + 4 }" height="${ ( bH + 4 ).toFixed(1) }"
                                      rx="${ radius + 1 }" ry="${ radius + 1 }"
                                      fill="none" stroke="${ teal }" stroke-width="1.5" stroke-dasharray="4,2" opacity="0.4"/>`;
                        }
                    });

                    defs += '</defs>';

                    // Trend line
                    const lineD = data.map( ( d, i ) =>
                        `${ i === 0 ? 'M' : 'L' }${ xCenter(i).toFixed(1) },${ yP( d.avg_ikm ).toFixed(1) }`
                    ).join( ' ' );

                    el.setAttribute( 'viewBox', `0 0 ${ W } ${ H }` );
                    el.setAttribute( 'width', '100%' );
                    el.setAttribute( 'height', H );
                    el.innerHTML = `
                        ${ defs }
                        ${ gridLines }
                        ${ bars }
                        <path d="${ lineD }" fill="none" stroke="${ teal }" stroke-width="1.5"
                              stroke-linecap="round" stroke-linejoin="round" stroke-dasharray="4,3" opacity="0.4"
                              pointer-events="none"/>
                    `;
                },

                init ()
                {
                    this.$nextTick( () => {
                        this.drawChart();
                        // Pasang listener sekali di wrapper — tidak hilang saat drawChart()
                        this.$refs.chartWrapper.addEventListener( 'bar-click', ( e ) => {
                            this.selectYear( e.detail.tahun );
                        } );
                    } );
                    window.addEventListener( 'resize', () => this.drawChart() );
                    document.addEventListener( 'dark-mode-changed', () => this.drawChart() );
                }
            };
        };
    </script>

    <div x-data="yearlyIkmDashboard(@js($years))"
        class="mb-8 bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-2xl shadow-sm overflow-hidden">

        {{-- Top gradient accent --}}
        <div class="h-1 w-full bg-linear-to-r from-teal-500 to-sky-400"></div>

        <div class="p-5 sm:p-6">

            {{-- Card Header --}}
            <div class="flex items-start justify-between mb-6 gap-3 flex-wrap">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-0.5">
                        Rata-rata IKM Kabupaten
                    </p>
                    <p class="text-base font-black text-slate-700 dark:text-slate-200 mt-0.5">
                        Tren Nilai IKM
                        <span class="text-slate-400 dark:text-slate-500 font-medium text-sm ml-1">
                            ({{ $maxYears }} Tahun Terakhir)
                        </span>
                    </p>
                </div>

                {{-- Predikat badge — reaktif terhadap bar dipilih --}}
                <div class="flex flex-col items-end gap-1.5">
                    <span class="text-xs font-black px-3 py-1.5 rounded-xl transition-colors" :class="{
                        'bg-teal-100 dark:bg-teal-900/30 text-teal-700 dark:text-teal-400'   : predikat.color === 'teal',
                        'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400'   : predikat.color === 'blue',
                        'bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400': predikat.color === 'amber',
                        'bg-rose-100 dark:bg-rose-900/30 text-rose-700 dark:text-rose-400'   : predikat.color === 'rose',
                    }" x-text="predikat.label">
                    </span>
                    <p class="text-[10px] text-slate-400 dark:text-slate-500 font-medium"
                        x-text="'Tahun ' + selectedYear"></p>
                </div>
            </div>

            {{-- Score + Chart --}}
            <div class="flex flex-col sm:flex-row sm:items-end gap-5">

                {{-- Big Score — reaktif: berubah saat bar diklik --}}
                <div class="shrink-0 sm:pb-1">
                    <p class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1"
                        x-text="selectedYear == {{ date('Y') }} ? 'Nilai Saat Ini' : 'Nilai Tahun ' + selectedYear">
                    </p>
                    <div class="flex items-end gap-1.5">
                        <span class="text-5xl font-black text-slate-800 dark:text-white tabular-nums leading-none transition-all duration-300"
                            x-text="selectedData?.avg_ikm.toFixed(2)"></span>
                        <span class="text-sm text-slate-400 dark:text-slate-500 font-bold mb-1">/ 100</span>
                    </div>
                    <template x-if="selectedYear == {{ date('Y') }}">
                        <span class="inline-flex items-center gap-1 text-[9px] font-bold px-2 py-0.5 rounded-md bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 mt-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse inline-block"></span>
                            TAHUN BERJALAN
                        </span>
                    </template>
                </div>

                {{-- Bar Chart: klik bar untuk pilih tahun --}}
                <div class="flex-1 relative rounded-xl overflow-hidden bg-slate-50 dark:bg-slate-700/30 p-2"
                    x-ref="chartWrapper">
                    <svg x-ref="yearlyChart" style="overflow: visible; display: block;"></svg>
                </div>
            </div>

        </div>
    </div>
@endif