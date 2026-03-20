<?php

namespace App\Filament\Resources\Advocates\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;

class AdvocateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('enrollment_no_str')
                    ->label('Enrollment no')
                    ->required()
                    ->helperText('You may include suffixes like (a). Stored as string.'),
                TextInput::make('year')
                    ->required()
                    ->numeric(),
                TextInput::make('name')
                    ->required(),
                Select::make('gender')
                    ->required()
                    ->options([
                        'MALE' => 'Male',
                        'FEMALE' => 'Female',
                        'OTHER' => 'Other',
                    ])
                    ->searchable(),
                FileUpload::make('attachment')
                    ->label('Attachment')
                    ->directory('advocates')
                    ->maxSize(10240)
                    ->preserveFilenames()
                    ->visibility('public')
                    ->helperText('Optional file for this advocate (PDF, image, etc.). Max 10MB).'),
                TextInput::make('father_name'),
                TextInput::make('membership_details')
                    ->label('Membership')
                    ->helperText('e.g., Member / Non Member')
                    ->nullable(),
                TextInput::make('address')
                    ->label('Address')
                    ->nullable(),
                TextInput::make('bar_association')
                    ->required(),
                TextInput::make('district')
                    ->required(),
            ]);
    }
}
