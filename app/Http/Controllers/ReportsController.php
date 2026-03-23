<?php

namespace App\Http\Controllers;

use App\Exports\SalesReportBySearch;
use App\Exports\SalesReportExport;
use App\Exports\PurchaseReportExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\DeletedOrder;
use App\Models\User;
use App\Models\Sales;
use App\Models\MenuItem;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\StockIssue;
use App\Models\SalesDetail;
use App\Models\MenuCategory;
use App\Models\StockReceive;
use DateTime;
use Illuminate\Http\Request;
use App\Models\PurchaseDetail;
use App\Models\StockIssueType;
use App\Models\StockIssueDetail;
use App\Models\StockReceiveDetail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Exports\SalesReportPdfExport;
use App\Exports\PurchaseReportPdfExport;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use App\Exports\BalanceReportPdfExport;
use Illuminate\Support\Facades\Log;

class ReportsController extends Controller
{
    public function bindingMenuCategory()
    {
        $menuCategoryList = MenuCategory::select('category_id as id', 'menu_category_name as name')
            ->where(['is_discontinued' => 0, 'is_deleted' => 0])
            ->get();
        return response()->json($menuCategoryList);
    }
    public function bindingStockItem()
    {
        $menuItemList = MenuItem::select('item_id as id', 'item_name as name')
            ->where(['is_discontinued' => 0])
            ->whereIn('item_type_id', [1, 3])
            ->get();
        return response()->json($menuItemList);
    }

    public function bindingStockIssue()
    {
        $stockIssueTypeList = StockIssueType::select('issue_type_id  as id', 'issue_type_name_1 as name')
            ->where(['is_discontinued' => 0])
            ->get();
        return response()->json($stockIssueTypeList);
    }

    public function bindingSupplier()
    {
        $supplierList = Supplier::select('supplier_id  as id', 'supplier_name as name')
            ->where('is_discontinued', 0)
            ->where('is_deleted', 0)
            ->get();
        return response()->json($supplierList);
    }

    public function bindingEmployee()
    {
        $employeeList = User::select('id', 'name')
            ->where('is_discontinued', 0)
            ->where('id', '!=', 1)
            ->get();
        return response()->json($employeeList);
    }

    public function stockInReport()
    {
        $stock_receive_list = StockReceive::select('receive_voucher_number', 'receive_date', 'remark')
            ->where('stock_receives.is_delete', 0)
            ->selectRaw('SUM(amount) as total_amount')
            ->join('stock_receive_details as RD', 'RD.stock_receive_id', '=', 'stock_receives.stock_receive_id')
            ->groupBy(['receive_voucher_number', 'receive_date', 'remark'])
            ->get();
        return view('admin.reports.stock_in.stock_in_report', compact('stock_receive_list'));
    }
    public function stockInReportBySearch(Request $req)
    {
        $startDate = $req->query('startDate');
        $endDate = $req->query('endDate');
        $endDate = date('Y-m-d', strtotime($endDate . ' +1 day'));
        $searchCategoryID = $req->query('searchCategoryID');
        $searchStockID = $req->query('searchStockID');
        $isCheckedItemSummary = $req->query('isCheckedItemSummary');

        $stock_in_report_list = [];

        if ($isCheckedItemSummary == 1) {
            if ($searchCategoryID == 0 && $searchStockID == 0) {
                $stock_in_report_list = StockReceiveDetail::select('stock_receive_details.*', 'SR.receive_voucher_number', 'SR.receive_date', 'I.item_name', 'MC.menu_category_name', 'U.unit_name', DB::raw('quantity*stock_receive_details.unit_cost as amount'))
                    ->join('stock_receives as SR', 'SR.stock_receive_id', '=', 'stock_receive_details.stock_receive_id')
                    ->join('menu_items as I', 'I.item_id', '=', 'stock_receive_details.item_id')
                    ->join('menu_categories as MC', 'I.sub_category_id', 'MC.category_id')
                    ->join('units as U', 'U.unit_id', '=', 'stock_receive_details.unit_id')
                    ->where('SR.is_delete', 0)
                    ->where('receive_date', '>=', date($startDate))
                    ->where('receive_date', '<=', date($endDate))
                    ->get();
            }
            if ($searchCategoryID != 0) {
                $stock_in_report_list = StockReceiveDetail::select('stock_receive_details.*', 'SR.receive_voucher_number', 'SR.receive_date', 'I.item_name', 'MC.menu_category_name', 'U.unit_name', DB::raw('quantity*stock_receive_details.unit_cost as amount'))
                    ->join('stock_receives as SR', 'SR.stock_receive_id', '=', 'stock_receive_details.stock_receive_id')
                    ->join('menu_items as I', 'I.item_id', '=', 'stock_receive_details.item_id')
                    ->join('menu_categories as MC', 'I.sub_category_id', 'MC.category_id')
                    ->join('units as U', 'U.unit_id', '=', 'stock_receive_details.unit_id')
                    ->where('SR.is_delete', 0)
                    ->where('receive_date', '>=', date($startDate))
                    ->where('receive_date', '<=', date($endDate))
                    ->where('I.sub_category_id', $searchCategoryID)
                    ->get();
            }
            if ($searchStockID != 0) {
                $stock_in_report_list = StockReceiveDetail::select('stock_receive_details.*', 'SR.receive_voucher_number', 'SR.receive_date', 'I.item_name', 'MC.menu_category_name', 'U.unit_name', DB::raw('quantity*stock_receive_details.unit_cost as amount'))
                    ->join('stock_receives as SR', 'SR.stock_receive_id', '=', 'stock_receive_details.stock_receive_id')
                    ->join('menu_items as I', 'I.item_id', '=', 'stock_receive_details.item_id')
                    ->join('menu_categories as MC', 'I.sub_category_id', 'MC.category_id')
                    ->join('units as U', 'U.unit_id', '=', 'stock_receive_details.unit_id')
                    ->where('SR.is_delete', 0)
                    ->where('receive_date', '>=', date($startDate))
                    ->where('receive_date', '<=', date($endDate))
                    ->where('I.item_id', $searchStockID)
                    ->get();
            }
        } else {
            $stock_in_report_list = StockReceive::select('receive_voucher_number', 'receive_date', 'remark')
                ->where('stock_receives.is_delete', 0)
                ->where('receive_date', '>=', date($startDate))
                ->where('receive_date', '<=', date($endDate))
                ->selectRaw('SUM(amount) as total_amount')
                ->join('stock_receive_details as RD', 'RD.stock_receive_id', '=', 'stock_receives.stock_receive_id')
                ->groupBy(['receive_voucher_number', 'receive_date', 'remark'])
                ->get();
        }
        return response()->json($stock_in_report_list);
    }

