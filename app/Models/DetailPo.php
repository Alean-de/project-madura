<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\PurchaseOrder;

class DetailPo extends Model
{

    protected $table = 'detail_po';

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

    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}
