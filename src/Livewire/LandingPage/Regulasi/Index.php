<?php

namespace Bale\BaleOrganisasi\Livewire\LandingPage\Regulasi;

use Bale\Umpak\Livewire\UmpakComponent;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Index extends UmpakComponent
{
    public function render()
    {
        $section = $this->section('regulasi');

        if (!$section) {
            return view('bale-organisasi::livewire.landing-page.regulasi.index', [
                'section' => null,
            ]);
        }

        $cacheKey = 'bale_org:regulasi_overview_data';
        $cacheUpdatedKey = 'bale_org:regulasi_updated_at';

        $regulasi = DB::table('sections')->where('slug', 'regulasi')->first();
        $latestUpdate = $regulasi?->updated_at ?? now()->toDateTimeString();

        $cachedUpdate = Cache::get($cacheUpdatedKey);

        if ($cachedUpdate != $latestUpdate) {
            Cache::forget($cacheKey);
            Cache::put($cacheUpdatedKey, $latestUpdate);
        }

        $data = Cache::remember($cacheKey, now()->addHours(24), function () use ($section) {
            $tabsRaw = $section->meta('custom.tabs', []) ?: [];
            if (is_string($tabsRaw)) {
                $tabs = array_map('trim', explode(',', $tabsRaw));
            } elseif (is_array($tabsRaw)) {
                $tabs = array_map('trim', $tabsRaw);
            } else {
                $tabs = [];
            }

            $items = collect($section->items ?: []);

            $groupedItems = [];
            foreach ($tabs as $tab) {
                $filtered = $items->filter(function ($i) use ($tab) {
                    $kategori = $i['kategori'] ?? null;
                    if (is_array($kategori)) {
                        $itemCategory = $kategori[0] ?? '';
                    } else {
                        $itemCategory = $kategori ?? '';
                    }

                    if (empty($itemCategory)) {
                        $itemCategory = 'Umum';
                    }

                    return strcasecmp(trim($itemCategory), trim($tab)) === 0;
                })->take(4);

                $formattedItems = [];
                foreach ($filtered as $item) {
                    $judul = $item['judul'] ?? 'Dokumen Tanpa Judul';
                    if (is_array($judul)) {
                        $judul = $judul[0] ?? 'Dokumen Tanpa Judul';
                    }

                    $deskripsi = $item['deskripsi'] ?? '';
                    if (is_array($deskripsi)) {
                        $deskripsi = $deskripsi[0] ?? '';
                    }

                    $tahun = $item['tahun'] ?? '';
                    if (is_array($tahun)) {
                        $tahun = $tahun[0] ?? '';
                    }

                    $itemUrl = $item['url'] ?? '#';
                    $urlVal = is_array($itemUrl) ? ($itemUrl[0] ?? '#') : $itemUrl;

                    $downloads = $item['uploads'] ?? null;
                    $uploadUrl = '#';
                    $fileType = 'file-text';

                    if (is_array($downloads)) {
                        if (isset($downloads['url'])) {
                            $uploadUrl = $downloads['url'];
                        } else {
                            $firstUpload = $downloads[0] ?? null;
                            if (is_array($firstUpload)) {
                                if (isset($firstUpload['url'])) {
                                    $uploadUrl = $firstUpload['url'];
                                }
                                if (isset($firstUpload['file_type'])) {
                                    $fileType = strtolower($firstUpload['file_type']);
                                }
                            } elseif (is_string($firstUpload)) {
                                $uploadUrl = $firstUpload;
                            }
                        }
                    } elseif (is_string($downloads)) {
                        $uploadUrl = $downloads;
                    }

                    // Prioritize uploads if present, otherwise use URL
                    if (!empty($uploadUrl) && $uploadUrl !== '#') {
                        $downloadUrl = $uploadUrl;
                    } elseif (!empty($urlVal) && $urlVal !== '#') {
                        $downloadUrl = $urlVal;
                    } else {
                        $downloadUrl = '#';
                    }

                    // Guess file extension if fileType is default
                    if ($fileType === 'file-text' && !empty($downloadUrl) && $downloadUrl !== '#') {
                        $path = parse_url($downloadUrl, PHP_URL_PATH) ?? '';
                        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                        if (in_array($ext, ['pdf', 'docx', 'doc', 'xlsx', 'xls', 'pptx', 'ppt', 'zip', 'rar'])) {
                            $fileType = $ext;
                        }
                    }

                    // Map to Lucide icon name
                    $itemIcon = match ($fileType) {
                        'pdf' => 'file-text',
                        'document', 'docx', 'doc' => 'file-text',
                        'spreadsheet', 'xlsx', 'xls' => 'file-spreadsheet',
                        'presentation', 'pptx', 'ppt' => 'presentation',
                        'archive', 'zip', 'rar' => 'archive',
                        default => 'file-text',
                    };

                    $formattedItems[] = [
                        'judul' => $judul,
                        'deskripsi' => $deskripsi,
                        'tahun' => $tahun,
                        'download_url' => $downloadUrl,
                        'icon' => $itemIcon,
                    ];
                }

                $groupedItems[$tab] = $formattedItems;
            }

            return [
                'tabs' => $tabs,
                'groupedItems' => $groupedItems,
                'title' => $section->meta('title'),
                'subtitle' => $section->meta('subtitle'),
                'tagline' => $section->meta('custom.tagline'),
                'icon' => $section->meta('custom.icon') ?? $section->meta('icon') ?? 'file-text',
                'buttons' => $section->buttons() ?: [],
            ];
        });

        return view('bale-organisasi::livewire.landing-page.regulasi.index', [
            'section' => $section,
            'tabs' => $data['tabs'],
            'groupedItems' => $data['groupedItems'],
            'title' => $data['title'],
            'subtitle' => $data['subtitle'],
            'tagline' => $data['tagline'],
            'icon' => $data['icon'],
            'buttons' => $data['buttons'],
        ]);
    }
}
