<?php

namespace App\Filament\Resources\NandaResource\Pages;

use App\Filament\Resources\NandaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNanda extends EditRecord
{
    protected static string $resource = NandaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
