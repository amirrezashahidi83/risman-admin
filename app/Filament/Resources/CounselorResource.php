<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CounselorResource\Pages;
use App\Filament\Resources\CounselorResource\RelationManagers;
use App\Models\Counselor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TagsColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\CheckboxColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Checkbox;
use Filament\Tables\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Tables\Actions\ReplicateAction;
use Filament\Tables\Actions\BulkAction;
use Filament\Forms\Components\Section;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Pages\Page;
use Filament\Forms\Components\Grid;
use Illuminate\Support\Str;
use Hash;
use Illuminate\Database\Eloquent\Model;

class CounselorResource extends Resource
{
    protected static ?string $model = Counselor::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
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
                            ->afterStateHydrated(function (TextInput $component,$state) {
                                if(fn (Page $livewire) => $livewire instanceof EditRecord){
                                    $component->state("");
                                }
                            })
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

    public static function table(Table $table): Table
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
                ReplicateAction::make()
                ->after(function (Model $replica): void {
                    $newUser = $replica->user->replicate();
                    $newUser->password = Hash::make('123456789');
                    $newUser->name = "اکانت تست";
                    $newUser->phoneNumber = strval(mt_rand(10000000000,9999999999));
                    $newUser->save();
                    $replica->user()->associate($newUser);
                    $replica->code = Str::random(8);
                    $replica->save();
                })
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
    
    public static function getRelations(): array
    {
        return [
            RelationManagers\UsersRelationManager::class,
            RelationManagers\StudentsRelationManager::class

        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCounselors::route('/'),
            'create' => Pages\CreateCounselor::route('/create'),
            'edit' => Pages\EditCounselor::route('/{record}/edit'),
        ];
    }    

    public function isTableSearchable(): bool
    {
        return true;
    }

}
