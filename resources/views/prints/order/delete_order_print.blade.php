<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link href="{{ asset('css/links_css/bootstrap.5.3.3.min.css') }}" rel="stylesheet">

    {{-- <title>Cancel Order Slip</title> --}}
</head>

{{-- <body>
    <p style="font-size: 13px; width:100%;  margin-right:8px; margin-bottom: 0px; margin-top:0px; text-align:center; font-weight:bold"> Cancel Order Slip</p>
    <form action="">
        @if (count($kitchenOrderPrintItems) != 0)
			<table
                style="font-size: 13px; width:100%; margin-left:-8px; margin-right:8px; margin-bottom: 0px; margin-top:0px"
                class="table table-borderless">
                        <th style="width:150px;">(Kitchen)</th>
            </table>
			<table
                style="font-size: 13px; width:100%; margin-left:-8px; margin-right:8px; margin-bottom: 0; margin-top:0"
                class="table table-borderless">
                <thead style="font-weight:bold">
                    <tr>
                        <th style="width:150px">{{ now()->format('Y-m-d') }}, {{ now()->format('h:i A') }} ( {{ $order['order_id'] }})</th>
                    </tr>
                </thead>
            </table>
			<table
                style="font-size: 13px; width:100%; margin-left:-8px; margin-right:8px; margin-bottom: 0; margin-top:0"
                class="table table-borderless">
                <thead style="font-weight:bold">
                    <tr>
                        <th style="width:150px">{{ $order['floor_name'] }}, {{ $order['table_name'] }}, {{ $order['table_order_number'] }}</th>
                    </tr>
                </thead>
            </table>
			<table
                style="font-size: 13px; width:100%; margin-left:-8px; margin-right:8px; margin-bottom: 0; margin-top:0"
                class="table table-borderless">
                <thead style="font-weight:bold">
                    <tr>
                        <th style="width:150px">{{ $orderedBy }}</th>
                    </tr>
                </thead>
            </table>
            <hr style="margin: 0 0 0 0; border: 1px solid black;">
            <table
                style="font-size: 13px; width:100%; margin-left:-8px; margin-right:8px; margin-bottom: 0; margin-top:0"
                class="table table-borderless">
                <thead style="font-weight:bold">
                    <tr>
                        <th style="width:150px">Name</th>
                        <th>Unit</th>
                        <th>Qty</th>
                        <th style="width: 150px">Remark</th>
                    </tr>
                </thead>
                <tbody style="font-weight:bold;">
                    @if (count($kitchenOrderPrintItems) != 0)
                        @foreach ($kitchenOrderPrintItems as $kitchenOrderPrintItem)
                            <tr>
                                <td>{{ $kitchenOrderPrintItem['item_name'] }}</td>
                                <td>{{ $kitchenOrderPrintItem['unit_name'] }}</td>
                                <td>{{ $kitchenOrderPrintItem['quantity'] }} </td>
                                <td>{{ $kitchenOrderPrintItem['remark'] }} </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
            <hr style="margin: 5px 0 5px 0; border: 1px solid black;">
        @endif
        @if (count($bbqOrderPrintItems) != 0)
			<table
                style="font-size: 13px; width:100%; margin-left:-8px; margin-right:8px; margin-bottom: 0px; margin-top:0px"
                class="table table-borderless">
                        <th style="width:150px; ">(BBQ)</th>
            </table>
			<table
                style="font-size: 13px; width:100%; margin-left:-8px; margin-right:8px; margin-bottom: 0; margin-top:0"
                class="table table-borderless">
                <thead style="font-weight:bold">
                    <tr>
                        <th style="width:150px">{{ now()->format('Y-m-d') }}, {{ now()->format('h:i A') }} ( {{ $order['order_id'] }})</th>
                    </tr>
                </thead>
            </table>
			<table
                style="font-size: 13px; width:100%; margin-left:-8px; margin-right:8px; margin-bottom: 0; margin-top:0"
                class="table table-borderless">
                <thead style="font-weight:bold">
                    <tr>
                        <th style="width:150px">{{ $order['floor_name'] }}, {{ $order['table_name'] }}, {{ $order['table_order_number'] }}</th>
                    </tr>
                </thead>
            </table>
			<table
                style="font-size: 13px; width:100%; margin-left:-8px; margin-right:8px; margin-bottom: 0; margin-top:0"
                class="table table-borderless">
                <thead style="font-weight:bold">
                    <tr>
                        <th style="width:150px">{{ $orderedBy }}</th>
                    </tr>
                </thead>
            </table>
            <hr style="margin: 0 0 0 0; border: px solid black;">
            <table
                style="font-size: 13px; width:100%; margin-left:-8px; margin-right:8px; margin-bottom: 0; margin-top:0"
                class="table table-borderless">
                <thead style="font-weight:bold">
                    <tr>
                        <th style="width:150px">Name</th>
                        <th>Unit</th>
                        <th>Qty</th>
                        <th style="width: 150px">Remark</th>
                    </tr>
                </thead>
                <tbody style="font-weight:bold;">
                    @if (count($bbqOrderPrintItems) != 0)

                        @foreach ($bbqOrderPrintItems as $bbqOrderPrintItem)
                            <tr>
                                <td>{{ $bbqOrderPrintItem['item_name'] }}</td>
                                <td>{{ $bbqOrderPrintItem['unit_name'] }}</td>
                                <td>{{ $bbqOrderPrintItem['quantity'] }} </td>
                                <td>{{ $bbqOrderPrintItem['remark'] }} </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
            <hr style="margin: 5px 0 5px 0; border: 1px solid black;">
        @endif
        @if (count($beerOrderPrintItems) != 0)
			<table
                style="font-size: 13px; width:100%; margin-left:-8px; margin-right:8px; margin-bottom: 0px; margin-top:0px"
                class="table table-borderless">
                        <th style="width:150px; ">(Bar)</th>
            </table>
			<table
                style="font-size: 13px; width:100%; margin-left:-8px; margin-right:8px; margin-bottom: 0; margin-top:0"
                class="table table-borderless">
                <thead style="font-weight:bold">
                    <tr>
                        <th style="width:150px">{{ now()->format('Y-m-d') }}, {{ now()->format('h:i A') }} ( {{ $order['order_id'] }})</th>
                    </tr>
                </thead>
            </table>
			<table
                style="font-size: 13px; width:100%; margin-left:-8px; margin-right:8px; margin-bottom: 0; margin-top:0"
                class="table table-borderless">
                <thead style="font-weight:bold">
                    <tr>
                        <th style="width:150px">{{ $order['floor_name'] }}, {{ $order['table_name'] }}, {{ $order['table_order_number'] }}</th>
                    </tr>
                </thead>
            </table>
			<table
                style="font-size: 13px; width:100%; margin-left:-8px; margin-right:8px; margin-bottom: 0; margin-top:0"
                class="table table-borderless">
                <thead style="font-weight:bold">
                    <tr>
                        <th style="width:150px">{{ $orderedBy }}</th>
                    </tr>
                </thead>
            </table>
            <hr style="margin: 0 0 0 0; border: 1px solid black;">
            <table
                style="font-size: 13px; width:100%; margin-left:-8px; margin-right:8px; margin-bottom: 0; margin-top:0"
                class="table table-borderless">
                <thead style="font-weight:bold">
                    <tr>
                        <th style="width:150px">Name</th>
                        <th>Unit</th>
                        <th>Qty</th>
                        <th style="width: 150px">Remark</th>
                    </tr>
                </thead>
                <tbody style="font-weight:bold;">
                    @if (count($beerOrderPrintItems) != 0)
                        @foreach ($beerOrderPrintItems as $beerOrderPrintItem)
                            <tr>
                                <td>{{ $beerOrderPrintItem['item_name'] }}</td>
                                <td>{{ $beerOrderPrintItem['unit_name'] }}</td>
                                <td>{{ $beerOrderPrintItem['quantity'] }} </td>
                                <td>{{ $beerOrderPrintItem['remark'] }} </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
            <hr style="margin: 5px 0 5px 0; border: 1px solid black;">
        @endif
        @if (count($otherOrderPrintItems) != 0)
			<table
                style="font-size: 13px; width:100%; margin-left:-8px; margin-right:8px; margin-bottom: 0px; margin-top:0px"
                class="table table-borderless">
                        <th style="width:150px; ">(Counter)</th>
            </table>
			<table
                style="font-size: 13px; width:100%; margin-left:-8px; margin-right:8px; margin-bottom: 0; margin-top:0"
                class="table table-borderless">
                <thead style="font-weight:bold">
                    <tr>
                        <th style="width:150px">{{ now()->format('Y-m-d') }}, {{ now()->format('h:i A') }} ( {{ $order['order_id'] }})</th>
                    </tr>
                </thead>
            </table>
			<table
                style="font-size: 13px; width:100%; margin-left:-8px; margin-right:8px; margin-bottom: 0; margin-top:0"
                class="table table-borderless">
                <thead style="font-weight:bold">
                    <tr>
                        <th style="width:150px">{{ $order['floor_name'] }}, {{ $order['table_name'] }}, {{ $order['table_order_number'] }}</th>
                    </tr>
                </thead>
            </table>
			<table
                style="font-size: 13px; width:100%; margin-left:-8px; margin-right:8px; margin-bottom: 0; margin-top:0"
                class="table table-borderless">
                <thead style="font-weight:bold">
                    <tr>
                        <th style="width:150px">{{ $orderedBy }}</th>
                    </tr>
                </thead>
            </table>
            <hr style="margin: 0 0 0 0; border: 1px solid black;">
            <table
                style="font-size: 13px; width:100%; margin-left:-8px; margin-right:8px; margin-bottom: 0; margin-top:0"
                class="table table-borderless">
                <thead style="font-weight:bold">
                    <tr>
                        <th style="width:150px">Name</th>
                        <th>Unit</th>
                        <th>Qty</th>
                        <th style="width: 150px">Remark</th>
                    </tr>
                </thead>
                <tbody style="font-weight:bold;">
                    @if (count($otherOrderPrintItems) != 0)
                        @foreach ($otherOrderPrintItems as $otherOrderPrintItem)
                            <tr>
                                <td>{{ $otherOrderPrintItem['item_name'] }}</td>
                                <td>{{ $otherOrderPrintItem['unit_name'] }}</td>
                                <td>{{ $otherOrderPrintItem['quantity'] }} </td>
                                <td>{{ $otherOrderPrintItem['remark'] }} </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
            <hr style="margin: 5px 0 5px 0; border: 1px solid black;">
        @endif
        @if (count($noodleOrderPrintItems) != 0)
			<table
                style="font-size: 13px; width:100%; margin-left:-8px; margin-right:8px; margin-bottom: 0px; margin-top:0px"
                class="table table-borderless">
                        <th style="width:150px; ">(Counter2)</th>
            </table>
			<table
                style="font-size: 13px; width:100%; margin-left:-8px; margin-right:8px; margin-bottom: 0; margin-top:0"
                class="table table-borderless">
                <thead style="font-weight:bold">
                    <tr>
                        <th style="width:150px">{{ now()->format('Y-m-d') }}, {{ now()->format('h:i A') }} ( {{ $order['order_id'] }})</th>
                    </tr>
                </thead>
            </table>
			<table
                style="font-size: 13px; width:100%; margin-left:-8px; margin-right:8px; margin-bottom: 0; margin-top:0"
                class="table table-borderless">
                <thead style="font-weight:bold">
                    <tr>
                        <th style="width:150px">{{ $order['floor_name'] }}, {{ $order['table_name'] }}, {{ $order['table_order_number'] }}</th>
                    </tr>
                </thead>
            </table>
			<table
                style="font-size: 13px; width:100%; margin-left:-8px; margin-right:8px; margin-bottom: 0; margin-top:0"
                class="table table-borderless">
                <thead style="font-weight:bold">
                    <tr>
                        <th style="width:150px">{{ $orderedBy }}</th>
                    </tr>
                </thead>
            </table>
            <hr style="margin: 0 0 0 0; border: 1px solid black;">
            <table
                style="font-size: 13px; width:100%; margin-left:-8px; margin-right:8px; margin-bottom: 0; margin-top:0"
                class="table table-borderless">
                <thead style="font-weight:bold">
                    <tr>
                        <th style="width:150px">Name</th>
                        <th>Unit</th>
                        <th>Qty</th>
                        <th style="width: 150px">Remark</th>
                    </tr>
                </thead>
                <tbody style="font-weight:bold;">
                    @if (count($noodleOrderPrintItems) != 0)
                        @foreach ($noodleOrderPrintItems as $noodleOrderPrintItem)
                            <tr>
                                <td>{{ $noodleOrderPrintItem['item_name'] }}</td>
                                <td>{{ $noodleOrderPrintItem['unit_name'] }}</td>
                                <td>{{ $noodleOrderPrintItem['quantity'] }} </td>
                                <td>{{ $noodleOrderPrintItem['remark'] }} </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
            <hr style="margin: 5px 0 5px 0; border: 1px solid black;">
        @endif
        @if (count($cuisineOrderPrintItems) != 0)
			<table
                style="font-size: 13px; width:100%; margin-left:-8px; margin-right:8px; margin-bottom: 0px; margin-top:0px"
                class="table table-borderless">
                        <th style="width:150px; ">(Counter3)</th>
            </table>
			<table
                style="font-size: 13px; width:100%; margin-left:-8px; margin-right:8px; margin-bottom: 0; margin-top:0"
                class="table table-borderless">
                <thead style="font-weight:bold">
                    <tr>
                        <th style="width:150px">{{ now()->format('Y-m-d') }}, {{ now()->format('h:i A') }} ( {{ $order['order_id'] }})</th>
                    </tr>
                </thead>
            </table>
			<table
                style="font-size: 13px; width:100%; margin-left:-8px; margin-right:8px; margin-bottom: 0; margin-top:0"
                class="table table-borderless">
                <thead style="font-weight:bold">
                    <tr>
                        <th style="width:150px">{{ $order['floor_name'] }}, {{ $order['table_name'] }}, {{ $order['table_order_number'] }}</th>
                    </tr>
                </thead>
            </table>
			<table
                style="font-size: 13px; width:100%; margin-left:-8px; margin-right:8px; margin-bottom: 0; margin-top:0"
                class="table table-borderless">
                <thead style="font-weight:bold">
                    <tr>
                        <th style="width:150px">{{ $orderedBy }}</th>
                    </tr>
                </thead>
            </table>
            <hr style="margin: 0 0 0 0; border: 1px solid black;">
            <table
                style="font-size: 13px; width:100%; margin-left:-8px; margin-right:8px; margin-bottom: 0; margin-top:0"
                class="table table-borderless">
                <thead style="font-weight:bold">
                    <tr>
                        <th style="width:150px">Name</th>
                        <th>Unit</th>
                        <th>Qty</th>
                        <th style="width: 150px">Remark</th>
                    </tr>
                </thead>
                <tbody style="font-weight:bold;">
                    @if (count($cuisineOrderPrintItems) != 0)
                        @foreach ($cuisineOrderPrintItems as $cuisineOrderPrintItem)
                            <tr>
                                <td>{{ $cuisineOrderPrintItem['item_name'] }}</td>
                                <td>{{ $cuisineOrderPrintItem['unit_name'] }}</td>
                                <td>{{ $cuisineOrderPrintItem['quantity'] }} </td>
                                <td>{{ $cuisineOrderPrintItem['remark'] }} </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
            <hr style="margin: 5px 0 5px 0; border: 1px solid black;">
        @endif
    </form>

</body> --}}

