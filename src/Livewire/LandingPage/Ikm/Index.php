<?php

namespace Bale\BaleOrganisasi\Livewire\LandingPage\Ikm;

use Bale\Umpak\Livewire\UmpakComponent;
use Illuminate\Support\Facades\Cache;
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

        $custom = $section->meta('custom', []);
        $tagline = $custom['tagline'] ?? '';

        $cacheKey = 'bale_org:ikm_overview_data';

        $data = Cache::rememberForever($cacheKey, function () {
            // Ambil data batch terbaru yang disetujui (status selesai & approved_at tidak null)
            $latestApproved = DB::table('ikm_batches')
                ->where('status', 'selesai')
                ->whereNotNull('approved_at')
                ->orderByDesc('tahun')
                ->orderByDesc('triwulan')
                ->first();

            $tahun = $latestApproved ? $latestApproved->tahun : now()->year;
            $triwulan = $latestApproved ? $latestApproved->triwulan : 1;

            // 1. Ambil data Nilai IKM Keseluruhan & Total Responden (only approved batches)
            $summary = DB::table('ikm_records')
                ->join('ikm_batches', 'ikm_records.ikm_batch_id', '=', 'ikm_batches.id')
                ->where('ikm_batches.status', 'selesai')
                ->whereNotNull('ikm_batches.approved_at')
                ->where('ikm_records.tahun', $tahun)
                ->where('ikm_records.triwulan', $triwulan)
                ->selectRaw('AVG(ikm_records.nilai_ikm) as avg_ikm, SUM(ikm_records.sampel) as total_responden')
                ->first();

            // 2. Ambil data Skor per Unit Kerja (OPD)
            $allScores = DB::table('ikm_records')
                ->join('ikm_batches', 'ikm_records.ikm_batch_id', '=', 'ikm_batches.id')
                ->where('ikm_batches.status', 'selesai')
                ->whereNotNull('ikm_batches.approved_at')
                ->where('ikm_records.tahun', $tahun)
                ->where('ikm_records.triwulan', $triwulan)
                ->select('ikm_records.nama_opd', 'ikm_records.nilai_ikm')
                ->get()
                ->map(function ($item) {
                    return [
                        'name' => $item->nama_opd,
                        'score' => (float) $item->nilai_ikm,
                        'color' => $item->nilai_ikm >= 80 ? 'teal' : 'amber'
                    ];
                })
                ->toArray();

            // 3. Hitung Rata-rata per Unsur (nrr_u1 - nrr_u9)
            $rawUnsur = DB::table('ikm_records')
                ->join('ikm_batches', 'ikm_records.ikm_batch_id', '=', 'ikm_batches.id')
                ->where('ikm_batches.status', 'selesai')
                ->whereNotNull('ikm_batches.approved_at')
                ->where('ikm_records.tahun', $tahun)
                ->where('ikm_records.triwulan', $triwulan)
                ->selectRaw('
                    AVG(ikm_records.nrr_u1) as u1, AVG(ikm_records.nrr_u2) as u2, AVG(ikm_records.nrr_u3) as u3,
                    AVG(ikm_records.nrr_u4) as u4, AVG(ikm_records.nrr_u5) as u5, AVG(ikm_records.nrr_u6) as u6,
                    AVG(ikm_records.nrr_u7) as u7, AVG(ikm_records.nrr_u8) as u8, AVG(ikm_records.nrr_u9) as u9
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

            return [
                'tahun' => $tahun,
                'triwulan' => $triwulan,
                'period' => "Triwulan {$triwulan} {$tahun}",
                'avgScore' => number_format($summary->avg_ikm ?? 0, 1),
                'totalResponden' => number_format($summary->total_responden ?? 0, 0, ',', '.'),
                'allScores' => $allScores,
                'unsurList' => $unsurList,
            ];
        });

        return view('bale-organisasi::livewire.landing-page.ikm.index', [
            'section' => $section,
            'avgScore' => $data['avgScore'],
            'totalResponden' => $data['totalResponden'],
            'period' => $data['period'],
            'tahun' => $data['tahun'],
            'triwulan' => $data['triwulan'],
            'allScoresJson' => json_encode($data['allScores']),
            'unsurList' => $data['unsurList'],
            'tagline' => $tagline
        ]);
    }
}
