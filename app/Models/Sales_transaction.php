<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sales_transaction extends Model
{
    //
    
    use HasFactory, Notifiable,SoftDeletes;
    
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
