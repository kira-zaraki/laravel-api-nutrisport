<?php

namespace App\Services;

use App\Models\Product;

class ProductService
{

    public function search($siteId, $keyword = null)
    {
        return Product::query()
            ->search($keyword)
            ->available()
            ->bySite($siteId)
            ->limit(5)
            ->get();

    }

}