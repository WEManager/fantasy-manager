<?php

declare(strict_types=1);

namespace App\Engines;

use App\Models\Fixture;
use App\Models\Lineup;
use App\Models\Player;

final class GameEngine
{
    public $gameId;

    public $hometeamName = 'Barcelona';

    public $awayteamName = 'Espanyol';

    public $chances = ['hometeam' => 0, 'awayteam' => 0];

    public $score = ['hometeam' => 0, 'awayteam' => 0];

    private $hometeam;

    private $awayteam;

    private $tactics = ['hometeam' => 50, 'awayteam' => 50];

    private $attacking = ['hometeam' => 0, 'awayteam' => 0];

    private $defending = ['hometeam' => 0, 'awayteam' => 0];

    private $eventMinutes = [];

    private $events = [];

    private $positionFacing = [
        'LD' => 'RF',
        'CLD' => 'CRF',
        'CD' => 'CF',
        'CRD' => 'CLF',
        'RD' => 'LF',

        'LM' => 'RM',
        'CLM' => 'CRM',
        'CM' => 'CM',
        'CRM' => 'CLM',
        'RM' => 'LM',

        'LF' => 'RD',
        'CLF' => 'CRD',
        'CF' => 'CD',
        'CRF' => 'CLD',
        'RF' => 'LD',
    ];

    private $hometeamSkills = [];

    private $awayteamSkills = [];

    private static $availablePositions = [
        'GK',
        'LD', 'CLD', 'CD', 'CRD', 'RD',
        'LM', 'CLM', 'CM', 'CRM', 'RM',
        'LF', 'CLF', 'CF', 'CRF', 'RF',
    ];

