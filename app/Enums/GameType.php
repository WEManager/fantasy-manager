<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum GameType: int implements HasLabel
{
    case REGULAR_TIME_ONLY = 1;
    case REGULAR_TIME_PLUS_PENALTIES = 2;
    case EXTRA_TIME_PLUS_PENALTIES = 3;

    public function getLabel(): string
    {
        return match ($this) {
            self::REGULAR_TIME_ONLY => 'Regular Time',
            self::REGULAR_TIME_PLUS_PENALTIES => 'Regular Time + Penalties',
            self::EXTRA_TIME_PLUS_PENALTIES => 'Extra Time',
        };
    }
}
