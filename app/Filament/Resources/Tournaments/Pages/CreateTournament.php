<?php

declare(strict_types=1);

namespace App\Filament\Resources\Tournaments\Pages;

use App\Filament\Resources\Tournaments\TournamentResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateTournament extends CreateRecord
{
    protected static string $resource = TournamentResource::class;
}
