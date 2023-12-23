<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helper\Utility;

class AttributeValue extends Model
{
    use HasFactory;
    protected $table = 'attribute_studies';

    protected function value(){
        return Utility::stringToType($this->value,$this->type);
    }

    public function attribute(){
        return $this->belongsTo(Attribute::class,'attribute_id');
    }
}
