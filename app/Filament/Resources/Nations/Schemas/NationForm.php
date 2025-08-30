<?php

declare(strict_types=1);

namespace App\Filament\Resources\Nations\Schemas;

use Filament\Schemas\Schema;

final class NationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                //
            ]);
    }
}
