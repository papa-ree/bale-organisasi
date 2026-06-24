<div class="min-h-screen bg-slate-50 dark:bg-slate-900 transition-colors duration-300">

    {{-- Page Header --}}
    <div class="bg-linear-to-br from-[#0c3a47] via-[#0d6b7a] to-[#075985] pt-12 pb-24 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="grid-show" width="40" height="40" patternUnits="userSpaceOnUse">
                        <path d="M 40 0 L 0 0 0 40" fill="none" stroke="white" stroke-width="1" />
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#grid-show)" />
            </svg>
        </div>
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <x-umpak::breadcrumb :items="[
        ['label' => 'Beranda', 'url' => '/'],
        ['label' => 'Berita', 'url' => route('bale-organisasi.post.index')],
        ['label' => $post->title],
    ]" class="mb-6 text-white/60 [&_ol]:text-white/60 [&_a]:text-white/60 [&_a:hover]:text-white [&_span]:text-white [&_svg]:text-white/30" />
            @if($post->categorySlug)
                <span
                    class="inline-block px-3 py-1 text-[10px] font-bold rounded-lg uppercase tracking-tight bg-white/15 text-white mb-4 backdrop-blur-sm">
                    {{ Str::headline($post->categorySlug) }}
                </span>
            @endif
            <h1 class="text-3xl sm:text-4xl font-black text-white leading-tight">{{ $post->title }}</h1>
            <div class="flex items-center gap-2 mt-4 text-white/60 text-sm">
                <x-umpak::icon name="calendar" class="w-4 h-4" />
                <time>{{ $post->formattedDate() }}</time>
            </div>
        </div>
    </div>

    {{-- Article Body (overlapping the header) --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-12 pb-20 relative z-10">
        <div class="lg:grid lg:grid-cols-12 lg:gap-8 items-start">

            {{-- Main Article Column --}}
            <div
                class="lg:col-span-8 bg-white dark:bg-slate-800 rounded-3xl shadow-xl border border-slate-100 dark:border-slate-700 overflow-hidden">
                {{-- Thumbnail --}}
                @if($post->hasThumbnail())
                    <div class="aspect-video overflow-hidden">
                        <img src="{{ cdn_asset('bagian-organisasi/thumbnails/' . $post->thumbnail) }}"
                            alt="{{ $post->title }}" class="w-full h-full object-cover">
                    </div>
                @endif

                {{-- Content --}}
                <div class="p-8 sm:p-12">
                    <div
                        class="prose prose-slate dark:prose-invert prose-headings:font-black prose-a:text-teal-600 dark:prose-a:text-teal-400 max-w-none">
                        <x-umpak::editorjs-renderer :content="$post->content" />
                    </div>

                    <div
                        class="mt-12 pt-8 border-t border-slate-100 dark:border-slate-700 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
                        <a href="{{ route('bale-organisasi.post.index') }}" wire:navigate
                            class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 dark:text-slate-400 hover:text-teal-600 dark:hover:text-teal-400 transition-colors">
                            <x-umpak::icon name="arrow-left" class="w-4 h-4" />
                            Kembali ke Berita
                        </a>
                        <x-umpak::share-button :url="request()->url()" :title="$post->title" />
                    </div>
                </div>
            </div>

            {{-- Sticky Sidebar Column --}}
            <aside class="hidden lg:block lg:col-span-4 sticky top-8">
                <x-bale-organisasi::suggested-posts :posts="$this->suggestedPosts" :currentId="$post->id"
                    variant="sidebar" />
            </aside>

            {{-- Mobile suggested posts (at bottom) --}}
            <div class="lg:hidden mt-8">
                <x-bale-organisasi::suggested-posts :posts="$this->suggestedPosts" :currentId="$post->id"
                    variant="grid" />
            </div>
        </div>
    </div>
</div>