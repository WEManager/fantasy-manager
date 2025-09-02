<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Player;

final class PlayerObserver
{
    public function creating(Player $player): void
    {

        // if ($this->player->club_id) {
        //     PlayerContract::create([
        //         'person_id' => $this->player->id,
        //         'club_id', $this->player->club_id,
        //         'wage' => rand(100, 999)
        //     ]);
        //     unset($this->player->club_id);
        // }

        $this->generatePlayerDetails();
        // $this->generateGoalkeepingSkills();
        // $this->generateTechnicalSkills();
        // $this->generateMentalSkills();
        // $this->generatePhysicalSkills();
        // $this->generateHiddenSkills();
    }

    public function generatePlayerDetails(): void
    {
        // $this->player->birthday = rand(1, 91);

        // $length = rand(160, 210);
        // $diff = rand(1, 15);

        // if ($length > 190) {
        //     $this->player->length = $length - $diff;
        // } elseif ($length < 170) {
        //     $this->player->length = $length + $diff;
        // } else {
        //     $this->player->length = $length;
        // }

        // $weight = rand(60, 100);
        // $diff = rand(1, 5);

        // if ($weight < 66) {
        //     $this->player->weight = $weight + $diff;
        // } elseif ($weight > 90) {
        //     $this->player->weight = $weight - $diff;
        // } else {
        //     $this->player->weight = $weight;
        // }

        // $preferredFoot = ['only_right', 'right', 'left', 'only_left'];
        // shuffle($preferredFoot);

        // $this->player->preferred_foot = $preferredFoot[0];

        // // Personality
        // $this->player->ambition = rand(1, 100);
        // $this->player->controversy = rand(1, 100);
        // $this->player->loyalty = rand(1, 100);
        // $this->player->pressure = rand(1, 100);
        // $this->player->professionalism = rand(1, 100);
        // $this->player->sportsmanship = rand(1, 100);
        // $this->player->temperament = rand(1, 100);
    }

    public function generateHiddenSkills(): void
    {
        // $this->player->adaptability = rand(1, 100);
        // $this->player->consistency = rand(1, 100);
        // $this->player->dirtiness = rand(1, 100);
        // $this->player->important_matches = rand(1, 100);
        // $this->player->injury_proneness = rand(1, 100);
        // $this->player->versatility = rand(1, 100);
    }

    public function generatePhysicalSkills(): void
    {
        // $this->player->acceleration = rand(1, 100);
        // $this->player->agility = rand(1, 100);
        // $this->player->balance = rand(1, 100);
        // $this->player->jumping_reach = rand(1, 100);
        // $this->player->natural_fitness = rand(1, 100);
        // $this->player->pace = rand(1, 100);
        // $this->player->stamina = rand(1, 100);
        // $this->player->strength = round(rand(1, 100) * ($this->player->weight / 100));
    }

    public function generateMentalSkills(): void
    {
        // $this->player->aggression = rand(1, 100);
        // $this->player->anticipation = rand(1, 100);
        // $this->player->bravery = rand(1, 100);
        // $this->player->composure = rand(1, 100);
        // $this->player->concentration = rand(1, 100);
        // $this->player->decisions = rand(1, 100);
        // $this->player->determination = rand(1, 100);
        // $this->player->flair = rand(1, 100);
        // $this->player->leadership = rand(1, 100);
        // $this->player->off_the_ball = rand(1, 100);
        // $this->player->positioning = rand(1, 100);
        // $this->player->teamwork = rand(1, 100);
        // $this->player->vision = rand(1, 100);
        // $this->player->work_rate = rand(1, 100);
    }

    public function generateTechnicalSkills(): void
    {
        // $this->player->corners = rand(1, 100);
        // $this->player->crossing = rand(1, 100);
        // $this->player->dribbling = rand(1, 100);
        // $this->player->finishing = rand(1, 100);
        // $this->player->first_touch = rand(1, 100);
        // $this->player->freekick_taking = rand(1, 100);
        // $this->player->heading = rand(1, 100);
        // $this->player->long_shots = rand(1, 100);
        // $this->player->long_throws = rand(1, 100);
        // $this->player->marking = rand(1, 100);
        // $this->player->passing = rand(1, 100);
        // $this->player->penalty_taking = rand(1, 100);
        // $this->player->tackling = rand(1, 100);
        // $this->player->technique = rand(1, 100);
    }

    public function generateGoalkeepingSkills(): void
    {
        // $this->player->aerial_reach = rand(1, 100);
        // $this->player->command_of_area = rand(1, 100);
        // $this->player->communication = rand(1, 100);
        // $this->player->eccentricity = rand(1, 100);
        // $this->player->handling = rand(1, 100);
        // $this->player->kicking = rand(1, 100);
        // $this->player->one_on_ones = rand(1, 100);
        // $this->player->reflexes = rand(1, 100);
        // $this->player->rushing_out = rand(1, 100);
        // $this->player->tendency_to_punch = rand(1, 100);
        // $this->player->throwing = rand(1, 100);
    }
}
