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
        Schema::create('sku_products', function (Blueprint $table) {
            $table->id();
            $table->string('sku_id');
            $table->string('product_id');
            $table->string('product_qty')->default(0);
            $table->string('purchasing_cost')->default(0.00);
            $table->string('selling_cost')->default(0.00);
            $table->string('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sku_products');
    }
};
