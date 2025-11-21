<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class SalesItem extends Model
{
    use SoftDeletes;
    
    //

        // fillable is for mass assigment (allowed na ifill up)
      protected $fillable = [
        'sales_transactions_id',
        'product_id',
        'unit_price',
        'subtotal',
        'total'
    ];

          //Entity Relationship to the sales_transaction
    public function sales_transaction(): BelongsTo
    {
        return $this->belongsTo(SalesTransaction::class);
    }

    public function product():BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

}
