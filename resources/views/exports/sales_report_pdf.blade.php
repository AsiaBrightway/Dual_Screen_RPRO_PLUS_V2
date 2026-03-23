<!DOCTYPE html>
<html>
<head>

    <title>Sales Report PDF</title>
    <style>
        @font-face {
            font-family: "notosansmyanmar";
            src: url("{{ storage_path('fonts/NotoSansMyanmar-Regular.ttf') }}") format("truetype");
        }

        @font-face {
            font-family: "dejavusans";
            src: url("{{ storage_path('fonts/dejavu-sans.book.ttf') }}") format("truetype");
        }

        body {
            font-family: 'dejavusans', 'notosansmyanmar', sans-serif;
            font-size: 8.5px;
            margin: 0;
            padding: 6px;
            background: #fff;
            color: #222;
        }

        /* Header */
        .header {
            text-align: center;
            margin-bottom: 6px;
            padding-bottom: 4px;
            border-bottom: 2px solid #512DA8;
        }

        .header h1 {
            margin: 0;
            font-size: 14px;
            color: #512DA8;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .date-range {
            margin-top: 2px;
            font-size: 9px;
            color: #512DA8;
            font-weight: 600;
        }

        /* Shared table styles for both tables */
        table.report-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-top: 8px;
            font-size: 8px;
        }

        table.report-table thead th {
            background-color: #512DA8;
            color: #fff;
            padding: 5px 6px;
            font-size: 8px;
            font-weight: 600;
            text-align: center;
            vertical-align: middle;
            border: 0;
        }

        table.report-table tbody td {
            padding: 4px 6px;
            font-size: 8px;
            vertical-align: middle;
            text-align: center;
            border-bottom: 1px solid rgba(243, 211, 211, 0.2);
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }

        /* Column alignment helpers */
        .text-column {
            text-align: left;
            padding-left: 6px;
        }

        .number-column {
            text-align: right;
            padding-right: 6px;
            white-space: nowrap;
        }

        /* Zebra rows */
        table.report-table tbody tr:nth-child(even) {
            background-color: #faf9fe;
            /* subtle purple-tint */
        }

        /* Item Summary specific small adjustments */
        .item-col-voucher {
            width: 70px;
        }

        .item-col-name {
            width: 220px;
        }

        .item-col-category {
            width: 110px;
        }

        .item-col-unit {
            width: 40px;
        }

        .item-col-number {
            width: 70px;
        }

        /* Sales Summary column widths (19 columns) */
        .col-no {
            width: 24px;
        }

        .col-voucher {
            width: 70px;
        }

        .col-floor {
            width: 40px;
        }

        .col-table {
            width: 50px;
        }

        .col-ord {
            width: 36px;
        }

        .col-customer {
            width: 100px;
        }

        .col-waiter {
            width: 70px;
        }

        .col-cashier {
            width: 70px;
        }

        .col-date {
            width: 56px;
        }

        .col-small-num {
            width: 70px;
        }

        /* reused for money columns */

        /* Totals section - right aligned */
        .total-section {
            width: 100%;
            margin-top: 8px;
            overflow: hidden;
        }

        .total-table {
            float: right;
            border-collapse: collapse;
            font-size: 8px;
            margin: 0;
        }

        .total-table td {
            padding: 4px 10px;
            white-space: nowrap;
        }

        .total-label {
            font-weight: 700;
            color: #512DA8;
            text-align: right;
            padding-right: 8px;
        }

        .total-value {
            font-weight: 700;
            text-align: right;
            border-top: 1px solid #512DA8;
            min-width: 80px;
        }

        /* Footer */
        .footer {
            clear: both;
            text-align: center;
            margin-top: 12px;
            font-size: 7px;
            color: #512DA8;
            border-top: 1px solid #DDD;
            padding-top: 6px;
        }

        /* Prevent very long words from breaking layout in narrow columns */
        td {
            word-break: keep-all;
        }

        /* Ensure small screens or PDF rendering maintain compactness */
        @media print {
            body {
                padding: 4px;
                font-size: 8px;
            }

            .header h1 {
                font-size: 13px;
            }
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>SALES REPORT</h1>
        <div class="date-range">
            {{ \Carbon\Carbon::parse($startDate)->format('d-M-Y') }}
            to
            {{ \Carbon\Carbon::parse($endDate)->format('d-M-Y') }}
        </div>
    </div>

    {{-- ITEM SUMMARY --}}
    @if ($isItemSummary)
        <table class="report-table">
            <thead>
                <tr>
                    <th class="col-no">No</th>
                    <th class="item-col-voucher">Voucher</th>
                    <th class="item-col-name text-column">Item Name</th>
                    <th class="item-col-category text-column">Category</th>
                    <th class="item-col-unit">Unit</th>
                    <th class="item-col-number number-column">Unit Cost</th>
                    <th class="item-col-number number-column">Sale Price</th>
                    <th class="col-ord">Quantity</th>
                    <th class="item-col-number number-column">Amount</th>
                    <th class="col-ord">FOC</th>
                    <th class="col-date">Order Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rows as $i => $row)
                    <tr>
                        <td class="col-no">{{ $i + 1 }}</td>
                        <td class="item-col-voucher">{{ $row->sale_voucher_number }}</td>
                        <td class="item-col-name text-column fit-text">{{ $row->item_name }}</td>
                        <td class="item-col-category text-column">{{ $row->menu_category_name }}</td>
                        <td class="item-col-unit">{{ $row->unit_name }}</td>
                        <td class="item-col-number number-column">{{ number_format($row->unit_cost, 2) }}</td>
                        <td class="item-col-number number-column">{{ number_format($row->item_selling_price, 2) }}</td>
                        <td class="col-ord">{{ $row->quantity }}</td>
                        <td class="item-col-number number-column">
                            {{ number_format($row->item_selling_price * $row->quantity, 2) }}
                        </td>
                        <td class="col-ord">{{ $row->is_foc ? 'Yes' : 'No' }}</td>
                        <td class="col-date">{{ \Carbon\Carbon::parse($row->order_time)->format('d-M-y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <table width="100%" style="margin-top: 30px; font-size: 12px;">
            <tr>
                <td align="right">
                    <table style="max-width: 350px; text-align: right;">
                        <tr>
                            <td style="color: #555; text-align:left;">Total Amount</td>
                            <td style="font-weight: 600; color:#555">
                                {{ number_format($totalAmount, 0) }}
                            </td>
                        </tr>
                        <tr>
                            <td style="color: #555; text-align:left;">Total Cost</td>
                            <td style="font-weight: 600; color:#555">
                                {{ number_format($totalCost, 0) }}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <hr style="margin: 8px 0; border-top: 1px dashed #ccc;">
                            </td>
                        </tr>
                        <tr>
                            <td style="color: #5A2CD6; font-weight: 600; text-align:left;">Net Profit</td>
                            <td style="font-weight: 600; color: #5A2CD6;">
                                {{ number_format($totalAmount - $totalCost, 0) }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    @else
        {{-- SALES SUMMARY (19 columns) --}}
        <table class="report-table">
            <thead>
                <tr>
                    <th class="col-no">No</th>
                    <th class="col-voucher">Voucher No</th>
                    <th class="col-floor">Floor</th>
                    <th class="col-table">Table</th>
                    <th class="col-ord">Ord No</th>
                    <th class="col-customer text-column">Customer</th>
                    <th class="col-waiter">Waiter</th>
                    <th class="col-cashier">Cashier</th>
                    <th class="col-date">Date</th>

                    <th class="col-small-num number-column">Total</th>
                    <th class="col-small-num number-column">Item Promo</th>
                    <th class="col-small-num number-column">Voucher Promo</th>
                    <th class="col-small-num number-column">Service Charges</th>
                    <th class="col-small-num number-column">Tax Amount</th>
                    <th class="col-small-num number-column">Net</th>
                    <th class="col-small-num number-column">Paid</th>
                    <th class="col-small-num number-column">Balance</th>
                    <th class="col-small-num number-column">Change</th>
                    <th class="col-small-num number-column">Delivery</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($rows as $i => $row)
                    <tr>
                        <td class="col-no">{{ $i + 1 }}</td>
                        <td class="col-voucher fit-text">{{ $row->sale_voucher_number }}</td>
                        <td class="col-floor fit-text">{{ $row->floor_name }}</td>
                        <td class="col-table fit-text">{{ $row->table_name }}</td>
                        <td class="col-ord">{{ $row->table_order_number }}</td>
                        <td class="col-customer text-column fit-text">{{ $row->customer_name ?: '—' }}</td>
                        <td class="col-waiter fit-text">{{ $row->waiter_name }}</td>
                        <td class="col-cashier fit-text">{{ $row->cashier_name }}</td>
                        <td class="col-date">{{ \Carbon\Carbon::parse($row->order_date)->format('d-M-y') }}
                        </td>

                        <td class="col-small-num number-column">{{ number_format($row->total_amount, 2) }}</td>
                        <td class="col-small-num number-column">
                            {{ number_format($row->total_item_promo_amount, 2) }}
                        </td>
                        <td class="col-small-num number-column">
                            {{ number_format($row->voucher_discount_amount ?? ($row->total_voucher_promo_amount ?? 0), 2) }}
                        </td>
                        <td class="col-small-num number-column">
                            {{ number_format($row->service_charges_amount, 2) }}
                        </td>
                        <td class="col-small-num number-column">{{ number_format($row->tax_amount, 2) }}</td>
                        <td class="col-small-num number-column">{{ number_format($row->net_amount, 2) }}</td>
                        <td class="col-small-num number-column">{{ number_format($row->paid_amount, 2) }}</td>
                        <td class="col-small-num number-column">{{ number_format($row->balance_amount, 2) }}
                        </td>
                        <td class="col-small-num number-column">{{ number_format($row->change_amount, 2) }}
                        </td>
                        <td class="col-small-num number-column">{{ number_format($row->delivery_charges, 2) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{-- <div class="summary-section mt-3">

            <!-- Top Left Items -->
            <div>
                <p>Total Amount: <strong>{{ $totalAmount }}</strong></p>
                <p>Cash Payment: <strong>{{ $cashPayment }}</strong></p>
                <p>Online Payment: <strong>{{ $totalOnline }}</strong></p>

                <!-- Row (Service Charges + Total Net Amount) -->
                <div class="d-flex justify-content-between">
                    <p>Service Charges: <strong>{{ $totalServiceCharges }}</strong></p>

                    <p style="min-width: 250px; text-align: right;">
                        Total Net Amount: <strong>{{ $totalNetAmount }}</strong>
                    </p>
                </div>

                <!-- Row (Tax + Total Cost) -->
                <div class="d-flex justify-content-between">
                    <p>Tax: <strong>{{ $totalTax }}</strong></p>

                    <p style="min-width: 250px; text-align: right;">
                        Total Cost: <strong>{{ $totalCost }}</strong>
                    </p>
                </div>

                <!-- Row (Discount + Net Profit) -->
                <div class="d-flex justify-content-between pt-2">
                    <p>Discount: <strong>{{ $totalDiscount }}</strong></p>

                    <p style="min-width: 250px; text-align: right;">
                        <span class="fw-bold text-primary">Net Profit</span>
                        <strong>{{ $totalNetProfit }}</strong>
                    </p>
                </div>

            </div>
        </div> --}}
        <div style="font-size:12px;">
            <table width="100%" cellspacing="0" cellpadding="0">
                <tr>
                    <!-- LEFT EMPTY SPACE (reduced from 30% to 25%) -->
                    <td width="25%"></td>

                    <!-- RIGHT CONTENT -->
                    <td width="75%" valign="top">
                        <table width="100%" cellspacing="0" cellpadding="4"
                            style="font-family: dejavusans; table-layout: fixed;">
                            <tr>

                                <!-- LEFT COLUMN -->
                                <td style="width:49%; vertical-align:top; text-align:left;">

                                    <table width="100%" cellspacing="0" cellpadding="4" style="margin-top:20px;">
                                        <tr>
                                            <td style="color:#555; padding-left:195px;">Total Amount</td>
                                            <td style="font-weight:bold; color:#555; text-align:right;">
                                                {{ $totalAmount }}</td>
                                        </tr>
                                        <tr>
                                            <td style="color:#555; padding-left:195px;">Cash Payment</td>
                                            <td style="font-weight:bold; color:#555; text-align:right;">
                                                {{ $cashPayment }}</td>
                                        </tr>
                                        <tr>
                                            <td style="color:#555; padding-left:195px;">Online Payment</td>
                                            <td style="font-weight:bold; color:#555; text-align:right;">
                                                {{ $totalOnline }}</td>
                                        </tr>
                                        <tr>
                                            <td style="color:#555; padding-left:195px;">Service Charges</td>
                                            <td style="font-weight:bold; color:#555; text-align:right;">
                                                {{ $totalServiceCharges }}</td>
                                        </tr>
                                        <tr>
                                            <td style="color:#555; padding-left:195px;">Tax</td>
                                            <td style="font-weight:bold; color:#555; text-align:right;">
                                                {{ $totalTax }}</td>
                                        </tr>
                                        <tr>
                                            <td style="color:#555; padding-left:195px;">Discount</td>
                                            <td style="font-weight:bold; color:#555; text-align:right;">
                                                {{ $totalDiscount }}</td>
                                        </tr>
                                    </table>

                                </td>

                                <td
                                    style="width:49%; vertical-align:top; text-align:left; padding-left:12px; padding-top:50px;">

                                    <table width="100%" cellspacing="0" cellpadding="4">
                                        <tr>
                                            <td style="color:#555; padding-left:125px;">Total Net Amount</td>
                                            <td style="font-weight:bold; color:#555; text-align:right;">
                                                {{ $totalNetAmount }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="color:#555; padding-left:125px;">Total Cost</td>
                                            <td style="font-weight:bold; color:#555; text-align:right;">
                                                {{ $totalCost }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td colspan="2" style="padding-top:6px; padding-bottom:6px;">
                                                <!-- HR stays exactly where it is -->
                                                <hr
                                                    style="border:0; border-top:1px dashed #ccc; width:66%; margin-left:auto;">
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="font-weight:bold; color:#4B2EC8; padding-left:125px;">Net Profit
                                            </td>
                                            <td style="font-weight:bold; color:#4B2EC8; text-align:right;">
                                                {{ $totalNetProfit }}
                                            </td>
                                        </tr>
                                    </table>

                                </td>


                            </tr>
                        </table>
                    </td>
                </tr>

            </table>
        </div>



    @endif

    <div class="footer">
        Generated on {{ \Carbon\Carbon::now()->format('d-M-Y H:i:s') }}
    </div>
</body>

</html>
