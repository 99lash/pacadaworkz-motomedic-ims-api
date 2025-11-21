<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    //

    
     use HasFactory, Notifiable;

      protected $fillable = [
        'role_name',
        'description'
    ];


}
