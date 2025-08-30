<?php

declare(strict_types=1);

namespace App\Filament\Resources\Nations\Pages;

use App\Filament\Resources\Nations\NationResource;
use Filament\Resources\Pages\EditRecord;

final class EditNation extends EditRecord
{
    protected static string $resource = NationResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
