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
        Schema::create('detail_po', function (Blueprint $table) {
            $table->id();

            // Relasi antar table
            $table->foreignId('purchase_order_id')->constrained('purchase_orders', 'id')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products', 'product_id');
            $table->foreignId('user_id')->nullable()->constrained('users', 'user_id')->onDelete('set null');

            // Kuantitas
            $table->integer('quantity')->default(1);
            $table->string('uom', 20)->default('pcs');
            $table->integer('uom_multiplier')->default(1);
            
            // Keuangan
            $table->decimal('unit_price', 15, 2);
            $table->decimal('subtotal', 15, 2);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pivot_product_po');
    }
};
