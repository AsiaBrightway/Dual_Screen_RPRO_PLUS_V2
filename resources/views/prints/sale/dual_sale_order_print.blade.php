<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Slip</title>
    <style>
        /* 1. RESET STYLES */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* 2. PAPER SIZE CONFIGURATION */
        @page {
            size: 48mm auto;
            /* Fixed width, auto height */
            margin: 0;
            /* No margins from browser */
        }

        body {
            font-family: 'Courier New', monospace;
            /* Monospace aligns numbers better */
            width: 48mm;
            padding: 2mm 2mm 2mm 2mm;
            /* Small padding so text doesn't touch edge */
            font-size: 10px;
            /* 10px is standard for 48mm paper */
            line-height: 1.2;
            color: black;
            background: white;
        }

        /* 3. HELPER CLASSES */
        .text-center {
            text-align: center;
        }

        .text-end {
            text-align: right;
        }

        .fw-bold {
            font-weight: bold;
        }

        .d-flex {
            display: flex;
        }

        .justify-between {
            justify-content: space-between;
        }

        .divider {
            border-top: 1px dashed black;
            margin: 5px 0;
            width: 100%;
        }

        .solid-divider {
            border-top: 1px solid black;
            margin: 5px 0;
            width: 100%;
        }

        /* 4. TABLE STYLES */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        /* The item name row */
        .item-row td {
            padding-top: 4px;
        }

        /* The numbers row */
        .details-row td {
            font-size: 9px;
            padding-bottom: 4px;
            border-bottom: 1px dotted #ccc;
            /* Optional light separator */
        }

        .details-row:last-child td {
            border-bottom: none;
            /* Remove border for last item */
        }

        .totals-table td {
            padding: 2px 0;
        }
    </style>
</head>