    public function __construct($gameId = null)
    {
        $this->gameId = $gameId;

        if (! is_null($gameId)) {
            $game = Fixture::find($gameId);

            $homeLineup = Lineup::where('club_id', $game->hometeam_id)->first();
            $awayLineup = Lineup::where('club_id', $game->awayteam_id)->first();

            $this->setLineup('hometeam', $homeLineup);
            $this->setLineup('awayteam', $awayLineup);

        }

        $this->setAreaSkills();

        $this->playGame();

        /*$this->hometeamLineup = [
            'GK' => $goalKeeper->goalkeeping['GK'],

            'LD' => $defender->wide_defending,
            'CLD' => null, // no player
            'CD' => $defender->central_defending,
            'CRD' => null,
            'RD' => $defender->wide_defending,

            'LM' => $midfielder->wide_midfielder,
            'CLM' => $midfielder->central_midfielder,
            'CM' => $midfielder->central_midfielder,
            'CRM' => $midfielder->central_midfielder,
            'RM' => $midfielder->wide_midfielder,

            'LF' => $forward->wide_attacker,
            'CLF' => null,
            'CF' => $forward->central_attacker,
            'CRF' => null,
            'RF' => $forward->wide_attacker,
        ];*/

        /*$players = 0;
        foreach ($this->hometeamLineup as $value) {
            if (!is_null($value)) {
                $players++;
            }
        }*/

        // dd($this->awayteamLineup);

        /*
         * LD vs RF
         * CLD vs CRF
         * CD vs CF
         * CRD vs CLF
         * RD vs LF
         *
         * LM vs RM
         * CLM vs CRM
         * CM vs CM
         * CRM vs CLM
         * RM vs LM
         *
         * LF vs RD
         * CLF vs CRD
         * CF vs CD
         * CRF vs CLD
         * RF vs LD
         */

        /*dd([
            'GK vs GK' => $this->hometeamLineup['GK'] . ' vs ' . $this->awayteamLineup['GK'],

            'LD vs RF' =>  $this->countStrengthForPosition('hometeam', 'LD') . ' vs ' . $this->countStrengthForPosition('awayteam', 'RF'),
            'CLD vs CRF' =>  $this->countStrengthForPosition('hometeam', 'CLD') . ' vs ' . $this->countStrengthForPosition('awayteam', 'CRF'),
            'CD vs CF' =>  $this->countStrengthForPosition('hometeam', 'CD') . ' vs ' . $this->countStrengthForPosition('awayteam', 'CF'),
            'CRD vs CLF' =>  $this->countStrengthForPosition('hometeam', 'CRD') . ' vs ' . $this->countStrengthForPosition('awayteam', 'CLF'),
            'RD vs LF' =>  $this->countStrengthForPosition('hometeam', 'RD') . ' vs ' . $this->countStrengthForPosition('awayteam', 'LF'),

            'LM vs RM' =>  $this->countStrengthForPosition('hometeam', 'LM') . ' vs ' . $this->countStrengthForPosition('awayteam', 'RM'),
            'CLM vs CRM' =>  $this->countStrengthForPosition('hometeam', 'CLM') . ' vs ' . $this->countStrengthForPosition('awayteam', 'CRM'),
            'CM vs CM' =>  $this->countStrengthForPosition('hometeam', 'CM') . ' vs ' . $this->countStrengthForPosition('awayteam', 'CM'),
            'CRM vs CLM' =>  $this->countStrengthForPosition('hometeam', 'CRM') . ' vs ' . $this->countStrengthForPosition('awayteam', 'CLM'),
            'RM vs LM' =>  $this->countStrengthForPosition('hometeam', 'RM') . ' vs ' . $this->countStrengthForPosition('awayteam', 'LM'),

            'LF vs RD' =>  $this->countStrengthForPosition('hometeam', 'LF') . ' vs ' . $this->countStrengthForPosition('awayteam', 'RD'),
            'CLF vs CRD' =>  $this->countStrengthForPosition('hometeam', 'CLF') . ' vs ' . $this->countStrengthForPosition('awayteam', 'CRD'),
            'CF vs CD' =>  $this->countStrengthForPosition('hometeam', 'CF') . ' vs ' . $this->countStrengthForPosition('awayteam', 'CD'),
            'CRF vs CLD' =>  $this->countStrengthForPosition('hometeam', 'CRF') . ' vs ' . $this->countStrengthForPosition('awayteam', 'CLD'),
            'RF vs LD' =>  $this->countStrengthForPosition('hometeam', 'RF') . ' vs ' . $this->countStrengthForPosition('awayteam', 'LD'),
        ]);*/

        /*dd([
            'hometeam counted on positions' => 'true',
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
        ]);*/

        /*        dd([
                    //'players' => $players,
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
                ]);*/
        // dd($this->countStrengthForPosition('hometeam', 'CD'));
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

        // $this->playGame();
    }

    /*
     * TODO: Applicate the tactics to be reflected in pitch area skills
     */

    public function playGame()
    {
        $this->setWinningChances();
        /* $this->setTactics(); */
        $this->setEvents();
        $this->startEvents();

        // echo $this->score['hometeam'] . ' - ' . $this->score['awayteam'] . '\\n';
        // echo $this->chances['hometeam'] . '% : ' . $this->chances['awayteam'] . '%\\n';
    }

