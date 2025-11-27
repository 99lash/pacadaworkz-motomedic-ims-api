<?php
namespace App\Services;
use App\Models\Category;

class CategoryService{


 public function getAllCategories(){
     $categories = Category::all();

     return $categories;
 }


}
