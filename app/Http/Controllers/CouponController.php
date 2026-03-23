<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CouponController extends Controller
{
    //direct coupon card page
    public function couponCardPage()
    {
        $coupon = Coupon::get()->last();
        if ($coupon == null || $coupon == "null") {
            $couponLastID = 0;
        } else {
            $couponLastID = $coupon->coupon_id;
        }

        $coupons = Coupon::get()->toArray();
        return view('admin.card.coupon.coupon_card', compact('couponLastID', 'coupons'));
    }

    //Coupon Card create
    public function createCouponCard(Request $req)
    {

        $coupon = Coupon::get()->last();
        if ($coupon == null || $coupon == "null") {
            $couponLastID = 0;
        } else {
            $couponLastID = $coupon->coupon_id;
        }

        $this->validationCheck($req);

        $couponCount = $req->coupon_count;

        for ($i = 1; $i <= $couponCount; $i++) {
            $data = $this->addCouponCardData($req);
            $data['coupon_code'] = "CP-" . ($i + $couponLastID);
            Coupon::create($data);
        }
        return redirect()->route('card#couponCardPage')->with('success', 'Coupon card created successfully.');
    }

    //coupon card  update
    public function updateCouponCard(Request $req)
    {

        $couponCardID = $req->edit_coupon_card_id;
        $data = $this->addCouponCardUpdateData($req);

        if ($data['is_discontinued'] == null || $data['is_discontinued'] == "null") {
            $data['is_discontinued'] = 0;
        }
        if ($data['is_discontinued'] == "on") {
            $data['is_discontinued'] = 1;
        }

        Coupon::where('coupon_id', $couponCardID)->update($data);
        return redirect()->route('card#couponCardPage')->with('update', 'Coupon card updated successfully.');
    }

    //coupon card delete
    public function deleteCouponCard(Request $req)
    {

        $couponCardID = $req->delete_coupon_card_id;

        Coupon::where('coupon_id', $couponCardID)->delete();
        return redirect()->route('card#couponCardPage')->with('delete', 'Coupon card deleted successfully.');
    }

    //Private Functions
    //add coupon card data
    private function addCouponCardData($req)
    {
        $data = [
            'coupon_name' => $req->coupon_name,
            'discount_type' => $req->radio_discount_type,
            'amount_discount' => $req->amount_discount,
            'percent_discount' => $req->percent_discount,
            'min_order_amount' => $req->min_order_amount,
            'expire_date' => $req->expire_date,
            'is_used' => "0",
            'is_discontinued' => "0",
            'is_deleted' => "0",
            'location_id' => "1",
            'is_updated' => "0",
            'modified_by' => Auth::user()->id,

        ];
        return $data;
    }

    //add coupon card update data
    private function addCouponCardUpdateData($req)
    {
        $data = [
            'coupon_code' => $req->edit_coupon_code,
            'coupon_name' => $req->edit_coupon_name,
            'discount_type' => $req->edit_radio_discount_type,
            'amount_discount' => $req->edit_amount_discount,
            'percent_discount' => $req->edit_percent_discount,
            'min_order_amount' => $req->edit_min_order_amount,
            'expire_date' => $req->edit_expire_date,
            'is_used' => "0",
            'is_discontinued' => $req->edit_coupon_card_is_discontinued,
            'is_deleted' => "0",
            'location_id' => "1",
            'is_updated' => "0",
            'modified_by' => Auth::user()->id,
        ];
        return $data;
    }

    //Validation
    private function validationCheck($req)
    {
        $validationRules = [
            'coupon_count' => 'required',
            'coupon_name' => 'required',
            'amount_discount' => 'required',
            'percent_discount' => 'required',
            'min_order_amount' => 'required',
            'expire_date' => 'required',
        ];

        $validationMessages = [
            'coupon_count.required' => 'Coupon Count ဖြည့်ရန်လိုအပ်ပါသည်',
            'coupon_name.required' => 'Coupon Name ဖြည့်ရန်လိုအပ်ပါသည်',
            'amount_discount.required' => 'Amount Discount ဖြည့်ရန်လိုအပ်ပါသည်',
            'percent_discount.required' => 'Percent Discount ဖြည့်ရန်လိုအပ်ပါသည်',
            'min_order_amount.required' => 'Min-Order Amount ဖြည့်ရန်လိုအပ်ပါသည်',
            'expire_date.required' => 'Expire Date ရွေးရန်လိုအပ်ပါသည်',

        ];

        Validator::make($req->all(), $validationRules, $validationMessages)->validate();
    }
}
