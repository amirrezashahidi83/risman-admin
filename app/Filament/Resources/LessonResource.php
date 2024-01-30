<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LessonResource\Pages;
use App\Filament\Resources\LessonResource\RelationManagers;
use App\Models\Lesson;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TagsColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LessonResource extends Resource
{
    protected static ?string $model = Lesson::class;

    protected static ?string $navigationGroup = 'تنظیمات';
    protected static ?string $modelLabel = 'درس';
    protected static ?string $pluralModelLabel = 'درس ها';

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

    public static $types = [
        1 => 'عمومی',
        2 => 'تخصصی'
    ];

    public static $main = [
        0 => 'خیر',
        1 => 'بله'
    ];

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')->label('نام درس'),
                TagsInput::make('topics')->label('مباحث'),
                Select::make('grade')->label('پایه')->
                options(static::$grades),
                Select::make('major')->label('رشته')->
                options(static::$majors),
                Select::make('type')->label('عمومی یا تخصصی')->
                options(static::$types),
                Checkbox::make('main')->label('درس اصلی')

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->label('نام درس')
                ->sortable()->searchable(),
                TextColumn::make('grade')->label('پایه')->sortable(),
                TextColumn::make('major')->label('رشته')->sortable(),
                TextColumn::make('type')->label('عمومی یا تخصصی')->sortable(),
                TextColumn::make('main')->label('درس اصلی')->sortable()


            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListLessons::route('/'),
            'create' => Pages\CreateLesson::route('/create'),
            'edit' => Pages\EditLesson::route('/{record}/edit'),
        ];
    }    
}