    public function stockOutReport()
    {
        $stock_issue_list = StockIssue::select('issue_voucher_number', 'issue_date', 'issue_type_name_1 as issue_type', 'total_qty', 'remark')
            ->where('stock_issues.is_delete', 0)
            ->join('stock_issue_types as ST', 'ST.issue_type_id', '=', 'stock_issues.issue_type_id')
            ->get();
        return view('admin.reports.stock_out.stock_out_report', compact('stock_issue_list'));
    }

    public function stockOutReportBySearch(Request $req)
    {
        $startDate = $req->query('startDate');
        $endDate = $req->query('endDate');
        $endDate = date('Y-m-d', strtotime($endDate . ' +1 day'));
        $searchCategoryID = $req->query('searchCategoryID');
        $searchStockID = $req->query('searchStockID');
        $searchIssueTypeID = $req->query('searchIssueTypeID');
        $isCheckedItemSummary = $req->query('isCheckedItemSummary');

        $stock_out_report_list = [];

        if ($isCheckedItemSummary == 1) {
            if ($searchCategoryID == 0 && $searchStockID == 0 && $searchIssueTypeID == 0) {
                $stock_out_report_list = StockIssueDetail::select(
                    'stock_issue_details.*',
                    'SI.issue_date',
                    'SI.issue_voucher_number',
                    'MI.item_name',
                    'MC.menu_category_name',
                    'SIT.issue_type_name_1 as issue_type_name',
                    'U.unit_name',
                )
                    ->join('stock_issues as SI', 'stock_issue_details.stock_issue_id', 'SI.stock_issue_id')
                    ->join('stock_issue_types as SIT', 'SI.issue_type_id', 'SIT.issue_type_id')
                    ->join('menu_items as MI', 'stock_issue_details.item_id', 'MI.item_id')
                    ->join('menu_categories as MC', 'MI.sub_category_id', 'MC.category_id')
                    ->join('units as U', 'stock_issue_details.unit_id', 'U.unit_id')
                    ->where('stock_issue_details.is_deleted', 0)
                    ->where('SI.issue_date', '>=', date($startDate))
                    ->where('SI.issue_date', '<=', date($endDate))
                    ->get();
            }
            if ($searchCategoryID != 0) {
                $stock_out_report_list = StockIssueDetail::select(
                    'stock_issue_details.*',
                    'SI.issue_date',
                    'SI.issue_voucher_number',
                    'MI.item_name',
                    'MC.menu_category_name',
                    'SIT.issue_type_name_1 as issue_type_name',
                    'U.unit_name',
                )
                    ->join('stock_issues as SI', 'stock_issue_details.stock_issue_id', 'SI.stock_issue_id')
                    ->join('stock_issue_types as SIT', 'SI.issue_type_id', 'SIT.issue_type_id')
                    ->join('menu_items as MI', 'stock_issue_details.item_id', 'MI.item_id')
                    ->join('menu_categories as MC', 'MI.sub_category_id', 'MC.category_id')
                    ->join('units as U', 'stock_issue_details.unit_id', 'U.unit_id')
                    ->where('stock_issue_details.is_deleted', 0)
                    ->where('SI.issue_date', '>=', date($startDate))
                    ->where('SI.issue_date', '<=', date($endDate))
                    ->where('MI.sub_category_id', $searchCategoryID)
                    ->get();
            }
            if ($searchStockID != 0) {
                $stock_out_report_list = StockIssueDetail::select(
                    'stock_issue_details.*',
                    'SI.issue_date',
                    'SI.issue_voucher_number',
                    'MI.item_name',
                    'MC.menu_category_name',
                    'SIT.issue_type_name_1 as issue_type_name',
                    'U.unit_name',
                )
                    ->join('stock_issues as SI', 'stock_issue_details.stock_issue_id', 'SI.stock_issue_id')
                    ->join('stock_issue_types as SIT', 'SI.issue_type_id', 'SIT.issue_type_id')
                    ->join('menu_items as MI', 'stock_issue_details.item_id', 'MI.item_id')
                    ->join('menu_categories as MC', 'MI.sub_category_id', 'MC.category_id')
                    ->join('units as U', 'stock_issue_details.unit_id', 'U.unit_id')
                    ->where('stock_issue_details.is_deleted', 0)
                    ->where('SI.issue_date', '>=', date($startDate))
                    ->where('SI.issue_date', '<=', date($endDate))
                    ->where('MI.item_id', $searchStockID)
                    ->get();
            }
            if ($searchIssueTypeID != 0) {
                $stock_out_report_list = StockIssueDetail::select(
                    'stock_issue_details.*',
                    'SI.issue_date',
                    'SI.issue_voucher_number',
                    'MI.item_name',
                    'MC.menu_category_name',
                    'SIT.issue_type_name_1 as issue_type_name',
                    'U.unit_name',
                )
                    ->join('stock_issues as SI', 'stock_issue_details.stock_issue_id', 'SI.stock_issue_id')
                    ->join('stock_issue_types as SIT', 'SI.issue_type_id', 'SIT.issue_type_id')
                    ->join('menu_items as MI', 'stock_issue_details.item_id', 'MI.item_id')
                    ->join('menu_categories as MC', 'MI.sub_category_id', 'MC.category_id')
                    ->join('units as U', 'stock_issue_details.unit_id', 'U.unit_id')
                    ->where('stock_issue_details.is_deleted', 0)
                    ->where('SI.issue_date', '>=', date($startDate))
                    ->where('SI.issue_date', '<=', date($endDate))
                    ->where('SIT.issue_type_id', $searchIssueTypeID)
                    ->get();
            }
        } else {
            $stock_out_report_list = StockIssue::select('issue_voucher_number', 'issue_date', 'issue_type_name_1 as issue_type', 'remark', 'total_qty')
                ->where('stock_issues.is_delete', 0)
                ->where('issue_date', '>=', date($startDate))
                ->where('issue_date', '<=', date($endDate))
                ->join('stock_issue_types as ST', 'ST.issue_type_id', '=', 'stock_issues.issue_type_id')
                ->get();
        }
        return response()->json($stock_out_report_list);
    }

