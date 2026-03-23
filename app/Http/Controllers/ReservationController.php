<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Floor;
use App\Models\Order;
use App\Models\Table;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Propaganistas\LaravelPhone\Rules\Phone;

class ReservationController extends Controller
{
    //direct reservation page
    public function reservationPage()
    {

        $floors = Floor::where('is_deleted', 0)->where('is_discontinued', 0)->get();
        $firstFloorID = $floors->first()?->floor_id;

        $tables = Table::where('tables.is_deleted', 0)
            ->where('tables.is_discontinued', 0)
            ->where('tables.floor_id', $firstFloorID)
            ->join('floors', 'tables.floor_id', 'floors.floor_id')
            ->get();

        $todayDate = Carbon::now()->format('Y-m-d');

        $occupiedTables = Order::get();
        $reservationTables = Reservation::where('reservation_date', "<", $todayDate)->delete();
        // $occupiedTables = Order::whereDate('created_at', $todayDate)->get();
        $reservationTables = Reservation::where('reservation_date', $todayDate)->get();

        return view('admin.store.reservation.table_reservation', compact('floors', 'tables', 'occupiedTables', 'reservationTables'));
    }

    public function getTableByDate(Request $req)
    {
        $reservationDate = $req->query('reservationDate');
        $floorID = $req->query('selectedFloorID');

        $tables = Table::where('tables.floor_id', $floorID)
            ->join('floors', 'tables.floor_id', 'floors.floor_id')
            ->get();

        // $occupiedTables = Order::whereDate('created_at', $reservationDate)->pluck('table_id')->toArray(); // Assuming 'table_id' is the column in the Order model representing occupied tables
        $occupiedTables = Order::pluck('table_id')->toArray();

        $reservationTables = Reservation::where('reservation_date', $reservationDate)
            ->pluck('table_id')->toArray();


        return response()->json(['tables' => $tables, 'occupiedTables' => $occupiedTables, 'reservationTables' => $reservationTables]);
    }

    public function getReservationByTableID(Request $req)
    {

        $tableID = $req->query('tableID');
        $reservationDate = $req->query('reservationDate');



        $reservationDetails = Reservation::where('table_id', $tableID)
            ->where('reservation_date', $reservationDate)
            ->get();
        return response()->json($reservationDetails);
    }

    //Create Reservation
    // public function createReservation(Request $req)
    // {
    //     $this->validationCheck($req);
    //     $data = $this->addReservationData($req);

    //     Reservation::create($data);

    //     return response()->json(['success' => 'Reservation created successfully.']);

    //     // return redirect()->route('store#reservationPage');
    // }

    public function createReservation(Request $req)
{
    $this->validationCheck($req);
    $data = $this->addReservationData($req);

    Reservation::create($data);

    return response()->json([
        'success' => true,
        'message' => 'Reservation created successfully.'
    ], 200);
}


    //Delete Reservation
    public function deleteReservationByTableID(Request $req)
    {
        $tableID = $req->query('tableID');
        $reservationDate = $req->query('reservationDate');

        Reservation::where('table_id', $tableID)
            ->where('reservation_date', $reservationDate)
            ->delete();

        return response()->json($tableID);
    }

    //private
    //Add Reservation Data
    private function addReservationData($req)
    {
        $data = [
            'table_id' => $req->table_id,
            'table_order_number' => "1",
            'name' => $req->name,
            'phone_number' => $req->phone_number,
            'number_of_person' => $req->number_of_person,
            'reservation_date' => $req->reservation_date,
            'reservation_time' => $req->reservation_time,
            'modified_by' => Auth::user()->id,
        ];
        return $data;
    }

    //validation check
    private function validationCheck($req)
    {
        $validationRules = [
            'name' => 'required',
            // 'phone_number' => ['required', new Phone(['MMR'])],
            'phone_number' => 'required|numeric|digits_between:7,11',
            'number_of_person' => 'required',
            'reservation_date' => 'required',
            'reservation_time' => 'required',
        ];

        $validationMessages = [
            'name.required' => 'Name ဖြည့်ရန်လိုအပ်ပါသည်',
            'phone_number.required' => 'Phone Number ဖြည့်ရန်လိုအပ်ပါသည်',
            'phone_number.numeric' => 'Phone Number သည် ကိန်းဂဏန်းများဖြစ်ရမည်',
            'phone_number.digits_between' => 'Phone Number မှားယွင်းနေပါသည်',
            'number_of_person.required' => 'Number of Person ဖြည့်ရန်လိုအပ်ပါသည်',
            'reservation_date.required' => 'Reservation Date ဖြည့်ရန်လိုအပ်ပါသည်',
            'reservation_time.required' => 'Reservation Time ဖြည့်ရန်လိုအပ်ပါသည်',
        ];

        Validator::make($req->all(), $validationRules, $validationMessages)->validate();
    }
}
