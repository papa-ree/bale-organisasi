<?php

namespace Bale\BaleOrganisasi\Livewire\LandingPage\Regulasi;

use Bale\Umpak\Livewire\UmpakComponent;

class Index extends UmpakComponent
{

    public function placeholder()
    {
        return <<<'HTML'
        <section id="regulasi-placeholder" class="py-20 bg-white dark:bg-slate-900 transition-colors duration-300">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="h-8 w-48 bg-slate-100 dark:bg-slate-800 rounded-lg mx-auto mb-12 animate-pulse"></div>
                <div class="grid sm:grid-cols-2 gap-4">
                    <div class="h-24 bg-slate-50 dark:bg-slate-800/50 rounded-3xl animate-pulse"></div>
                    <div class="h-24 bg-slate-50 dark:bg-slate-800/50 rounded-3xl animate-pulse"></div>
                </div>
            </div>
        </section>
        HTML;
    }
    public function render()
    {
        return view('bale-organisasi::livewire.landing-page.regulasi.index', [
            'section' => $this->section('regulasi'),
        ]);
    }
}