    public function purchaseReport()
    {
        $purchase_list = Purchase::select('purchase_voucher_number', 'purchase_date', 'due_date', 'purchases.remark', 'purchases.total_amount', 'S.supplier_name')
            ->selectRaw('IFNULL(SUM(PL.voucher_discount),0)+purchases.total_item_discount as discount_amount')
            ->selectRaw('IFNULL(SUM(PL.transport_charges), 0) as transport_charges')
            ->selectRaw('IFNULL(SUM(PL.other_charges), 0) as other_charges')
            ->selectRaw('IFNULL(SUM(PL.tax), 0) as tax')
            ->selectRaw('IFNULL(SUM(PL.paid_amount), 0) as paid_amount')
            ->join('suppliers as S', 'S.supplier_id', '=', 'purchases.supplier_id')
            ->leftJoin('purchase_payment_logs as PL', 'PL.purchase_id', '=', 'purchases.purchase_id')
            ->where('purchases.is_delete', 0)
            ->whereDate('purchase_date', today())
            ->groupBy(['purchase_voucher_number', 'purchase_date', 'due_date', 'remark', 'total_amount', 'total_item_discount', 'supplier_name'])
            ->get();

        return view('admin.reports.purchase.purchase_report', compact('purchase_list'));
    }

    public function purchaseReportBySearch(Request $req)
    {
        $startDate = $req->query('startDate');
        $endDate   = $req->query('endDate');

        $fromDate = $startDate
            ? Carbon::parse($startDate)->startOfDay()
            : Carbon::today()->startOfDay();

        $toDate = $endDate
            ? Carbon::parse($endDate)->endOfDay()
            : Carbon::today()->endOfDay();
        $searchCategoryID = $req->query('searchCategoryID');
        $searchStockID = $req->query('searchStockID');
        $searchSupplierID = $req->query('searchSupplierID');
        $isCheckedItemSummary = $req->query('isCheckedItemSummary');

        $purchase_report_list = [];

        if ($isCheckedItemSummary == 1) {
            if ($searchCategoryID == 0 && $searchStockID == 0 && $searchSupplierID == 0) {
                $purchase_report_list = PurchaseDetail::select(
                    'purchase_details.*',
                    'P.purchase_voucher_number',
                    'purchase_date',
                    'S.supplier_name',
                    'MI.item_name',
                    'MC.menu_category_name',
                    'MI.sub_category_id',
                    'U.unit_name',
                    DB::raw('quantity*purchase_details.unit_cost as amount')
                )
                    ->join('purchases as P', 'P.purchase_id', '=', 'purchase_details.purchase_id')
                    ->join('suppliers as S', 'S.supplier_id', '=', 'P.supplier_id')
                    ->leftjoin('menu_items as MI', 'MI.item_id', '=', 'purchase_details.item_id')
                    ->join('menu_categories as MC', 'MI.sub_category_id', 'MC.category_id')
                    ->join('units as U', 'U.unit_id', '=', 'purchase_details.unit_id')
                    ->where('P.is_delete', 0)
                    ->where('purchase_date', '>=', date($fromDate))
                    ->where('purchase_date', '<=', date($toDate))
                    ->get();
            }
            if ($searchCategoryID != 0) {
                $purchase_report_list = PurchaseDetail::select(
                    'purchase_details.*',
                    'P.purchase_voucher_number',
                    'purchase_date',
                    'S.supplier_name',
                    'MI.item_name',
                    'MC.menu_category_name',
                    'MI.sub_category_id',
                    'U.unit_name',
                    DB::raw('quantity*purchase_details.unit_cost as amount')
                )
                    ->join('purchases as P', 'P.purchase_id', '=', 'purchase_details.purchase_id')
                    ->join('suppliers as S', 'S.supplier_id', '=', 'P.supplier_id')
                    ->join('menu_items as MI', 'MI.item_id', '=', 'purchase_details.item_id')
                    ->join('menu_categories as MC', 'MI.sub_category_id', 'MC.category_id')
                    ->join('units as U', 'U.unit_id', '=', 'purchase_details.unit_id')
                    ->where('P.is_delete', 0)
                    ->where('purchase_date', '>=', date($fromDate))
                    ->where('purchase_date', '<=', date($toDate))
                    ->where('MI.sub_category_id', $searchCategoryID)
                    ->get();
            }
            if ($searchStockID != 0) {
                $purchase_report_list = PurchaseDetail::select(
                    'purchase_details.*',
                    'P.purchase_voucher_number',
                    'purchase_date',
                    'S.supplier_name',
                    'MI.item_name',
                    'MC.menu_category_name',
                    'MI.sub_category_id',
                    'U.unit_name',
                    DB::raw('quantity*purchase_details.unit_cost as amount')
                )
                    ->join('purchases as P', 'P.purchase_id', '=', 'purchase_details.purchase_id')
                    ->join('suppliers as S', 'S.supplier_id', '=', 'P.supplier_id')
                    ->join('menu_items as MI', 'MI.item_id', '=', 'purchase_details.item_id')
                    ->join('menu_categories as MC', 'MI.sub_category_id', 'MC.category_id')
                    ->join('units as U', 'U.unit_id', '=', 'purchase_details.unit_id')
                    ->where('P.is_delete', 0)
                    ->where('purchase_date', '>=', date($fromDate))
                    ->where('purchase_date', '<=', date($toDate))
                    ->where('MI.item_id', $searchStockID)
                    ->get();
            }
            if ($searchSupplierID != 0) {
                $purchase_report_list = PurchaseDetail::select(
                    'purchase_details.*',
                    'P.purchase_voucher_number',
                    'purchase_date',
                    'S.supplier_name',
                    'MI.item_name',
                    'MC.menu_category_name',
                    'MI.sub_category_id',
                    'U.unit_name',
                    DB::raw('quantity*purchase_details.unit_cost as amount')
                )
                    ->join('purchases as P', 'P.purchase_id', '=', 'purchase_details.purchase_id')
                    ->join('suppliers as S', 'S.supplier_id', '=', 'P.supplier_id')
                    ->join('menu_items as MI', 'MI.item_id', '=', 'purchase_details.item_id')
                    ->join('menu_categories as MC', 'MI.sub_category_id', 'MC.category_id')
                    ->join('units as U', 'U.unit_id', '=', 'purchase_details.unit_id')
                    ->where('P.is_delete', 0)
                    ->where('purchase_date', '>=', date($fromDate))
                    ->where('purchase_date', '<=', date($toDate))
                    ->where('S.supplier_id', $searchSupplierID)
                    ->get();
            }
        } else {
            $purchase_report_list = Purchase::select('purchase_voucher_number', 'purchase_date', 'due_date', 'purchases.remark', 'purchases.total_amount', 'S.supplier_name')
                ->selectRaw('IFNULL(SUM(PL.voucher_discount),0)+purchases.total_item_discount as discount_amount')
                ->selectRaw('IFNULL(SUM(PL.transport_charges), 0) as transport_charges')
                ->selectRaw('IFNULL(SUM(PL.other_charges), 0) as other_charges')
                ->selectRaw('IFNULL(SUM(PL.tax), 0) as tax')
                ->selectRaw('IFNULL(SUM(PL.paid_amount), 0) as paid_amount')
                ->join('suppliers as S', 'S.supplier_id', '=', 'purchases.supplier_id')
                ->leftJoin('purchase_payment_logs as PL', 'PL.purchase_id', '=', 'purchases.purchase_id')
                ->where('purchases.is_delete', 0)
                ->where('purchase_date', '>=', date($fromDate))
                ->where('purchase_date', '<=', date($toDate))
                ->groupBy(['purchase_voucher_number', 'purchase_date', 'due_date', 'remark', 'total_amount', 'total_item_discount', 'supplier_name'])
                ->get();
        }
        return response()->json($purchase_report_list);
    }
    public function salesReport()
    {
        $todayDate = Carbon::now()->format('Y-m-d');
        $sales = Sales::select('*', 'waiter.name as waiter_name', 'cashier.name as cashier_name')
            ->join('tables', 'sales.table_id', 'tables.table_id')
            ->join('floors', 'tables.floor_id', 'floors.floor_id')
            ->leftjoin('customers', 'sales.customer_id', 'customers.customer_id')
            ->leftjoin('users as waiter', 'sales.waiter_id', 'waiter.id')
            ->join('users as cashier', 'sales.cashier_id', 'cashier.id')
            ->where('sales.is_delete', 0)
            ->whereDate('sales.created_at', $todayDate)
            ->get()
            ->sortByDesc('sale_id')
            ->toArray();

        $total_sale_cost = SalesDetail::join('menu_items as items1', 'sales_details.item_id', '=', 'items1.item_id')
            ->join('units', 'units.unit_id', '=', 'items1.unit_id')
            ->join('menu_categories as MC', 'items1.sub_category_id', 'MC.category_id')
            ->join('sales as S', 'sales_details.sale_id', 'S.sale_id')
            ->join('item_selling_prices as ISP', 'items1.item_id', 'ISP.item_id')
            ->whereDate('sales_details.created_at', $todayDate)
            ->where('S.is_delete', 0)
            ->sum(DB::raw('ISP.unit_cost * sales_details.quantity'));

        // dd($sales);

        return view('admin.reports.sales.sales_report', compact('sales', 'total_sale_cost'));
    }

