@extends('layouts.admin.master')
@section('title', 'Top Sale Reports')
<link rel="stylesheet" href="{{ asset('css/links_css/dataTable.2.0.8.css') }}">
<link rel="stylesheet" href="{{ asset('css/links_css/buttons.dataTables.3.0.2.css') }}">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

@section('content')
    <style>
        /* FIX: prevent refresh UI shifting / scrollbar damage */
        .home-content {
            overflow-x: hidden !important;
        }

        /* Fix for Datatables forcing overflow and shifting UI */
        table.dataTable {
            width: 100% !important;
        }

        .top_sale_report_list_by_default {
            table-layout: fixed;
            width: 100%;
        }

        .top_sale_report_list_by_default th {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* Make Select2 match Bootstrap input height */
        .select2-container .select2-selection--single {
            height: 38px !important;
            border: 1px solid #ced4da !important;
            border-radius: 6px !important;
            display: flex !important;
            align-items: center !important;
        }

        /* Remove the big white space inside */
        .select2-selection__rendered {
            padding-left: 10px !important;
        }

        .select2-selection__arrow {
            height: 100% !important;
            right: 10px !important;
        }

        .select2-container .select2-dropdown {
            border: 1px solid #ced4da !important;
            border-radius: 6px !important;
        }

        .select2-results__option {
            padding: 6px 10px !important;
        }

        .select2-results__option--highlighted {
            background-color: #512da8 !important;
            color: #fff !important;
        }

        /* Hover effect */
        .select2-results__option:hover {
            background-color: #1348db !important;
        }
    </style>

    <section class="home-section">
        <div class="home-title">
            <i class='bx bx-menu'></i>
            <span class="text">Top Sale Reports</span>
        </div>

        <div class="home-content" style="margin-left: 20px">
            <div class="top_sale_report_container row">
                <!-- LEFT SIDE FILTER -->
                <div class="col-3 top_sale_filter top-sale-report-left">

                    <!-- Search By Date -->
                    <div class="border border-1 p-3 rounded-2 mt-3">
                        <label class="mb-3" style="color: #512da8">Search By Date:</label>
                        <div class="mb-3 ms-3">
                            <label class="form-label">Date</label>
                            <input type="date" name="searchDate" class="form-control" id="searchDate"
                                value="{{ request('searchDate') }}">
                        </div>
                    </div>

                    <!-- Search By Month -->
                    <div class="border border-1 p-3 mt-3 rounded-2">
                        <label class="mb-3" style="color: #512da8">Search by Month:</label>
                        <div class="mb-3 ms-3">
                            <label class="form-label">Month</label>
                            <!-- Visible input -->
                            <input type="text" class="form-control" id="searchMonthDisplay"
                                placeholder="MM-YYYY (eg. Jan-2022)" autocomplete="off">

                            <!-- Hidden real value sent to AJAX -->
                            <input type="hidden" name="searchMonth" id="searchMonth" value="{{ request('searchMonth') }}">

                        </div>
                    </div>

                    <!-- Search By Category -->
                    <div class="border border-1 p-3 mt-3 rounded-2">
                        <label class="mb-3" style="color: #512da8">Search by Category:</label>

                        <div class="mb-3 ms-3">
                            <select name="searchCategoryID" id="categoryList" class="form-control">
                                <option value="0" {{ request('searchCategoryID', 0) == 0 ? 'selected' : '' }}>
                                    All Categories
                                </option>

                                @foreach ($categories as $category)
                                    <option value="{{ $category->category_id }}"
                                        {{ request('searchCategoryID') == $category->category_id ? 'selected' : '' }}>
                                        {{ $category->menu_category_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div style="display: flex; justify-content:end; margin-top: 15px; margin-bottom: 15px;">
                        <button type="button" class="btn custom_btn" id="btn_top_sales_search">Search</button>
                    </div>
                </div>

                <!-- RIGHT SIDE TABLE -->
                <div class="col-9 sale-report-right">
                    <div class="sales_table p-3" style="height: calc(100% - 116px);">
                        <!-- DEFAULT TABLE -->
                        <div class="report_by_default_container">
                            <table id="stock_in_report_list_by_default"
                                class="stock_in_report_list_by_default display nowrap" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>Rank</th>
                                        <th>Item Name</th>
                                        <th>Category</th>
                                        <th>Quantity Sold</th>
                                        <th>Unit</th>
                                        <th>Sale Price</th>
                                        <th>Total Orders</th>
                                        <th>Total Sales</th>
                                    </tr>
                                </thead>
                                <tbody id="default_table_body">
                                    @include('admin.reports.top_sale.top_sale_search', [
                                        'top_sale_items' => $top_sale_items,
                                    ])
                                </tbody>
                            </table>
                        </div>

                        <!-- SEARCH RESULT TABLE -->
                        <div class="report_by_search_container d-none">
                            <table id="stock_in_report_list_by_search" class="display nowrap" style="width:100%;">
                                <thead class="table_header">
                                    <tr>
                                        <th>Rank</th>
                                        <th>Item Name</th>
                                        <th>Category</th>
                                        <th>Quantity Sold</th>
                                        <th>Unit</th>
                                        <th>Sale Price</th>
                                        <th>Total Orders</th>
                                        <th>Total Sales</th>
                                    </tr>
                                </thead>
                                <tbody id="search_table_body">
                                    <!-- AJAX Rendered Content -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- TOTALS -->
                    <div class="sale_total_div mt-3 flex-column flex-md-row">
                        <div class="form-group">
                            <label class="form-label">Total Quantity Sold:</label>
                            <input class="form-control muted text-start" type="text" id="total_quantity"
                                value="{{ number_format($top_sale_items->sum('total_sold_qty')) }}" readonly>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Total Sales Amount:</label>
                            <input class="form-control muted text-start" type="text" id="total_sales_amount"
                                value="{{ number_format($top_sale_items->sum('total_sales_amount'), 2) }}" readonly>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Total Orders:</label>
                            <input class="form-control muted text-start" type="text" id="total_orders_count"
                                value="{{ number_format($top_sale_items->sum('total_orders')) }}" readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- JS LIBRARIES -->
    <script src="{{ asset('script/links_js/jquery.3.6.4.min.js') }}"></script>
    <script src="{{ asset('script/links_js/jquery.validate.1.19.5.js') }}"></script>
    <script src="{{ asset('script/links_js/jquery.dataTables.1.13.7.min.js') }}"></script>
    <script src="{{ asset('script/links_js/dataTables.bootstrap5_1.13.7.min.js') }}"></script>
    <script src="{{ asset('script/links_js/jquery.table2excel.1.1.0.min.js') }}"></script>
    <script src="{{ asset('script/links_js/jspdf.umd.min.js') }}"></script>
    <script src="{{ asset('script/links_js/tableExport.1.30.0.min.js') }}"></script>
    <script src="{{ asset('script/printThis.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


    <script src="{{ asset('script/top_sale_report_script.js') }}"></script>
    <script>
        // Run after page loads
        $(document).ready(function() {

            // 1. FORCE REPORT MENU TO STAY OPEN
            // Add the class to keep sidebar dropdown open
            $('.reports-list').addClass('showMenu');

            // OPTIONAL: Mark Top Sale Reports as active
            $(".reports-submenu a[href*='top_sale']").addClass('active');

            // Month display → focus to show month picker
            $('#searchMonthDisplay').on('focus', function() {
                try {
                    this.type = 'month';
                } catch (e) {}
            });

            // Convert month after user selects
            $('#searchMonthDisplay').on('change blur', function() {

                let val = $(this).val(); // Could be "2025-10" or "Oct-2025"

                this.type = 'text';

                let iso = convertToISO(val); // Convert to YYYY-MM format

                if (iso) {
                    $('#searchMonth').val(iso);

                    // Display format: Oct-2025
                    $(this).val(convertToDisplay(iso));
                } else {
                    $('#searchMonth').val('');
                    $(this).val('');
                }
            });

            // Convert display "Oct-2025" → "2025-10"
            function convertToISO(value) {

                // If already YYYY-MM return
                if (/^\d{4}-\d{2}$/.test(value)) {
                    return value;
                }

                // If display format: Oct-2025
                let months = {
                    Jan: "01",
                    Feb: "02",
                    Mar: "03",
                    Apr: "04",
                    May: "05",
                    Jun: "06",
                    Jul: "07",
                    Aug: "08",
                    Sep: "09",
                    Oct: "10",
                    Nov: "11",
                    Dec: "12"
                };

                let parts = value.split('-');
                if (parts.length === 2) {
                    let month = months[parts[0]];
                    let year = parts[1];
                    if (month && year) {
                        return `${year}-${month}`;
                    }
                }

                return null;
            }

            // Convert ISO "2025-10" → "Oct-2025"
            function convertToDisplay(isoValue) {
                const months = [
                    "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                    "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
                ];

                let parts = isoValue.split('-');
                let year = parts[0];
                let monthIndex = parseInt(parts[1], 10) - 1;

                return `${months[monthIndex]}-${year}`;
            }

            $('#categoryList').select2({
                width: '100%',
                placeholder: "Select Category",
            });

            $('#categoryList').on('change', function() {
                $('#btn_top_sales_search').click();
            });
        });
    </script>

@endsection
