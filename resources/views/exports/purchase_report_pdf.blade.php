<!DOCTYPE html>
<html>

<head>
    <title>Purchase Report PDF</title>

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
        }

        .date-range {
            margin-top: 2px;
            font-size: 9px;
            color: #512DA8;
            font-weight: 600;
        }

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
            padding: 8px;
            text-align: center;
        }

        table.report-table tbody td {
            padding: 8px;
            font-size: 8px;
            text-align: center;
            border-bottom: 1px solid #eee;
            vertical-align: top;
        }

        .text-column {
            text-align: left;
            padding-left: 6px;
            white-space: normal;
            word-wrap: break-word;
        }

        .number-column {
            text-align: right;
            padding-right: 6px;
        }

        /* FIXED REMARK WIDTH */
        .remark-column {
            width: 160px;
            text-align: left;
            white-space: normal;
            word-wrap: break-word;
        }

        .footer {
            text-align: center;
            margin-top: 12px;
            font-size: 7px;
            color: #512DA8;
            border-top: 1px solid #DDD;
            padding-top: 6px;
        }
    </style>
</head>

<body>

    <div class="header">
        <h1>PURCHASE REPORT</h1>
        <div class="date-range">
            {{ \Carbon\Carbon::parse($startDate)->format('d-M-Y') }}
            to
            {{ \Carbon\Carbon::parse($endDate)->format('d-M-Y') }}
        </div>
    </div>

    @if ($isItemSummary)
        @php $totalAmount = 0; @endphp

        <table class="report-table">
            <thead>
                <tr>
                    <th style="width:30px;">No</th>
                    <th style="width:80px;">Voucher No</th>
                    <th>Supplier Name</th>
                    <th style="width:85px;">Purchase Date</th>
                    <th style="width:80px;">Expire Date</th>
                    <th>Item Name</th>
                    <th>Category</th>
                    <th style="width:30px;">Unit</th>
                    <th style="width:30px;">Qty</th>
                    <th style="width:80px;">Unit Cost</th>
                    <th style="width:80px;">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rows as $i => $row)
                    @php
                        $lineAmount = $row->unit_cost * $row->quantity;
                        $totalAmount += $lineAmount;
                    @endphp
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $row->purchase_voucher_number }}</td>
                        <td class="text-column">{{ $row->supplier_name }}</td>
                        <td>{{ \Carbon\Carbon::parse($row->purchase_date)->format('d-M-y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($row->expire_date)->format('d-M-y') }}</td>
                        <td class="text-column">{{ $row->item_name }}</td>
                        <td class="text-column">{{ $row->menu_category_name }}</td>
                        <td>{{ $row->unit_name }}</td>
                        <td class="number-column">{{ (int) $row->quantity }}</td>
                        <td class="number-column">{{ (int) $row->unit_cost }}</td>
                        <td class="number-column">{{ (int) $lineAmount }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <table style="width:100%; margin-top:15px; font-size:12px;">
            <tr>
                <td align="right">
                    <table style="max-width:350px; text-align:right;">
                        <tr>
                            <td style="text-align:left; padding-right:20px;">Total Amount</td>
                            <td style="font-weight:600;">{{ number_format($totalAmount) }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    @else
        @php
            $totalAmount = 0;
            $netTotal = 0;
        @endphp


        <table class="report-table">
            <thead>
                <tr>
                    <th style="width:30px;">No</th>
                    <th style="width:80px;">Voucher No</th>
                    <th style="width:160px;">Supplier Name</th>
                    <th style="width:85px;">Purchase Date</th>
                    <th style="width:80px;">Due Date</th>
                    <th style="width:80px;">Total Amount</th>
                    <th style="width:80px;">Transport Charges</th>
                    <th style="width:80px;">Other Charges</th>
                    <th style="width:60px;">Tax</th>
                    <th style="width:80px;">Discount</th>
                    <th style="width:80px;">Paid Amount</th>
                    <th style="width:80px;">Balance</th>
                    <th style="width:80px;">Net Amount</th>
                    <th style="width:80px;">Remark</th>
                </tr>

            </thead>
            <tbody>
                @foreach ($rows as $i => $row)
                    @php
                        $total = (int) ($row->total_amount ?? 0);
                        $transport = (int) ($row->transport_charges ?? 0);
                        $other = (int) ($row->other_charges ?? 0);
                        $tax = (int) ($row->tax ?? 0);
                        $discount = (int) ($row->discount_amount ?? 0);
                        $paid = (int) ($row->paid_amount ?? 0);

                        $net = $total + $transport + $other + $tax - $discount;
                        $balance = $net - $paid;

                        $totalAmount += $total;
                        $netTotal += $net;
                    @endphp
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $row->purchase_voucher_number }}</td>
                        <td class="text-column">{{ $row->supplier_name }}</td>
                        <td>{{ \Carbon\Carbon::parse($row->purchase_date)->format('d-M-y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($row->due_date)->format('d-M-y') }}</td>
                        <td class="number-column">{{ number_format($total) }}</td>
                        <td class="number-column">{{ number_format($transport) }}</td>
                        <td class="number-column">{{ number_format($other) }}</td>
                        <td class="number-column">{{ number_format($tax) }}</td>
                        <td class="number-column">{{ number_format($discount) }}</td>
                        <td class="number-column">{{ number_format($paid) }}</td>
                        <td class="number-column">{{ number_format($balance) }}</td>
                        <td class="number-column">{{ number_format($net) }}</td>
                        <td class="number-column">{{ $row->remark }}</td>
                    </tr>
                @endforeach

            </tbody>
        </table>

        <table style="width:100%; margin-top:15px; font-size:12px;">
            <tr>
                <td align="right">
                    <table style="max-width:350px; text-align:right; padding-right:80px;">
                        <tr>
                            <td style="text-align:left; padding-right:20px;">Total Net Amount</td>
                            <td style="font-weight:600;">{{ number_format((int) $netTotal) }}</td>

                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    @endif

    <div class="footer">
        Generated on {{ \Carbon\Carbon::now()->format('d-M-Y H:i:s') }}
    </div>

</body>

</html>
