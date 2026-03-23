<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Models\PurchaseDetail;
use App\Models\SalesDetail;
use App\Models\StockIssue;
use Illuminate\Http\Request;
use App\Models\StockIssueType;
use App\Models\StockIssueDetail;
use App\Models\StockReceiveDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Termwind\Components\Raw;

class StockIssueController extends Controller
{
    //direct stock issue page
    public function issuePage()
    {
        $itemList = MenuItem::query()->where(['menu_items.is_discontinued' => 0, 'menu_items.is_deleted' => 0])
            // ->whereIn('I.item_type_id', [1, 2, 3])
            ->where('I.item_type_id', '!=', 1)
            ->select('menu_items.*', 'U.unit_name')
            ->join('units as U', 'U.unit_id', '=', 'menu_items.unit_id')
            ->join('item_types as I', 'I.item_type_id', '=', 'menu_items.item_type_id')
            // ->join('item_selling_prices as SP', 'SP.item_id', '=', 'menu_items.item_id')
            ->get();

        $stockIssueTypeList = StockIssueType::query()->where(['is_discontinued' => 0])->get();
        $voucherNo = "SIV-" . date('y') . "-" . StockIssue::whereYear('issue_date', date('Y'))->count() + 1;
        return view('admin.stock_control.stock_issue.issue', compact('itemList', 'stockIssueTypeList', 'voucherNo'));
    }

    public function checkStoreQty(Request $request)
    {

        try {
            [$storeQty, $stockIn, $unitCost] = $this->getStockBalance($request->itemID, $request->unitID);
            $unitCost = (int)$unitCost;
            // dd($unitCost);
            return response()->json(['success' => $storeQty, 'unitCost' => $unitCost]);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }
    }


