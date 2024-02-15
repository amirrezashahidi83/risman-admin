<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Laravel\Passport\HasApiTokens;
use App\Models\Enums\RoleEnum;
use App\Models\Enums\StateEnum;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens,HasFactory,Notifiable;



    
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'remember_token',
    ];

    protected $casts = [
        'role' => RoleEnum::class,
        'status' => StateEnum::class

    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getAuthPassword() {
        return $this->password;
    }

    public function school(){
        return $this->belongsTo(School::class);
    }

    
    public function schedule(){
        return $this->hasMany(Schedule::class);
    }

    public function userable(){
        return $this->morphTo();
    }

    public function subscription(){
        $this->hasOne(Subscription::class);
    }

}
