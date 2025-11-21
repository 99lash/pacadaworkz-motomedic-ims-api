<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Inventory extends Model
{
    use SoftDeletes;
    
    //

     // fillable is for mass assigment (allowed na ifill up)
      protected $fillable = [
        'product_id',
        'supplier_id',
        'quantity',
        'reorder_quantity_point',
        'minimum_quantity',
        'last_quantity_count'
    ];
   
    
//Entity Reletionship to the products
      public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

//Entity Relationship to the supplier
      public function supplier():BelongsTo
      {
        return $this->belongsTo(Supplier::class);
      }
}
