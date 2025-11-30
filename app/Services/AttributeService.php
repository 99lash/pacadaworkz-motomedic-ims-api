<?php

namespace App\Services;
use App\Models\Attribute;

class AttributeService{
 

    //get all attributes
     public function getAllAttributes($search = null){
    
        $query = Attribute::query();
       
        if($search)
            $query->where('name','Ilike',"%{$search}%");



        return $query->paginate(10)->withQueryString();
     }



     //get attributes by id
    public function getAttributeById($id){
            
        return Attribute::findOrFail($id);
    }

   
 //crate new Attribute
    public function create(array $data){
          
      
        return Attribute::create(['name'=> $data['name']]);
      


    }

}