<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class StudyPlan extends Model
{
    use HasFactory;

    const DAY = 'day';
    const WEEK = 'week';
    const TWO_WEEKS = '2week';
    const MONTH = 'month';
    const PERIOD = 'period';

    const ALL_TYPES = [
        self::DAY,
        self::WEEK,
        self::TWO_WEEKS,
        self::MONTH,
        self::PERIOD
    ];

    protected $guarded = [];

    protected function data() : Attribute{
        return Attribute::make(
            get: fn (string $value) => StudyTime::findMany(json_decode($value,true)),
        );
    }

    public function student(){
        return $this->belongsTo(Student::class);
    }

}
