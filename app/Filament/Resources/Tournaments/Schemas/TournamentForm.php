<?php

declare(strict_types=1);

namespace App\Filament\Resources\Tournaments\Schemas;

use App\Enums\TournamentType;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

final class TournamentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                Select::make('type')
                    ->options(TournamentType::class)
                    ->required()
                    ->default(TournamentType::LEAGUE),

                TextInput::make('participants')
                    ->numeric()
                    ->required()
                    ->minValue(1),

                TextInput::make('groups')
                    ->numeric()
                    ->required()
                    ->minValue(1)
                    ->maxValue(8),

                Toggle::make('playoffs')
                    ->default(false),

                TextInput::make('proceeding_to_playoffs')
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(8)
                    ->visible(fn ($get) => $get('playoffs')),
            ]);
    }
}
