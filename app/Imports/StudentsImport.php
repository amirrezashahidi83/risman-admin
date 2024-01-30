<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\Counselor;
use App\Models\User;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Hash;
use Filament\Notifications\Notification;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Concerns\WithEvents;

class StudentsImport implements OnEachRow,WithStartRow, WithEvents
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    private $currentCode;
    
    function __construct(private $options){
	    $this->currentCode = 1;
    }

    public function onRow(Row $row)
    {
        $row = $row->toArray();

	for($index = 0;$index < count($row);$index++){
		$row[$index] = trim($row[$index]);
	}

    $grades = ['هفتم' => 1,'هشتم' => 2,'نهم' => 3,'دهم' => 4,'یازدهم' => 5,'دوازدهم' => 6,'فارغ' => 6];
    $majors = ['' => 0,'بدون رشته' => 0,'ریاضی' => 1,'تجربی' => 2,'انسانی' => 3];

	if(strlen($row[1]) != 0){
        $is_name_seperated = $this->options['seperated_name'] ?? false;
        $phoneNumber = $is_name_seperated ? $row[3] : $row[2];
        
        if(User::where('phoneNumber',$phoneNumber)->count() == 0){

        $user = User::firstOrCreate(
            [
                'password' => Hash::make('12345678'),
                'name' =>  $is_name_seperated ? $row[1] .' '. $row[2] : $row[1],
                'phoneNumber' => $phoneNumber,
                'role' => 2,
                'status' => $this->options['status'],
                'rate' => 0,
            ]
        );

        $grade_row = $is_name_seperated ? $row[4] : $row[3];
        $major_row = $is_name_seperated ? $row[5] : $row[4];
        $student =  Student::firstOrCreate(
            [
                'user_id' => $user->id,
                'grade' => array_key_exists($grade_row,$grades ) ? $grades[$grade_row] : 6,
                'major' => array_key_exists($major_row,$majors) ? $majors[$major_row] : 0,
                'counselor_id' => $this->currentCode,
                'school' => $this->options['school'],
                'goal' => 'هدف شما',
                'rate' => 0
            ]
        );

        Notification::make()
        ->title('اضافه شد')
        ->body('دانش آموز  ' . $row[1] . ' با موفقیت اضافه شد')
        ->success()
        ->send();
        }
        }
    }

    public function startRow() : int{
        return $this->options['startingRow'] ?? 1;
   }
    public function registerEvents(): array
    {
	    return [
		    BeforeSheet::class => function(BeforeSheet $event) {
				$sheetName = $event->getSheet()->getTitle();
                $random = Str::random(8);
                $user = User::create(
                    [
                        'phoneNumber' => $random,
                        'name' => $sheetName,
                        'role' => 1,
                        'password' => Hash::make('12345678'),
                        'status' => 1,
                        'rate' => 0
                    ]
                );

                $counselor = Counselor::create(
                    [
                        'code' => $random,
                        'user_id' => $user->id
                    ]
                );

                $this->currentCode = $counselor->id;
			} 
		];
	}
}
