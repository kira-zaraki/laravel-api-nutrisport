<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreProductRequest;
use App\Services\BackOffice\ProductService;

class AdminProductController extends Controller
{
    public function __construct(
        protected ProductService $productService,
    ) {}
    public function store(StoreProductRequest $request)
    {
        try {
            $product = $this->productService->create($request->only('name', 'stock', 'prices'));
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Failed to create product'], 500);
        }

        return response()->json($product, 201);
    }
}
