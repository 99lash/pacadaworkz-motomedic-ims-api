<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attribute;
use App\Models\AttributesValue;

class AttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Size Attribute
        $size = Attribute::create(['name' => 'Size']);
        $sizes = ['Standard', 'Oversize', '80/90-17', '90/80-17'];
        foreach ($sizes as $val) {
            AttributesValue::create(['attribute_id' => $size->id, 'value' => $val]);
        }

        // 2. Create Material/Type Attribute
        $material = Attribute::create(['name' => 'Material/Type']);
        $materials = ['Synthetic', 'Semi-Synthetic', 'Iridium', 'Copper', 'Steel'];
        foreach ($materials as $val) {
            AttributesValue::create(['attribute_id' => $material->id, 'value' => $val]);
        }

        // 3. Create Color Attribute
        $color = Attribute::create(['name' => 'Color']);
        $colors = ['Black', 'Silver', 'Gold', 'Red'];
        foreach ($colors as $val) {
            AttributesValue::create(['attribute_id' => $color->id, 'value' => $val]);
        }
    }
}
