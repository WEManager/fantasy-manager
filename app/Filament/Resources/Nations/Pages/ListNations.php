<?php

declare(strict_types=1);

namespace App\Filament\Resources\Nations\Pages;

use App\Filament\Resources\Nations\NationResource;
use Filament\Resources\Pages\ListRecords;

final class ListNations extends ListRecords
{
    protected static string $resource = NationResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
