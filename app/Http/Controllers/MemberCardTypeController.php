<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MemberCardType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MemberCardTypeController extends Controller
{
    //direct member card type page
    public function memberCardTypePage()
    {
        $memberCardTypes = MemberCardType::get()->toArray();
        return view('admin.card.member.member_card_type', compact('memberCardTypes'));
    }

    //Member Card Type create
    public function createMemberCardType(Request $req)
    {

        $this->validationCheck($req);
        $data = $this->addMemberCardTypeData($req);
        if ($data['is_discontinued'] == null || $data['is_discontinued'] == "null") {
            $data['is_discontinued'] = 0;
        }
        if ($data['is_discontinued'] == "on") {
            $data['is_discontinued'] = 1;
        }
        MemberCardType::create($data);
        return redirect()->route('card#memberCard#memberCardTypePage')->with(['success' => 'Member Card Type Created Successfully']);
    }

    //member card type  update
    public function updateMemberCardType(Request $req)
    {

        $memberCardTypeID = $req->edit_member_card_type_id;
        $data = $this->addMemberCardTypeUpdateData($req);

        if ($data['is_discontinued'] == null || $data['is_discontinued'] == "null") {
            $data['is_discontinued'] = 0;
        }
        if ($data['is_discontinued'] == "on") {
            $data['is_discontinued'] = 1;
        }

        MemberCardType::where('member_card_type_id', $memberCardTypeID)->update($data);
        return redirect()->route('card#memberCard#memberCardTypePage')->with('update', 'Member Card Type Updated Successfully');
    }

    //member card type delete
    public function deleteMemberCardType(Request $req)
    {

        $memberCardTypeID = $req->delete_member_card_type_id;

        MemberCardType::where('member_card_type_id', $memberCardTypeID)->delete();
        return redirect()->route('card#memberCard#memberCardTypePage')->with('delete', 'Member Card Type Deleted Successfully');
    }

    //Private Functions
    //add member card type data
    private function addMemberCardTypeData($req)
    {
        $data = [
            'member_card_type_name' => $req->card_type_name,
            'other_name'  => $req->other_name,
            'discount_type' => $req->radio_discount_type,
            'amount_discount' => $req->amount_discount,
            'percent_discount' => $req->percent_discount,
            'remark' => $req->remark,
            'location_id' => "1",
            'is_deleted' => "0",
            'is_updated' => "0",
            'is_discontinued' => $req->is_discontinued,
            'modified_by' => Auth::user()->id,
        ];
        return $data;
    }

    //add member card type update data
    private function addMemberCardTypeUpdateData($req)
    {
        $data = [
            'member_card_type_name' => $req->edit_member_card_type_name,
            'other_name'  => $req->edit_other_name,
            'discount_type' => $req->edit_radio_discount_type,
            'amount_discount' => $req->edit_amount_discount,
            'percent_discount' => $req->edit_percent_discount,
            'remark' => $req->edit_remark,
            'location_id' => "1",
            'is_deleted' => "0",
            'is_updated' => "0",
            'is_discontinued' => $req->edit_member_card_type_is_discontinued,
            'modified_by' => Auth::user()->id,
        ];
        return $data;
    }

    //Validation
    private function validationCheck($req)
    {
        $validationRules = [

            'card_type_name' => 'required|unique:member_card_types,member_card_type_name',
            'amount_discount' => 'required|numeric',
            'percent_discount' => 'required|numeric',
        ];

        $validationMessages = [
            'card_type_name.required' => 'Card Type Name ဖြည့်ရန်လိုအပ်ပါသည်',
            'card_type_name.unique' => 'Card Type Name တူနေပါသည်',
            'amount_discount.required' => 'Amount Discount ဖြည့်ရန်လိုအပ်ပါသည်',
            'amount_discount.numeric' => 'Amount Discount သည် Number ဖြစ်ရပါမည်',
            'percent_discount.required' => 'Percent Discount ဖြည့်ရန်လိုအပ်ပါသည်',
            'percent_discount.numeric' => 'Percent Discount သည် Number ဖြစ်ရပါမည်',
        ];

        Validator::make($req->all(), $validationRules, $validationMessages)->validate();
    }
}
