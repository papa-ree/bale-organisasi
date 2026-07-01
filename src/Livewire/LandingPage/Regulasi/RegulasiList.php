<?php
// RegulasiList.php (Controller)
namespace Bale\BaleOrganisasi\Livewire\LandingPage\Regulasi;

use Bale\Umpak\Livewire\UmpakComponent;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

class RegulasiList extends UmpakComponent
{
    use WithPagination;

    #[Layout('bale-organisasi::layouts.app')]
    #[Title('Dokumen & Regulasi - Bale Organisasi')]

    #[Url(as: 'q')] public string $search = '';
    #[Url(as: 'kategori')] public string $category = 'all';
    #[Url(as: 'format')] public string $format = 'all';
    #[Url(as: 'tahun')] public string $year = 'all';
    #[Url(as: 'urut')] public string $sort = 'terbaru';

    // public function updated($property)
    // {
    //     if (in_array($property, ['search', 'category', 'format', 'year']))
    //         $this->resetPage();
    // }

    // public function resetFilters()
    // {
    //     $this->reset(['search', 'category', 'format', 'year', 'sort']);
    //     $this->resetPage();
    // }

    // public function render()
    // {
    //     $items = collect($this->section('regulasi')?->items ?? []);
    //     $transformed = $this->transformItems($items);

    //     $filtered = $this->applyFilters($transformed);
    //     $sorted = $this->applySorting($filtered);

    //     return view('bale-organisasi::livewire.landing-page.regulasi.regulasi-list', [
    //         'documents' => $this->paginate($sorted),
    //         'stats' => $this->calculateStats($filtered),
    //         'categories' => $this->getCategoryTree($transformed),
    //         'formatCounts' => $transformed->groupBy('fmt')->map->count(),
    //         'popularDocs' => $transformed->take(5),
    //         'years' => $transformed->pluck('tahun')->unique()->sortDesc(),
    //     ]);
    // }

    protected function transformItems($items)
    {
        return $items->map(function ($item) {
            $upload = $item['uploads'][0] ?? null;
            $catMapping = [
                'Kelembagaan & Anjab' => 'kelembagaan',
                'Yanlik & Tata Laksana' => 'yanlik',
                'Kinerja & RB' => 'kinerja',
            ];

            $filename = $upload['original_name'] ?? '';
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION)) ?: 'file';

            return [
                'id' => $item['id'][0] ?? '',
                'title' => $item['judul'][0] ?? 'Tanpa Judul',
                'tahun' => (int) ($item['tahun'][0] ?? date('Y')),
                'cat' => ($item['kategori'][0] ?? '') ?: 'Umum',
                'cat_id' => $catMapping[($item['kategori'][0] ?? '') ?: 'Umum'] ?? 'lainnya',
                'desc' => $item['deskripsi'][0] ?? 'Tidak ada deskripsi tambahan.',
                'fmt' => $ext,
                'size' => $this->formatBytes($upload['size'] ?? 0),
                'uploaded' => $item['updated_at'][0] ?? now(),
                'url' => $upload['url'] ?? '#',
            ];
        });
    }

    protected function calculateStats($items)
    {
        return [
            'total' => $items->count(),
            'new' => $items->filter(fn($d) => (now()->diffInDays(\Carbon\Carbon::parse($d['uploaded']))) <= 60)->count(),
        ];
    }

    private function formatBytes($bytes, $precision = 1)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    // protected function paginate($items, $perPage = 10)
    // {
    //     $page = Paginator::resolveCurrentPage() ?: 1;
    //     return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, ['path' => Paginator::resolveCurrentPath()]);
    // }

    private function getCategoryTree($items)
    {
        $tree = [
            ['id' => 'kelembagaan', 'label' => 'Kelembagaan & Anjab', 'icon' => '🏛️'],
            ['id' => 'yanlik', 'label' => 'Yanlik & Tata Laksana', 'icon' => '📋'],
            ['id' => 'kinerja', 'label' => 'Kinerja & RB', 'icon' => '📊'],
        ];

        foreach ($tree as &$cat) {
            $cat['count'] = $items->where('cat_id', $cat['id'])->count();
        }
        return $tree;
    }

    public function render()
    {
        $items = collect($this->section('regulasi')?->items ?? []);
        $transformed = $this->transformItems($items);

        return view('bale-organisasi::livewire.landing-page.regulasi.regulasi-list', [
            'allDocuments' => $transformed, // Kirim semua data tanpa dipaginasi di server
            'stats' => $this->calculateStats($transformed),
            'categories' => $this->getCategoryTree($transformed),
            'years' => $transformed->pluck('tahun')->unique()->sortDesc(),
        ]);
    }

}
