<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\OrderService;
use App\Services\CartService;
use App\Http\Requests\StoreOrderRequest;

class OrderController extends Controller
{
    public function store(
        StoreOrderRequest $request,
        OrderService $orders,
        CartService $cart
    ){
        try {
            $cartData = $cart->getCart(
                $request->site,
                $request->cart_id
            );

            $order = $orders->create(
                $request->user(),
                $cartData,
                $request->validated()
            );

            if($order){
                $cart->clear(
                    $request->site,
                    $request->cart_id
                );
            }
        } catch (\Throwable $th) {
            return $this->error('Impossible de créer la commande', 500, $th->getMessage());
        }

        return $this->success($order, 'Commande créée avec succès');
    }

    public function orderByUser(){
        $orders = auth()->user()->orders()->with('items.product')->latest()->get();
        return $this->success($orders, 'Commandes récupérées avec succès');
    }
}
