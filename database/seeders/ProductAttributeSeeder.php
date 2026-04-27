<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\AttributesValue;

class ProductAttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Honda Wave 125 Piston Kit -> Size: Standard
        $piston = Product::where('sku', 'HND-PST-001')->first();
        $stdSize = AttributesValue::where('value', 'Standard')->first();
        if ($piston && $stdSize) {
            $piston->attribute_values()->attach($stdSize->id);
        }

        // 2. Motul 7100 Engine Oil -> Material/Type: Synthetic
        $oil = Product::where('sku', 'MTL-OIL-003')->first();
        $synthetic = AttributesValue::where('value', 'Synthetic')->first();
        if ($oil && $synthetic) {
            $oil->attribute_values()->attach($synthetic->id);
        }

        // 3. NGK Iridium Spark Plug -> Material/Type: Iridium
        $plug = Product::where('sku', 'NGK-SPK-004')->first();
        $iridium = AttributesValue::where('value', 'Iridium')->first();
        if ($plug && $iridium) {
            $plug->attribute_values()->attach($iridium->id);
        }

        // 4. Dunlop Tire -> Size: 80/90-17
        $tire = Product::where('sku', 'DNL-TR-005')->first();
        $tireSize = AttributesValue::where('value', '80/90-17')->first();
        if ($tire && $tireSize) {
            $tire->attribute_values()->attach($tireSize->id);
        }
    }
}
