<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Notifications\Notifiable;
use App\Models\Enums\AdminRoleEnum;
use Illuminate\Support\Collection;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable implements FilamentUser,HasTenants
{
    use HasFactory,Notifiable,Traits\Multitenantable;
    use HasRoles;

    protected $guarded = [];
    
    protected $table = 'admins';

    protected $casts = [
    ];

    public static $SUPER_ADMIN_ID = 0;

    protected static function booted () {
        static::creating(function ($model) {
            $model->school_id = auth()->user()->school_id;
        });

        if( auth()->check())
        if(! auth()->user()->hasRole('super_admin'))
        static::addGlobalScope('created_by_school_id', function (Builder $builder) {
            $builder->where('school_id', auth()->user()->school_id);
        });
    }
    
    public function canAccessPanel(\Filament\Panel $panel): bool
    {
        return true;
    }

    public function counselors(){
        return $this->hasMany(Counselor::class);
    }

    public function getTenants(Panel $panel): Collection
    {
        return collect([$this->school]);
    }
    
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function canAccessTenant(Model $tenant): bool
    {
        return $this->school == $tenant;
    }

}
