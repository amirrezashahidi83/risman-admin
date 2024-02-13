<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Notifications\Notifiable;
use App\Models\Enums\AdminRoleEnum;

class Admin extends Authenticatable implements FilamentUser
{
    use HasFactory,Notifiable;

    protected $guarded = [];
    
    protected $casts = [
        'role' => AdminRoleEnum::class,
    ];

    public function canAccessPanel(\Filament\Panel $panel): bool
    {
        return true;
    }

    public function counselors(){
        return $this->hasMany(Counselor::class);
    }


}
