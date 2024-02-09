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
        $role = auth()->user()->role->value;

        if($role == 'school'){
            $query = StudentPlan::whereRelation('counselor','admin_id',auth()->user()->id)
            ->orWhereRelation('counselor.admin','role','counselor');

        }else if($role == 'counselor'){
            $query = StudentPlan::whereRelation('counselor','admin_id',auth()->user()->id);
        }


        return $table
            ->heading('آخرین برنامه ها')
            ->query(
                $query
            )
            ->columns([
                TextColumn::make('plan.counselor.user.name')->label('مشاور'),
                TextColumn::make('student.user.name')->label('دانش آموز'),
                //TextColumn::make('created_at')->label('تاریخ ارسال')->jalaliDateTime()->since(),
            ])->defaultSort('created_at', 'desc')
            ->paginated([10]);
    }

}
