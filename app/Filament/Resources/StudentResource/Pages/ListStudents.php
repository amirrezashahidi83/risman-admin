<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Forms\Components\TextInput;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Grid;
use App\Filament\Imports\StudentImporter;
use Filament\Forms\Get;
use App\Imports\StudentsImport;
use App\Filament\Resources\CounselorResource;
use Filament\Actions\Action;
use App\Models\User;
use App\Models\Counselor;
use Filament\Notifications\Notification;


class ListStudents extends ListRecords
{
    protected static string $resource = StudentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Action::make('excel')
            ->label('excel')
            ->form([
                FileUpload::make('file')
                ->label('فایل')
                ->disk('local'),
                TextInput::make('school')
                ->label('موسسه'),
                Checkbox::make('seperated_name')
                ->label('نام و نام خانوادگی دو سطر جدا هستند'),
                TextInput::make('startingRow')
                ->label('سطر شروع')
                ->default(1),
                Select::make('status')
                ->label('وضعیت دانش آموزان')
                ->options([
                    0 => 'غیر فعال',
                    1 => 'فعال'
                ])->required(),
                Checkbox::make('add_counselor')
                ->label('افزودن مشاور')
                ->live(),
                Grid::make('counselor')
                ->hidden(fn (Get $get): bool => ! $get('add_counselor'))
                ->schema(
                    CounselorResource::getForm()
                )
                
            ])
            ->action(function (array $data) {
                $newData = $data;

                if(isset( $data['counselor'] )){
                    $user = User::firstOrCreate(
                        $data['counselor']['user']
                    );
                    $counselor = Counselor::firstOrCreate($data['counselor']['counselor']);
                    $counselor->user_id = $user->id;
                    $counselor->save();

                    $newData['counselor'] = $counselor;

                    Notification::make()
                    ->title('اضافه شد')
                    ->body('مشاور  ' . $user->name . ' با موفقیت اضافه شد')
                    ->success()
                    ->send();
            
                }

                $importer = new StudentsImport($newData);
                Excel::import($importer,$data['file'],'local',\Maatwebsite\Excel\Excel::XLSX);
            })  
	    ->hidden(auth()->user()->role->value != 'super')

        ];
    }
}
