<?php

namespace App\Factories;

use App\Models\Product;

class JsonFeedGenerator implements FeedGenerator
{
    public function generate()
    {
        return Product::select(
            'id',
            'name',
            'stock'
        )->get()->toJson();
    }
}