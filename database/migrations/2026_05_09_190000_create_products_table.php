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
            $table->foreignId('user_id')->nullable()->constrained('users', 'user_id')->nullOnDelete();
            $table->id('product_id');
            $table->string('product_name');
            $table->foreignId('category_id')->nullable()->constrained('categories', 'category_id')->nullOnDelete();
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers', 'supplier_id');
            $table->decimal('purchase_price', 15, 0);
            $table->decimal('selling_price', 15, 0);
            $table->integer('initial_stock')->default(0);
            $table->integer('minimum_stock')->default(0);
            $table->string('unit', 20);

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
