<?php

namespace App\Http\Livewire;

use App\Lineup;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class EditLineup extends Component
{
    public $title;

    public $lineup;

    public array $players = [];

    public $position_1;
    public $position_2;
    public $position_3;
    public $position_4;
    public $position_5;
    public $position_6;
    public $position_7;
    public $position_8;
    public $position_9;
    public $position_10;
    public $position_11;

    public $player_1;
    public $player_2;
    public $player_3;
    public $player_4;
    public $player_5;
    public $player_6;
    public $player_7;
    public $player_8;
    public $player_9;
    public $player_10;
    public $player_11;

    public $substitute_1;
    public $substitute_2;
    public $substitute_3;
    public $substitute_4;
    public $substitute_5;
    public $substitute_6;

    //public $errorBag = [];

    public function mount(Collection $players, Lineup $lineup)
    {
        $this->title = 'Loaded';

        $this->lineup = $lineup;

        for ($i = 1; $i < 12; $i++) {
            $this->{"position_$i"} = $lineup->{"position_$i"};
            $this->{"player_$i"} = $lineup->{"player_$i"};
        }

        for ($i = 1; $i < 7; $i++) {
            $this->{"substitute_$i"} = $lineup->{"substitute_$i"};
        }

        $playersArray = [];
        foreach ($players as $player) {
            $playersArray[$player->id] = $player->toArray();
        }
        $this->players = $playersArray;
    }

    public function positionChange($position, $playerId)
    {
        $found = $this->findCurrentPlayer($position, $playerId);

        if (!$found) {

            // Check if available position
            for ($i = 1; $i < 12; $i++) {
                if ($this->{"player_$i"} < 1) {
                    $this->{"player_$i"} = $playerId;
                    $this->{"position_$i"} = $position;

                    $this->setPlayerPosition($playerId, $position);

                    return;
                }
            }

            $players = [];
            foreach ($this->players as $player) {
                $players[$player['id']] = $player;
            }
            $this->players = $players;

            $this->addError('too_many_players', __('You can only have 11 players'));
        }
    }

    public function setPlayerPosition($playerId, $position)
    {
        $players = [];
        foreach ($this->players as $player) {
            if ($player['id'] == $playerId) {
                $player['selected_position'] = $position;
            }

            $players[$player['id']] = $player;
        }

        $this->players = $players;
    }

    public function render()
    {
        return view('livewire.edit-lineup')->with(['title' => $this->title, 'players' => $this->players, 'lineup' => $this->lineup]);
    }

    /**
     * @param $newPosition
     * @param $playerId
     * @return bool
     */
    private function findCurrentPlayer($newPosition, $playerId): bool
    {
        $found = false;

        for ($i = 1; $i < 12; $i++) {
            // If this is the same player
            if ($this->{"player_$i"} == $playerId) {

                // If selecting no position for player
                if ($newPosition == '') {
                    $this->{"player_$i"} = null;
                    return true;
                }

                $currentPlayer = $this->{"player_$i"};
                $oldPosition = $this->{"position_$i"};

                for ($x = 1; $x < 12; $x++) {
                    // Then check if this position is already used
                    if ($this->{"position_$x"} == $newPosition) {
                        // If so, set the other players position to this players old position
                        $playerOnTheNewPosition = $this->{"player_$x"};
                        $this->{"position_$x"} = $oldPosition;
                    }
                }


                $this->setPlayerPosition($currentPlayer, $newPosition);
                if (isset($playerOnTheNewPosition)) {
                    $this->setPlayerPosition($playerOnTheNewPosition, $oldPosition);
                }

                // This player is awarded a new position
                $this->{"position_$i"} = $newPosition;
                $found = true;

                break;
            }
        }
        return $found;
    }
}
