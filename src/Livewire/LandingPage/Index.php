<?php

namespace Bale\BaleOrganisasi\Livewire\LandingPage;

use Bale\Umpak\Livewire\UmpakComponent;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

class Index extends UmpakComponent
{
    #[Layout('bale-organisasi::layouts.app')]
    #[Title('Bale Organisasi')]
    public function render()
    {
        return view('bale-organisasi::livewire.landing-page.index');
    }
}