<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Table;
use App\Models\Order;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TableController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(string $id)
    {
                     
                // $floorID = $request->query('id');
        // $tables = Table::where('is_discontinued', 0)->where('is_deleted', operator: 0)->where('floor_id',operator: $id)->get();
        // return response()->json([
        //     'status' => 'success',
        //     'message' => 'Tables fetched successfully',
        //     'data' => [
        //         'tables' => $tables,
        //     ]
        // ], 200);
        // return response()->json($tables);

                // Validate incoming request
                // $request->validate([
                //     'selectedFloorID' => 'required|integer|exists:floors,floor_id',
                // ]);
        
                // $floorID = $request->query('selectedFloorID');
        
                // Get occupied tables
                $occupiedTables = Order::pluck('table_id')->toArray();
                // return response()->json($occupiedTables);
        
                // Fetch tables with floor details
                $tables = Table::where('is_discontinued', 0)->where('is_deleted', operator: 0)->where('floor_id',operator: $id)->get();

                // $tables = Table::where('floor_id', $id)
                // ->with('floor') // Assuming a relationship exists
                // ->get()
                // ->map(function ($table) use ($occupiedTables) {
                //     $table->is_occupied = in_array($table->id, $occupiedTables);
                //     return $table;
                // });
        
                // Get reservation tables based on date and time
                $todayDate = Carbon::now()->format('Y-m-d');
                $currentTime = Carbon::now()->addMinutes(120)->format('H:i:s');
        
                $reservationTables = Reservation::where('reservation_date', $todayDate)
                    ->where('reservation_time', '<=', $currentTime)
                    ->pluck('table_id')
                    ->toArray();
        
                return response()->json([
                    'status' => 'success',
                    'message' => 'Tables fetched successfully',
                    'data' => [
                        'tables' => $tables,
                        'occupiedTables' => $occupiedTables,
                        'reservationTables' => $reservationTables
                    ]
                ], 200);
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

    public function getcompanyinfo()
    {
             
                $occupiedTables = Order::pluck('table_id')->toArray();
                // return response()->json($occupiedTables);
        
                // Fetch tables with floor details
                $companyinfo = Table::where('is_discontinued', 0)->where('is_deleted', operator: 0)->where('floor_id',1)->get();

                // $tables = Table::where('floor_id', $id)
                // ->with('floor') // Assuming a relationship exists
                // ->get()
                // ->map(function ($table) use ($occupiedTables) {
                //     $table->is_occupied = in_array($table->id, $occupiedTables);
                //     return $table;
                // });
        
                // Get reservation tables based on date and time
                $todayDate = Carbon::now()->format('Y-m-d');
                $currentTime = Carbon::now()->addMinutes(120)->format('H:i:s');
        
                $reservationTables = Reservation::where('reservation_date', $todayDate)
                    ->where('reservation_time', '<=', $currentTime)
                    ->pluck('table_id')
                    ->toArray();
        
                return response()->json([
                    'status' => 'success',
                    'message' => 'Tables fetched successfully',
                    'data' => [
                        'tables' => $companyinfo,
                        'occupiedTables' => $occupiedTables,
                        'reservationTables' => $reservationTables
                    ]
                ], 200);
    }
}
