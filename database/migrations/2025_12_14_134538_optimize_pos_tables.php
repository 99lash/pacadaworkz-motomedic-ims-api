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
        Schema::table('cart_items', function (Blueprint $table) {
            $table->decimal('unit_price', 10, 2)->after('product_id')->default(0);
        });

        Schema::table('sales_transactions', function (Blueprint $table) {
            $table->string('discount_type')->nullable()->after('discount');
            $table->dropColumn('payment_status');
        });

        Schema::table('sales_items', function (Blueprint $table) {
            $table->dropColumn(['subtotal', 'total']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales_items', function (Blueprint $table) {
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
        });

        Schema::table('sales_transactions', function (Blueprint $table) {
            $table->dropColumn('discount_type');
            $table->string('payment_status')->default(null);
        });

        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropColumn('unit_price');
        });
    }
};
