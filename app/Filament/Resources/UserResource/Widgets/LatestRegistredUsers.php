<?php

namespace App\Filament\Resources\UserResource\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TagsColumn;
use Filament\Tables\Columns\ImageColumn;
use App\Models\User;
use Filament\Tables\Filters\SelectFilter;

class LatestRegistredUsers extends BaseWidget
{
    public function table(Table $table): Table
    {
        $query = User::query();
        if(auth()->user()->role->value != 'super'){
	}	
        return $table
            ->heading('آخرین ثبت نامی ها')
            ->filters([
                
                SelectFilter::make('role')->label('نقش')
                ->options(
                    [
                        1 => 'مشاور',
                        2 => 'دانش آموز'
                    ]
                )
                
            ])
            ->query(
                User::query()
            )
            ->columns([
                TextColumn::make('name')->label('نام'),
                TextColumn::make('role')->label('نقش'),
                ImageColumn::make('profilePic')->label('عکس'),
               // TextColumn::make('created_at')->label('تاریخ ثبت نام')->jalaliDateTime()->since(),
            ])->defaultSort('created_at', 'desc')
            ->paginated([10]);
    }
}
