<?php

declare(strict_types=1);

namespace App\Enums;

enum TournamentType: string
{
    case LEAGUE = 'league';
    case GROUPS = 'groups';
    case CHAMPIONSHIP = 'championship';
}
