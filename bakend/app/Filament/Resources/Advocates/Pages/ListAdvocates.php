<?php

namespace App\Filament\Resources\Advocates\Pages;

use App\Filament\Resources\Advocates\AdvocateResource;
use App\Imports\AdvocatesImport;
use Filament\Actions\CreateAction;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ListAdvocates extends ListRecords
{
    protected static string $resource = AdvocateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),

            Action::make('import')
                ->label('Import Excel')
                ->icon('heroicon-o-arrow-up-tray')
                ->form([
                    FileUpload::make('files')
                        ->label('Excel Files (.xlsx)')
                        ->multiple()
                        ->disk('local') // IMPORTANT
                        ->directory('imports') // IMPORTANT
                        ->acceptedFileTypes([
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        ])
                        ->required(),
                ])
                ->action(function (array $data) {

                    $files = [];
                    if (isset($data['files'])) {
                        $files = is_array($data['files']) ? $data['files'] : [$data['files']];
                    }

                    if (count($files) === 0) {
                        Notification::make()
                            ->danger()
                            ->title('No files')
                            ->body('No files were uploaded for import.')
                            ->send();

                        return redirect()->back();
                    }

                    $successCount = 0;
                    $errors = [];

                    foreach ($files as $file) {
                        try {
                            $path = Storage::disk('local')->path($file);

                            Excel::import(
                                new AdvocatesImport,
                                $path
                            );

                            // delete uploaded file
                            try {
                                Storage::disk('local')->delete($file);
                            } catch (\Throwable $_) {
                                // ignore delete errors
                            }

                            $successCount++;
                        } catch (\Throwable $e) {
                            // attempt to remove file on failure as well
                            try {
                                Storage::disk('local')->delete($file);
                            } catch (\Throwable $_) {
                                // ignore
                            }

                            $errors[] = ($file . ': ' . $e->getMessage());
                        }
                    }

                    if (count($errors) === 0) {
                        Notification::make()
                            ->success()
                            ->title('Import Completed')
                            ->body("Imported {$successCount} file(s) successfully.")
                            ->send();
                    } else {
                        Notification::make()
                            ->warning()
                            ->title('Import Completed With Errors')
                            ->body("Imported {$successCount} file(s). " . implode(' ; ', array_slice($errors, 0, 3)))
                            ->send();
                    }

                    // Redirect back to refresh the Filament list view in a safe, compatible way.
                    return redirect()->back();
                }),
        ];
    }
}
