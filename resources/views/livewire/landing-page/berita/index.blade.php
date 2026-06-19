@if($section)
    <section id="berita" class="py-20 bg-white dark:bg-slate-900 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Section Header --}}
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-4" data-aos="fade-up">
                <div class="max-w-2xl">
                    @if($section->meta('custom.tagline'))
                        <span
                            class="text-xs font-semibold text-teal-600 dark:text-teal-400 uppercase tracking-widest block mb-2">
                            {{ $section->meta('custom.tagline') }}
                        </span>
                    @endif
                    <h2 class="text-3xl font-bold text-slate-800 dark:text-white leading-tight">
                        {{ $section->meta('title') }}
                    </h2>
                    <p class="text-slate-500 dark:text-slate-400 mt-3 text-sm leading-relaxed">
                        {{ $section->meta('subtitle') }}
                    </p>
                </div>

                @if(count($section->buttons()) > 0)
                    <div class="hidden md:block">
                        @foreach($section->buttons() as $btn)
                            <a href="{{ $btn['url'] }}" wire:navigate.hover
                                class="inline-flex items-center gap-2 text-sm font-bold text-teal-600 dark:text-teal-400 hover:text-teal-700 dark:hover:text-teal-300 transition-all group">
                                {{ $btn['label'] }}
                                <span class="group-hover:translate-x-1 transition-transform">
                                    <x-umpak::icon name="move-right" />
                                </span>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- News Grid --}}
            <div class="grid md:grid-cols-3 gap-8" data-aos="fade-up" data-aos-delay="100">
                @forelse($posts as $post)
                    <article
                        class="group bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-3xl overflow-hidden hover:shadow-2xl hover:shadow-teal-500/10 hover:-translate-y-2 transition-all duration-500 flex flex-col">
                        <a href="{{ route('bale-organisasi.post.show', $post->slug) }}" wire:navigate.hover>
                            {{-- Thumbnail --}}
                            <div class="relative overflow-hidden aspect-video bg-slate-100 dark:bg-slate-700">
                                @if($post->hasThumbnail())
                                    <img src="{{ cdn_asset('bagian-organisasi/thumbnails/' . $post->thumbnail) }}"
                                        alt="{{ $post->title }}"
                                        class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" />
                                @else
                                    <div class="w-full h-full flex items-center justify-center opacity-30">
                                        <x-umpak::icon name="image" class="w-12 h-12" />
                                    </div>
                                @endif
                                <div
                                    class="absolute inset-0 bg-linear-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                                </div>
                                @if($post->categorySlug)
                                    <div class="absolute top-4 left-4">
                                        <span
                                            class="px-3 py-1 text-[10px] font-bold rounded-lg uppercase tracking-tight shadow-sm bg-teal-500 text-white">
                                            {{ Str::headline($post->categorySlug) }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                            {{-- Content --}}
                            <div class="p-6 flex flex-col flex-1">
                                <h3
                                    class="font-bold text-slate-800 dark:text-white text-base leading-snug mb-3 group-hover:text-teal-600 dark:group-hover:text-teal-400 transition-colors line-clamp-2">
                                    {{ $post->title }}
                                </h3>
                                @if($section->meta('custom.show_excerpt', true) && $post->excerpt)
                                    <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-3 mb-6 leading-relaxed">
                                        {{ $post->excerpt }}
                                    </p>
                                @endif
                                <div
                                    class="mt-auto flex items-center justify-between pt-5 border-t border-slate-100 dark:border-slate-700/50">
                                    <div class="flex items-center gap-2">
                                        <x-umpak::icon name="calendar" class="w-3.5 h-3.5 text-slate-400" />
                                        <span class="text-[11px] font-medium text-slate-400 dark:text-slate-500 uppercase">
                                            {{ $post->formattedDate() }}
                                        </span>
                                    </div>
                                    <a href="{{ route('bale-organisasi.post.show', $post->slug) }}" wire:navigate
                                        class="text-xs font-bold text-teal-600 dark:text-teal-400 flex items-center gap-1.5 hover:gap-2 transition-all">
                                        Baca <span class="tracking-widest">→</span>
                                    </a>
                                </div>
                            </div>
                        </a>
                    </article>
                @empty
                    <div
                        class="col-span-3 py-20 text-center border-2 border-dashed border-slate-200 dark:border-slate-800 rounded-3xl">
                        <p class="text-slate-400 text-sm">Belum ada berita yang diterbitkan.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
@else
    <x-umpak::section-error name="Berita" />
@endif