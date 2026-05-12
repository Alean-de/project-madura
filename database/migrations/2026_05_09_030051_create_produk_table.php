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
        Schema::create('produks', function (Blueprint $table) {
            $table->id();
            $table->string('nama_produk');
            $table->foreignId('kategori_id')->nullable();
            $table->foreignId('supplier_id')->nullable();
            $table->decimal('harga_beli', 15, 0);
            $table->decimal('harga_jual', 15, 0);
            $table->integer('stok_awal')->default(0);
            $table->integer('stok_minimum')->default(0);
            $table->string('satuan', 20);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk');
    }
};