    public function makeGoalKick($currentTeamKey)
    {
        $competitorTeamKey = ($currentTeamKey === 'hometeam') ? 'awayteam' : 'hometeam';

        $playerName = $this->{$currentTeamKey}['GK']->full_name;

        $startingPlaySkills = $this->{$currentTeamKey}['GK']->kicking + $this->{$currentTeamKey}['GK']->passing;

        // Randomize if keeper is kicking or passing out the ball
        // TODO: Implement decision based on tactics - right now based on keeper skills
        $kickOrPass = rand(1, $startingPlaySkills);

        if ($kickOrPass <= $this->{$currentTeamKey}['GK']->kicking) {

            $event = CommentsEngine::goalkeeper_kicks_the_ball($playerName).' ';

            // Decide what part the ball goes to
            $possiblePositions = ['LF', 'CF', 'RF', 'LM', 'CLM', 'CM', 'CRM', 'RM'];
            shuffle($possiblePositions);

            if (isset($this->{$competitorTeamKey}[$this->positionFacing[$possiblePositions[0]]])) {
                // Person model
                $duelant = $this->{$competitorTeamKey}[$this->positionFacing[$possiblePositions[0]]];
                $duelantDisadvantage = false;
            } else {
                // Get alternative duelant and add disadvantage for being out of position
                $duelant = $this->getNearbyPlayer($this->positionFacing[$possiblePositions[0]], $competitorTeamKey);
                $duelantDisadvantage = true;
            }

            [$player, $playerDisadvantage] = $this->getPlayerOnPitchAtArea($currentTeamKey, $possiblePositions[0]);

            // Heading duels on midfield or offense
            $event .= GameDuelsEngine::headingDuelBetween($player, $duelant, $playerDisadvantage, $duelantDisadvantage);

            return $event;
        }
        // TODO: Decide what part the ball goes to
        $possiblePositions = ['CLM', 'CM', 'CRM', 'LD', 'CLD', 'CD', 'CRD', 'RD'];
        shuffle($possiblePositions);

        [$player, $playerDisadvantage] = $this->getPlayerOnPitchAtArea($currentTeamKey, $possiblePositions[0]);

        // dd([$player->full_name, $playerDisadvantage]);

        // TODO: Defenders starting attack or passing back home
        return CommentsEngine::goalkeeper_passes_the_ball_short($playerName);

    }

    public function makeDefensivePass($currentTeamKey, $pos)
    {
        $player = false;
        if (isset($this->{$currentTeamKey}[$pos])) {
            $player = $this->{$currentTeamKey}[$pos];
        }

        if ($player) {
            shuffle(self::$availablePositions);

            if (mb_strlen(self::$availablePositions[0]) === 2) {
                $keyLetter = self::$availablePositions[0][1];
            } else {
                $keyLetter = self::$availablePositions[0][2];
            }

            // Short pass to another defender
            if ($keyLetter === 'D') {
                // Is pass successful?
                // TODO: Should depend upon opponent tactics regarding press
                // Create a new "makeDefensivePass?"
                if (rand(1, 100) < $player->passing) {
                    return CommentsEngine::passes($player);
                }

                return CommentsEngine::bad_pass($player);

            }

            // Pass from defender to midfield
            if ($keyLetter === 'M') {
                dd($player->full_name.' passar upp bollen på mittfältet');
            }
            // Pass from defender to forwards
            if ($keyLetter === 'F') {
                dd($player->full_name.' slår en långboll mot forwardsen');
            }

        } else {
            dd('De passar mellan försvararna i backlinjen.');
        }

        dd([
            $pos,
            $player,
            'Pass in the defense',
        ]);
    }

    public function makeMidfieldPass($currentTeamKey, $pos)
    {
        $player = false;
        if (isset($this->{$currentTeamKey}[$pos])) {
            $player = $this->{$currentTeamKey}[$pos];
        }

        if ($player) {
            dd($player->full_name.' passar på mittfältet.');
        } else {
            dd('De tar sig fram genom mittfältet.');
        }

        dd([
            $pos,
            $player,
            'Pass in the midfield',
        ]);
    }

    public function makeForwardPlay($currentTeamKey, $pos)
    {
        $player = false;
        if (isset($this->{$currentTeamKey}[$pos])) {
            $player = $this->{$currentTeamKey}[$pos];
        }

        if ($player) {
            dd($player->full_name.' har bollen i anfall.');
        } else {
            dd('De spelar runt bollen i anfallet.');
        }

        dd([
            $pos,
            $player,
            'Forwards got the ball',
        ]);
    }

    public function renderEvents()
    {
        $data = '<h1>'.$this->hometeamName.' '.$this->score['hometeam'].'-'.$this->score['awayteam'].' '.$this->awayteamName.'</h1>';

        foreach ($this->events as $event) {
            $ev = str_replace('hometeam', $this->hometeamName, $event['event']);
            $ev = str_replace('awayteam', $this->awayteamName, $ev);
            $data .= $event['time'].' min : '.$ev.'<br />';
        }

        return $data;
    }

