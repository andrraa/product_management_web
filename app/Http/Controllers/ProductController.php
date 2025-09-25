<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class ProductController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        $user = Auth::user();

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
                ->addColumn('actions', function($product) use ($user) {
                    $actions = [];

                    if (
                        ($user->role === User::ROLE_EMPLOYEE && $product->type == Product::TYPE_FOOD) ||
                        $user->role === User::ROLE_ADMIN
                    ) {
                        $actions['edit'] = route('product.edit', $product->product_id);
                    }

                    if ($user->role === User::ROLE_ADMIN) {
                        $actions['delete'] = route('product.destroy', $product->product_id);
                    }

                    return $actions;
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

    public function edit(Product $product): View|RedirectResponse
    {
        $user = Auth::user();

        if ($user->role === User::ROLE_EMPLOYEE && $product->type !== Product::TYPE_FOOD) {
            return to_route('product.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengedit produk ini.');
        }

        $validator = Helper::generateValidator(ProductRequest::class, '#form-product');

        return view('product.edit', compact(['validator', 'product']));
    }

    public function update(ProductRequest $request, Product $product): RedirectResponse
    {
        $user = Auth::user();

        if ($user->role === User::ROLE_EMPLOYEE && $product->type !== Product::TYPE_FOOD) {
            return to_route('product.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengedit produk ini.');
        }

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