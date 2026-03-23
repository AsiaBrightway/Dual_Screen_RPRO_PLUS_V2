<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Slip</title>

    <!-- Minimal CSS (best for thermal print) -->
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 2px 0;
        }

        hr {
            border: 1px solid #000;
        }
    </style>
</head>

<body>

    <form style="width:260px; margin:0 auto;">

        <!-- HEADER -->
        <div style="display:flex; flex-direction: column; align-items: center; justify-content:center; margin-bottom:5px; gap: 3px;">
            <span style="font-size:14px; font-weight:bold;">
                {{ $shop_name }}
            </span>
            <span style="font-size:12px; font-weight:bold;">
                {{ $phone }}
            </span>
        </div>

        <hr style="margin:0 0 5px 0;">

        <!-- INFO -->
        <div style="font-size:12px; font-weight:bold; line-height:1.6;">
            <div style="display:flex; justify-content:space-between;">
                <span>Invoice: {{ $invoiceNumber }}</span>
                <span>Table: {{ $order['table_name'] }}</span>
            </div>

            <div style="display:flex; justify-content:space-between;">
                <span>Date: {{ now()->format('d-m-Y') }}</span>
                <span>Time: {{ now()->format('h:i A') }}</span>
            </div>
        </div>

        <hr style="margin:5px 0; border: 1px dashed black;">

        <!-- ITEMS TABLE -->
        <table style="font-size:12px; font-weight:bold;">
            <thead>
                <tr>
                    <th style="width:110px; text-align:left;">Name</th>
                    <th style="width:55px; text-align:right;">Price</th>
                    <th style="width:30px; text-align:right;">Qty</th>
                    <th style="width:65px; text-align:right;">Amount</th>
                </tr>
            </thead>
            <tbody>

                @php
                $totalSaleAmount = 0;
                $totalSaleQty = 0;
                $totalPromotionAmount = 0;
                $totalPromotionQty = 0;
                @endphp

                @foreach ($orderDetails as $orderDetail)
                <tr>
                    <td style="text-align:left;">
                        {{ $orderDetail['item_name'] }}
                    </td>
                    <td style="text-align:right;">
                        {{ number_format($orderDetail['item_price']) }}
                    </td>
                    <td style="text-align:right;">
                        {{ $orderDetail['quantity'] }}
                    </td>
                    <td style="text-align:right;">
                        @if ($orderDetail['is_foc'])
                        FOC
                        @else
                        {{ number_format($orderDetail['item_price'] * $orderDetail['quantity']) }}
                        @endif
                    </td>
                </tr>

                @php
                if (!$orderDetail['is_foc']) {
                $totalSaleAmount += $orderDetail['item_price'] * $orderDetail['quantity'];
                }
                $totalSaleQty += $orderDetail['quantity'];
                @endphp
                @endforeach

            </tbody>
        </table>

        <hr style="margin:5px 0; border: 1px dashed black;">

        <!-- PROMOTIONS -->
        @if (count($promotionOrderDetails))
        <div style="font-size:12px; font-weight:bold; margin-bottom:3px;">
            Promotions
        </div>

        <table style="font-size:12px; font-weight:bold;">
            <tbody>
                @foreach ($promotionOrderDetails as $promotionOrderDetail)
                <tr>
                    <td style="width:110px; text-align:left;">
                        {{ $promotionOrderDetail['item_name'] }}
                    </td>
                    <td style="width:55px; text-align:right;">
                        {{ number_format($promotionOrderDetail['promotion_price']) }}
                    </td>
                    <td style="width:30px; text-align:right;">
                        {{ $promotionOrderDetail['quantity'] }}
                    </td>
                    <td style="width:65px; text-align:right;">
                        @if ($promotionOrderDetail['is_foc'])
                        0
                        @else
                        {{ number_format($promotionOrderDetail['promotion_price'] * $promotionOrderDetail['quantity']) }}
                        @endif
                    </td>
                </tr>

                @php
                if (!$promotionOrderDetail['is_foc']) {
                $totalPromotionAmount +=
                $promotionOrderDetail['promotion_price'] * $promotionOrderDetail['quantity'];
                }
                $totalPromotionQty += $promotionOrderDetail['quantity'];
                @endphp
                @endforeach
            </tbody>
        </table>

        <hr style="margin:5px 0; border: 1px dashed black;">
        @endif

        <!-- TOTAL -->
        <table style="font-size:12px; font-weight:bold;">
            <tr>
                <td style="width:110px;">Total</td>
                <td style="width:55px;"></td>
                <td style="width:30px; text-align:right;">
                    {{ $totalSaleQty + $totalPromotionQty }}
                </td>
                <td style="width:65px; text-align:right;">
                    {{ number_format($totalSaleAmount + $totalPromotionAmount) }}
                </td>
            </tr>
        </table>

        <hr style="margin:5px 0;">

        <!-- FOOTER -->
        <div style="display:flex; justify-content:center; margin-top:8px; font-size:12px;">
            Thank You
        </div>

    </form>
    <script>
        window.onload = function() {
            window.print();

            setTimeout(function() {
                window.location.href = "/admin/dineInPage";
            }, 10000);
        };
    </script>

</body>

</html>