    /*
     * Get a player that plays nearby this position
     *
     * @return Person | null
     */
    public function getNearbyPlayer($position, $team): ?Player
    {
        // If pos is CD, nearby is CRD and CLD
        // If pos is CRD, nearby is CD and RD
        // If pos is CRM, nearby is CM and RM
        // If pos is CLD, nearby is LD and CD
        // If pos is DL, nearby is CLD
        // If pos is DR, nearby is CRD

        if (mb_strlen($position) === 3) {
            $nearbyPos = [mb_substr($position, -2), 'C'.mb_substr($position, -1)];
            shuffle($nearbyPos);
        } elseif (mb_substr($position, 0, 1) === 'C') {
            $nearbyPos = ['CR'.mb_substr($position, -1), 'CL'.mb_substr($position, -1)];
            shuffle($nearbyPos);
        } else {
            $nearbyPos = ['C'.$position];
        }

        if (isset($this->{$team}[$nearbyPos[0]])) {
            return $this->{$team}[$nearbyPos[0]];
        }
        if (isset($nearbyPos[1]) && isset($this->{$team}[$nearbyPos[1]])) {
            return $this->{$team}[$nearbyPos[1]];
        }

        return null;

    }

    private function setLineup($team, Lineup $lineup)
    {
        $this->{$team.'Lineup'}[$lineup->position_1] = Player::find($lineup->player_1);
        $this->{$team.'Lineup'}[$lineup->position_2] = Player::find($lineup->player_2);
        $this->{$team.'Lineup'}[$lineup->position_3] = Player::find($lineup->player_3);
        $this->{$team.'Lineup'}[$lineup->position_4] = Player::find($lineup->player_4);
        $this->{$team.'Lineup'}[$lineup->position_5] = Player::find($lineup->player_5);
        $this->{$team.'Lineup'}[$lineup->position_6] = Player::find($lineup->player_6);
        $this->{$team.'Lineup'}[$lineup->position_7] = Player::find($lineup->player_7);
        $this->{$team.'Lineup'}[$lineup->position_8] = Player::find($lineup->player_8);
        $this->{$team.'Lineup'}[$lineup->position_9] = Player::find($lineup->player_9);
        $this->{$team.'Lineup'}[$lineup->position_10] = Player::find($lineup->player_10);
        $this->{$team.'Lineup'}[$lineup->position_11] = Player::find($lineup->player_11);
        $this->{$team.'Lineup'}['tactics'] = 70;

        $this->tactics[$team] = $this->{$team.'Lineup'}['tactics'];
        // dd([$team . 'Lineup' => $this->{$team . 'Lineup'}]);

        foreach (self::$availablePositions as $position) {
            if (! array_key_exists($position, $this->{$team.'Lineup'})) {
                $this->{$team.'Lineup'}[$position] = null;
            } else {
                $this->{$team}[$position] = $this->{$team.'Lineup'}[$position];
                $this->{$team.'Lineup'}[$position] = $this->getPlayerPositionSkills($this->{$team.'Lineup'}[$position], $position)[$position];
            }
        }
    }

    private function setAreaSkills()
    {

        $this->hometeamAreaSkills = [
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
        ];

        $this->awayteamAreaSkills = [
            'GK' => $this->awayteamLineup['GK'],
            'LD' => $this->countStrengthForPosition('awayteam', 'LD'),
            'CLD' => $this->countStrengthForPosition('awayteam', 'CLD'),
            'CD' => $this->countStrengthForPosition('awayteam', 'CD'),
            'CRD' => $this->countStrengthForPosition('awayteam', 'CRD'),
            'RD' => $this->countStrengthForPosition('awayteam', 'RD'),
            'LM' => $this->countStrengthForPosition('awayteam', 'LM'),
            'CLM' => $this->countStrengthForPosition('awayteam', 'CLM'),
            'CM' => $this->countStrengthForPosition('awayteam', 'CM'),
            'CRM' => $this->countStrengthForPosition('awayteam', 'CRM'),
            'RM' => $this->countStrengthForPosition('awayteam', 'RM'),
            'LF' => $this->countStrengthForPosition('awayteam', 'LF'),
            'CLF' => $this->countStrengthForPosition('awayteam', 'CLF'),
            'CF' => $this->countStrengthForPosition('awayteam', 'CF'),
            'CRF' => $this->countStrengthForPosition('awayteam', 'CRF'),
            'RF' => $this->countStrengthForPosition('awayteam', 'RF'),
        ];

    }

