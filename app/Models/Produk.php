<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $fillable = [
        'id',
        'nama_produk',
        'kategori_id',
        'supplier_id',
        'harga_beli',
        'harga_jual',
        'stok_awal',
        'stok_minimum',
        'satuan'
    ];
}
