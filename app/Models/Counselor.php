<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;
use App\Models\Student;
use Illuminate\Notifications\Notifiable;

class Counselor extends Model
{
    use HasFactory;
    use Notifiable;

    protected $table = 'counselors';
    protected $guarded = [];

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


}
