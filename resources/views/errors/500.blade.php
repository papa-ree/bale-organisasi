<x-bale-organisasi::layouts.error>
    <x-slot:title>Kesalahan Server</x-slot:title>
    <div class="min-h-screen flex items-center justify-center text-center">
        <div>
            <h1 class="text-9xl font-bold text-gray-200">500</h1>
            <h2 class="text-2xl font-semibold mt-4">Kesalahan Server</h2>
            <p class="text-gray-600 mt-2">Terjadi kesalahan pada server. Silakan coba beberapa saat lagi.</p>
            <a href="{{ url('/') }}" wire:navigate class="mt-6 inline-block text-blue-600 font-medium">
                Kembali ke Beranda
            </a>
        </div>
    </div>
</x-bale-organisasi::layouts.error>