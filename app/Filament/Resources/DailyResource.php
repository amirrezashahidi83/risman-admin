<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DailyResource\Pages;
use App\Filament\Resources\DailyResource\RelationManagers;
use App\Models\Daily;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;

class DailyResource extends Resource
{
    protected static ?string $model = Daily::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->label('نام'),
                Textarea::make('text')->label('متن'),
                Textarea::make('link')->label('لینک'),
                Select::make('gender')
                ->options(
                    [
                        'male' => 'مرد',
                        'female' => 'زن'
                    ]
                )
                ->label('جنسیت'),
                ToggleColumn::make('status')->label('وضعیت')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('نام'),
                ToggleColumn::make('status')->label('وضعیت'),
                TextColumn::make('text')->label('متن جمله'),
                TextColumn::make('link')->label('لینک'),
                TextColumn::make('gender')->label('جنسیت'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListDailies::route('/'),
            'create' => Pages\CreateDaily::route('/create'),
            'edit' => Pages\EditDaily::route('/{record}/edit'),
        ];
    }
}
