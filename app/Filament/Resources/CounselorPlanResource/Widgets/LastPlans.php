<?php

namespace App\Filament\Resources\CounselorPlanResource\Widgets;

use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Table;
use App\Models\StudentPlan;
use Filament\Tables\Columns\TextColumn;
use App\Models\Admin;
use Auth;

class LastPlans extends BaseWidget
{

    public function table(Table $table): Table
    {
        $query = StudentPlan::query();
        $user = Auth::user();
        if($user->role->value != 'super'){
            $query = $query->whereRelation('plan.counselor','admin_id',$user->id);
        }

        return $table
            ->heading('آخرین برنامه ها')
            ->query(
                $query
            )
            ->columns([
                TextColumn::make('plan.counselor.user.name')->label('مشاور'),
                TextColumn::make('student.user.name')->label('دانش آموز'),
                TextColumn::make('created_at')->label('تاریخ ارسال')->since(),
            ])->defaultSort('created_at', 'desc')
            ->paginated([10]);
    }

}
