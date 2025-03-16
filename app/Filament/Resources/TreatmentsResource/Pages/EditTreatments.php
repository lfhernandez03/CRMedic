<?php

namespace App\Filament\Resources\TreatmentsResource\Pages;

use App\Filament\Resources\TreatmentsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTreatments extends EditRecord
{
    protected static string $resource = TreatmentsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
