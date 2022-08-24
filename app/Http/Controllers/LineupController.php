<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\Lineup;
use Illuminate\Http\Request;

class LineupController extends Controller {
    public function edit($locale, Club $club, $squad) {
        if (!$lineup = Lineup::where('club_id', $club->id)->where('team', $squad)->first()) {
            $lineup = Lineup::create([
                'club_id' => $club->id,
                'team' => $squad,
            ]);
        }

        // $this->authorize('view', $lineup);

        $type = getContractType($squad);

        if ($squad == 'senior') $squad = '';

        $players = $club->players($type)->get();

        for ($i = 1; $i <= 17; $i++) {
            foreach ($players as $player) {
                if ($i < 12) {
                    if ($player->id === $lineup->{"player_$i"}) {
                        $player->selected_position = $lineup->{"position_$i"};
                        break;
                    }
                } else {
                    $sub = $i-11;
                    if ($player->id === $lineup->{"substitute_$sub"}) {
                        $player->selected_position = "SUB_$sub";
                    }
                }
            }
        }

        return view('lineups.edit')->with(['club' => $club, 'lineup' => $lineup, 'players' => $players, 'squad' => $squad]);
    }

    public function update($locale, Lineup $lineup) {
        $this->authorize('update', $lineup);

        if ($lineup->update(\request()->all())) {
            return response('Success!');
        }
    }
}
