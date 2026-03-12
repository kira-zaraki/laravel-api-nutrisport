<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Site;

class ProductController extends Controller
{
    public function productListe()
    {
        return $this->success(Product::with('prices')->paginate(10), 'Produits récupérés avec succès');
    }

    public function show(Product $product)
    {
        return $this->success($product->load('prices'), 'Produit récupéré avec succès');
    }

    public function productsBySite($site)
    {   
        $products = Product::bySite($site)->get();

        return $this->success($products, 'Produits récupérés avec succès');
    }

    public function showBySite(Site $site, Product $product)
    {
        return $this->success($product, 'Produit récupéré avec succès');
    }
}
