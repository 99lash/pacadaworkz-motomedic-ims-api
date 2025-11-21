<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product_attributes extends Model
{
    //

    use HasFactory, Notifiable,SoftDeletes;
    
      protected $fillable = [
        'attribute_id',
        'attribute_value_id'
    ];


}
