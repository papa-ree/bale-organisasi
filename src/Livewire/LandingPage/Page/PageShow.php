<?php

namespace Bale\BaleOrganisasi\Livewire\LandingPage\Page;

use Bale\Umpak\Livewire\UmpakComponent;
use Livewire\Attributes\Layout;

class PageShow extends UmpakComponent
{
    public string $slug;

    public function mount(string $slug): void
    {
        
        $this->slug = $slug;
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $page = $this->page($this->slug);

        if (! $page) abort(404);

        return view('bale-organisasi::livewire.landing-page.page.page-show', [
            'page' => $page
        ])->title($page->title . ' — Bale Organisasi');
    }
}