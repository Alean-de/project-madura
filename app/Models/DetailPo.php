<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\PurchaseOrder;

class DetailPo extends Model
{
     protected $fillable = [
        'purchase_order_id',
        'product_id',
        'user_id',
        'quantity',
        'uom',
        'uom_multiplier',
        'unit_price',
        'subtotal',
    ];

     protected static function booted()
    {
        static::saving(function ($detailPo) {
            // 1. Hitung total uang berdasarkan harga per Kardus/UOM
            $detailPo->subtotal = $detailPo->quantity * $detailPo->unit_price;

            // 2. Hitung total isi fisik (Qty Kardus * Isi per Kardus)
            // Jika multiplier kosong/null, otomatis dianggap dikali 1
            $multiplier = $detailPo->uom_multiplier ?? 1;
            $detailPo->total_quantity_pcs = $detailPo->quantity * $multiplier;
        });
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id', 'id');
    }
}
