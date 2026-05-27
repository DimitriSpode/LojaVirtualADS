<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreProductRequest;
use App\Http\Requests\Api\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class ProductApiController extends Controller
{
    public function index(): JsonResponse
    {
        $products = Product::query()
            ->with('type')
            ->orderBy('name')
            ->get();

        return response()->json($products);
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        $product = Product::create($request->validated());

        return response()->json($product->load('type'), 201);
    }

    public function update(UpdateProductRequest $request, Product $product): JsonResponse
    {
        $product->update($request->validated());

        return response()->json($product->load('type'));
    }

    public function destroy(Product $product): JsonResponse
    {
        $product->delete();

        return response()->json([
            'message' => 'Produto excluído com sucesso.',
        ]);
    }
}
