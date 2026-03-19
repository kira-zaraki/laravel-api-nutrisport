<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_prices')
            ->withPivot(['price'])
            ->withTimestamps();
    }
}
