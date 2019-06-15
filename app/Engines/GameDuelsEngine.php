<?php

namespace App\Engines;

class GameDuelsEngine
{
    public static function headingDuelBetween($player1, $player2, $player1oopDisadvantage = false, $player2oopDisadvantage = false)
    {
        if (is_null($player1) && is_null($player2)) {
            return CommentsEngine::ball_out_of_play();
        } elseif (is_null($player1)) {
            // TODO: player1 should do something when won the ball, pass, shoot, dribble
            return CommentsEngine::won_ball_without_duel($player2);
        } elseif (is_null($player2)) {
            // TODO: player2 should do something when won the ball, pass, shoot, dribble
            return CommentsEngine::won_ball_without_duel($player1);
        } else {

            list($player1HeadingSkills, $totalSkills) = self::calculatePlayersHeadingSkills($player1, $player2, $player1oopDisadvantage, $player2oopDisadvantage);
            $randomWinner = rand(1, $totalSkills);

            // if true, player1 wins the duel else player2
            if ($randomWinner <= $player1HeadingSkills) {
                // TODO: Implement statistics passing success + 1
                // TODO: player1 should do something when won the ball, pass, shoot, dribble
                return CommentsEngine::won_ball_in_heading_duel_against($player1, $player2);
            } else {
                // TODO: Implement statistics breaking passes success + 1
                // TODO: player2 should do something when won the ball, pass, shoot, dribble
                return CommentsEngine::won_ball_in_heading_duel_against($player2, $player1);
            }
        }
    }

    /**
     * @param $player1
     * @param $player2
     * @param $player1oopDisadvantage
     * @param $player2oopDisadvantage
     * @return array
     */
    protected static function calculatePlayersHeadingSkills($player1, $player2, $player1oopDisadvantage, $player2oopDisadvantage): array
    {
        $player1MentalSkills = (($player1->teamwork + $player1->vision + $player1->flair) / 3) * $player1->work_rate / 100;
        $player2MentalSkills = (($player2->teamwork + $player2->vision + $player2->flair) / 3) * $player2->work_rate / 100;

        // If players are out of percentage, only give them 40% of their original skill
        $player1Percentage = ($player1oopDisadvantage) ? .4 : 1;
        $player2Percentage = ($player2oopDisadvantage) ? .4 : 1;

        $player1HeadingSkills = $player1->heading + $player1->jumping_reach + ($player1->technique / 2) * $player1MentalSkills * $player1Percentage;
        $player2HeadingSkills = $player2->heading + $player2->jumping_reach + ($player2->technique / 2) * $player2MentalSkills * $player2Percentage;

        $totalSkills = $player1HeadingSkills + $player2HeadingSkills;
        return array($player1HeadingSkills, $totalSkills);
    }

}