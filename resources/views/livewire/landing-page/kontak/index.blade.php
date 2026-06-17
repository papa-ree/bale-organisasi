@if($section)
    <section id="cta-kontak" @class([
        'py-20 relative overflow-hidden',
        'bg-linear-to-br from-[#0c3a47] via-[#0d6b7a] to-[#075985]' => $section->meta('custom.background_style') === 'gradient',
        'bg-slate-900' => $section->meta('custom.background_style') === 'image',
    ])>
        {{-- Background Image Overlay (jika tipe image) --}}
        @if($section->meta('custom.background_style') === 'image' && count($section->backgroundImages()) > 0)
            <div class="absolute inset-0 z-0">
                <img src="{{ cdn_asset($section->backgroundImages()[0]['url']) }}" class="w-full h-full object-cover opacity-30"
                    alt="background">
                <div class="absolute inset-0 bg-linear-to-b from-transparent to-slate-950/80"></div>
            </div>
        @endif

        <div class="max-w-4xl mx-auto px-6 text-center relative z-10" data-aos="fade-up">
            <h2 class="text-3xl md:text-4xl font-black text-white mb-4 leading-tight">
                {{ $section->meta('title') }}
            </h2>
            <p class="text-white/80 text-lg mb-10 max-w-2xl mx-auto leading-relaxed">
                {{ $section->meta('subtitle') }}
            </p>

            {{-- Buttons Loop --}}
            @if(count($section->buttons()) > 0)
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    @foreach($section->buttons() as $btn)
                        <a href="{{ $btn['url'] }}" @class([
                            'w-full sm:w-auto px-10 py-4 text-sm font-black rounded-2xl transition-all duration-300 shadow-xl active:scale-95 hover:-translate-y-1',
                            $btn['class'] ?: 'text-white border-2 border-white/50 hover:bg-white/10'
                        ])>
                            {{ $btn['label'] }}
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Subtle Decorative Elements --}}
        <div class="absolute -top-20 -left-20 w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-20 -right-20 w-64 h-64 bg-sky-400/10 rounded-full blur-3xl"></div>
    </section>
@else
    <x-umpak::section-error name="Kontak CTA" />
@endif