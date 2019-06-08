<?php

namespace App\Engines;

use App\Person;

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

        $goalKeeper = Person::find(1);
        $midfielder = Person::find(2);
        $defender = Person::find(1);
        $forward = Person::find(3);

        $this->hometeamLineup = [
            'GK' => $goalKeeper->goalkeeping['GK'],

            'LD' => $defender->wide_defending,
            'CLD' => $defender->central_defending, // no player
            'CD' => $defender->central_defending,
            'CRD' => null,
            'RD' => null,

            'LM' => $midfielder->wide_midfielder,
            'CLM' => $midfielder->central_midfielder,
            'CM' => $midfielder->central_midfielder,
            'CRM' => $midfielder->central_midfielder,
            'RM' => $midfielder->wide_midfielder,

            'LF' => $forward->wide_attacker,
            'CLF' => $forward->central_attacker,
            'CF' => null,
            'CRF' => null,
            'RF' => null,
        ];

        $players = 0;
        foreach ($this->hometeamLineup as $value) {
            if (!is_null($value)) {
                $players++;
            }
        }


        dd([
            'players' => $players,
            'GK' => $this->hometeamLineup['GK'],
            'LD' => $this->countStrengthForPosition('hometeam', 'LD'),
            'CLD' => $this->countStrengthForPosition('hometeam', 'CLD'),
            'CD' => $this->countStrengthForPosition('hometeam', 'CD'),
            'CRD' => $this->countStrengthForPosition('hometeam', 'CRD'),
            'RD' => $this->countStrengthForPosition('hometeam', 'RD'),
            'LM' => $this->countStrengthForPosition('hometeam', 'LM'),
            'CLM' => $this->countStrengthForPosition('hometeam', 'CLM'),
            'CM' => $this->countStrengthForPosition('hometeam', 'CM'),
            'CRM' => $this->countStrengthForPosition('hometeam', 'CRM'),
            'RM' => $this->countStrengthForPosition('hometeam', 'RM'),
            'LF' => $this->countStrengthForPosition('hometeam', 'LF'),
            'CLF' => $this->countStrengthForPosition('hometeam', 'CLF'),
            'CF' => $this->countStrengthForPosition('hometeam', 'CF'),
            'CRF' => $this->countStrengthForPosition('hometeam', 'CRF'),
            'RF' => $this->countStrengthForPosition('hometeam', 'RF'),
        ]);
        //dd($this->countStrengthForPosition('hometeam', 'CD'));
        /*        dd([
                    'CD' => $this->hometeamLineup['CD'],
                    'CLD' => $this->hometeamLineup['CLD'],
                    'CRD' => $this->hometeamLineup['CRD'],
                    'LD counted' => $this->countStrengthForPosition('hometeam', 'CD'),
                ]);*/

        /* Divide strength into areas
         * LD = 75% + 25% from CLD
         * CLD = 50% + 25% from LD + 25% from CD
         */

        /*
         * 5x9 positions available = 45, 3 roles for every position = 135 variations + goalie
         * Positions: (offensive / defensive role is possible in every position)
         *
         * Left Defender (LDO, LDM, LDD)
         * Centre Left Defender
         * Centre Defender
         * Centre Right Defender
         * Right Defender
         *
         * Left midfielder
         * Centre left midfielder
         * Centre midfielder
         * Centre right midfielder
         * Right midfielder
         *
         * Left forward
         * Centre left forward
         * Centre forward
         * Centre right forward
         * Right forward
         */

        /*
         * Positions:
         *
         * (SW) (Sweeper) Sweeper - Sweep up balls behind defensive line, rarely meets a forward
         *  - Hold Position, Dribble Less, Shoot Less Often
         * (LI) (Sweeper) Libero - Sweep up balls behind defensive line, helps the midfield in possession
         *  - More Risky Passes, (if attack Get Further Forward, Shoot More Often )
         * (CD) (Centre back) Central Defender - Stop the attackers, mark forwards, clear ball when required.
         *  - Hold Position, Dribble Less, Shoot Less Often
         * (BPD) (Centre back) Ball Playing Defender - CD + long passes to launch counter attacks.
         *  - Hold Position, More Risky Passes
         * (DCB) (Centre back) Defensive Centre Back - CD + tackling more + prevent ball from getting into the area.
         * - Hold Position, Dribble Less, Shoot Less Often, More Direct Passes, Fewer Risky Passes
         * (DM) (Defensive midfielder) Defensive Midfielder - Help defenders when out of possession, help creative midfielders when in possession.
         * - Hold Position, Dribble Less
         * (DLP) (Centre back, Defensive midfielder) Deep Lying Playmaker - Focus on pinpoint offensive passes, helps defeners when out of possession.
         * - Hold Position, Shoot Less Often
         * (BWM) (Defensive midfielder, Centre midfielder) Ball Winning Midfielder - Focus on winning balls, need technique to keep ball when in possession.
         * - Close Down Much More, Tackle Harder
         * (AM) (Defensive midfielder) Anchor Man -
         */

        $this->playGame();
    }

    public function countStrengthForPosition($team, $position)
    {
        $sidePositions = ['LD', 'RD', 'LM', 'RM', 'LF', 'RF'];

        if (in_array($position, $sidePositions)) {
            //dd($position . ' is a side position');
            $posVal = $this->{$team . 'Lineup'}[$position][$position] * .75;
            $assistVal = $this->{$team . 'Lineup'}['C' . $position]['C' . $position] * .2;

            return $posVal + $assistVal;
        }

        if (in_array($position, ['CD', 'CLD', 'CRD', 'CM', 'CLM', 'CRM', 'CF', 'CLF', 'CRF'])) {

            $posVal = $this->{$team . 'Lineup'}[$position][$position] * .6;

            $key = array_search($position, array_keys($this->{$team . 'Lineup'}));
            $assisting1 = array_slice($this->{$team . 'Lineup'}, $key - 1, 1);
            $assisting2 = array_slice($this->{$team . 'Lineup'}, $key + 1, 1);

            $ass1pos = array_keys($assisting1)[0];
            $ass2pos = array_keys($assisting2)[0];

            if (in_array($ass1pos, $sidePositions)) {
                $a = reset($assisting1);
                if (isset($a[$position])) {
                    $assist1Val = $a[$position] * .25;
                } else {
                    $assist1Val = 0;
                }
            } else {
                $a = reset($assisting1);
                if (isset($a[$position])) {
                    $assist1Val = $a[$position] * .2;
                } else {
                    $assist1Val = 0;
                }
            }

            if (in_array($ass2pos, $sidePositions)) {
                $a = reset($assisting2);
                if (isset($a[$position])) {
                    $assist2Val = $a[$position] * .25;
                } else {
                    $assist2Val = 0;
                }
            } else {
                $a = reset($assisting2);
                if (isset($a[$position])) {
                    $assist2Val = $a[$position] * .2;
                } else {
                    $assist2Val = 0;
                }
            }
        }

        return ($posVal + $assist1Val + $assist2Val);
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

    private
    function createShot($currentTeamKey)
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

    private
    function createAttack($currentTeamKey, $competitorTeamKey, $counter, $isCounter = false)
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

    private
    function createEvent($currentTeamKey, $competitorTeamKey, $minute)
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

    private
    function startEvents()
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

    private
    function setEvents()
    {
        $events = rand(5, 25);

        for ($i = 0; $i < $events; $i++) {
            $minute = rand(1, 90);
            $this->eventMinutes[] = $minute;
        }

        sort($this->eventMinutes);
    }

    private
    function setWinningChances()
    {
        $totalChance = 0;

        list($homeChance, $totalChance, $homeBenchChance) = $this->calculateChanceForTeam($totalChance, 'hometeam');
        list($awayChance, $totalChance, $awayBenchChance) = $this->calculateChanceForTeam($totalChance, 'awayteam');

        $this->chances['hometeam'] = ($homeChance / $totalChance) + 0.0075; // home advantage
        $this->chances['awayteam'] = ($awayChance / $totalChance) - 0.0075; // away disadvantage
    }

    private
    function setWeightBasedOnTactics()
    {
        $this->attacking['hometeam'] = $this->tactics['hometeam'] * $this->chances['hometeam'];
        $this->defending['hometeam'] = (100 - $this->tactics['hometeam']) * $this->chances['hometeam'];

        $this->attacking['awayteam'] = $this->tactics['awayteam'] * $this->chances['awayteam'];
        $this->defending['awayteam'] = (100 - $this->tactics['awayteam']) * $this->chances['awayteam'];
    }

    private
    function setTactics()
    {
        $this->tactics['hometeam'] = $this->getTactic();
        $this->tactics['awayteam'] = $this->getTactic();

        $this->setWeightBasedOnTactics();
    }

    private
    function getTactic()
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
    protected
    function calculateChanceForTeam($totalChance, $team): array
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

    public
    function renderEvents()
    {
        $data = '<h1>' . $this->hometeamName . ' ' . $this->score['hometeam'] . '-' . $this->score['awayteam'] . ' ' . $this->awayteamName . '</h1>';

        foreach ($this->events as $event) {
            $ev = str_replace('hometeam', $this->hometeamName, $event['event']);
            $ev = str_replace('awayteam', $this->awayteamName, $ev);
            $data .= $event['time'] . ' min : ' . $ev . '<br />';
        }

        return $data;
    }

}