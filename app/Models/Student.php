<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use App\Models\User;
use App\Models\Counselor;
use App\Models\Enums\GradeEnum;
use App\Models\Enums\MajorEnum;

class Student extends Model
{
    use HasFactory;

    protected $casts = [
        'major' => MajorEnum::class,
        'grade' => GradeEnum::class
    ];
    
    protected $guarded = [];

    public function user2(){
        return $this->morphOne(User::class,'userable');
    }

    public function user() : BelongsTo {
        return $this->belongsTo(User::class);
    }


    public function counselor() : BelongsTo {
        return $this->belongsTo(Counselor::class);
    }

    public function groups() {
        return $this->belongsToMany(Group::class,'group_students');
    }

    public function counselorPlan() {
        return $this->belongsTo(CounselorPlan::class,'plan_id');
    }

    public function allPlans() {
        return $this->belongsToMany(CounselorPlan::class,'student_plans','student_id','plan_id');
    }

    public function requests() {
        return $this->belongsTo(PlanRequest::class);
    }

    public function studyPlans(){
        return $this->hasMany(StudyPlan::class);
    }
}
