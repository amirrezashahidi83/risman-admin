<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ExamPlan;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'price',
        'grade',
        'major',
        'is_ready'
    ];

    public function exam_plans(): HasMany{
        return $this->hasMany(ExamPlan::class);
    }
}
