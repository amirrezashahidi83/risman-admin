<?php
namespace App\Models\Enums;
use Filament\Support\Contracts\HasLabel;

enum RoleEnum : int implements HasLabel {
    case COUNSELOR = 1;
    case STUDENT = 2;

    public function getLabel() : ?string {
        return match($this){
            RoleEnum::COUNSELOR => 'مشاور',
            RoleEnum::STUDENT => 'دانش آموز'
        };
    }
}

?>