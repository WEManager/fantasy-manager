<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Fixture;
use App\Models\Player;
use Inertia\Inertia;
use Inertia\Response;

final class FixtureController extends Controller
{
    public function __invoke(Fixture $game): Response
    {
        $game->loadMissing([
            'group.tournament',
            'hometeam',
            'awayteam',
            'gameHappenings',
            'homeLineup',
            'awayLineup',
        ]);

        // Carregar dados dos lineups com jogadores
        $homeTeamLineup = $game->homeLineup;
        $awayTeamLineup = $game->awayLineup;

        // Formatar lineup do time da casa
        $homePlayers = [];
        if ($homeTeamLineup) {
            for ($i = 1; $i <= 11; $i++) {
                $playerId = $homeTeamLineup->{'player_'.$i};
                $position = $homeTeamLineup->{'position_'.$i};

                if ($playerId && $position) {
                    $player = Player::find($playerId);
                    if ($player) {
                        // Usar chave única combinando posição e slot para evitar sobrescrita
                        $uniquePosition = $position.'_'.$i;
                        $homePlayers[$uniquePosition] = [
                            'id' => $player->id,
                            'know_as' => $player->know_as,
                            'full_name' => $player->full_name,
                            'position' => $position,
                            'slot' => $i,
                            'nation' => $player->nation ? [
                                'id' => $player->nation->id,
                                'name' => $player->nation->name,
                                'iso_code' => $player->nation->iso_code,
                            ] : null,
                        ];
                    }
                }
            }
        }

        // Formatar lineup do time visitante
        $awayPlayers = [];
        if ($awayTeamLineup) {
            for ($i = 1; $i <= 11; $i++) {
                $playerId = $awayTeamLineup->{'player_'.$i};
                $position = $awayTeamLineup->{'position_'.$i};

                if ($playerId && $position) {
                    $player = Player::find($playerId);
                    if ($player) {
                        // Usar chave única combinando posição e slot para evitar sobrescrita
                        $uniquePosition = $position.'_'.$i;
                        $awayPlayers[$uniquePosition] = [
                            'id' => $player->id,
                            'know_as' => $player->know_as,
                            'full_name' => $player->full_name,
                            'position' => $position,
                            'slot' => $i,
                            'nation' => $player->nation ? [
                                'id' => $player->nation->id,
                                'name' => $player->nation->name,
                                'iso_code' => $player->nation->iso_code,
                            ] : null,
                        ];
                    }
                }
            }
        }

        // Adicionar dados de lineup formatados ao game
        $game->homeLineup = $homeTeamLineup ? [
            'id' => $homeTeamLineup->id,
            'club_id' => $homeTeamLineup->club_id,
            'players' => $homePlayers,
        ] : null;

        $game->awayLineup = $awayTeamLineup ? [
            'id' => $awayTeamLineup->id,
            'club_id' => $awayTeamLineup->club_id,
            'players' => $awayPlayers,
        ] : null;

        return Inertia::render('games/show/page', compact('game'));
    }
}