    private function getPlayerPositionSkills($player, $position)
    {
        if ($position === 'GK') {
            return $player->goalkeeping;
        }
        if (in_array($position, ['LD', 'RD'])) {
            return $player->wide_defending;
        }
        if (in_array($position, ['LM', 'RM'])) {
            return $player->wide_midfielder;
        }
        if (in_array($position, ['LF', 'RF'])) {
            return $player->wide_attacker;
        }
        if (in_array($position, ['CLD', 'CD', 'CRD'])) {
            return $player->central_defending;
        }
        if (in_array($position, ['CLM', 'CM', 'CRM'])) {
            return $player->central_midfielder;
        }
        if (in_array($position, ['CLF', 'CF', 'CRF'])) {
            return $player->central_attacker;
        }
    }

    private function countStrengthForPosition($team, $position)
    {
        $sidePositions = ['LD', 'RD', 'LM', 'RM', 'LF', 'RF'];
        $centerPositions = ['CD', 'CLD', 'CRD', 'CM', 'CLM', 'CRM', 'CF', 'CLF', 'CRF'];

        if (in_array($position, $sidePositions)) {
            // dd($position . ' is a side position');
            $posVal = isset($this->{$team.'Lineup'}[$position]) ? $this->{$team.'Lineup'}[$position] * .75 : null;
            $assistVal = isset($this->{$team.'Lineup'}['C'.$position]) ? $this->{$team.'Lineup'}['C'.$position] * .2 : null;

            // dump('C' . $position . ' is assisting ' . $position);

            return $posVal + $assistVal;
        }

        if (in_array($position, $centerPositions)) {

            $posVal = isset($this->{$team.'Lineup'}[$position]) ? $this->{$team.'Lineup'}[$position] * .6 : 0;

            $key = array_search($position, array_keys($this->{$team.'Lineup'}));
            $assisting1 = array_slice($this->{$team.'Lineup'}, $key - 1, 1);
            $assisting2 = array_slice($this->{$team.'Lineup'}, $key + 1, 1);

            $ass1pos = array_keys($assisting1)[0];
            $ass2pos = array_keys($assisting2)[0];

            if (in_array($ass1pos, $sidePositions)) {
                $assist1Val = $this->strengthForNearbyPosition($assisting1, $position, 25);
            } else {
                $assist1Val = $this->strengthForNearbyPosition($assisting1, $position, 20);
            }

            if (in_array($ass2pos, $sidePositions)) {
                $assist2Val = $this->strengthForNearbyPosition($assisting2, $position, 25);
            } else {
                $assist2Val = $this->strengthForNearbyPosition($assisting2, $position, 20);
            }
        }

        /*if ($position == 'CLD') {
            dump([$ass1pos . ' is assisting ' . $position, $ass1pos . ' value is ' . $assist1Val, '$assist1Val = $this->strengthForNearbyPosition( $assisting1,' . $position . ', %);', $assisting1]);
            dump([$ass2pos . ' is assisting ' . $position, $ass2pos . ' value is ' . $assist2Val, '$assist2Val = $this->strengthForNearbyPosition( $assisting2,' . $position . ', %);', $assisting2['CD']]);
            dump($position . ' = ' . $posVal . '+' . $assist1Val . '+' . $assist2Val);
            dump('Slut på assvärden för ' . $position);
        }*/

        return $posVal + $assist1Val + $assist2Val;
    }

