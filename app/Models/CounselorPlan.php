<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class CounselorPlan extends Model
{
    use HasFactory;
    
    public $guarded = [];

    protected static function booted(): void
    {
        if( auth()->check())
        if(! auth()->user()->hasRole('super_admin'))
        static::addGlobalScope('created_by_school_id', function (Builder $builder) {
            $builder->whereRelation('counselor','school_id', auth()->user()->school_id);
        });
    }

    public function students(){
        return $this->hasMany(Student::class,'plan_id');
    }

    public function recentStudents(){
        return $this->belongsToMany(Student::class,'student_plans','plan_id');
    }

    public function counselor(){
        return $this->belongsTo(Counselor::class,'counselor_id');
    }

    public function studentPlans(){
        return $this->hasMany(StudentPlan::class);
    }
}
