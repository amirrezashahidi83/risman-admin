<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentResource\Pages;
use App\Filament\Resources\StudentResource\RelationManagers;
use App\Models\Student;
use App\Models\User;
use App\Models\Schedule;
use App\Models\Counselor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TagsColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\CheckboxColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Hidden;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Forms\Components\Section;
use Filament\Tables\Actions\ReplicateAction;
use App\Models\Enums\MajorEnum;
use App\Models\Enums\GradeEnum;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Pages\Page;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\CheckboxList;
use Hash;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use App\Filament\Resources\UserResource;
use Filament\Forms\Components\Textarea;
use Melipayamak\MelipayamakApi;
use Filament\Notifications\Notification;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationGroup = 'کاربران';
    protected static ?string $modelLabel = 'دانش آموز';
    protected static ?string $pluralModelLabel = 'دانش آموزان';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('اطلاعات عمومی')->label('')
                ->relationship('user')
                ->schema([
		    Hidden::make('role')->default(2),
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
                            ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state))
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
                    Hidden::make('goal')->default('هدف شما')
                    ->dehydrated(fn (Page $livewire) => $livewire instanceof CreateRecord),
                    Select::make('major')->label('رشته')
                    ->options(MajorEnum::class),
                    Select::make('grade')->label('پایه')
                    ->options(GradeEnum::class),
                    Select::make('status')->label('وضعیت')->options([
                        0 => 'غیر فعال',
                        1 => 'فعال'
                    ])->required(),
                    TextInput::make('school')->label('مدرسه'),
                    Select::make('counselor_id')->label('مشاور')
                    ->
                    options(
                        Counselor::all()->pluck('user.name','id')->filter(function ($value,$key) {
                            return isset($value) && isset($key);
                        })
                    )
                    ->searchable(),
                    ]
                )
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
                TextColumn::make('school')->label('مدرسه')
                ->searchable()->sortable(),
                TextColumn::make('major')->label('رشته')->sortable(),
                TextColumn::make('grade')->label('پایه')->sortable(),
                TextColumn::make('user.status')->label('وضعیت')->sortable(),
                TextColumn::make('counselor.user.name')->label('نام مشاور'),
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
        SelectFilter::make('school'),
        SelectFilter::make('major')
        ->label('رشته')
        ->options([
            0 => 'بدون رشته',
            1 => 'ریاضی',
            2 => 'تجربی',
            3 => 'انسانی'
        ]),
        SelectFilter::make('grade')
        ->label('پایه')
        ->options([
            1 => 'هفتم',
            2 => 'هشتم',
            3 => 'نهم',
            4 => 'دهم',
            5 => 'یازدهم',
            6 => 'دوازدهم'
        ]),
		SelectFilter::make('counselor')
		->label('مشاور')->relationship('counselor','code'),
                Filter::make('created_at')->label('تاریخ ثبت نام')
                ->form([
                    Section::make()->label('ثبت نام')->schema([
                    DatePicker::make('created_from')->label('تاریخ شروع'),
                    DatePicker::make('created_until')->label('تاریخ پایان'),
                    ])
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
                    $record->user->delete();
                })->requiresConfirmation(),
                ReplicateAction::make()
                ->form(
                    [
                        CheckboxList::make('relations')
                        ->options([
                            'study_plans' => 'کپی گزارش ها',
                        ])    
                    ]
                )
                ->after(function (Model $replica,array $data): void {
                    $newUser = $replica->user->replicate();
                    $newUser->password = Hash::make('123456789');
                    $newUser->name = "اکانت تست";
                    $newUser->phoneNumber = strval(mt_rand(10000000000,99999999999));
                    $newUser->save();
                    $replica->user()->associate($newUser);
                    
                    if( isset($data['study_plans']))
                    foreach( $replica->studyPlans as $studyPlan){
                            $new_study = $studyPlan->replicate();
                            $new_study->student_id = $replica->id;
                            $new_study->save();
                    }

                    $replica->save();
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
                        ->body('پیامک برای دانش آموز ' . $record->user->name . ' با موفقیت ارسال شد')
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
                        ->body('پیامک برای دانش آموز ' . $record->user->name . ' با موفقیت ارسال شد')
                        ->success()
                        ->send();    
                    }

                })
,

            ])
            ->paginated([10, 25, 50, 100,250, 'all']);
    }
    
    public static function getRelations(): array
    {
        return [
            RelationManagers\UsersRelationManager::class
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }    

}
