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
    #[Title('Dokumen & Regulasi - Bagian Organisasi Setda Kab. Ponorogo')]

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

    protected function getTabs()
    {
        $section = $this->section('regulasi');
        $tabs = ['Kelembagaan & Anjab', 'Yanlik & Tata Laksana', 'Kinerja & RB'];
        if ($section?->meta('tabs')) {
            $tabsMeta = $section->meta('tabs');
            if (is_array($tabsMeta)) {
                $tabs = $tabsMeta;
            } elseif (is_string($tabsMeta)) {
                $tabs = array_map('trim', explode(',', $tabsMeta));
            }
        }
        return $tabs;
    }

    protected function transformItems($items)
    {
        $tabs = $this->getTabs();
        $catMapping = [];
        foreach ($tabs as $tabName) {
            $catMapping[$tabName] = \Illuminate\Support\Str::slug($tabName);
        }

        return $items->map(function ($item) use ($catMapping) {
            // 1. Extract ID safely
            $id = $item['id'] ?? '';
            if (is_array($id)) {
                $id = $id[0] ?? '';
            }

            // 2. Extract Title safely
            $title = $item['judul'] ?? 'Tanpa Judul';
            if (is_array($title)) {
                $title = $title[0] ?? 'Tanpa Judul';
            }

            // 3. Extract Tahun safely
            $tahunVal = $item['tahun'] ?? '';
            if (is_array($tahunVal)) {
                $tahunVal = $tahunVal[0] ?? '';
            }
            $tahun = (int) ($tahunVal ?: date('Y'));

            // 4. Extract Kategori safely
            $kategoriVal = $item['kategori'] ?? '';
            if (is_array($kategoriVal)) {
                $kategoriVal = $kategoriVal[0] ?? '';
            }
            $cat = ($kategoriVal ?: 'Umum');

            // Find matching category ID (case-insensitive & trimmed)
            $catId = 'lainnya';
            foreach ($catMapping as $key => $slug) {
                if (strcasecmp(trim($cat), trim($key)) === 0) {
                    $catId = $slug;
                    break;
                }
            }

            // If it didn't match and category is 'Umum', try to map to 'umum' if 'Umum' tab is present,
            // or default to slugified version of category
            if ($catId === 'lainnya') {
                $catId = \Illuminate\Support\Str::slug($cat);
            }

            // 5. Extract Deskripsi safely
            $desc = $item['deskripsi'] ?? 'Tidak ada deskripsi tambahan.';
            if (is_array($desc)) {
                $desc = $desc[0] ?? 'Tidak ada deskripsi tambahan.';
            }

            // 6. Extract Date safely
            $uploaded = $item['updated_at'] ?? null;
            if (is_array($uploaded)) {
                $uploaded = $uploaded[0] ?? now();
            }
            if (empty($uploaded)) {
                $uploaded = now();
            }

            // 7. Extract URL, Format, and Size
            $itemUrl = $item['url'] ?? '#';
            $urlVal = is_array($itemUrl) ? ($itemUrl[0] ?? '#') : $itemUrl;

            $downloads = $item['uploads'] ?? null;
            $uploadUrl = '#';
            $uploadSize = 0;
            $fileType = 'file-text';
            $originalName = '';

            if (is_array($downloads)) {
                if (isset($downloads['url'])) {
                    $uploadUrl = $downloads['url'];
                    $uploadSize = $downloads['size'] ?? 0;
                    $fileType = $downloads['file_type'] ?? 'file-text';
                    $originalName = $downloads['original_name'] ?? $downloads['name'] ?? '';
                } else {
                    $firstUpload = $downloads[0] ?? null;
                    if (is_array($firstUpload)) {
                        if (isset($firstUpload['url'])) {
                            $uploadUrl = $firstUpload['url'];
                        }
                        if (isset($firstUpload['size'])) {
                            $uploadSize = $firstUpload['size'];
                        }
                        if (isset($firstUpload['file_type'])) {
                            $fileType = $firstUpload['file_type'];
                        }
                        $originalName = $firstUpload['original_name'] ?? $firstUpload['name'] ?? '';
                    } elseif (is_string($firstUpload)) {
                        $uploadUrl = $firstUpload;
                    }
                }
            } elseif (is_string($downloads)) {
                $uploadUrl = $downloads;
            }

            // Prioritize uploads URL if set, otherwise fallback to URL
            if (!empty($uploadUrl) && $uploadUrl !== '#') {
                $downloadUrl = $uploadUrl;
            } elseif (!empty($urlVal) && $urlVal !== '#') {
                $downloadUrl = $urlVal;
            } else {
                $downloadUrl = '#';
            }

            // Determine format/extension
            $ext = '';
            if (!empty($fileType) && $fileType !== 'file-text') {
                $ext = strtolower($fileType);
            }

            if (empty($ext) || $ext === 'file') {
                $checkPath = !empty($originalName) ? $originalName : $downloadUrl;
                if (!empty($checkPath) && $checkPath !== '#') {
                    $path = parse_url($checkPath, PHP_URL_PATH) ?? '';
                    $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                }
            }

            if (empty($ext)) {
                $ext = 'file';
            }

            // Map spreadsheet, presentation, document names to fit the Alpine filters
            if ($ext === 'spreadsheet') {
                $ext = 'xlsx';
            } elseif ($ext === 'presentation') {
                $ext = 'pptx';
            } elseif ($ext === 'document') {
                $ext = 'docx';
            }

            $sizeStr = $uploadSize > 0 ? $this->formatBytes($uploadSize) : '-';

            return [
                'id' => $id,
                'title' => $title,
                'tahun' => $tahun,
                'cat' => $cat,
                'cat_id' => $catId,
                'desc' => $desc,
                'fmt' => $ext,
                'size' => $sizeStr,
                'uploaded' => $uploaded,
                'url' => $downloadUrl,
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

    private function getCategoryTree($items)
    {
        $tabs = $this->getTabs();
        $tree = [];

        foreach ($tabs as $tab) {
            $slug = \Illuminate\Support\Str::slug($tab);
            $lower = strtolower($tab);
            $icon = '📁';
            if (str_contains($lower, 'kelembagaan') || str_contains($lower, 'anjab')) {
                $icon = '🏛️';
            } elseif (str_contains($lower, 'yanlik') || str_contains($lower, 'tata laksana')) {
                $icon = '📋';
            } elseif (str_contains($lower, 'kinerja') || str_contains($lower, 'rb')) {
                $icon = '📊';
            } elseif (str_contains($lower, 'umum')) {
                $icon = '📂';
            }

            $tree[] = [
                'id' => $slug,
                'label' => $tab,
                'icon' => $icon,
            ];
        }

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