    public function modifySaleReportBySearch(Request $req)
    {

        $startDate = \Carbon\Carbon::parse($req->query('startDate'))->startOfDay();
        $endDate = \Carbon\Carbon::parse($req->query('endDate'))->endOfDay();
        // $startDateTime = \Carbon\Carbon::parse($req->query('startDate'));
        // $endDateTime = \Carbon\Carbon::parse($req->query('endDate'));

        $searchCategoryID = $req->query('searchCategoryID');
        $searchStockID = $req->query('searchStockID');
        $isCheckedItemSummary = $req->query('isCheckedItemSummary');
        $isFOCSummary = (int) $req->query('isFOCSummary');
        $isDiscountSummary = (int) $req->query('isDiscountSummary');
        $isKPaySummary = (int) $req->query('isKPaySummary');
        $isDeletedSummary = (int) $req->query('isDeletedSummary');
        // $searchEmployeeID = $req->query('searchEmployeeID');
        // $searchDeletedOrderID = $req->query('searchDeletedOrderID');

        if ($isCheckedItemSummary == 1) {

            $sales_report_list = SalesDetail::select(
                'S.sale_voucher_number',
                'items1.item_name',
                'items1.sub_category_id',
                'MC.menu_category_name',
                'units.unit_name',
                'ISP.item_selling_price',
                'ISP.unit_cost',
                'promotion_price',
                'quantity',
                'is_foc',
                'order_time',
                'S.is_delete'
            )
                ->join('menu_items as items1', 'sales_details.item_id', '=', 'items1.item_id')
                ->join('units', 'units.unit_id', '=', 'items1.unit_id')
                // ->join('users as U', 'sales_details.ordered_by', 'U.id')
                ->join('menu_categories as MC', 'items1.sub_category_id', 'MC.category_id')
                ->join('sales as S', 'sales_details.sale_id', 'S.sale_id')
                ->join('item_selling_prices as ISP', 'items1.item_id', 'ISP.item_id')
                ->where('S.is_delete', 0)
                ->whereBetween('sales_details.created_at', [$startDate, $endDate]);
            if ($searchCategoryID != 0) {
                $sales_report_list->where('items1.sub_category_id', $searchCategoryID);
            }

            if ($searchStockID != 0) {
                $sales_report_list->where('items1.item_id', $searchStockID);
            }
            $sales_report_list = $sales_report_list->get()->toArray();

            $total_sale_cost = $sales_report_list ? collect($sales_report_list)->sum(function ($item) {
                return $item['unit_cost'] * $item['quantity'];
            }) : 0;

            $query = Sales::where('is_delete', 0)
                ->whereBetween('created_at', [$startDate, $endDate]);

            $total_cash_payment = $query->sum(DB::raw('paid_amount'));

            // $total_online_payment = $query->sum(DB::raw('online_paid'));


        } else {
            $sales_report_list = Sales::select(
                'sales.sale_id',
                'sale_voucher_number',
                'floors.floor_name',
                'tables.table_name',
                'table_order_number',
                'customers.customer_name',
                'waiter.name as waiter_name',
                'cashier.name as cashier_name',
                'order_date',
                'total_amount',
                'total_item_promo_amount',
                'voucher_discount_amount',
                'service_charges_amount',
                'tax_amount',
                'net_amount',
                'paid_amount',
                'balance_amount',
                'change_amount',
                'delivery_charges',
                'online_paid',
                'is_delete'
            )
                ->join('tables', 'sales.table_id', 'tables.table_id')
                ->join('floors', 'tables.floor_id', 'floors.floor_id')
                ->leftjoin('customers', 'sales.customer_id', 'customers.customer_id')
                ->leftjoin('users as waiter', 'sales.waiter_id', 'waiter.id')
                ->join('users as cashier', 'sales.cashier_id', 'cashier.id')
                // ->where('sales.is_delete', '0')
                ->whereBetween('sales.created_at', [$startDate, $endDate]);

            if ($isFOCSummary != 0) {

                $sales_report_list->where('sales.is_delete', '0')
                    ->where('sales.voucher_foc', '=', 1);
                // dd($isFOCSummary, $sales_report_list->get()->toArray());
            } elseif ($isDiscountSummary != 0) {

                $sales_report_list->where('sales.is_delete', '0')
                    ->whereBetween('sales.voucher_discount_percent', [1, 99]);
                // dd($isDiscountSummary, $sales_report_list->get()->toArray());
            } elseif ($isKPaySummary != 0) {

                $sales_report_list->where('sales.is_delete', '0')
                    ->where('sales.payment_type_id', '!=', 1);
                // dd($isKPaySummary, $sales_report_list->get()->toArray());
            } elseif ($isDeletedSummary != 0) {

                $sales_report_list->where('sales.is_delete', '1');
                // dd($isDeletedSummary, $sales_report_list->get()->toArray());
            } else {

                $sales_report_list->where('sales.is_delete', '0');
                // dd($isDeletedSummary,$sales_report_list->get()->toArray());
            }

            $total_cash_payment = $sales_report_list->sum(DB::raw('paid_amount'));

            $sales_report_list = $sales_report_list->orderByDesc('sale_id')->get();

            $total_sale_cost = SalesDetail::join('menu_items as items1', 'sales_details.item_id', '=', 'items1.item_id')
                ->join('units', 'units.unit_id', '=', 'items1.unit_id')
                // ->join('users as U', 'sales_details.ordered_by', 'U.id')
                ->join('menu_categories as MC', 'items1.sub_category_id', 'MC.category_id')
                ->join('sales as S', 'sales_details.sale_id', 'S.sale_id')
                ->join('item_selling_prices as ISP', 'items1.item_id', 'ISP.item_id')
                ->whereIn('S.sale_id', $sales_report_list->pluck('sale_id'))
                ->sum(DB::raw('ISP.unit_cost * sales_details.quantity'));
        }

        return response()->json([
            'sales_report_list' => $sales_report_list,
            'total_sale_cost' => $total_sale_cost,
            'total_cash_payment' => $total_cash_payment
        ]);
    }

