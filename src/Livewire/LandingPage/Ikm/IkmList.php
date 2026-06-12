<?php

namespace Bale\BaleOrganisasi\Livewire\LandingPage\Ikm;

use Bale\Umpak\Livewire\UmpakComponent;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

class IkmList extends UmpakComponent
{
    #[Layout('bale-organisasi::layouts.app')]
    #[Title('Indeks Kepuasan Masyarakat - Bale Organisasi')]
    public function render()
    {
        return view('bale-organisasi::livewire.landing-page.ikm.ikm-list');
    }
}
