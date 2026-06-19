<!DOCTYPE html>
<html lang="id" class="scroll-smooth dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- SEO --}}
    {{-- <x-seo::seo-meta /> --}}

    <title>{{ $title ?? $umpakOrg?->organizationName ?? config('app.name') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet" />

    <link rel="icon" type="image/x-icon" href="{{ cdn_asset('shared/favicon.ico') }}"
        referrerpolicy="{{ app()->isLocal() ? 'no-referrer' : 'strict-origin-when-cross-origin' }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <x-umpak::analytics />

    @livewireStyles
</head>

<body
    class="antialiased bg-white dark:bg-slate-900 transition-colors duration-300 scrollbar-gutter-stable scrollbar-thin scrollbar-track-slate-200 dark:scrollbar-track-slate-800 scrollbar-thumb-teal-500 dark:scrollbar-thumb-teal-600 hover:scrollbar-thumb-teal-600 dark:hover:scrollbar-thumb-teal-500 scrollbar-thumb-rounded-full scrollbar-track-rounded-full overscroll-none">

    {{-- Navbar --}}
    <x-bale-organisasi::navbar />

    {{-- Main Content --}}
    <main>
        {{ $slot }}
    </main>

    {{-- Footer --}}
    <livewire:bale-organisasi.landing-page.footer.index />

    <livewire:umpak.shared-components.floating-contact borderColor="teal-500" />

    @livewireScripts
</body>

</html>