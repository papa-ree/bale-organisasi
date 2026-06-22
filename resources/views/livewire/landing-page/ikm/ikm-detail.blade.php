<div>
    {{-- ══ Data Injection (avoid Blade/JS string conflict in attributes) ══ --}}
    <script>
        window.ikmChartData = {!! $chartJson !!};
        window.ikmKabStatsByPeriod = {!! $kabStatsJson !!};
    </script>

    {{-- ══ Alpine Factory Function ══ --}}
    <script>
        window.ikmDetailApp = function ( initialActivePeriod )
        {
            return {
                chartData: window.ikmChartData || [],
                kabStatsMap: window.ikmKabStatsByPeriod || {},
                activePeriod: initialActivePeriod,
                visibleCount: 6,
                chart: { tooltip: null },

                /* ─── Computed ─── */
                get visibleChartData () { return this.chartData.slice( -this.visibleCount ); },
                get timelinePeriods () { return [ ...this.visibleChartData ].reverse(); },
                get hasMore () { return this.visibleCount < this.chartData.length; },
                get totalHidden () { return Math.max( 0, this.chartData.length - this.visibleCount ); },

                get activeRecord ()
                {
                    return this.chartData.find( d => d.key === this.activePeriod ) || this.chartData.at( -1 ) || null;
                },
                get kabStats () { return this.kabStatsMap[ this.activePeriod ] || { avg: 0, rank: 0, total: 0 }; },

                get skor () { return this.activeRecord?.skor ?? 0; },
                get kabAvg () { return this.kabStats.avg; },
                get kabRank () { return this.kabStats.rank; },
                get kabTotal () { return this.kabStats.total; },
                get sampel () { return this.activeRecord?.sampel ?? 0; },
                get unsur () { return this.activeRecord?.unsur ?? []; },

                get predikat ()
                {
                    const s = this.skor;
                    if ( s >= 88.31 ) return { label: 'Sangat Baik', cls: 'badge-sb', color: 'teal' };
                    if ( s >= 76.61 ) return { label: 'Baik', cls: 'badge-b', color: 'blue' };
                    if ( s >= 65.00 ) return { label: 'Cukup', cls: 'badge-c', color: 'amber' };
                    return { label: 'Tidak Baik', cls: 'badge-kb', color: 'rose' };
                },
                get tren ()
                {
                    const idx = this.chartData.findIndex( d => d.key === this.activePeriod );
                    if ( idx <= 0 ) return null;
                    return +( this.skor - this.chartData[ idx - 1 ].skor ).toFixed( 2 );
                },
                get delta () { return +( this.skor - this.kabAvg ).toFixed( 2 ); },

                /* ─── Actions ─── */
                loadMore ()
                {
                    this.visibleCount = Math.min( this.visibleCount + 1, this.chartData.length );
                    this.$nextTick( () => this.drawChart() );
                },
                setPeriod ( key )
                {
                    this.activePeriod = key;
                    this.$wire.setPeriod( key );
                    this.$nextTick( () => this.drawChart() );
                },

                /* ─── SVG Chart ─── */
                drawChart ()
                {
                    const el = this.$refs.chartSvg;
                    if ( !el || this.visibleChartData.length < 2 ) return;

                    const data = this.visibleChartData;
                    const W = el.parentElement.clientWidth || 560;
                    const H = Math.min( 240, Math.max( 160, W * 0.32 ) );
                    const PAD = { t: 20, r: 16, b: 36, l: 40 };
                    const cW = W - PAD.l - PAD.r;
                    const cH = H - PAD.t - PAD.b;

                    const scores = data.map( d => d.skor );
                    const minY = Math.floor( Math.min( ...scores ) - 5 );
                    const maxY = Math.ceil( Math.max( ...scores ) + 5 );
                    const xP = i => PAD.l + ( i / ( data.length - 1 ) ) * cW;
                    const yP = v => PAD.t + cH - ( ( v - minY ) / ( maxY - minY ) ) * cH;

                    const cs = getComputedStyle( document.documentElement );
                    const teal = cs.getPropertyValue( '--teal-primary' ).trim();
                    const border = cs.getPropertyValue( '--border' ).trim();
                    const muted = cs.getPropertyValue( '--text-muted' ).trim();
                    const bgSurf = cs.getPropertyValue( '--bg-surface' ).trim();

                    const lineD = data.map( ( d, i ) =>
                        ( i === 0 ? "M" : "L" ) + xP( i ).toFixed( 1 ) + "," + yP( d.skor ).toFixed( 1 )
                    ).join( " " );
                    const areaD = lineD
                        + " L" + xP( data.length - 1 ).toFixed( 1 ) + "," + ( PAD.t + cH ).toFixed( 1 )
                        + " L" + PAD.l + "," + ( PAD.t + cH ).toFixed( 1 ) + " Z";

                    const gridStep = Math.ceil( ( maxY - minY ) / 4 );
                    let gridLines = "";
                    for ( let v = minY; v <= maxY; v += gridStep ) {
                        const y = yP( v ).toFixed( 1 );
                        gridLines += "<line x1='" + PAD.l + "' y1='" + y + "' x2='" + ( W - PAD.r ) + "' y2='" + y + "' stroke='" + border + "' stroke-width='1' stroke-dasharray='4,4'/>";
                        gridLines += "<text x='" + ( PAD.l - 6 ) + "' y='" + y + "' fill='" + muted + "' font-size='10' text-anchor='end' dominant-baseline='middle' font-family='Plus Jakarta Sans'>" + v + "</text>";
                    }

                    const xlabels = data.map( ( d, i ) =>
                        "<text x='" + xP( i ).toFixed( 1 ) + "' y='" + ( PAD.t + cH + 18 ).toFixed( 1 ) + "' fill='" + muted + "' font-size='10' text-anchor='middle' font-family='Plus Jakarta Sans'>" + d.period + "</text>"
                    ).join( "" );

                    const verticals = data.map( ( d, i ) =>
                    {
                        if ( d.key !== this.activePeriod ) return "";
                        return "<line x1='" + xP( i ).toFixed( 1 ) + "' y1='" + PAD.t + "' x2='" + xP( i ).toFixed( 1 ) + "' y2='" + ( PAD.t + cH ).toFixed( 1 ) + "' stroke='" + teal + "' stroke-width='1.5' stroke-dasharray='3,3' opacity='0.5'/>";
                    } ).join( "" );

                    const dots = data.map( ( d, i ) =>
                    {
                        const isAct = d.key === this.activePeriod;
                        return "<circle cx='" + xP( i ).toFixed( 1 ) + "' cy='" + yP( d.skor ).toFixed( 1 ) + "' r='" + ( isAct ? 7 : 5 ) + "'"
                            + " fill='" + ( isAct ? bgSurf : teal ) + "' stroke='" + teal + "' stroke-width='" + ( isAct ? 2.5 : 0 ) + "'"
                            + " data-key='" + d.key + "' data-skor='" + d.skor + "' data-sampel='" + d.sampel + "' data-period='" + d.period + "'"
                            + " class='chart-dot' style='cursor:pointer'/>";
                    } ).join( "" );

                    el.setAttribute( "viewBox", "0 0 " + W + " " + H );
                    el.setAttribute( "height", H );
                    el.setAttribute( "width", "100%" );

                    el.innerHTML =
                        "<defs>"
                        + "<linearGradient id='ag' x1='0' y1='0' x2='0' y2='1'>"
                        + "<stop offset='0%' stop-color='" + teal + "' stop-opacity='0.18'/>"
                        + "<stop offset='100%' stop-color='" + teal + "' stop-opacity='0.01'/>"
                        + "</linearGradient></defs>"
                        + gridLines + xlabels + verticals
                        + "<path d='" + areaD + "' fill='url(#ag)'/>"
                        + "<path d='" + lineD + "' fill='none' stroke='" + teal + "' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'/>"
                        + dots;

                    el.querySelectorAll( ".chart-dot" ).forEach( dot =>
                    {
                        dot.addEventListener( "click", () => this.setPeriod( dot.getAttribute( "data-key" ) ) );
                        dot.addEventListener( "mouseenter", () =>
                        {
                            this.chart.tooltip = {
                                period: dot.getAttribute( "data-period" ),
                                skor: dot.getAttribute( "data-skor" ),
                                sampel: dot.getAttribute( "data-sampel" ),
                            };
                        } );
                        dot.addEventListener( "mouseleave", () => { this.chart.tooltip = null; } );
                    } );
                },

                init ()
                {
                    this.$nextTick( () => this.drawChart() );
                    window.addEventListener( "resize", () => this.drawChart() );
                }
            };
        };
    </script>

    {{-- ══ Root Alpine Wrapper ══ --}}
    <div x-data="ikmDetailApp('{{ $activePeriod }}')" class="font-jakarta">

        @if($notFound)
            <div class="flex flex-col items-center justify-center py-32 gap-4 text-center">
                <x-umpak::icon name="search-x" class="w-14 h-14 text-slate-300 dark:text-slate-600" />
                <p class="text-lg font-semibold text-slate-500 dark:text-slate-400">Data instansi tidak ditemukan.</p>
                <a href="javascript:history.back()"
                    class="text-sm font-bold text-teal-600 dark:text-teal-400 hover:underline flex items-center gap-1">
                    <x-umpak::icon name="arrow-left" class="w-3.5 h-3.5" /> Kembali
                </a>
            </div>
        @else

            {{-- ══ 1. HERO HEADER ══ --}}
            @include('bale-organisasi::livewire.landing-page.ikm.section.detail-hero')

            {{-- ══ 2. OVERLAP METRIC CARDS ══ --}}
            @include('bale-organisasi::livewire.landing-page.ikm.section.detail-metric-cards')

            {{-- ══ 3. MAIN 2-COLUMN LAYOUT ══ --}}
            <div class="px-5 sm:px-8 pb-12 max-w-7xl mx-auto">
                <div class="flex flex-col-reverse lg:flex-row gap-6 items-start">

                    {{-- LEFT: Main Content --}}
                    <div class="flex-1 min-w-0 space-y-5">

                        {{-- Trend Chart --}}
                        @include('bale-organisasi::livewire.landing-page.ikm.section.detail-trend-chart')

                        {{-- 9 Unsur Layanan --}}
                        @include('bale-organisasi::livewire.landing-page.ikm.section.detail-unsur-grid')

                        {{-- History Table --}}
                        {{-- @include('bale-organisasi::livewire.landing-page.ikm.section.detail-history-table') --}}

                        {{-- Comparative Matrix --}}
                        {{-- @include('bale-organisasi::livewire.landing-page.ikm.section.detail-comparative') --}}

                        {{-- Footer Note --}}
                        <p class="text-[10px] text-center italic text-slate-400 dark:text-slate-600 pb-2">
                            * Data IKM berdasarkan hasil survei yang telah diverifikasi. Diperbarui per Triwulan.
                        </p>
                    </div>

                    {{-- RIGHT: Sticky Sidebar --}}
                    <div class="w-full lg:w-72 xl:w-80 shrink-0 lg:sticky lg:top-4">
                        @include('bale-organisasi::livewire.landing-page.ikm.section.detail-period-sidebar')
                    </div>

                </div>
            </div>

        @endif
    </div>
</div>