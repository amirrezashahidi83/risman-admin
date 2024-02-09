<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CounselorPlanResource\Pages;
use App\Filament\Resources\CounselorPlanResource\RelationManagers;
use App\Models\StudentPlan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Infolists\Components\ViewEntry;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Infolist;
use Auth;
class CounselorPlanResource extends Resource
{
    protected static ?string $model = StudentPlan::class;


    protected static ?string $navigationGroup = 'عملکرد';
    protected static ?string $modelLabel = 'برنامه مطالعاتی';
    protected static ?string $pluralModelLabel = 'برنامه های مطالعاتی';
    
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
        ->schema([
            Grid::make(1)->schema([
            ViewEntry::make('data')->label('برنامه')
            ->view('plan')
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
                TextColumn::make('plan.counselor.user.name')->label('مشاور')
                ->sortable()->searchable(),
                TextColumn::make('student.user.name')->label('دانش آموز')
                ->sortable()->searchable(),
                TextColumn::make('created_at')->label('تاریخ ارسال')
                ->sortable()->jalaliDateTime()
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),

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
    public static function getEloquentQuery(): Builder
    {
	    if( Auth::user()->role->value != 'super'){
		return parent::getEloquentQuery()->whereRelation('plan.counselor','admin_id',Auth::user()->id);
	    }
	return parent::getEloquentQuery();
    }

}
