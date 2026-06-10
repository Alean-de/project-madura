<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{

    protected $fillable = [
        'user_id',
        'supplier_name',
        'contacts',
        'city',
        'status',
    ];

    public function products()
    {
        return $this->hasMany(Supplier::class, 'supplier_id', 'id');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function suppliers()
    {
        return $this->belongsTo(User::class, 'supplier_id', 'id');
    }

    public function scopeOwned($query)
    {
        return $query->where('user_id', auth()->id());
    }
}
