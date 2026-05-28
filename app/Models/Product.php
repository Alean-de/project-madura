<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $primaryKey = 'product_id';

    protected $fillable = [
        'user_id',
        'product_id',
        'product_name',
        'category_id',
        'supplier_id',
        'purchase_price',
        'selling_price',
        'initial_stock',
        'minimum_stock',
        'unit'
    ];

    public function categories()
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }

    public function suppliers()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'supplier_id');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function scopeOwned($query)
    {
        return $query->where('user_id', auth()->id());
    }
}
