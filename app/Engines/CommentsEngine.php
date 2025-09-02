<?php

declare(strict_types=1);

namespace App\Engines;

final class CommentsEngine
{
    public static function pass_in_defence($player)
    {
        $player = [$player->lastname, $player->full_name];
        shuffle($player);
    }

    public static function goalkeeper_passes_the_ball_short($playerName)
    {
        $player = [__('The goalkeeper'), $playerName];
        shuffle($player);

        $passBallShort = [
            $player[0].__(' is passing the ball short.'),
            $player[0].__(' passes the ball short.'),
            $player[0].__(' passes the ball to his defense.'),
        ];
        shuffle($passBallShort);

        return $passBallShort[0];
    }

    public static function goalkeeper_kicks_the_ball($playerName)
    {
        $player = [__('The goalkeeper'), $playerName];
        shuffle($player);

        $kicksBall = [
            $player[0].__(' kicks the ball upfield.'),
            $player[0].__(' plays the ball long.'),
            $player[0].__(' kicks the ball far up the field.'),
        ];
        shuffle($kicksBall);

        return $kicksBall[0];
    }

    public static function ball_out_of_play()
    {
        $ballOutOfPlay = [
            __('The ball just rolls out over the line.'),
            __('No player was fast enough to get the ball before it rolls out of play.'),
            __('The players did not manage to keep the ball on the pitch.'),
        ];
        shuffle($ballOutOfPlay);

        return $ballOutOfPlay[0];
    }

    public static function won_ball_without_duel($player)
    {
        $player = [$player->lastname, $player->full_name];
        shuffle($player);

        $wonBallWithoutDuel = [
            __('Only :Player met the ball, easy game for him.', ['player' => $player[0]]),
            __('There were no other players than :Player to meet the ball, so he is in possession.', ['player' => $player[0]]),
            __(':Player could pick up the ball without a duel.', ['player' => $player[0]]),
            __('No opponent close to where the ball was dropping down, so easy pick up for :Player.', ['player' => $player[0]]),
        ];
        shuffle($wonBallWithoutDuel);

        return $wonBallWithoutDuel[0];
    }

    public static function won_ball_in_heading_duel_against($winner, $loser)
    {
        $winnerName = [$winner->lastname, $winner->full_name];
        $loserName = [$loser->lastname, $loser->full_name];
        shuffle($winnerName);
        shuffle($loserName);

        if ($winnerName[0] === $loserName[0]) {
            $loserName[0] = $loserName[1];
        }

        $wonBallInHeadingDuel = [
            __(':Winner and :Loser goes up in a duel, this time :Winner gets his head on the ball.', ['winner' => $winnerName[0], 'loser' => $loserName[0]]),
            __(':Loser and :Winner goes up in a duel, this time :Winner gets his head on the ball.', ['winner' => $winnerName[0], 'loser' => $loserName[0]]),
            __(':Winner and :Loser in a duel, :Winner gets up highest and reach the ball in front of :Loser.', ['winner' => $winnerName[0], 'loser' => $loserName[0]]),
            __(':Loser and :Winner in a duel, :Winner gets up highest and reach the ball in front of :Loser.', ['winner' => $winnerName[0], 'loser' => $loserName[0]]),
            __('Wow, both :Loser and :Winner jumps high in the duel, but this time :Winner gets the ball.', ['winner' => $winnerName[0], 'loser' => $loserName[0]]),
            __('Wow, both :Winner and :Loser jumps high in the duel, but this time :Winner gets the ball.', ['winner' => $winnerName[0], 'loser' => $loserName[0]]),
        ];
        shuffle($wonBallInHeadingDuel);

        return $wonBallInHeadingDuel[0];
    }

    public static function is_attacking()
    {
        $isAttacking = [
            'is attacking',
            'is trying to get something done here',
            'is on the offensive',
            'is looking for opportunities',
        ];
        shuffle($isAttacking);

        return $isAttacking[0];
    }

    public static function is_in_attack_mode($currentTeamKey)
    {
        $side = [
            'left',
            'right',
        ];
        shuffle($side);

        $part = [
            'the offensive part of the pitch',
            'the '.$side[0].' flank',
            'the '.$side[0].'wing area',
        ];
        shuffle($part);

        $isInAttackMode = [
            $currentTeamKey.' are still attacking on the offensive part of the pitch.',
            'They are still attacking on '.$part[0].'.',
            $currentTeamKey.' are working hard outside the penalty area.',
            'They are working hard outside the penalty area.',
            $currentTeamKey.' are passing the ball on '.$part[0].'.',
            'They are passing the ball on '.$part[0].'.',
        ];
        shuffle($isInAttackMode);

        return $isInAttackMode[0];
    }

