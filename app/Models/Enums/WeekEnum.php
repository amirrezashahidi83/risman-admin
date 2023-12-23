<?php
namespace App\Models\Enums;
use Filament\Support\Contracts\HasLabel;

enum WeekEnum : int implements HasLabel {
    case SAT = 0;
    case SUN = 1;
    case MON = 2;
    case TUE = 3;
    case WED = 4;
    case THU = 5;
    case FRI = 6;

    public function getLabel(): ?string{
        return match($this){
            WeekEnum::SAT => 'شنبه',
            WeekEnum::SUN => 'یکشنبه',
            WeekEnum::MON => 'دوشنبه ',
            WeekEnum::TUE => 'سه شنبه',
            WeekEnum::WED => 'چهارشنبه',
            WeekEnum::THU => 'پنجشنبه',
            WeekEnum::FRI => 'جمعه',

        };
    }
}

?>