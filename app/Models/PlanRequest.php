<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class PlanRequest extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        if( auth()->check())
        if(! auth()->user()->hasRole('super_admin'))
        static::addGlobalScope('created_by_school_id', function (Builder $builder) {
            $builder->whereRelation('student.user','school_id', auth()->user()->school_id);
        });
    }

    public function student(){
        return $this->belongsTo(Student::class);
    }

    public function counselor(){
        return $this->belongsTo(Counselor::class);
    }
}
