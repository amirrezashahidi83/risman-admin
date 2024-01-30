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
use Filament\Forms\Components\Hidden;
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
use App\Models\User;
use Filament\Forms\Components\CheckboxList;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Forms\Components\Textarea;
use Melipayamak\MelipayamakApi;
use Filament\Notifications\Notification;

class CounselorResource extends Resource
{
    protected static ?string $model = Counselor::class;

    protected static ?string $navigationGroup = 'کاربران';
    protected static ?string $modelLabel = 'مشاور';
    protected static ?string $pluralModelLabel = 'مشاوران';

    public static function getForm() : array {
        return [
            Section::make('اطلاعات عمومی')->label('')
            ->schema([
                Hidden::make('counselor.user.rate')->default(0),
                Hidden::make('counselor.user.password')->default(Hash::make('12345678')),
        Hidden::make('counselor.user.role')->default(1),
                Grid::make('')->schema(
                    [
                        TextInput::make('counselor.user.name')->label('نام و نام خانوادگی')->required(),
                        TextInput::make('counselor.user.phoneNumber')->label('شماره تلفن')->required()
                        ->unique(column: 'phoneNumber',ignoreRecord: true,table: User::class),
                    ]
                )->columns(2),
                Select::make('counselor.user.status')->label('وضعیت')
                ->required()->options([
                    0 => 'غیر فعال',
                    1 => 'فعال'
                ])
            ]),
            Section::make('اطلاعات مشاور')->label('')
            ->schema([
                Grid::make('')->schema([
                TextInput::make('counselor.counselor.code')->label('کد مشاوره')->readonly(fn (Page $livewire) => $livewire instanceof EditRecord)
                ->afterStateHydrated(function (TextInput $component,$state) {
                    $component->state(! $state ? Str::random(8) : $state);
                })->unique(column: 'code',ignoreRecord: true,table: Counselor::class),
                ])->columns(1),
                ])
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Section::make('اطلاعات عمومی')->label('')
                ->relationship('user')
                ->schema([
		    Hidden::make('role')->default(1),
                    Grid::make('')->schema(
                        [
                            TextInput::make('name')->label('نام و نام خانوادگی')->required(),
                            TextInput::make('phoneNumber')->label('شماره تلفن')->required()
                            ->unique(column: 'phoneNumber',ignoreRecord: true),
                        ]
                    )->columns(2),

                    Grid::make('')->schema(
                        [
                            FileUpload::make('profilePic')->label('عکس پروفایل')->disk('public')
			    ->default('/logo192.png')
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
		    ImageColumn::make('user.profilePic')->label('عکس پروفایل')
		    ->state(function (Counselor $record) {
                    return 'https://risman.app'.$record->user->profilePic;
                }),
                    TextColumn::make('code')->label('کد مشاوره')
                    ->searchable()->sortable(),
                    TextColumn::make('user.status')->label('وضعیت')->sortable(),
                    TextColumn::make('created_at')->label('تاریخ ثبت نام')->sortable()
                ])
                ->filters([
                    TernaryFilter::make('status')->label('وضعیت')
                    ->options(
                        [
                            0 => 'غیرفعال',
                            1 => 'فعال'
                        ]
                    )->attribute('user.status')
                    ->trueLabel('فعال')
                    ->falseLabel('غیرفعال'),            
                Filter::make('created_at')->label('تاریخ ثبت نام')
                ->form([
                    DatePicker::make('created_from')->label('شروع'),
                    DatePicker::make('created_until')->label('پایان'),
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
                Tables\Actions\EditAction::make(),
                Action::make('delete')->
                action(function($record): void{
                    $user_id = $record['user']['id'];
                    $record->delete();
                    User::where('id',$user_id)->first()->delete();
                })->requiresConfirmation(),
                ReplicateAction::make()
                ->form([
                    CheckboxList::make('relations')
                    ->options([
                        'students' => 'کپی دانش آموزان',
                    ])
                ])
                ->after(function (Model $replica,array $data): void {

                    $newUser = $replica->user->replicate();
                    $newUser->password = Hash::make('123456789');
                    $newUser->name = "اکانت تست مشاور";
                    $newUser->phoneNumber = strval(mt_rand(10000000000,99999999999));
                    $newUser->save();
                    $replica->user()->associate($newUser);
                    $replica->code = Str::random(8);
                    $replica->save();

                    if( isset($data['students']))
                    foreach($replica->students as $student){
                        $newStudent = $student->replicate();
                        $newStudent->password = Hash::make('123456789');
                        $newStudent->name = "اکانت تست دانش آموز";
                        $newStudent->phoneNumber = strval(mt_rand(10000000000,99999999999));
                        $newStudent->counselor_id = $replica->id;
                        $newUser->save();
                    }

                }),
                Action::make('sms')
                ->label('ارسال پیامک')
                ->form([
                    Textarea::make('text')
                    ->label('متن پیامک')
                    ->required()
                ])
                ->action(function(array $data,$record) : void{
                    $username = '9122245852';
                    $password = '34fc5';
                    $api = new MelipayamakApi($username,$password);
                    $sms = $api->sms('soap');
                    $result = json_decode($sms->sendByBaseNumber(array($data['text']),$record->user->phoneNumber,192728));
                    Notification::make()
                    ->title('ارسال شد')
                    ->body('پیامک برای مشاور ' . $record->user->name . ' با موفقیت ارسال شد')
                    ->success()
                    ->send();    

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
                BulkAction::make('sms')
                ->label('ارسال پیامک گروهی')
                ->form([
                    Textarea::make('text')
                    ->label('متن پیامک')
                    ->required()
                ])
                ->action(function(array $data,$records) : void{
                    $username = '9122245852';
                    $password = '34fc5';
                    $from = "500010608307";
                    $api = new MelipayamakApi($username,$password);
                    $sms = $api->sms('soap');
                    foreach($records as $record){
                        $result = json_decode($sms->sendByBaseNumber(array($data['text']),$record->user->phoneNumber,192728));
                        Notification::make()
                        ->title('ارسال شد')
                        ->body('پیامک برای مشاور ' . $record->user->name . ' با موفقیت ارسال شد')
                        ->success()
                        ->send();    
                    }

                })

            ])
            ->paginated([10, 25, 50, 100,250, 'all']);
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
