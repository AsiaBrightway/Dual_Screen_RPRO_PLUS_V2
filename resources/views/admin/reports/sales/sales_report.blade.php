@extends('layouts.admin.master')
@section('title', 'Sales Reports')
<link rel="stylesheet" href="{{ asset('css/links_css/dataTable.2.0.8.css') }}">
<link rel="stylesheet" href="{{ asset('css/links_css/buttons.dataTables.3.0.2.css') }}">
<link rel="stylesheet" href="{{ asset('css/links_css/select2.min.css') }}">

@section('content')
    <style>
        /* Remove extra top margin/padding in dropdown */
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

        .tooltip-inner {
            background-color: #512DA8 !important;
        }

        .tooltip .tooltip-arrow::before {
            border-top-color: #512DA8 !important;
        }

        .hover-bg-light:hover {
            background-color: #f8f9fa;
        }

        .bg-light-danger {
            background-color: rgba(220, 53, 69, 0.05);
        }

        .loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 1);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 100;
        }

        .sales_table {
            position: relative;
            overflow-x: hidden
        }
    </style>
    <section class="home-section">
        <div class="home-title">
            <i class='bx bx-menu'></i>
            <span class="text">Sales Reports</span>
        </div>
        <div class="home-content" style="margin-left: 20px">
            <div class="table_buttons_container mb-3"
                style="display: flex; justify-content:end; gap:5px; margin-right:11px">
                <button class="btn btn-primary" id="btn_itemSummary_print"><i class="fa-solid fa-print"></i> Print</button>
                <a href="{{ route('sales.export.excel') }}" class="btn btn-success" id="excel_export"><i
                        class="fa-solid fa-file-excel"></i> Excel</a>
                {{-- <button class="btn btn-success" id="btn_itemSummary_excel"><i class="fa-solid fa-file-excel"></i>
                    Excel</button> --}}
                <a href="{{ route('sales.export.pdf', request()->all()) }}" class="btn btn-danger" id="pdf_export">
                    <i class="fa-solid fa-file-pdf"></i> PDF
                </a>
                {{-- <button class="btn btn-danger" id="btn_itemSummary_pdf"><i class="fa-solid fa-file-pdf"></i> PDF</button> --}}
            </div>
            <div class="sales_report_container row">
                <div class="col-3 sales_filter sale-report-left">
                    <div class="border border-1 p-3 rounded-2 mt-3">
                        <label class="mb-3" style="color: #512da8">Search By Date:</label>
                        {{-- <div class="mb-3 ms-3">
                                <label class="form-label">Start Date & Time</label>
                                <input type="datetime-local" class="form-control" id="startDate" name="startDate">
                            </div>

                            <div class="mb-3 ms-3">
                                <label class="form-label">End Date & Time</label>
                                <input type="datetime-local" class="form-control" id="endDate" name="endDate">
                            </div> --}}
                        <div class="mb-3 ms-3">
                            <label class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="startDate" name="startDate">
                        </div>
                        <div class="mb-3 ms-3">
                            <label class="form-label">End Date</label>
                            <input type="date" class="form-control" id="endDate" name="endDate">
                        </div>
                    </div>
                    <div class="border border-1 p-3 mt-3 rounded-2 checkByFOC">
                        <label class="mb-3" style="color: #512da8">Search By FOC</label>
                        <div class="form-check mb-3 mx-3">
                            <input class="form-check-input" type="checkbox" id="byFOCCheck" name="byFOCCheck">
                            <label class="form-check-label" for="byFOCCheck">By FOC Summary</label>
                        </div>
                        <div class="form-check mb-3 mx-3">
                            <input class="form-check-input" type="checkbox" id="byDiscountCheck" name="byDiscountCheck">
                            <label class="form-check-label" for="byDiscountCheck">By Discount Summary</label>
                        </div>
                        <div class="form-check mb-3 mx-3">
                            <input class="form-check-input" type="checkbox" id="byKPayCheck" name="byKPayCheck">
                            <label class="form-check-label" for="byKPayCheck">By KPay Summary</label>
                        </div>
                        <div class="form-check mb-3 mx-3">
                            <input class="form-check-input" type="checkbox" id="byDeletedCheck" name="byDeletedCheck">
                            <label class="form-check-label" for="byDeletedCheck">By Deleted Summary</label>
                        </div>
                    </div>
                    <div class="border border-1 p-3 mt-3 rounded-2 checkItemSummary">
                        <label class="mb-3" style="color: #512da8">Search By Item Summary:</label>
                        <div class="form-check mb-3 mx-3">
                            <input class="form-check-input" type="checkbox" id="byItemSummaryCheck"
                                name="byItemSummaryCheck">
                            <label class="form-check-label" for="byItemSummaryCheck">By Item Summary</label>
                        </div>
                        <div class="searchCategoryCheck_div form-check mb-3 ms-5 d-none">
                            <input class="form-check-input" type="checkbox" name="bySearchCategory" id="bySearchCategory">
                            <label class="form-check-label" for="bySearchCategory">By Category</label>
                        </div>
                        <div class="searchItemCheck_div form-check mb-3 ms-5 d-none">
                            <input class="form-check-input" type="checkbox" name="bySearchStockItem" id="bySearchStockItem">
                            <label class="form-check-label" for="bySearchStockItem">By Stock Item</label>
                        </div>
                        <div class="selectCategory_div mx-3 d-none">
                            <select class="form-select form-select-sm" id="categoryList" name="categoryList">
                                {{-- <option value="0">--Select Category</option> --}}
                            </select>
                        </div>
                        <div class="selectItem_div mx-3 d-none">
                            <select class="form-select form-select-sm" id="stockItemList" name="stockItemList">
                                {{-- <option value="0">--Select Item</option> --}}
                            </select>
                        </div>
                        <div class="selectEmployee_div mx-3 d-none">
                            <select class="form-select form-select-sm" id="employeeList" name="employeeList">
                                {{-- <option value="0">--Select Item</option> --}}
                            </select>
                        </div>
                    </div>
                    <div style="display: flex; justify-content:end">
                        <button type="submit" class="btn custom_btn" id="btn_sales_search"
                            name="-">Search</button>
                    </div>
                </div>
                <div class="col-9 sale-report-right">
                    <div class="sales_table p-3 pb-1">
                        <div class="loading-overlay">
                            <span>Loading...</span>
                        </div>
                        <div class="report_by_default_container">
                            <table id="sales_report_list_by_default" class="display nowrap" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Voucher No:</th>
                                        <th>Floor Name</th>
                                        <th>Table Name</th>
                                        <th>Table Order No:</th>                                       
                                        <th>Total Amount</th>
                                        <th>Item Promo</th>
                                        <th>Voucher Promo </th>
                                        <th>Service</th>
                                        <th>Tax</th>
                                        <th>Net Amount</th>
                                        <th>Cash</th>
                                        <th>Online</th>
                                        <th>Balance</th>
                                        <th>Change</th>
                                        <th>Customer Name</th>
                                        <th>Waiter Name</th>
                                        <th>Cashier Name</th>
                                        <th>Order Date</th>
                                        {{-- <th>Delivery Charges</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalAmount = 0;
                                        $totalPromo = 0;
                                        $totalCashPayment = 0;
                                        $totalOnlinePayment = 0;
                                        $totalService = 0;
                                        $totalTax = 0;
                                        $totalNetAmount = 0;
                                    @endphp
                                    @if (count($sales) != 0)
                                        @php
                                            $count = 1;
                                            // dd($sales);
                                        @endphp
                                        @foreach ($sales as $sale)
                                            <tr>
                                                <td>{{ $count }}</td>
                                                <td>{{ $sale['sale_voucher_number'] }}</td>
                                                <td>{{ $sale['floor_name'] }}</td>
                                                <td>{{ $sale['table_name'] }}</td>
                                                <td>{{ $sale['table_order_number'] }}</td>                                              
                                                <td style="text-align:end; padding-right:25px">{{ $sale['total_amount'] }}
                                                </td>
                                                @if ($sale['total_item_promo_amount'] != null)
                                                    <td style="text-align:end; padding-right:25px">
                                                        {{ abs($sale['total_item_promo_amount']) }}
                                                        @php
                                                            $totalPromo += abs($sale['total_item_promo_amount']);
                                                        @endphp
                                                    </td>
                                                @else
                                                    <td style="text-align:end; padding-right:25px">
                                                        @php
                                                            $totalPromo += 0;
                                                        @endphp
                                                    </td>
                                                @endif
                                                {{-- add voucher promotion --}}
                                                @if ($sale['voucher_discount_amount'] != null)
                                                    <td style="text-align:end; padding-right:25px">
                                                        {{ $sale['voucher_discount_amount'] }}
                                                        @php
                                                            $totalPromo += $sale['voucher_discount_amount'];
                                                        @endphp
                                                    </td>
                                                @else
                                                    <td style="text-align:end; padding-right:25px">
                                                        @php
                                                            $totalPromo += 0;
                                                        @endphp
                                                    </td>
                                                @endif
                                                <td style="text-align:end; padding-right:25px">
                                                    {{ $sale['service_charges_amount'] != null ? $sale['service_charges_amount'] : 0 }}
                                                </td>
                                                <td style="text-align:end; padding-right:25px">
                                                    {{ $sale['tax_amount'] != null ? $sale['tax_amount'] : 0 }}</td>
                                                <td style="text-align:end; padding-right:25px">{{ $sale['net_amount'] }}
                                                </td>
                                                <td style="text-align:end; padding-right:25px">{{ $sale['paid_amount'] }}
                                                </td>
                                                <td style="text-align:end; padding-right:25px">{{ $sale['online_paid'] }}
                                                </td>
                                                <td style="text-align:end; padding-right:25px">
                                                    {{ $sale['balance_amount'] }}</td>
                                                <td style="text-align:end; padding-right:25px">
                                                    {{ $sale['change_amount'] }}
                                                </td>
                                                <td>{{ $sale['customer_name'] ?? '-----' }}</td>
                                                <td>{{ $sale['waiter_name'] ?? '-----' }}</td>
                                                <td data-toggle="tooltip" title="{{ $sale['cashier_name'] }}"
                                                    style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 130px">
                                                    {{ $sale['cashier_name'] }}</td>
                                                <td>{{ date('d-M-y', strtotime($sale['order_date'])) }}</td>
                                                {{-- <td style="text-align:end; padding-right:25px">
                                                    {{ $sale['delivery_charges'] }}</td> --}}
                                            </tr>
                                            @php
                                                $count++;
                                                $totalAmount += $sale['total_amount'];
                                                $totalOnlinePayment += $sale['online_paid'];
                                                $totalCashPayment += $sale['paid_amount'];
                                                $totalService += $sale['service_charges_amount'];
                                                $totalTax += $sale['tax_amount'];
                                                $totalNetAmount += $sale['net_amount'];
                                            @endphp
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        <div class="report_by_search_container d-none">
                            <table id="sales_report_list_by_search" class="display nowrap" style="width:100%;">
                                <thead class="table_header">
                                    <tr>
                                        <th>No</th>
                                        <th>Purchase Date</th>
                                        <th>Purchase Voucher</th>
                                        <th>Supplier</th>
                                        <th>Item Name</th>
                                        <th>Category</th>
                                        <th>Unit</th>
                                        <th>Qty</th>
                                        <th>Unit Cost</th>
                                        <th>Amount</th>
                                        <th>Expire Date</th>
                                    </tr>
                                </thead>
                                <tbody class="body_data">
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- SALE TOTAL DIV -->
                        <div class="row justify-content-around align-items-end">
                            <div class="col-sm-6 col-md-4 col-lg-5">
                                <div id="left-total">
                                    <!-- Total Amount -->
                                    <div
                                        class="d-flex justify-content-between align-items-center p-1 rounded hover-bg-light">
                                        <span class="text-secondary">
                                            Total Amount
                                        </span>
                                        <span id="total_amount"
                                            class="fw-bold text-secondary">{{ number_format($totalAmount) }}</span>
                                    </div>

                                    <!-- Total Cash Payment -->
                                    <div
                                        class="d-flex justify-content-between align-items-center p-1 rounded hover-bg-light">
                                        <span class="text-secondary">
                                            Cash Payment
                                        </span>
                                        <span id="total_cash_payment"
                                            class="fw-bold text-secondary">{{ number_format($totalCashPayment) }}</span>
                                    </div>

                                    <!-- Total Online Payment -->
                                    <div
                                        class="d-flex justify-content-between align-items-center p-1 rounded hover-bg-light">
                                        <span class="text-secondary">
                                            Online Payment
                                        </span>
                                        <span id="total_online_payment"
                                            class="fw-bold text-secondary">{{ number_format($totalOnlinePayment) }}</span>
                                    </div>

                                    <!-- Total Service -->
                                    <div
                                        class="d-flex justify-content-between align-items-center p-1 rounded hover-bg-light">
                                        <span class="text-secondary">
                                            Service Charges
                                        </span>
                                        <span id="total_service"
                                            class="fw-bold text-secondary">{{ number_format($totalService) }}</span>
                                    </div>

                                    <!-- Total Tax -->
                                    <div
                                        class="d-flex justify-content-between align-items-center p-1 rounded hover-bg-light">
                                        <span class="text-secondary">
                                            Tax
                                        </span>
                                        <span id="total_tax"
                                            class="fw-bold text-secondary">{{ number_format($totalTax) }}</span>
                                    </div>

                                    <!-- Total Discount -->
                                    <div
                                        class="d-flex justify-content-between align-items-center p-1 rounded hover-bg-light">
                                        <span class="text-secondary">
                                            Discount
                                        </span>
                                        <span id="total_promo"
                                            class="fw-bold text-secondary">{{ number_format($totalPromo) }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-4 col-lg-5">
                                <div class="pb-1 d-flex flex-column justify-content-end">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span id="total_net_text" class="text-secondary">
                                            Total Net Amount
                                        </span>
                                        <span id="total_net_amount" class="fw-bold text-secondary"
                                            style="font-size: 1.1rem;">
                                            {{ number_format($totalNetAmount) }}
                                        </span>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center mb-3 position-relative">
                                        <span class="text-secondary">
                                            Total Cost
                                        </span>
                                        <span class="fw-bold text-secondary" style="font-size: 1.1rem;">
                                            <span id="total_cost">{{ number_format($total_sale_cost) }}</span>
                                        </span>
                                        <!-- Divider Line -->
                                        <div
                                            style="position: absolute; bottom: -10px; left: 0; right: 0; border-bottom: 2px dashed #dee2e6;">
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center pt-2">
                                        <span class="fw-bold" style="color: #512DA8;">
                                            Net Profit
                                        </span>
                                        <span id="total_net_profit" class="fw-bold"
                                            style="color: #512DA8; font-size: 1.1rem;">
                                            {{ number_format($totalNetAmount - $total_sale_cost) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End SALE TOTAL DIV -->
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

    <script src="{{ asset('script/sales_report_script.js') }}"></script>
@endsection