    public function excelSaleExport(Request $req)
    {
        $startDate = $req->input('startDate');
        $endDate = $req->input('endDate');

        $isCheckedItemSummary = $req->input('isCheckedItemSummary');
        $searchCategoryID = $req->input('searchCategoryID');
        $searchStockID = $req->input('searchStockID');
        $isFOCSummary = $req->input('isFOCSummary');
        $isDiscountSummary = $req->input('isDiscountSummary');
        $isKPaySummary = $req->input('isKPaySummary');
        $isDeletedSummary = $req->input('isDeletedSummary');

        $dateForFile = $endDate ? Carbon::parse($endDate) : Carbon::now();
        $fileName = 'SalesReport-' . $dateForFile->format('Y_m_d') . '.xlsx';

        return Excel::download(
            new SalesReportExport(
                $startDate,
                $endDate,
                $isCheckedItemSummary,
                $searchCategoryID,
                $searchStockID,
                $isFOCSummary,
                $isDiscountSummary,
                $isKPaySummary,
                $isDeletedSummary
            ),
            $fileName
        );
    }

    public function excelPurchaseExport(Request $req)
    {
        $startDate = $req->input('startDate');
        $endDate   = $req->input('endDate');

        $isCheckedItemSummary = $req->input('isCheckedItemSummary');
        $searchCategoryID     = $req->input('searchCategoryID');
        $searchStockID        = $req->input('searchStockID');
        $searchSupplierID     = $req->input('searchSupplierID');

        $dateForFile = $endDate ? Carbon::parse($endDate) : Carbon::now();
        $fileName = 'PurchaseReport-' . $dateForFile->format('Y_m_d') . '.xlsx';

        return Excel::download(
            new PurchaseReportExport(
                $startDate,
                $endDate,
                $isCheckedItemSummary,
                $searchCategoryID,
                $searchStockID,
                $searchSupplierID
            ),
            $fileName
        );
    }

    public function exportSalePdf(Request $request)
    {
        return (new SalesReportPdfExport(
            $request->startDate,
            $request->endDate,
            $request->isCheckedItemSummary,
            $request->searchCategoryID,
            $request->searchStockID,
            $request->isFOCSummary,
            $request->isDiscountSummary,
            $request->isKPaySummary,
            $request->isDeletedSummary
        ))->download();
    }

    public function exportPurchasePdf(Request $request)
    {
        return (new PurchaseReportPdfExport(
            $request->startDate,
            $request->endDate,
            $request->isCheckedItemSummary ?? 0,
            $request->searchCategoryID ?? 0,
            $request->searchStockID ?? 0,
            $request->searchSupplierID ?? 0
        ))->download();
    }
    public function balanceReport(Request $req)
    {
        $searchDate = $req->query('date')
            ? Carbon::parse($req->query('date'))->toDateString()
            : Carbon::now()->toDateString();


        $searchCategoryID = $req->query('searchCategoryID');

        // Get all items with activity up to and including search date
        $activeItemIds = $this->getActiveItemIds($searchDate);

        if (empty($activeItemIds)) {
            $balance_report_list = collect();
            // return view('admin.reports.balance.balance_report', compact('balance_report_list', 'searchDate', 'searchCategoryID'));
        }


        // Calculate batch-level inventory for each item
        $balance_report_list = collect();

        foreach ($activeItemIds as $itemId) {
            $itemReport = $this->calculateItemFIFOBalance($itemId, $searchDate);

            // Apply category filter if needed
            if ($searchCategoryID && $searchCategoryID != 0) {
                if ($itemReport['sub_category_id'] != $searchCategoryID) {

                    continue;
                }
            }

            $balance_report_list->push((object)$itemReport);
        }

        if ($req->expectsJson() || $req->wantsJson()) {
            return response()->json(($balance_report_list));
        }

        return view('admin.reports.balance.balance_report', compact('balance_report_list', 'searchDate', 'searchCategoryID'));
    }

    private function getActiveItemIds($searchDate)
    {
        $purchaseIds = DB::table('purchase_details')
            ->join('purchases', 'purchase_details.purchase_id', '=', 'purchases.purchase_id')
            ->whereDate('purchases.purchase_date', '=', $searchDate)
            ->where(function ($q) {
                $q->whereNull('purchases.is_delete')->orWhere('purchases.is_delete', 0);
            })
            ->pluck('purchase_details.item_id');

        // dd($purchaseIds->toArray());

        $receiveIds = DB::table('stock_receive_details')
            ->join('stock_receives', 'stock_receive_details.stock_receive_id', '=', 'stock_receives.stock_receive_id')
            ->whereDate('stock_receives.receive_date', '=', $searchDate)
            ->where(function ($q) {
                $q->whereNull('stock_receives.is_delete')->orWhere('stock_receives.is_delete', 0);
            })
            ->pluck('stock_receive_details.item_id');

        // dd($receiveIds->toArray());

        $soldIds = DB::table('sales_details')
            ->join('sales', 'sales_details.sale_id', '=', 'sales.sale_id')
            ->whereDate('sales.order_date', '=', $searchDate)
            ->where(function ($q) {
                $q->whereNull('sales.is_delete')->orWhere('sales.is_delete', 0);
            })
            ->pluck('sales_details.item_id');

        // dd($soldIds->toArray());

        $issuedIds = DB::table('stock_issue_details')
            ->join('stock_issues', 'stock_issue_details.stock_issue_id', '=', 'stock_issues.stock_issue_id')
            ->whereDate('stock_issues.issue_date', '=', $searchDate)
            ->where(function ($q) {
                $q->whereNull('stock_issues.is_delete')->orWhere('stock_issues.is_delete', 0);
            })
            ->pluck('stock_issue_details.item_id');

        // dd($issuedIds->toArray());

        return array_values(array_unique(array_merge(
            $purchaseIds->toArray(),
            $receiveIds->toArray(),
            $soldIds->toArray(),
            $issuedIds->toArray()
        )));
    }

