<?php

namespace Bale\BaleOrganisasi\Livewire\LandingPage\Footer;

use Bale\Umpak\Livewire\UmpakComponent;

class Index extends UmpakComponent
{
    public function render()
    {
        return view('bale-organisasi::livewire.landing-page.footer.index', [
            'section' => $this->section('footer'),
            'hero'    => $this->section('hero'),
        ]);
    }
}
