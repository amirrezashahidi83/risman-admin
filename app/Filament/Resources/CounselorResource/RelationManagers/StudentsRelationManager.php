<?php

namespace App\Filament\Resources\CounselorResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TagsColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\CheckboxColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Checkbox;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Forms\Components\Section;
use App\Models\Enums\MajorEnum;
use App\Models\Enums\GradeEnum;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Pages\Page;
use Filament\Resources\Pages\CreateRecord;
use Filament\Tables\Actions\ReplicateAction;
use Illuminate\Support\Str;
use Hash;

class StudentsRelationManager extends RelationManager
{
    protected static string $relationship = 'students';

    protected static ?string $recordTitleAttribute = 'name';

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
                        ->unique(ignoreRecord: true),
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
            Section::make('')->label('اطلاعات دانش آموز')
            ->schema(
                [
                Select::make('major')->label('رشته')
                ->options(MajorEnum::class),
                Select::make('grade')->label('پایه')
                ->options(GradeEnum::class),
                Select::make('status')->label('وضعیت')->options([
                    0 => 'غیر فعال',
                    1 => 'فعال'
                ]),
                TextInput::make('school')->label('مدرسه'),
                Select::make('counselor_id')->label('کد مشاور')
                ->
                relationship('counselor','code')
                ->searchable(),
                ]
            )
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
                TextColumn::make('school')->label('مدرسه')
                ->searchable()->sortable(),
                TextColumn::make('major')->label('رشته')->sortable(),
                TextColumn::make('grade')->label('پایه')->sortable(),
                TextColumn::make('user.status')->label('وضعیت')->sortable(),
                TextColumn::make('counselor.user.name')->label('نام مشاور'),
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
                    Schedule::where('user_id',$user_id)->first()->delete();
                    User::where('id',$user_id)->first()->delete();
                })->requiresConfirmation(),
                ReplicateAction::make()
                ->beforeReplicaSaved(function (Model $replica): void {
                    $replica->user->password = Hash::make('123456789');
                    $replica->user->name = "اکانت تست";
                    $replica->user->phoneNumber = Str::random(8);

                })

            ])
            ->bulkActions([
                BulkAction::make('delete')->
                action(function($records): void{
                    foreach($records as $record){
                        $user_id = $record['user']['id'];
                        $record->delete();
                        Schedule::where('user_id',$user_id)->first()->delete();
                        User::where('id',$user_id)->first()->delete();
                    }
                })->requiresConfirmation(),

            ]);
    }

}
