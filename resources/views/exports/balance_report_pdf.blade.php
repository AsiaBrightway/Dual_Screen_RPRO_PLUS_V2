<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Stock Balance Report</title>

    <style>
        body {
            font-family: dejavusans, notosansmyanmar, sans-serif;
            font-size: 8.5px;
            margin: 0;
            padding: 8px;
            color: #222;
        }

        .header {
            text-align: center;
            margin-bottom: 8px;
            padding-bottom: 6px;
            border-bottom: 2px solid #512DA8;
        }

        .header h1 {
            margin: 0;
            font-size: 14px;
            color: #512DA8;
            font-weight: bold;
        }

        .date-text {
            font-size: 9px;
            color: #512DA8;
            margin-top: 2px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            table-layout: fixed;
        }

        thead th {
            background: #512DA8;
            color: #fff;
            padding: 5px 4px;
            font-size: 8px;
            text-align: center;
            border: 1px solid #512DA8;
            word-wrap: break-word;
        }

        tbody td {
            padding: 5px 4px;
            font-size: 8px;
            border: 1px solid #ddd;
            text-align: center;
            vertical-align: top;
            word-wrap: break-word;
        }

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .footer {
            margin-top: 10px;
            text-align: center;
            font-size: 7px;
            color: #512DA8;
            border-top: 1px solid #ddd;
            padding-top: 4px;
        }
    </style>
</head>

<body>

    {{-- ================= HEADER ================= --}}
    <div class="header">
        <h1>STOCK BALANCE REPORT</h1>
        <div class="date-text">
            Date : {{ \Carbon\Carbon::parse($searchDate)->format('d-M-Y') }}
        </div>
    </div>

    {{-- ================= TABLE ================= --}}
    <table>
        <thead>
            <tr>
                <th style="width:28px;">No</th>
                <th>Item Name</th>
                <th>Category</th>
                <th style="width:40px;">Unit</th>
                <th style="width:65px;">Unit Cost</th>
                <th style="width:65px;">Sale Price</th>
                <th style="width:45px;">Purchased</th>
                <th style="width:45px;">Received</th>
                <th style="width:40px;">Sold</th>
                <th style="width:40px;">Issued</th>
                <th style="width:45px;">Balance</th>
                <th style="width:75px;">Amount</th>
            </tr>
        </thead>

        <tbody>
            @if (isset($rows) && count($rows) > 0)
                @foreach ($rows as $i => $r)
                    <tr>
                        <td>{{ $i + 1 }}</td>

                        <td class="text-left">
                            {{ $r->item_name ?? '-' }}
                        </td>

                        <td class="text-left">
                            {{ $r->menu_category_name ?? '-' }}
                        </td>

                        <td>
                            {{ $r->unit_name ?? '-' }}
                        </td>

                        <td class="text-right">
                            {{ number_format($r->weighted_unit_cost ?? 0, 0) }}
                        </td>

                        <td class="text-right">
                            {{ number_format($r->sale_price ?? 0, 0) }}
                        </td>

                        <td>{{ $r->purchased_qty ?? 0 }}</td>
                        <td>{{ $r->received_qty ?? 0 }}</td>
                        <td>{{ $r->sold_qty ?? 0 }}</td>
                        <td>{{ $r->issued_qty ?? 0 }}</td>
                        <td>{{ $r->balance_qty ?? 0 }}</td>

                        <td class="text-right">
                            {{ number_format($r->amount ?? 0, 0) }}
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="12" style="text-align:center; padding:12px;">
                        No data available
                    </td>
                </tr>
            @endif
        </tbody>
    </table>

    {{-- ================= FOOTER ================= --}}
    <div class="footer">
        Generated on {{ \Carbon\Carbon::now()->format('d-M-Y H:i:s') }}
    </div>

</body>

</html>
