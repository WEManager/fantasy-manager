<?php
/**
 * Created by PhpStorm.
 * User: jimmiejohansson
 * Date: 2019-06-18
 * Time: 18:34
 */

namespace App\Engines;


use App\Lineup;
use App\TournamentGame;

class MatchEngine
{
    private $game;
    private $hometeamLineup;
    private $awayteamLineup;

    public function __construct(TournamentGame $game)
    {
        $this->game = $game;
        $this->hometeamLineup = Lineup::where('club_id', $this->game->hometeam_id)->where('team', $this->game->hometeam_squad)->first();
        $this->awayteamLineup = Lineup::where('club_id', $this->game->awayteam_id)->where('team', $this->game->awayteam_squad)->first();
    }

    private function getLineups()
    {

    }
}