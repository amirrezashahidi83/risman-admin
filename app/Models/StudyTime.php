<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use App\Models\Collections\StudyTimeCollection;

class StudyTime extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function newCollection(array $models = [])
    {
        return new StudyTimeCollection($models);
    }

    public function lesson(){
        return $this->belongsTo(Lesson::class);
    }

    public function attributes(){
        return $this->belongsToMany(Attribute::class,'attribute_studies','study_id','attribute_id')->withPivot('value');
    }

    public function values(){
        return $this->hasMany(AttributeValue::class,'study_id');
    }
}
