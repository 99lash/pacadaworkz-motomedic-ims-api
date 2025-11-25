<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class SystemSetting extends Model
{
    use SoftDeletes;

    //
     // fillable is for mass assigment (allowed na ifill up)
      protected $fillable = [
        'user_id',
        'setting_key',
        'setting_value',
        'description'
    ];

    //Entity relationship to user
      public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
