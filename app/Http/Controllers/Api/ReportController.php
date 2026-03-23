<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\Sales;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function dailyShopReport(Request $request) {

        $date = $request->date ?? now()->toDateString();

        $sales = Sales::whereDate('created_at', $date)
            ->where('is_delete', 0)
            ->selectRaw('
                SUM(total_amount) as total_amount,
                SUM(total_item_promo_amount + voucher_discount_amount) as total_discount,
                COUNT(*) as sales_count
            ')
            ->first();

        $purchases = Purchase::whereDate('created_at', $date)
            ->where('is_delete', 0)
            ->selectRaw('
                SUM(total_amount) as total_amount,
                SUM(total_item_discount) as total_discount,
                COUNT(*) as purchases_count
            ')
            ->first();

        return response()->json([
            'success' => true,
            'date' => $date,
            'sales_amount' => $sales->total_amount ?? 0,
            'sales_discount' => $sales->total_discount ?? 0,
            'sales_count' => $sales->sales_count ?? 0,
            'purchases_amount' => $purchases->total_amount ?? 0,
            'purchases_discount' => $purchases->total_discount ?? 0,
            'purchases_count' => $purchases->purchases_count ?? 0,
        ]);
    }
}
