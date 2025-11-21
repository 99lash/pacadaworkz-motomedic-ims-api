<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttributesValue extends Model
{
    use SoftDeletes;
    
    //

       protected $fillable = [
        'attribute_id',
        'value'
    ];

}
