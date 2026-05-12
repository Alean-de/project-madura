<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $primaryKey = 'product_id';

    protected $fillable = [
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

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }
}
