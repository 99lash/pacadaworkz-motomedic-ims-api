<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockAdjustment extends Model
{
    use SoftDeletes;
    
    //
    
      protected $fillable = [
        'user_id',
        'adjustment_no',
        'reason',
        'notes'
    ];
}
