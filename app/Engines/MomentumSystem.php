<?php

declare(strict_types=1);

namespace App\Engines;

/**
 * Sistema de momentum e pressão psicológica
 * Baseado no documento técnico - momentum impacta performance e decisões
 */
final class MomentumSystem
{
    // Constantes de momentum
    private const MAX_MOMENTUM = 1.0;

    private const MIN_MOMENTUM = -1.0;

    private const MOMENTUM_DECAY = 0.95;        // Decaimento por evento

    private const MOMENTUM_THRESHOLD = 0.3;     // Threshold para efeitos significativos

    // Impactos de eventos no momentum
    private const EVENT_IMPACTS = [
        'goal' => 0.4,
        'goalkeeper_save' => 0.15,
        'shot_wide' => -0.05,
        'foul' => -0.08,
        'yellow_card' => -0.1,
        'red_card' => -0.2,
        'dribble_success' => 0.05,
        'pass_success' => 0.02,
        'defense_success' => 0.08,
        'turnover' => -0.03,
    ];

    private float $homeMomentum = 0.0;

    private float $awayMomentum = 0.0;

    private int $consecutiveEvents = 0;

    private string $lastEventTeam = '';

    public function __construct()
    {
        $this->homeMomentum = 0.0;
        $this->awayMomentum = 0.0;
        $this->consecutiveEvents = 0;
        $this->lastEventTeam = '';
    }

    /**
     * Atualiza o momentum baseado em um evento
     */
    public function updateMomentum(string $team, string $eventType, bool $isPositive = true): void
    {
        // Aplicar decaimento natural
        $this->homeMomentum *= self::MOMENTUM_DECAY;
        $this->awayMomentum *= self::MOMENTUM_DECAY;

        // Determinar impacto do evento
        $impact = $this->getEventImpact($eventType, $isPositive);

        // Aplicar momentum ao time correto
        if ($team === 'hometeam') {
            $this->homeMomentum = $this->clampMomentum($this->homeMomentum + $impact);
        } else {
            $this->awayMomentum = $this->clampMomentum($this->awayMomentum + $impact);
        }

        // Aplicar momentum negativo ao time adversário (efeito psicológico)
        $negativeImpact = $impact * -0.3; // 30% do impacto negativo
        if ($team === 'hometeam') {
            $this->awayMomentum = $this->clampMomentum($this->awayMomentum + $negativeImpact);
        } else {
            $this->homeMomentum = $this->clampMomentum($this->homeMomentum + $negativeImpact);
        }

        // Contar eventos consecutivos para efeito de "sequência"
        if ($this->lastEventTeam === $team) {
            $this->consecutiveEvents++;
            $this->applyConsecutiveBonus($team);
        } else {
            $this->consecutiveEvents = 1;
        }

        $this->lastEventTeam = $team;
    }

    /**
     * Obtém modificadores de performance baseados no momentum
     */
    public function getMomentumModifiers(string $team): array
    {
        $momentum = $team === 'hometeam' ? $this->homeMomentum : $this->awayMomentum;

        // Aplicar efeitos apenas se o momentum for significativo
        if (abs($momentum) < self::MOMENTUM_THRESHOLD) {
            return [
                'pass_accuracy' => 1.0,
                'shot_accuracy' => 1.0,
                'dribble_success' => 1.0,
                'defensive_awareness' => 1.0,
                'decision_making' => 1.0,
                'aggression' => 1.0,
            ];
        }

        // Curva suave para os modificadores
        $modifier = $momentum * 0.15; // Até 15% de modificação

        return [
            'pass_accuracy' => 1.0 + $modifier,
            'shot_accuracy' => 1.0 + $modifier,
            'dribble_success' => 1.0 + $modifier,
            'defensive_awareness' => 1.0 + ($modifier * 0.8),
            'decision_making' => 1.0 + ($modifier * 0.6),
            'aggression' => 1.0 + ($modifier * 0.4),
        ];
    }

    /**
     * Obtém o status do momentum em texto
     */
    public function getMomentumStatus(string $team): string
    {
        $momentum = $team === 'hometeam' ? $this->homeMomentum : $this->awayMomentum;

        return match (true) {
            $momentum > 0.5 => 'Excelente',
            $momentum > 0.2 => 'Bom',
            $momentum > -0.2 => 'Neutro',
            $momentum > -0.5 => 'Ruim',
            default => 'Muito Ruim',
        };
    }

    /**
     * Obtém o momentum atual de um time
     */
    public function getMomentum(string $team): float
    {
        return $team === 'hometeam' ? $this->homeMomentum : $this->awayMomentum;
    }

    /**
     * Obtém o momentum de ambos os times
     */
    public function getBothMomentums(): array
    {
        return [
            'hometeam' => $this->homeMomentum,
            'awayteam' => $this->awayMomentum,
        ];
    }

    /**
     * Aplica modificadores de momentum aos atributos de um jogador
     */
    public function applyMomentumToPlayer(\App\Models\Player $player, string $team): \App\Models\Player
    {
        $modifiers = $this->getMomentumModifiers($team);
        $momentumPlayer = clone $player;

        // Aplicar modificadores aos atributos relevantes
        $stats = $momentumPlayer->stats;

        if (isset($stats['technical']['short_passing'])) {
            $stats['technical']['short_passing'] =
                (int) ($stats['technical']['short_passing'] * $modifiers['pass_accuracy']);
        }

        if (isset($stats['technical']['finishing'])) {
            $stats['technical']['finishing'] =
                (int) ($stats['technical']['finishing'] * $modifiers['shot_accuracy']);
        }

        if (isset($stats['technical']['dribbling'])) {
            $stats['technical']['dribbling'] =
                (int) ($stats['technical']['dribbling'] * $modifiers['dribble_success']);
        }

        if (isset($stats['mental']['composure'])) {
            $stats['mental']['composure'] =
                (int) ($stats['mental']['composure'] * $modifiers['decision_making']);
        }

        $momentumPlayer->stats = $stats;

        return $momentumPlayer;
    }

    /**
     * Reseta o momentum (útil para início de segundo tempo)
     */
    public function resetMomentum(): void
    {
        $this->homeMomentum = 0.0;
        $this->awayMomentum = 0.0;
        $this->consecutiveEvents = 0;
        $this->lastEventTeam = '';
    }

    /**
     * Aplica decaimento gradual do momentum
     */
    public function decayMomentum(): void
    {
        $this->homeMomentum *= self::MOMENTUM_DECAY;
        $this->awayMomentum *= self::MOMENTUM_DECAY;
    }

    /**
     * Obtém o impacto de um evento no momentum
     */
    private function getEventImpact(string $eventType, bool $isPositive): float
    {
        $baseImpact = self::EVENT_IMPACTS[$eventType] ?? 0.0;

        if (! $isPositive) {
            $baseImpact *= -1;
        }

        return $baseImpact;
    }

    /**
     * Aplica bônus por eventos consecutivos
     */
    private function applyConsecutiveBonus(string $team): void
    {
        if ($this->consecutiveEvents >= 3) {
            $bonus = min(0.1, $this->consecutiveEvents * 0.02);

            if ($team === 'hometeam') {
                $this->homeMomentum = $this->clampMomentum($this->homeMomentum + $bonus);
            } else {
                $this->awayMomentum = $this->clampMomentum($this->awayMomentum + $bonus);
            }
        }
    }

    /**
     * Limita o momentum dentro dos valores válidos
     */
    private function clampMomentum(float $momentum): float
    {
        return max(self::MIN_MOMENTUM, min(self::MAX_MOMENTUM, $momentum));
    }
}
