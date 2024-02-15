<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Builder;

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

    protected $casts = [
        'day' => Enums\WeekEnum::class
    ];
    
    protected $guarded = [];

    protected static function booted(): void
    {
        if( auth()->check())
        if(! auth()->user()->hasRole('super_admin'))
        static::addGlobalScope('created_by_school_id', function (Builder $builder) {
            $builder->whereRelation('student','school_id', auth()->user()->school_id);
        });
    }

    protected function data() : Attribute{
        return Attribute::make(
            get: fn (string $value) => array_values(json_decode($value,true)),
        );
    }

    public function student(){
        return $this->belongsTo(Student::class);
    }

}
