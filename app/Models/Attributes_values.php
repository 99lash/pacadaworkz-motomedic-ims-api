<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attributes_values extends Model
{
    //

       use HasFactory, Notifiable,SoftDeletes;

      protected $fillable = [
        'attribute_id',
        'value'
    ];

}