    private function calculateItemFIFOBalance($itemId, $searchDate)
    {
        // Get item details
        $item = DB::table('menu_items')
            ->leftJoin('menu_categories as MC', 'menu_items.sub_category_id', '=', 'MC.category_id')
            ->leftJoin('units', 'units.unit_id', '=', 'menu_items.unit_id')
            ->leftJoin('item_selling_prices as ISP', 'ISP.item_id', '=', 'menu_items.item_id')
            ->where('menu_items.item_id', $itemId)
            ->select(
                'menu_items.item_id',
                'menu_items.item_name',
                'menu_items.sub_category_id',
                'MC.menu_category_name',
                'units.unit_name',
                DB::raw('MAX(ISP.unit_cost) as isp_unit_cost'),
                DB::raw('MAX(ISP.item_selling_price) as sale_price')
            )
            ->groupBy('menu_items.item_id', 'menu_items.item_name', 'menu_items.sub_category_id', 'MC.menu_category_name', 'units.unit_name')
            ->first();

        // Step 1: Get all incoming transactions (purchases + receives) up to search date, ordered by date and batch
        $incomingBatches = $this->getIncomingBatches($itemId, $searchDate);

        // Step 2: Get all outgoing transactions (sales + issues) up to search date
        $outgoingQty = $this->getOutgoingQuantity($itemId, $searchDate);

        // Step 3: Apply FIFO consumption to determine remaining batches
        $remainingBatches = $this->applyFIFOConsumption($incomingBatches, $outgoingQty);
        // Step 4: Get activity on the specific search date
        $dateActivity = $this->getDateActivity($itemId, $searchDate);

        // Step 5: Calculate weighted average unit cost from remaining batches
        $totalRemainingQty = array_sum(array_column($remainingBatches, 'remaining_qty'));
        $totalRemainingValue = array_sum(array_map(function ($batch) {
            return $batch['remaining_qty'] * $batch['unit_cost'];
        }, $remainingBatches));

        $weightedUnitCost = $totalRemainingQty > 0
            ? $totalRemainingValue / $totalRemainingQty
            : ($item->isp_unit_cost ?? 0);

        return [
            'item_id' => $item->item_id,
            'item_name' => $item->item_name,
            'sub_category_id' => $item->sub_category_id,
            'menu_category_name' => $item->menu_category_name,
            'unit_name' => $item->unit_name,
            'purchased_qty' => $dateActivity['purchased_qty'],
            'purchased_value' => $dateActivity['purchased_value'],
            'received_qty' => $dateActivity['received_qty'],
            'received_value' => $dateActivity['received_value'],
            'sold_qty' => $dateActivity['sold_qty'],
            'issued_qty' => $dateActivity['issued_qty'],
            'total_in_qty' => $dateActivity['purchased_qty'] + $dateActivity['received_qty'],
            'total_out_qty' => $dateActivity['sold_qty'] + $dateActivity['issued_qty'],
            'balance_qty' => $totalRemainingQty,
            'weighted_unit_cost' => round($weightedUnitCost, 2),
            'amount' => round($totalRemainingValue, 2),
            'isp_unit_cost' => floor($item->isp_unit_cost ?? 0),
            'sale_price' => floor($item->sale_price ?? 0),
        ];
    }

    private function getIncomingBatches($itemId, $searchDate)
    {
        $purchases = DB::table('purchase_details')
            ->join('purchases', 'purchase_details.purchase_id', '=', 'purchases.purchase_id')
            ->where('purchase_details.item_id', $itemId)
            ->whereDate('purchases.purchase_date', '<=', $searchDate)
            ->where(function ($q) {
                $q->whereNull('purchases.is_delete')->orWhere('purchases.is_delete', 0);
            })
            ->select(
                'purchase_details.batch_number',
                'purchase_details.quantity',
                'purchase_details.unit_cost',
                'purchases.purchase_date as transaction_date'
            )
            ->get();

        $receives = DB::table('stock_receive_details')
            ->join('stock_receives', 'stock_receive_details.stock_receive_id', '=', 'stock_receives.stock_receive_id')
            ->where('stock_receive_details.item_id', $itemId)
            ->whereDate('stock_receives.receive_date', '<=', $searchDate)
            ->where(function ($q) {
                $q->whereNull('stock_receives.is_delete')->orWhere('stock_receives.is_delete', 0);
            })
            ->select(
                'stock_receive_details.batch_number',
                'stock_receive_details.quantity',
                'stock_receive_details.unit_cost',
                'stock_receives.receive_date as transaction_date'
            )
            ->get();

        // Merge and sort by transaction date and batch number (FIFO order)
        $allBatches = $purchases->concat($receives)
            ->sortBy([
                ['transaction_date', 'asc'],
                ['batch_number', 'asc']
            ])
            ->values()
            ->toArray();

        return array_map(function ($batch) {
            return [
                'batch_number' => $batch->batch_number,
                'quantity' => $batch->quantity,
                'unit_cost' => $batch->unit_cost,
                'transaction_date' => $batch->transaction_date,
            ];
        }, $allBatches);
    }

    private function getOutgoingQuantity($itemId, $searchDate)
    {
        $soldQty = DB::table('sales_details')
            ->join('sales', 'sales_details.sale_id', '=', 'sales.sale_id')
            ->where('sales_details.item_id', $itemId)
            ->whereDate('sales.order_date', '=', $searchDate)
            ->where(function ($q) {
                $q->whereNull('sales.is_delete')->orWhere('sales.is_delete', 0);
            })
            ->sum('sales_details.quantity');

        $issuedQty = DB::table('stock_issue_details')
            ->join('stock_issues', 'stock_issue_details.stock_issue_id', '=', 'stock_issues.stock_issue_id')
            ->where('stock_issue_details.item_id', $itemId)
            ->whereDate('stock_issues.issue_date', '=', $searchDate)
            ->where(function ($q) {
                $q->whereNull('stock_issues.is_delete')->orWhere('stock_issues.is_delete', 0);
            })
            ->sum('stock_issue_details.quantity');

        return $soldQty + $issuedQty;
    }

