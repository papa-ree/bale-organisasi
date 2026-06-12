<x-bale-organisasi::layouts.error>
    <x-slot:title>Halaman Tidak Ditemukan</x-slot:title>
    <div class="min-h-screen flex items-center justify-center text-center">
        <div>
            <h1 class="text-9xl font-bold text-gray-200">404</h1>
            <h2 class="text-2xl font-semibold mt-4">Halaman Tidak Ditemukan</h2>
            <p class="text-gray-600 mt-2">Halaman yang Anda cari tidak ada atau telah dipindahkan.</p>
            <a href="{{ url('/') }}" wire:navigate class="mt-6 inline-block text-blue-600 font-medium">
                Kembali ke Beranda
            </a>
        </div>
    </div>
</x-bale-organisasi::layouts.error>