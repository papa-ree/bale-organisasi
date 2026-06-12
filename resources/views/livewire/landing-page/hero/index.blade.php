@if($section)
    <section id="beranda"
        x-data="{ 
                                                                                                                                 current: 0, 
                                                                                                                                 total: {{ count($section->items) }},
                                                                                                                                 next() { this.current = (this.current + 1) % this.total },
                                                                                                                                 prev() { this.current = (this.current - 1 + this.total) % this.total }
                                                                                                                             }"
        x-init="setInterval(() => next(), 8000)" class="relative min-h-[90vh] flex flex-col overflow-hidden bg-teal-900">

        {{-- Background Visual - Default Layer --}}
        <div class="absolute inset-0 z-0">
            <div class="absolute inset-0 bg-gradient-to-br from-[#0c3a47] via-[#0d6b7a] to-[#075985] opacity-90"></div>
            <svg class="absolute inset-0 w-full h-full opacity-10" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                        <path d="M 40 0 L 0 0 0 40" fill="none" stroke="white" stroke-width="1" />
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#grid)" />
            </svg>
        </div>

        {{-- Carousel Content --}}
        <div class="relative flex-1 flex items-center z-10">
            <div class="absolute inset-0 bg-linear-to-r from-teal-600/20 via-teal-500/20 to-transparent"></div>

            @foreach($section->items as $index => $item)
                @php
                    // Penyesuaian akses data array dari Bale CMS
                    $title = $item['title'][0] ?? '';
                    $tagline = $item['tagline'][0] ?? '';
                    $description = $item['description'][0] ?? '';
                    $label = $item['label'][0] ?? '';
                    $url = $item['url'][0] ?? '#';
                    $class = $item['class'][0] ?? 'bg-white text-teal-700';
                    $showBtn = ($item['show'][0] ?? 'true') === 'true';
                    $image = $item['uploads'][0]['url'] ?? null;
                @endphp

                <div x-show="current === {{ $index }}" x-cloak
                    x-transition:enter="transition cubic-bezier(0.4, 0, 0.2, 1) duration-1000"
                    x-transition:enter-start="opacity-0 translate-x-20" x-transition:enter-end="opacity-100 translate-x-0"
                    x-transition:leave="transition cubic-bezier(0.4, 0, 0.2, 1) duration-1000"
                    x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 -translate-x-20"
                    class="absolute inset-0 flex items-center">

                    {{-- Per-slide Background Image with Zoom Animation --}}
                    @if($image)
                        <div class="absolute inset-0 z-0 overflow-hidden">
                            <img src="{{ $image }}" class="w-full h-full object-cover opacity-40 scale-110"
                                style="animation: slow-zoom 20s infinite alternate;" alt="">
                            {{-- <div class="absolute inset-0 bg-linear-to-r from-teal-950 via-teal-900/60 to-transparent"></div>
                            --}}
                        </div>
                    @endif

                    <div class="relative z-10 max-w-7xl mx-auto px-6 py-20 w-full">
                        <div class="max-w-3xl">
                            @if($tagline)
                                <div x-transition:enter="transition delay-300 duration-700"
                                    x-transition:enter-start="opacity-0 -translate-y-4"
                                    x-transition:enter-end="opacity-100 translate-y-0">
                                    <span
                                        class="inline-block px-4 py-1.5 text-xs font-bold tracking-widest uppercase text-teal-200 bg-white/10 rounded-full mb-6 backdrop-blur-md border border-white/10">
                                        {{ $tagline }}
                                    </span>
                                </div>
                            @endif

                            <h1
                                class="text-4xl sm:text-5xl lg:text-7xl font-black text-white leading-[1.1] mb-6 drop-shadow-2xl">
                                {{ $title }}
                            </h1>

                            <p class="text-white/80 text-lg sm:text-xl mb-10 leading-relaxed max-w-2xl font-medium">
                                {{ $description }}
                            </p>

                            @if($showBtn && $label)
                                <div class="flex flex-col sm:flex-row gap-4">
                                    <a href="{{ $url }}"
                                        class="inline-flex items-center justify-center gap-3 px-8 py-4 text-sm font-semibold rounded-2xl transition-all shadow-2xl hover:shadow-teal-500/20 active:scale-95 group {{ $class }}">
                                        {{ $label }}
                                        <x-umpak::icon name="arrow-right"
                                            class="w-4 h-4 transition-transform group-hover:translate-x-1" />
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach

            {{-- Controls --}}
            @if(count($section->items) > 1)
                <div
                    class="absolute inset-x-0 top-1/2 -translate-y-1/2 flex justify-between px-4 sm:px-10 z-30 pointer-events-none">
                    <button @click="prev()" aria-label="Previous"
                        class="pointer-events-auto w-12 h-12 bg-white/10 hover:bg-teal-500 rounded-2xl flex items-center justify-center text-white backdrop-blur-xl transition-all border border-white/10 active:scale-90">
                        <x-umpak::icon name="chevron-left" class="w-6 h-6" />
                    </button>
                    <button @click="next()" aria-label="Next"
                        class="pointer-events-auto w-12 h-12 bg-white/10 hover:bg-teal-500 rounded-2xl flex items-center justify-center text-white backdrop-blur-xl transition-all border border-white/10 active:scale-90">
                        <x-umpak::icon name="chevron-right" class="w-6 h-6" />
                    </button>
                </div>

                {{-- Indicators --}}
                <div
                    class="absolute bottom-12 left-1/2 -translate-x-1/2 flex gap-3 z-30 bg-black/10 backdrop-blur-md px-4 py-2 rounded-full border border-white/10">
                    @foreach($section->items as $i => $s)
                        <button @click="current = {{ $i }}" class="h-1.5 rounded-full transition-all duration-500"
                            :class="current === {{ $i }} ? 'bg-teal-400 w-8' : 'bg-white/30 w-2'"
                            aria-label="Go to slide {{ $i + 1 }}"></button>
                    @endforeach
                </div>
            @endif
        </div>

        <style>
            @keyframes slow-zoom {
                from {
                    transform: scale(1.05);
                }

                to {
                    transform: scale(1.15);
                }
            }
        </style>

    </section>
@else
    <x-umpak::section-error name="Hero" />
@endif