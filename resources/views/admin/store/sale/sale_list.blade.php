@extends('layouts.admin.master')
@section('title', 'Sale Lists')

@section('content')
<section class="home-section">
    <div class="home-title">
        <i class='bx bx-menu'></i>
        <span class="text">Sale Lists</span>
    </div>

    <div class="home-content">
        {{-- <div class="table_buttons_container mb-3" style="display: flex; justify-content:end; gap:5px; margin-right:11px">
                <form method="GET" action="{{ route('sale#saleListPage') }}">
        <input type="date" class="form-control" name="dailyPrintDate"
            value="{{ request()->query('dailyPrintDate', now()->format('Y-m-d')) }}"
            onchange="this.form.submit()">
        </form>
        <div>
            <input type="date" class="form-control" id="dailyPrintDate" name="dailyPrintDate" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
        </div>

        <button class="btn btn-primary" id="btn_dailyPrint"><i class="fa-solid fa-print"></i> Daily Sale
            Print</button>
    </div> --}}

    <div class="table_buttons_container mb-3 d-flex flex-wrap justify-content-start justify-content-sm-end align-items-start gap-2" style="margin-right:11px">
        <div class="d-flex flex-column flex-md-row gap-2">
            <form method="GET" action="{{ route('sale#saleListPage') }}">
                <input type="date" class="form-control" id="dailyPrintDate" name="dailyPrintDate"
                    value="{{ request()->query('dailyPrintDate', now()->format('Y-m-d')) }}"
                    onchange="this.form.submit()">
            </form>
        </div>

        <div class="w-auto">
            <button class="btn btn-primary d-flex align-items-center text-nowrap" id="btn_dailyPrint">
                <i class="fa-solid fa-print me-2"></i> Daily Sale Print
            </button>
        </div>
    </div>

    <div id="sale_list_label" class="row align-items-center bg-white mt-3">
        <div class="col-6">
            <label><i class="fa-solid fa-table-list" style="padding-left:5px; padding-right: 12px"></i>Sale
                Lists</label>
        </div>
        <div class="col-6" style="text-align: right">
            <i class="bx bxs-chevron-down arrow"></i>
        </div>
    </div>
    <div class="sale_list_container shadow-sm show_container">
        <table id="sale_list" class="table table-striped nowrap" style="width:100%">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Voucher No:</th>
                    <th>Floor Name</th>
                    <th>Table Name</th>
                    <th>Order No:</th>
                    <th>Total Amount</th>
                    <th>Net Amount</th>
                    <th>Payment Type</th>
                    <th>Online</th>
                    <th>Cash</th>
                    {{-- <th>Balance</th>
                    <th>Change</th>
                    <th>Customer Name</th>
                    <th>Waiter Name</th>
                    <th>Cashier Name</th>
                    <th>Order Date / Time</th>
                    <th>Delivery Charges</th> --}}
                    <th>View</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                @php
                $totalCashPayment = 0;
                $totalOnlinePayment = 0;
                $totalNetAmount = 0;
                @endphp
                @if (count($sales) != 0)
                @php
                $count = 1;
                @endphp
                @foreach ($sales as $sale)
                <tr>
                    <td>{{ $count }}</td>
                    <td>{{ $sale['sale_voucher_number'] }}</td>
                    <td>{{ $sale['floor_name'] }}</td>
                    <td>{{ $sale['table_name'] }}</td>
                    <td>{{ $sale['table_order_number'] }}</td>
                    <td>{{ $sale['total_amount'] }}</td>
                    <td>{{ $sale['net_amount'] }}</td>
                    <td>{{ $sale['payment_type_name'] }}</td>
                    <td>{{ $sale['online_paid'] }}</td>
                    <td>{{ $sale['paid_amount'] }}</td>
                    {{-- <td>{{ $sale['balance_amount'] }}</td>
                    <td>{{ $sale['change_amount'] }}</td>
                    <td>{{ $sale['customer_name'] ?? '-----' }}</td>
                    <td>{{ $sale['waiter_name'] ?? '-----' }}</td>
                    <td>{{ $sale['cashier_name'] }}</td>
                    <td>{{ $sale['order_date'] }}</td>
                    <td>{{ $sale['delivery_charges'] }}</td> --}}
                    <td><a href="{{ route('sale#saleOrderDetails', [$sale['sale_id'], 'date' => $dailyPrintDate]) }}"><i
                                class="fa-solid fa-eye" style="color: green; cursor: pointer;"></i></a>
                    </td>
                    <td><a data-sale_id="{{ $sale['sale_id'] }}"
                            data-sale_voucher_number="{{ $sale['sale_voucher_number'] }}"
                            data-bs-toggle="modal" data-bs-target="#delete_sale_modal"
                            class="delete_sale_modal_dialog"><i class="fa-regular fa-trash-can"
                                style="color: red;cursor: pointer;"></i></a>
                    </td>
                </tr>
                @php
                $count++;
                $totalNetAmount += $sale['net_amount'];
                $totalCashPayment += $sale['paid_amount'];
                $totalOnlinePayment += $sale['online_paid'];
                @endphp
                @endforeach
                @endif
            </tbody>
        </table>
    </div>
    <div class="sale_list_total_div p-2 bg-white shadow-sm mt-4 flex-column flex-md-row">
        <div class="form-group">
            <label class="form-label">Total Net Amount</label>
            <input class="form-control muted text-start" type="text" id="total_quantity"
                value="{{ $totalNetAmount }}" readonly>
        </div>
        <div class="form-group">
            <label class="form-label">Total Cash Payment</label>
            <input class="form-control muted text-start" type="text" id="total_quantity"
                value="{{ $totalCashPayment }}" readonly>
        </div>
        <div class="form-group">
            <label class="form-label">Total Online Payment</label>
            <input class="form-control muted text-start" type="text" id="total_quantity"
                value="{{ $totalOnlinePayment }}" readonly>
        </div>
    </div>
    <!--Delete Sale Modal -->
    <div class="modal fade" id="delete_sale_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header text-center" style="background-color: #512DA8">
                    <h1 class="modal-title fs-5 w-100" id="delete_modal_header" style="color: white">
                    </h1>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form action="{{ route('sale#delete') }}" id="saleDeleteModalForm" method="POST">
                    @csrf
                    <div class="modal-body" style="margin-left: 20px; margin-right:20px">
                        <input type="text" name="delete_sale_id" id="delete_sale_id" hidden>
                        <div class="row align-items-center mb-3 mt-1">
                            <div>
                                <label class="form-label">Delete Reason</label>
                                <textarea class="form-control" id="sale_delete_reason" name="sale_delete_reason" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" style="margin-right: 20px">
                        <input type="submit" class="btn btn-danger" value="Delete">
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>

</section>
<script src="{{ asset('script/links_js/jquery.3.6.4.min.js') }}"></script>
<script src="{{ asset('script/links_js/jquery.validate.1.19.5.js') }}"></script>
<script src="{{ asset('script/links_js/jquery.dataTables.1.13.7.min.js') }}"></script>
<script src="{{ asset('script/links_js/dataTables.bootstrap5_1.13.7.min.js') }}"></script>
<script src="{{ asset('script/sale_list_script.js') }}"></script>



@endsection