    private function applyFIFOConsumption($incomingBatches, $totalOutgoingQty)
    {
        $remainingBatches = [];
        $qtyToConsume = $totalOutgoingQty;

        foreach ($incomingBatches as $batch) {
            if ($qtyToConsume <= 0) {
                // No more consumption needed, add full batch
                $remainingBatches[] = [
                    'batch_number' => $batch['batch_number'],
                    'remaining_qty' => $batch['quantity'],
                    'unit_cost' => $batch['unit_cost'],
                ];
            } elseif ($qtyToConsume >= $batch['quantity']) {
                // Batch fully consumed
                $qtyToConsume -= $batch['quantity'];
                // Don't add to remaining batches
            } else {
                // Batch partially consumed
                $remainingQty = $batch['quantity'] - $qtyToConsume;
                $remainingBatches[] = [
                    'batch_number' => $batch['batch_number'],
                    'remaining_qty' => $remainingQty,
                    'unit_cost' => $batch['unit_cost'],
                ];
                $qtyToConsume = 0;
            }
        }

        return $remainingBatches;
    }

    private function getDateActivity($itemId, $searchDate)
    {
        $purchaseActivity = DB::table('purchase_details')
            ->join('purchases', 'purchase_details.purchase_id', '=', 'purchases.purchase_id')
            ->where('purchase_details.item_id', $itemId)
            ->whereDate('purchases.purchase_date', $searchDate)
            ->where(function ($q) {
                $q->whereNull('purchases.is_delete')->orWhere('purchases.is_delete', 0);
            })
            ->select(
                DB::raw('COALESCE(SUM(purchase_details.quantity), 0) as qty'),
                DB::raw('COALESCE(SUM(purchase_details.quantity * purchase_details.unit_cost), 0) as value')
            )
            ->first();

        $receiveActivity = DB::table('stock_receive_details')
            ->join('stock_receives', 'stock_receive_details.stock_receive_id', '=', 'stock_receives.stock_receive_id')
            ->where('stock_receive_details.item_id', $itemId)
            ->whereDate('stock_receives.receive_date', $searchDate)
            ->where(function ($q) {
                $q->whereNull('stock_receives.is_delete')->orWhere('stock_receives.is_delete', 0);
            })
            ->select(
                DB::raw('COALESCE(SUM(stock_receive_details.quantity), 0) as qty'),
                DB::raw('COALESCE(SUM(stock_receive_details.quantity * stock_receive_details.unit_cost), 0) as value')
            )
            ->first();

        $soldQty = DB::table('sales_details')
            ->join('sales', 'sales_details.sale_id', '=', 'sales.sale_id')
            ->where('sales_details.item_id', $itemId)
            ->whereDate('sales.order_date', $searchDate)
            ->where(function ($q) {
                $q->whereNull('sales.is_delete')->orWhere('sales.is_delete', 0);
            })
            ->sum('sales_details.quantity');

        $issuedQty = DB::table('stock_issue_details')
            ->join('stock_issues', 'stock_issue_details.stock_issue_id', '=', 'stock_issues.stock_issue_id')
            ->where('stock_issue_details.item_id', $itemId)
            ->whereDate('stock_issues.issue_date', $searchDate)
            ->where(function ($q) {
                $q->whereNull('stock_issues.is_delete')->orWhere('stock_issues.is_delete', 0);
            })
            ->sum('stock_issue_details.quantity');

        return [
            'purchased_qty' => $purchaseActivity->qty ?? 0,
            'purchased_value' => $purchaseActivity->value ?? 0,
            'received_qty' => $receiveActivity->qty ?? 0,
            'received_value' => $receiveActivity->value ?? 0,
            'sold_qty' => $soldQty ?? 0,
            'issued_qty' => $issuedQty ?? 0,
        ];
    }

    public function exportBalancePdf(Request $request)
    {
        $searchDate = $request->filled('date')
            ? Carbon::parse($request->query('date'))->toDateString()
            : Carbon::now()->toDateString();

        $searchCategoryID = (int) $request->query('searchCategoryID', 0);

        return (new BalanceReportPdfExport(
            $searchDate,
            $searchCategoryID
        ))->download('D');
    }



    public function topSaleReport(Request $req)
    {
        $searchDate = $req->query('searchDate')
            ? Carbon::parse($req->query('searchDate'))->toDateString()
            : null;
        $searchMonth = $req->query('searchMonth')
            ? Carbon::parse($req->query('searchMonth'))->format('Y-m')
            : null;
        $searchCategoryID = $req->query('searchCategoryID', 0);

        $query = DB::table('sales_details')
            ->join('sales', 'sales_details.sale_id', '=', 'sales.sale_id')
            ->join('menu_items', 'sales_details.item_id', '=', 'menu_items.item_id')
            ->leftJoin('menu_categories', 'menu_items.sub_category_id', '=', 'menu_categories.category_id')
            ->leftJoin('units', 'menu_items.unit_id', '=', 'units.unit_id')
            ->leftJoin('item_selling_prices as ISP', 'ISP.item_id', '=', 'menu_items.item_id')
            ->select(
                'menu_items.item_id',
                'menu_items.item_name',
                'menu_categories.menu_category_name',
                'units.unit_name',
                DB::raw('SUM(sales_details.quantity) as total_sold_qty'),
                DB::raw('SUM(sales_details.quantity * COALESCE(ISP.item_selling_price, 0)) as total_sales_amount'),
                DB::raw('COUNT(DISTINCT sales.sale_id) as total_orders'),
                DB::raw('COALESCE(MAX(ISP.item_selling_price), 0) as sale_price')
            )
            ->where(function ($q) {
                $q->whereNull('sales.is_delete')->orWhere('sales.is_delete', 0);
            })
            ->groupBy(
                'menu_items.item_id',
                'menu_items.item_name',
                'menu_categories.menu_category_name',
                'units.unit_name'
            );

        if ($searchDate) {
            $query->whereDate('sales.order_date', $searchDate);
        }

        if ($searchMonth) {
            $query->whereYear('sales.order_date', Carbon::parse($searchMonth)->year)
                ->whereMonth('sales.order_date', Carbon::parse($searchMonth)->month);
        }

        if ($searchCategoryID && $searchCategoryID != 0) {
            $query->where('menu_items.sub_category_id', $searchCategoryID);
        }

        $top_sale_items = $query->orderBy('total_sold_qty', 'desc')
            ->orderBy('total_sales_amount', 'desc')
            ->get();

        // Return JSON if it's an AJAX call
        if ($req->ajax()) {
            return response()->json($top_sale_items);
        }

        // Otherwise return normal view
        $categories = DB::table('menu_categories')->get();
        return view('admin.reports.top_sale.top_sale_report', compact(
            'top_sale_items',
            'searchDate',
            'searchMonth',
            'categories'
        ));
    }

