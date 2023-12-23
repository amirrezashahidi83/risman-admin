<?php
namespace App\Models\Enums;
use Filament\Support\Contracts\HasLabel;

enum MajorEnum : int implements HasLabel{

    case NONE = 0;
    case MATH = 1;
    case EXPERRIMENTAL = 2;
    case HUMANITIES = 3;

    public function getLabel() : ?string {
        return match($this){
            MajorEnum::NONE => 'بدون رشته',
            MajorEnum::MATH => 'ریاضی',
            MajorEnum::EXPERRIMENTAL => 'تجربی',
            MajorEnum::HUMANITIES => 'انسانی',
        };
    }
}

?>