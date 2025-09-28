<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookingRequest;
use App\Models\Booking;
use App\Models\Checkout;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class BookingController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->user = Auth::user();
    }

    public function index(): JsonResponse
    {
        $user = $this->user;

        $bookings = Booking::all()
            ->where('by_user_id', $user->user_id)
            ->where('is_completed', '!=', 1);

        return DataTables::of($bookings)
            ->addIndexColumn()
            ->escapeColumns()
            ->addColumn('actions', function ($booking) {
                return [
                    'edit' => route('booking.update', $booking->booking_id),
                    'delete' => route('booking.destroy', $booking->booking_id),
                ];
            })
            ->toJson();
    }

    public function store(BookingRequest $request): JsonResponse
    {
        $validated = $request->validated();

        foreach ($validated['cart'] as $item) {
            Booking::create([
                'customer_name' => $validated['name'],
                'customer_phone' => $validated['phone'] ?? null,
                'payment_method' => $validated['payment_method'],
                'package_name' => $item['name'],
                'package_price' => $item['price'],
                'package_quantity' => $item['quantity'],
                'total' => $item['price'] * $item['quantity'],
                'by_user_id' => $this->user->user_id,
                'by_user_name' => $this->user->username,
                'notes' => $validated['notes'],
                'is_paid' => $validated['payment_status'],
            ]);
        }

        return response()->json(['success' => true]);
    }

    public function update(Request $request, Booking $booking): JsonResponse
    {
        $booking->update([
            'is_completed' => 1
        ]);

        Checkout::create([
            'name' => $booking->customer_name,
            'quantity' => $booking->package_quantity,
            'type' => Product::TYPE_BILLING,
            'price' => $booking->package_price,
            'total_price' => $booking->total,
            'payment_method' => $booking->payment_method,
            'fk_user_id' => $booking->by_user_id,
        ]);

        return response()->json(['success' => true]);
    }

    public function destroy(Booking $booking): JsonResponse
    {
        $booking->delete();

        return response()->json(['success' => true]);
    }
}
