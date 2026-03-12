<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CartService;

class CartController extends Controller
{

    public function add(Request $request, CartService $cart)
    {
        $cart = $cart->add(
            $request->site,
            $request->cart_id,
            $request->product_id,
            $request->quantity
        );
        return $this->success($cart, 'Produit ajouté au panier avec succès');
    }

    public function remove(Request $request, CartService $cart)
    {
        return $cart->remove(
            $request->site,
            $request->cart_id,
            $request->product_id
        );
    }

    public function show(Request $request, CartService $cart)
    {
        $cart = $cart->getCart(
            $request->site,
            $request->cart_id
        );
        return $this->success($cart, 'Panier récupéré avec succès');
    }

}