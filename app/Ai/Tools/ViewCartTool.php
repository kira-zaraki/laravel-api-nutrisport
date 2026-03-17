<?php

namespace App\Ai\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use App\Ai\Tools\ToolAgent;
use Stringable;

class ViewCartTool extends ToolAgent implements Tool
{
    
    /**
     * Get the description of the tool's purpose.
     */
    public function description(): Stringable|string
    {
        return <<<'DESC'
                Retrieve the current contents of the customer's cart.

                Important rules for the AI:
                - Do not invent products.
                - Always return the actual cart contents from the CartService.
                - The AI can use this tool to show the user their current cart.
                - Return the cart in JSON format including product_id, quantity, name (if available), stock, and price.
                DESC;
    }

    /**
     * Execute the tool.
     */
    public function handle(Request $request): Stringable|string
    {
        $cart = $this->cartService->getCart($this->site_id, $this->cart_id);

        return json_encode([
            'message' => 'Current cart contents',
            'cart' => $cart
        ]);
    }

    /**
     * Get the tool's schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [];
    }
}
