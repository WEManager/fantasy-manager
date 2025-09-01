<?php

declare(strict_types=1);

namespace App\Filament\Resources\Players\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

final class PlayerView
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('full_name')
                    ->label('Nome Completo'),
                TextEntry::make('know_as')
                    ->label('Conhecido Como'),
                TextEntry::make('fifa_player_id')
                    ->label('ID FIFA'),
                TextEntry::make('nation.name')
                    ->label('Nacionalidade'),
                TextEntry::make('birth_date')
                    ->label('Data de Nascimento'),
                TextEntry::make('length')
                    ->label('Altura (cm)'),
                TextEntry::make('weight')
                    ->label('Peso (kg)'),
                TextEntry::make('preferred_foot')
                    ->label('Pé Preferido'),
                TextEntry::make('weak_foot')
                    ->label('Pé Fraco'),
                TextEntry::make('skill_moves')
                    ->label('Habilidades'),
                TextEntry::make('positions')
                    ->label('Posições'),
                TextEntry::make('specialities')
                    ->label('Especialidades'),
                TextEntry::make('play_styles')
                    ->label('Estilos de Jogo'),
            ]);
    }
}
