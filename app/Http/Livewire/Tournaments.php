<?php

namespace App\Http\Livewire;

use App\Models\Tournament;
use Livewire\Component;

class Tournaments extends Component {
    public $orderBy;

    public $tournaments = [];

    public function mount() {
        $this->orderBy = 'nationality';

        $tournaments = Tournament::orderBy($this->orderBy)->get(['id', 'slug', 'name', 'nationality']);

        $tournamentsByNation = [];

        foreach ($tournaments as $tnmt) {
            $tournamentsByNation[$tnmt->nationality][] = $tnmt;
        }

        $this->tournaments = $tournamentsByNation;
    }

    public function render() {
        $tournaments = $this->tournaments;

        return view('livewire.tournaments', compact('tournaments'));
    }
}
