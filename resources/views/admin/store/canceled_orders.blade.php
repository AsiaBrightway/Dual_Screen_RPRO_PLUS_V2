@extends('layouts.admin.master')
@section('title', 'Canceled Orders')

@section('content')
<section class="home-section">
    <div class="home-title">
        <i class="bx bx-menu"></i>
        <span class="text">Canceled Orders</span>
    </div>

    <div class="home-content">
        <div class="table_buttons_container mb-3 d-flex flex-wrap justify-content-start justify-content-sm-end align-items-start gap-2" style="margin-right:11px">
            <form action="{{ route('canceledOrders') }}">
                <input type="date" class="form-control" name="filterDate"
                    value="{{ $filterDate }}" onchange="this.form.submit()">
            </form>
        </div>

        <div id="canceled_orders_label" class="row align-items-center bg-white mt-3">
            <div class="col-6">
                <label>
                    <i class="fa-solid fa-table-list" style="padding-left:5px; padding-right: 12px"></i>Canceled Orders
                </label>
            </div>
            <div class="col-6" style="text-align: right">
                <i class="bx bxs-chevron-down arrow"></i>
            </div>
        </div>

        <div class="canceled_orders_container shadow-sm show_container">
            <table id="canceled_orders" class="table table-striped nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Item Name</th>
                        <th>Quantity</th>
                        <th>Order ID</th>        
                        <th>Table</th>
                        <th>Order By</th>
                        <th>Deleted By</th>
                        <th>Deleted Time</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($canceledOrders) != 0)
                    @php $count = 1;
                    @endphp
                    @foreach ($canceledOrders as $order)
                    <tr>
                        <td>{{ $count++ }}</td>
                        <td>{{ $order->item_name }}</td>
                        <td>{{ $order->quantity }}</td>
                        <td>{{ $order->order_id }}</td>                  
                        <td>{{ $order->table_name }}</td>
                        <td>{{ $order->ordered_by_name }}</td>
                        <td>{{ $order->deleted_by_name }}</td>
                        <td>{{ \Carbon\Carbon::parse($order->created_at)->format('h:i A') }}</td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</section>
<script src="{{ asset('script/links_js/jquery.3.6.4.min.js') }}"></script>
<script src="{{ asset('script/links_js/jquery.validate.1.19.5.js') }}"></script>
<script src="{{ asset('script/links_js/jquery.dataTables.1.13.7.min.js') }}"></script>
<script src="{{ asset('script/links_js/dataTables.bootstrap5_1.13.7.min.js') }}"></script>
<script src="{{ asset('script/canceled_orders_script.js') }}"></script>
@endsection