<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    public function products()
    {
        return $this->belongsToMany(Product::class, 'prices')
            ->withPivot(['price'])
            ->withTimestamps();
    }
}
