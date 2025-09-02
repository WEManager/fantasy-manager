<?php

declare(strict_types=1);

namespace App\Models\Traits;

trait PlayerOveralls
{
    /** @return array<string,int|float> */
    public function getGoalkeepingAttribute(): array
    {
        /** @var array{
         *   raw?: array<string,int>,
         *   mental?: array<string,int>,
         *   physical?: array<string,int>,
         *   technical?: array<string,int>,
         *   goalkeeping?: array<string,int>
         * } $stats
         */
        $stats = $this->stats ?? [];

        /** @var array<string,int> $raw */
        $raw = $stats['raw'] ?? [];
        /** @var array<string,int> $gk */
        $gk = $stats['goalkeeping'] ?? [];
        /** @var array<string,int> $mental */
        $mental = $stats['mental'] ?? [];
        /** @var array<string,int> $physical */
        $physical = $stats['physical'] ?? [];

        $avgGk = $this->avg($gk);
        $avgMental = $this->avg($mental);
        $avgPhysical = $this->avg($physical);

        $primaryWeighted = (
            (int) ($raw['gk_diving'] ?? 0) +
            (int) ($raw['agility'] ?? 0) +
            (int) ($raw['gk_handling'] ?? 0) +
            (int) ($raw['gk_reflexes'] ?? 0)
        ) * 1.5;

        $primaryAvg = $primaryWeighted / 4.0;

        return [
            ...$gk,
            'average_goalkeeping' => $avgGk,
            'average_mental' => $avgMental,
            'average_physical' => $avgPhysical,
            'GK' => ($primaryAvg + $avgGk) / 2.0,
        ];
    }

    /** @return array<string,int|float> */
    public function getCentralDefendingAttribute(): array
    {
        $skills = $this->getSkills();
        /** @var array<string,int> $raw */
        $raw = $this->stats['raw'] ?? [];

        $skills['CD'] = $skills['CLD'] = $skills['CRD'] = (
            (
                (($raw['defensive_awareness'] ?? 0)
                + ($raw['standing_tackle'] ?? 0)
                + ($raw['sliding_tackle'] ?? 0)
                + ($raw['att_position'] ?? 0)
                + ($raw['strength'] ?? 0)) * 1.5
            ) / 5.0
            + (float) $skills['average']
        ) / 2.0;

        return $skills;
    }

    /** @return array<string,int|float> */
    public function getWideDefendingAttribute(): array
    {
        $skills = $this->getSkills();
        /** @var array<string,int> $raw */
        $raw = $this->stats['raw'] ?? [];

        $skills['LD'] = $skills['RD'] = (
            (
                (($raw['dribbling'] ?? 0)
                + ($raw['standing_tackle'] ?? 0)
                + ($raw['sliding_tackle'] ?? 0)
                + ($raw['att_position'] ?? 0)
                + ($raw['stamina'] ?? 0)) * 1.5
            ) / 5.0
            + (float) $skills['average']
        ) / 2.0;

        return $skills;
    }

    /** @return array<string,int|float> */
    public function getCentralMidfielderAttribute(): array
    {
        $skills = $this->getSkills();
        /** @var array<string,int> $raw */
        $raw = $this->stats['raw'] ?? [];

        $skills['CM'] = $skills['CLM'] = $skills['CRM'] = (
            (
                (($raw['short_passing'] ?? 0)
                + ($raw['long_passing'] ?? 0)
                + ($raw['vision'] ?? 0)
                + ($raw['composure'] ?? 0)) * 1.5
            ) / 4.0
            + (float) $skills['average']
        ) / 2.0;

        return $skills;
    }

    /** @return array<string,int|float> */
    public function getWideMidfielderAttribute(): array
    {
        $skills = $this->getSkills();
        /** @var array<string,int> $raw */
        $raw = $this->stats['raw'] ?? [];

        $skills['LM'] = $skills['RM'] = (
            (
                (($raw['dribbling'] ?? 0)
                + ($raw['crossing'] ?? 0)
                + ($raw['short_passing'] ?? 0)
                + ($raw['stamina'] ?? 0)) * 1.5
            ) / 4.0
            + (float) $skills['average']
        ) / 2.0;

        return $skills;
    }

    /** @return array<string,int|float> */
    public function getCentralAttackerAttribute(): array
    {
        $skills = $this->getSkills();
        /** @var array<string,int> $raw */
        $raw = $this->stats['raw'] ?? [];

        $skills['CF'] = $skills['CLF'] = $skills['CRF'] = (
            (
                (($raw['finishing'] ?? 0)
                + ($raw['heading_accuracy'] ?? 0)
                + ($raw['att_position'] ?? 0)
                + ($raw['composure'] ?? 0)
                + ($raw['dribbling'] ?? 0)
                + ($raw['ball_control'] ?? 0)) * 1.5
            ) / 6.0
            + (float) $skills['average']
        ) / 2.0;

        return $skills;
    }

    /** @return array<string,int|float> */
    public function getWideAttackerAttribute(): array
    {
        $skills = $this->getSkills();
        /** @var array<string,int> $raw */
        $raw = $this->stats['raw'] ?? [];

        $skills['LF'] = $skills['RF'] = (
            (
                (($raw['crossing'] ?? 0)
                + ($raw['dribbling'] ?? 0)
                + ($raw['sprint_speed'] ?? 0)
                + ($raw['finishing'] ?? 0)) * 1.5
            ) / 4.0
            + (float) $skills['average']
        ) / 2.0;

        return $skills;
    }

    /** @param array<string,int|float> $vals */
    private function avg(array $vals): float
    {
        if ($vals === []) {
            return 0.0;
        }
        $sum = 0.0;
        foreach ($vals as $v) {
            $sum += (float) $v;
        }

        return $sum / count($vals);
    }

    /** @return array<string, int|float|array<string, int|float>> */
    private function getSkills(): array
    {
        /** @var array{
         *   mental?: array<string,int>,
         *   physical?: array<string,int>,
         *   technical?: array<string,int>
         * } $stats
         */
        $stats = $this->stats ?? [];

        /** @var array<string,int> $mental */
        $mental = $stats['mental'] ?? [];
        /** @var array<string,int> $physical */
        $physical = $stats['physical'] ?? [];
        /** @var array<string,int> $technical */
        $technical = $stats['technical'] ?? [];

        $avgMental = $this->avg($mental);
        $avgPhysical = $this->avg($physical);
        $avgTechnical = $this->avg($technical);

        return [
            'mental' => $mental,
            'physical' => $physical,
            'technical' => $technical,
            'average_mental' => $avgMental,
            'average_physical' => $avgPhysical,
            'average_technical' => $avgTechnical,
            'average' => ($avgMental + $avgPhysical + $avgTechnical) / 3.0,
        ];
    }
}
