<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
    protected $primaryKey = 'category_id';

    protected $fillable = [
    'user_id',
    'category_name',
    'product_count',
    'status'
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id', 'category_id');
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
