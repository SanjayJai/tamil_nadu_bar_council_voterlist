<?php

namespace App\Filament\Resources\Advocates\Pages;

use App\Filament\Resources\Advocates\AdvocateResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewAdvocate extends ViewRecord
{
    protected static string $resource = AdvocateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
