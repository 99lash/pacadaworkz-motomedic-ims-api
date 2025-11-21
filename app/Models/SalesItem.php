<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesItem extends Model
{
    use SoftDeletes;
    
    //

      protected $fillable = [
        'sales_transactions_id',
        'product_id',
        'unit_price',
        'subtotal',
        'total'
    ];

}
