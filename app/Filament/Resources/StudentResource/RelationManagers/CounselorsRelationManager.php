<?php

namespace App\Filament\Resources\StudentResource\RelationManagers;

use App\Models\Counselor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TagsColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\CheckboxColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Checkbox;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Forms\Components\Section;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Pages\Page;
use Filament\Forms\Components\Grid;
use Illuminate\Support\Str;
use Hash;

class CounselorsRelationManager extends RelationManager
{
    protected static ?string $inverseRelationship  = 'counselor';

    protected static ?string $recordTitleAttribute = 'id';

    public function form(Form $form): Form
    {
        return $form
            ->schema([

                Section::make('اطلاعات عمومی')->label('')
                ->relationship('user')
                ->schema([
                    Grid::make('')->schema(
                        [
                            TextInput::make('name')->label('نام و نام خانوادگی')->required(),
                            TextInput::make('phoneNumber')->label('شماره تلفن')->required()
                            ->unique(column: 'phoneNumber',ignoreRecord: true),
                        ]
                    )->columns(2),

                    Grid::make('')->schema(
                        [
                            FileUpload::make('profilePic')->label('عکس پروفایل')->disk('public'),
                        ]
                    )->columns(1),
                    Grid::make('')->schema(
                        [
                            TextInput::make('password')->label('رمز عبور')
                            ->password()->confirmed()
                            ->dehydrated(
                                function($livewire){
                                    return strlen($livewire->data['user']['password']) != 0;
                                }
                            )
                            ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                            ->required(fn (Page $livewire) => $livewire instanceof CreateRecord),
                            TextInput::make('password_confirmation')
                            ->label('تکرار رمز عبور')
                            ->password()->dehydrated(false)
    
                        ]
                    )->columns(2),
                    Select::make('status')->label('وضعیت')
                    ->required()->options([
                        0 => 'غیر فعال',
                        1 => 'فعال'
                    ])

                ]),
                Section::make('اطلاعات مشاور')->label('')
                ->schema([
                    Grid::make('')->schema([
                    TextInput::make('code')->label('کد مشاوره')->readonly(fn (Page $livewire) => $livewire instanceof EditRecord)
                    ->afterStateHydrated(function (TextInput $component,$state) {
                        $component->state(! $state ? Str::random(8) : $state);
                    })->unique(column: 'code',ignoreRecord: true),
                    Select::make('status')->label('پیام اتوماتیک')->options([
                        0 => 'غیر فعال',
                        1 => 'فعال'
                    ]),
                    Select::make('status')->label('وضعیت')->options([
                        0 => 'غیر فعال',
                        1 => 'فعال'
                    ]),
                    ])->columns(3),
                ])
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                    TextColumn::make('user.name')->label('نام')
                    ->searchable()->sortable(),
                    TextColumn::make('user.phoneNumber')->label('شماره تلفن')
                    ->searchable()->sortable(),
                    TextColumn::make('user.balance')->label('موجودی'),
                    TextColumn::make('user.score')->label('امتیاز'),
                    ImageColumn::make('user.profilePic')->label('عکس پروفایل'),
                    TextColumn::make('code')->label('کد مشاوره')
                    ->searchable()->sortable(),
                    TextColumn::make('user.status')->label('وضعیت')->sortable(),


                ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Action::make('delete')->
                action(function($record): void{
                    $user_id = $record['user']['id'];
                    $record->delete();
                    User::where('id',$user_id)->first()->delete();
                })->requiresConfirmation(),
            ])
            ->bulkActions([
                BulkAction::make('delete')->
                action(function($records): void{
                    foreach($records as $record){
                        $user_id = $record['user']['id'];
                        $record->delete();
                        User::where('id',$user_id)->first()->delete();
                    }
                })->requiresConfirmation(),

            ]);
    }
}
