<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sales_item extends Model
{
    //

    
    use HasFactory, Notifiable,SoftDeletes;
    
      protected $fillable = [
        'sales_transactions_id',
        'product_id',
        'unit_price',
        'subtotal',
        'total'
    ];

}
