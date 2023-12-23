<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Enums\StateEnum;

class Transaction extends Model
{
    public $timestamps = false;
    protected $table = 'transactions';

    protected $casts = [
       'status' => StateEnum::class
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
