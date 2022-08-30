<?php

namespace App\Observers;

use App\Models\Player;
use App\Models\PlayerContract;

class PlayerObserver {
    private $person;

    public function creating(Player $person){
        $this->person = &$person;

        // if ($this->person->club_id) {
        //     PlayerContract::create([
        //         'person_id' => $this->person->id,
        //         'club_id', $this->person->club_id,
        //         'wage' => rand(100, 999)
        //     ]);
        //     unset($this->person->club_id);
        // }

        $this->generatePersonalDetails();
        // $this->generateGoalkeepingSkills();
        // $this->generateTechnicalSkills();
        // $this->generateMentalSkills();
        // $this->generatePhysicalSkills();
        // $this->generateHiddenSkills();
    }

    public function generatePersonalDetails() {
        $this->person->form = rand(1, 99);
        // $this->person->birthday = rand(1, 91);

        // $length = rand(160, 210);
        // $diff = rand(1, 15);

        // if ($length > 190) {
        //     $this->person->length = $length - $diff;
        // } elseif ($length < 170) {
        //     $this->person->length = $length + $diff;
        // } else {
        //     $this->person->length = $length;
        // }

        // $weight = rand(60, 100);
        // $diff = rand(1, 5);

        // if ($weight < 66) {
        //     $this->person->weight = $weight + $diff;
        // } elseif ($weight > 90) {
        //     $this->person->weight = $weight - $diff;
        // } else {
        //     $this->person->weight = $weight;
        // }

        // $preferredFoot = ['only_right', 'right', 'left', 'only_left'];
        // shuffle($preferredFoot);

        // $this->person->preferred_foot = $preferredFoot[0];

        // // Personality
        // $this->person->ambition = rand(1, 100);
        // $this->person->controversy = rand(1, 100);
        // $this->person->loyalty = rand(1, 100);
        // $this->person->pressure = rand(1, 100);
        // $this->person->professionalism = rand(1, 100);
        // $this->person->sportsmanship = rand(1, 100);
        // $this->person->temperament = rand(1, 100);
    }

    public function generateHiddenSkills() {
        $this->person->adaptability = rand(1, 100);
        $this->person->consistency = rand(1, 100);
        $this->person->dirtiness = rand(1, 100);
        $this->person->important_matches = rand(1, 100);
        $this->person->injury_proneness = rand(1, 100);
        $this->person->versatility = rand(1, 100);
    }

    public function generatePhysicalSkills() {
        $this->person->acceleration = rand(1, 100);
        $this->person->agility = rand(1, 100);
        $this->person->balance = rand(1, 100);
        $this->person->jumping_reach = rand(1, 100);
        $this->person->natural_fitness = rand(1, 100);
        $this->person->pace = rand(1, 100);
        $this->person->stamina = rand(1, 100);
        $this->person->strength = round(rand(1, 100) * ($this->person->weight / 100));
    }

    public function generateMentalSkills() {
        $this->person->aggression = rand(1, 100);
        $this->person->anticipation = rand(1, 100);
        $this->person->bravery = rand(1, 100);
        $this->person->composure = rand(1, 100);
        $this->person->concentration = rand(1, 100);
        $this->person->decisions = rand(1, 100);
        $this->person->determination = rand(1, 100);
        $this->person->flair = rand(1, 100);
        $this->person->leadership = rand(1, 100);
        $this->person->off_the_ball = rand(1, 100);
        $this->person->positioning = rand(1, 100);
        $this->person->teamwork = rand(1, 100);
        $this->person->vision = rand(1, 100);
        $this->person->work_rate = rand(1, 100);
    }

    public function generateTechnicalSkills() {
        $this->person->corners = rand(1, 100);
        $this->person->crossing = rand(1, 100);
        $this->person->dribbling = rand(1, 100);
        $this->person->finishing = rand(1, 100);
        $this->person->first_touch = rand(1, 100);
        $this->person->freekick_taking = rand(1, 100);
        $this->person->heading = rand(1, 100);
        $this->person->long_shots = rand(1, 100);
        $this->person->long_throws = rand(1, 100);
        $this->person->marking = rand(1, 100);
        $this->person->passing = rand(1, 100);
        $this->person->penalty_taking = rand(1, 100);
        $this->person->tackling = rand(1, 100);
        $this->person->technique = rand(1, 100);
    }

    public function generateGoalkeepingSkills() {
        $this->person->aerial_reach = rand(1, 100);
        $this->person->command_of_area = rand(1, 100);
        $this->person->communication = rand(1, 100);
        $this->person->eccentricity = rand(1, 100);
        $this->person->handling = rand(1, 100);
        $this->person->kicking = rand(1, 100);
        $this->person->one_on_ones = rand(1, 100);
        $this->person->reflexes = rand(1, 100);
        $this->person->rushing_out = rand(1, 100);
        $this->person->tendency_to_punch = rand(1, 100);
        $this->person->throwing = rand(1, 100);
    }
}
