<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use SoftDeletes;
    
    //

      protected $fillable = [
        'role_name',
        'description'
    ];
  
     public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

}
