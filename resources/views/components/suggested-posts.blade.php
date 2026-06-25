@props(['posts', 'currentId', 'variant' => 'grid'])

@php
    $filteredPosts = collect($posts)
        ->where('id', '!=', $currentId)
        ->take($variant === 'sidebar' ? 5 : 3);
@endphp

@if($filteredPosts->isNotEmpty())
    <div class="{{ $variant === 'grid' ? 'mt-16 max-w-4xl mx-auto px-4 sm:px-0' : '' }}">
        <div class="flex items-center justify-between mb-6">
            <h2 class="{{ $variant === 'grid' ? 'text-2xl' : 'text-lg' }} font-black text-slate-800 dark:text-white flex items-center gap-2">
                <span class="w-1.5 h-6 bg-teal-400 rounded-full"></span>
                {{ $variant === 'grid' ? 'Rekomendasi Berita' : 'Berita Lainnya' }}
            </h2>
            @if($variant === 'grid')
                <a href="{{ route('bale-organisasi.post.index') }}" wire:navigate.hover
                    class="text-sm font-bold text-teal-600 dark:text-teal-400 hover:opacity-80 transition-opacity">
                    Lihat Semua Berita →
                </a>
            @endif
        </div>

        <div class="{{ $variant === 'grid' ? 'grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6' : 'flex flex-col gap-4' }}">
            @foreach($filteredPosts as $item)
                <article class="group bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1 overflow-hidden {{ $variant === 'sidebar' ? 'flex items-center gap-4 p-3' : 'flex flex-col h-full' }}">
                    {{-- Thumbnail --}}
                    <a href="{{ route('bale-organisasi.post.show', $item->slug) }}" wire:navigate.hover
                        class="{{ $variant === 'sidebar' ? 'w-20 h-20 shrink-0 rounded-xl' : 'aspect-video' }} overflow-hidden relative block">
                        @if($item->hasThumbnail())
                            <img src="{{ $item->thumbnail }}" alt="{{ $item->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        @else
                            <div class="w-full h-full bg-linear-to-br from-slate-100 to-slate-200 dark:from-slate-700 dark:to-slate-800 flex items-center justify-center">
                                <x-umpak::icon name="image" class="{{ $variant === 'sidebar' ? 'w-5 h-5' : 'w-8 h-8' }} text-slate-300 dark:text-slate-600" />
                            </div>
                        @endif
                    </a>

                    {{-- Content --}}
                    <div class="{{ $variant === 'grid' ? 'p-5 flex flex-col flex-1' : 'min-w-0' }}">
                        @if($variant === 'grid')
                            <div class="flex items-center gap-2 mb-3 text-[10px] uppercase tracking-wider font-bold text-slate-400 dark:text-slate-500">
                                <x-umpak::icon name="calendar" class="w-3 h-3" />
                                <span>{{ $item->formattedDate() }}</span>
                            </div>
                        @endif
                        
                        <h3 class="{{ $variant === 'grid' ? 'text-sm' : 'text-xs' }} font-bold text-slate-800 dark:text-white line-clamp-2 group-hover:text-teal-600 dark:group-hover:text-teal-400 transition-colors {{ $variant === 'grid' ? 'mb-4 flex-1' : 'mb-1' }}">
                            <a href="{{ route('bale-organisasi.post.show', $item->slug) }}" wire:navigate.hover>
                                {{ $item->title }}
                            </a>
                        </h3>

                        @if($variant === 'grid')
                            <a href="{{ route('bale-organisasi.post.show', $item->slug) }}" wire:navigate.hover
                                class="inline-flex items-center gap-1.5 text-xs font-black text-teal-600 dark:text-teal-400">
                                Baca Selengkapnya
                                <x-umpak::icon name="arrow-right" class="w-3 h-3 transition-transform group-hover:translate-x-1" />
                            </a>
                        @else 
                            <span class="text-[10px] font-bold text-slate-400">{{ $item->formattedDate() }}</span>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>

        @if($variant === 'sidebar')
            <a href="{{ route('bale-organisasi.post.index') }}" wire:navigate.hover
                class="mt-6 flex items-center justify-center py-3 rounded-xl border border-dashed border-slate-200 dark:border-slate-700 text-xs font-bold text-slate-500 dark:text-slate-400 hover:border-teal-600 hover:text-teal-600 transition-all">
                Semua Berita
            </a>
        @endif
    </div>
@endif

