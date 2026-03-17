<?php

namespace App\Ai\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use App\Models\Product;
use App\Services\ProductService;
use App\Ai\Tools\ToolAgent;
use Stringable;

class SearchProductsTool extends ToolAgent implements Tool
{

    public function __construct(
        protected int $site_id, 
        private ProductService $productService
    )
    {}

    /**
     * Get the description of the tool's purpose.
     */
    public function description(): Stringable|string
    {
        return 'Search products by keyword';
    }

    /**
     * Execute the tool.
     */
    public function handle(Request $request): Stringable|string
    {
        $keyword = $request['keyword'];

        $products = $this->productService->search($this->site_id, $keyword);

        return json_encode([
            'message' => 'Here are the available products',
            'products' => $products->map(fn ($product) => [
                'product_id' => $product->id,
                'name' => $product->name,
                'stock' => $product->stock,
                'price' => $product?->prices?->first()?->price
            ])
        ]);
    }

    /**
     * Get the tool's schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'keyword' => $schema->string()->description('Search keyword like whey, creatine, protein')->required(),
        ];
    }
}
