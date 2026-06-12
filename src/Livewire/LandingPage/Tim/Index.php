<?php

namespace Bale\BaleOrganisasi\Livewire\LandingPage\Tim;

use Bale\Umpak\Livewire\UmpakComponent;

class Index extends UmpakComponent
{
    public function render()
    {
        return view('bale-organisasi::livewire.landing-page.tim.index', [
            'section' => $this->section('tim'),
        ]);
    }
}
