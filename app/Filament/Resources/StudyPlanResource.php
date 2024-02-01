<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudyPlanResource\Pages;
use App\Filament\Resources\StudyPlanResource\RelationManagers;
use App\Models\StudyPlan;
use App\Models\StudyTime;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\KeyValueEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Infolists\Components\Grid;

class StudyPlanResource extends Resource
{
    protected static ?string $model = StudyPlan::class;

    protected static ?string $navigationGroup = 'عملکرد';
    protected static ?string $modelLabel = 'گزارش مطالعه';
    protected static ?string $pluralModelLabel = 'گزارشات مطالعاتی';


    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
        ->schema([
            Grid::make(1)->schema([
            ViewEntry::make('data')->label('برنامه')
            ->view('study_entry')
            ])
        ]);
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('student.user.name')->label('دانش آموز')
                ->searchable(),
                TextColumn::make('student.counselor.user.name')->label('مشاور')
                ->searchable(),
                TextColumn::make('day')->label('روز')
                ->sortable()->searchable(),
                TextColumn::make('created_at')->label('تاریخ ارسال')
                ->sortable()->searchable()->jalaliDateTime()
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make()->label('مشاهده گزارش'),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudyPlans::route('/'),
            'create' => Pages\CreateStudyPlan::route('/create'),
            'edit' => Pages\EditStudyPlan::route('/{record}/edit'),
        ];
    }    
}
