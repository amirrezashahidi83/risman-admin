<?php

namespace App\Filament\Resources\CounselorPlanResource\Widgets;

use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Table;
use App\Models\StudentPlan;
use Filament\Tables\Columns\TextColumn;

class LastPlans extends BaseWidget
{

    public function table(Table $table): Table
    {
        return $table
            ->heading('آخرین برنامه ها')
            ->query(
                StudentPlan::query()
            )
            ->columns([
                TextColumn::make('plan.counselor.user.name')->label('مشاور'),
                TextColumn::make('student.user.name')->label('دانش آموز'),
                TextColumn::make('created_at')->label('تاریخ ارسال')->since(),
            ])->defaultSort('created_at', 'desc')
            ->paginated([10]);
    }

}
