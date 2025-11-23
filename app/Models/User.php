<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\belongsTo;
use App\Models\Role;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
     use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'role_id',
        'name',
        'email',
        'password_hash',
        'first_name',
        'last_name'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

   //Entity relationship to role
    public function role(): belongsTo
    {
        return $this->belongsTo(Role::class);
    }

    //Entity relationship to sales_ransaction
    public function sales_transactions(): HasMany
    {
          return $this->hasMany(SalesTransaction::class);
    }

    //Entity relationship to stock_adjustments
    public function stock_adjustments():HasMany
    {
        return $this->hasMany(StockAdjustment::class);
    }
     
     //Entity relationship to stock_adjustments
    public function stock_movements():HasMany
    {
        return $this->hasMany(StockMovement::class);
    }
     
   
    //Entity relationship to activity_logs
    public function activity_logs():HasMany
    {
        return $this->hasMany(ActivityLogs::class);
    }

    //Entity relationship to system_settings
    public function system_settings():HasOne
    {
        return $this->hasMany(SystemSetting::class);
    }
     
    
   


}
