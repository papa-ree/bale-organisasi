<?php

namespace Bale\BaleOrganisasi\Livewire\LandingPage\Ikm;

use Bale\Umpak\Livewire\UmpakComponent;
use Illuminate\Support\Facades\DB;

class Index extends UmpakComponent
{

    public function placeholder()
    {
        return <<<'HTML'
        <section id="ikm-placeholder" class="py-20 bg-slate-50 dark:bg-slate-800/50 transition-colors duration-300">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="h-8 w-48 bg-slate-200 dark:bg-slate-700 rounded-lg mx-auto mb-12 animate-pulse"></div>
                <div class="grid lg:grid-cols-3 gap-8">
                    <div class="lg:col-span-1 h-64 bg-white dark:bg-slate-800 rounded-3xl animate-pulse"></div>
                    <div class="lg:col-span-2 h-64 bg-white dark:bg-slate-800 rounded-3xl animate-pulse"></div>
                </div>
            </div>
        </section>
        HTML;
    }

    public function render()
    {
        $section = $this->section('ikm');

        if (!$section) {
            return view('bale-organisasi::livewire.landing-page.ikm.index', ['section' => null]);
        }

        // Ambil parameter dari meta custom
        $custom = $section->meta('custom', []);
        $tahun = $custom['tahun'] ?? now()->year;
        $triwulan = $custom['triwulan'] ?? 1;
        $tagline = $custom['tagline'] ?? '';

        // 1. Ambil data Nilai IKM Keseluruhan & Total Responden
        $summary = DB::table('ikm_records')
            ->where('tahun', $tahun)
            ->where('triwulan', $triwulan)
            ->selectRaw('AVG(nilai_ikm) as avg_ikm, SUM(sampel) as total_responden')
            ->first();

        // 2. Ambil data Skor per Unit Kerja (OPD)
        // $scoresPerOpd = DB::table('ikm_records')
        //     ->where('tahun', $tahun)
        //     ->where('triwulan', $triwulan)
        //     ->select('nama_opd', 'nilai_ikm', 'kategori')
        //     ->inRandomOrder() // Mengambil secara acak
        //     ->limit(5)        // Maksimal 5 data
        //     ->get();

        $allScores = DB::table('ikm_records')
            ->where('tahun', $tahun)
            ->where('triwulan', $triwulan)
            ->select('nama_opd', 'nilai_ikm')
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->nama_opd,
                    'score' => (float) $item->nilai_ikm,
                    'color' => $item->nilai_ikm >= 80 ? 'teal' : 'amber'
                ];
            });

        // 3. Hitung Rata-rata per Unsur (nrr_u1 - nrr_u9)
        $rawUnsur = DB::table('ikm_records')
            ->where('tahun', $tahun)
            ->where('triwulan', $triwulan)
            ->selectRaw('
                AVG(nrr_u1) as u1, AVG(nrr_u2) as u2, AVG(nrr_u3) as u3,
                AVG(nrr_u4) as u4, AVG(nrr_u5) as u5, AVG(nrr_u6) as u6,
                AVG(nrr_u7) as u7, AVG(nrr_u8) as u8, AVG(nrr_u9) as u9
            ')->first();

        $unsurList = [
            ['label' => 'Persyaratan', 'nilai' => $rawUnsur->u1 ?? 0],
            ['label' => 'Sistem/Mekanisme', 'nilai' => $rawUnsur->u2 ?? 0],
            ['label' => 'Waktu Penyelesaian', 'nilai' => $rawUnsur->u3 ?? 0],
            ['label' => 'Biaya/Tarif', 'nilai' => $rawUnsur->u4 ?? 0],
            ['label' => 'Produk Layanan', 'nilai' => $rawUnsur->u5 ?? 0],
            ['label' => 'Kompetensi Pelaksana', 'nilai' => $rawUnsur->u6 ?? 0],
            ['label' => 'Perilaku Pelaksana', 'nilai' => $rawUnsur->u7 ?? 0],
            ['label' => 'Sarana Prasarana', 'nilai' => $rawUnsur->u8 ?? 0],
            ['label' => 'Penanganan Pengaduan', 'nilai' => $rawUnsur->u9 ?? 0],
        ];

        return view('bale-organisasi::livewire.landing-page.ikm.index', [
            'section' => $section,
            'avgScore' => number_format($summary->avg_ikm ?? 0, 1),
            'totalResponden' => number_format($summary->total_responden ?? 0, 0, ',', '.'),
            'period' => "Triwulan {$triwulan} {$tahun}",
            'tahun' => $tahun,
            'triwulan' => $triwulan,
            'allScoresJson' => $allScores->toJson(),
            // 'scores' => $scoresPerOpd,
            'unsurList' => $unsurList,
            'tagline' => $tagline
        ]);
    }
}
