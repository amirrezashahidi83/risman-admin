<?php

namespace App\Filament\Resources\StudyPlanResource\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Columns\TextColumn;

class LastReports extends BaseWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->heading('آخرین گزارشات')
            ->query(
                // ...
            )
            ->columns([
                
            ]);
    }
}
