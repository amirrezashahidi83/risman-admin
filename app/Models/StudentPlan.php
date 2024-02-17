<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class StudentPlan extends Model
{
    use HasFactory;

    protected $table = 'student_plans';

    protected static function booted(): void
    {
        if( auth()->check())
        if(! auth()->user()->hasRole('super_admin'))
        static::addGlobalScope('created_by_school_id', function (Builder $builder) {
            $builder->whereRelation('plan.counselor.user','school_id', auth()->user()->school_id);
	    if(auth()->user()->hasRole('supervisor')){
                $builder->whereRelation('plan.counselor','admin_id',auth()->user()->id);
            }

        });
    }

    public function plan() {
        return $this->belongsTo(CounselorPlan::class);
    }

    public function student() {
        return $this->belongsTo(Student::class);
    }
}
