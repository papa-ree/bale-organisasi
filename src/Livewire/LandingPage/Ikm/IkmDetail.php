<?php

namespace Bale\BaleOrganisasi\Livewire\LandingPage\Ikm;

use Bale\Umpak\Livewire\UmpakComponent;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('bale-organisasi::layouts.app')]
#[Title('Indeks Kepuasan Masyarakat - Bale Organisasi')]
class IkmDetail extends UmpakComponent
{
    public $instansiId = 0;
    public string $activePeriod = '';

    public function mount($id = 0): void
    {
        $this->instansiId = $id;

        // Default ke triwulan saat ini
        if (!$this->activePeriod) {
            $year = date('Y');
            $triwulan = (int) ceil(date('n') / 3);
            $this->activePeriod = "{$year}-{$triwulan}";
        }
    }

    public function setPeriod(string $period): void
    {
        $this->activePeriod = $period;
    }

    public function render()
    {
        [$tahun, $triwulan] = $this->parsePeriod($this->activePeriod);

        // 1. Data Instansi Utama
        $instansi = $this->getInstansi();
        if (!$instansi) {
            return view('bale-organisasi::livewire.landing-page.ikm.ikm-detail', [
                'notFound' => true,
                'chartJson' => '[]',
                'activePeriod' => $this->activePeriod,
            ]);
        }

        // 2. Data Histori & Periode
        $histori = $this->getHistori($instansi->nama_opd);
        $periodeList = $histori->map(fn($r) => "{$r->tahun}-{$r->triwulan}")->values()->toArray();
        $periodeAktif = $histori->where('tahun', $tahun)->where('triwulan', $triwulan)->first() ?? $histori->last();

        // 3. Statistik Kabupaten & Peringkat (Cached)
        $kabStats = $this->getKabupatenStats($tahun, $triwulan);
        $rank = array_search($instansi->nama_opd, $kabStats['rankList']) + 1;

        // Pre-load kabStats untuk SEMUA periode instansi ini (Alpine lookup instan)
        $kabStatsByPeriod = $this->getKabStatsByPeriod($periodeList, $instansi->nama_opd);

        // 4. Transformasi Data untuk UI
        $skorAktif = $periodeAktif ? (float) $periodeAktif->nilai_ikm : 0;
        $tren = $this->calculateTrend($histori, $tahun, $triwulan, $skorAktif);
        $chartData = $this->getChartData($histori);

        return view('bale-organisasi::livewire.landing-page.ikm.ikm-detail', [
            'notFound' => false,
            'instansi' => $instansi,
            'periodeAktif' => $periodeAktif,
            'periodeList' => $periodeList,
            'histori' => $histori,
            'chartJson' => json_encode($chartData, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT),
            'kabStatsJson' => json_encode($kabStatsByPeriod, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT),
            'activePeriod' => "{$tahun}-{$triwulan}",
            'skorAktif' => $skorAktif,
            'predikat' => $this->getPredikat($skorAktif),
            'tren' => $tren,
            'unsur' => $this->getUnsurBreakdown($periodeAktif),
            'kabStats' => $kabStats,
            'rank' => $rank,
            'sampel' => $periodeAktif ? (int) ($periodeAktif->sampel ?? 0) : 0,
        ]);
    }

    // ── Logic Extraction ───────────────────────────────────────────────────

    private function getInstansi()
    {
        return DB::table('ikm_records')->where('id', $this->instansiId)->first();
    }

    private function getHistori(string $namaOpd)
    {
        return DB::table('ikm_records')
            ->where('nama_opd', $namaOpd)
            ->orderBy('tahun')
            ->orderBy('triwulan')
            ->get();
    }

