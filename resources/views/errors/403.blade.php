@extends('bale-organisasi::layouts.error')

<x-slot:title>Akses Ditolak</x-slot:title>

@section('content')
<div class="min-h-[80vh] flex items-center justify-center px-4 py-16">
    <div class="max-w-2xl w-full text-center">

        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-rose-50 dark:bg-rose-900/30 border border-rose-200 dark:border-rose-700 text-rose-600 dark:text-rose-400 text-xs font-bold uppercase tracking-widest mb-8">
            <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
            Akses Ditolak
        </div>

        <div class="relative inline-flex items-center justify-center mb-8">
            <div class="text-[10rem] sm:text-[14rem] font-black leading-none bg-linear-to-br from-rose-200 via-rose-300 to-pink-300 dark:from-rose-800 dark:via-rose-900 dark:to-slate-800 bg-clip-text text-transparent select-none">
                403
            </div>
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="w-24 h-24 sm:w-32 sm:h-32 rounded-3xl bg-white dark:bg-slate-900 border-2 border-rose-200 dark:border-rose-700 shadow-2xl shadow-rose-100 dark:shadow-none flex items-center justify-center">
                    <svg class="w-12 h-12 sm:w-16 sm:h-16 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                    </svg>
                </div>
            </div>
        </div>

        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white mb-3">
            Akses Ditolak
        </h1>
        <p class="text-slate-500 dark:text-slate-400 text-base leading-relaxed mb-10 max-w-md mx-auto">
            Anda tidak memiliki izin untuk mengakses halaman ini. Jika Anda merasa ini adalah kesalahan, hubungi administrator.
        </p>

        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ url('/') }}"
               class="inline-flex items-center justify-center gap-2 px-7 py-3.5 rounded-2xl bg-teal-600 text-white font-bold text-sm shadow-lg shadow-teal-600/20 hover:bg-teal-700 transition-all active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Kembali ke Beranda
            </a>
            <a href="javascript:history.back()"
               class="inline-flex items-center justify-center gap-2 px-7 py-3.5 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold text-sm hover:bg-slate-200 dark:hover:bg-slate-700 transition-all active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/></svg>
                Halaman Sebelumnya
            </a>
        </div>

    </div>
</div>
@endsection
