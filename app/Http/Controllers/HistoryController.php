<?php

namespace App\Http\Controllers;

use App\Models\Checkout;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class HistoryController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            $user = Auth::user();

            $histories = Checkout::query()
                ->select([
                    'name',
                    'quantity',
                    'payment_method',
                    'price',
                    'type',
                    'total_price',
                    'fk_user_id'
                ])
                ->where('fk_user_id', $user->user_id);

            return DataTables::of($histories)
                ->addIndexColumn()
                ->escapeColumns()
                ->toJson();
        }

        return view('history.index');
    }
}
