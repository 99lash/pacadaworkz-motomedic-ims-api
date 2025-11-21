<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SystemSetting extends Model
{
    use SoftDeletes;
    
    //
    
      protected $fillable = [
        'user_id',
        'setting_key',
        'setting_value',
        'description'
    ];
}