    private function getKabupatenStats(int $tahun, int $triwulan): array
    {
        $cacheKey = "bale_org:ikm_stats:{$tahun}_{$triwulan}";

        return Cache::remember($cacheKey, now()->addHours(24), function () use ($tahun, $triwulan) {
            $allRecords = DB::table('ikm_records')
                ->where('tahun', $tahun)
                ->where('triwulan', $triwulan)
                ->select('nama_opd', 'nilai_ikm')
                ->get();

            return [
                'avg' => round($allRecords->avg('nilai_ikm') ?? 0, 2),
                'total' => $allRecords->count(),
                'rankList' => $allRecords->sortByDesc('nilai_ikm')->values()->pluck('nama_opd')->toArray(),
            ];
        });
    }

    /**
     * Pre-load kabStats untuk semua periode agar Alpine bisa lookup instan
     * tanpa menunggu server round-trip saat ganti periode.
     */
    private function getKabStatsByPeriod(array $periodeList, string $namaOpd): array
    {
        $result = [];
        foreach ($periodeList as $pKey) {
            [$py, $pt] = explode('-', $pKey);
            $stats = $this->getKabupatenStats((int) $py, (int) $pt);
            $rank = array_search($namaOpd, $stats['rankList']);
            $result[$pKey] = [
                'avg' => $stats['avg'],
                'total' => $stats['total'],
                'rank' => $rank !== false ? $rank + 1 : 0,
            ];
        }
        return $result;
    }

    private function calculateTrend($histori, $tahun, $triwulan, $currentSkor): ?float
    {
        $periodeList = $histori->map(fn($r) => "{$r->tahun}-{$r->triwulan}")->values()->toArray();
        $currentIdx = array_search("{$tahun}-{$triwulan}", $periodeList);
        $prevRecord = ($currentIdx > 0) ? $histori->get($currentIdx - 1) : null;

        return $prevRecord ? round($currentSkor - (float) $prevRecord->nilai_ikm, 2) : null;
    }

    private function getUnsurBreakdown($record): array
    {
        if (!$record) return [];

        $labels = $this->getUnsurLabels();
        $breakdown = [];

        foreach (range(1, 9) as $i) {
            $col = "nrr_u{$i}";
            $breakdown[] = [
                'label' => $labels[$i - 1],
                'nilai' => (float) ($record->$col ?? 0)
            ];
        }

        return $breakdown;
    }

    private function getChartData($histori): array
    {
        $labels = $this->getUnsurLabels();

        return $histori->map(function ($record) use ($labels) {
            $unsurDetail = [];
            foreach (range(1, 9) as $i) {
                $col = "nrr_u{$i}";
                $unsurDetail[] = [
                    'label' => $labels[$i - 1],
                    'nilai' => round((float) ($record->$col ?? 0), 2),
                ];
            }
            return [
                'period' => "TW{$record->triwulan} {$record->tahun}",
                'key' => "{$record->tahun}-{$record->triwulan}",
                'skor' => (float) $record->nilai_ikm,
                'sampel' => (int) ($record->sampel ?? 0),
                'unsur' => $unsurDetail,
            ];
        })->values()->toArray();
    }

    private function getUnsurLabels(): array
    {
        return [
            'Persyaratan', 'Sistem/Mekanisme', 'Waktu Penyelesaian',
            'Biaya/Tarif', 'Produk Layanan', 'Kompetensi Pelaksana',
            'Perilaku Pelaksana', 'Sarana Prasarana', 'Penanganan Pengaduan',
        ];
    }

    // ── Static Helpers ────────────────────────────────────────────────────

    private function parsePeriod(string $period): array
    {
        $parts = explode('-', $period);
        return [(int) ($parts[0] ?? date('Y')), (int) ($parts[1] ?? ceil(date('n') / 3))];
    }

    public function getPredikat(float $skor): array
    {
        if ($skor >= 88.31) return ['label' => 'Sangat Baik', 'class' => 'badge-sb'];
        if ($skor >= 76.61) return ['label' => 'Baik', 'class' => 'badge-b'];
        if ($skor >= 65.00) return ['label' => 'Cukup', 'class' => 'badge-c'];
        return ['label' => 'Tidak Baik', 'class' => 'badge-kb'];
    }
}