    public static function getStockBalance($itemID, $unitID)
    {
        //---------------------------Stock In--------------------------//
        $receiveDetailList = StockReceiveDetail::query()
            ->where(['item_id' => $itemID, 'unit_id' => $unitID, 'is_deleted' => 0])
            ->selectRaw('batch_number')
            ->selectRaw('quantity as receiveQty')
            ->selectRaw('0 as purchaseQty')
            ->selectRaw('"Receive" as type')
            ->selectRaw('created_at')
            ->groupBy(['batch_number', 'receiveQty', 'purchaseQty', 'type', 'created_at'])->get();

        $purchaseDetailList = PurchaseDetail::query()
            ->where(['item_id' => $itemID, 'unit_id' => $unitID, 'is_deleted' => 0])
            ->selectRaw('batch_number')
            ->selectRaw('0 as receiveQty')
            ->selectRaw('quantity as purchaseQty')
            ->selectRaw('"Purchase" as type')
            ->selectRaw('created_at')
            ->groupBy(['batch_number', 'receiveQty', 'purchaseQty', 'type', 'created_at'])->get();

        $stockInLists = collect(array_merge($receiveDetailList->toArray(), $purchaseDetailList->toArray()))
            ->map(function ($item) {
                $item['receiveQty'] = (float) $item['receiveQty'];
                $item['purchaseQty'] = (float) $item['purchaseQty'];
                return $item;
            })
            ->sortBy('created_at')
            ->values();

        $itemUnitCost = MenuItem::select('ISP.unit_cost')
            ->join('item_selling_prices as ISP', 'ISP.item_id', '=', 'menu_items.item_id')
            ->where('menu_items.item_id', $itemID)
            ->first();
        // dd($itemID,$itemUnitCost->toArray());
        // Calculate total receiveQty and purchaseQty
        $totalReceiveQty = $stockInLists->sum('receiveQty');
        $totalPurchaseQty = $stockInLists->sum('purchaseQty');
        $totalStockInQty = $totalReceiveQty + $totalPurchaseQty;

        //---------------------------Stock Out--------------------------//
        $issueDetailList = StockIssueDetail::query()
            ->where(['item_id' => $itemID, 'unit_id' => $unitID, 'is_deleted' => 0])
            ->selectRaw('batch_number')
            ->selectRaw('quantity as issueQty')
            ->selectRaw('0 as saleQty')
            ->selectRaw('issue_type as type')
            ->selectRaw('created_at')
            ->groupBy(['batch_number', 'issueQty', 'saleQty', 'type', 'created_at'])->get();

        $salesDetailList = SalesDetail::query()
            ->where(['item_id' => $itemID, 'unit_id' => $unitID])
            ->selectRaw('batch_number')
            ->selectRaw('0 as issueQty')
            ->selectRaw('quantity as saleQty')
            ->selectRaw('sale_type as type')
            ->selectRaw('created_at')
            ->groupBy(['batch_number', 'issueQty', 'saleQty', 'type', 'created_at'])->get();

        $stockOutLists = collect(array_merge($issueDetailList->toArray(), $salesDetailList->toArray()))
            ->map(function ($item) {
                $item['issueQty'] = (float) $item['issueQty'];
                $item['saleQty'] = (float) $item['saleQty'];
                return $item;
            })
            ->sortBy('created_at');

        // Calculate total issueQty and saleQty
        $totalIssueQty = $stockOutLists->sum('issueQty');
        $totalSaleQty = $stockOutLists->sum('saleQty');
        $totalStockOutQty = $totalIssueQty + $totalSaleQty;

        //----------Stock Qty ---------//
        $stockQty = $totalStockInQty - $totalStockOutQty;

        $tempStockOutQty = $totalStockOutQty;
        $stockIn = [];
        foreach ($stockInLists as $key => $stockInList) {
            $totalStockInQty = $stockInList['receiveQty'] + $stockInList['purchaseQty'];
            if ($stockInList['type'] == "Receive") {
                $expireDate = StockReceiveDetail::select('*')->where('unit_id', $unitID)->where('item_id', $itemID)->where('batch_number', $stockInList['batch_number'])->get();
                $expireDate = $expireDate[0]['expire_date'];
            } else if ($stockInList['type'] == "Purchase") {
                $expireDate = PurchaseDetail::select('*')->where('unit_id', $unitID)->where('item_id', $itemID)->where('batch_number', $stockInList['batch_number'])->get();
                $expireDate = $expireDate[0]['expire_date'];
            } else if ($stockInList['type'] == "Issue") {
                $expireDate = StockIssueDetail::select('*')->where('unit_id', $unitID)->where('item_id', $itemID)->where('batch_number', $stockInList['batch_number'])->get();
                $expireDate = $expireDate[0]['expire_date'];
            } else if ($stockInList['type'] == "Sale") {
                $expireDate = SalesDetail::select('*')->where('unit_id', $unitID)->where('item_id', $itemID)->where('batch_number', $stockInList['batch_number'])->get();
                $expireDate = $expireDate[0]['expire_date'];
            }
            if ($tempStockOutQty > 0) {
                if ($tempStockOutQty < $totalStockInQty) {
                    if ($stockInList['receiveQty'] != 0) {
                        $detail = (object) [
                            "batch_number" => $stockInList['batch_number'],
                            "receiveQty" => $stockInList['receiveQty'] - $tempStockOutQty,
                            "purchaseQty" => $stockInList['purchaseQty'],
                            "type" => $stockInList['type'],
                            "created_at" => $stockInList['created_at'],
                            "expire_date" => $expireDate
                        ];
                        array_push($stockIn, $detail);
                    } else if ($stockInList['purchaseQty'] != 0) {
                        $detail = (object) [
                            "batch_number" => $stockInList['batch_number'],
                            "receiveQty" => $stockInList['receiveQty'],
                            "purchaseQty" => $stockInList['purchaseQty'] - $tempStockOutQty,
                            "type" => $stockInList['type'],
                            "created_at" => $stockInList['created_at'],
                            "expire_date" => $expireDate
                        ];
                        array_push($stockIn, $detail);
                    }
                }
                $tempStockOutQty = $tempStockOutQty - $totalStockInQty;
            } else {
                $detail = (object) [
                    "batch_number" => $stockInList['batch_number'],
                    "receiveQty" => $stockInList['receiveQty'],
                    "purchaseQty" => $stockInList['purchaseQty'],
                    "type" => $stockInList['type'],
                    "created_at" => $stockInList['created_at'],
                    "expire_date" => $expireDate
                ];
                array_push($stockIn, $detail);
            }
        }
        // dd($stockQty, $stockIn);
        return [$stockQty, $stockIn, $itemUnitCost->unit_cost];


        // $receiveDetailList = StockReceiveDetail::query()
        //     ->where(['item_id' => $itemID, 'unit_id' => $unitID])
        //     ->selectRaw('IFNULL(unit_cost,0) as unit_cost')
        //     ->selectRaw('batch_number')
        //     ->selectRaw('IFNULL(SUM(quantity),0) as RQty')
        //     ->selectRaw('0 as IQty')
        //     ->selectRaw('0 as BalanceQty')
        //     // ->selectRaw('Date(expire_date) as expire_date')
        //     ->selectRaw('"Receive" as type')
        //     ->selectRaw('created_at')
        //     ->where('is_deleted', 0)
        //     ->groupBy(['unit_cost', 'type', 'created_at', 'batch_number'])->get();

        // $purchaseDetailList = PurchaseDetail::query()
        //     ->where(['item_id' => $itemID, 'unit_id' => $unitID])
        //     ->selectRaw('IFNULL(unit_cost,0) as unit_cost')
        //     ->selectRaw('batch_number')
        //     ->selectRaw('IFNULL(SUM(quantity),0) as RQty')
        //     ->selectRaw('0 as IQty')
        //     ->selectRaw('0 as BalanceQty')
        //     // ->selectRaw('Date(expire_date) as expire_date')
        //     ->selectRaw('"Purchase" as type')
        //     ->selectRaw('created_at')
        //     ->where('is_deleted', 0)
        //     ->groupBy(['unit_cost', 'type', 'created_at', 'batch_number'])->get();

        // $issueDetailList = StockIssueDetail::query()
        //     ->where(['item_id' => $itemID, 'unit_id' => $unitID])
        //     ->selectRaw('IFNULL(unit_cost,0) as unit_cost')
        //     ->selectRaw('batch_number')
        //     ->selectRaw('0 as RQty')
        //     ->selectRaw('IFNULL(SUM(quantity),0) as IQty')
        //     ->selectRaw('0 as BalanceQty')
        //     // ->selectRaw('Date(expire_date) as expire_date')
        //     ->selectRaw('"Issue" as type')
        //     ->selectRaw('created_at')
        //     ->where('is_deleted', 0)
        //     ->groupBy(['unit_cost', 'type', 'created_at', 'batch_number'])->get();

        // $salesDetailList = SalesDetail::query()
        //     ->where(['item_id' => $itemID, 'unit_id' => $unitID])
        //     ->selectRaw('IFNULL(unit_cost,0) as unit_cost')
        //     ->selectRaw('batch_number')
        //     ->selectRaw('0 as RQty')
        //     ->selectRaw('IFNULL(SUM(quantity),0) as IQty')
        //     ->selectRaw('0 as BalanceQty')
        //     // ->selectRaw('Date(expire_date) as expire_date')
        //     ->selectRaw('"Sale" as type')
        //     ->selectRaw('created_at')
        //     ->groupBy(['unit_cost', 'type', 'created_at', 'batch_number'])->get();

        // $B_Qty = 0;
        // $list = array_merge($receiveDetailList->toArray(), $purchaseDetailList->toArray(), $issueDetailList->toArray(), $salesDetailList->toArray());

        // $result = [];
        // for ($i = 0; $i < count($list); ++$i) {

        //     $filtered_array = collect($list)->where('batch_number', '=', $list[$i]['batch_number'])->where('type', '=', $list[$i]['type'])->where('created_at', '=', $list[$i]['created_at'])->all();

        //     $R_Qty = collect($filtered_array)->sum('RQty');
        //     $I_Qty = collect($filtered_array)->sum('IQty');


        //     if ($list[$i]['type'] == "Receive") {
        //         $expireDate = StockReceiveDetail::select('*')->where('unit_id', $unitID)->where('item_id', $itemID)->where('batch_number', $list[$i]['batch_number'])->get();
        //         $expireDate = $expireDate[0]['expire_date'];
        //     } else if ($list[$i]['type'] == "Purchase") {
        //         $expireDate = PurchaseDetail::select('*')->where('unit_id', $unitID)->where('item_id', $itemID)->where('batch_number', $list[$i]['batch_number'])->get();
        //         $expireDate = $expireDate[0]['expire_date'];
        //     } else if ($list[$i]['type'] == "Issue") {
        //         $expireDate = StockIssueDetail::select('*')->where('unit_id', $unitID)->where('item_id', $itemID)->where('batch_number', $list[$i]['batch_number'])->get();
        //         $expireDate = $expireDate[0]['expire_date'];
        //     } else if ($list[$i]['type'] == "Sale") {
        //         $expireDate = SalesDetail::select('*')->where('unit_id', $unitID)->where('item_id', $itemID)->where('batch_number', $list[$i]['batch_number'])->get();
        //         $expireDate = $expireDate[0]['expire_date'];
        //     }

        //     $detail = (object) [
        //         "unit_cost" => $list[$i]['unit_cost'],
        //         "batch_number" => $list[$i]['batch_number'],
        //         "RQty" => $R_Qty,
        //         "IQty" => $I_Qty,
        //         "BalanceQty" => $R_Qty - $I_Qty,
        //         "type" => $list[$i]['type'],
        //         "created_at" => $list[$i]['created_at'],
        //         "expire_date" => $expireDate
        //     ];
        //     array_push($result, $detail);

        //     // $result = collect($result)->where('BalanceQty', '>', '0')->all();
        // }

        // $result = collect($result)->sortBy('created_at')->values()->all();

        // $B_Qty = collect($result)->sum('BalanceQty');
        // return [$B_Qty, $result];
    }

