<?php

namespace App\Services;
use App\Models\Attribute;
use App\Models\AttributesValue;
class AttributeService{
 

    //get all attributes
     public function getAllAttributes($search = null){
    
        $query = Attribute::with('attribute_values.attribute');
       
        if($search)
            $query->where('name','like',"%{$search}%");



        return $query->paginate(10)->withQueryString();
     }



     //get attributes by id
    public function getAttributeById($id){
            
        return Attribute::with('attribute_values.attribute')->findOrFail($id);
    }

   
 //crate new Attribute
    public function create(array $data){
          
      
        return Attribute::create(['name'=> $data['name']]);
      


    }


//update attribute
  public function update(array $data,$id){
      $attribute = Attribute::findOrFail($id);

      $attribute->update($data);
      return $attribute;
  } 
  

  //delete attribute
   public function delete($id){
        $attribute = Attribute::findOrFail($id);
        $attribute->delete();

   }


   //create value to the specific attribute
   public function createAttributesValue(array $data,$id){
        $attribute_value = AttributesValue::create([
        'attribute_id' => $id,
        'value' => $data['value'],
      ]);
  

      return $attribute_value;
   }

}