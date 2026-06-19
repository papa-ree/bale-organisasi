<?php

namespace Bale\BaleOrganisasi\Livewire\LandingPage\Ikm\Section;

use Bale\Umpak\Livewire\UmpakComponent;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Url;

class IkmListContent extends UmpakComponent
{
    #[Url(history: true)]
    public $search = '';

    #[Url(history: true)]
    public $period = 'all';

    #[Url(history: true)]
    public $predikat = 'all';

    #[Url(history: true)]
    public $kategori = 'all';

    public array $itemsList = [];

    public function updatedSearch()
    {
        // Purify input: remove special characters but keep spaces and alphanumeric
        $this->search = preg_replace('/[^a-zA-Z0-9\s]/', '', $this->search);
    }

    public function mount()
    {
        if (!$this->period || $this->period === 'all') {
            $year = date('Y');
            $month = date('n');
            $triwulan = ceil($month / 3);
            $this->period = "{$year}-{$triwulan}";
        }
    }

    public function render()
    {
        // 1. Get List Data with filters
        $query = DB::table('ikm_records');

        if ($this->search) {
            $query->where('nama_opd', 'like', '%' . $this->search . '%');
        }

        if ($this->period !== 'all') {
            [$tahun, $triwulan] = explode('-', $this->period);
            $query->where('tahun', $tahun)->where('triwulan', $triwulan);
        }

        // Logic for Predikat filtering
        if ($this->predikat !== 'all') {
            if ($this->predikat === 'Sangat Baik') {
                $query->where('nilai_ikm', '>=', 88.31);
            } elseif ($this->predikat === 'Baik') {
                $query->whereBetween('nilai_ikm', [76.61, 88.30]);
            } elseif ($this->predikat === 'Cukup') {
                $query->whereBetween('nilai_ikm', [65.00, 76.60]);
            }
        }

        // Fetch and Map with derived categories
        $records = $query->get();
        
        $this->itemsList = $records->map(function($item) {
            $nilai = (float) $item->nilai_ikm;
            $predikat = 'Sangat Baik';
            if ($nilai < 65.00) $predikat = 'Tidak Baik';
            elseif ($nilai < 76.61) $predikat = 'Cukup';
            elseif ($nilai < 88.31) $predikat = 'Baik';

            return [
                'nama' => $item->nama_opd,
                'kategori' => $this->determineCategory($item->nama_opd),
                'sampel' => (int) $item->sampel,
                'skor' => $nilai,
                'predikat' => $predikat,
                'id' => $item->id ?? 0
            ];
        })
        ->filter(function($item) {
            if ($this->kategori === 'all') return true;
            return $item['kategori'] === $this->kategori;
        })
        ->values()
        ->toArray();

        // 2. Get unique categories from ALL records in this period (so chips stay consistent)
        $categories = $records->map(fn($i) => $this->determineCategory($i->nama_opd))
            ->unique()
            ->sort()
            ->values();

        // 3. Get available periods for filter
        $periods = DB::table('ikm_records')
            ->select('tahun', 'triwulan')
            ->distinct()
            ->orderByDesc('tahun')
            ->orderByDesc('triwulan')
            ->get();

        return view('bale-organisasi::livewire.landing-page.ikm.section.ikm-list-content', [
            'categories' => $categories,
            'periods' => $periods
        ]);
    }

    /**
     * Determine category based on OPD Name rules.
     * Single Responsibility: Handling categorization logic.
     */
    private function determineCategory(?string $nama): string
    {
        if (!$nama) return 'Lainnya';
        
        $namaLower = strtolower(trim($nama));

        // Rule 1: Satpol PP -> Dinas
        if (str_contains($namaLower, 'satuan polisi pamong praja')) {
            return 'Dinas';
        }

        // Rule 2: Yankes (Prefix keywords)
        $yankesKeywords = ['instalasi', 'laboratorium', 'puskesmas', 'rumah sakit'];
        foreach ($yankesKeywords as $keyword) {
            if (str_starts_with($namaLower, $keyword)) {
                return 'Yankes';
            }
        }

        // Default Rule: First word
        $firstWord = explode(' ', trim($nama))[0];
        return ucwords(strtolower($firstWord));
    }

    public function resetFilters()
    {
        $this->reset(['search', 'period', 'predikat', 'kategori']);
        $this->dispatch('filters-reset');
    }
}
