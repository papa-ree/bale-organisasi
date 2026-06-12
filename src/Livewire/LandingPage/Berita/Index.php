<?php

namespace Bale\BaleOrganisasi\Livewire\LandingPage\Berita;

use Bale\Umpak\Livewire\UmpakComponent;

class Index extends UmpakComponent
{
    public function render()
    {
        $section = $this->section('berita');
        $limit = $section ? $section->meta('custom.post_limit', 3) : 3;

        return view('bale-organisasi::livewire.landing-page.berita.index', [
            'section' => $section,
            'posts' => $this->latestPosts($limit), // Menggunakan helper UmpakComponent
        ]);
    }
}
