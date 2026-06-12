<?php

namespace Bale\BaleOrganisasi\Livewire\LandingPage\Hero;

use Bale\Umpak\Livewire\UmpakComponent;

class Index extends UmpakComponent
{
    public function render()
    {
        return view('bale-organisasi::livewire.landing-page.hero.index', [
            'section' => $this->section('hero'),
        ]);
    }
}
