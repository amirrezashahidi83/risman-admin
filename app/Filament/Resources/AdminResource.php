<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdminResource\Pages;
use App\Filament\Resources\AdminResource\RelationManagers;
use App\Models\Admin;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\Page;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\EditRecord;
use Hash;

class AdminResource extends Resource
{
    protected static ?string $model = Admin::class;

    protected static ?string $navigationGroup = 'کاربران';
    protected static ?string $modelLabel = 'ادمین';
    protected static ?string $pluralModelLabel = 'ادمین ها';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->label('نام')->required(),
                TextInput::make('username')->label('نام کاربری')->required(),
                TextInput::make('email')->label('ایمیل یا شماره تلفن')->required(),
                Select::make('role')->label('نقش')->required()
                ->options(
                    auth()->user()->role->value == 'super' ?
                    [
                    'school' => 'موسسه',
                    'counselor' => 'سر مشاور'
                    ]
                    :
                    [
                        'counselor' => 'سر مشاور'
                    ]
                ),
                Grid::make('')->schema(
                    [
                        TextInput::make('password')->label('رمز عبور')
                        ->password()->confirmed()
			->afterStateHydrated(function (TextInput $component,$state) {
				if(fn ($livewire) => $livewire instanceof EditRecord){
				  $component->state("");

														                                    }})
                        ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                        ->dehydrated(fn ($state) => filled($state))
                        ->required(fn (Page $livewire) => $livewire instanceof CreateRecord),
                        TextInput::make('password_confirmation')
                        ->label('تکرار رمز عبور')
                        ->password()->dehydrated(false)

                    ]
                )->columns(2),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('نام')
                ->searchable()->sortable(),
                TextColumn::make('username')->label('نام کاربری')
                ->searchable()->sortable(),
                TextColumn::make('email')->label('ایمیل یا شماره تلفن')
                ->searchable()->sortable(),
                TextColumn::make('role')->label('نقش')
                ->searchable()->sortable(),
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
            RelationManagers\CounselorsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAdmins::route('/'),
            'create' => Pages\CreateAdmin::route('/create'),
            'edit' => Pages\EditAdmin::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return Admin::whereNot('role','super');
    }
}
