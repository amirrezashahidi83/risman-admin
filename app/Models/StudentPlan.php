<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentPlan extends Model
{
    use HasFactory;

    protected $table = 'student_plans';

    public function plan() {
        return $this->belongsTo(CounselorPlan::class);
    }

    public function student() {
        return $this->belongsTo(Student::class);
    }
}
