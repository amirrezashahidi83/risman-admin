<?php

namespace App\Filament\Resources\CounselorPlanResource\Pages;

use App\Filament\Resources\CounselorPlanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCounselorPlan extends EditRecord
{
    protected static string $resource = CounselorPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
