<div class="min-h-screen bg-slate-50 dark:bg-slate-900 transition-colors duration-300">
    {{-- Header Section --}}
    <div class="relative overflow-hidden bg-linear-to-br from-teal-700 via-teal-600 to-sky-500">
        {{-- Pattern overlay --}}
        <div class="absolute inset-0 opacity-10">
            <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="grid-page" width="40" height="40" patternUnits="userSpaceOnUse">
                        <path d="M 40 0 L 0 0 0 40" fill="none" stroke="white" stroke-width="1" />
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#grid-page)" />
            </svg>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 py-10 sm:py-14">
            <div data-aos="fade-up">
                <p class="text-[12px] tracking-widest uppercase text-teal-300 mb-2 opacity-80">Transparansi
                    Publik</p>
                <h1 class="text-2xl sm:text-3xl lg:text-4xl text-white mb-3 leading-tight tracking-tight">
                    Indeks Kepuasan Masyarakat
                </h1>
                <p class="text-sm sm:text-base text-white/70 max-w-2xl leading-relaxed">
                    Data hasil survei kepuasan masyarakat seluruh unit pelayanan publik Kabupaten Ponorogo. Dapat
                    diakses, dicari, dan diunduh secara bebas.
                </p>
            </div>
        </div>
    </div>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
        {{-- Livewire Content Section --}}
        <livewire:bale-organisasi.landing-page.ikm.section.ikm-list-content />

        {{-- Footer Note --}}
        {{-- <div class="px-4 py-8 border-t border-slate-100 dark:border-slate-800 mt-8">
            <p
                class="text-[10px] text-center italic text-slate-400 dark:text-slate-500 leading-relaxed max-w-2xl mx-auto">
                * Data IKM bersifat ilustratif untuk keperluan prototipe. Data resmi akan ditampilkan sesuai hasil
                survei yang telah diverifikasi sesuai regulasi yang berlaku.
            </p>
        </div> --}}
    </main>
</div>