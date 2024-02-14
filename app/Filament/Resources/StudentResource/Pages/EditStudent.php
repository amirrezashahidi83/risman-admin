<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use Filament\Pages\Actions;
use App\Models\User;
use Filament\Pages\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use Hash;
class EditStudent extends EditRecord
{
    protected static string $resource = StudentResource::class;
        
    protected function getHeaderActions(): array
    {
        return [
            Action::make('delete')->
            label('حذف')
            ->action(function(array $data): void{
                $this->record->user->delete();
                $this->record->delete();
            })->requiresConfirmation(),


        ];
    }
}
