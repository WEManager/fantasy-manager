<?php

namespace App\Engines;

use function Psy\sh;

class CommentsEngine
{

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
            'the ' . $side[0] . ' flank',
            'the ' . $side[0] . 'wing area',
        ];
        shuffle($part);

        $isInAttackMode = [
            $currentTeamKey . ' are still attacking on the offensive part of the pitch.',
            'They are still attacking on ' . $part[0] . '.',
            $currentTeamKey . ' are working hard outside the penalty area.',
            'They are working hard outside the penalty area.',
            $currentTeamKey . ' are passing the ball on ' . $part[0] . '.',
            'They are passing the ball on ' . $part[0] . '.',
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
            'But whoops, ' . $lostBall[0] . '.',
            'But there ' . $lostBall[0] . '.',
            'But the ball rolls over the line after ' . $missed[0] . '.',
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
            'saw what was coming, acted accordingly'
        ];
        shuffle($read);

        $oppositionInCharge = [
            'and the opposition is now in charge',
            'and the opposition is now in control',
            'and the opposition is now in possession',
            'and ' . $theOppositionTeamKey . ' is now in charge',
            'and ' . $theOppositionTeamKey . ' is now in control',
            'and ' . $theOppositionTeamKey . ' is now in possession',
            'and took the ball into their belongings',
        ];
        shuffle($oppositionInCharge);

        $opposition = [
            'There the opposition',
            'Now the opposition',
            'There ' . $theOppositionTeamKey,
            'Now ' . $theOppositionTeamKey,
            'And... now ' . $theOppositionTeamKey,
            'There... ' . $theOppositionTeamKey,
        ];
        shuffle($opposition);

        $oppositionGetsTheBall = [
            'Oh! They ' . $lostBall[0] . ' ' . $theChargeOfBall[0] . ' ' . $oppositionInCharge[0] . '.',
            $opposition[0] . ' ' . $read[0] . ' ' . $oppositionInCharge[0] . '.',
        ];
        shuffle($oppositionGetsTheBall);

        return $oppositionGetsTheBall[0];
    }

    public static function counter_attack($theOppositionTeamKey) {

        $counterAttack = [
            $theOppositionTeamKey . ' is going for a counter attack! ',
            $theOppositionTeamKey . ' is rapidly turning into a counter attack! ',
            $theOppositionTeamKey . ' is starting a counter! ',
        ];
        shuffle($counterAttack);

        return $counterAttack[0];
    }

    public static function shoots() {
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

    public static function missed_shot() {
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

    public static function saved_shot() {
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

    public static function goal() {
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