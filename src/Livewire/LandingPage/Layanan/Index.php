<?php

namespace Bale\BaleOrganisasi\Livewire\LandingPage\Layanan;

use Bale\Umpak\Livewire\UmpakComponent;

class Index extends UmpakComponent
{
    public function render()
    {
        return view('bale-organisasi::livewire.landing-page.layanan.index', [
            'section' => $this->section('layanan'),
        ]);
    }
}
