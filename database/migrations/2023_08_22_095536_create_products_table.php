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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image')->nullable();
            $table->string('category_id');
            $table->string('unit_id');
            $table->decimal('unit_cost', 8,2)->nullable()->default(0.00);
            $table->decimal('shipping_cost', 8,2)->nullable()->default(0.00);
            $table->decimal('total_cost', 8,2)->nullable()->default(0.00);
            $table->decimal('selling_cost', 8,2)->nullable()->default(0.00);
            $table->bigInteger('qty')->nullable()->default(0);
            $table->string('created_by');
            $table->string('active_status')->default('in_active')->nullable();
            $table->string('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
