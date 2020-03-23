<?php

namespace App\Http\Livewire;

use App\Club;
use Livewire\Component;

class Squad extends Component
{
    public $club;

    public $squad;

    public $locale;

    public $players = [];

    public function mount($locale, $club, $squad)
    {
        $this->locale = $locale;
        $this->club = $club;

        $this->updateSquad($squad);
    }

    public function updateSquad($squad)
    {
        app()->setLocale($this->locale);

        $this->squad = $squad;

        $this->players = $this->club->players(getContractType($squad))
            ->get([
                'person_id',
                'firstname',
                'lastname',
                'age',
                'nationality',
                'form',
            ]);
    }

    public function render()
    {
        return view('livewire.squad')->with(['locale' => $this->locale, 'club' => $this->club, 'players' => $this->players, 'squad' => $this->squad]);
    }
}
