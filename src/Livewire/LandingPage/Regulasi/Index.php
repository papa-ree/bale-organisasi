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
        $latestUpdate = $regulasi->updated_at;

        $cachedUpdate = Cache::get($cacheUpdatedKey);

        if ($cachedUpdate != $latestUpdate) {
            Cache::forget($cacheKey);
            Cache::put($cacheUpdatedKey, $latestUpdate);
        }

        $data = Cache::remember($cacheKey, now()->addHours(24), function () use ($section) {
            $tabs = $section->meta('custom.tabs', []) ?: [];
            $items = collect($section->items ?: []);

            $groupedItems = [];
            foreach ($tabs as $tab) {
                $filtered = $items->filter(function ($i) use ($tab) {
                    $itemCategory = $i['kategori'][0] ?? '';
                    if (empty($itemCategory)) {
                        $itemCategory = 'Umum';
                    }
                    return $itemCategory === $tab;
                })->take(4);

                $formattedItems = [];
                foreach ($filtered as $item) {
                    $formattedItems[] = [
                        'judul' => $item['judul'][0] ?? 'Dokumen Tanpa Judul',
                        'deskripsi' => $item['deskripsi'][0] ?? '',
                        'tahun' => $item['tahun'][0] ?? '',
                        'download_url' => $item['uploads'][0]['url'] ?? $item['url'][0] ?? '#',
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
            'buttons' => $data['buttons'],
        ]);
    }
}
