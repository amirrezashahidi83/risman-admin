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
use Filament\Tables\Grouping\Group;
use DB;
use Auth;
use App\Models\Enums\AdminRoleEnum;
use Filament\Tables\Filters\Indicator;
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
                            ->unique(ignoreRecord: true)
                            ->disabled(! auth()->user()->hasRole('super_admin')),
                        ]
                    )->columns(2),

                    Grid::make('')->schema(
                        [
                            FileUpload::make('profilePic')->label('عکس پروفایل')->disk('public')
                            ->directory('images')->default('/logo192.png')
		    	    ->dehydrateStateUsing( function(array $state): string { 
					return count(array_values($state)) > 0 ? '/v1/storage/'.array_values($state)[0] : '';
			    })
                ->dehydrated(
                    function($get){
                        return count($get('profilePic')) != 0;
                    }
                ),
                        ]
                    )->columns(1),
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
                            ->required(fn ($livewire) => $livewire instanceof CreateRecord),
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
                    ->disabled(! auth()->user()->hasRole('super_admin'))
                ]),
                Section::make('')->label('اطلاعات دانش آموز')
                ->schema(
                    [
                    Hidden::make('goal')->default('هدف شما')
                    ->dehydrated(fn ($livewire) => $livewire instanceof CreateRecord),
                    Grid::make()->schema([
                        Select::make('major')->label('رشته')
                        ->options(MajorEnum::class),
                        Select::make('grade')->label('پایه')
                        ->options(GradeEnum::class),
                        Hidden::make('status')->default(true),
                    ])->columns(2),
                    Grid::make()->schema([
                        TextInput::make('school')->label('مدرسه'),
                        Select::make('counselor_id')->label('مشاور')
                        ->
                        options(
                            auth()->user()->hasRole('super_admin') ?
                            Counselor::all()->pluck('user.name','id')
                            ->filter(function ($value,$key) {
                                return isset($value) && isset($key);
                            })
                            ->map(function ($item,$key) {
                                return $item.' '.Counselor::find($key)->code;
			    })
			    :
			    Counselor::with('user')->where('admin_id',auth()->user()->id)->get()->pluck('user.name','id')
                            ->filter(function ($value,$key) {
                                return isset($value) && isset($key);
                            })
                            ->map(function ($item,$key) {
                                return $item.' '.Counselor::find($key)->code;
			    })


                        )
                        ->searchable(),
                    ])->columns(2)
                    ]
                )
            ]);
    }

    public static function table(Table $table): Table
    {
	return $table
	    ->groups(
	    	[
	        	Group::make('counselor.user.name')
			->label('نام مشاور')
		]
	    )
            ->columns([
                TextColumn::make('user.name')->label('نام')
                ->searchable()->sortable(),
                TextColumn::make('user.phoneNumber')->label('شماره تلفن')
                ->searchable()->sortable(),
                TextColumn::make('user.balance')->label('موجودی'),
                TextColumn::make('user.score')->label('امتیاز')->sortable(),
                ImageColumn::make('user.profilePic')->label('عکس پروفایل')
                ->state(function (Student $record) {
                    $suffix = isset($record->user->profilePic) && ! str_starts_with($record->user->profilePic,'/') ?
                        '/v1/storage/' : '';
                    return 'https://risman.app'.$suffix.( $record->user->profilePic ?? '');
                }),
                TextColumn::make('school')->label('مدرسه')
                ->searchable()->sortable(),
                TextColumn::make('major')->label('رشته')->sortable(),
                TextColumn::make('grade')->label('پایه')->sortable(),
                TextColumn::make('user.status')->label('وضعیت')->sortable(),
                TextColumn::make('counselor.user.name')->label('نام مشاور')
                ->state(function (Student $record) {
                    return isset($record->counselor) ? $record->counselor->user->name. ' '.$record->counselor->code : '';
                })->searchable()->sortable(),
                TextColumn::make('created_at')->label('تاریخ ثبت نام')->sortable()
                ->jalaliDateTime() 
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
        Filter::make('school')
        ->label('موسسه')
        ->form(
            [
            Select::make('school')
            ->label('موسسه')
            ->options(
                collect(DB::select("select DISTINCT school from students"))
                ->pluck('school','school')
                ->filter(function ($value,$key) {
                    return isset($value) && isset($key) && strlen($value) > 0;
                })->toArray()

            )
            ->multiple(),
            Checkbox::make('exclude')
            ->label('exclude')
            ]
        )
        ->query(function (Builder $query, array $data): Builder {

            if($data['school'] == []){
                return $query;
            }
            return $query
            ->when(
                $data,
                fn (Builder $query, $data): Builder => 
                    $data['exclude'] ? 
                    $query->whereNotIn('school',$data['school'])
                    : 
                    $query->whereIn('school',$data['school'])
            );
        })
        ->indicateUsing(function (array $data): ?array {
            $indicators = [];
            if (! $data['school']) {
                return null;
            }
            foreach($data['school'] as $school_name){
                $indicators[] = 'موسسه : '.$school_name;
            }

            return $indicators;
        })
        ->hidden(! auth()->user()->hasRole('super_admin')),
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
        Filter::make('counselor')
        ->form([
            Select::make('counselor_id')
            ->label('مشاور')
            ->options(
                auth()->user()->hasRole('super_admin') ?
                                Counselor::all()->pluck('user.name','id')
                                ->filter(function ($value,$key) {
                                    return isset($value) && isset($key);
                                })
                                ->map(function ($item,$key) {
                                    return $item.' '.Counselor::find($key)->code;
                    })
                    :
                    Counselor::where('admin_id',auth()->user()->id)->get()->pluck('user.name','id')
                                ->filter(function ($value,$key) {
                                    return isset($value) && isset($key);
                                })
                                ->map(function ($item,$key) {
                                    return $item.' '.Counselor::find($key)->code;
                    })


            )->multiple()
        ])
        ->query(function (Builder $query, array $data): Builder { 
            if($data['counselor_id'] == []){
                return $query;
            }
            return $query->when(
                $data['counselor_id'],
                fn (Builder $query, $counselor_ids) => $query->whereIn('counselor_id',$counselor_ids)
            );
        })
        ->indicateUsing(function (array $data): ?array {
            $indicators = [];
            if (! $data['counselor_id']) {
                return null;
            }
            foreach($data['counselor_id'] as $id){
                $indicators[] = 'مشاور : '.Counselor::find($id)->user->name;
            }

            return $indicators;
        })
	,

                Filter::make('created_at')->label('تاریخ ثبت نام')
                ->form([
                    Section::make()->label('ثبت نام')->schema([
                    DatePicker::make('created_from')->label('تاریخ شروع')->jalali(),
                    DatePicker::make('created_until')->label('تاریخ پایان')->jalali(),
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
            ->filtersFormColumns(3)
            ->actions([
		Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Action::make('delete')->
                action(function($record): void{
                    $user_id = $record['user']['id'];
                    $record->delete();
                    $record->user->delete();
                })->requiresConfirmation()
                ->hidden( !auth()->user()->hasRole('super_admin')),
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
                })
                ->hidden( !auth()->user()->hasRole('super_admin')),
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
                    ->hidden( !auth()->user()->hasRole('super_admin'))

            ])
            ->bulkActions([
                BulkAction::make('set_active')
                ->label('فعال/غیر فعال کردن')
                ->form(
                    [
                        Select::make('status')
                        ->label('وضعیت')
                        ->options(
                            [
                                0 => 'غیرفعال',
                                1 => 'فعال'
                            ]
                        )
                    ]
                )
                ->action(
                    function($records,$data){
                        foreach($records as $record){
                            $record->user->status = $data['status'];
                            $record->user->save();
                        }
                    }
                ),
                BulkAction::make('change_school')
                ->label('تغییر مدرسه')
                ->form(
                    [
                        Select::make('school')
                        ->label('موسسه')
                        ->options(
                            collect(DB::select("select DISTINCT school from students"))
                            ->pluck('school','school')
                            ->filter(function ($value,$key) {
                                return isset($value) && isset($key) && strlen($value) > 0;
                            })->toArray()
            
                        ),            
                    ]
                )
                ->action(function($records,$data): void{
                    foreach($records as $record){
                        $record->school = $data['school'];
                        $record->save();

                    }
                })
                ->hidden(! auth()->user()->hasRole('super_admin')),
                BulkAction::make('change_counselor')
                ->label('تغییر مشاور')
                ->form(
                    [
                        Select::make('counselor_id')->label('مشاور')
                        ->
                        options(
                            Counselor::all()->pluck('user.name','id')->filter(function ($value,$key) {
                                return isset($value) && isset($key);
                            })
                            ->map(function ($item,$key) {
                                return $item.' '.Counselor::find($key)->code;
                            })
                        )
                        ->searchable()    
                    ]
                )
                ->action(function($records,$data): void{
                    foreach($records as $record){
                        $record->counselor_id = $data['counselor_id'];
                        $record->save();

                    }
                }),
                BulkAction::make('add_score')
                ->label('افزایش / کاهش سکه')
                ->form(
                    [
                        TextInput::make('score')
                        ->label('مقدار سکه')
                        ->required()
                    ]
                )
                ->action(function($records,$data): void{
                    foreach($records as $record){
                        $record->user->score = $record->user->score + $data['score'];
                        $record->user->save();

                    }
                })
                ->hidden( !auth()->user()->hasRole('super_admin')),
                BulkAction::make('delete')->
                label('حذف گروهی')
                ->action(function($records): void{
                    foreach($records as $record){
                        $user_id = $record['user']['id'];
                        $record->delete();
                        User::where('id',$user_id)->first()->delete();
                    }
                })->requiresConfirmation()
                ->hidden( !auth()->user()->hasRole('super_admin')),
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
                ->hidden( ! auth()->user()->hasRole('super_admin'))
,

            ])
            ->paginated([10, 25, 50, 100,250, 'all']);
    }
    
    public static function getRelations(): array
    {
        return [
            RelationManagers\CounselorsRelationManager::class,
            RelationManagers\StudyPlanRelationManager::class,
            RelationManagers\AllPlansRelationManager::class
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
