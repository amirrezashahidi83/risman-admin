<?php
namespace App\Models\Enums;
use Filament\Support\Contracts\HasLabel;


enum GradeEnum : int implements HasLabel{
    case SEVENTH = 1;
    case EIGHTH = 2;
    case NINTH = 3;
    case TENTH = 4;
    case ELEVENTH = 5;
    case TWELFTH = 6;

    public function getLabel() : ? string{
        return match($this){
            GradeEnum::SEVENTH => 'هفتم',
            GradeEnum::EIGHTH => 'هشتم',
            GradeEnum::NINTH => 'نهم',
            GradeEnum::TENTH => 'دهم',
            GradeEnum::ELEVENTH => 'یازدهم',
            GradeEnum::TWELFTH => 'دوازدهم',

        };
    }
}

?>