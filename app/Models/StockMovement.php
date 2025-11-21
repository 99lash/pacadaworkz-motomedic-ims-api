<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockMovement extends Model
{
    use SoftDeletes;
    
    //
    
      protected $fillable = [
        'product_id',
        'user_id',
        'movement_type',
        'quantity',
        'reference_type',
        'reference_id',
        'notes'
    ];
}
