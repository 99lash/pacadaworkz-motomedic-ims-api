<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add refund columns to sales_transactions
        Schema::table('sales_transactions', function (Blueprint $table) {
            $table->decimal('refund_amount', 10, 2)->default(0)->after('total_amount');
            $table->text('refund_reason')->nullable()->after('status');
            $table->timestamp('refunded_at')->nullable()->after('updated_at');
        });

        // 2. Add quantity_returned to sales_items
        Schema::table('sales_items', function (Blueprint $table) {
            $table->integer('quantity_returned')->default(0)->after('quantity');
        });

        // 3. Update stock_movements reference_type pseudo-enum to include 'return' for PostgreSQL
        // Laravel's enum for PostgreSQL creates a VARCHAR with a CHECK constraint.
        // We need to drop and re-add the CHECK constraint with the new value.
        DB::statement('ALTER TABLE stock_movements DROP CONSTRAINT IF EXISTS stock_movements_reference_type_check');
        DB::statement("ALTER TABLE stock_movements ADD CONSTRAINT stock_movements_reference_type_check CHECK (reference_type IN ('purchase', 'sale', 'adjustment', 'return'))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Revert sales_transactions changes
        Schema::table('sales_transactions', function (Blueprint $table) {
            $table->dropColumn(['refund_amount', 'refund_reason', 'refunded_at']);
        });

        // 2. Revert sales_items changes
        Schema::table('sales_items', function (Blueprint $table) {
            $table->dropColumn('quantity_returned');
        });

        // 3. Revert stock_movements pseudo-enum for PostgreSQL
        DB::statement('ALTER TABLE stock_movements DROP CONSTRAINT IF EXISTS stock_movements_reference_type_check');
        DB::statement("ALTER TABLE stock_movements ADD CONSTRAINT stock_movements_reference_type_check CHECK (reference_type IN ('purchase', 'sale', 'adjustment'))");
    }
};
