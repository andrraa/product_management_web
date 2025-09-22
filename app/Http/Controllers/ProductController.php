<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class ProductController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            $products = Product::query()
                ->select([
                    'product_id',
                    'name',
                    'price',
                    'stock',
                    'type'
                ]);

            return DataTables::of($products)
                ->addIndexColumn()
                ->escapeColumns()
                ->addColumn('actions', function($product) {
                    return [
                        'edit' => route('product.edit', $product->product_id),
                        'delete' => route('product.destroy', $product->product_id),
                    ];
                })
                ->toJson();
        }

        $totals = [
            'food'    => Product::where('type', Product::TYPE_FOOD)->count(),
            'billing' => Product::where('type', Product::TYPE_BILLING)->count(),
        ];
        
        return view('product.index', compact('totals'));
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

        return view('product.edit', compact(['validator', 'product']));
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