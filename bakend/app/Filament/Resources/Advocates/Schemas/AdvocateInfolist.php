<?php

namespace App\Filament\Resources\Advocates\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class AdvocateInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('enrollment_no_str')
                    ->label('Enrollment no')
                    ->placeholder('-'),
                TextEntry::make('year')
                    ->numeric(),
                TextEntry::make('name'),
                TextEntry::make('gender'),
                TextEntry::make('father_name')
                    ->placeholder('-'),
                TextEntry::make('bar_association'),
                TextEntry::make('district'),
                TextEntry::make('membership_details')
                    ->label('Membership')
                    ->placeholder('-'),
                TextEntry::make('address')
                    ->label('Address')
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
