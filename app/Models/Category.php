<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Category extends Model
{
    use SoftDeletes;
    
    //

      protected $fillable = [
        'name',
        'description'
    ];


    public function products(): belongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
