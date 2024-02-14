<?php

namespace App\Filament\Resources\CounselorResource\Pages;

use App\Filament\Resources\CounselorResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Hash;

class EditCounselor extends EditRecord
{
    protected static string $resource = CounselorResource::class;


    protected function getHeaderActions(): array
    {
        return [
		Actions\DeleteAction::make()
		->record($this->record->user),
        ];
    }
}
