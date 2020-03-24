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

    protected $updatesQueryString = ['squad'];

    public function mount($locale, $club)
    {
        $this->locale = $locale;
        $this->club = $club;
        $this->squad = request()->query('squad', $this->squad);

        $this->updateSquad($this->squad);
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
