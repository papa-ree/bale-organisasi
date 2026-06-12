<x-bale-organisasi::layouts.error>
    <x-slot:title>Sedang Dalam Pemeliharaan</x-slot:title>
    <div class="min-h-screen flex items-center justify-center text-center">
        <div>
            <h1 class="text-9xl font-bold text-gray-200">503</h1>
            <h2 class="text-2xl font-semibold mt-4">Sedang Dalam Pemeliharaan</h2>
            <p class="text-gray-600 mt-2">Sistem sedang dalam pemeliharaan. Silakan kembali nanti.</p>
            <a href="{{ url('/') }}" wire:navigate class="mt-6 inline-block text-blue-600 font-medium">
                Kembali ke Beranda
            </a>
        </div>
    </div>
</x-bale-organisasi::layouts.error>