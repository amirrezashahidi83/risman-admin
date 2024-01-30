<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CounselorPlanResource\Pages;
use App\Filament\Resources\CounselorPlanResource\RelationManagers;
use App\Models\CounselorPlan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CounselorPlanResource extends Resource
{
    protected static ?string $model = CounselorPlan::class;


    protected static ?string $navigationGroup = 'عملکرد';
    protected static ?string $modelLabel = 'برنامه مطالعاتی';
    protected static ?string $pluralModelLabel = 'برنامه های مطالعاتی';
    
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
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListCounselorPlans::route('/'),
            'create' => Pages\CreateCounselorPlan::route('/create'),
            'edit' => Pages\EditCounselorPlan::route('/{record}/edit'),
        ];
    }
}
