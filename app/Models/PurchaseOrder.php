<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Supplier;

class PurchaseOrder extends Model
{
    protected $fillable = [
        'po_number',
        'supplier_id',
        'status',
        'items',
        'total_price',
        'expired_at'
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'supplier_id');
    }

    public function detailPo()
    {
        return $this->hasMany(DetailPo::class, 'purchase_order_id', 'id');
    }
}
