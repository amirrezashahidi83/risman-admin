<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Forms\Components\TextInput;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;

class ListStudents extends ListRecords
{
    protected static string $resource = StudentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('وارد کردن  اکسل')
            ->form(
                [
                    Select::make('grade')
                    ->label('پایه')
                    ->options(
                        [
                            1 => 'هفتم',
                            2 => 'هشتم',
                            3 => 'نهم',
                            4 => 'دهم',
                            5 => 'یازدهم',
                            6 => 'دوازدهم'
                        ]
                        ),
                    FileUpload::make('excel_file')
                    ->disk('public')
                    ->required()
                ]
            )
            ->action( function (array $data) {
                //$path = storage_path('app\public\it9ztcnq79Tk3r5t0ygIdqjDtVLDaX-metac2V2ZW4uY3N2-.xls');
                Excel::import(new UsersImport($data['grade']),storage_path('app/public/'.$data['excel_file']));
            })
        ];
    }
}
