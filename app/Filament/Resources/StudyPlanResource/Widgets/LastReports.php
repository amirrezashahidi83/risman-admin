<?php

namespace App\Filament\Resources\StudyPlanResource\Widgets;

use App\Models\StudyPlan;
use App\Models\Admin;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Columns\TextColumn;
use Auth;
class LastReports extends BaseWidget
{
    public function table(Table $table): Table
    {
	$query = StudyPlan::query();
        $user = Auth::user();
        if($user->role->value != 'super'){
            $query = $query->whereRelation('student.counselor','admin_id',$user->id);
        }
        return $table
            ->heading('آخرین گزارشات')
    	    ->query(
                $query
            )
            ->columns([
                TextColumn::make('student.user.name')
                ->label('دانش آموز'),
                TextColumn::make('student.counselor.user.name')
                ->label('مشاور'),
                TextColumn::make('created_at')
                ->label('تاریخ ارسال')
                ->jalaliDateTime()
                ->since()
            ])->defaultSort('created_at', 'desc');
    }
}
