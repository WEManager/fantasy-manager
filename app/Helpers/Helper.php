<?php

declare(strict_types=1);

use App\Models\Season;

function getCurrentSeason(): ?Season
{
    return Season::whereDate('start_time', '<=', now())
        ->whereDate('end_time', '>=', now())
        ->first();
}

/** @return array<string> */
function getContractType(string $squad): array
{
    return match (mb_strtolower($squad)) {
        'u19' => ['youth'],
        'u21' => ['reserve'],
        default => ['key', 'regular'],
    };
}

/** @return array<string> */
function getPositions(): array
{
    return [
        'GK',
        'LD', 'CLD', 'CD', 'CRD', 'RD',
        'LM', 'CLM', 'CM', 'CRM', 'RM',
        'LF', 'CLF', 'CF', 'CRF', 'RF',
    ];
}

/** @return array<string> */
function getPositionsExceptGoalkeeper(): array
{
    $positions = getPositions();

    foreach ($positions as $key => $position) {
        if ($position === 'GK') {
            unset($positions[$key]);
        }
    }

    return $positions;
}
