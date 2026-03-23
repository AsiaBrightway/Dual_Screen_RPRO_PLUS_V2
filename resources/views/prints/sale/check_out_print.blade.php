<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link href="{{ asset('css/links_css/bootstrap.5.3.3.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/links_css/twitter_bootstrap.5.3.0.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/links_css/dataTable.2.0.8.css') }}">
    <title>Order Slip</title>
</head>

<body>
    <form action="">

        <!-- <div style="display: flex; justify-content: center; width: 100%;">
            <h5>"Mercury"</h6>
        </div>
        <div style="display: flex; justify-content: center; width: 100% ; margin-bottom:5px ">
            <span style="font-size: 14px; font-weight:bold">76st, 32st x 33st</span>
        </div>
        <hr style="margin: 0 0 5px 0; border: 1px solid black;">
        <div class="row" style="font-size: 12px; font-weight:bold; margin-top:10px;">
            <div class="col-6">
                <label class="form-label">Invoice: {{ $sale['sale_voucher_number'] }}</label>
            </div>
            <div class="col-6">
                <div class="row">
                    <label class="form-label" style="margin-left: 50px">{{ now()->format('Y-m-d') }}</label>
                </div>
                <div class="row">
                    <label class="form-label" style="margin-left: 60px">{{ now()->format('h:i A') }}</label>
                </div>

            </div>
        </div>
        <table style="font-size: 12px; font-weight:bold; width:100%; margin-left:-8px; margin-right:8px; margin-bottom: 0; margin-top:0"
            class="table table-borderless">
            <thead>
                <tr>
                    <th style="width:120px">Name</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th style="width: 30px">Amount</th>
                </tr>
            </thead>
            <tbody>
                @if (count($saleDetails) != 0)
                    @php
                        $totalAmount = 0;
                        $totalQty = 0;
                    @endphp
                    @foreach ($saleDetails as $saleDetail)
                        <tr>
                            <td>{{ $saleDetail['item_name'] }}</td>
                            <td>{{ $saleDetail['quantity'] }}</td>
                            <td>{{ number_format($saleDetail['item_price']) }} </td>
                            <td class="text-end">
                                @if ($saleDetail['is_foc'] == 1)
                                    0
                                @elseif($saleDetail['is_foc'] == 0)
                                    {{ number_format($saleDetail['item_price'] * $saleDetail['quantity']) }}
                                @endif
                            </td>
                        </tr>
                        @php
                            if ($saleDetail['is_foc'] == 0) {
                                $totalAmount += $saleDetail['item_price'] * $saleDetail['quantity'];
                            }
                            $totalQty += $saleDetail['quantity'];
                        @endphp
                    @endforeach
                @endif
            </tbody>
        </table>
        <hr style="margin: 0 0 5px 0; border: 1px solid black;">
        <div class="row" style="font-size: 12px; font-weight:bold">
            <div class="col-4">
                <label class="form-label">Total</label>
            </div>
            <div class="col-4">
                <label class="form-label" style="margin-left: 17px">{{ $totalQty }}</label>
            </div>
            <div class="col-4 text-end">
                <label class="form-label" style="margin-right:15px">{{ number_format($totalAmount) }} </label>
            </div>
        </div>
        <div class="row" style="font-size: 12px; font-weight:bold">
            <div class="col-4">
                <label class="form-label">Tax</label>
            </div>
            <div class="col-4">
                <label class="form-label"></label>
            </div>
            <div class="col-4 text-end">
                <label class="form-label " style="margin-right: 15px">{{ number_format($sale['tax_amount']) }}</label>
            </div>
        </div>
        <div class="row" style="font-size: 12px; font-weight:bold">
            <div class="col-4">
                <label class="form-label">Service</label>
            </div>
            <div class="col-4">
                <label class="form-label"></label>
            </div>
            <div class="col-4 text-end">
                <label class="form-label "
                    style="margin-right: 15px">{{ number_format($sale['service_charges_amount']) }}</label>
            </div>
        </div>
        <div class="row" style="font-size: 12px; font-weight:bold">
            <div class="col-4">
                <label class="form-label">Discount</label>
            </div>
            <div class="col-4">
                <label class="form-label"></label>
            </div>
            <div class="col-4 text-end">
                @php
                    $voucher_discount_amount = 0;
                    $member_card_amount = 0;
                    $coupon_card_amount = 0;
                    $totalDiscount = 0;
                    $netAmount = 0;
                    if ($sale['voucher_discount_amount'] == null) {
                        $voucher_discount_amount = 0;
                    } else {
                        $voucher_discount_amount = $sale['voucher_discount_amount'];
                    }
                    if ($sale['member_card_amount'] == null) {
                        $member_card_amount = 0;
                    } else {
                        $member_card_amount = $sale['member_card_amount'];
                    }
                    if ($sale['coupon_card_amount'] == null) {
                        $coupon_card_amount = 0;
                    } else {
                        $coupon_card_amount = $sale['coupon_card_amount'];
                    }
                    $totalDiscount = $voucher_discount_amount + $member_card_amount + $coupon_card_amount;

                    $netAmount = $totalAmount + $sale['tax_amount'] + $sale['service_charges_amount'] - $totalDiscount;

                    $paidAmount = $sale['online_paid'] + $sale['paid_amount'];
                @endphp
                <label class="form-label " style="margin-right: 15px">{{ number_format($totalDiscount) }}</label>
            </div>
        </div>
        <hr style="margin: 0 0 5px 0; border: 1px solid black;">
        <div class="row" style="font-size: 12px; font-weight:bold">
            <div class="col-4">
                <label class="form-label">Net Value:</label>
            </div>
            <div class="col-4">
                <label class="form-label"></label>
            </div>
            <div class="col-4 text-end">
                <label class="form-label" style="margin-right: 15px">{{ number_format($netAmount) }}</label>
            </div>
        </div>
        <div class="row" style="font-size: 12px; font-weight:bold">
            <div class="col-4">
                <label class="form-label">Paid:</label>
            </div>
            <div class="col-4">
                <label class="form-label"></label>
            </div>
            <div class="col-4 text-end">
                <label class="form-label" style="margin-right: 15px">{{ number_format($paidAmount) }}</label>
            </div>
        </div>
        <hr style="margin: 0 0 5px 0;border: 1px solid black;">
        <div style="display: flex; justify-content: center; width: 100%; margin-top:10px">
            <h7>Thank You</h7>
        </div> -->
    </form>

</body>
<script src="{{ asset('script/links_js/jquery.3.6.4.min.js') }}"></script>
<script src="{{ asset('script/links_js/bootstrap.bundle.5.3.3.min.js') }}"></script>
<script src="{{ asset('script/links_js/twitter_bootstrap_bundle.5.3.0.min.js') }}"></script>
<script src="{{ asset('script/links_js/dataTable.2.0.8.js') }}"></script>
<script src="{{ asset('script/links_js/dataTables.bootstrap5_2.0.8.js') }}"></script>
<script>
    window.onload = function() {
        window.JSREPORT_CHROME_PDF_OPTIONS = {
            height: (document.body.scrollHeight + 100).toFixed(0) + 'px',
        };
    };
</script>

</html>
