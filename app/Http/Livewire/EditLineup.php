<?php

namespace App\Http\Livewire;

use App\Lineup;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class EditLineup extends Component
{
    public $lineup;

    public $players;

    public function mount(Collection $players, Lineup $lineup)
    {
        $this->lineup = $lineup;

        $this->players = [];
        foreach ($players as $player) {
            $this->players[$player->id] = $player;
        }
    }

    public function updatePosition($key, $player_id)
    {
        dump("TEST!");
    }

    public function render()
    {
        return view('livewire.edit-lineup')->with(['players' => $this->players, 'lineup' => $this->lineup]);
    }
}
