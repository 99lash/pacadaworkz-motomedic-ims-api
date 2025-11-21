<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class ActivityLog extends Model
{
    //

      use HasFactory, Notifiable,SoftDeletes;
    // fillable is for mass assigment (allowed na ifill up)
      protected $fillable = [
        'user_id',
        'action',
        'module',
        'description',
        'ip_address',
        'user_agent'
    ];

     //Entity relationship to user
      public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
