<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Order Slip</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
        }
    </style>
</head>

<body>
    <form action="">
        <div style="display: flex; justify-content: center; width: 100% ; margin-bottom:5px;">
            <span style="font-size: 14px; font-weight:bold;">{{ $shop_name }}</span>
            {{-- <span style="font-size: 12px; font-weight:bold">{{ $phone }}</span> --}}
        </div> {{-- <div style="display: flex; justify-content: center; width: 100% ; margin-bottom:5px "> <span style="font-size: 14px; font-weight:bold">76st, 32st x 33st</span> </div> --}}
        <hr style="margin: 0 0 5px 0; border: 1px solid black;">
        <div style="font-size: 12px; font-weight: bold; line-height: 1.6;"> <!-- Row 1: Invoice left, Date right -->
            <div style="display: flex; justify-content: space-between;"> <span>Invoice:
                    {{ $sale['sale_voucher_number'] }}</span> <span style="margin-right: 24px">Table:
                    {{ $sale['table_name'] }}</span> </div> <!-- Row 2: Table left, Time right -->
            <div style="display: flex; justify-content: space-between;"> <span>Date: {{ now()->format('d-m-Y') }}</span>
                <span style="margin-right: 24px">Time: {{ now()->format('h:i A') }}</span>
            </div>
        </div>
        <table
            style="font-size: 12px; font-weight:bold; width:100%; margin-left:-8px; margin-right:8px; margin-bottom: 0; margin-top:0"
            class="table table-borderless">
            <thead>
                <tr>
                    <th style="width: 120px; text-align:left;">Name</th>
                    <th style="width: 60px;text-align:left; ">Price</th>
                    <th style="text-align:left; text-indent: -20px">Qty</th>
                    <th style="text-align:left; text-indent: 0px">Amount</th>
                </tr>
            </thead>
            <tbody> @php
                $totalamount = 0;
                $totalSaleAmount = 0;
                $totalSaleQty = 0;
                $totalPromotionAmount = 0;
                $totalPromotionQty = 0;
                @endphp @if (count($saleDetails) != 0)
                @foreach ($saleDetails as $saleDetail)
                <tr>
                    <td style="width: 120px">{{ $saleDetail['item_name'] }}</td>
                    <td style="width: 60px; text-indent: -5px;">{{ number_format($saleDetail['item_price']) }} </td>
                    <td style="text-indent: -13px">{{ $saleDetail['quantity'] }}</td>
                    <td class="text-end" style="text-indent: 10px">
                        @if ($saleDetail['is_foc'] == 1)
                        FOC
                        @elseif($saleDetail['is_foc'] == 0)
                        {{ number_format($saleDetail['item_price'] * $saleDetail['quantity']) }}
                        @endif
                    </td>
                </tr> @php
                if ($saleDetail['is_foc'] == 0) {
                $totalSaleAmount += $saleDetail['item_price'] * $saleDetail['quantity'];
                }
                $totalSaleQty += $saleDetail['quantity'];
                @endphp
                @endforeach
                @endif
            </tbody>
        </table>
        <hr style="margin: 0 0 5px 0; border: 1px dashed black;">
        @if (count($promotionSaleDetails) != 0) <label class="form-label"
            style="font-size: .7em; font-weight:bold">Promotions: </label>
        <table
            style="font-size: 12px;font-weight:bold; width:100%; margin-left:-8px; margin-right:8px; margin-bottom: 0; margin-top:0"
            class="table table-borderless">
            <tbody>
                @foreach ($promotionSaleDetails as $promotionSaleDetail)
                <tr>
                    <td style="width: 115px">{{ $promotionSaleDetail['item_name'] }}</td>
                    <td style="width: 60px">{{ number_format($promotionSaleDetail['promotion_price']) }} </td>
                    <td>{{ $promotionSaleDetail['quantity'] }}</td>
                    <td class="text-end" style="text-align: center;">
                        @if ($promotionSaleDetail['is_foc'] == 1)
                        0
                        @elseif($promotionSaleDetail['is_foc'] == 0)
                        {{ number_format($promotionSaleDetail['promotion_price'] * $promotionSaleDetail['quantity']) }}
                        @endif
                    </td>
                </tr> @php
                if ($promotionSaleDetail['is_foc'] == 0) {
                $totalPromotionAmount +=
                $promotionSaleDetail['promotion_price'] * $promotionSaleDetail['quantity'];
                }
                $totalPromotionQty += $promotionSaleDetail['quantity'];
                @endphp
                @endforeach
            </tbody>
        </table>
        <hr style="margin: 0 0 5px 0; border: 1px dashed black;">
        @endif
        <div class="row" style="font-size: 13px; font-weight:bold;">
            <table
                style="font-size: 13px; font-weight:bold; width:100%; margin-left:-8px; margin-right:8px; margin-bottom:0; margin-top:0;"
                class="table table-borderless">
                <tbody>
                    <tr>
                        <td style="width: 120px;"></td>
                        <td style="width: 60px; text-align:left; padding-left:50px;">Total</td>
                        <td style="width: 10px; padding-right: 5px;">:</td>
                        <td style="text-align:right; padding-right:20px;">
                            {{ number_format($totalSaleAmount + $totalPromotionAmount) }}
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td style="text-align:left; padding-left:50px;">Service</td>
                        <td style="width: 10px; padding-right: 5px;">:</td>
                        <td style="text-align:right; padding-right:20px;">
                            {{ number_format($sale['service_charges_amount'] ?? 0) }}
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td style="text-align:left; padding-left:50px;">Tax</td>
                        <td style="width: 10px; padding-right: 5px;">:</td>
                        <td style="text-align:right; padding-right:20px;">
                            {{ number_format($sale['tax_amount'] ?? 0) }}
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td style="text-align:left; padding-left:50px;">Discount</td>
                        <td style="width: 10px; padding-right: 5px;">:</td>
                        <td style="text-align:right; padding-right:20px;"> @php
                            $voucher_discount_amount = $sale['voucher_discount_amount'] ?? 0;
                            $member_card_amount = $sale['member_card_amount'] ?? 0;
                            $coupon_card_amount = $sale['coupon_card_amount'] ?? 0;
                            $totalDiscount = $voucher_discount_amount + $member_card_amount + $coupon_card_amount;
                            $totalAmount = $totalSaleAmount + $totalPromotionAmount;
                            $serviceCharges = $sale['service_charges_amount'] ?? 0;
                            $tax = $sale['tax_amount'] ?? 0;
                            $netAmount = $totalAmount + $serviceCharges + $tax - $totalDiscount;
                            $paidAmount = ($sale['online_paid'] ?? 0) + ($sale['paid_amount'] ?? 0);
                            $change = $paidAmount - $netAmount;
                            if ($change < 0) {
                                $change=0;
                                }
                                @endphp
                                {{ number_format($totalDiscount) }} </td>
                    </tr>
                    <tr>
                        <td colspan="4">
                            <hr style="margin: 3px 0; border: 1px dashed black;">
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td style="font-size: 14px; text-align:left; padding-left:50px;">Net Total</td>
                        <td style="width: 10px; padding-right: 5px;">:</td>
                        <td style="text-align:right; padding-right:20px;">{{ number_format($netAmount) }}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td style="text-align:left; padding-left:50px;">Paid</td>
                        <td style="width: 10px; padding-right: 10px;">:</td>
                        <td style="text-align:right; padding-right:20px;">{{ number_format($paidAmount) }}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td style="text-align:left; padding-left:50px;">Change</td>
                        <td style="width: 10px; padding-right: 5px;">:</td>
                        <td style="text-align:right; padding-right:20px;">{{ number_format($change) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <hr style="margin: 0 0 5px 0;border: 1px solid black;">
        <div style="display: flex; justify-content: center; width: 100%; margin-top:10px">
            <h7>Thank You</h7>
        </div>
    </form>

</body>
<script src="{{ asset('script/links_js/jquery.3.6.4.min.js') }}"></script>

<script src="{{ asset('script/links_js/dataTable.2.0.8.js') }}"></script>
<script src="{{ asset('script/links_js/dataTables.bootstrap5_2.0.8.js') }}"></script>
<script>
    let isPrinting = false;

    window.onload = function() {
        isPrinting = true;
        window.print();
    };

    // Chrome / Edge
    window.onafterprint = function() {
        setTimeout(function() {
            redirectAndClose();
        }, 10000); // 👉 10 seconds wait
    };

    // Firefox fallback
    window.onfocus = function() {
        if (!isPrinting) return;

        setTimeout(function() {
            redirectAndClose();
        }, 10000); // 👉 10 seconds wait
    };

    function redirectAndClose() {
        if (window.opener && !window.opener.closed) {
            window.opener.location.href = "{{ route('store#dineInPage') }}";
        }
        window.close();
    }
</script>

</html>