<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class SalesTransaction extends Model
{
    use SoftDeletes;
    
    //
        // fillable is for mass assigment (allowed na ifill up)
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


          //Entity Reletionship to the user
    public function user():BelongsTo
    {
      return $this->belongsTo(User::class);
    }
  
      //Entity Reletionship to the sales_items
    public function sales_items(): HasMany
    {
        return $this->hasMany(SalesItem::class);
    }

}
