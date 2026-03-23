<?php

namespace App\Http\Controllers;

use App\Models\StockIssueType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StockIssueTypeController extends Controller
{
    //direct issue type page
    public function issueTypePage()
    {
        $stockIssueTypeList = StockIssueType::query()->get(); //->where('is_discontinued',null)
        return view('admin.stock_control.issue_type.issue_type', compact('stockIssueTypeList'));
    }

    public function createStockIssueType(Request $request)
    {
        $this->validationCheck($request);
        $data = $this->addStockIssueTypeData($request);
        // dd($data);
        StockIssueType::create($data);
        return redirect()->route('stockControl#issue_type')->with('success', 'Stock Issue Type Created Successfully');
    }

    public function updateStockIssueTypeModal(Request $request)
    {
        $selectedIssueType = StockIssueType::query()->where(['issue_type_id' => $request->issue_type_id])->get();
        return response()->json(['success' => $selectedIssueType]);
    }

    public function updateStockIssueType(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'edit_issue_type_code' => 'required|unique:stock_issue_types,issue_type_code,' . $request->issue_type_id . ',issue_type_id',
            'edit_issue_type_name1' => 'required|unique:stock_issue_types,issue_type_name_1,' . $request->issue_type_id . ',issue_type_id',
        ], [
            'edit_issue_type_code.required' => 'You need to enter type code',
            'edit_issue_type_code.unique' => 'Type code is already exits!',
            'edit_issue_type_name1.required' => 'You need to enter type name',
            'edit_issue_type_name1.unique' => 'Type name is already exits!'
        ]);
        if ($validator->passes()) {
            try {
                StockIssueType::where('issue_type_id', '=', $request->issue_type_id)->update([
                    'issue_type_code' => $request->edit_issue_type_code,
                    'issue_type_name_1' => $request->edit_issue_type_name1,
                    'issue_type_name_2' => $request->edit_issue_type_name2,
                    'is_discontinued' => filter_var($request->edit_is_discontinued, FILTER_VALIDATE_BOOLEAN),
                    'is_updated' => true,
                    'modified_by' => $request->loginUserID,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
                return response()->json(['success' => $request->issue_type_id]);
            } catch (\Exception $e) {
                return response()->json(['errors' => $e->getMessage()]);
            }
        } else {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
        }
    }

    private function addStockIssueTypeData($request)
    {
        $data = [
            'issue_type_code' => $request->issue_type_code,
            'issue_type_name_1' => $request->issue_type_name1,
            'issue_type_name_2' => $request->issue_type_name2,
            'is_discontinued' => filter_var($request->is_discontinued, FILTER_VALIDATE_BOOLEAN),
            'location_id' => 1,
            'is_updated' => false,
            'modified_by' => $request->loginUserID
        ];
        return $data;
    }

    //Validation
    private function validationCheck($req)
    {
        $validationRules = [
            'issue_type_code' => 'required|unique:stock_issue_types,issue_type_code',
            'issue_type_name1' => 'required|unique:stock_issue_types,issue_type_name_1',
        ];

        $validationMessages = [
            'issue_type_code.required' => 'Issue Type Code ဖြည့်ရန်လိုအပ်ပါသည်',
            'issue_type_code.unique' => 'Issue Type Code တူနေပါသည်',
            'issue_type_name1.required' => 'Issue Type Name ဖြည့်ရန်လိုအပ်ပါသည်',
            'issue_type_name1.unique' => 'Issue Type name တူနေပါသည်'
        ];

        Validator::make($req->all(), $validationRules, $validationMessages)->validate();
    }
}
