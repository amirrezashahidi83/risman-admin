<?php

namespace App\Filament\Resources\DailyResource\Pages;

use App\Filament\Resources\DailyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDailies extends ListRecords
{
    protected static string $resource = DailyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
