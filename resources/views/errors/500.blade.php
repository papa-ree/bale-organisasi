@extends('bale-organisasi::layouts.error')

<x-slot:title>Kesalahan Server</x-slot:title>

@section('content')
<div class="min-h-[80vh] flex items-center justify-center px-4 py-16">
    <div class="max-w-2xl w-full text-center">

        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-500 dark:text-slate-400 text-xs font-bold uppercase tracking-widest mb-8">
            <span class="w-1.5 h-1.5 rounded-full bg-slate-400 animate-pulse"></span>
            Kesalahan Internal Server
        </div>

        <div class="relative inline-flex items-center justify-center mb-8">
            <div class="text-[10rem] sm:text-[14rem] font-black leading-none bg-linear-to-br from-slate-200 via-slate-300 to-gray-300 dark:from-slate-600 dark:via-slate-700 dark:to-slate-800 bg-clip-text text-transparent select-none">
                500
            </div>
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="w-24 h-24 sm:w-32 sm:h-32 rounded-3xl bg-white dark:bg-slate-900 border-2 border-slate-200 dark:border-slate-700 shadow-2xl shadow-slate-100 dark:shadow-none flex items-center justify-center">
                    <svg class="w-12 h-12 sm:w-16 sm:h-16 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
            </div>
        </div>

        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white mb-3">
            Terjadi Kesalahan pada Server
        </h1>
        <p class="text-slate-500 dark:text-slate-400 text-base leading-relaxed mb-6 max-w-md mx-auto">
            Sistem kami mengalami gangguan sementara. Tim teknis kami sedang bekerja untuk menyelesaikannya.
        </p>

        {{-- Info box --}}
        <div class="inline-flex items-start gap-3 px-5 py-4 rounded-2xl bg-teal-50 dark:bg-teal-900/20 border border-teal-100 dark:border-teal-800 text-left mb-10 max-w-sm mx-auto">
            <svg class="w-5 h-5 text-teal-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p class="text-sm text-teal-700 dark:text-teal-300 font-medium">
                Jika masalah terus berlanjut, hubungi administrator melalui kontak resmi kami.
            </p>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="javascript:location.reload()"
               class="inline-flex items-center justify-center gap-2 px-7 py-3.5 rounded-2xl bg-teal-600 text-white font-bold text-sm shadow-lg shadow-teal-600/20 hover:bg-teal-700 transition-all active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                Muat Ulang Halaman
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