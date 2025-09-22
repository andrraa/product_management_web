<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            $type = $request->get('type');
            $search = $request->get('search');

            $query = Product::query()
                ->select([
                        'product_id',
                        'name',
                        'price',
                        'stock',
                        'type'
                ]);

            if (!empty($type)) {
                $query->where('type', $type);
            }

            if (!empty($search)) {
                $query->where('name', 'like', "%{$search}%");
            }

            $products = $query->get();

            return response()->json($products);
        }
        
        return view('dashboard');
    }
}