<?php

declare(strict_types=1);

namespace App\Filament\Resources\Players\Pages;

use App\Filament\Resources\Players\PlayerResource;
use Filament\Resources\Pages\CreateRecord;

final class CreatePlayer extends CreateRecord
{
    protected static string $resource = PlayerResource::class;
}
