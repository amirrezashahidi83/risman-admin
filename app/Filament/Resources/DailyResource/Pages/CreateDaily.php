<?php

namespace App\Filament\Resources\DailyResource\Pages;

use App\Filament\Resources\DailyResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateDaily extends CreateRecord
{
    protected static string $resource = DailyResource::class;
}
