<?php

declare(strict_types=1);

namespace App\Filament\Resources\Players\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

final class PlayersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('full_name')
                    ->label('Nome')
                    ->sortable()
                    ->searchable()
                    ->weight('bold'),

                TextColumn::make('know_as')
                    ->label('Conhecido Como')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('nation.name')
                    ->label('Nacionalidade')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('preferred_position')
                    ->label('Posição Principal')
                    ->badge()
                    ->color('primary'),

                TextColumn::make('weak_foot')
                    ->label('Pé Fraco')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        '1', '2' => 'danger',
                        '3' => 'warning',
                        '4', '5' => 'success',
                        default => 'gray',
                    }),

                TextColumn::make('skill_moves')
                    ->label('Habilidades')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        '1', '2' => 'danger',
                        '3' => 'warning',
                        '4', '5' => 'success',
                        default => 'gray',
                    }),

                TextColumn::make('birth_date')
                    ->label('Data de Nascimento')
                    ->date()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('length')
                    ->label('Altura (cm)')
                    ->numeric()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('weight')
                    ->label('Peso (kg)')
                    ->numeric()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('nation')
                    ->label('Nacionalidade')
                    ->relationship('nation', 'name'),
                SelectFilter::make('preferred_position')
                    ->label('Posição Principal')
                    ->options([
                        'Goalkeeper' => 'Goleiro',
                        'Defender' => 'Defensor',
                        'Midfielder' => 'Meio-campista',
                        'Forward' => 'Atacante',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('full_name', 'asc');
    }
}
