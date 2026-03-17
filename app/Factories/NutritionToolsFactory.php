<?php

namespace App\Factories;
use App\Ai\Tools\SearchProductsTool;
use App\Ai\Tools\AddToCartTool;
use App\Ai\Tools\ViewCartTool;
use App\Ai\Tools\CheckoutTool;

class NutritionToolsFactory
{
    public function make(int $siteId, string $cartId): array
    {
        return [
            app(SearchProductsTool::class, [
                'site_id' => $siteId,
            ]),
            app(AddToCartTool::class, [
                'site_id' => $siteId,
                'cart_id' => $cartId,
            ]),
            app(ViewCartTool::class, [
                'site_id' => $siteId,
                'cart_id' => $cartId,
            ]),
            app(CheckoutTool::class, [
                'site_id' => $siteId,
                'cart_id' => $cartId,
            ]),
        ];
    }
}