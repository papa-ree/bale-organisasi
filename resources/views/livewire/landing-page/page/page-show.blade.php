<div class="min-h-screen bg-slate-50 dark:bg-slate-900 transition-colors duration-300">

    {{-- Page Header --}}
    <div class="bg-linear-to-br from-teal-700 via-teal-600 to-sky-500 pt-12 pb-24 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
                <defs><pattern id="grid-page" width="40" height="40" patternUnits="userSpaceOnUse"><path d="M 40 0 L 0 0 0 40" fill="none" stroke="white" stroke-width="1" /></pattern></defs>
                <rect width="100%" height="100%" fill="url(#grid-page)" />
            </svg>
        </div>
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <x-umpak::breadcrumb :items="[
                ['label' => 'Beranda', 'url' => '/'],
                ['label' => $page->title],
            ]" class="mb-6 text-white/60 [&_a]:text-white/60 [&_a:hover]:text-white [&_.current]:text-white" />
            <h1 class="text-3xl sm:text-4xl font-black text-white leading-tight">{{ $page->title }}</h1>
        </div>
    </div>

    {{-- Page Content (overlapping the header) --}}
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 -mt-12 pb-20 relative z-10">
        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-xl border border-slate-100 dark:border-slate-700 overflow-hidden">
            <div class="p-8 sm:p-12">
                <div class="prose prose-slate dark:prose-invert prose-headings:font-black prose-a:text-teal-600 dark:prose-a:text-teal-400 max-w-none">
                    <x-umpak::editorjs-renderer :content="$page->content" />
                </div>
            </div>
        </div>
    </div>
</div>