<?php

namespace App\Traits;

trait PlayerOveralls
{
  public function getAverage($skills, $name)
  {
    $summary = 0;

    foreach ($skills[$name] as $key => $value) {
      $summary += $value;
    }

    $skills['average_' . $name] = $summary / count($skills[$name]);

    return $skills;
  }

  private function getSkills()
  {
    $skills = [
      'mental' => $this->getMentalValues(),
      'physical' => $this->getPhysicalValues(),
      'technical' => $this->getTechnicalValues(),
    ];

    $skills = $this->getAverage($skills, 'mental');
    $skills = $this->getAverage($skills, 'physical');
    $skills = $this->getAverage($skills, 'technical');

    $skills['average'] = ($skills['average_mental'] + $skills['average_physical'] + $skills['average_technical']) / 3;

    return $skills;
  }

  public function getGoalkeepingAttribute()
  {
    $goalkeeping = [
      'goalkeeping' => $this->getGoalkeepingValues(),
      'mental' => $this->getMentalValues(),
      'physical' => $this->getPhysicalValues(),
    ];


    $goalkeeping = $this->getAverage($goalkeeping, 'goalkeeping');
    $goalkeeping = $this->getAverage($goalkeeping, 'mental');
    $goalkeeping = $this->getAverage($goalkeeping, 'physical');

    $goalkeeping['GK'] = (
      (
        // Give primary goalkeeping values more weight (50% more)
        ($this->aerial_reach + $this->agility + $this->handling + $this->reflexes) * 1.5
        // Divide the weighted values to an average
      ) / 4 +

      // Give the rest of goalkeeping values importance
      $goalkeeping['average_goalkeeping']
      // Divide weighted and default values by 2 to get a combined value
    ) / 2;

    return $goalkeeping;
  }

  public function getCentralDefendingAttribute()
  {
    $skills = $this->getSkills();

    $skills['CD'] = $skills['CLD'] = $skills['CRD'] = (
      (
        // Give primary central defender values more weight (50% more)
        ($this->aerial_reach + $this->tackling + $this->decisions + $this->positioning + $this->strength) * 1.5
        // Divide the weighted values to an average
      ) / 5 +

      // Give the rest of central defender values importance
      $skills['average']
      // Divide weighted and default values by 2 to get a combined value
    ) / 2;

    return $skills;
  }

  public function getWideDefendingAttribute()
  {
    $skills = $this->getSkills();

    $skills['LD'] = $skills['RD'] = (
      (
        // Give primary wide defender values more weight (50% more)
        ($this->dribbling + $this->tackling + $this->positioning + $this->stamina) * 1.5
        // Divide the weighted values to an average
      ) / 4 +

      // Give the rest of wide defender values importance
      $skills['average']
      // Divide weighted and default values by 2 to get a combined value
    ) / 2;

    return $skills;
  }

  public function getCentralMidfielderAttribute()
  {
    $skills = $this->getSkills();

    $skills['CM'] = $skills['CLM'] = $skills['CRM'] = (
      (
        // Give primary central defender values more weight (50% more)
        ($this->passing + $this->decisions + $this->teamwork) * 1.5
        // Divide the weighted values to an average
      ) / 3 +

      // Give the rest of central defender values importance
      $skills['average']
      // Divide weighted and default values by 2 to get a combined value
    ) / 2;

    return $skills;
  }

  public function getWideMidfielderAttribute()
  {
    $skills = $this->getSkills();

    $skills['LM'] = $skills['RM'] = (
      (
        // Give primary central defender values more weight (50% more)
        ($this->dribbling + $this->passing + $this->teamwork + $this->stamina) * 1.5
        // Divide the weighted values to an average
      ) / 4 +

      // Give the rest of central defender values importance
      $skills['average']
      // Divide weighted and default values by 2 to get a combined value
    ) / 2;

    return $skills;
  }

  public function getCentralAttackerAttribute()
  {
    $skills = $this->getSkills();

    $skills['CF'] = $skills['CLF'] = $skills['CRF'] = (
      (
        // Give primary central defender values more weight (50% more)
        ($this->passing + $this->flair + $this->decisions + $this->off_the_ball + $this->teamwork + $this->finishing + $this->dribbling) * 1.5
        // Divide the weighted values to an average
      ) / 7 +

      // Give the rest of central defender values importance
      $skills['average']
      // Divide weighted and default values by 2 to get a combined value
    ) / 2;

    return $skills;
  }

  public function getWideAttackerAttribute()
  {
    $skills = $this->getSkills();

    $skills['LF'] = $skills['RF'] = (
      (
        // Give primary central defender values more weight (50% more)
        ($this->crossing + $this->dribbling + $this->technique + $this->pace) * 1.5
        // Divide the weighted values to an average
      ) / 4 +

      // Give the rest of central defender values importance
      $skills['average']
      // Divide weighted and default values by 2 to get a combined value
    ) / 2;

    return $skills;
  }
}
