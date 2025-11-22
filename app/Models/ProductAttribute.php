<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class ProductAttribute extends Model
{
    use SoftDeletes;
    
    //
 
    protected $dates = ['deleted_at'];

// fillable is for mass assigment (allowed na ifill up)
      protected $fillable = [
        'attribute_id',
        'attribute_value_id'
    ];


     public function attribute(): BelongsTo
    {
        return $this->belongsTo(Attribute::class);
    }

}
