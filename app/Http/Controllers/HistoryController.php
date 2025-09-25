<?php

namespace App\Http\Controllers;

use App\Models\Checkout;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class HistoryController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            $user = Auth::user();

            $histories = Checkout::with(['user'])
                ->select([
                    'name',
                    'quantity',
                    'payment_method',
                    'price',
                    'type',
                    'total_price',
                    'fk_user_id',
                    'created_at'
                ])
                ->where('fk_user_id', $user->user_id);

            if ($request->filled('start_datetime') && $request->filled('end_datetime')) {
                $start = Carbon::parse($request->start_datetime, 'Asia/Makassar')->timezone('UTC');
                $end   = Carbon::parse($request->end_datetime, 'Asia/Makassar')->timezone('UTC');

                $histories->whereBetween('created_at', [$start, $end]);
            }

            $baseQuery = clone $histories;

            $qrisTotal   = (clone $baseQuery)->where('payment_method', 'qris')->sum('total_price');
            $qrisCount   = (clone $baseQuery)->where('payment_method', 'qris')->count();

            $tunaiTotal  = (clone $baseQuery)->where('payment_method', 'tunai')->sum('total_price');
            $tunaiCount  = (clone $baseQuery)->where('payment_method', 'tunai')->count();

            return DataTables::of($histories)
                ->addIndexColumn()
                ->with([
                    'qris_total'  => $qrisTotal,
                    'qris_count'  => $qrisCount,
                    'tunai_total' => $tunaiTotal,
                    'tunai_count' => $tunaiCount,
                ])
                ->escapeColumns()
                ->toJson();
        }

        return view('history.index');
    }

    public function export(Request $request): Response
    {
        $user = Auth::user();

        $query = Checkout::query()
            ->where('fk_user_id', $user->user_id);

        $start = Carbon::parse($request->start_datetime, 'Asia/Makassar')->timezone('UTC');
        $end   = Carbon::parse($request->end_datetime, 'Asia/Makassar')->timezone('UTC');

        $query->whereBetween('created_at', [$start, $end]);

        $histories = $query->orderBy('created_at', 'desc')->get();

        $jumlahFoodQris = $histories->where('type', 'food')->where('payment_method', 'qris')
            ->count();
        $totalFoodQris  = $histories->where('type', 'food')->where('payment_method', 'qris')
            ->sum('total_price');

        $jumlahFoodTunai = $histories->where('type', 'food')->where('payment_method', 'tunai')
            ->count();
        $totalFoodTunai  = $histories->where('type', 'food')->where('payment_method', 'tunai')
            ->sum('total_price');

        $jumlahBillingQris = $histories->where('type', 'billing')->where('payment_method', 'qris')
            ->count();
        $totalBillingQris  = $histories->where('type', 'billing')->where('payment_method', 'qris')
            ->sum('total_price');

        $jumlahBillingTunai = $histories->where('type', 'billing')->where('payment_method', 'tunai')
            ->count();
        $totalBillingTunai  = $histories->where('type', 'billing')->where('payment_method', 'tunai')
            ->sum('total_price');

        $pdf = Pdf::loadView('history.pdf', compact([
            'histories',
            'jumlahFoodQris',
            'totalFoodQris',
            'jumlahFoodTunai',
            'totalFoodTunai',
            'jumlahBillingQris',
            'totalBillingQris',
            'jumlahBillingTunai',
            'totalBillingTunai',
            'start',
            'end']
        ))->setPaper('a4', 'landscape');

        return $pdf->download("report-{$start->format('Y-m-d')}_to_{$end->format('Y-m-d')}.pdf");
    }
}