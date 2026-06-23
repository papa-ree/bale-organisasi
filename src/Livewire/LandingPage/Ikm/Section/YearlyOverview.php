<?php

namespace Bale\BaleOrganisasi\Livewire\LandingPage\Ikm\Section;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class YearlyOverview extends Component
{
    /** Pilihan apakah ingin menampilkan tahun berkelan (Current Year) atau tidak */
    public bool $includeCurrentYear = false;

    public function render()
    {
        $currentYear = (int) date('Y');
        $maxYear = $this->includeCurrentYear ? $currentYear : $currentYear - 1;

        // Cache key unik berdasarkan parameter inklusi tahun berjalan
        $cacheKey = 'ikm_yearly_overview_' . $maxYear . '_' . ($this->includeCurrentYear ? 'inc' : 'exc');

        $years = Cache::remember($cacheKey, now()->addHour(), function () use ($maxYear) {
            // Ambil agregat per tahun, dibatasi maksimal 5 tahun ke belakang
            $yearlyData = DB::table('ikm_records')
                ->where('tahun', '<=', $maxYear)
                ->select(
                    'tahun',
                    DB::raw('AVG(nilai_ikm) as avg_ikm'),
                    DB::raw('SUM(sampel) as total_sampel'),
                    DB::raw('COUNT(DISTINCT CONCAT(tahun, "-", triwulan)) as total_periode')
                )
                ->groupBy('tahun')
                ->orderByDesc('tahun')
                ->limit(5)
                ->get();

            // Untuk setiap tahun, ambil skor per-triwulan untuk grafik sparkline
            return $yearlyData->map(function ($row) {
                $quarterly = DB::table('ikm_records')
                    ->where('tahun', $row->tahun)
                    ->select('triwulan', DB::raw('AVG(nilai_ikm) as avg_ikm'))
                    ->groupBy('triwulan')
                    ->orderBy('triwulan')
                    ->get()
                    ->map(fn($q) => [
                        'label' => 'TW' . $q->triwulan,
                        'skor' => round((float) $q->avg_ikm, 2),
                    ])
                    ->values()
                    ->toArray();

                $avg = round((float) $row->avg_ikm, 2);

                return [
                    'tahun' => $row->tahun,
                    'avg_ikm' => $avg,
                    'total_sampel' => (int) $row->total_sampel,
                    'total_periode' => (int) $row->total_periode,
                    'predikat' => $this->getPredikat($avg),
                    'quarterly' => $quarterly,
                ];
            })->toArray();
        });

        return view('bale-organisasi::livewire.landing-page.ikm.section.yearly-overview', [
            'years' => $years,
        ]);
    }

    /** Determine predikat label and badge class based on IKM score */
    private function getPredikat(float $skor): array
    {
        if ($skor >= 88.31)
            return ['label' => 'Sangat Baik', 'cls' => 'badge-sb', 'color' => 'teal'];
        if ($skor >= 76.61)
            return ['label' => 'Baik', 'cls' => 'badge-b', 'color' => 'blue'];
        if ($skor >= 65.00)
            return ['label' => 'Cukup', 'cls' => 'badge-c', 'color' => 'amber'];
        return ['label' => 'Tidak Baik', 'cls' => 'badge-kb', 'color' => 'rose'];
    }
}
