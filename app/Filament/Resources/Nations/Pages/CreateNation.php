<?php

declare(strict_types=1);

namespace App\Filament\Resources\Nations\Pages;

use App\Filament\Resources\Nations\NationResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateNation extends CreateRecord
{
    protected static string $resource = NationResource::class;
}
