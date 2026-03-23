<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="{{ asset('css/links_css/bootstrap.5.3.3.min.css') }}" rel="stylesheet">
    <title>Sale Slip</title>
    <style>
        body {
            font-size: 12px;
            font-family: Arial, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 5px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .category-header {
            font-weight: bold;
            text-align: left;
            margin-top: 15px;
            padding: 5px 0;
        }
    </style>
</head>

<body>
    <div style="display: flex; justify-content:end">
        <label style="font-size: 10px; font-weight:bold">Date: {{ $dailyPrintDate }}</label>
    </div>

    @php
        $subTotalQuantity = 0;
        $subTotalPrice = 0;
    @endphp

    @if (count($saleDetailsNestedDatas) != 0)
        @foreach ($saleDetailsNestedDatas as $category)
            <div class="category-header">
                {{ $category['menu_category_name'] }}
            </div>
            <hr style="margin: 0 0 0 0; border: 1px solid black;">
            <table>
                <tbody>
                    @php
                        $totalQuantity = 0;
                        $totalPrice = 0;
                    @endphp
                    @foreach ($category['items'] as $item)
                        <tr>
                            <td style="min-width:150px; max-width:150px">{{ $item['item_name'] }}</td>
                            <td style="min-width:50px; max-width:50px">
                                {{ $item['quantity'] }}
                                @php
                                    $totalQuantity += $item['quantity'];
                                    $subTotalQuantity += $item['quantity'];
                                @endphp
                            </td>
                            <td style="min-width:80px; max-width:80px; text-align:end;">
                                @php
                                    // $price = $item['quantity'] * $item['sale_price'];
                                    $totalPrice += $item['sale_price'];
                                    $subTotalPrice += $item['sale_price'];
                                @endphp
                                Ks {{ number_format($item['sale_price']) }}
                                
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <hr style="margin: 0 0 0 0; border: 1px solid black;">
            <table>
                <tbody>
                    <tr>
                        <td style="min-width:150px; max-width:150px"><strong>Total</strong></td>
                        <td style="min-width:50px; max-width:50px"><strong>{{ $totalQuantity }}</strong></td>
                        <td style="min-width:80px; max-width:80px; text-align:end;">
                            <strong>Ks {{ number_format($totalPrice) }}</strong>
                        </td>
                    </tr>
                </tbody>
            </table>
        @endforeach
    @else
        <div>No sales data found for the selected date.</div>
    @endif

    @if (count($focSaleDetails) != 0)
        <div class="category-header">
            All FOC
        </div>
        <hr style="margin: 0 0 0 0; border: 1px solid black;">
        <table>
            <tbody>
                @php
                    $totalQuantity = 0;
                    $totalPrice = 0;
                @endphp
                @foreach ($focSaleDetails as $focSaleDetail)
                    <tr>
                        <td style="min-width:150px; max-width:150px">{{ $focSaleDetail['item_name'] }}</td>
                        <td style="min-width:50px; max-width:50px">
                            {{ $focSaleDetail['quantity'] }}
                            @php
                                $totalQuantity += $focSaleDetail['quantity'];
                                $subTotalQuantity += $focSaleDetail['quantity'];
                            @endphp
                        </td>
                        <td style="min-width:80px; max-width:80px; text-align:end;">
                            Ks {{ number_format($focSaleDetail['sale_price']) }}
                            @php
                                $totalPrice += $focSaleDetail['sale_price'];
                                // $subTotalPrice += $focSaleDetail['sale_price'];
                            @endphp
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <hr style="margin: 0 0 0 0; border: 1px solid black;">
        <table>
            <tbody>
                <tr>
                    <td style="min-width:150px; max-width:150px"><strong>Total</strong></td>
                    <td style="min-width:50px; max-width:50px"><strong>{{ $totalQuantity }}</strong></td>
                    <td style="min-width:80px; max-width:80px; text-align:end;">
                        <strong>Ks {{ number_format($totalPrice) }}</strong>
                    </td>
                </tr>
            </tbody>
        </table>
    @endif

    <hr style="margin: 20px 0 0 0; border: 1px solid black;">
    <table>
        <tbody>
            <tr>
                <td style="min-width:150px; max-width:150px"><strong>Net Total</strong></td>
                <td style="min-width:50px; max-width:50px"><strong>{{ $subTotalQuantity }}</strong></td>
                <td style="min-width:80px; max-width:80px; text-align:end;">
                    <strong>Ks {{ number_format($subTotalPrice) }}</strong>
                </td>
            </tr>
        </tbody>
    </table>
    <hr style="margin: 0 0 0 0; border: 1px solid black;">

    <script>
        window.onload = function() {
            // Trigger print dialog
            window.print();

            // Optionally, close the window/tab after printing
            window.onafterprint = function() {
                window.close();
            };
            window.close();
        };
    </script>
</body>

</html>
