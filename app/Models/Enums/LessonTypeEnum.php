<?php
namespace App\Models\Enums;
use Filament\Support\Contracts\HasLabel;


enum LessonTypeEnum : int implements HasLabel{
    case NONE = 0;
    case SUPER = -1; 
    case SPECIAL = 1;
    case GENERAL = 2;

    public function getLabel() : ? string{
        return match($this){
            LessonTypeEnum::NONE => 'هیچکدام',
            LessonTypeEnum::SPECIAL => 'تخصصی',
            LessonTypeEnum::GENERAL => 'عمومی',
            LessonTypeEnum::SUPER => 'غیر درسی',
        };
    }
}

?>