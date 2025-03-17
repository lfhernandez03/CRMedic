<?php

namespace App\Filament\Resources\SpecialitiesResource\Pages;

use App\Filament\Resources\SpecialitiesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSpecialities extends ListRecords
{
    protected static string $resource = SpecialitiesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
