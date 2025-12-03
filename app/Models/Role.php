<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
class Role extends Model
{
    use SoftDeletes;
    
    //

        // fillable is for mass assigment (allowed na ifill up)
      protected $fillable = [
        'role_name',
        'description'
    ];
  
    //Reletionship to the user
     public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    //Entity Reletionship to the permissions via role_permissions
  public function permissions(): BelongsToMany
{
    return $this->belongsToMany(Permission::class, 'role_permissions')
        ->using(RolePermission::class)
        ->withTimestamps()
        ->withPivot('deleted_at')
        ->wherePivotNull('deleted_at'); 
}

}
