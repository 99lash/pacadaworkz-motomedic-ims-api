<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class StockAdjustment extends Model
{
    use SoftDeletes;
    
    //
    
     // fillable is for mass assigment (allowed na ifill up)
      protected $fillable = [
        'user_id',
        'adjustment_no',
        'reason',
        'notes'
    ];

     //Entity relationship to the user
      public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
