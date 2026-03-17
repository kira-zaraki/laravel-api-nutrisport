<?php

namespace App\Ai\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use App\Services\CartService;
use App\Services\OrderService;
use Auth;
use Stringable;

class CheckoutTool extends ToolAgent implements Tool
{
    /**
     * Get the description of the tool's purpose.
     */
    public function description(): Stringable|string
    {
        return <<<'DESC'
                Complete the checkout process for the customer's cart.

                Important rules for the AI:
                - Only use products currently in the cart.
                - Do not invent products or quantities.
                - Require the user to provide shipping information: shipping_name, shipping_address, shipping_city, shipping_country.
                - Use bank transfer as the payment method.
                - Return the created order ID and a summary of the order (total and number of items).
                DESC;
    }

    /**
     * Execute the tool.
     */
    public function handle(Request $request): Stringable|string
    {
        $cart = $this->cartService->getCart($this->site_id, $this->cart_id);

        if (empty($cart['items']))
            return json_encode(['error' => 'Cart is empty. Cannot checkout.']);

        $user = Auth::user();
        $lastOrder = $user->orders()->latest()->first();
        
        if (!$lastOrder)
            return json_encode(['error' => 'No previous order found. Cannot determine shipping information.']);
        
        $shipping = [
            'shipping_name' => $lastOrder->shipping_name,
            'shipping_address' => $lastOrder->shipping_address,
            'shipping_city' => $lastOrder->shipping_city,
            'shipping_country' => $lastOrder->shipping_country,
            'site' => $this->site_id,
        ];

        try {
            $order = $this->orderService->create($user, $cart, $shipping);
            $this->cartService->clear($this->site_id, $this->cart_id);

            return json_encode([
                'message' => 'Order created successfully',
                'order_id' => $order->id,
                'total' => $order->total,
                'items_count' => $order->items->count()
            ]);

        } catch (\Throwable $th) {
            return json_encode(['error' => 'Checkout failed: ' . $th->getMessage()]);
        }
    }

    /**
     * Get the tool's schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'value' => $schema->string()->required(),
        ];
    }
}