    public static function gets_interrupted()
    {
        $missed = [
            'a missed pass',
            'a missed cross',
            'a bad cross',
            'a bad pass',
            'a misunderstanding',
            'a good read from the defender',
        ];
        shuffle($missed);

        $lostBall = [
            'they lost the ball',
            'they gave the ball away',
            'they missed a pass',
            'the defender read the pass',
        ];
        shuffle($lostBall);

        $getsInterrupted = [
            'But they gets interrupted.',
            'But whoops, '.$lostBall[0].'.',
            'But there '.$lostBall[0].'.',
            'But the ball rolls over the line after '.$missed[0].'.',
        ];
        shuffle($getsInterrupted);

        return $getsInterrupted[0];
    }

    public static function opposition_gets_the_ball($theOppositionTeamKey)
    {
        $lostBall = [
            'lost',
            'got robbed of',
            'do not any longer have',
        ];
        shuffle($lostBall);

        $theChargeOfBall = [
            'the ball',
            'the possession',
            'the control of the ball',
            'the control',
            'the charge',
            'the charge of the ball',
        ];
        shuffle($theChargeOfBall);

        $read = [
            'read the play',
            'read the play very well',
            'read the pass',
            'read the pass very well',
            'saw what was coming, acted accordingly',
        ];
        shuffle($read);

        $oppositionInCharge = [
            'and the opposition is now in charge',
            'and the opposition is now in control',
            'and the opposition is now in possession',
            'and '.$theOppositionTeamKey.' is now in charge',
            'and '.$theOppositionTeamKey.' is now in control',
            'and '.$theOppositionTeamKey.' is now in possession',
            'and took the ball into their belongings',
        ];
        shuffle($oppositionInCharge);

        $opposition = [
            'There the opposition',
            'Now the opposition',
            'There '.$theOppositionTeamKey,
            'Now '.$theOppositionTeamKey,
            'And... now '.$theOppositionTeamKey,
            'There... '.$theOppositionTeamKey,
        ];
        shuffle($opposition);

        $oppositionGetsTheBall = [
            'Oh! They '.$lostBall[0].' '.$theChargeOfBall[0].' '.$oppositionInCharge[0].'.',
            $opposition[0].' '.$read[0].' '.$oppositionInCharge[0].'.',
        ];
        shuffle($oppositionGetsTheBall);

        return $oppositionGetsTheBall[0];
    }

    public static function counter_attack($theOppositionTeamKey)
    {

        $counterAttack = [
            $theOppositionTeamKey.' is going for a counter attack! ',
            $theOppositionTeamKey.' is rapidly turning into a counter attack! ',
            $theOppositionTeamKey.' is starting a counter! ',
        ];
        shuffle($counterAttack);

        return $counterAttack[0];
    }

    public static function shoots()
    {
        $shoots = [
            'Shoots! ',
            'Taking on a shot! ',
            'They try a shot from there! ',
            'Going for a shot from there! ',
            'Going for a shot!! ',
            'A suprising shot! ',
            'A shot! ',
            'A good shot! ',
            'Shoots from there! ',
        ];
        shuffle($shoots);

        return $shoots[0];
    }

    public static function missed_shot()
    {
        $missedShot = [
            'But it goes way wide. ',
            'But it flies way over the bar. ',
            'That was not even close. ',
            'IN THE BAR! ',
            'IN THE CROSSBAR! ',
            'Just wide! ',
            'Just over the bar! ',
            'It curves just outside the post! ',
            'It drops just over the bar! ',
        ];
        shuffle($missedShot);

        return $missedShot[0];
    }

    public static function saved_shot()
    {
        $savedShot = [
            'Saved by the goalkeeper!',
            'The keeper saves that one!',
            'The keeper takes it!',
            'The keeper has full control of that one!',
            'The keeper manage to get it just over the bar!',
            'The keeper manage to get it just outside the post!',
        ];
        shuffle($savedShot);

        return $savedShot[0];
    }

    public static function goal()
    {
        $goal = [
            'GOAAAAL!!',
            'THEY SCOOOORE!!',
            'GOOOAAAALLL!!',
            'GOAL!!',
            'THAT WENT IN, GOOOOAAALLL!!',
        ];
        shuffle($goal);

        return $goal[0];
    }
}
