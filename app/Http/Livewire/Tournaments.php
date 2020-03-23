<?php

namespace App\Http\Livewire;

use App\Tournament;
use Livewire\Component;

class Tournaments extends Component
{
    public $orderBy;

    public $tournaments = [];

    public function mount()
    {
        $this->orderBy = 'nationality';

        $tnmts = Tournament::orderBy($this->orderBy)->get(['id', 'slug', 'name', 'nationality']);

        $tournaments = [];
        foreach ($tnmts as $tnmt) {
            $tournaments[$tnmt->nationality][] = $tnmt;
        }

        $this->tournaments = $tournaments;
    }

    public function render()
    {
        return view('livewire.tournaments', ['tournaments' => $this->tournaments]);
    }
}
