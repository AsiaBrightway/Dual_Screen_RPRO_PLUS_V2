<?php

namespace App\Http\Controllers;

use App\Models\ItemSellingPrice;
use Exception;
use App\Models\Unit;
use App\Models\ItemType;
use App\Models\MenuItem;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\SalesDetail;
use App\Models\MainCategory;
use Illuminate\Http\Request;
use App\Models\PurchaseDetail;
use App\Models\SupplierLedger;
use App\Models\PurchasePaymentLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PurchaseController extends Controller
{
    //direct purchase page
    public function purchasePage()
    {
        $itemList = MenuItem::query()->where(['menu_items.is_discontinued' => 0, 'menu_items.is_deleted' => 0])
            ->where('I.item_type_id', '!=', 1)
            ->select('menu_items.*', 'U.unit_name')
            ->join('units as U', 'U.unit_id', '=', 'menu_items.unit_id')
            ->join('item_types as I', 'I.item_type_id', '=', 'menu_items.item_type_id')
            ->get();
        $supplierList = Supplier::query()->where('is_discontinued', '0')->where('is_deleted', '0')->get();
        // Purchase::where('active', 1)->max('price');
        // $voucher_count = ['year' => date('y'),'count' => Purchase::whereYear('purchase_date', date('Y'))->count()+1];
        $voucherNo = "P-" . date('y') . "-" . Purchase::whereYear('purchase_date', date('Y'))->count() + 1;

        $mainCategories = MainCategory::where('is_deleted', 0)->get()->toArray();
        $subCategories = [];
        $itemTypes = ItemType::get()->toArray();
        $units  = Unit::where('is_discontinued', 0)
            ->get()->toArray();

        return view('admin.stock_control.stock_purchase.purchase', compact('itemList', 'supplierList', 'voucherNo', 'mainCategories', 'subCategories', 'itemTypes', 'units'));
    }

    //direct purchase list page
    public function purchaseListPage(Request $req)
    {
        // $purchaseList = Purchase::query()->where(['purchases.is_cancel' => null])
        // ->select('purchases.*', 'S.supplier_name')
        // ->selectRaw("SUM(paid_amount) as paid_amount")
        // ->join('suppliers as S', 'S.supplier_id', '=', 'purchases.supplier_id')
        // ->join('supplier_ledgers as L', 'L.purchase_id', '=', 'purchases.purchase_id')
        // ->groupBy(['purcahse_id', 'purchase_date', 'discount_date', 'purchase_voucher_number', 'supplier_id', 'store_id', 'currency_id', 'exchange_rate', 'total_amount', 'transport_charges', 'tax', 'other_charges', 'total_discount', 'remark', 'is_cancel', 'cancel_by', 'cancel_date', 'cancel_reason', 'location_id', 'is_updated', 'modified_by', 'created_at', 'updated_at','supplier_name'])
        // ->get();

        $purchaseList = Purchase::query()->where(['purchases.is_delete' => 0])
            ->select('purchases.*', 'S.supplier_name')
            ->selectRaw('IFNULL(SUM(PL.voucher_discount),0)+purchases.total_item_discount as discount_amount')
            ->selectRaw('IFNULL(SUM(PL.transport_charges), 0) as transport_charges')
            ->selectRaw('IFNULL(SUM(PL.other_charges), 0) as other_charges')
            ->selectRaw('IFNULL(SUM(PL.tax), 0) as tax')
            ->selectRaw('IFNULL(SUM(PL.paid_amount), 0) as paid_amount') // ->selectRaw('COALESCE(SUM(PL.paid_amount), 0) as paid_amount')
            ->join('suppliers as S', 'S.supplier_id', '=', 'purchases.supplier_id')
            ->leftJoin('purchase_payment_logs as PL', 'PL.purchase_id', '=', 'purchases.purchase_id')
            ->groupBy(['purchase_id', 'purchase_voucher_number', 'supplier_id', 'purchase_date', 'due_date', 'remark', 'total_amount', 'total_item_discount', 'is_delete', 'delete_reason', 'is_updated', 'modified_by', 'created_at', 'updated_at', 'supplier_name'])
            ->when($req->has('dailyPurchaseDate'), function ($query) use ($req) {
                $query->whereDate('purchases.purchase_date', $req->dailyPurchaseDate);
            }, function ($query) {
                $query->orderBy('purchases.purchase_id', 'DESC')->limit(10);
            })
            ->get();

        return view('admin.stock_control.stock_purchase.purchase_list', compact('purchaseList'));
    }

    public function purchaseOrderDetails($purchase_id)
    {
        // Get purchase master info
        $purchase = Purchase::select('purchases.*', 'S.supplier_name')
            ->join('suppliers as S', 'purchases.supplier_id', '=', 'S.supplier_id')
            ->where('purchases.purchase_id', $purchase_id)
            ->first();

        // Safety check
        if (!$purchase) {
            abort(404, 'Purchase not found');
        }

        $purchaseID = $purchase->purchase_id;
        $purchaseVoucherNumber = $purchase->purchase_voucher_number;
        $supplierID = $purchase->supplier_id;

        // Supplier info
        $supplier = Supplier::where('supplier_id', $supplierID)->first();

        // Purchase detail items
        $purchaseDetails = PurchaseDetail::where('purchase_details.purchase_id', $purchaseID)
            ->join('menu_items as items', 'purchase_details.item_id', '=', 'items.item_id')
            ->join('units', 'purchase_details.unit_id', '=', 'units.unit_id')
            ->select(
                'purchase_details.*',
                'items.item_name',
                'units.unit_name'
            )
            ->get()
            ->toArray();

        return view(
            'admin.store.purchase.purchase_order_details',
            compact(
                'purchase',
                'purchaseDetails',
                'purchaseVoucherNumber',
                'supplier'
            )
        );
    }

    //Create Item
    public function createPurchaseItem(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'create_main_category' => 'required|not_in:0',
            'create_sub_category' => 'required|not_in:0',
            'create_item_code' => 'required|unique:menu_items,item_code',
            // 'create_bar_code' => 'required|unique:menu_items,bar_code',
            'create_item_name' => 'required',
            'create_item_image' => 'mimes:jpg,jpeg,png',
            'create_unit_cost' => 'required|numeric',
            'create_item_selling_price' => [
                'required',
                'numeric',
                'gt:' . $req->create_unit_cost,
            ]
        ], [
            'create_main_category.required' => 'Main Category ရွေးရန်လိုအပ်ပါသည်',
            'create_main_category.not_in' => 'Main Category ရွေးရန်လိုအပ်ပါသည်',
            'create_sub_category.required' => 'Sub Category ရွေးရန်လိုအပ်ပါသည်',
            'create_item_code.required' => 'Item Code ဖြည့်ရန်လိုအပ်ပါသည်',
            'create_item_code.unique' => 'Item Code တူနေပါသည်',
            // 'create_bar_code.required' => 'Bar Code ဖြည့်ရန်လိုအပ်ပါသည်',
            // 'create_bar_code.unique' => 'Bar Code တူနေပါသည်',
            'create_item_name.required' => 'Item Name ဖြည့်ရန်လိုအပ်ပါသည်',
            'create_item_image.mimes' => 'Image သည် JPG, JPEG, PNG Format သာဖြစ်ရပါမည်',
            'create_item_selling_price.required' => 'Selling Price ဖြည့်ရန်လိုအပ်ပါသည်',
            'create_item_selling_price.numeric' => 'Selling Price သည် Number ဖြစ်ရပါမည်',
            'create_unit_cost.required' => 'Unit Cost ဖြည့်ရန်လိုအပ်ပါသည်',
            'create_item_selling_price.gt' => 'Selling Price သည် Unit Cost ထက်များရမည်',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $this->addItemData($req);

        if ($data['is_discontinued'] == null || $data['is_discontinued'] == "null") {
            $data['is_discontinued'] = 0;
        }
        if ($data['is_discontinued'] == "on") {
            $data['is_discontinued'] = 1;
        }

        if ($req->hasFile('create_item_image')) {
            $fileName = uniqid() . '_' . $req->file('create_item_image')->getClientOriginalName();
            $req->file('create_item_image')->storeAs('public/Images/', $fileName);
            $data['item_image'] = $fileName;
        }

        try {
            DB::beginTransaction();
            $menuItem = MenuItem::create($data);

            try {
                $sellingPriceData = $this->addPriceControlData($req);
                $sellingPriceData['item_id'] = $menuItem->id;
                ItemSellingPrice::create($sellingPriceData);
            } catch (\Exception $e) {
                Log::error('ItemSellingPrice insert failed: ' . $e->getMessage());
            }

            DB::commit();
            return response()->json(['success' => 'Item created successfully!']);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['errors' => ['general' => $e->getMessage()]], 500);
        }
    }

    // Add Price Control Data
    private function addPriceControlData($req)
    {
        $data = [
            'unit_id' => $req->create_item_unit,
            'currency_id' => 1,
            'unit_cost' => $req->create_unit_cost,
            'item_selling_price' => $req->create_item_selling_price,
            'is_updated' => "0",
            'modified_by' => Auth::user()->id,
        ];
        return $data;
    }

    public function updatePurchasePage($purchaseID)
    {
        $itemList = MenuItem::query()->where(['menu_items.is_discontinued' => 0, 'menu_items.is_deleted' => 0]) // ->orwhere('menu_items.is_discontinued','0')->where('menu_items.is_deleted','0')
            ->whereIn('I.item_type_id', [2, 3])
            ->select('menu_items.*', 'U.unit_name')
            ->join('units as U', 'U.unit_id', '=', 'menu_items.unit_id')
            ->join('item_types as I', 'I.item_type_id', '=', 'menu_items.item_type_id')
            ->get();
        $supplierList = Supplier::query()->where('is_discontinued', '0')->where('is_deleted', '0')->get();

        $selectedPurchase = Purchase::query()->where(['is_delete' => 0, 'purchase_id' => $purchaseID])->get();

        $selectedPurchaseDetailList = PurchaseDetail::query()->where(['purchase_id' => $purchaseID])
            ->select('purchase_details.item_id as itemID', 'I.item_name', 'I.item_code', 'I.bar_code as barcode', 'purchase_details.unit_id', 'U.unit_name', 'purchase_details.quantity as qty', 'purchase_details.unit_cost',  'purchase_details.discount_amount as discount', 'purchase_details.is_foc as foc', 'purchase_details.expire_date')
            ->selectRaw('purchase_details.quantity*purchase_details.unit_cost as amount')
            ->selectRaw('purchase_details.quantity*purchase_details.unit_cost-purchase_details.discount_amount as net_amount')
            ->join('menu_items as I', 'I.item_id', '=', 'purchase_details.item_id')
            ->join('units as U', 'U.unit_id', '=', 'purchase_details.unit_id')
            ->get();
        // $paidList = SupplierLedger::query()->where(['purchase_id' =>$purchaseID])->where('paid_amount','>',0)
        //                     ->select('supplier_ledger_id','payment_date', 'paid_amount')
        //                     ->get();
        return view('admin.stock_control.stock_purchase.update_purchase', compact('itemList', 'supplierList', 'selectedPurchase', 'selectedPurchaseDetailList'));
        // return redirect()->route('stockControl#stock_purchase#updatePurchasePage',compact('itemList','supplierList','selectedPurchase','selectedPurchaseDetailList'))->with( [ 'id' => $purchaseID] );
    }

    public function checkPurchaseDetailValidation(Request $request)
    {
        $qty = $request->store_qty + $request->qty;

        // Add the calculated qty to the request data for validation
        $request->merge(['calculated_qty' => $qty]);

        $validator = Validator::make($request->all(), [
            'itemID' => 'gt:0',
            'calculated_qty' => 'required|numeric|min:0',
            'unit_cost' => 'required|numeric',
            'discount' => 'numeric'
        ], [
            'itemID.gt' => 'Item Name ရွေးရန်လိုအပ်ပါသည်',
            'calculated_qty.required' => 'Quantity ဖြည့်ရန်လိုအပ်ပါသည်',
            'calculated_qty.numeric' => "Quantity သည် Number ဖြစ်ရပါမည်",
            'calculated_qty.min' => 'Purchase Qty သည် Store Qty ကို ပြန်ဖြည့်ရန် မလုံလောက်ပါ။',
            'unit_cost.required' => 'Unit Cost ဖြည့်ရန်လိုအပ်ပါသည်',
            'unit_cost.numeric' => "Unit Cost သည် Number ဖြစ်ရပါမည်",
            'discount.numeric' => "Discount သည် Number ဖြစ်ရပါမည်"
        ]);
        if ($validator->passes()) {
            return response()->json(['success' => 'Added successful']);
        } else {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
        }
    }

    public function updateCheckPurchaseDetailValidation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'itemID' => 'gt:0',
            'qty' => 'required|numeric',
            'unit_cost' => 'required|numeric',
            'discount' => 'numeric'
        ], [
            'itemID.gt' => 'Item Name ရွေးရန်လိုအပ်ပါသည်',
            'qty.required' => 'Quantity ဖြည့်ရန်လိုအပ်ပါသည်',
            'unit_cost.required' => 'Unit Cost ဖြည့်ရန်လိုအပ်ပါသည်',
            'qty.numeric' => "Quantity သည် Number ဖြစ်ရပါမည်",
            'unit_cost.numeric' => "Unit Cost သည် Number ဖြစ်ရပါမည်",
            'discount.numeric' => "Discount သည် Number ဖြစ်ရပါမည်"
        ]);
        if ($validator->passes()) {
            return response()->json(['success' => 'Added successful']);
        } else {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
        }
    }

    public function savePurchase(Request $request)
    {
        // dd($request);
        $validator = Validator::make($request->all(), [
            'supplier_name' => 'gt:0',
            // 'purchase_detaillist' => 'required|array|min:1'
        ], [
            'supplier_name.gt' => 'Supplier Name ရွေးရန်လိုအပ်ပါသည်',
            // 'purchase_detaillist' =>'You need to set item detail!'
        ]);
        if ($validator->passes()) {
            try {
                DB::beginTransaction();
                $purchase_detaillist = $request->purchase_detailList;

                $voucherNo = "P-" . date('y') . "-" . Purchase::whereYear('purchase_date', date('Y'))->count() + 1;
                $data = $this->addPurchaseMasterData($request, $voucherNo);

                $result = Purchase::create($data);

                foreach ($purchase_detaillist as $detail) {
                    $detail_data = $this->addPurchaseDetailData($detail, $result->id);
                    $purchaseDetail = PurchaseDetail::create($detail_data);

                    if ((int)$detail['store_qty'] < 0) {
                        $data = [
                            'batch_number' => $purchaseDetail->batch_number,
                            'expire_date' => $purchaseDetail->expire_date,
                            'sale_type' => "Purchase"
                        ];
                        SalesDetail::where('item_id', $purchaseDetail->item_id)
                            ->where('batch_number', 0)
                            ->update($data);
                    }
                }
                DB::commit();
                // return response()->json(['success'=>$purchase_detaillist]);
                return response()->json(['success' => "Save Successful!"]);
            } catch (\Exception $e) {
                DB::rollBack();

                return response()->json(['errors' => $e->getMessage()]);
            }
        } else {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
        }
    }

    public function updatePurchase(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'supplier_name' => 'gt:0',
        ], [
            'supplier_name.gt' => 'Supplier Name ရွေးရန်လိုအပ်ပါသည်',
        ]);
        if ($validator->passes()) {
            try {
                DB::beginTransaction();
                $purchase_detaillist = $request->purchase_detailList;
                $data = $this->addPurchaseMasterData($request, $request->voucher_no);
                $data['is_updated'] = true;
                Purchase::where('purchase_id', $request->purchaseID)->update($data);

                PurchaseDetail::where('purchase_id',  $request->purchaseID)->delete(); //Delete old detail by purchaseID
                foreach ($purchase_detaillist as $detail) {
                    $detail_data = $this->addPurchaseDetailData($detail, $request->purchaseID);
                    $detail_data['is_updated'] = true;
                    PurchaseDetail::create($detail_data);
                }

                // $total = PurchasePaymentLog::where('purchase_id','=',$request->purchaseID)->pluck('total_amount');
                $total = PurchasePaymentLog::where('purchase_id', '=', $request->purchaseID)->select('total_amount')->skip(0)->take(1)->get();
                if (count($total) > 0) {
                    if ($total[0]->total_amount > $request->totalAmount) {
                        $sub_amount = $total[0]->total_amount - $request->totalAmount;
                        $sub_net = $total[0]->total_amount - ($request->totalAmount - $request->totalDiscount);
                        PurchasePaymentLog::where('purchase_payment_log_id', $total[0]->purchase_payment_log_id)->update([
                            'total_amount' =>  DB::raw('total_amount - ' . $sub_amount),
                            'net_amount' =>  DB::raw('net_amount - ' . $sub_net),
                            'balance' => DB::raw('balance - ' . $sub_amount),
                        ]);
                        PurchasePaymentLog::where('purchase_id', $request->purchaseID)->skip(1)->update([
                            'total_amount' =>  DB::raw('total_amount - ' . $sub_amount),
                            'net_amount' =>  DB::raw('net_amount - ' . $sub_amount),
                            'balance' => DB::raw('balance - ' . $sub_amount),
                        ]);
                    } else if ($total[0]->total_amount < $request->totalAmount) {
                        $add_amount = $request->totalAmount - $total[0]->total_amount;
                        $add_net = ($request->totalAmount -  $request->totalDiscount) - $total[0]->total_amount;
                        PurchasePaymentLog::where('purchase_payment_log_id', $total[0]->purchase_payment_log_id)
                            ->update([
                                'total_amount' =>  DB::raw('total_amount + ' . $add_amount),
                                'net_amount' =>  DB::raw('net_amount + ' . $add_net),
                                'balance' => DB::raw('balance + ' . $add_amount),
                            ]);
                        PurchasePaymentLog::where('purchase_id', $request->purchaseID)->skip(1)
                            ->update([
                                'total_amount' =>  DB::raw('total_amount + ' . $add_amount),
                                'net_amount' =>  DB::raw('net_amount + ' . $add_amount),
                                'balance' => DB::raw('balance + ' . $add_amount),
                            ]);
                    }
                }
                DB::commit();
                // return response()->json(['success'=>$tot]);
                return response()->json(['success' => "Update Successful!"]);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['errors' => $e->getMessage()]);
            }
        } else {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
        }
    }

    private function addPurchaseMasterData($request, $voucherNo)
    {
        $data = [
            'purchase_voucher_number' => $voucherNo,
            'supplier_id' => $request->supplier_name,
            'purchase_date' => $request->purchase_date,
            'due_date' => $request->due_date,
            'remark' => $request->remark,
            'total_amount' => $request->totalAmount,
            'total_item_discount' => $request->totalDiscount,
            'is_updated' => false,
            'is_delete' => false,
            'modified_by' =>  $request->loginUserID
        ];
        return $data;
    }

    private function addPurchaseDetailData($detail, $purchaseID)
    {
        $data = [
            'purchase_id' => $purchaseID,
            'item_id' => $detail['itemID'],
            'unit_id' => $detail['unit_id'],
            'batch_number' => $purchaseID,
            'quantity' => $detail['qty'],
            'unit_cost' => $detail['unit_cost'],
            'discount_amount' => isset($detail['discount']) ? $detail['discount'] : 0,
            'expire_date' => $detail['expire_date'],
            'is_foc' => filter_var($detail['foc'], FILTER_VALIDATE_BOOLEAN),
            'is_updated' => false,
            'is_deleted' => false,
        ];
        return $data;
    }

    public function deleteSelectedPurchase(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'delete_reason' => 'required'
        ], [
            'delete_reason' => 'Delete Reason ဖြည့်ရန်လိုအပ်ပါသည်'
        ]);
        if ($validator->passes()) {
            try {
                DB::beginTransaction();
                $purchaseID = $request->purchase_deleteID;

                Purchase::where('purchase_id', $purchaseID)->update([
                    'is_delete' => 1,
                    'delete_reason' => $request->delete_reason,
                    'modified_by' => $request->loginUserID,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

                PurchaseDetail::where('purchase_id', $purchaseID)->update([
                    'is_deleted' => 1,
                    'updated_at' => date('Y-m-d H:i:s')
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

    public function GetPaidLog(Request $request)
    {
        try {
            $total = PurchasePaymentLog::where('purchase_id', '=', $request->purchaseID)->skip(0)->take(1)->get();
            if (count($total) == 0) {
                $total = Purchase::where('purchase_id', $request->purchaseID)
                    ->select('purchases.*', 'S.supplier_name')
                    ->selectRaw('total_amount-total_item_discount as balance')
                    ->join('suppliers as S', 'S.supplier_id', '=', 'purchases.supplier_id')->get();
            } else {
                $total = PurchasePaymentLog::where('purchase_payment_logs.purchase_id', '=', $request->purchaseID)
                    ->select('purchase_payment_logs.*', 'P.purchase_voucher_number', 'P.due_date', 'S.supplier_name') //'P.total_item_discount' IFNULL
                    ->selectRaw('0 as total_item_discount')
                    ->join('purchases as P', 'P.purchase_id', '=', 'purchase_payment_logs.purchase_id')
                    ->join('suppliers as S', 'S.supplier_id', '=', 'P.supplier_id')
                    ->orderByDesc('purchase_payment_log_id')->skip(0)->take(1)->get();
            }
            return response()->json(['success' => $total]);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }
    }

    public function createPaymentLog(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'pay_amount' => 'required|numeric|gt:0',
            'voucher_discount' => 'numeric',
            'tax' => 'numeric',
            'transport_charges' => 'numeric',
            'other_charges' => 'numeric'
        ], [
            'pay_amount.required' => 'Paid Amount ဖြည့်ရန်လိုအပ်ပါသည်',
            'pay_amount.numeric' =>  'Paid Amount သည် Number ဖြစ်ရပါမည်',
            'pay_amount.gt' => 'Paid Amount ဖြည့်ရန်လိုအပ်ပါသည်',
            'voucher_discount' =>  'Voucher Discount သည် Number ဖြစ်ရပါမည်',
            'tax' =>  'Tax သည် Number ဖြစ်ရပါမည်',
            'transport_charges' => 'Transport Charges သည် Number ဖြစ်ရပါမည်',
            'other_charges' =>  'Other Charges သည် Number ဖြစ်ရပါမည်',
        ]);
        if ($validator->passes()) {
            try {
                $data = $this->addPaymentLog($request);
                PurchasePaymentLog::create($data);
                return response()->json(['success' => "Save successful!"]);
            } catch (\Exception $e) {
                return response()->json(['errors' => $e->getMessage()]);
            }
        } else {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
        }
    }

    private function validationCheckPaymentLog(Request $request)
    {
        $validationRules = [
            'pay_amount' => 'required|numeric|gt:0',
            'voucher_discount' => 'numeric',
            'tax' => 'numeric',
            'transport_charges' => 'numeric',
            'other_charges' => 'numeric'
        ];

        $validationMessages = [
            'pay_amount.required' => 'Paid Amount ဖြည့်ရန်လိုအပ်ပါသည်',
            'pay_amount.numeric' =>  'Paid Amount သည် Number ဖြစ်ရပါမည်',
            'pay_amount.gt' => 'Paid Amount ဖြည့်ရန်လိုအပ်ပါသည်',
            'voucher_discount' =>  'Voucher Discount သည် Number ဖြစ်ရပါမည်',
            'tax' =>  'Tax သည် Number ဖြစ်ရပါမည်',
            'transport_charges' => 'Transport Charges သည် Number ဖြစ်ရပါမည်',
            'other_charges' =>  'Other Charges သည် Number ဖြစ်ရပါမည်',
        ];

        $validator = Validator::make($request->all(), $validationRules, $validationMessages)->validate();
        return $validator;
    }

    private function addPaymentLog(Request $request)
    {
        $data = [
            'purchase_id' => $request->purchaseID,
            'paid_date' => $request->pay_date,
            'voucher_discount' => $request->voucher_discount,
            'total_amount' => $request->opening_total,
            'tax' => $request->tax,
            'transport_charges' => $request->transport_charges,
            'other_charges' => $request->other_charges,
            'paid_amount' => $request->pay_amount,
            'net_amount' => $request->net_amount,
            'balance' => $request->balance
        ];
        return $data;
    }

    private function addPaymentLogForEdit(Request $request)
    {
        $data = [
            'paid_date' => $request->pay_date,
            'voucher_discount' => $request->voucher_discount,
            'total_amount' => $request->opening_total,
            'tax' => $request->tax,
            'transport_charges' => $request->transport_charges,
            'other_charges' => $request->other_charges,
            'paid_amount' => $request->pay_amount,
            'net_amount' => $request->net_amount,
            'balance' => $request->balance
        ];
        return $data;
    }

    public function GetPaidEditLog(Request $request)
    {
        try {
            $paid_edit_log = PurchasePaymentLog::where('purchase_payment_logs.purchase_id', '=', $request->purchaseID)
                ->select('purchase_payment_logs.*', 'P.purchase_voucher_number', 'P.due_date', 'S.supplier_name') //'P.total_item_discount'
                ->selectRaw('0 as total_item_discount')
                ->join('purchases as P', 'P.purchase_id', '=', 'purchase_payment_logs.purchase_id')
                ->join('suppliers as S', 'S.supplier_id', '=', 'P.supplier_id')
                ->orderByDesc('purchase_payment_log_id')->skip(0)->take(1)->get();
            return response()->json(['success' => $paid_edit_log]);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()]);
        }
    }

    public function editPaymentLog(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pay_amount' => 'required|numeric|gt:0',
            'voucher_discount' => 'numeric',
            'tax' => 'numeric',
            'transport_charges' => 'numeric',
            'other_charges' => 'numeric'
        ], [

            'pay_amount.required' => 'Paid Amount ဖြည့်ရန်လိုအပ်ပါသည်',
            'pay_amount.numeric' =>  'Paid Amount သည် Number ဖြစ်ရပါမည်',
            'pay_amount.gt' => 'Paid Amount ဖြည့်ရန်လိုအပ်ပါသည်',
            'voucher_discount' =>  'Voucher Discount သည် Number ဖြစ်ရပါမည်',
            'tax' =>  'Tax သည် Number ဖြစ်ရပါမည်',
            'transport_charges' => 'Transport Charges သည် Number ဖြစ်ရပါမည်',
            'other_charges' =>  'Other Charges သည် Number ဖြစ်ရပါမည်',
        ]);
        if ($validator->passes()) {
            try {
                $data = $this->addPaymentLogForEdit($request);
                PurchasePaymentLog::where('purchase_payment_log_id', $request->purchase_payment_log_id)->update($data);
                return response()->json(['success' => "Edit successful!"]);
            } catch (\Exception $e) {
                return response()->json(['errors' => $e->getMessage()]);
            }
        } else {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
        }
    }

    public function deletePaidLog(Request $request)
    {
        PurchasePaymentLog::where('purchase_payment_log_id', $request->purchase_payment_log_id)->delete();
        return response()->json(['success' => "Delete successful!"]);
    }

    //private
    //Add Item Data
    private function addItemData($req)
    {
        $data = [
            'main_category_id' => $req->create_main_category,
            'sub_category_id' => $req->create_sub_category,
            'item_type_id' => $req->create_item_type,
            'item_code' => $req->create_item_code,
            'bar_code' => $req->create_item_code,
            'item_name' => $req->create_item_name,
            'other_name' => $req->create_other_name,
            'unit_id' => $req->create_item_unit,
            'item_image' => $req->create_item_image,
            'location_id' => "1",
            'is_discontinued' => $req->create_is_discontinued,
            'is_deleted' => "0",
            'modified_by' => Auth::user()->id,
        ];
        return $data;
    }
}