    public function getTopSaleItemsForSearch(Request $req)
    {
        $searchDate = $req->query('searchDate') ?: null;
        $searchMonth = $req->query('searchMonth') ?: null;
        $searchCategoryID = $req->query('searchCategoryID') ?: null;

        $query = DB::table('sales_details')
            ->join('sales', 'sales_details.sale_id', '=', 'sales.sale_id')
            ->join('menu_items', 'sales_details.item_id', '=', 'menu_items.item_id')
            ->leftJoin('menu_categories', 'menu_items.sub_category_id', '=', 'menu_categories.category_id')
            ->leftJoin('units', 'menu_items.unit_id', '=', 'units.unit_id')
            ->leftJoin('item_selling_prices as ISP', 'ISP.item_id', '=', 'menu_items.item_id')
            ->select(
                'menu_items.item_id',
                'menu_items.item_name',
                'menu_categories.menu_category_name',
                'units.unit_name',
                DB::raw('SUM(sales_details.quantity) as total_sold_qty'),
                DB::raw('SUM(sales_details.quantity * COALESCE(ISP.item_selling_price,0)) as total_sales_amount'),
                DB::raw('COUNT(DISTINCT sales.sale_id) as total_orders'),
                DB::raw('COALESCE(MAX(ISP.item_selling_price),0) as sale_price')
            )
            ->where(function ($q) {
                $q->whereNull('sales.is_delete')->orWhere('sales.is_delete', 0);
            })
            ->groupBy(
                'menu_items.item_id',
                'menu_items.item_name',
                'menu_categories.menu_category_name',
                'units.unit_name'
            );

        // DATE
        if ($searchDate) {
            $query->whereDate('sales.order_date', $searchDate);
        }

        // MONTH
        if ($searchMonth) {
            $year = substr($searchMonth, 0, 4);
            $month = substr($searchMonth, 5, 2);

            $query->whereYear('sales.order_date', $year)
                ->whereMonth('sales.order_date', $month);
        }

        // CATEGORY
        if ($searchCategoryID) {
            $query->where('menu_items.sub_category_id', $searchCategoryID);
        }

        return $query->orderByDesc('total_sold_qty')
            ->orderByDesc('total_sales_amount')
            ->get();
    }

    public function renderTopSaleSearch(Request $req)
    {
        $top_sale_items = $this->getTopSaleItemsForSearch($req);

        $searchDate = $req->query('searchDate');
        $searchMonth = $req->query('searchMonth');
        $searchCategoryID = $req->query('searchCategoryID');

        $html = view(
            'admin.reports.top_sale.top_sale_search',
            compact('top_sale_items', 'searchDate', 'searchMonth', 'searchCategoryID')
        )->render();

        return response()->json([
            'html' => $html,
            'totalQuantity' => $top_sale_items->sum('total_sold_qty'),
            'totalSales' => $top_sale_items->sum('total_sales_amount'),
            'totalOrders' => $top_sale_items->sum('total_orders'),
        ]);
    }

    public function shopsReport(Request $req)
    {
        $date = $req->date ?? now()->toDateString();
        $shops = config('shops.locations');

        // Make Paralell API Calls
        $responses = Http::pool(function ($pool) use ($shops, $date) {
            $requests = [];
            foreach ($shops as $index => $shop) {
                $requests[$index] = $pool
                    ->withHeaders([
                        'Host' => $shop['host']  // Use each shop's unique host
                    ])
                    ->get("{$shop['host']}/api/daily-shop-report", ['date' => $date]);
            }
            // foreach ($shops as $index => $shop) {
            //     $requests[$index] = $pool
            //         ->withHeaders([
            //         'Host' => $shop['host']  // Use each shop's unique host
            //         ])
            //         ->get("{$shop['domain']}/api/daily-shop-report", ['date' => $date]);
            // }
            return $requests;
        });

        $sales = Sales::whereDate('created_at', $date)
            ->where('is_delete', 0)
            ->selectRaw('
                SUM(total_amount) as total_amount,
                SUM(total_item_promo_amount + voucher_discount_amount) as total_discount,
                COUNT(*) as sales_count
            ')
            ->first();

        // dd($sales->get()->toArray());

        $purchases = Purchase::whereDate('created_at', $date)
            ->where('is_delete', 0)
            ->selectRaw('
                SUM(total_amount) as total_amount,
                SUM(total_item_discount) as total_discount,
                COUNT(*) as purchases_count
            ')
            ->first();

        $reports = [
            [
                'name' => 'Main Shop',
                'link' => null,
                'date' => $date,
                'sales_amount' => $sales->total_amount ?? 0,
                'sales_discount' => $sales->total_discount ?? 0,
                'sales_count' => $sales->sales_count ?? 0,
                'purchases_amount' => $purchases->total_amount ?? 0,
                'purchases_discount' => $purchases->total_discount ?? 0,
                'purchases_count' => $purchases->purchases_count ?? 0,
            ]
        ];

        foreach ($shops as $index => $shop) {
            $response = $responses[$index];

            // Check if it's a connection exception
            if ($response instanceof ConnectException) {
                Log::error('Shop API connection failed', [
                    'shop' => $shop['name'],
                    'error' => $response->getMessage(),
                ]);
                continue;
            }

            // Now safe to call ->successful()
            if ($response->successful()) {
                $jsonData = $response->json();
                $reports[] = [
                    'name' => $shop['name'],
                    'link' => $shop['link'],
                    'date' => $jsonData['date'],
                    'sales_amount' => $jsonData['sales_amount'] ?? 0,
                    'sales_discount' => $jsonData['sales_discount'] ?? 0,
                    'sales_count' => $jsonData['sales_count'] ?? 0,
                    'purchases_amount' => $jsonData['purchases_amount'] ?? 0,
                    'purchases_discount' => $jsonData['purchases_discount'] ?? 0,
                    'purchases_count' => $jsonData['purchases_count'] ?? 0,
                ];
            } else {
                Log::warning('Shop API returned error', [
                    'shop' => $shop['name'],
                    'status' => $response->status(),
                ]);
            }
        }

        // dd($reports);

        return view('admin.reports.shops.shops_report', compact('reports', 'date'));
    }
}
