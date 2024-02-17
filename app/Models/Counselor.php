<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;
use App\Models\Student;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Builder;

class Counselor extends Model
{
    use HasFactory;
    use Notifiable;
    use HasRoles;
    use Traits\Multitenantable;
    protected $table = 'counselors';
    protected $guarded = [];

    protected static function booted () {
        static::creating(function ($model) {
            $model->user->school_id = auth()->user()->school_id;
        });

        if( auth()->check())
        if(! auth()->user()->hasRole('super_admin'))
        static::addGlobalScope('created_by_school_id', function (Builder $builder) {
            $builder->whereRelation('user','school_id', auth()->user()->school_id);
            if(auth()->user()->hasRole('supervisor')){
                $builder->where('admin_id',auth()->user()->id);
            }
        });
    }

    public function students() : HasMany {
        return $this->hasMany(Student::class,'counselor_id','id');
    }

    public function user() : BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function user2(){
        return $this->morphOne(User::class,'userable');
    }
    
    public function groups() {
        return $this->hasMany(Group::class,'counselor');
    }

    public function plans() {
        return $this->belongsTo(CounselorPlan::class);
    }


    public function allPlans() {
        return $this->belongsToMany(StudentPlan::class,'students','plan_id');
    }

    public function requests() {
        return $this->hasMany(PlanRequest::class);
    }

    public function admin(){
        return $this->belongsTo(Admin::class);
    }

    public function school(){
        return $this->belongsTo(School::class);
    }



}
