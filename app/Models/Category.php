<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //

      use HasFactory, Notifiable;

      protected $fillable = [
        'name',
        'description'
    ];
}
