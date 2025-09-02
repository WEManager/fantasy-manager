<?php

declare(strict_types=1);

namespace App\Models\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

/**
 * @implements CastsAttributes<
 *   array{
 *     raw: array<string,int>,
 *     mental: array<string,int>,
 *     physical: array<string,int>,
 *     technical: array<string,int>,
 *     goalkeeping: array<string,int>
 *   },
 *   array<string,int>|array{raw?: array<string,int>}
 * >
 */
final class PlayerStatsCast implements CastsAttributes
{
    /**
     * @param  array<string, mixed>  $attributes
     * @return array{
     *   raw: array<string,int>,
     *   mental: array<string,int>,
     *   physical: array<string,int>,
     *   technical: array<string,int>,
     *   goalkeeping: array<string,int>
     * }
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): array
    {
        /** @var array<string,int> $stats */
        $stats = [];

        if (is_string($value) && $value !== '') {
            /** @var array<string,mixed> $decoded */
            $decoded = json_decode($value, true, 512, JSON_THROW_ON_ERROR);
            // normaliza pra int
            foreach ($decoded as $k => $v) {
                $stats[(string) $k] = (int) $v;
            }
        }

        return [
            'raw' => $stats,
            'mental' => $this->getMentalStats($stats),
            'physical' => $this->getPhysicalStats($stats),
            'technical' => $this->getTechnicalStats($stats),
            'goalkeeping' => $this->getGoalkeepingStats($stats),
        ];
    }

    /**
     * @param  TSet|null  $value
     * @param  array<string, mixed>  $attributes
     * @param  array<string,int>|array{raw?: array<string,int>}|mixed  $value
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): string
    {
        if (is_array($value) && array_key_exists('raw', $value) && is_array($value['raw'])) {
            $value = $value['raw'];
        }

        if (! is_array($value)) {
            throw new InvalidArgumentException('Player stats must be an array of raw statistics.');
        }

        /** @var array<string,int> $normalized */
        $normalized = [];
        foreach ($value as $k => $v) {
            $normalized[(string) $k] = (int) $v;
        }

        return json_encode($normalized, JSON_THROW_ON_ERROR);
    }

    /**
     * @param  array<string,int>  $s
     * @return array<string,int>
     */
    private function getMentalStats(array $s): array
    {
        return [
            'aggression' => $this->i($s, 'aggression'),
            'att_position' => $this->i($s, 'att_position'),
            'composure' => $this->i($s, 'composure'),
            'interceptions' => $this->i($s, 'interceptions'),
            'vision' => $this->i($s, 'vision'),
        ];
    }

    /**
     * @param  array<string,int>  $s
     * @return array<string,int>
     */
    private function getPhysicalStats(array $s): array
    {
        return [
            'agility' => $this->i($s, 'agility'),
            'balance' => $this->i($s, 'balance'),
            'jumping' => $this->i($s, 'jumping'),
            'reactions' => $this->i($s, 'reactions'),
            'sprint_speed' => $this->i($s, 'sprint_speed'),
            'stamina' => $this->i($s, 'stamina'),
            'strength' => $this->i($s, 'strength'),
        ];
    }

    /**
     * @param  array<string,int>  $s
     * @return array<string,int>
     */
    private function getTechnicalStats(array $s): array
    {
        return [
            'ball_control' => $this->i($s, 'ball_control'),
            'crossing' => $this->i($s, 'crossing'),
            'curve' => $this->i($s, 'curve'),
            'defensive_awareness' => $this->i($s, 'defensive_awareness'),
            'dribbling' => $this->i($s, 'dribbling'),
            'fk_accuracy' => $this->i($s, 'fk_accuracy'),
            'finishing' => $this->i($s, 'finishing'),
            'heading_accuracy' => $this->i($s, 'heading_accuracy'),
            'long_passing' => $this->i($s, 'long_passing'),
            'long_shots' => $this->i($s, 'long_shots'),
            'penalties' => $this->i($s, 'penalties'),
            'short_passing' => $this->i($s, 'short_passing'),
            'shot_power' => $this->i($s, 'shot_power'),
            'sliding_tackle' => $this->i($s, 'sliding_tackle'),
            'standing_tackle' => $this->i($s, 'standing_tackle'),
            'volleys' => $this->i($s, 'volleys'),
        ];
    }

    /**
     * @param  array<string,int>  $s
     * @return array<string,int>
     */
    private function getGoalkeepingStats(array $s): array
    {
        return [
            'gk_diving' => $this->i($s, 'gk_diving'),
            'gk_handling' => $this->i($s, 'gk_handling'),
            'gk_kicking' => $this->i($s, 'gk_kicking'),
            'gk_positioning' => $this->i($s, 'gk_positioning'),
            'gk_reflexes' => $this->i($s, 'gk_reflexes'),
        ];
    }

    /** @param array<string,int> $s */
    private function i(array $s, string $key): int
    {
        return (int) $s[$key];
    }
}
