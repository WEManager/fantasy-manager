<?php

declare(strict_types=1);

namespace App\Filament\Resources\Players\Schemas;

use App\Models\Nation;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

final class PlayerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('full_name')
                    ->label('Nome Completo')
                    ->required()
                    ->maxLength(255),

                TextInput::make('know_as')
                    ->label('Conhecido Como')
                    ->maxLength(255),

                TextInput::make('fifa_player_id')
                    ->label('ID FIFA')
                    ->numeric()
                    ->unique(ignoreRecord: true),

                Select::make('nation_id')
                    ->label('Nacionalidade')
                    ->options(Nation::pluck('name', 'id'))
                    ->searchable()
                    ->required(),

                DatePicker::make('birth_date')
                    ->label('Data de Nascimento')
                    ->required(),

                TextInput::make('length')
                    ->label('Altura (cm)')
                    ->numeric()
                    ->minValue(100)
                    ->maxValue(250),

                TextInput::make('weight')
                    ->label('Peso (kg)')
                    ->numeric()
                    ->minValue(30)
                    ->maxValue(150),

                Select::make('preferred_foot')
                    ->label('Pé Preferido')
                    ->options([
                        'right' => 'Direito',
                        'left' => 'Esquerdo',
                        'both' => 'Ambos',
                    ])
                    ->required(),

                Select::make('weak_foot')
                    ->label('Pé Fraco')
                    ->options([
                        1 => '1 - Muito Fraco',
                        2 => '2 - Fraco',
                        3 => '3 - Regular',
                        4 => '4 - Bom',
                        5 => '5 - Muito Bom',
                    ])
                    ->required(),

                Select::make('skill_moves')
                    ->label('Habilidades')
                    ->options([
                        1 => '1 - Muito Básico',
                        2 => '2 - Básico',
                        3 => '3 - Regular',
                        4 => '4 - Bom',
                        5 => '5 - Muito Bom',
                    ])
                    ->required(),

                TagsInput::make('positions')
                    ->label('Posições')
                    ->separator(',')
                    ->suggestions([
                        'GK', 'CB', 'LB', 'RB', 'DM', 'CM', 'AM', 'LW', 'RW', 'ST',
                    ]),

                TagsInput::make('specialities')
                    ->label('Especialidades')
                    ->separator(',')
                    ->suggestions([
                        'Speed Dribbler', 'Dribbler', 'Playmaker', 'Crosser', 'Finisher',
                    ]),

                TagsInput::make('play_styles')
                    ->label('Estilos de Jogo')
                    ->separator(',')
                    ->suggestions([
                        'Tiki-taka', 'Counter-attack', 'Possession', 'Direct',
                    ]),

                // Atributos Físicos
                TextInput::make('stats.physical.agility')
                    ->label('Agilidade')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100),

                TextInput::make('stats.physical.sprint_speed')
                    ->label('Velocidade')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100),

                TextInput::make('stats.physical.stamina')
                    ->label('Resistência')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100),

                TextInput::make('stats.physical.strength')
                    ->label('Força')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100),

                // Atributos Técnicos
                TextInput::make('stats.technical.ball_control')
                    ->label('Controle de Bola')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100),

                TextInput::make('stats.technical.dribbling')
                    ->label('Drible')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100),

                TextInput::make('stats.technical.finishing')
                    ->label('Finalização')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100),

                TextInput::make('stats.technical.short_passing')
                    ->label('Passe Curto')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100),

                TextInput::make('stats.technical.long_passing')
                    ->label('Passe Longo')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100),

                TextInput::make('stats.technical.shot_power')
                    ->label('Potência de Chute')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100),

                // Atributos Mentais
                TextInput::make('stats.mental.aggression')
                    ->label('Agressividade')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100),

                TextInput::make('stats.mental.composure')
                    ->label('Compostura')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100),

                TextInput::make('stats.mental.vision')
                    ->label('Visão')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100),

                // Atributos de Goleiro
                TextInput::make('stats.goalkeeping.gk_diving')
                    ->label('Mergulho (GK)')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100),

                TextInput::make('stats.goalkeeping.gk_handling')
                    ->label('Defesa com as Mãos (GK)')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100),

                TextInput::make('stats.goalkeeping.gk_reflexes')
                    ->label('Reflexos (GK)')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100),

                // Outros atributos
                KeyValue::make('stats.raw')
                    ->label('Outros Atributos')
                    ->keyLabel('Atributo')
                    ->valueLabel('Valor')
                    ->addActionLabel('Adicionar Atributo')
                    ->reorderable(),
            ]);
    }
}
