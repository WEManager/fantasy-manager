<?php

declare(strict_types=1);

namespace App\Models\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use InvalidArgumentException;

final class PlayerStatsCast implements CastsAttributes
{
    /**
     * @return array<string, mixed>
     */
    public function get($model, string $key, $value, array $attributes): array
    {
        $stats = json_decode($value, true) ?? [];
        
        return [
            'raw' => $stats, // Estatísticas brutas/planas
            'mental' => $this->getMentalStats($stats),
            'physical' => $this->getPhysicalStats($stats),
            'technical' => $this->getTechnicalStats($stats),
            'goalkeeping' => $this->getGoalkeepingStats($stats),
        ];
    }

    /**
     * @param array<string, mixed> $attributes
     */
    public function set($model, string $key, $value, array $attributes): string
    {
        // Se já for um array, assume que são as estatísticas brutas
        if (is_array($value)) {
            return json_encode($value);
        }

        throw new InvalidArgumentException('Player stats must be an array of raw statistics.');
    }

    /**
     * @return array<string, mixed>
     */
    private function getMentalStats(array $stats): array
    {
        return [
            'aggression' => $stats['aggression'] ?? null,
            'att_position' => $stats['att_position'] ?? null,
            'composure' => $stats['composure'] ?? null,
            'interceptions' => $stats['interceptions'] ?? null,
            'vision' => $stats['vision'] ?? null,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function getPhysicalStats(array $stats): array
    {
        return [
            'agility' => $stats['agility'] ?? null,
            'balance' => $stats['balance'] ?? null,
            'jumping' => $stats['jumping'] ?? null,
            'reactions' => $stats['reactions'] ?? null,
            'sprint_speed' => $stats['sprint_speed'] ?? null,
            'stamina' => $stats['stamina'] ?? null,
            'strength' => $stats['strength'] ?? null,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function getTechnicalStats(array $stats): array
    {
        return [
            'ball_control' => $stats['ball_control'] ?? null,
            'crossing' => $stats['crossing'] ?? null,
            'curve' => $stats['curve'] ?? null,
            'defensive_awareness' => $stats['defensive_awareness'] ?? null,
            'dribbling' => $stats['dribbling'] ?? null,
            'fk_accuracy' => $stats['fk_accuracy'] ?? null,
            'finishing' => $stats['finishing'] ?? null,
            'heading_accuracy' => $stats['heading_accuracy'] ?? null,
            'long_passing' => $stats['long_passing'] ?? null,
            'long_shots' => $stats['long_shots'] ?? null,
            'penalties' => $stats['penalties'] ?? null,
            'short_passing' => $stats['short_passing'] ?? null,
            'shot_power' => $stats['shot_power'] ?? null,
            'sliding_tackle' => $stats['sliding_tackle'] ?? null,
            'standing_tackle' => $stats['standing_tackle'] ?? null,
            'volleys' => $stats['volleys'] ?? null,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function getGoalkeepingStats(array $stats): array
    {
        return [
            'gk_diving' => $stats['gk_diving'] ?? null,
            'gk_handling' => $stats['gk_handling'] ?? null,
            'gk_kicking' => $stats['gk_kicking'] ?? null,
            'gk_positioning' => $stats['gk_positioning'] ?? null,
            'gk_reflexes' => $stats['gk_reflexes'] ?? null,
        ];
    }
}