<body>
    <div class="ticket">
        <div class="text-center fw-bold" style="font-size: 14px; margin-bottom: 3px;">
            {{ $shop_name }}
        </div>
        <div class="text-center fw-bold" style="font-size: 12px; margin-bottom: 5px;">
            {{ $phone }}
        </div>

        <div class="solid-divider"></div>

        <div style="font-size: 9px; font-weight: bold;">
            <div class="d-flex justify-between">
                <span>Invoice: {{ $sale['sale_voucher_number'] }}</span>
                <span>Table: {{ $sale['table_name'] }}</span>
            </div>
            <div class="d-flex justify-between">
                <span>Date: {{ now()->format('d/m/y') }}</span>
                <span>Time: {{ now()->format('h:i A') }}</span>
            </div>
        </div>

        <div class="solid-divider"></div>

        <table>
            <tbody>
                @php
                $totalamount = 0;
                $totalSaleAmount = 0;
                $totalSaleQty = 0;
                $totalPromotionAmount = 0;
                $totalPromotionQty = 0;
                @endphp

                @if (count($saleDetails) != 0)
                @foreach ($saleDetails as $saleDetail)
                <tr class="item-row">
                    <td colspan="2" class="fw-bold">
                        {{ $saleDetail['item_name'] }}
                    </td>
                </tr>
                <tr class="details-row fw-bold">
                    <td>
                        {{ $saleDetail['quantity'] }} x {{ number_format($saleDetail['item_price']) }}
                    </td>
                    <td class="text-end">
                        @if ($saleDetail['is_foc'] == 1)
                        FOC
                        @else
                        {{ number_format($saleDetail['item_price'] * $saleDetail['quantity']) }}
                        @endif
                    </td>
                </tr>

                @php
                if ($saleDetail['is_foc'] == 0) {
                $totalSaleAmount += $saleDetail['item_price'] * $saleDetail['quantity'];
                }
                $totalSaleQty += $saleDetail['quantity'];
                @endphp
                @endforeach
                @endif
            </tbody>
        </table>

        @if (count($promotionSaleDetails) != 0)
        <div class="divider"></div>
        <div style="font-size: 9px; font-weight: bold; margin-bottom: 2px;">Promos:</div>
        <table>
            <tbody>
                @foreach ($promotionSaleDetails as $promotionSaleDetail)
                <tr class="item-row">
                    <td colspan="2" class="fw-bold">{{ $promotionSaleDetail['item_name'] }}</td>
                </tr>
                <tr class="details-row fw-bold">
                    <td>
                        {{ $promotionSaleDetail['quantity'] }} x {{ number_format($promotionSaleDetail['promotion_price']) }}
                    </td>
                    <td class="text-end">
                        @if ($promotionSaleDetail['is_foc'] == 1)
                        0
                        @else
                        {{ number_format($promotionSaleDetail['promotion_price'] * $promotionSaleDetail['quantity']) }}
                        @endif
                    </td>
                </tr>
                @php
                if ($promotionSaleDetail['is_foc'] == 0) {
                $totalPromotionAmount += $promotionSaleDetail['promotion_price'] * $promotionSaleDetail['quantity'];
                }
                $totalPromotionQty += $promotionSaleDetail['quantity'];
                @endphp
                @endforeach
            </tbody>
        </table>
        @endif

        <div class="divider"></div>

        @php
        $voucher_discount_amount = $sale['voucher_discount_amount'] ?? 0;
        $member_card_amount = $sale['member_card_amount'] ?? 0;
        $coupon_card_amount = $sale['coupon_card_amount'] ?? 0;
        $totalDiscount = $voucher_discount_amount + $member_card_amount + $coupon_card_amount;
        $totalAmount = $totalSaleAmount + $totalPromotionAmount;
        $serviceCharges = $sale['service_charges_amount'] ?? 0;
        $tax = $sale['tax_amount'] ?? 0;
        $netAmount = $totalAmount + $serviceCharges + $tax - $totalDiscount;
        $onlinePaid = $sale['online_paid'] ?? 0;
        $cashPaid = $sale['paid_amount'] ?? 0;
        $paidAmount = $onlinePaid + $cashPaid;
        $change = $paidAmount - $netAmount;
        if ($change < 0) $change=0;
            @endphp

            <table class="totals-table" style="font-weight: bold;">
            <tr>
                <td>Total</td>
                <td class="text-end">{{ number_format($totalAmount) }}</td>
            </tr>
            @if(($sale['service_charges_amount'] ?? 0) > 0)
            <tr>
                <td>Service</td>
                <td class="text-end">{{ number_format($sale['service_charges_amount']) }}</td>
            </tr>
            @endif
            @if(($sale['tax_amount'] ?? 0) > 0)
            <tr>
                <td>Tax</td>
                <td class="text-end">{{ number_format($sale['tax_amount']) }}</td>
            </tr>
            @endif
            @if($totalDiscount > 0)
            <tr>
                <td>Discount</td>
                <td class="text-end">{{ number_format($totalDiscount) }}</td>
            </tr>
            @endif
            </table>

            <div class="divider"></div>

            <table class="totals-table" style="font-size: 12px; font-weight: bold;">
                <tr>
                    <td>Net Total</td>
                    <td class="text-end">{{ number_format($netAmount) }}</td>
                </tr>
            </table>

            <table class="totals-table fw-bold">
                @if($cashPaid > 0)
                    <tr>
                        <td>Cash</td>
                        <td class="text-end">{{ number_format($cashPaid) }}</td>
                    </tr>
                @endif
                
                @if($onlinePaid > 0)
                    <tr>
                        <td>{{ $sale['payment_type_name'] }}</td>
                        <td class="text-end">{{ number_format($onlinePaid) }}</td>
                    </tr>
                @endif
                
                <tr>
                    <td>Change</td>
                    <td class="text-end">{{ number_format($change) }}</td>
                </tr>
            </table>

            <div class="solid-divider"></div>
            <div class="text-center fw-bold" style="margin-top: 5px; font-size: 11px;">
                Thank You
            </div>
    </div>

    <script src="{{ asset('script/links_js/jquery.3.6.4.min.js') }}"></script>
    <script>
        let isPrinting = false;

        window.onload = function() {
            isPrinting = true;
            window.print();
        };

        window.onafterprint = function() {
            setTimeout(function() {
                redirectAndClose();
            }, 1000);
        };

        window.onfocus = function() {
            if (!isPrinting) return;
            setTimeout(function() {
                redirectAndClose();
            }, 1000);
        };

        function redirectAndClose() {
            if (window.opener && !window.opener.closed) {
                window.opener.location.href = "{{ route('store#dineInPage') }}";
            }
            window.close();
        }
    </script>
</body>

</html>