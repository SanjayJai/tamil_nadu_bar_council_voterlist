<?php

namespace App\Filament\Resources\Advocates;

use App\Filament\Resources\Advocates\Pages\CreateAdvocate;
use App\Filament\Resources\Advocates\Pages\EditAdvocate;
use App\Filament\Resources\Advocates\Pages\ListAdvocates;
use App\Filament\Resources\Advocates\Pages\ViewAdvocate;
use App\Filament\Resources\Advocates\Schemas\AdvocateForm;
use App\Filament\Resources\Advocates\Schemas\AdvocateInfolist;
use App\Filament\Resources\Advocates\Tables\AdvocatesTable;
use App\Models\Advocate;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AdvocateResource extends Resource
{
    protected static ?string $model = Advocate::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return AdvocateForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return AdvocateInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AdvocatesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'enrollment_no_str',
            'name',
            'address',
            'bar_association',
        ];
    }

    public static function getGlobalSearchResultDetails(\Illuminate\Database\Eloquent\Model $record): array
    {
        return [
            'Enrollment' => (string) ($record->enrollment_no_str ?? $record->enrollment_no ?? ''),
            'Location' => (string) ($record->address ?? ''),
            'Bar Association' => (string) ($record->bar_association ?? ''),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAdvocates::route('/'),
            'create' => CreateAdvocate::route('/create'),
            'view' => ViewAdvocate::route('/{record}'),
            'edit' => EditAdvocate::route('/{record}/edit'),
        ];
    }
}
