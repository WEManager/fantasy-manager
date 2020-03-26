<?php

namespace App\Http\Livewire;

use App\Lineup;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class EditLineup extends Component
{
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
        $this->lineup = $lineup;

        $playersArray = [];
        foreach ($players as $player) {
            $playersArray[$player->id] = $player->toArray();
        }
        $this->players = $playersArray;
        //$this->updatePlayersArray();


        $this->savedLineup();
    }

    private function savedLineup()
    {
        for ($i = 1; $i < 12; $i++) {
            $this->{"position_$i"} = $this->lineup->{"position_$i"};
            $this->{"player_$i"} = $this->lineup->{"player_$i"};
        }

        for ($i = 1; $i < 7; $i++) {
            $this->{"substitute_$i"} = $this->lineup->{"substitute_$i"};
        }
    }

    public function resetLineup()
    {
        $this->savedLineup();
    }

    public function saveLineup()
    {
        for ($i = 1; $i < 12; $i++) {
            $this->lineup->{"player_$i"} = $this->{"player_$i"};
            $this->lineup->{"position_$i"} = $this->{"position_$i"};
        }

        for ($i = 1; $i < 7; $i++) {
            $this->lineup->{"substitute_$i"} = $this->{"substitute_$i"};
        }

        $this->lineup->save();
    }

    public function positionChange($position, $playerId)
    {
        $this->resetErrorBag();

        $this->unsetCurrentPlayerOldPosition($playerId);

        // if the new position is as substitute
        if (strpos($position, 'substitute_') !== false) {

            $playerAlreadyOnNewPosition = $this->{$position};

            // check if we find player in any position on the pitch
            $playerPositionKeys = $this->getPlayerPositionKeys($playerId);

            $this->putPlayersInNewPositions($position, $playerId, $playerPositionKeys, $playerAlreadyOnNewPosition);

            return;
        } else if (in_array($position, getPositions())) {

            for ($i = 1; $i < 12; $i++) {
                if ($this->{"position_$i"} == $position) {
                    $playerKey = "player_$i";
                    $playerAlreadyOnNewPosition = $this->{"player_$i"};

                    $this->{"player_$i"} = null;
                }
            }

            if (!isset($playerAlreadyOnNewPosition)) {
                $availablePlayerPositionKeys = $this->getEmptyPlayerPositionKeys();

                if (count($availablePlayerPositionKeys)) {
                    $this->{$availablePlayerPositionKeys[0]} = $playerId;
                    $this->{$availablePlayerPositionKeys[1]} = $position;
                } else {
                    $this->addError('too_many_players', __('You can only have 11 players'));
                }

                $this->updatePlayersArray();

                return;
            }


            // check if we find player in any position on the pitch
            $playerPositionKeys = $this->getPlayerPositionKeys($playerId);

            $this->putPlayersInNewPositions($position, $playerId, $playerPositionKeys, $playerAlreadyOnNewPosition, $playerKey);

            $this->updatePlayersArray();

            return;
        } // If player is just put off pitch
        else {
            for ($i = 1; $i < 12; $i++) {
                if ($this->{"player_$i"} == $playerId) {
                    $this->{"player_$i"} = null;
                }
            }
            for ($i = 1; $i < 7; $i++) {
                if ($this->{"substitute_$i"} == $playerId) {
                    $this->{"substitute_$i"} = null;
                }
            }
        }

        return;
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

    public function updatePlayersArray()
    {
        $playersArray = [];
        foreach ($this->players as $player) {
            $positionKey = $this->getPlayerPositionKeys($player['id']);
            if (count($positionKey)) {
                $player['selected_position'] = $this->{$positionKey[1]};
            } else {
                unset($player['selected_position']);
            }
            $playersArray[$player['id']] = $player;
        }

        $this->players = $playersArray;
    }

    public function render()
    {
        return view('livewire.edit-lineup')->with(['players' => $this->players, 'lineup' => $this->lineup]);
    }

    private function getPlayerSubstituteKey($playerId): string
    {
        for ($i = 1; $i < 7; $i++) {
            if ($this->{"substitute_$i"} == $playerId) {
                return "substitute_$i";
            }
        }

        return "";
    }

    private function getPlayerPositionKeys($playerId): array
    {
        for ($i = 1; $i < 12; $i++) {
            if ($this->{"player_$i"} == $playerId) {
                return ["player_$i", "position_$i"];
            }
        }

        return [];
    }

    /**
     * @param $position
     * @param $playerId
     * @param array $playerPositionKeys
     * @param $playerAlreadyOnNewPosition
     */
    private function putPlayersInNewPositions($position, $playerId, array $playerPositionKeys, $playerAlreadyOnNewPosition, $playerKey = null): void
    {
        if (count($playerPositionKeys)) {
            $this->{$playerPositionKeys[0]} = $playerAlreadyOnNewPosition;
            $this->setPlayerPosition($playerAlreadyOnNewPosition, $this->{$playerPositionKeys[1]});

        } else {

            // if not, check if we find the player on the bench
            $substituteKey = $this->getPlayerSubstituteKey($playerId);
            if (strlen($substituteKey)) {
                $this->{$substituteKey} = $playerAlreadyOnNewPosition;
                $this->setPlayerPosition($playerAlreadyOnNewPosition, $substituteKey);
            }
        }

        if ($playerKey !== null) {
            $this->{$playerKey} = $playerId;
        } else {
            $this->{$position} = $playerId;
        }

        $this->setPlayerPosition($playerId, $position);
    }

    private function getEmptyPlayerPositionKeys(): array
    {
        for ($i = 1; $i < 12; $i++) {
            if ($this->{"player_$i"} == null) {
                return ["player_$i", "position_$i"];
            }
        }

        return [];
    }

    private function unsetCurrentPlayerOldPosition($playerId): void
    {
        for ($i = 1; $i < 12; $i++) {
            if ($this->{"player_$i"} == $playerId) {
                $this->{"player_$i"} = null;
            }
        }
    }
}
