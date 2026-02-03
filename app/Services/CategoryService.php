<?php
namespace App\Services;
use App\Models\Category;

class CategoryService{

    protected $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }


 public function getAllCategories($search = null){

      $query = Category::query();

      if($search)
      {
        $query->where('name','Ilike',"%{$search}%")->orWhere('description','Ilike',"%{$search}%");
      }

     return $query->paginate(10)->withQueryString();
 }


 public function create(array $categoryData){

    $category = Category::create([
        'name' => $categoryData['name'],
        'description' => $categoryData['description']
    ]);

    $this->activityLogService->log(
        module: 'Category',
        action: 'Create',
        description: "Category created: {$category->name}",
        userId: auth()->id()
    );

    return $category;
 }



 public function getCategoryById($id)
 {

     $category = Category::findOrFail($id);

     return $category;

 }



 public function update($id, array $update)
 {

    $category = Category::findOrfail($id);
    $oldName = $category->name;

    $category->update([
       'name' => $update['name'],
       'description' => $update['description']
    ]);

    $this->activityLogService->log(
        module: 'Category',
        action: 'Update',
        description: "Category updated from '{$oldName}' to '{$category->name}'",
        userId: auth()->id()
    );

     return $category;

 }



 public function delete($id){

    $category = Category::findOrFail($id);
    $categoryName = $category->name;

    $category->delete();

    $this->activityLogService->log(
        module: 'Category',
        action: 'Delete',
        description: "Category deleted: {$categoryName}",
        userId: auth()->id()
    );

   return true;

 }

}
