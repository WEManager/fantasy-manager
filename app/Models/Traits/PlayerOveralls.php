<?php

declare(strict_types=1);

namespace App\Models\Traits;

trait PlayerOveralls
{
    /**
     * @param  array<string, mixed>  $skills
     * @return array<string, mixed>
     */
    public function getAverage(array $skills, string $name): array
    {
        $summary = 0;

        foreach ($skills[$name] as $key => $value) {
            $summary += $value;
        }

        $skills['average_'.$name] = $summary / count($skills[$name]);

        return $skills;
    }

    /** @return array<string, int> */
    public function getGoalkeepingAttribute(): array
    {
        $stats = $this->stats ?? [];

        // Usar as estatísticas já agrupadas pelo cast
        $goalkeeping = $stats['goalkeeping'] ?? [];
        $mental = $stats['mental'] ?? [];
        $physical = $stats['physical'] ?? [];

        $goalkeeping = $this->getAverage($goalkeeping, 'goalkeeping');
        $goalkeeping = $this->getAverage($mental, 'mental');
        $goalkeeping = $this->getAverage($physical, 'physical');

        $goalkeeping['GK'] = (
            (
                // Give primary goalkeeping values more weight (50% more)
                (($stats['gk_diving']) + ($stats['agility']) + ($stats['gk_handling']) + ($stats['gk_reflexes'])) * 1.5
                // Divide the weighted values to an average
            ) / 4 +
            // Give the rest of goalkeeping values importance
            $goalkeeping['average_goalkeeping']
            // Divide weighted and default values by 2 to get a combined value
        ) / 2;

        return $goalkeeping;
    }

    /** @return array<string, int|float> */
    public function getCentralDefendingAttribute(): array
    {
        $skills = $this->getSkills();
        $stats = $this->stats['raw'] ?? [];

        $skills['CD'] = $skills['CLD'] = $skills['CRD'] = (
            (
                // Give primary central defender values more weight (50% more)
                (($stats['defensive_awareness']) + ($stats['standing_tackle']) + ($stats['sliding_tackle']) + ($stats['att_position']) + ($stats['strength'])) * 1.5
                // Divide the weighted values to an average
            ) / 5 +
            // Give the rest of central defender values importance
            $skills['average']
            // Divide weighted and default values by 2 to get a combined value
        ) / 2;

        return $skills;
    }

    /** @return array<string, int|float> */
    public function getWideDefendingAttribute(): array
    {
        $skills = $this->getSkills();
        $stats = $this->stats['raw'] ?? [];

        $skills['LD'] = $skills['RD'] = (
            (
                // Give primary wide defender values more weight (50% more)
                (($stats['dribbling']) + ($stats['standing_tackle']) + ($stats['sliding_tackle']) + ($stats['att_position']) + ($stats['stamina'])) * 1.5
                // Divide the weighted values to an average
            ) / 5 +
            // Give the rest of wide defender values importance
            $skills['average']
            // Divide weighted and default values by 2 to get a combined value
        ) / 2;

        return $skills;
    }

    /** @return array<string, int|float> */
    public function getCentralMidfielderAttribute(): array
    {
        $skills = $this->getSkills();
        $stats = $this->stats['raw'] ?? [];

        $skills['CM'] = $skills['CLM'] = $skills['CRM'] = (
            (
                // Give primary central midfielder values more weight (50% more)
                (($stats['short_passing']) + ($stats['long_passing']) + ($stats['vision']) + ($stats['composure'])) * 1.5
                // Divide the weighted values to an average
            ) / 4 +
            // Give the rest of central midfielder values importance
            $skills['average']
            // Divide weighted and default values by 2 to get a combined value
        ) / 2;

        return $skills;
    }

    /** @return array<string, int|float> */
    public function getWideMidfielderAttribute(): array
    {
        $skills = $this->getSkills();
        $stats = $this->stats['raw'] ?? [];

        $skills['LM'] = $skills['RM'] = (
            (
                // Give primary wide midfielder values more weight (50% more)
                (($stats['dribbling']) + ($stats['crossing']) + ($stats['short_passing']) + ($stats['stamina'])) * 1.5
                // Divide the weighted values to an average
            ) / 4 +
            // Give the rest of wide midfielder values importance
            $skills['average']
            // Divide weighted and default values by 2 to get a combined value
        ) / 2;

        return $skills;
    }

    /** @return array<string, int|float> */
    public function getCentralAttackerAttribute(): array
    {
        $skills = $this->getSkills();
        $stats = $this->stats['raw'] ?? [];

        $skills['CF'] = $skills['CLF'] = $skills['CRF'] = (
            (
                // Give primary central attacker values more weight (50% more)
                (($stats['finishing']) + ($stats['heading_accuracy']) + ($stats['att_position']) + ($stats['composure']) + ($stats['dribbling']) + ($stats['ball_control'])) * 1.5
                // Divide the weighted values to an average
            ) / 6 +
            // Give the rest of central attacker values importance
            $skills['average']
            // Divide weighted and default values by 2 to get a combined value
        ) / 2;

        return $skills;
    }

    /** @return array<string, int|float> */
    public function getWideAttackerAttribute(): array
    {
        $skills = $this->getSkills();
        $stats = $this->stats['raw'] ?? [];

        $skills['LF'] = $skills['RF'] = (
            (
                // Give primary wide attacker values more weight (50% more)
                (($stats['crossing']) + ($stats['dribbling']) + ($stats['sprint_speed']) + ($stats['finishing'])) * 1.5
                // Divide the weighted values to an average
            ) / 4 +
            // Give the rest of wide attacker values importance
            $skills['average']
            // Divide weighted and default values by 2 to get a combined value
        ) / 2;

        return $skills;
    }

    /** @return array<string, int> */
    private function getSkills(): array
    {
        $stats = $this->stats ?? [];

        // Usar as estatísticas já agrupadas pelo cast
        $mental = $stats['mental'] ?? [];
        $physical = $stats['physical'] ?? [];
        $technical = $stats['technical'] ?? [];

        $skills = [
            'mental' => $mental,
            'physical' => $physical,
            'technical' => $technical,
        ];

        $skills = $this->getAverage($skills, 'mental');
        $skills = $this->getAverage($skills, 'physical');
        $skills = $this->getAverage($skills, 'technical');

        $skills['average'] = ($skills['average_mental'] + $skills['average_physical'] + $skills['average_technical']) / 3;

        return $skills;
    }
}
