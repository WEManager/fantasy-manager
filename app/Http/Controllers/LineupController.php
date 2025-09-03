<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\Lineup;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

final class LineupController extends Controller
{
    public function edit(Club $club): Response
    {
        $club->loadMissing(['players:id,club_id,know_as,best_position,positions']);

        $lineup = Lineup::firstOrCreate(
            ['club_id' => $club->id],
            []
        );

        $selectedByPlayerId = $this->buildSelectedMap($lineup);

        /** @var \Illuminate\Support\Collection<int,array{
         *   id:int, know_as:string, best_position:string,
         *   positions:array<int,string>, selected_position:string|null
         * }> $players
         */
        $players = $club->players->map(
            /**
             * @param  \App\Models\Player  $p
             * @return array{id:int,know_as:string,best_position:string,positions:array<int,string>,selected_position:string|null}
             */
            fn ($p) => [
                'id' => (int) $p->id,
                'know_as' => (string) $p->know_as,
                'best_position' => (string) $p->best_position,
                'positions' => (array) $p->positions,
                'selected_position' => $selectedByPlayerId[(int) $p->id] ?? (string) $p->best_position,
            ]
        )->values();

        return Inertia::render('lineups/edit/page', [
            'club' => $club,
            'lineup' => $lineup,
            'players' => $players,
        ]);
    }

    public function update(Request $request, Lineup $lineup): RedirectResponse
    {
        $this->authorize('update', $lineup);

        $rules = [];
        for ($i = 1; $i <= 11; $i++) {
            $rules["player_$i"] = ['nullable', 'integer', 'exists:players,id'];
            $rules["position_$i"] = ['nullable', 'string', 'max:20'];
        }
        for ($s = 1; $s <= 6; $s++) {
            $rules["substitute_$s"] = ['nullable', 'integer', 'exists:players,id'];
        }

        $data = $request->validate($rules);

        $lineup->update($data);

        return redirect()
            ->route('edit_lineup', ['club' => $lineup->club])
            ->with('message', 'Escalação atualizada com sucesso!')
            ->with('type', 'success');
    }

    /**
     * @return array<int,string>
     */
    private function buildSelectedMap(Lineup $lineup): array
    {
        $map = [];

        // titulares
        for ($i = 1; $i <= 11; $i++) {
            $pid = (int) ($lineup->{"player_$i"} ?? 0);
            if ($pid > 0) {
                $pos = (string) ($lineup->{"position_$i"} ?? '');
                if ($pos !== '') {
                    $map[$pid] = $pos;
                }
            }
        }
        // reservas
        for ($s = 1; $s <= 6; $s++) {
            $pid = (int) ($lineup->{"substitute_$s"} ?? 0);
            if ($pid > 0) {
                $map[$pid] = "SUB_$s";
            }
        }

        return $map;
    }
}
