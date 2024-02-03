<?php
namespace App\Models\Enums;
use Filament\Support\Contracts\HasLabel;

enum AdminRoleEnum : string implements HasLabel {
    case SUPER = 'super';
    case SCHOOL = 'school';
    case COUNS = 'counselor';

    public function getLabel(): ?string{
        return match($this){
            AdminRoleEnum::SUPER => 'ادمین اصلی',
            AdminRoleEnum::SCHOOL => 'مدرسه',
            AdminRoleEnum::COUNS => 'مشاور',

        };
    }
}

?>