<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamPlan extends Model
{
    use HasFactory;
    protected $exam_file = 'exam_plans';
}
