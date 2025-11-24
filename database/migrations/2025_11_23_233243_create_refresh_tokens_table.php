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
        Schema::create('refresh_tokens', function (Blueprint $table) {
     $table->id(); // bigint primary key
$table->foreignId('user_id')->constrained()->cascadeOnDelete();
$table->string('token', 64)->unique(); // string para sa token
$table->boolean('revoked')->default(false);
$table->timestamps(); // may created_at at updated_at
$table->timestamp('expires_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refresh_tokens');
    }
};
