<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RolePermission extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    //


        // fillable is for mass assigment (allowed na ifill up)
      protected $fillable = [
        'role_id',
        'permission_id'
    ];

}
