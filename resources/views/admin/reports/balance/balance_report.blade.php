@extends('layouts.admin.master')
@section('title', 'Balance Reports')
<link rel="stylesheet" href="{{ asset('css/links_css/dataTable.2.0.8.css') }}">
<link rel="stylesheet" href="{{ asset('css/links_css/buttons.dataTables.3.0.2.css') }}">
<link rel="stylesheet" href="{{ asset('css/links_css/select2.min.css') }}">

@section('content')
    <style>
        .sales_report_list_by_default {
            table-layout: fixed;
            width: 100%;
        }

        .sales_report_list_by_default th {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .select2-container .select2-dropdown {
            margin-top: -2px !important;
            padding-top: 0 !important;
        }

        /* Remove space before first option */
        .select2-results {
            padding-top: 0 !important;
        }

        /* Remove search box spacing */
        .select2-search--dropdown {
            display: block;
        }

        /* Move dropdown closer to the select box */
        .select2-container .select2-dropdown {
            margin-top: -6px !important;
            /* adjust this number (-4, -6, -8) */
        }

        .select2-container--open .select2-dropdown--below {
            top: 0 !important;
            margin-top: 0 !important;
        }

        .select2-container--open .select2-dropdown--above {
            top: 100% !important;
            /* Push above dropdown to bottom */
        }
    </style>
    <section class="home-section">
        <div class="home-title">
            <i class='bx bx-menu'></i>
            <span class="text">Stock Balance Reports</span>
        </div>
        <div class="home-content" style="margin-left: 20px">
            <div class="table_buttons_container mb-3"
                style="display: flex; justify-content:end; gap:5px; margin-right:11px">
                <button class="btn btn-primary" id="btn_itemSummary_print"><i class="fa-solid fa-print"></i> Print</button>
                <button class="btn btn-success" id="btn_itemSummary_excel"><i class="fa-solid fa-file-excel"></i>
                    Excel</button>
                <a href="{{ route('reports.balance.export.pdf') }}" id="balance_pdf_export" class="btn btn-danger"
                    target="_blank">
                    <i class="fa-solid fa-file-pdf"></i> PDF
                </a>



            </div>
            <div class="stock_out_report_container row">

                <div class="col-3 stock_out_filter stock-out-report-left">
                    {{-- <form action="{{ route('reports#balance') }}" method="GET"> --}}
                    <div class="border border-1 p-3 rounded-2 mt-3">
                        <label class="mb-3" style="color: #512da8">Search By Date:</label>
                        <div class="mb-3 ms-3">
                            <label class="form-label">Date</label>
                            <input type="date" class="form-control" id="date" name="date">
                        </div>
                    </div>
                    <div class="border border-1 p-3 mt-3 rounded-2">
                        <label class="mb-3" style="color: #512da8">Search By Item Summary:</label>
                        {{-- <div class="form-check mb-3 mx-3">
                                <input class="form-check-input" type="checkbox" id="byItemSummaryCheck"
                                    name="byItemSummaryCheck">
                                <label class="form-check-label" for="byItemSummaryCheck">By Item Summary</label>
                            </div> --}}
                        <div class="searchCategoryCheck_div form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="bySearchCategory" id="bySearchCategory">
                            <label class="form-check-label" for="bySearchCategory">By Category</label>
                        </div>
                        {{-- <div class="searchItemCheck_div form-check mb-3 ms-5 d-none">
                                <input class="form-check-input" type="checkbox" name="bySearchStockItem" id="bySearchStockItem">
                                <label class="form-check-label" for="bySearchStockItem">By Stock Item</label>
                            </div>
                            <div class="searchIssueTypeCheck_div form-check mb-3 ms-5 d-none">
                                <input class="form-check-input" type="checkbox" name="bySearchIssueType" id="bySearchIssueType">
                                <label class="form-check-label" for="bySearchIssueType">By Stock Issue Type</label>
                            </div> --}}
                        <div class="selectCategory_div mx-3 d-none">
                            <select class="form-select form-select-sm" id="categoryList" name="categoryList">
                            </select>
                        </div>
                        {{-- <div class="selectItem_div mx-3 d-none">
                                <select class="form-select form-select-sm" id="stockItemList" name="stockItemList">
                                </select>
                            </div>
                            <div class="selectIssueType_div mx-3 d-none">
                                <select class="form-select form-select-sm" id="issueTypeList" name="issueTypeList">
                                </select>
                            </div> --}}
                    </div>
                    <div style="display: flex; justify-content:end">
                        <button class="btn custom_btn" id="btn_sales_search" name="btn_sales_search">Search</button>
                    </div>
                    {{-- </form> --}}
                </div>


                <div class="col-9 sale-report-right">
                    <div class="sales_table p-3">
                        {{-- <div class="table_buttons_container mb-3" style="display: flex; justify-content:end; gap:5px;">
                                <button class="btn btn-primary" id="btn_itemSummary_print"><i class="fa-solid fa-print"></i> Print</button>
                                <button class="btn btn-success" id="btn_itemSummary_export"><i class="fa-solid fa-file-excel"></i> Excel</button>
                                <button class="btn btn-danger" id="btn_itemSummary_pdf"><i class="fa-solid fa-file-pdf"></i> PDF</button>
                            </div> --}}
                        <div class="report_by_default_container">
                            {{-- <table id="sales_report_list_by_default" class="display nowrap" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Item Name</th>
                                            <th>Cateogry</th>
                                            <th>Unit</th>
                                            <th>Unit Cost</th>
                                            <th>Sale Price</th>
                                            <th>Qty</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $totalAmount = 0;
                                            $totalPromo = 0;
                                            $totalOnlinePayment = 0;
                                        @endphp
                                        @if (count($sales_report_list) != 0)
                                            @php
                                                $count = 1;
                                            @endphp
                                            @foreach ($sales_report_list as $sale)
                                                <tr>
                                                    <td>{{ $count }}</td>
                                                    <td>{{ $sale['item_name'] }}</td>
                                                    <td>{{ $sale['menu_category_name'] }}</td>
                                                    <td>{{ $sale['unit_name'] }}</td>
                                                    <td>{{ $sale['unit_cost'] }}</td>
                                                    <td>{{ $sale['item_selling_price'] }}</td>
                                                    <td>{{ $sale['quantity'] }}</td>
                                                    <td>{{ $sale['unit_cost'] * $sale['quantity'] }}</td>

                                                </tr>
                                                @php
                                                    $count++;
                                                @endphp
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table> --}}
                            <table id="sales_report_list_by_default" class="display nowrap" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th style="width: 250px">Item Name</th>
                                        <th>Category</th>
                                        <th>Unit</th>
                                        <th>Unit Cost</th>
                                        {{-- <th>Batch Number</th> --}}
                                        <th>Sale Price</th>
                                        <th>Purchased</th>
                                        <th>Received</th>
                                        <th>Sold</th>
                                        <th>Issued</th>
                                        <th>Balance</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody class="body_data">
                                    @php $totalAmount = 0; @endphp

                                    @if (count($balance_report_list) != 0)
                                        @php
                                            $count = 1;
                                        @endphp
                                        @foreach ($balance_report_list as $item)
                                            <tr>
                                                <td>{{ $count }}</td>
                                                <td>{{ $item->item_name }}</td>
                                                <td>{{ $item->menu_category_name }}</td>
                                                <td>{{ $item->unit_name }}</td>
                                                <td>{{ (int) $item->weighted_unit_cost }}</td>
                                                {{-- <td>{{ $item->batch_number != 0 ? $item->batch_number : "undefined" }}</td> --}}
                                                <td>{{ number_format($item->sale_price) }}</td>
                                                <td>{{ (int) $item->purchased_qty }}</td>
                                                <td>{{ (int) $item->received_qty }}</td>
                                                <td>{{ (int) $item->sold_qty }}</td>
                                                <td>{{ (int) $item->issued_qty }}</td>
                                                <td>{{ (int) $item->balance_qty }}</td>
                                                <td>{{ number_format($item->amount) }}</td>
                                            </tr>
                                            @php
                                                $totalAmount += (float) $item->amount;
                                                $count++;
                                            @endphp
                                        @endforeach
                                    @endif
                                </tbody>
                                {{-- <tfoot>
                                    <tr>
                                        <td colspan="11" class="text-end"><strong>Total Amount</strong></td>
                                        <td><strong>{{ number_format($totalAmount, 2) }}</strong></td>
                                    </tr>
                                </tfoot> --}}
                            </table>
                        </div>
                    </div>
                    <div class="sale_total_div row">
                        <div class="col-12 col-md-4">
                            <div class="form-group">
                                <label class="form-label">Total Cost:</label>
                                <input class="form-control muted text-end" type="text" id="total_cost" name="total_cost"
                                    value="{{ number_format($totalAmount) }}" readonly>
                            </div>
                        </div>
                        {{-- <div class="form-group">
                                <label class="form-label">Total Amount:</label>
                                <input class="form-control muted text-end" type="text" id="total_amount"
                                    name="total_amount" value="{{ number_format($totalAmount) }}" readonly>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Total Online Payment:</label>
                                <input class="form-control muted text-end" type="text" id="total_online_payment"
                                    name="total_online_payment" value="{{ number_format($totalOnlinePayment) }}" readonly>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Total Discount:</label>
                                <input class="form-control muted text-end" type="text" id="total_promo"
                                    name="total_promo" value="{{ number_format($totalPromo) }}" readonly>
                            </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="{{ asset('script/links_js/jquery.3.6.4.min.js') }}"></script>
    <script src="{{ asset('script/links_js/jquery.validate.1.19.5.js') }}"></script>
    <script src="{{ asset('script/links_js/jquery.dataTables.1.13.7.min.js') }}"></script>
    <script src="{{ asset('script/links_js/dataTables.bootstrap5_1.13.7.min.js') }}"></script>

    <script src="{{ asset('script/links_js/jquery.table2excel.1.1.0.min.js') }}"></script>
    <script src="{{ asset('script/links_js/jspdf.umd.min.js') }}"></script>
    <script src="{{ asset('script/links_js/tableExport.1.30.0.min.js') }}"></script>
    <script src="{{ asset('script/links_js/select2.min.js') }}"></script>
    <script src="{{ asset('script/printThis.js') }}"></script>

    <script src="{{ asset('script/balance_report_script.js') }}"></script>
@endsection
