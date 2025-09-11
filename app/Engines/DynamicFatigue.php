<?php

declare(strict_types=1);

namespace App\Engines;

use App\Models;

/**
 * Sistema de fadiga dinâmica para jogadores
 * Baseado no documento técnico - fadiga impacta performance ao longo da partida
 */
final class DynamicFatigue
{
    // Tipos de intensidade de ação
    public const LOW_INTENSITY = 1;

    public const MEDIUM_INTENSITY = 2;

    public const HIGH_INTENSITY = 3;

    public const MAXIMUM_INTENSITY = 4;

    // Constantes de fadiga
    private const BASE_FATIGUE_RATE = 0.001;        // Fadiga base por ação

    private const INTENSITY_MULTIPLIER = 0.002;     // Multiplicador por intensidade

    private const STAMINA_FACTOR = 0.5;             // Fator de resistência do jogador

    private const RECOVERY_RATE = 0.0005;           // Taxa de recuperação durante pausas

    /**
     * Atualiza a fadiga de um jogador baseada na ação realizada
     */
    public static function updatePlayerFatigue(
        Models\Player $player,
        int $actionIntensity,
        float $timeElapsed,
        bool $isActiveInPlay = true
    ): void {
        // Inicializar fadiga se não existir
        if (! isset($player->current_fatigue)) {
            $player->current_fatigue = 0.0;
        }

        if ($isActiveInPlay) {
            // Calcular fadiga baseada na ação
            $baseFatigue = self::BASE_FATIGUE_RATE * $actionIntensity;
            $staminaFactor = self::calculateStaminaFactor($player);
            $timeFactor = $timeElapsed / 60.0; // Normalizar por minuto

            $fatigueIncrease = $baseFatigue * $staminaFactor * $timeFactor;
            $player->current_fatigue = min(1.0, $player->current_fatigue + $fatigueIncrease);
        } else {
            // Recuperação durante pausas (bola parada, intervalo)
            $recoveryAmount = self::RECOVERY_RATE * $timeElapsed;
            $player->current_fatigue = max(0.0, $player->current_fatigue - $recoveryAmount);
        }
    }

    /**
     * Obtém modificadores de performance baseados na fadiga
     */
    public static function getFatigueModifiers(Models\Player $player): array
    {
        $fatigue = $player->current_fatigue ?? 0.0;

        // Curva não-linear: fadiga impacta mais no final
        $fatigueCurve = $fatigue * $fatigue;

        return [
            'pass_accuracy' => 1.0 - ($fatigueCurve * 0.4),      // Até 40% de redução
            'shot_accuracy' => 1.0 - ($fatigueCurve * 0.5),      // Até 50% de redução
            'dribble_success' => 1.0 - ($fatigueCurve * 0.6),    // Até 60% de redução
            'defensive_awareness' => 1.0 - ($fatigueCurve * 0.3), // Até 30% de redução
            'speed' => 1.0 - ($fatigueCurve * 0.7),              // Até 70% de redução
            'decision_making' => 1.0 - ($fatigueCurve * 0.4),    // Até 40% de redução
            'foul_probability' => 1.0 + ($fatigueCurve * 0.5),   // Até 50% de aumento
        ];
    }

    /**
     * Determina a intensidade da ação baseada no tipo
     */
    public static function getActionIntensity(string $actionType): int
    {
        return match ($actionType) {
            'goal' => self::MAXIMUM_INTENSITY,
            'shot' => self::HIGH_INTENSITY,
            'dribble' => self::HIGH_INTENSITY,
            'defense' => self::HIGH_INTENSITY,
            'pass' => self::MEDIUM_INTENSITY,
            'recovery' => self::MEDIUM_INTENSITY,
            'goalkeeper_save' => self::HIGH_INTENSITY,
            default => self::LOW_INTENSITY,
        };
    }

    /**
     * Verifica se o jogador está muito cansado para continuar
     */
    public static function isPlayerExhausted(Models\Player $player): bool
    {
        $fatigue = $player->current_fatigue ?? 0.0;

        return $fatigue > 0.9;
    }

    /**
     * Calcula a probabilidade de lesão baseada na fadiga
     */
    public static function calculateInjuryRisk(Models\Player $player, int $actionIntensity): float
    {
        $fatigue = $player->current_fatigue ?? 0.0;
        $baseRisk = 0.0001; // 0.01% base

        $fatigueMultiplier = 1.0 + ($fatigue * 5.0); // Até 6x mais risco
        $intensityMultiplier = 1.0 + ($actionIntensity * 0.5); // Até 3x mais risco

        return $baseRisk * $fatigueMultiplier * $intensityMultiplier;
    }

    /**
     * Obtém o status de fadiga em texto
     */
    public static function getFatigueStatus(Models\Player $player): string
    {
        $fatigue = $player->current_fatigue ?? 0.0;

        return match (true) {
            $fatigue < 0.2 => 'Fresco',
            $fatigue < 0.4 => 'Bom',
            $fatigue < 0.6 => 'Cansado',
            $fatigue < 0.8 => 'Muito Cansado',
            default => 'Exausto',
        };
    }

    /**
     * Aplica modificadores de fadiga aos atributos do jogador
     */
    public static function applyFatigueToPlayer(Models\Player $player): Models\Player
    {
        $modifiers = self::getFatigueModifiers($player);

        // Criar uma cópia do jogador com atributos modificados
        $fatiguedPlayer = clone $player;

        // Aplicar modificadores aos atributos relevantes
        $stats = $fatiguedPlayer->stats;

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

        if (isset($stats['technical']['defensive_awareness'])) {
            $stats['technical']['defensive_awareness'] =
                (int) ($stats['technical']['defensive_awareness'] * $modifiers['defensive_awareness']);
        }

        $fatiguedPlayer->stats = $stats;

        return $fatiguedPlayer;
    }

    /**
     * Calcula o fator de resistência baseado nos atributos do jogador
     */
    private static function calculateStaminaFactor(Models\Player $player): float
    {
        $stamina = $player->stamina ?? 50;
        $physical = $player->stats['physical']['stamina'] ?? 50;

        // Combinar stamina do modelo com atributo físico
        $combinedStamina = ($stamina + $physical) / 2.0;

        // Fator inverso: jogadores com mais stamina se cansam menos
        return 1.0 - (($combinedStamina - 50) / 100.0) * self::STAMINA_FACTOR;
    }
}
