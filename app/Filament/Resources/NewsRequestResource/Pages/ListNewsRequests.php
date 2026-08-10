<?php

namespace App\Filament\Resources\NewsRequestResource\Pages;

use App\Filament\Resources\NewsRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNewsRequests extends ListRecords
{
    protected static string $resource = NewsRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
