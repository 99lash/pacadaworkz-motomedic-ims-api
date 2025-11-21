<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseOrder extends Model
{
    use SoftDeletes;
    
    //

      protected $fillable = [
        'supplier_id',
        'user_id',
        'order_date',
        'expected_delivery',
        'total_amount',
        'status',
        'notes'
    ];

}
