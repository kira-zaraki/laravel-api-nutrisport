<?php

namespace App\Ai\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use App\Services\CartService;
use App\Ai\Tools\ToolAgent;
use Stringable;

class AddToCartTool extends ToolAgent implements Tool
{
    public function __construct(
        protected int $site_id,
        protected string $cart_id,
        protected CartService $cartService
    ) {}

    /**
     * Get the description of the tool's purpose.
     */
    public function description(): Stringable|string
    {
        return <<<'DESC'
                Add one or more products to the customer's cart.

                Important rules for the AI:
                - Only use valid product IDs that were provided by the last product search.
                - Do not invent product IDs or names.
                - If the user refers to products like "these products" or "the first two", use the IDs from the last product list.
                - Each product object must include:
                    - product_id: the numeric ID of the product
                    - quantity: number of units to add (default 1)
                - After adding, return the updated cart contents.
                DESC;
    }

    /**
     * Execute the tool.
     */
    public function handle(Request $request): Stringable|string
    {
        $products = $request['products'];

        foreach ($items as $item) {
            $this->cartService->add(
                $this->site_id,
                $this->cart_id,
                $item['product_id'],
                $item['quantity'] ?? 1
            );
        }

        return json_encode('$cart');
    }

    /**
     * Get the tool's schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'products' => $schema->array(
                $schema->object([
                    'product_id' => $schema->integer()->description('The numeric ID of the product from the search results'),
                    'name' => $schema->string()->description('Optional: Product name, can be used to find ID from previous messages'),
                    'quantity' => $schema->integer()->default(1),
                    'index' => $schema->integer()->description('Optional: position of the product in the last message list')
                ])
            )->required()
        ];
    }
}
