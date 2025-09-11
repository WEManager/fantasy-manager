<?php

declare(strict_types=1);

namespace App\Engines;

/**
 * Sistema de zonas do campo para determinar contexto dos eventos
 * Baseado no documento técnico - zonas influenciam probabilidades e tipos de eventos
 */
final class FieldZones
{
    // Zonas principais do campo
    public const DEFENSIVE_THIRD = 'defensive';

    public const MIDDLE_THIRD = 'middle';

    public const ATTACKING_THIRD = 'attacking';

    // Zonas específicas
    public const PENALTY_AREA = 'penalty_area';

    public const GOAL_AREA = 'goal_area';

    public const WING = 'wing';

    public const CENTER = 'center';

    // Contexto de posse
    public const BUILD_UP = 'build_up';

    public const CREATION = 'creation';

    public const FINISHING = 'finishing';

    /**
     * Determina a zona do campo baseada no contexto do evento
     */
    public static function determineZone(
        string $teamWithBall,
        string $actionType,
        float $fieldPosition = 0.5
    ): string {
        // Posição 0.0 = defesa própria, 0.5 = meio-campo, 1.0 = ataque adversário

        if ($fieldPosition < 0.33) {
            return self::DEFENSIVE_THIRD;
        }
        if ($fieldPosition < 0.67) {
            return self::MIDDLE_THIRD;
        }

        return self::ATTACKING_THIRD;

    }

    /**
     * Determina o contexto de jogo baseado na zona e tipo de ação
     */
    public static function determineContext(string $zone, string $actionType): string
    {
        return match ($zone) {
            self::DEFENSIVE_THIRD => match ($actionType) {
                'pass', 'recovery' => self::BUILD_UP,
                default => self::BUILD_UP,
            },
            self::MIDDLE_THIRD => match ($actionType) {
                'pass', 'dribble' => self::CREATION,
                'shot' => self::CREATION,
                default => self::CREATION,
            },
            self::ATTACKING_THIRD => match ($actionType) {
                'shot', 'dribble' => self::FINISHING,
                'pass' => self::FINISHING,
                default => self::FINISHING,
            },
            default => self::CREATION,
        };
    }

    /**
     * Obtém modificadores de probabilidade baseados na zona
     * 
     * @return array<string, float>
     */
    public static function getZoneModifiers(string $zone): array
    {
        return match ($zone) {
            self::DEFENSIVE_THIRD => [
                'pass_success' => 1.1,      // Passes mais seguros na defesa
                'shot_accuracy' => 0.3,     // Chutes muito raros da defesa
                'dribble_success' => 0.8,   // Dribles mais difíceis
                'foul_probability' => 0.7,  // Menos faltas na defesa
            ],
            self::MIDDLE_THIRD => [
                'pass_success' => 1.0,      // Passes normais
                'shot_accuracy' => 0.6,     // Chutes de longa distância
                'dribble_success' => 1.0,   // Dribles normais
                'foul_probability' => 1.0,  // Faltas normais
            ],
            self::ATTACKING_THIRD => [
                'pass_success' => 0.9,      // Passes mais arriscados
                'shot_accuracy' => 1.4,     // Chutes mais precisos
                'dribble_success' => 1.2,   // Dribles mais eficazes
                'foul_probability' => 1.3,  // Mais faltas no ataque
            ],
            default => [
                'pass_success' => 1.0,
                'shot_accuracy' => 1.0,
                'dribble_success' => 1.0,
                'foul_probability' => 1.0,
            ],
        };
    }

    /**
     * Calcula a posição no campo baseada no contexto do evento
     */
    public static function calculateFieldPosition(
        string $teamWithBall,
        string $actionType,
        ?\App\Models\Player $attackingPlayer = null,
        ?\App\Models\Player $defendingPlayer = null
    ): float {
        // Lógica simplificada baseada no tipo de ação e jogadores envolvidos

        $basePosition = match ($actionType) {
            'goal' => 0.95,           // Gols sempre no ataque
            'shot' => 0.85,           // Chutes principalmente no ataque
            'dribble' => 0.70,        // Dribles no meio/ataque
            'pass' => 0.60,           // Passes em todo o campo
            'recovery' => 0.40,       // Recuperações no meio/defesa
            'defense' => 0.30,        // Defesas na própria área
            default => 0.50,          // Neutro
        };

        // Ajustar baseado nos atributos dos jogadores
        if ($attackingPlayer) {
            $attackingModifier = match (true) {
                $attackingPlayer->stats['technical']['finishing'] > 80 => 0.1,
                $attackingPlayer->stats['technical']['finishing'] > 60 => 0.05,
                default => 0.0,
            };
            $basePosition = min(1.0, $basePosition + $attackingModifier);
        }

        if ($defendingPlayer) {
            $defendingModifier = match (true) {
                $defendingPlayer->stats['technical']['defensive_awareness'] > 80 => -0.1,
                $defendingPlayer->stats['technical']['defensive_awareness'] > 60 => -0.05,
                default => 0.0,
            };
            $basePosition = max(0.0, $basePosition + $defendingModifier);
        }

        return $basePosition;
    }

    /**
     * Obtém o xG (Expected Goals) baseado na zona e contexto
     */
    public static function getExpectedGoals(string $zone, string $context, ?\App\Models\Player $shooter = null): float
    {
        $baseXG = match ($zone) {
            self::DEFENSIVE_THIRD => 0.01,
            self::MIDDLE_THIRD => 0.05,
            self::ATTACKING_THIRD => 0.15,
            default => 0.05,
        };

        $contextModifier = match ($context) {
            self::BUILD_UP => 0.5,
            self::CREATION => 1.0,
            self::FINISHING => 1.5,
            default => 1.0,
        };

        $playerModifier = 1.0;
        if ($shooter) {
            $finishing = $shooter->stats['technical']['finishing'] ?? 50;
            $playerModifier = 0.5 + ($finishing / 100.0);
        }

        return $baseXG * $contextModifier * $playerModifier;
    }
}
