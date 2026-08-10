<?php

namespace App\Filament\Resources\NewsRequestResource\Pages;

use App\Filament\Resources\NewsRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNewsRequest extends EditRecord
{
    protected static string $resource = NewsRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
