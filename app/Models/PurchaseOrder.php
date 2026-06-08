<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Supplier;
use App\Models\Product;

class PurchaseOrder extends Model
{
    protected $primaryKey = 'id';

    protected $fillable = [
        'po_number',
        'supplier_id',
        'user_id',
        'status',
        'total_price',
        'expired_at'
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'supplier_id');
    }

    public function detailPo(): HasMany
    {
        return $this->hasMany(DetailPo::class, 'purchase_order_id', 'id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}
