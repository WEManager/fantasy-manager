<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum GameStatus: int implements HasLabel
{
    case NOT_STARTED = 0;
    case ACTIVE = 1;
    case ENDED = 2;
    case WAITING_FOR_SECOND_HALF = 3;
    case WAITING_FOR_EXTRA_TIME = 4;
    case WAITING_FOR_PENALTIES = 5;
    case CANCELLED = 6;
    case POSTPONED = 7;
    case NOT_DECIDED = 8;

    public function getLabel(): string
    {
        return match ($this) {
            self::NOT_STARTED => 'Not started',
            self::ACTIVE => 'Active',
            self::ENDED => 'Ended',
            self::WAITING_FOR_SECOND_HALF => 'Waiting for second half',
            self::WAITING_FOR_EXTRA_TIME => 'Waiting for extra time',
            self::WAITING_FOR_PENALTIES => 'Waiting for penalties',
            self::CANCELLED => 'Cancelled',
            self::POSTPONED => 'Postponed',
            self::NOT_DECIDED => 'Not decided',
        };
    }
}
