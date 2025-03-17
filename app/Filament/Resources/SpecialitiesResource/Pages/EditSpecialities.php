<?php

namespace App\Filament\Resources\SpecialitiesResource\Pages;

use App\Filament\Resources\SpecialitiesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSpecialities extends EditRecord
{
    protected static string $resource = SpecialitiesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
