<?php
namespace App\Models\Enums;
use Filament\Support\Contracts\HasLabel;

enum StateEnum : int implements HasLabel {
    case ON = 1;
    case OFF = 0;

    public function getLabel(): ?string{
        return match($this){
            StateEnum::ON => 'فعال',
            StateEnum::OFF => 'غیرفعال'
        };
    }
}

?>