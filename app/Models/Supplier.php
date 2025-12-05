<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
class Supplier extends Model
{
    use SoftDeletes;
    
    //

 // fillable is for mass assigment (allowed na ifill up)
      protected $fillable = [
        'name',
        'contact_person',
        'email',
        'phone',
        'address',
        'is_active'
    ];
      
      //Entity Reletionship to the purchase_orders
        public function purchase_orders(): HasMany
    {
        return $this->hasMany(PurchaseOrder::class);
    } 

       //Entity Reletionship to the inventory
        public function inventory(): HasOne
    {
        return $this->hasMany(Inventory::class);
    } 
}
