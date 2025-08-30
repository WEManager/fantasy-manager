<?php

declare(strict_types=1);

namespace App\Filament\Resources\Nations;

// use App\Filament\Resources\Nations\Pages\CreateNation;
use App\Filament\Resources\Nations\Pages\EditNation;
use App\Filament\Resources\Nations\Pages\ListNations;
use App\Filament\Resources\Nations\Schemas\NationForm;
use App\Filament\Resources\Nations\Tables\NationsTable;
use App\Models\Nation;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

final class NationResource extends Resource
{
    protected static ?string $model = Nation::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFlag;

    public static function form(Schema $schema): Schema
    {
        return NationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return NationsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListNations::route('/'),
            // 'create' => CreateNation::route('/create'),
            'edit' => EditNation::route('/{record}/edit'),
        ];
    }
}
