<?php

namespace Bale\BaleOrganisasi\Livewire\LandingPage\Kontak;

use Bale\Umpak\Livewire\UmpakComponent;

class Index extends UmpakComponent
{
    public function render()
    {
        return view('bale-organisasi::livewire.landing-page.kontak.index', [
            'section' => $this->section('kontak'),
        ]);
    }
}
