<?php

namespace App\Filament\Resources\Advocates\Pages;

use App\Filament\Resources\Advocates\AdvocateResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditAdvocate extends EditRecord
{
    protected static string $resource = AdvocateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
