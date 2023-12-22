<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Enums\MajorEnum;
use App\Models\Enums\GradeEnum;
use App\Models\Enums\StateEnum;
use App\Models\Enums\LessonTypeEnum;

class Lesson extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $casts = [
        'topics' => 'array',
        'major' => MajorEnum::class,
        'grade' => GradeEnum::class,
        'main' => StateEnum::class,
        'type' => LessonTypeEnum::class
    ];


}
