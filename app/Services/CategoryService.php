<?php
namespace App\Services;
use App\Models\Category;

class CategoryService{


 public function getAllCategories($search = null){

      $query = Category::query();
      
      if($search)
      {
        $query->where('name','Ilike',"%{$search}%")->orWhere('description','Ilike',"%{$search}%");
      }
    
     return $query->paginate(10)->withQueryString();
 }


 public function create(array $category){
     
    return Category::create([
        'name' => $category['name'],
        'description' => $category['description']
    ]);
   
 }



 public function getCategoryById($id)
 {
    
     $category = Category::find($id);

     return $category;

 }



 public function update($id, array $update)
 {
      
    $category = Category::findOrfail($id);
    
    $category->update([
       'name' => $update['name'],
       'description' => $update['description']
    ]);
     
     return $category;

 }



 public function delete($id){
    
    $category = Category::findOrFail($id);
    
   return $category->delete();

 }

}
