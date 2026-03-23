<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="{{ asset('css/links_css/bootstrap.5.3.3.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/links_css/twitter_bootstrap.5.3.0.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/links_css/dataTable.2.0.8.css') }}">
    <title>Sales Report Print</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h2 {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
            font-size: 14px;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        @media print {
            body {
                margin: 0;
            }
        }
    </style>
</head>

<body>
    <h2>Sales Summary Report</h2>

    @if ($isCheckedItemSummary)
        {{-- Item Summary Table --}}
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Voc:</th>
                    <th>Item</th>
                    <th>Category</th>
                    <th>Unit</th>
                    <th>Unit Cost</th>
                    <th>Sale Price</th>
                    <th>Item Promo</th>
                    <th>Qty</th>
                    <th>Amount</th>
                    <th>FOC</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $count = 1;
                    $totalItemAmount = 0;
                @endphp
                @foreach ($sales as $sale)
                    <tr>
                        <td>{{ $count }}</td>
                        <td>{{ $sale['sale_voucher_number'] }}</td>
                        <td>{{ $sale['item_name'] }}</td>
                        <td>{{ $sale['menu_category_name'] }}</td>
                        <td>{{ $sale['unit_name'] }}</td>
                        <td>{{ $sale['unit_cost'] }}</td>
                        <td>{{ $sale['item_selling_price'] }}</td>
                        <td>{{ $sale['promotion_price'] != null ? $sale['item_selling_price'] - $sale['promotion_price'] : 0 }}
                        </td>
                        <td>{{ $sale['quantity'] }}</td>
                        <td>
                            {{ $sale['promotion_price'] != null
                                ? $sale['quantity'] * $sale['promotion_price']
                                : $sale['quantity'] * $sale['item_selling_price'] }}
                        </td>
                        <td>{{ $sale['is_foc'] }}</td>
                        <td style="text-align:start;">{{ date('d-M-y', strtotime($sale['order_time'])) }}</td>
                    </tr>
                    @php
                        $count++;
                        $totalItemAmount +=
                            $sale['promotion_price'] != null
                                ? $sale['quantity'] * $sale['promotion_price']
                                : $sale['quantity'] * $sale['item_selling_price'];
                    @endphp
                @endforeach
            </tbody>
        </table>
    @else
        {{-- Default Sales Report Table (16 columns - excluding Voucher Promo) --}}
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Voc:</th>
                    <th>Floor</th>
                    <th>Table</th>
                    <th>Order</th>
                    <th>Waiter</th>
                    <th>Cashier</th>
                    <th>ODate</th>
                    <th>Total</th>
                    <th>VPromo</th>
                    <th>Service</th>
                    <th>Tax</th>
                    <th>Net</th>
                    <th>Paid</th>
                    <th>Bal</th>
                    <th>Change</th>
                </tr>
            </thead>
            <tbody>
                @php $count = 1; @endphp
                @foreach ($sales as $sale)
                    <tr>
                        <td>{{ $count }}</td>
                        <td>{{ $sale['sale_voucher_number'] }}</td>
                        <td>{{ $sale['floor_name'] }}</td>
                        <td>{{ $sale['table_name'] }}</td>
                        <td>{{ $sale['table_order_number'] }}</td>
                        <td>{{ $sale['waiter_name'] }}</td>
                        <td>{{ $sale['cashier_name'] }}</td>
                        <td style="text-align:start;">{{ date('d-M-y', strtotime($sale['order_date'])) }}</td>
                        <td style="text-align:start;">{{ $sale['total_amount'] }}</td>
                        <td style="text-align:start;">{{ $sale['voucher_discount_amount'] ?? 0 }}</td>
                        <td style="text-align:start;">{{ $sale['service_charges_amount'] }}</td>
                        <td style="text-align:start;">{{ $sale['tax_amount'] }}</td>
                        <td style="text-align:start;">{{ $sale['net_amount'] }}</td>
                        <td style="text-align:start;">{{ $sale['paid_amount'] }}</td>
                        <td style="text-align:start;">{{ $sale['balance_amount'] }}</td>
                        <td style="text-align:start;">{{ $sale['change_amount'] }}</td>
                    </tr>
                    @php $count++; @endphp
                @endforeach
            </tbody>
        </table>
    @endif

    {{-- <div class="totals-section">
            <table class="totals-table">
                <tr>
                    <td>Total Amount:</td>
                    <td>{{ number_format($totalAmount) }}</td>
                </tr>
                <tr>
                    <td>Total Cash Payment:</td>
                    <td>{{ number_format($totalCashPayment) }}</td>
                </tr>
                <tr>
                    <td>Total Online Payment:</td>
                    <td>{{ number_format($totalOnlinePayment) }}</td>
                </tr>
                <tr>
                    <td>Total Service Charges:</td>
                    <td>{{ number_format($totalServiceCharges) }}</td>
                </tr>
                <tr>
                    <td>Total Tax:</td>
                    <td>{{ number_format($totalTax) }}</td>
                </tr>
                <tr>
                    <td>Total Discount:</td>
                    <td>{{ number_format($totalPromo) }}</td>
                </tr>
            </table>
        </div> --}}

    <!-- SALE TOTAL -->
    <div class="row justify-content-around align-items-end" style="font-size: 15px">
        <div class="col-sm-6 col-md-4 col-lg-5">
            @if ($isCheckedItemSummary)
                <div></div>
            @else
                <div id="left-total">
                    <!-- Total Amount -->
                    <div class="d-flex justify-content-between align-items-center p-1 rounded hover-bg-light">
                        <span class="text-secondary">
                            Total Amount
                        </span>
                        <span id="total_amount" class="fw-bold text-secondary">{{ number_format($totalAmount) }}</span>
                    </div>

                    <!-- Total Cash Payment -->
                    <div class="d-flex justify-content-between align-items-center p-1 rounded hover-bg-light">
                        <span class="text-secondary">
                            Cash Payment
                        </span>
                        <span id="total_cash_payment"
                            class="fw-bold text-secondary">{{ number_format($totalCashPayment) }}</span>
                    </div>

                    <!-- Total Online Payment -->
                    <div class="d-flex justify-content-between align-items-center p-1 rounded hover-bg-light">
                        <span class="text-secondary">
                            Online Payment
                        </span>
                        <span id="total_online_payment"
                            class="fw-bold text-secondary">{{ number_format($totalOnlinePayment) }}</span>
                    </div>

                    <!-- Total Service -->
                    <div class="d-flex justify-content-between align-items-center p-1 rounded hover-bg-light">
                        <span class="text-secondary">
                            Service Charges
                        </span>
                        <span id="total_service"
                            class="fw-bold text-secondary">{{ number_format($totalServiceCharges) }}</span>
                    </div>

                    <!-- Total Tax -->
                    <div class="d-flex justify-content-between align-items-center p-1 rounded hover-bg-light">
                        <span class="text-secondary">
                            Tax
                        </span>
                        <span id="total_tax" class="fw-bold text-secondary">{{ number_format($totalTax) }}</span>
                    </div>

                    <!-- Total Discount -->
                    <div class="d-flex justify-content-between align-items-center p-1 rounded hover-bg-light">
                        <span class="text-secondary">
                            Discount
                        </span>
                        <span id="total_promo" class="fw-bold text-secondary">{{ number_format($totalPromo) }}</span>
                    </div>
                </div>
            @endif
        </div>
        <div class="col-sm-6 col-md-4 col-lg-5">
            <div class="pb-1 d-flex flex-column justify-content-end">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    @if ($isCheckedItemSummary)
                        <span id="total_net_text" class="text-secondary">
                            Total Amount
                        </span>
                        <span id="total_net_amount" class="fw-bold text-secondary" style="font-size: 1.1rem;">
                            {{ number_format($totalItemAmount) }}
                        </span>
                    @else
                        <span id="total_net_text" class="text-secondary">
                            Total Net Amount
                        </span>
                        <span id="total_net_amount" class="fw-bold text-secondary" style="font-size: 1.1rem;">
                            {{ number_format($totalNetAmount) }}
                        </span>
                    @endif
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3 position-relative">
                    <span class="text-secondary">
                        Total Cost
                    </span>
                    <span class="fw-bold text-secondary" style="font-size: 1.1rem;">
                        <span id="total_cost">{{ number_format($totalSalesCost) }}</span>
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
                    <span id="total_net_profit" class="fw-bold" style="color: #512DA8; font-size: 1.1rem;">
                        @if ($isCheckedItemSummary)
                            {{ number_format($totalItemAmount - $totalSalesCost) }}
                        @else
                            {{ number_format($totalNetAmount - $totalSalesCost) }}
                        @endif
                    </span>
                </div>
            </div>
        </div>
    </div>

</body>
<script src="{{ asset('script/links_js/jquery.3.6.4.min.js') }}"></script>
<script src="{{ asset('script/links_js/bootstrap.bundle.5.3.3.min.js') }}"></script>
<script src="{{ asset('script/links_js/twitter_bootstrap_bundle.5.3.0.min.js') }}"></script>
<script src="{{ asset('script/links_js/dataTable.2.0.8.js') }}"></script>
<script src="{{ asset('script/links_js/dataTables.bootstrap5_2.0.8.js') }}"></script>
<script>
    window.onload = function() {
        // Trigger the print dialog
        window.print();

        // Close or redirect after a short delay
        setTimeout(function() {
            window.location.href = "/admin/reports/sales";
        }, 10000);

    };
</script>

</html>
