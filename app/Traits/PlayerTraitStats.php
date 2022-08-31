<?php

namespace App\Traits;

trait PlayerTraitStats {
  private function getTechnicalValues() {
    return [
      'corners' => $this->corners,
      'crossing' => $this->crossing,
      'dribbling' => $this->dribbling,
      'finishing' => $this->finishing,
      'first_touch' => $this->first_touch,
      'freekick_taking' => $this->freekick_taking,
      'heading' => $this->heading,
      'long_shots' => $this->long_shots,
      'long_throws' => $this->long_throws,
      'marking' => $this->marking,
      'passing' => $this->passing,
      'penalty_taking' => $this->penalty_taking,
      'tackling' => $this->tackling,
      'technique' => $this->technique,
    ];
  }

  private function getMentalValues() {
    return [
      'aggression' => $this->aggression,
      'anticipation' => $this->anticipation,
      'bravery' => $this->bravery,
      'composure' => $this->composure,
      'concentration' => $this->concentration,
      'decisions' => $this->decisions,
      'determination' => $this->determination,
      'flair' => $this->flair,
      'leadership' => $this->leadership,
      'off_the_ball' => $this->off_the_ball,
      'positioning' => $this->positioning,
      'teamwork' => $this->teamwork,
      'vision' => $this->vision,
      'work_rate' => $this->work_rate,
    ];
  }

  private function getPhysicalValues() {
    return [
      'acceleration' => $this->acceleration,
      'agility' => $this->agility,
      'balance' => $this->balance,
      'jumping_reach' => $this->jumping_reach,
      'natural_fitness' => $this->natural_fitness,
      'pace' => $this->pace,
      'stamina' => $this->stamina,
      'strength' => $this->strength,
    ];
  }

  private function getGoalkeepingValues() {
    return [
      'aerial_reach' => $this->aerial_reach,
      'command_of_area' => $this->command_of_area,
      'communication' => $this->communication,
      'eccentricity' => $this->eccentricity,
      'first_touch' => $this->first_touch,
      'handling' => $this->handling,
      'kicking' => $this->kicking,
      'one_on_ones' => $this->one_on_ones,
      'passing' => $this->passing,
      'reflexes' => $this->reflexes,
      'rushing_out' => $this->rushing_out,
      'tendency_to_punch' => $this->tendency_to_punch,
      'throwing' => $this->throwing,
    ];
  }

  public function getTechnicalAttribute() {
    return $this->getTechnicalValues();
  }

  public function getMentalAttribute() {
    return $this->getMentalValues();
  }

  public function getPhysicalAttribute() {
    return $this->getPhysicalValues();
  }
}
