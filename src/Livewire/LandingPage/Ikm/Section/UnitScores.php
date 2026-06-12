<?php

namespace Bale\BaleOrganisasi\Livewire\LandingPage\Ikm\Section;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class UnitScores extends Component
{
    public $tahun;
    public $triwulan;

    public function render()
    {
        $scores = DB::table('ikm_records')
            ->where('tahun', $this->tahun)
            ->where('triwulan', $this->triwulan)
            ->select('nama_opd', 'nilai_ikm')
            ->inRandomOrder()
            ->limit(5)
            ->get();

        return view('bale-organisasi::livewire.landing-page.ikm.section.unit-scores', [
            'scores' => $scores
        ]);
    }
}
