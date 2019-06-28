<?php
/**
 * Created by PhpStorm.
 * User: jimmiejohansson
 * Date: 2019-06-18
 * Time: 18:34
 */

namespace App\Engines;


use App\GameEvent;
use App\Lineup;
use App\Person;
use App\TournamentGame;
use Illuminate\Support\Facades\Cache;

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

        // If game has not started yet
        if ($this->game->isAboutToStart) {
            $this->startGame();
            $this->generateChances();
        }

        if ($this->game->isTimeForHalftime) {
            $this->halftime();
        }

        if ($this->game->isTimeForSecondHalf) {
            $this->startSecondHalf();
        }

        if ($this->game->isAboutToEnd) {
            $this->endGame();
        }

        if (time() >= strtotime($this->game->start_time)) {
            $this->winningChances();
        }
    }

    private function startGame()
    {
        if (is_null($this->game->hometeam_score)) $this->game->hometeam_score = 0;
        if (is_null($this->game->awayteam_score)) $this->game->awayteam_score = 0;
        $this->game->status = '1';
        $this->game->save();
    }

    private function halftime()
    {
        $this->game->status = '3';
        $this->game->save();
    }

    private function startSecondHalf()
    {
        $this->game->status = '1';
        $this->game->save();
    }

    private function endGame()
    {
        $this->game->status = '2';
        $this->game->save();
    }

    private function winningChances()
    {
        for ($i = 1; $i < 12; $i++) {
            $this->hometeamLineup->{'player_' . $i} = Person::find($this->hometeamLineup->{'player_' . $i});
            $this->awayteamLineup->{'player_' . $i} = Person::find($this->awayteamLineup->{'player_' . $i});
        }

        $hometeamSkills = $this->teamStrength('hometeam');
        $awayteamSkills = $this->teamStrength('awayteam');

        $totalSkill = $hometeamSkills + $awayteamSkills;

        if (rand(0, $totalSkill) <= $hometeamSkills) {
            $this->createChance('hometeam', 'awayteam');
        } else {
            $this->createChance('awayteam', 'hometeam');
        }
    }

    private function createChance($creators, $defenders, $isCounter = false)
    {
        $attackingMidfield = $this->getMidfieldSkills($creators);
        $defendingMidfield = $this->getMidfieldSkills($defenders);

        $totalMidfield = $attackingMidfield + $defendingMidfield;

        if (rand(0, $totalMidfield) <= $attackingMidfield) {
            echo $creators . ' går till attack';
            $this->scoringChance($creators, $defenders);
        } elseif (!$isCounter) {
            echo $defenders . ' bryter på mittfältet';
            $this->counterAttack($defenders, $creators);
        }
    }

    private function scoringChance($creators, $defenders)
    {
        $attackingForwards = $this->getAttackerSkills($creators);
        $defendingBacks = $this->getDefendingSkills($defenders);

        $totalSkills = $attackingForwards + $defendingBacks;

        if (rand(0, $totalSkills) <= $attackingForwards) {
            echo $creators . ' gör mål!';
            $this->game->{$creators . '_score'} = is_null($this->game->{$creators . '_score'}) ? 1 : $this->game->{$creators . '_score'} + 1;
            $this->game->{$defenders . '_score'} = is_null($this->game->{$defenders . '_score'}) ? 0 : $this->game->{$defenders . '_score'};
            $this->game->save();
        } else {
            echo $defenders . ' lyckas försvara sig.';
        }
    }

    private function counterAttack($creators, $defenders)
    {
        if (rand(1, 5) <= 2) {
            $this->createChance($creators, $defenders, true);
        }
    }

    private function teamStrength($teamKey)
    {

        $defense = $this->getDefendingSkills($teamKey);
        $midfield = $this->getMidfieldSkills($teamKey);
        $attack = $this->getAttackerSkills($teamKey);

        return $defense + $midfield + $attack;
    }

    private function getDefendingSkills($teamKey)
    {
        $totalValue = 0;

        foreach ($this->{$teamKey . 'Lineup'}->getAttributes() as $key => $value) {
            if (strpos($key, 'position_') !== false) {

                $number = explode('_', $key);
                // if is defender
                if (substr($value, -1) === 'D') {
                    if (substr($value, 0, 1) === 'C') {
                        $totalValue += $this->{$teamKey . 'Lineup'}->{'player_' . $number[1]}->central_defending[$value];
                    } else {
                        $totalValue += $this->{$teamKey . 'Lineup'}->{'player_' . $number[1]}->wide_defending[$value];
                    }
                }
            }
        }

        return $totalValue;
    }

    private function getMidfieldSkills($teamKey)
    {
        $totalValue = 0;

        foreach ($this->{$teamKey . 'Lineup'}->getAttributes() as $key => $value) {
            if (strpos($key, 'position_') !== false) {

                $number = explode('_', $key);
                // if is defender
                if (substr($value, -1) === 'M') {
                    if (substr($value, 0, 1) === 'C') {
                        $totalValue += $this->{$teamKey . 'Lineup'}->{'player_' . $number[1]}->central_midfielder[$value];
                    } else {
                        $totalValue += $this->{$teamKey . 'Lineup'}->{'player_' . $number[1]}->wide_midfielder[$value];
                    }
                }
            }
        }

        return $totalValue;
    }

    private function getAttackerSkills($teamKey)
    {
        $totalValue = 0;

        foreach ($this->{$teamKey . 'Lineup'}->getAttributes() as $key => $value) {
            if (strpos($key, 'position_') !== false) {

                $number = explode('_', $key);
                // if is defender
                if (substr($value, -1) === 'F') {
                    if (substr($value, 0, 1) === 'C') {
                        $totalValue += $this->{$teamKey . 'Lineup'}->{'player_' . $number[1]}->central_attacker[$value];
                    } else {
                        $totalValue += $this->{$teamKey . 'Lineup'}->{'player_' . $number[1]}->wide_attacker[$value];
                    }
                }
            }
        }

        return $totalValue;
    }

    protected function generateChances(): void
    {
        // Generate 10 to 26 chances in a game
        $chances = rand(10, 26);
        for ($i = 0; $i < $chances; $i++) {
            $minute = rand(1, 90);
            if ($minute > 45) $minute = $minute + 15;
            GameEvent::create([
                'game_id' => $this->game->id,
                'event_time' => date('Y-m-d H:i:s', strtotime($this->game->start_time . ' + ' . $minute . ' minutes')),
            ]);
        }
    }
}
