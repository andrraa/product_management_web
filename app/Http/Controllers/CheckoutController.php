<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Models\Checkout;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function checkout(CheckoutRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $user = Auth::user();

        foreach ($validated['cart'] as $item) {
            Checkout::create([
                'name'          => $item['name'],
                'quantity'      => $item['quantity'],
                'type'          => $item['type'],
                'price'         => $item['price'],
                'total_price'   => $item['price'] * $item['quantity'],
                'payment_method'=> $validated['payment'],
                'fk_user_id'    => $user->user_id,
            ]);

            Product::where('product_id', $item['id'])
                ->where('type', Product::TYPE_FOOD)
                ->decrement('stock', $item['quantity']);
        }

        return response()->json(['success' => true]);
    }
}