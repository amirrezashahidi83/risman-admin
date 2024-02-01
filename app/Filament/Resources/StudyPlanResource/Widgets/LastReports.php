<?php

namespace App\Filament\Resources\StudyPlanResource\Widgets;

use App\Models\StudyPlan;
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
                StudyPlan::query()
            )
            ->columns([
                TextColumn::make('student.user.name')
                ->label('دانش آموز'),
                TextColumn::make('student.counselor.user.name')
                ->label('مشاور'),
                TextColumn::make('created_at')
                ->label('تاریخ ارسال')
                ->since()
            ])->defaultSort('created_at', 'desc');
    }
}
