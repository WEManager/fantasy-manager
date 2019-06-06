<?php

namespace App\Engines;

class GameEngine
{

    public $hometeamName = 'Barcelona';
    public $awayteamName = 'Espanyol';
    private $hometeam;
    private $awayteam;
    public $chances = ['hometeam' => 0, 'awayteam' => 0];
    private $tactics = ['hometeam' => 50, 'awayteam' => 50];
    private $attacking = ['hometeam' => 0, 'awayteam' => 0];
    private $defending = ['hometeam' => 0, 'awayteam' => 0];
    public $score = ['hometeam' => 0, 'awayteam' => 0];
    private $eventMinutes = [];
    private $events = [];

    public function __construct($gameId = null, $hometeam, $awayteam)
    {
        if (is_null($gameId)) {
            $this->hometeam = [
                rand(1, 100),
                rand(1, 100),
                rand(1, 100),
                rand(1, 100),
                rand(1, 100),
                rand(1, 100),
                rand(1, 100),
                rand(1, 100),
                rand(1, 100),
                rand(1, 100),
                rand(1, 100),
                rand(1, 100),
                rand(1, 100),
                rand(1, 100),
                rand(1, 100),
                rand(1, 100),
            ];
            $this->awayteam = [
                rand(1, 100),
                rand(1, 100),
                rand(1, 100),
                rand(1, 100),
                rand(1, 100),
                rand(1, 100),
                rand(1, 100),
                rand(1, 100),
                rand(1, 100),
                rand(1, 100),
                rand(1, 100),
                rand(1, 100),
                rand(1, 100),
                rand(1, 100),
                rand(1, 100),
                rand(1, 100),
            ];
        } else {
            $this->hometeam = $hometeam;
            $this->awayteam = $awayteam;
        }

        $this->playGame();
    }

    public function playGame()
    {
        $this->setWinningChances();
        $this->setTactics();
        $this->setEvents();

        $this->startEvents();

        // echo $this->score['hometeam'] . ' - ' . $this->score['awayteam'] . '\\n';
        // echo $this->chances['hometeam'] . '% : ' . $this->chances['awayteam'] . '%\\n';
    }

    private function createShot($currentTeamKey)
    {
        $quality = rand(1, 100);

        $event = CommentsEngine::shoots();
        if ($quality < 33) {
            $event .= CommentsEngine::missed_shot();
        } elseif ($quality < 67) {
            $event .= CommentsEngine::saved_shot();
        } else {
            $event .= CommentsEngine::goal();
            $this->score[$currentTeamKey] += 1;
        }

        return $event;
    }

    private function createAttack($currentTeamKey, $competitorTeamKey, $counter, $isCounter = false)
    {
        $event = (!$isCounter) ? $currentTeamKey . ' ' . CommentsEngine::is_attacking() . '. ' : '';

        // anfall med många man, väldigt stor risk för kontring
        $eventSum = rand(0, ceil($this->attacking[$currentTeamKey] + $this->defending[$competitorTeamKey]));

        // $currentTeam får anfall
        if ($eventSum <= $this->attacking[$currentTeamKey]) {
            $event .= CommentsEngine::is_in_attack_mode($currentTeamKey) . ' ' . $this->createShot($currentTeamKey);
        } elseif (!$counter) {
            $event .= CommentsEngine::is_in_attack_mode($currentTeamKey) . ' ' . CommentsEngine::gets_interrupted();
        } else {
            $event .= CommentsEngine::is_in_attack_mode($currentTeamKey) . ' ' . CommentsEngine::opposition_gets_the_ball($competitorTeamKey) . ' ';

            $event .= CommentsEngine::counter_attack($competitorTeamKey) . ' ' . $this->createAttack($competitorTeamKey, $currentTeamKey, rand(0, 1) < 1, true);
        }

        return $event;
    }

