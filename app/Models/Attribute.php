<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Attribute extends Model
{
    use HasFactory;

    protected $guarded = [];
    
    public function studyTimes(){
        return $this->belongsToMany(StudyTime::class,'attribute_studies')->withPivot('value');
    }

    public function values(){
        return $this->hasMany(AttributeValue::class);
    }

    public static function getOrCreateMultiple(array $rows): Collection
    {
        $models = new Collection();

        foreach ($rows as $row) {
            // Assuming $row has a 'unique_key' to check against
            $model = static::firstOrCreate(
                ['name' => $row['name']], 
                $row
            );

            $models->push($model);
        }

        return $models;
    }

}
