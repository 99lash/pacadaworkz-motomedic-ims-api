<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // I-drop muna ang table kung gusto talagang i-recreate
        Schema::dropIfExists('product_attributes');

        // Gumawa ulit ng table sa tamang column order
        Schema::create('product_attributes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attribute_value_id')->constrained('attributes_values')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Sa rollback, puwede mo rin i-drop at gumawa ulit ng old structure
        Schema::dropIfExists('product_attributes');

        Schema::create('product_attributes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attribute_value_id')->constrained('attributes_values')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }
};
