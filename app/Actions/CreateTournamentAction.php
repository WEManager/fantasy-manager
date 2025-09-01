<?php

declare(strict_types=1);

namespace App\Actions;

use App\Enums\TournamentType;
use App\Http\Requests\CreateTournamentRequest;
use App\Models\Tournament;
use App\Models\TournamentGroup;
use App\Models\TournamentParticipant;
use Illuminate\Support\Facades\DB;

final class CreateTournamentAction
{
    public function handle(CreateTournamentRequest $request): Tournament
    {
        return DB::transaction(function () use ($request) {
            $createdTournament = Tournament::create([
                'name' => $request->name,
                'type' => TournamentType::from($request->type),
                'groups' => $request->groups,
                'playoffs' => $request->playoffs,
                'proceeding_to_playoffs' => $request->proceeding_to_playoffs,
                'participants' => count($request->selectedClubs),
            ]);

            // Criar grupos para o torneio
            for ($i = 1; $i <= $request->groups; $i++) {
                TournamentGroup::create([
                    'tournament_id' => $createdTournament->id,
                    'name' => "Grupo $i",
                ]);
            }

            // Adicionar clubes participantes
            foreach ($request->selectedClubs as $clubId) {
                TournamentParticipant::create([
                    'club_id' => $clubId,
                    'tournament_id' => $createdTournament->id,
                    'season_id' => 1, // Temporário - deve vir do request
                ]);
            }

            return $createdTournament;
        });
    }
}
