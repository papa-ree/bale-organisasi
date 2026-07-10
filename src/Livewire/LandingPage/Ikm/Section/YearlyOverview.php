<?php

namespace Bale\BaleOrganisasi\Livewire\LandingPage\Ikm\Section;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class YearlyOverview extends Component
{
    /** Pilihan apakah ingin menampilkan tahun berjalan (Current Year) atau tidak */
    public bool $includeCurrentYear = false;

    /** Maksimal jumlah tahun yang ditampilkan di chart */
    public int $maxYears = 5;

    public function render()
    {
        $currentYear = (int) date('Y');
        $maxYear = $this->includeCurrentYear ? $currentYear : $currentYear - 1;

        // Cache key unik berdasarkan parameter inklusi tahun berjalan
        $cacheKey = 'ikm_yearly_overview_' . $maxYear . '_' . ($this->includeCurrentYear ? 'inc' : 'exc');

        $years = Cache::remember($cacheKey, now()->addHour(), function () use ($maxYear) {
            // Ambil agregat per tahun, dibatasi maksimal 5 tahun ke belakang (only approved batches)
            $yearlyData = DB::table('ikm_records')
                ->join('ikm_batches', 'ikm_records.ikm_batch_id', '=', 'ikm_batches.id')
                ->where('ikm_batches.status', 'selesai')
                ->whereNotNull('ikm_batches.approved_at')
                ->whereNull('ikm_batches.deleted_at')
                ->whereNull('ikm_records.deleted_at')
                ->where('ikm_records.tahun', '<=', $maxYear)
                ->select(
                    'ikm_records.tahun',
                    DB::raw('AVG(ikm_records.nilai_ikm) as avg_ikm'),
                    DB::raw('SUM(ikm_records.sampel) as total_sampel'),
                    DB::raw('COUNT(DISTINCT CONCAT(ikm_records.tahun, "-", ikm_records.triwulan)) as total_periode')
                )
                ->groupBy('ikm_records.tahun')
                ->orderByDesc('ikm_records.tahun')
                ->limit($this->maxYears)
                ->get();

            // Untuk setiap tahun, ambil skor per-triwulan untuk grafik sparkline (only approved batches)
            return $yearlyData->map(function ($row) {
                $quarterly = DB::table('ikm_records')
                    ->join('ikm_batches', 'ikm_records.ikm_batch_id', '=', 'ikm_batches.id')
                    ->where('ikm_batches.status', 'selesai')
                    ->whereNotNull('ikm_batches.approved_at')
                    ->whereNull('ikm_batches.deleted_at')
                    ->whereNull('ikm_records.deleted_at')
                    ->where('ikm_records.tahun', $row->tahun)
                    ->select('ikm_records.triwulan', DB::raw('AVG(ikm_records.nilai_ikm) as avg_ikm'))
                    ->groupBy('ikm_records.triwulan')
                    ->orderBy('ikm_records.triwulan')
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
            'maxYears' => $this->maxYears,
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
