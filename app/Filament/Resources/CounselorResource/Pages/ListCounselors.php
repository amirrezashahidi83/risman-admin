<?php

namespace App\Filament\Resources\CounselorResource\Pages;

use App\Filament\Resources\CounselorResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCounselors extends ListRecords
{
    protected static string $resource = CounselorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
