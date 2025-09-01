<?php

declare(strict_types=1);

namespace App\Services\Sofifa\Dto;

/** @immutable */
final class PlayerPayload
{
    public function __construct(
        public int $id,
        public string $fullName,
        public string $knowAs,
        public int $nationId,
        public ?int $birthDate,
        public int $length,
        public int $weight,
        public string $preferredPosition,
        /** @var array<string> */
        public array $positions,
        public string $preferredFoot,
        public int $skillMoves,
        public int $weakFoot,
        /** @var array<string> */
        public array $specialities,
        /** @var array<string> */
        public array $playStyles,
        /** @var array<string, int> */
        public array $stats,
    ) {}
}
