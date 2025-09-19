<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        return view('product.index');
    }

    public function create(): View
    {
        $validator = Helper::generateValidator(ProductRequest::class, '#form-product');

        return view('product.create', compact('validator'));
    }

    public function store(ProductRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        if (Product::create($validated)) {
            return to_route('product.index')->with('success', 'Product created successfully!');
        }

        return to_route('product.index')->with('error', 'Failed to create product!');
    }

    public function edit(Product $product): View
    {
        $validator = Helper::generateValidator(ProductRequest::class, '#form-product');

        return view('product.edit', compact('validator'));
    }

    public function update(ProductRequest $request, Product $product): RedirectResponse
    {
        $validated = $request->validated();

        if ($product->update($validated)) {
            return to_route('product.index')->with('success', 'Product updated successfully!');
        }

        return to_route('product.index')->with('error', 'Failed to update product!');
    }

    public function destroy(Request $request, Product $product): JsonResponse
    {
        if (!$request->expectsJson()) {
            abort(403);
        }

        $product->delete();

        return response()->json(['success' => true]);
    }
}
