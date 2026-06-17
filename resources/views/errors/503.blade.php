@extends('bale-organisasi::layouts.error')

<x-slot:title>Layanan Tidak Tersedia</x-slot:title>

@section('content')
<div class="min-h-[80vh] flex items-center justify-center px-4 py-16">
    <div class="max-w-2xl w-full text-center">

        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-sky-50 dark:bg-sky-900/30 border border-sky-200 dark:border-sky-700 text-sky-600 dark:text-sky-400 text-xs font-bold uppercase tracking-widest mb-8">
            <span class="w-1.5 h-1.5 rounded-full bg-sky-500 animate-pulse"></span>
            Sedang dalam Pemeliharaan
        </div>

        <div class="relative inline-flex items-center justify-center mb-8">
            <div class="text-[10rem] sm:text-[14rem] font-black leading-none bg-linear-to-br from-sky-200 via-sky-300 to-teal-300 dark:from-sky-800 dark:via-sky-900 dark:to-slate-800 bg-clip-text text-transparent select-none">
                503
            </div>
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="w-24 h-24 sm:w-32 sm:h-32 rounded-3xl bg-white dark:bg-slate-900 border-2 border-sky-200 dark:border-sky-700 shadow-2xl shadow-sky-100 dark:shadow-none flex items-center justify-center">
                    <svg class="w-12 h-12 sm:w-16 sm:h-16 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white mb-3">
            Layanan Sedang Diperbaiki
        </h1>
        <p class="text-slate-500 dark:text-slate-400 text-base leading-relaxed mb-6 max-w-md mx-auto">
            Portal Bagian Organisasi Setda Kab. Ponorogo sedang dalam proses pemeliharaan dan peningkatan layanan. Kami akan segera kembali.
        </p>

        {{-- Maintenance card --}}
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 mb-10 max-w-sm mx-auto shadow-sm">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-2xl bg-sky-50 dark:bg-sky-900/30 flex items-center justify-center">
                    <svg class="w-5 h-5 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="text-left">
                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Status</p>
                    <p class="text-sm font-bold text-sky-600 dark:text-sky-400">Pemeliharaan Terjadwal</p>
                </div>
            </div>
            <p class="text-xs text-slate-500 text-left leading-relaxed">
                Tim teknis kami sedang bekerja keras untuk meningkatkan performa layanan. Terima kasih atas kesabaran Anda.
            </p>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="javascript:location.reload()"
               class="inline-flex items-center justify-center gap-2 px-7 py-3.5 rounded-2xl bg-teal-600 text-white font-bold text-sm shadow-lg shadow-teal-600/20 hover:bg-teal-700 transition-all active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                Cek Lagi
            </a>
        </div>

    </div>
</div>
@endsection