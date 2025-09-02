<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\Lineup;
use Inertia\Inertia;
use Inertia\Response;

final class LineupController extends Controller
{
    public function edit(Club $club, $squad): Response
    {
        if (! $lineup = Lineup::where('club_id', $club->id)->where('team', $squad)->first()) {
            $lineup = Lineup::create([
                'club_id' => $club->id,
                'team' => $squad,
            ]);
        }

        // $type = getContractType($squad);

        // if ($squad == 'senior') $squad = '';

        $players = $club->players;

        for ($i = 1; $i <= 17; $i++) {
            foreach ($players as $player) {
                if ($i < 12) {
                    if ($player->id === $lineup->{"player_$i"}) {
                        $player->selected_position = $lineup->{"position_$i"};
                        break;
                    }
                } else {
                    $sub = $i - 11;

                    if ($player->id === $lineup->{"substitute_$sub"}) {
                        $player->selected_position = "SUB_$sub";
                    }
                }
            }
        }

        return Inertia::render('lineups/edit/page', compact('club', 'lineup', 'players', 'squad'));
    }

    public function update(Lineup $lineup)
    {
        $this->authorize('update', $lineup);

        $data = request()->all();
        unset($data['_token'], $data['_method']);

        $lineup->update($data);

        return redirect()
            ->route('edit_lineup', ['club' => $lineup->club, 'squad' => $lineup->team])
            ->with('message', 'Escalação atualizada com sucesso!')
            ->with('type', 'success');
    }
}
