<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesTransaction extends Model
{
    use SoftDeletes;
    
    //
    
      protected $fillable = [
        'user_id',
        'transaction_no',
        'subtotal',
        'tax',
        'discount',
        'total_amount',
        'payment_method',
        'payment_status'
    ];

}
