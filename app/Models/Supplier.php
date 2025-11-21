<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    //

    
    use HasFactory, Notifiable,SoftDeletes;
    
      protected $fillable = [
        'name',
        'contact_person',
        'email',
        'phone',
        'address',
        'is_active'
    ];

}