    // Calculate winning chances based on pitch area skills
    private function setWinningChances()
    {
        $homeChance = 0;
        $awayChance = 0;
        $totalChance = 0;

        foreach ($this->hometeamAreaSkills as $position => $areaSkill) {
            $homeChance += $areaSkill;
            $totalChance += $areaSkill;
        }
        foreach ($this->awayteamAreaSkills as $position => $areaSkill) {
            $awayChance += $areaSkill;
            $totalChance += $areaSkill;
        }

        $this->chances['hometeam'] = ($homeChance / $totalChance) + 0.0075; // home advantage
        $this->chances['awayteam'] = ($awayChance / $totalChance) - 0.0075; // away disadvantage
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

    private function createEvent($currentTeamKey, $competitorTeamKey, $minute)
    {
        // dd([$currentTeamKey, $competitorTeamKey, $minute]);

        $areas = self::$availablePositions;
        shuffle($areas);
        $teamPart = mb_substr($areas[0], -1); // K = Keeper, D = Defense, M = Midfield, F = Forward

        // dd($this->makeGoalKick($competitorTeamKey));

        if ($teamPart === 'K') {
            $event = $this->makeGoalKick($currentTeamKey);
        }
        if ($teamPart === 'D') {
            $event = $this->makeDefensivePass($currentTeamKey, $areas[0]);
        }
        if ($teamPart === 'M') {
            $event = $this->makeMidfieldPass($currentTeamKey, $areas[0]);
        }
        if ($teamPart === 'F') {
            $event = $this->makeForwardPlay($currentTeamKey, $areas[0]);
        }

        /*dd([
            $this->{$currentTeamKey . 'AreaSkills'},
            substr($areas[0], -1)
        ]);*/

        /*if ($this->tactics[$currentTeamKey] > 80) {
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
        }*/

        return [
            'time' => $minute,
            'team' => $currentTeamKey,
            'event' => $event,
        ];
    }

    /**
     * @param  string  $currentTeamKey
     * @param  string  $position
     */
    private function getPlayerOnPitchAtArea($currentTeamKey, $position): array
    {
        if (isset($this->{$currentTeamKey}[$position])) {
            // Person model
            $player = $this->{$currentTeamKey}[$position];
            $playerDisadvantage = false;
        } else {
            // Get alternative player in nearby position and add disadvantage for being out of position
            $player = $this->getNearbyPlayer($position, $currentTeamKey);
            $playerDisadvantage = true;
        }

        return [$player, $playerDisadvantage];
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
        $event = (! $isCounter) ? $currentTeamKey.' '.CommentsEngine::is_attacking().'. ' : '';

        // anfall med många man, väldigt stor risk för kontring
        $eventSum = rand(0, ceil($this->attacking[$currentTeamKey] + $this->defending[$competitorTeamKey]));

        // $currentTeam får anfall
        if ($eventSum <= $this->attacking[$currentTeamKey]) {
            $event .= CommentsEngine::is_in_attack_mode($currentTeamKey).' '.$this->createShot($currentTeamKey);
        } elseif (! $counter) {
            $event .= CommentsEngine::is_in_attack_mode($currentTeamKey).' '.CommentsEngine::gets_interrupted();
        } else {
            $event .= CommentsEngine::is_in_attack_mode($currentTeamKey).' '.CommentsEngine::opposition_gets_the_ball($competitorTeamKey).' ';

            $event .= CommentsEngine::counter_attack($competitorTeamKey).' '.$this->createAttack($competitorTeamKey, $currentTeamKey, rand(0, 1) < 1, true);
        }

        return $event;
    }

    private function strengthForNearbyPosition($assisting, $position, $percent): float
    {
        $a = reset($assisting);
        if (! is_null($a)) {
            $assistVal = $a * ($percent / 100);
        } else {
            $assistVal = 0;
        }

        return $assistVal;
    }
}
