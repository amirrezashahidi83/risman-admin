<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CounselorPlan extends Model
{
    use HasFactory;

    public $guarded = [];

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
