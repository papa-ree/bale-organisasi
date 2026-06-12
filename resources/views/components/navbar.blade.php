<header
    class="sticky top-0 z-50 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md border-b border-slate-100 dark:border-slate-800 shadow-sm transition-colors duration-300">
    <nav x-data="umpakNav()" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">

            {{-- Brand --}}
            <div class="flex items-center gap-3">
                <div
                    class="w-10 h-10 rounded-xl bg-linear-to-br from-teal-500 to-sky-400 flex items-center justify-center text-white font-bold text-sm shadow-sm">
                    <x-umpak::cdn-img path="shared/logo.png" class="w-7 h-7 object-contain" />
                </div>
                <span class="font-bold text-slate-800 dark:text-white text-sm leading-tight hidden sm:block">
                    {{ $umpakOrg->organizationName }}<br />
                    <span class="text-teal-600 dark:text-teal-400 font-medium text-xs">Setda Kab. Ponorogo</span>
                </span>
            </div>

            {{-- Desktop Nav --}}
            <div class="hidden lg:flex items-center gap-6">
                @foreach ($umpakNav as $i => $item)
                    @php
                        $isInternal = str_starts_with($item->resolvedUrl, '/') || str_contains($item->resolvedUrl, config('app.url'));
                        $isAnchor = str_contains($item->resolvedUrl, '#');
                        $useNavigate = $isInternal && !$isAnchor;
                    @endphp

                    @if ($item->hasChildren())
                        <div class="relative cursor-pointer" @click="openDropdown({{ $i }})" @click.outside="closeDropdown()">
                            <button @class([
                                'flex items-center cursor-pointer gap-1 text-sm transition-colors duration-200 focus:outline-none',
                                'font-semibold text-teal-600 dark:text-teal-400' => str_starts_with(request()->url(), $item->resolvedUrl),
                                'font-medium text-slate-600 dark:text-slate-300 hover:text-teal-600 dark:hover:text-teal-400' => !str_starts_with(request()->url(), $item->resolvedUrl),
                            ]) type="button">
                                <span>{{ $item->name }}</span>
                                <x-umpak::icon name="chevron-down" class="w-3.5 h-3.5" />
                            </button>

                            <div x-show="isDropdownOpen({{ $i }})" x-cloak x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 translate-y-2"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                class="absolute left-1/2 -translate-x-1/2 mt-3 w-56 bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-100 dark:border-slate-700/50 py-2 z-50">
                                @foreach ($item->children as $child)
                                    @php
                                        $childInternal = str_starts_with($child->resolvedUrl, '/') || str_contains($child->resolvedUrl, config('app.url'));
                                        $childAnchor = str_contains($child->resolvedUrl, '#');
                                        $childNavigate = $childInternal && !$childAnchor;
                                    @endphp
                                    <a href="{{ $child->resolvedUrl }}"
                                        @if($childNavigate) wire:navigate.hover @endif
                                        class="block px-4 py-2 text-sm text-slate-600 dark:text-slate-300 hover:bg-teal-50 dark:hover:bg-teal-500/10 hover:text-teal-600 dark:hover:text-teal-400 transition-colors">
                                        {{ $child->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <a href="{{ $item->resolvedUrl }}"
                            @if($useNavigate) wire:navigate.hover @endif
                            @class([
                            'text-sm transition-colors duration-200',
                            'font-semibold text-teal-600 dark:text-teal-400' => request()->url() == $item->resolvedUrl || (request()->is('/') && $item->slug == 'beranda'),
                            'font-medium text-slate-600 dark:text-slate-300 hover:text-teal-600 dark:hover:text-teal-400' => request()->url() != $item->resolvedUrl,
                        ])>
                            {{ $item->name }}
                        </a>
                    @endif
                @endforeach

                {{-- Dark Mode Toggle --}}
                <button type="button"
                    class="hs-dark-mode-active:hidden block hs-dark-mode dark-toggle w-10 h-10 rounded-xl bg-slate-100/50 dark:bg-slate-800/50 flex items-center justify-center text-slate-600 dark:text-slate-300 hover:bg-teal-50 dark:hover:bg-slate-700 transition-all duration-300"
                    data-hs-theme-click-value="dark">
                    <span class="group inline-flex shrink-0 justify-center items-center size-9">
                        <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z" />
                        </svg>
                    </span>
                </button>
                <button type="button"
                    class="hs-dark-mode-active:block hidden hs-dark-mode dark-toggle w-10 h-10 rounded-xl bg-slate-100/50 dark:bg-slate-800/50 flex items-center justify-center text-slate-600 dark:text-slate-300 hover:bg-teal-50 dark:hover:bg-slate-700 transition-all duration-300"
                    data-hs-theme-click-value="light">
                    <span class="group inline-flex shrink-0 justify-center items-center size-9">
                        <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="4" />
                            <path d="M12 2v2" />
                            <path d="M12 20v2" />
                            <path d="m4.93 4.93 1.41 1.41" />
                            <path d="m17.66 17.66 1.41 1.41" />
                            <path d="M2 12h2" />
                            <path d="M20 12h2" />
                            <path d="m6.34 17.66-1.41 1.41" />
                            <path d="m19.07 4.93-1.41 1.41" />
                        </svg>
                    </span>
                </button>

                <a href="#kontak"
                    class="px-6 py-2.5 text-sm font-bold text-white bg-teal-600 hover:bg-teal-700 rounded-xl transition-all duration-300 shadow-md shadow-teal-600/20">
                    Hubungi Kami
                </a>
            </div>

            {{-- Mobile Actions --}}
            <div class="flex items-center gap-2 lg:hidden">
                <button @click="toggleTheme()"
                    class="dark-toggle w-10 h-10 rounded-xl bg-slate-100/50 dark:bg-slate-800/50 flex items-center justify-center text-slate-600 dark:text-slate-300"
                    aria-label="Toggle dark mode">
                    <x-umpak::icon name="moon" class="w-4 h-4 block dark:hidden" />
                    <x-umpak::icon name="sun" class="w-4 h-4 hidden dark:block" />
                </button>
                <button @click="toggleMobile()" aria-label="Toggle menu"
                    class="p-2.5 rounded-xl text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                    <x-umpak::icon name="menu" class="w-6 h-6" x-show="!mobileOpen" />
                    <x-umpak::icon name="x" class="w-6 h-6" x-show="mobileOpen" x-cloak />
                </button>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div x-show="mobileOpen" x-cloak x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
            class="lg:hidden pb-6 space-y-1 border-t border-slate-100 dark:border-slate-800 mt-2 pt-4">
            @foreach ($umpakNav as $i => $item)
                @php
                    $isInternal = str_starts_with($item->resolvedUrl, '/') || str_contains($item->resolvedUrl, config('app.url'));
                    $isAnchor = str_contains($item->resolvedUrl, '#');
                    $useNavigate = $isInternal && !$isAnchor;
                @endphp

                @if ($item->hasChildren())
                    <div class="space-y-1">
                        <div class="px-4 py-2 text-xs font-bold text-slate-400 uppercase tracking-wider">{{ $item->name }}
                        </div>
                        @foreach ($item->children as $child)
                            @php
                                $childInternal = str_starts_with($child->resolvedUrl, '/') || str_contains($child->resolvedUrl, config('app.url'));
                                $childAnchor = str_contains($child->resolvedUrl, '#');
                                $childNavigate = $childInternal && !$childAnchor;
                            @endphp
                            <a href="{{ $child->resolvedUrl }}"
                                @if($childNavigate) wire:navigate.hover @endif
                                class="block px-4 py-2 text-sm font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-xl">
                                {{ $child->name }}
                            </a>
                        @endforeach
                    </div>
                @else
                    <a href="{{ $item->resolvedUrl }}"
                        @if($useNavigate) wire:navigate.hover @endif
                        @class([
                        'block px-4 py-2.5 text-sm rounded-xl transition-all',
                        'font-semibold text-teal-600 dark:text-teal-400 bg-teal-50 dark:bg-teal-900/20' => request()->url() == $item->resolvedUrl,
                        'font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800' => request()->url() != $item->resolvedUrl,
                    ])>
                        {{ $item->name }}
                    </a>
                @endif
            @endforeach
            <a href="#kontak"
                class="block px-4 py-3 text-sm font-bold text-white bg-teal-600 hover:bg-teal-700 rounded-xl text-center mt-4 shadow-lg shadow-teal-600/20">
                Hubungi Kami
            </a>
        </div>
    </nav>
</header>