    // public function checkIssueDetailValidation(Request $request)
    // {

    //     try {
    //         $validator = Validator::make($request->all(), [
    //             'itemID' => 'gt:0',
    //             'quantity' => 'required|numeric|gt:0'
    //         ], [
    //             'itemID.gt' => 'Item Name ရွေးရန်လိုအပ်ပါသည်',
    //             'quantity.required' => 'Quantity ဖြည့်ရန်လိုအပ်ပါသည်',
    //             'quantity.numeric' => "Quantity သည် Number ဖြစ်ရပါမည်",
    //             'quantity.gt' => 'Quantity ဖြည့်ရန်လိုအပ်ပါသည်',
    //         ]);
    //         if ($validator->passes()) {
    //             $balanceList = $this->getStockBalance($request->itemID, $request->unitID);
    //             $BQty = $balanceList[0];
    //             $list = $balanceList[1];
    //             return response()->json(['success' => [$BQty, $list]]);
    //             // return response()->json(['success'=> [ $receiveDetailList]]);
    //         } else {
    //             return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
    //         }
    //     } catch (\Exception $e) {
    //         return response()->json(['errors' => $e->getMessage()]);
    //     }
    // }
    public function checkIssueDetailValidation(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'itemID' => 'required|gt:0',
                'quantity' => 'required|numeric|gt:0'
            ], [
                'itemID.required' => 'Item Name ရွေးရန်လိုအပ်ပါသည်',
                'itemID.gt' => 'Item Name ရွေးရန်လိုအပ်ပါသည်',
                'quantity.required' => 'Quantity ဖြည့်ရန်လိုအပ်ပါသည်',
                'quantity.numeric' => "Quantity သည် Number ဖြစ်ရပါမည်",
                'quantity.gt' => 'Quantity ဖြည့်ရန်လိုအပ်ပါသည်',
            ]);
            
            if ($validator->passes()) {
                $balanceList = $this->getStockBalance($request->itemID, $request->unitID);
                $BQty = $balanceList[0];
                $list = $balanceList[1];
                return response()->json(['success' => [$BQty, $list]]);
            } else {
                return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
            }
        } catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }
    }



    //direct stock issue list page
    public function issueListPage(Request $req)
    {
        $stockIssueList = StockIssue::query()->where(['stock_issues.is_delete' => 0])
            ->select('stock_issues.*', 'S.issue_type_name_1 as issue_type')
            ->join('stock_issue_types as S', 'S.issue_type_id', '=', 'stock_issues.issue_type_id')
            ->when($req->has('issueDate'), function($query) use ($req) {
                $query->whereDate('stock_issues.issue_date', $req->issueDate);
            }, function($query) {
                // $query->orderBy('stock_issues.stock_issue_id', 'DESC');
                $query->whereDate('stock_issues.issue_date', '>=', now()->subDays(30))
                  ->orderBy('stock_issues.stock_issue_id', 'DESC');
            })
            ->get();

        return view('admin.stock_control.stock_issue.issue_list', compact('stockIssueList'));
    }

    public function createStockIssue(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'issue_type' => 'gt:0',
        ], [
            'issue_type.gt' => 'Issue Type ရွေးရန်လိုအပ်ပါသည်!',
        ]);

        if ($validator->passes()) {
            try {
                DB::beginTransaction();
                $stockIssue_detaillist = $request->issue_detailList;
                $voucherNo = "SIV-" . date('y') . "-" . StockIssue::whereYear('issue_date', date('Y'))->count() + 1;
                $data = $this->addStockIssueMasterData($request, $voucherNo);
                $result = StockIssue::create($data);

                foreach ($stockIssue_detaillist as $detail) {
                    $balance_result = $this->getStockBalance($detail['itemID'], $detail['unitID']);
                    $balanceList = $balance_result[1];
                    if ((int)$balance_result[0] < (int)($detail['quantity'])) {
                        $validator->errors()->add(
                            'storeQty',
                            'Not enough quantity (' . $detail['item_name'] . ')!'
                        );
                        DB::rollBack();
                        return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
                    } else {

                        // return response()->json(['errors' => $balance_result[0]]);
                        $issue_Qty = (int)($detail['quantity']);
                        foreach ($balanceList as $item) {
                            $balanceQty = $item->receiveQty + $item->purchaseQty;

                            if (floatval($balanceQty) >= floatval($issue_Qty)) {
                                $detail['quantity'] = $issue_Qty;
                                $detail['expire_date'] = $item->expire_date;
                                $detail_data = $this->addStockIssueDetailData($detail, $result->id);
                                StockIssueDetail::create($detail_data);
                                $issue_Qty = 0;
                                break;
                            } else if (floatval($balanceQty) < floatval($issue_Qty)) {
                                $detail['quantity'] =  $balanceQty;
                                $detail['expire_date'] = $item->expire_date;
                                $issue_Qty = $issue_Qty - (int)($balanceQty);
                                $detail_data = $this->addStockIssueDetailData($detail, $result->id);
                                StockIssueDetail::create($detail_data);
                            }
                        };
                    }
                }
                DB::commit();
                return response()->json(['success' => "Save Successful!"]);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['errors' => $e->getMessage()]);
            }
        } else {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
        }
    }
    private function addStockIssueMasterData($request, $voucherNo)
    {
        $data = [
            'issue_date' => $request->issue_date,
            'issue_voucher_number' => $voucherNo,
            'issue_type_id' => $request->issue_type,
            'remark' => isset($request->remark) ? $request->remark : '',
            'total_qty' => $request->totalQty,
            'is_updated' => false,
            'is_delete' => 0,
            'modified_by' => $request->loginUserID,
        ];
        return $data;
    }
    private function addStockIssueDetailData($detail, $stockIssueID)
    {
        $data = [
            'stock_issue_id' => $stockIssueID,
            'location_id' => 1,
            'item_id' => $detail['itemID'],
            'unit_id' => $detail['unitID'],
            'batch_number' => $detail['batch_number'],
            'quantity' => $detail['quantity'],
            'expire_date' => $detail['expire_date'],
            'issue_type' => $detail['issue_type'],
            'is_deleted' => 0,
            'is_updated' => false,
        ];
        return $data;
    }

    public function updateStockIssuePage($issueID)
    {
        $itemList = MenuItem::query()->where(['menu_items.is_discontinued' => 0, 'menu_items.is_deleted' => 0])
            ->where('I.item_type_id', "!=", 1)
            ->select('menu_items.*', 'U.unit_name')
            ->join('units as U', 'U.unit_id', '=', 'menu_items.unit_id')
            ->join('item_types as I', 'I.item_type_id', '=', 'menu_items.item_type_id')
            ->join('item_selling_prices as SP', 'SP.item_id', '=', 'menu_items.item_id')
            ->get();
        // dd($itemList->toArray());
        $stockIssueTypeList = StockIssueType::query()->where(['is_discontinued' => 0])->get();

        $selectedStockIssue = StockIssue::query()->where(['is_delete' => 0, 'stock_issue_id' => $issueID])->get();

        $selectedStockIssuesDetailList = StockIssueDetail::query()->where(['stock_issue_id' => $issueID])
            ->select('stock_issue_details.item_id as itemID', 'I.item_name', 'I.item_code', 'I.bar_code as barcode', 'stock_issue_details.unit_id as unitID', 'batch_number', 'U.unit_name', 'quantity', 'issue_type', 'expire_date', DB::raw('true as is_update'))
            ->join('menu_items as I', 'I.item_id', '=', 'stock_issue_details.item_id')
            ->join('units as U', 'U.unit_id', '=', 'stock_issue_details.unit_id')
            ->get();
        // dd($selectedStockIssuesDetailList->toArray());
        return view('admin.stock_control.stock_issue.update_issue', compact('itemList', 'stockIssueTypeList', 'selectedStockIssue', 'selectedStockIssuesDetailList'));
    }


    public function updateStockIssue(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'issue_type' => 'gt:0',
        ], [
            'issue_type.gt' => 'You need to select issue type!',
        ]);

        if ($validator->passes()) {
            try {
                DB::beginTransaction();
                $issueID = $request->issueID;
                $detailList = $request->issue_detailList;
                $master_data = $this->addStockIssueMasterData($request, $request->voucher_no);
                StockIssue::where('stock_issue_id', '=', $issueID)->update($master_data);
                StockIssueDetail::where('stock_issue_id', '=', $issueID)->delete();

                foreach ($detailList as $detail) {
                    $balance_result = $this->getStockBalance($detail['itemID'], $detail['unitID']);
                    $balanceList = $balance_result[1];
                    // dd($balance_result[0], $detail['quantity']);
                    // return response()->json(['errors' => (int)($detail['quantity'])]);
                    if ((int)$balance_result[0] < (int)($detail['quantity'])) {
                        $validator->errors()->add(
                            'storeQty',
                            'Not enough quantity (' . $detail['item_name'] . ')!'
                        );
                        DB::rollBack();
                        return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
                    } else {
                        $issue_Qty = (int)($detail['quantity']);
                        foreach ($balanceList as $item) {
                            $balanceQty = $item->receiveQty + $item->purchaseQty;
                            if (floatval($balanceQty) >= floatval($issue_Qty)) {
                                $detail['quantity'] = $issue_Qty;
                                $detail['expire_date'] = $item->expire_date;
                                $detail_data = $this->addStockIssueDetailData($detail, $issueID);
                                StockIssueDetail::create($detail_data);
                                $issue_Qty = 0;
                                break;
                            } else if (floatval($balanceQty) < floatval($issue_Qty)) {
                                $detail['quantity'] =  $balanceQty;
                                $detail['expire_date'] = $item->expire_date;
                                $issue_Qty = $issue_Qty - (int)($balanceQty);
                                $detail_data = $this->addStockIssueDetailData($detail, $issueID);
                                StockIssueDetail::create($detail_data);
                            }
                        };
                    }
                }
                DB::commit();
                return response()->json(['success' => "Update successful!"]);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['errors' => $e->getMessage()]);
            }
        } else {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
        }
    }

    public function deleteStoreIssue(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'delete_reason' => 'required'
        ], [
            'delete_reason' => 'Delete Reason ဖြည့်ရန်လိုအပ်ပါသည်!'
        ]);
        if ($validator->passes()) {
            try {
                DB::beginTransaction();
                $issueID = $request->issue_deleteID;
                StockIssue::where('stock_issue_id', $issueID)->update([
                    'is_delete' => true,
                    'delete_reason' => $request->delete_reason,
                    'modified_by' => $request->loginUserID,
                ]);
                StockIssueDetail::where('stock_issue_id', $issueID)->update([
                    'is_deleted' => true
                ]);
                DB::commit();
                return response()->json(['success' => "Delete Successful!"]);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['errors' => $e->getMessage()]);
            }
        } else {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
        }
    }

    //Print Report
    public function issueListReportPage()
    {
        $stockIssueList = StockIssue::query()->where(['stock_issues.is_delete' => 0])
            ->select('stock_issues.*', 'S.issue_type_name_1 as issue_type')
            ->join('stock_issue_types as S', 'S.issue_type_id', '=', 'stock_issues.issue_type_id')
            ->get();
        return view('admin.report.issueListReport', compact('stockIssueList'));
    }

    public function prnpriview()
    {
        $printStockIssueList = StockIssue::query()->where(['stock_issues.is_delete' => 0])
            ->select('stock_issues.*', 'S.issue_type_name_1 as issue_type')
            ->join('stock_issue_types as S', 'S.issue_type_id', '=', 'stock_issues.issue_type_id')
            ->get();
        return view('admin.report.issueListReportPrint', compact('printStockIssueList'));
    }

    public function issueDetailsPage($id, Request $request)
    {
        $issueDate = $request->query('issueDate');
    
        $itemList = MenuItem::query()->where(['menu_items.is_discontinued' => 0, 'menu_items.is_deleted' => 0])
            ->where('I.item_type_id', "!=", 1)
            ->select('menu_items.*', 'U.unit_name')
            ->join('units as U', 'U.unit_id', '=', 'menu_items.unit_id')
            ->join('item_types as I', 'I.item_type_id', '=', 'menu_items.item_type_id')
            ->join('item_selling_prices as SP', 'SP.item_id', '=', 'menu_items.item_id')
            ->get();
        // dd($itemList->toArray());
        $stockIssueTypeList = StockIssueType::query()->where(['is_discontinued' => 0])->get();

        $selectedStockIssue = StockIssue::query()->where(['is_delete' => 0, 'stock_issue_id' => $id])->get();

        $selectedStockIssuesDetailList = StockIssueDetail::query()->where(['stock_issue_id' => $id])
            ->select('stock_issue_details.item_id as itemID', 'I.item_name', 'I.item_code', 'I.bar_code as barcode', 'stock_issue_details.unit_id as unitID', 'batch_number', 'U.unit_name', 'quantity', 'issue_type', 'expire_date', DB::raw('true as is_update'))
            ->join('menu_items as I', 'I.item_id', '=', 'stock_issue_details.item_id')
            ->join('units as U', 'U.unit_id', '=', 'stock_issue_details.unit_id')
            ->get();
        
        $issueTypeName = $stockIssueTypeList->firstWhere('issue_type_id', $selectedStockIssue[0]['issue_type_id'])->issue_type_name_1 ?? '';
        // dd($selectedStockIssue->toArray());
        // dd($itemList->toArray(), $stockIssueTypeList->toArray(), $selectedStockIssue->toArray() ,$selectedStockIssuesDetailList->toArray());
        return view('admin.stock_control.stock_issue.issue_details', compact('itemList', 'stockIssueTypeList', 'selectedStockIssue', 'selectedStockIssuesDetailList', 'issueTypeName', 'issueDate'));
    }

}