<body>
    <p style="font-size:13px;text-align:center;font-weight:bold">
        Cancel Order Slip
    </p>

    @foreach ($groupedItems as $category)
        @if (count($category['items']) > 0)
            {{-- Category Name --}}
            <table class="table table-borderless" style="font-size:13px;width:100%;margin-bottom:0">
                <tr>
                    <th>{{ $category['name'] }}</th>
                </tr>
            </table>

            {{-- Date & Order --}}
            <table class="table table-borderless" style="font-size:13px;width:100%;margin-bottom:0">
                <tr>
                    <th>{{ now()->format('Y-m-d h:i A') }} ({{ $order['order_id'] }})</th>
                </tr>
            </table>

            {{-- Floor / Table --}}
            <table class="table table-borderless" style="font-size:13px;width:100%;margin-bottom:0">
                <tr>
                    <th>{{ $order['floor_name'] }}, {{ $order['table_name'] }}, {{ $order['table_order_number'] }}</th>
                </tr>
            </table>

            {{-- Ordered By --}}
            <table class="table table-borderless" style="font-size:13px;width:100%;margin-bottom:0">
                <tr>
                    <th>{{ $orderedBy }}</th>
                </tr>
            </table>

            <hr style="margin:0;border:1px solid black;">

            {{-- Table header --}}
            <table class="table table-borderless" style="font-size:13px;width:100%;margin-bottom:0">
                <thead style="font-weight:bold">
                    <tr>
                        <th>Name</th>
                        <th>Unit</th>
                        <th>Qty</th>
                        <th>Remark</th>
                    </tr>
                </thead>

                <tbody style="font-weight:bold">
                    @foreach ($category['items'] as $item)
                        <tr>
                            <td>{{ $item['item_name'] }}</td>
                            <td>{{ $item['unit_name'] }}</td>
                            <td>{{ $item['quantity'] }}</td>
                            <td>{{ $item['remark'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <hr style="margin:5px 0;border:1px solid black;">
        @endif
    @endforeach

</body>

<script src="{{ asset('script/links_js/jquery.3.6.4.min.js') }}"></script>

<script src="{{ asset('script/links_js/twitter_bootstrap_bundle.5.3.0.min.js') }}"></script>
<script src="{{ asset('script/links_js/dataTable.2.0.8.js') }}"></script>
<script src="{{ asset('script/links_js/dataTables.bootstrap5_2.0.8.js') }}"></script>
<script src="{{ asset('script/links_js/bootstrap.bundle.5.3.3.min.js') }}"></script>
<script>
    window.onload = function() {
        // Trigger print dialog
        window.print();

        // Optionally, close the window/tab after printing
        window.onafterprint = function() {
            window.close();
        };
    };
</script>

</html>
