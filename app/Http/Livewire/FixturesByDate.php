<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\TournamentGame;
use App\TournamentStanding;
use Illuminate\Support\Facades\Cache;

class FixturesByDate extends Component
{
    public $date;
    public $before;
    public $after;
    public $fixtures = [];
    public $participants = [];
    public $groupId;

    protected $updatesQueryString = ['date'];

    public function mount($groupId)
    {
        $date = request()->query('date', date('Y-m-d'));
        $this->date = $date;
        $this->before = date('Y-m-d', strtotime($date . ' - 1 day'));
        $this->after = date('Y-m-d', strtotime($date . ' + 1 day'));
        $this->groupId = $groupId;

        $this->fixtures = $this->getFixtures();
    }

    public function goToPreviousDay()
    {
        $this->date = date('Y-m-d', strtotime($this->date . ' - 1 day'));
        $this->before = date('Y-m-d', strtotime($this->date . ' - 1 day'));
        $this->after = date('Y-m-d', strtotime($this->date . ' + 1 day'));
        $this->fixtures = $this->getFixtures();
    }

    public function goToNextDay()
    {
        $this->date = date('Y-m-d', strtotime($this->date . ' + 1 day'));
        $this->before = date('Y-m-d', strtotime($this->date . ' - 1 day'));
        $this->after = date('Y-m-d', strtotime($this->date . ' + 1 day'));
        $this->fixtures = $this->getFixtures();
    }

    public function getFixtures()
    {
        $this->participants = $this->getParticipatingClubs();

        $fixtures = TournamentGame::where('group_id', $this->groupId)->whereDate('start_time', $this->date)->get();

        foreach ($fixtures as $fixture) {
            $fixture->hometeam = $this->participants[$fixture->hometeam_id];
            $fixture->awayteam = $this->participants[$fixture->hometeam_id];
        }

        return $fixtures;
    }

    public function getParticipatingClubs() : array
    {
        $cacheKey = 'participants_group_' . $this->groupId;

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $participatingClubs = [];
        $participants = TournamentStanding::where('group_id', $this->groupId)->with('club')->get();
        foreach ($participants as $participant) {
            $participatingClubs[$participant->club->id] = $participant->club;
        }

        Cache::put($cacheKey, $participatingClubs);

        return $participatingClubs;
    }

    public function render()
    {
        return view('livewire.fixtures-by-date')->with([
            'date' => $this->date,
            'before' => $this->before,
            'after' => $this->after,
            'fixtures' => $this->fixtures,
            'participants' => (array) $this->participants,
        ]);
    }
}
