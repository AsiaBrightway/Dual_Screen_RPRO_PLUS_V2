<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\MemberCard;
use Illuminate\Http\Request;
use App\Models\MemberCardType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MemberCardController extends Controller
{
    //direct member card page
    public function memberCardPage()
    {
        $customers = Customer::get()->toArray();
        $memberCardTypes = MemberCardType::get()->toArray();
        $memberCards = MemberCard::select('*', 'member_cards.is_discontinued as member_card_is_discontinued', 'member_cards.remark as member_card_remark')
            ->join('customers', 'member_cards.customer_id', 'customers.customer_id')
            ->join('member_card_types', 'member_cards.member_card_type_id', 'member_card_types.member_card_type_id')
            ->get()
            ->toArray();

        return view('admin.card.member.member_card', compact('customers', 'memberCardTypes', 'memberCards'));
    }

    //Get Member Card Type By Member Card Type ID (dropdown)
    public function getMemberCardTypeByMemberCardTypeID(Request $req)
    {
        $member_card_type_id = $req->query('memberCardTypeID');
        $memberCardType = MemberCardType::where('member_card_type_id', $member_card_type_id)->get();
        return response()->json($memberCardType);
    }

    //Member Card create
    public function createMemberCard(Request $req)
    {

        $this->validationCheck($req);
        $data = $this->addMemberCardData($req);
        if ($data['is_discontinued'] == null || $data['is_discontinued'] == "null") {
            $data['is_discontinued'] = 0;
        }
        if ($data['is_discontinued'] == "on") {
            $data['is_discontinued'] = 1;
        }
        MemberCard::create($data);
        return redirect()->route('card#memberCard#memberCardPage')->with('success', 'Member Card Created Successfully');
    }

    //member card  update
    public function updateMemberCard(Request $req)
    {

        $memberCardID = $req->edit_member_card_id;
        $data = $this->addMemberCardUpdateData($req);

        if ($data['is_discontinued'] == null || $data['is_discontinued'] == "null") {
            $data['is_discontinued'] = 0;
        }
        if ($data['is_discontinued'] == "on") {
            $data['is_discontinued'] = 1;
        }

        MemberCard::where('member_card_id', $memberCardID)->update($data);
        return redirect()->route('card#memberCard#memberCardPage')->with('update', 'Member Card Updated Successfully');
    }

    //member card delete
    public function deleteMemberCard(Request $req)
    {

        $memberCardID = $req->delete_member_card_id;

        MemberCard::where('member_card_id', $memberCardID)->delete();
        return redirect()->route('card#memberCard#memberCardPage')->with('delete', 'Member Card Deleted Successfully');
    }

    //Private Functions
    //add member card data
    private function addMemberCardData($req)
    {
        $data = [
            'customer_id' => $req->customer,
            'member_card_type_id' => $req->member_card_type,
            'member_card_code' => $req->card_code,
            'create_date' => $req->create_date,
            'expire_date' => $req->expire_date,
            'has_expire' => "1",
            'remark' => $req->remark,
            'is_expired' => "0",
            'is_discontinued' => $req->is_discontinued,
            'is_deleted' => "0",
            'location_id' => "1",
            'is_updated' => "0",
            'modified_by' => Auth::user()->id,

        ];
        return $data;
    }

    //add member card update data
    private function addMemberCardUpdateData($req)
    {
        $data = [
            'customer_id' => $req->edit_customer,
            'member_card_type_id' => $req->edit_member_card_type,
            'member_card_code' => $req->edit_card_code,
            'create_date' => $req->edit_create_date,
            'expire_date' => $req->edit_expire_date,
            'has_expire' => "1",
            'remark' => $req->edit_remark,
            'is_expired' => "0",
            'is_discontinued' => $req->edit_member_card_is_discontinued,
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
            'customer' => 'required|not_in:0',
            'member_card_type' => 'required|not_in:0',
            'card_code' => 'required|unique:member_cards,member_card_code',
            'create_date' => 'required',
            'expire_date' => 'required',
        ];

        $validationMessages = [
            'customer.required' => 'Customer Name ရွေးရန်လိုအပ်ပါသည်',
            'customer.not_in' => 'Customer Name ရွေးရန်လိုအပ်ပါသည်',
            'member_card_type.required' => 'Card Type Name ရွေးရန်လိုအပ်ပါသည်',
            'member_card_type.not_in' => 'Card Type Name ရွေးရန်လိုအပ်ပါသည်',
            'card_code.required' => 'Card Code ဖြည့်ရန်လိုအပ်ပါသည်',
            'card_code.unique' => 'Card Code တူနေပါသည်',
            'create_date.required' => 'Create Date ရွေးရန်လိုအပ်ပါသည်',
            'expire_date.required' => 'Expire Date ရွေးရန်လိုအပ်ပါသည်',

        ];

        Validator::make($req->all(), $validationRules, $validationMessages)->validate();
    }
}
