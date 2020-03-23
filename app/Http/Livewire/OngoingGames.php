<?php

namespace App\Http\Livewire;

use App\TournamentGame;
use Livewire\Component;
use Livewire\WithPagination;

class OngoingGames extends Component
{
    use WithPagination;

    public $limit = 15;

    protected $updatesQueryString = ['limit'];

    public function mount()
    {
        $this->limit = request()->query('search', $this->limit);
    }

    public function render()
    {
        return view('livewire.ongoing-games', ['ongoing' => TournamentGame::where('status', '1')->paginate($this->limit)]);
    }
}
