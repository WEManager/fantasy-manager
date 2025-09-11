<?php

declare(strict_types=1);

namespace App\Services;

use InvalidArgumentException;

/**
 * RNG determinístico baseado no MVP
 * Implementa XorShift32 para geração de números aleatórios reproduzíveis
 */
final class Rng
{
    private int $s;

    public function __construct(int $seed)
    {
        $this->s = $seed;
    }

    /**
     * Gera número float entre 0 e 1 (exclusivo)
     */
    public function uniform(): float
    {
        return $this->step() / 0x100000000;
    }

    /**
     * Gera número inteiro entre min e max (inclusivo)
     */
    public function range(int $min, int $max): int
    {
        if ($min > $max) {
            throw new InvalidArgumentException("Min ($min) cannot be greater than max ($max)");
        }

        $range = $max - $min + 1;

        return $min + (int) floor($this->uniform() * $range);
    }

    /**
     * Seleciona item aleatório de um array
     */
    public function pick(array $items): mixed
    {
        if (empty($items)) {
            throw new InvalidArgumentException('Cannot pick from empty array');
        }

        // Reindexar array para garantir índices sequenciais
        $values = array_values($items);
        $i = (int) floor($this->uniform() * count($values));

        return $values[$i];
    }

    /**
     * Gera boolean com probabilidade especificada
     */
    public function chance(float $probability): bool
    {
        return $this->uniform() < $probability;
    }

    /**
     * Gera número inteiro compatível com rand() do PHP
     */
    public function randInt(int $min, int $max): int
    {
        return $this->range($min, $max);
    }

    /**
     * Obtém a seed atual (para debug/logging)
     */
    public function getSeed(): int
    {
        return $this->s;
    }

    /**
     * Redefine a seed
     */
    public function setSeed(int $seed): void
    {
        $this->s = $seed;
    }

    /**
     * XorShift32 simples - gera próximo número da sequência
     */
    private function step(): int
    {
        $x = $this->s;
        $x ^= ($x << 13) & 0xFFFFFFFF;
        $x ^= ($x >> 17);
        $x ^= ($x << 5) & 0xFFFFFFFF;
        $this->s = $x & 0xFFFFFFFF;

        return $this->s;
    }
}
