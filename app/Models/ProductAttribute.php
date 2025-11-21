<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductAttribute extends Model
{
    use SoftDeletes;
    
    //

      protected $fillable = [
        'attribute_id',
        'attribute_value_id'
    ];


}
