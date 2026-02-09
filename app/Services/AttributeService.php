<?php

namespace App\Services;
use App\Models\Attribute;
use App\Models\AttributesValue;
use App\Services\ActivityLogService;
use Illuminate\Support\Facades\Auth;

class AttributeService{

    protected $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }
 

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
    
            $attribute = Attribute::create(['name'=> $data['name']]);
    
            $this->activityLogService->log(
                'Attribute', // module
                'created',   // action
                'Attribute created: ' . $attribute->name,
                Auth::id()
            );
    
            return $attribute;
        }


//update attribute
  public function update(array $data,$id){
      $attribute = Attribute::findOrFail($id);

      $attribute->update($data);

                  $this->activityLogService->log(
                      'Attribute', // module
                      'updated',   // action
                      'Attribute updated: ' . $attribute->name,
                      Auth::id()
                  );
      return $attribute;
  } 
  

  //delete attribute
   public function delete($id){
        $attribute = Attribute::findOrFail($id);
        $attributeName = $attribute->name; // Capture name before deletion
        $attributeId = $attribute->id;
        $attributeTable = $attribute->getTable();

        $attribute->delete();

                    $this->activityLogService->log(
                        'Attribute', // module
                        'deleted',   // action
                        'Attribute deleted: ' . $attributeName,
                        Auth::id()
                    );   }


   //create value to the specific attribute
   public function createAttributesValue(array $data,$id){
        $attribute_value = AttributesValue::create([
        'attribute_id' => $id,
        'value' => $data['value'],
      ]);

      $attribute = Attribute::findOrFail($id);

      $attribute_name = $attribute->name;
      $this->activityLogService->log(
          'Attribute Value', // module
          'created',         // action
          'Attribute Value created: ' . $attribute_value->value . ' for Attribute : ' . $attribute_name,
          Auth::id()
      );

      return $attribute_value;
   }

}