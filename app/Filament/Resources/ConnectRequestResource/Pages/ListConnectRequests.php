<?php

namespace App\Filament\Resources\ConnectRequestResource\Pages;

use App\Filament\Resources\ConnectRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListConnectRequests extends ListRecords
{
    protected static string $resource = ConnectRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
