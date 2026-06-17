@extends('bale-organisasi::layouts.error')

<x-slot:title>Tidak Terautentikasi</x-slot:title>

@section('content')
<div class="min-h-[80vh] flex items-center justify-center px-4 py-16">
    <div class="max-w-2xl w-full text-center">

        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-amber-50 dark:bg-amber-900/30 border border-amber-200 dark:border-amber-700 text-amber-600 dark:text-amber-400 text-xs font-bold uppercase tracking-widest mb-8">
            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
            Autentikasi Diperlukan
        </div>

        <div class="relative inline-flex items-center justify-center mb-8">
            <div class="text-[10rem] sm:text-[14rem] font-black leading-none bg-linear-to-br from-amber-200 via-amber-300 to-orange-300 dark:from-amber-800 dark:via-amber-900 dark:to-slate-800 bg-clip-text text-transparent select-none">
                401
            </div>
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="w-24 h-24 sm:w-32 sm:h-32 rounded-3xl bg-white dark:bg-slate-900 border-2 border-amber-200 dark:border-amber-700 shadow-2xl shadow-amber-100 dark:shadow-none flex items-center justify-center">
                    <svg class="w-12 h-12 sm:w-16 sm:h-16 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                    </svg>
                </div>
            </div>
        </div>

        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white mb-3">
            Autentikasi Diperlukan
        </h1>
        <p class="text-slate-500 dark:text-slate-400 text-base leading-relaxed mb-10 max-w-md mx-auto">
            Anda perlu masuk terlebih dahulu untuk mengakses halaman ini. Silakan login dengan akun Anda.
        </p>

        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('login') }}"
               class="inline-flex items-center justify-center gap-2 px-7 py-3.5 rounded-2xl bg-amber-500 text-white font-bold text-sm shadow-lg shadow-amber-500/20 hover:bg-amber-600 transition-all active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                Masuk / Login
            </a>
            <a href="{{ url('/') }}"
               class="inline-flex items-center justify-center gap-2 px-7 py-3.5 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold text-sm hover:bg-slate-200 dark:hover:bg-slate-700 transition-all active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Kembali ke Beranda
            </a>
        </div>

    </div>
</div>
@endsection