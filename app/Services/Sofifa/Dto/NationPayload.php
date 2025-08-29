<?php

declare(strict_types=1);

namespace App\Services\Sofifa\Dto;

/** @immutable */
final class NationPayload
{
    public function __construct(
        public int $id,
        public string $value,
        public string $flag,
    ) {}
}
