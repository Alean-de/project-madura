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
        Schema::create('stock_adjusments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')
              ->constrained(table: 'products', column: 'product_id')
              ->onDelete('cascade');            
            $table->date('exp_date');
            $table->integer('qty');
            $table->enum('status', ['barang_masuk', 'rusak', 'exp', 'barang_keluar', 'pending']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_adjusments');
    }
};
