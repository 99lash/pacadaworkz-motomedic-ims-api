<?php
namespace App\Services;
use App\Models\Category;

class CategoryService{


 public function getAllCategories(){
     $categories = Category::all();

     return $categories;
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


}
