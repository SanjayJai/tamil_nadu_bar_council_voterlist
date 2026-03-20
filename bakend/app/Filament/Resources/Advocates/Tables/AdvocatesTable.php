<?php

namespace App\Filament\Resources\Advocates\Tables;

use App\Models\Advocate;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class AdvocatesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('enrollment_no_str')
                    ->label('Enrollment no')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn($state, $record) => (string) ($state ?? $record->enrollment_no)),
                TextColumn::make('year')
                    ->sortable()
                    ->formatStateUsing(fn($state) => (string) $state),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('gender')
                    ->searchable(),
                TextColumn::make('father_name')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('bar_association')
                    ->searchable(),
                TextColumn::make('membership_details')
                    ->label('Membership')
                    ->searchable(),
                TextColumn::make('address')
                    ->label('Address')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->formatStateUsing(fn($state) => strlen((string)$state) > 60 ? substr($state,0,60) . '…' : $state),
                TextColumn::make('district')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            
            ->paginationPageOptions([10, 25, 50, 100, 500])
            ->defaultPaginationPageOption(10)
            ->filters([
                SelectFilter::make('gender')
                    ->label('Gender')
                    ->options([
                        'MALE' => 'Male',
                        'FEMALE' => 'Female',
                        'OTHER' => 'Other',
                    ]),
                SelectFilter::make('year')
                    ->label('Year')
                    ->options(fn () => Advocate::query()->select('year')->distinct()->pluck('year', 'year')->toArray()),
                SelectFilter::make('bar_association')
                    ->label('Bar Association')
                    ->options(fn () => Advocate::query()->select('bar_association')->distinct()->pluck('bar_association', 'bar_association')->filter()->toArray()),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
