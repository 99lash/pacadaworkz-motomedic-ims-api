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
        Schema::table('sales_transactions', function (Blueprint $table) {
            $table->decimal('amount_tendered', 10, 2)->after('payment_method')->default(0);
            $table->decimal('change', 10, 2)->after('amount_tendered')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales_transactions', function (Blueprint $table) {
            $table->dropColumn(['amount_tendered', 'change']);
        });
    }
};
