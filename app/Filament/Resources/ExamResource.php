<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExamResource\Pages;
use App\Filament\Resources\ExamResource\RelationManagers;
use App\Models\Exam;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Checkbox;
use App\Filament\Resources\ExamResource\RelationMangers;
use Filament\Tables\Columns\TextColumn;

class ExamResource extends Resource
{
    protected static ?string $model = Exam::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static $majors = [
        1 => 'ریاضی',
        2 => 'تجربی',
        3 => 'انسانی'
    ];

    public static $grades = [
        1 => 'دهم',
        2 => 'یازدهم',
        3 => 'دوازدهم'
    ];

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')->label('نام'),
                TextInput::make('price')->label('قیمت'),
                Select::make('grade')->label('پایه')
                ->options(static::$grades),
                Select::make('major')->label('رشته')
                ->options(static::$grades),
                CheckBox::make('is_ready')->label('آیا آماده است؟'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->label('نام آزمون')
                ->sortable()->searchable(),
                TextColumn::make('price')->label('قیمت')
                ->sortable(),
                TextColumn::make('grade')->label('پایه')
                ->enum(static::$grades)->sortable(),
                TextColumn::make('major')->label('رشته تحصیلی')
                ->enum(static::$majors)->sortable()
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            RelationManagers\ExamPlansRelationManager::class
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListExams::route('/'),
            'create' => Pages\CreateExam::route('/create'),
            'edit' => Pages\EditExam::route('/{record}/edit'),
        ];
    }    
}
