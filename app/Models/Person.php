<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    protected $fillable = [
        'age',
        'lastname',
        'firstname',
        'nationality',
    ];

    protected $hidden = [
        'dirtiness',
        'consistency',
        'versatility',
        'adaptability',
        'injury_proneness',
        'important_matches',
    ];

    public function club()
    {
        return $this->hasOneThrough(Club::class, PlayerContract::class, 'person_id', 'id', 'id', 'club_id');

        /*return $this->hasOneThrough(Club::class, )
        return $this->belongsToMany(Club::class, 'player_contracts', 'person_id', 'club_id')
            // Only get contracts that are currently valid
            ->whereDate('from', '<', date('Y-m-d'))
            ->whereDate('until', '>', date('Y-m-d'));*/
    }

    public function contract()
    {
        return $this->hasOne(PlayerContract::class, 'person_id')
            ->whereDate('from', '<', date('Y-m-d'))
            ->whereDate('until', '>', date('Y-m-d'));
    }

    public function getTechnicalAttribute()
    {
        $technical = [];
        foreach ($this->getTechnicalValues() as $key => $value) {
            $technical[__($key)] = $value;
        }

        ksort($technical);

        return $technical;
    }

    public function getMentalAttribute()
    {
        $mental = [];
        foreach ($this->getMentalValues() as $key => $value) {
            $mental[__($key)] = $value;
        }

        ksort($mental);

        return $mental;
    }

    public function getPhysicalAttribute()
    {
        $physical = [];
        foreach ($this->getPhysicalValues() as $key => $value) {
            $physical[__($key)] = $value;
        }

        ksort($physical);

        return $physical;
    }

    public function getFullNameAttribute()
    {
        return $this->firstname . ' ' . $this->lastname;
    }

    private function getMentalValues()
    {
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

    private function getPhysicalValues()
    {
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

    private function getGoalkeepingValues()
    {
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

    private function getTechnicalValues()
    {
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

        $goalkeeping['GK'] =
            (
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

    public function getCentralDefendingAttribute()
    {
        $skills = $this->getSkills();

        $skills['CD'] = $skills['CLD'] = $skills['CRD'] =
            (
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

        $skills['LD'] = $skills['RD'] =
            (
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

        $skills['CM'] = $skills['CLM'] = $skills['CRM'] =
            (
                (
                    // Give primary central defender values more weight (50% more)
                    ($this->passing + $this->decisions + $this->team_work) * 1.5
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

        $skills['LM'] = $skills['RM'] =
            (
                (
                    // Give primary central defender values more weight (50% more)
                    ($this->dribbling + $this->passing + $this->team_work + $this->stamina) * 1.5
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

        $skills['CF'] = $skills['CLF'] = $skills['CRF'] =
            (
                (
                    // Give primary central defender values more weight (50% more)
                    ($this->passing + $this->creativity + $this->decisions + $this->movement + $this->team_work + $this->shooting + $this->dribbling) * 1.5
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

        $skills['LF'] = $skills['RF'] =
            (
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

    public function getAverage($skills, $name)
    {
        $summary = 0;
        foreach ($skills[$name] as $key => $value) {
            $summary += $value;
        }
        $skills['average_' . $name] = $summary / count($skills[$name]);

        return $skills;
    }

}
