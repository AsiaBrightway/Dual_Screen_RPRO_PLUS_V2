<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
        }

        th {
            background: #f0f0f0;
            font-weight: bold;
        }

        tfoot td {
            font-weight: bold;
            text-align: right;
        }
    </style>
</head>

<body>
    <h3 style="text-align:center;">Item Summary ({{ $startDate }} - {{ $endDate }})</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Voucher No</th>
                <th>Item</th>
                <th>Category</th>
                <th>Unit</th>
                <th>Cost</th>
                <th>Price</th>
                <th>Promo</th>
                <th>Qty</th>
                <th>Amount</th>
                <th>FOC</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
            @foreach ($data as $i => $s)
                @php
                    $amt = $s->item_selling_price * $s->quantity;
                    $total += $amt;
                @endphp
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $s->sale_voucher_number }}</td>
                    <td>{{ $s->item_name }}</td>
                    <td>{{ $s->menu_category_name }}</td>
                    <td>{{ $s->unit_name }}</td>
                    <td>{{ number_format($s->unit_cost, 0) }}</td>
                    <td>{{ number_format($s->item_selling_price, 0) }}</td>
                    <td>{{ number_format($s->promotion_price, 0) }}</td>
                    <td>{{ $s->quantity }}</td>
                    <td>{{ number_format($amt, 0) }}</td>
                    <td>{{ $s->is_foc ? 'Yes' : 'No' }}</td>
                    <td>{{ \Carbon\Carbon::parse($s->order_time)->format('d-M-y') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="11">Total Amount</td>
                <td>{{ number_format($total, 0) }}</td>
            </tr>
        </tfoot>
    </table>
</body>

</html>
