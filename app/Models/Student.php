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
    use Traits\Multitenantable;

    protected $casts = [
        'major' => MajorEnum::class,
        'grade' => GradeEnum::class
    ];
    
    protected $guarded = [];

    protected static function booted () {
        static::creating(function ($model) {
            $model->school_id = auth()->user()->school_id;
        });

        if( auth()->check())
        if(! auth()->user()->hasRole('super_admin'))
        static::addGlobalScope('created_by_school_id', function (Builder $builder) {
            $builder->where('school_id', auth()->user()->school_id);
            if(auth()->user()->hasRole('supervisor')){
                $builder->whereRelation('counselor','admin_id',auth()->user()->id);
            }
        });
    }

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
        return $this->hasMany(PlanRequest::class);
    }

    public function studyPlans(){
        return $this->hasMany(StudyPlan::class);
    }

protected static function replicateRelations($oldModel, &$newModel)
{
    foreach($oldModel->getRelations() as $relation => $modelCollection) {

        foreach ($modelCollection as $model) {
            $childModel = $model->replicate();
            $childModel->push();
            $childModel->setRelations([]);

            $newModel->{$relation}()->save($childModel); 
            static::replicateRelations($model,$childModel);
        }
    }

    return $newModel;
}

}
