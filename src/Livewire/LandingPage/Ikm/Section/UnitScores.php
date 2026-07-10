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
            ->join('ikm_batches', 'ikm_records.ikm_batch_id', '=', 'ikm_batches.id')
            ->where('ikm_batches.status', 'selesai')
            ->whereNotNull('ikm_batches.approved_at')
            ->whereNull('ikm_batches.deleted_at')
            ->whereNull('ikm_records.deleted_at')
            ->where('ikm_records.tahun', $this->tahun)
            ->where('ikm_records.triwulan', $this->triwulan)
            ->select('ikm_records.nama_opd', 'ikm_records.nilai_ikm')
            ->inRandomOrder()
            ->limit(5)
            ->get();

        return view('bale-organisasi::livewire.landing-page.ikm.section.unit-scores', [
            'scores' => $scores
        ]);
    }
}
