<?php

namespace App\Ai\Tools;

use App\Services\CartService;
use App\Services\OrderService;

abstract class ToolAgent
{
    public function __construct(
        protected int $site_id,
        protected string $cart_id,
        protected CartService $cartService,
        protected OrderService $orderService
    ) {}
}
