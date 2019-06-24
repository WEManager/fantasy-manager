<?php
/**
 * Created by PhpStorm.
 * User: jimmiejohansson
 * Date: 2019-06-18
 * Time: 18:34
 */

namespace App\Engines;


use App\Lineup;
use App\Person;
use App\TournamentGame;

class MatchEngine
{
    private $game;
    private $hometeamLineup;
    private $awayteamLineup;

    protected $hometeamPositions;
    protected $awayteamPositions;

    public function __construct(TournamentGame $game)
    {
        $this->game = $game;
        $this->hometeamLineup = Lineup::where('club_id', $this->game->hometeam_id)->where('team', $this->game->hometeam_squad)->first();
        $this->awayteamLineup = Lineup::where('club_id', $this->game->awayteam_id)->where('team', $this->game->awayteam_squad)->first();

        $this->winningChances();

        dd([
            $this->hometeamLineup,
            $this->awayteamLineup,
        ]);
    }

    private function winningChances()
    {
        for ($i=1;$i<12;$i++) {
            $this->hometeamLineup->{'player_' . $i} = Person::find($this->hometeamLineup->{'player_' . $i});
            $this->awayteamLineup->{'player_' . $i} = Person::find($this->awayteamLineup->{'player_' . $i});
        }
    }
}