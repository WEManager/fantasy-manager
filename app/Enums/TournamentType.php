<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum TournamentType: string implements HasLabel
{
    case LEAGUE = 'league';
    case GROUPS = 'groups';
    case PLAYOFFS = 'playoffs';

    public function getLabel(): string
    {
        return match ($this) {
            self::LEAGUE => 'League',
            self::GROUPS => 'Groups',
            self::PLAYOFFS => 'Playoffs',
        };
    }
}
