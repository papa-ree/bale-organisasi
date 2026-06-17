@if($section)
@php
    $items = collect($section->items);
    $quickLinks = $items->filter(fn($i) => in_array('quick link', (array) ($i['grup'] ?? [])));
    $contacts = $items->filter(fn($i) => in_array('contact', (array) ($i['grup'] ?? [])));
    $socials = $items->filter(fn($i) => in_array('social', (array) ($i['grup'] ?? [])));
    $bgImages = $section->meta('background.images', []);
    $isSlider = $section->meta('background.type') === 'slider';
@endphp

<footer id="kontak" class="bg-slate-800 dark:bg-slate-950 text-white transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-12">

            {{-- Grid 1: Brand (Hero Data) & Social --}}
            <div >
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-12 h-12 rounded-2xl bg-linear-to-br from-[#0c3a47] via-[#0d6b7a] to-[#075985] flex items-center justify-center text-white font-black text-lg shadow-lg">
                        BO
                    </div>
                    <span class="font-bold text-white text-base leading-tight">
                        {{ $hero ? $hero->meta('title') : $section->meta('custom.tagline') }}<br />
                        <span class="text-teal-400 font-medium text-xs text-nowrap">{{ $hero ? $hero->meta('subtitle') : 'Setda Kab. Ponorogo' }}</span>
                    </span>
                </div>
                <p class="text-slate-400 text-sm leading-relaxed mb-6 italic opacity-80 decoration-teal-500/30">
                    "{{ $section->meta('subtitle') }}"
                </p>

                {{-- Social Icons from Footer Items (sm_ pattern) --}}
                <div class="flex flex-wrap gap-2">
                    @foreach($socials as $social)
                        @foreach($social as $key => $values)
                            @if(str_starts_with($key, 'sm_'))
                                @php 
                                    $platform = str_replace('sm_', '', $key);
                                    $socialUrl = $values[0] ?? '#';
                                    $iconName = match($platform) { 'x' => 'twitter', default => $platform };
                                @endphp
                                @if($socialUrl && $socialUrl !== '#')
                                    <a href="{{ $socialUrl }}" target="_blank" aria-label="{{ $platform }}"
                                        class="w-10 h-10 rounded-xl bg-slate-700 hover:bg-teal-600 flex items-center justify-center transition-all hover:scale-110 active:scale-95 shadow-lg shadow-black/20 group">
                                        <x-umpak::icon :name="$iconName" class="w-4 h-4" />
                                    </a>
                                @endif
                            @endif
                        @endforeach
                    @endforeach

                    {{-- Fallback jika grup social kosong, ambil dari config global --}}
                    @if($socials->isEmpty() && $section->meta('custom.show_social', true))
                        @foreach(['facebook', 'instagram', 'youtube', 'x'] as $platform)
                            @php $url = $umpakOrg->{'social'.ucfirst($platform)}; @endphp
                            @if($url)
                                <a href="{{ $url }}" target="_blank"
                                    class="w-10 h-10 rounded-xl bg-slate-700 hover:bg-teal-600 flex items-center justify-center transition-all hover:scale-110 active:scale-95">
                                    <x-umpak::icon :name="$platform === 'x' ? 'twitter' : $platform" class="w-4 h-4" />
                                </a>
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>

            {{-- Grid 2: Quick Links --}}
            <div>
                <h4 class="font-bold text-white mb-6 text-sm uppercase tracking-widest border-l-4 border-teal-500 pl-3">Navigasi Cepat</h4>
                <ul class="space-y-3">
                    @foreach($quickLinks as $link)
                        <li>
                            <a href="{{ $link['url'][0] ?? '#' }}" wire:navigate class="text-slate-400 hover:text-teal-400 text-sm transition-all hover:translate-x-1 inline-block">
                                {{ Str::title($link['nama'][0] ?? 'Link') }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Grid 3: Contact Details --}}
            <div  >
                <h4 class="font-bold text-white mb-6 text-sm uppercase tracking-widest border-l-4 border-teal-500 pl-3">Informasi Kontak</h4>
                <ul class="space-y-5 text-sm text-slate-400">
                    @foreach($contacts as $contact)
                        <li class="flex items-start gap-4 group">
                            <div class="w-9 h-9 rounded-xl bg-slate-700 group-hover:bg-teal-600 group-hover:text-white flex items-center justify-center shrink-0 text-teal-400 transition-colors shadow-lg shadow-black/10">
                                <x-umpak::icon :name="$contact['icon'][0] ?? 'info'" class="w-4 h-4" />
                            </div>
                            <span class="leading-relaxed pt-1">{{ $contact['value'][0] ?? '' }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Grid 4: Institutional Slider Images --}}
            <div  >
                {{-- <h4 class="font-bold text-white mb-6 text-sm uppercase tracking-widest border-l-4 border-teal-500 pl-3">Atribut Instansi</h4> --}}
                <div @class([
                    'grid gap-4',
                    'grid-cols-2' => $isSlider && count($bgImages) >= 2,
                    'grid-cols-1' => !$isSlider || count($bgImages) < 2
                ])>
                    @foreach(collect($bgImages)->take(2) as $img)
                        {{-- <div class="relative aspect-square rounded-2xl overflow-hidden shadow-2xl border border-slate-700 bg-white group p-3"> --}}
                            <img src="{{ $img['cdn_url'] ?? cdn_asset($img['path']) }}" class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-500" alt="Institutional Branding">
                        {{-- </div> --}}
                    @endforeach
                </div>
                
                @if($section->meta('custom.working_hours'))
                    <div class="mt-8 p-4 bg-slate-900/50 rounded-2xl border border-slate-700/50 backdrop-blur-sm">
                        <div class="flex items-center gap-2 mb-2">
                             <x-umpak::icon name="clock" class="w-3 h-3 text-teal-400" />
                             <p class="text-[10px] text-slate-500 font-bold uppercase tracking-tighter">Jam Layanan</p>
                        </div>
                        <p class="text-xs text-slate-300 font-medium italic">{{ $section->meta('custom.working_hours') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Bottom Bar --}}
    <div class="border-t border-slate-700/50 dark:border-slate-800 bg-slate-900/50">
        <div class="max-w-7xl mx-auto px-6 py-8 flex flex-col sm:flex-row items-center justify-between gap-6">
            <div class="flex flex-col gap-1 text-center sm:text-left">
                <p class="text-[11px] text-slate-400 font-bold tracking-wide">
                    {{ $section->meta('custom.copyright') }}
                </p>
                <p class="text-[10px] text-slate-600">Platform dikembangkan dengan teknologi Balé CMS Framework.</p>
            </div>
            <div class="flex items-center gap-4">
                <div class="hidden sm:flex items-center gap-2 opacity-50 grayscale hover:grayscale-0 hover:opacity-100 transition-all">
                    <x-umpak::icon name="shield-check" class="w-4 h-4 text-teal-600" />
                    <span class="text-[10px] text-slate-500 font-black uppercase tracking-[0.2em] whitespace-nowrap">Pemerintah Kabupaten Ponorogo</span>
                </div>
            </div>
        </div>
    </div>
</footer>
@else
    <x-umpak::section-error name="Footer" />
@endif
