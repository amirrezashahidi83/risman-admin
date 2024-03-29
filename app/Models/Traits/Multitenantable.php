<?php
namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;
trait Multitenantable
{
    protected static function bootMultitenantable(): void
    {
        static::creating(function ($model) {
            $model->school_id = auth()->user()->school_id;
        });

        if( auth()->check())
        if(! auth()->user()->hasRole('super_admin'))
        static::addGlobalScope('created_by_school_id', function (Builder $builder) {
		if($builder->has('user')){
		$builder->whereRelation('user','school_id', auth()->user()->school_id);
		}else{
		$builder->where('school_id',auth()->user()->school_id);
		}
		if( auth()->user()->hasRole('supervisor')){
                if($builder->has('counselor')){
                    $builder->whereRelation('counselor','admin_id',auth()->user()->id);
                }else if($builder->has('admin')){
                    $builder->where('admin_id',auth()->user()->id);
                }
            }
        });
    }
}

?>
