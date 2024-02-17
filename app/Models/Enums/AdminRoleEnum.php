<?php
namespace App\Models\Enums;
use Filament\Support\Contracts\HasLabel;

enum AdminRoleEnum : int  {
    case SUPER = 1;
    case SCHOOL = 2;
    case COUNS = 3;

}

?>