    private function createEvent($currentTeamKey, $competitorTeamKey, $minute)
    {

        if ($this->tactics[$currentTeamKey] > 80) {
            // anfall med hela laget, extremt stor risk för kontring
            $event = $this->createAttack($currentTeamKey, $competitorTeamKey, rand(1, 100) < 75);
        } elseif ($this->tactics[$currentTeamKey] > 65) {
            // anfall med flera man, stor risk för kontring
            $event = $this->createAttack($currentTeamKey, $competitorTeamKey, rand(1, 100) < 55);
        } elseif ($this->tactics[$currentTeamKey] > 55) {
            // anfallare kör, risk för kontring
            $event = $this->createAttack($currentTeamKey, $competitorTeamKey, rand(1, 100) < 40);
        } elseif ($this->tactics[$currentTeamKey] > 45) {
            // anfall, liten risk för kontring
            $event = $this->createAttack($currentTeamKey, $competitorTeamKey, rand(1, 100) < 30);
        } elseif ($this->tactics[$currentTeamKey] > 35) {
            // anfall, väldigt liten risk för kontring
            $event = $this->createAttack($currentTeamKey, $competitorTeamKey, rand(1, 100) < 20);
        } elseif ($this->tactics[$currentTeamKey] > 25) {
            // anfall, extremt liten risk för kontring
            $event = $this->createAttack($currentTeamKey, $competitorTeamKey, rand(1, 100) < 10);
        } else {
            // anfall med en gubbe, extremt liten risk för kontring
            $event = $this->createAttack($currentTeamKey, $competitorTeamKey, rand(1, 100) < 5);
        }

        return [
            'time' => $minute,
            'team' => $currentTeamKey,
            'event' => $event,
        ];
    }

    private function startEvents()
    {
        foreach ($this->eventMinutes as $minute) {
            $chance = rand(0, 100) / 100;
            // Hometeam gets a chance
            if ($chance <= $this->chances['hometeam']) {
                $this->events[] = $this->createEvent('hometeam', 'awayteam', $minute);
            } // Awayteam gets a chance
            else {
                $this->events[] = $this->createEvent('awayteam', 'hometeam', $minute);
            }
        }
    }

    private function setEvents()
    {
        $events = rand(5, 25);

        for ($i = 0; $i < $events; $i++) {
            $minute = rand(1, 90);
            $this->eventMinutes[] = $minute;
        }

        sort($this->eventMinutes);
    }

    private function setWinningChances()
    {
        $totalChance = 0;

        list($homeChance, $totalChance, $homeBenchChance) = $this->calculateChanceForTeam($totalChance, 'hometeam');
        list($awayChance, $totalChance, $awayBenchChance) = $this->calculateChanceForTeam($totalChance, 'awayteam');

        $this->chances['hometeam'] = ($homeChance / $totalChance) + 0.0075; // home advantage
        $this->chances['awayteam'] = ($awayChance / $totalChance) - 0.0075; // away disadvantage
    }

    private function setWeightBasedOnTactics()
    {
        $this->attacking['hometeam'] = $this->tactics['hometeam'] * $this->chances['hometeam'];
        $this->defending['hometeam'] = (100 - $this->tactics['hometeam']) * $this->chances['hometeam'];

        $this->attacking['awayteam'] = $this->tactics['awayteam'] * $this->chances['awayteam'];
        $this->defending['awayteam'] = (100 - $this->tactics['awayteam']) * $this->chances['awayteam'];
    }

    private function setTactics()
    {
        $this->tactics['hometeam'] = $this->getTactic();
        $this->tactics['awayteam'] = $this->getTactic();

        $this->setWeightBasedOnTactics();
    }

    private function getTactic()
    {
        $tactics = [
            85, // all out offensive 85-15
            70, // very offensive 70-30
            60, // offensive 60-40
            50, // neutral 50-50
            40, // defensive 40-60
            30, // very defensive 30-70
            15, // park the bus 15-85
        ];
        shuffle($tactics);

        return $tactics[0];
    }

    /*private function gettingAnInjury($team)
    {
        foreach ($this->{$team} as $index => $value) {
            if ($index < 11) {
                // 16.9 times per 1000 appearances
                $injured = rand(1, 1000) < 17;
            }
        }

    }*/

    /**
     * @param $totalChance
     * @return array
     */
    protected function calculateChanceForTeam($totalChance, $team): array
    {
        $chance = 0;
        $benchChance = 0;

        rsort($this->{$team});
        foreach ($this->{$team} as $index => $value) {
            if ($index < 11) {
                $chance += $value;
                $totalChance += $value;
            } else {
                $benchChance += $value;
            }
        }
        return [$chance, $totalChance, $benchChance];
    }

    public function renderEvents()
    {
        $data = '<h1>' . $this->hometeamName . ' '.$this->score['hometeam'].'-'.$this->score['awayteam'].' ' . $this->awayteamName . '</h1>';

        foreach ($this->events as $event) {
            $ev = str_replace('hometeam', $this->hometeamName, $event['event']);
            $ev = str_replace('awayteam', $this->awayteamName, $ev);
            $data .= $event['time'] . ' min : ' . $ev . '<br />';
        }

        return $data;
    }

}