<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Group extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function students(){
        return $this->belongsToMany(Student::class,'group_students');
    }

    public function counselor(){
        return $this->belongsTo(Counselor::class);
    }
}
