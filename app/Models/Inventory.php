<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    //

    
    use HasFactory, Notifiable,SoftDeletes;
    
      protected $fillable = [
        'product_id',
        'supplier_id',
        'quantity',
        'reorder_quantity_point',
        'minimum_quantity',
        'last_quantity_count'
    ];

}
