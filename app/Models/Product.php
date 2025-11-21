<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    
    //

      protected $fillable = [
        'category_id',
        'brand_id',
        'sku',
        'name',
        'description',
        'unit_price',
        'cost_price',
        'reorder_level',
        'image_url',
        'is_active'
    ];

}
