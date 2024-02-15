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
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Grouping\Group;

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
                ->sortable()->jalaliDateTime()->since()
            ])
            ->filters([
                Filter::make('created_at')->label('تاریخ ثبت نام')
                ->form([
                    DatePicker::make('created_from')->label('شروع')
                    ->jalali(),
                    DatePicker::make('created_until')->label('پایان')
                    ->jalali(),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['created_from'],
                            fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                        )
                        ->when(
                            $data['created_until'],
                            fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                        );
                })

            ])
            ->actions([
                Tables\Actions\ViewAction::make(),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->groups([
                Group::make('plan.counselor.user.name')->label('نام مشاور'),
                Group::make('student.user.name')->label('نام دانش آموز'),
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
