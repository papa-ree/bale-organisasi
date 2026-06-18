<?php

namespace Bale\BaleOrganisasi\Livewire\LandingPage\Regulasi;

use Bale\Umpak\Livewire\UmpakComponent;

class Index extends UmpakComponent
{
    public function render()
    {
        return view('bale-organisasi::livewire.landing-page.regulasi.index', [
            'section' => $this->section('regulasi'),
        ]);
    }
}
