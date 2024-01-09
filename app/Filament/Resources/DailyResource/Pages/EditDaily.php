<?php

namespace App\Filament\Resources\DailyResource\Pages;

use App\Filament\Resources\DailyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDaily extends EditRecord
{
    protected static string $resource = DailyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
