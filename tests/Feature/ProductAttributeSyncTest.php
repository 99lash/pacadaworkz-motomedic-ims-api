<?php

namespace Tests\Feature;

use App\Models\Attribute;
use App\Models\AttributesValue;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Permission;
use App\Models\Product;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductAttributeSyncTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create roles
        $role = Role::create([
            'role_name' => 'superadmin',
            'description' => 'Super Admin'
        ]);

        // Create permissions
        $perm1 = Permission::create(['name' => 'Create', 'module' => 'Products']);
        $perm2 = Permission::create(['name' => 'Edit', 'module' => 'Products']);
        $perm3 = Permission::create(['name' => 'View', 'module' => 'Products']);
        
        $role->permissions()->attach([$perm1->id, $perm2->id, $perm3->id]);

        // Create user
        $this->user = User::create([
            'name' => 'testuser',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'role_id' => $role->id,
            'first_name' => 'Test',
            'last_name' => 'User'
        ]);
    }

    public function test_can_create_product_with_attributes()
    {
        $category = Category::create(['name' => 'Electronics']);
        $brand = Brand::create(['name' => 'Apple']);
        
        $attribute = Attribute::create(['name' => 'Color']);
        $value1 = AttributesValue::create(['attribute_id' => $attribute->id, 'value' => 'Space Grey']);
        $value2 = AttributesValue::create(['attribute_id' => $attribute->id, 'value' => 'Silver']);

        $payload = [
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'sku' => 'IPHONE-15-PRO',
            'name' => 'iPhone 15 Pro',
            'description' => 'Latest iPhone',
            'unit_price' => 999.99,
            'cost_price' => 700.00,
            'initial_stock' => 10,
            'attribute_values' => [$value1->id, $value2->id]
        ];

        $response = $this->actingAs($this->user, 'api')
            ->postJson('/api/v1/products', $payload);

        $response->assertStatus(201)
            ->assertJsonPath('data.name', 'iPhone 15 Pro')
            ->assertJsonCount(2, 'data.attribute_values');

        $this->assertDatabaseHas('product_attributes', [
            'attribute_value_id' => $value1->id,
        ]);
        $this->assertDatabaseHas('product_attributes', [
            'attribute_value_id' => $value2->id,
        ]);
    }

    public function test_can_update_product_attributes()
    {
        $category = Category::create(['name' => 'Electronics']);
        $brand = Brand::create(['name' => 'Apple']);
        
        $product = Product::create([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'sku' => 'IPHONE-15',
            'name' => 'iPhone 15',
            'unit_price' => 799.99,
            'cost_price' => 500.00
        ]);

        $attribute = Attribute::create(['name' => 'Color']);
        $value1 = AttributesValue::create(['attribute_id' => $attribute->id, 'value' => 'Black']);
        $value2 = AttributesValue::create(['attribute_id' => $attribute->id, 'value' => 'White']);

        // Attach one attribute first
        $product->attribute_values()->attach($value1->id);

        $payload = [
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'name' => 'iPhone 15 Updated',
            'unit_price' => 799.99,
            'cost_price' => 500.00,
            'attribute_values' => [$value2->id] // Change to value2
        ];

        $response = $this->actingAs($this->user, 'api')
            ->putJson("/api/v1/products/{$product->id}", $payload);

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data.attribute_values')
            ->assertJsonPath('data.attribute_values.0.value', 'White');

        // Check that the old relation is soft deleted
        $this->assertDatabaseHas('product_attributes', [
            'product_id' => $product->id,
            'attribute_value_id' => $value1->id,
        ]);
        
        $deletedPivot = \DB::table('product_attributes')
            ->where('product_id', $product->id)
            ->where('attribute_value_id', $value1->id)
            ->first();
            
        $this->assertNotNull($deletedPivot->deleted_at, 'The pivot record should be soft deleted.');

        $this->assertDatabaseHas('product_attributes', [
            'product_id' => $product->id,
            'attribute_value_id' => $value2->id,
            'deleted_at' => null
        ]);
    }
}
