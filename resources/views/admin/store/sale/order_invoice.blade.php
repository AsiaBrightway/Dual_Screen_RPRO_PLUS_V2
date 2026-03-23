@extends('layouts.admin.master')
@section('title', 'Order Invoice')

@section('content')
    <style>
        .custom-title {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .custom-label {
            margin-left: auto;
            margin-right: 25px;
        }

        .custom-text {
            margin-left: 0px;
            /* Adjust margin as needed */
        }

        .col-5 {
            display: flex;
            align-items: center;
        }
    </style>
    <section class="home-section">
        <div class="home-title custom-title">
            <i class='bx bx-menu'></i>
            <span class="text custom-text">Order Invoice</span>
            <label class="custom-label" style="color:#512DA8; font-weight:bold"><i class="fa-solid fa-calendar-days"
                    style="padding-right: 5px"></i>
                {{ now()->format('l, F j, Y') }}</label>
        </div>
        <div class="home-content">
            <div class="row pb-3 align-items-center" style="color:#512DA8; font-weight:bold">
                {{-- <div class="col">
                </div> --}}
                <div class="col" style="text-align: right">
                    <span hidden id="screen-status" class="text-danger me-2" style="font-size: 12px;">Not Connected</span>
                    <button class="btn btn-danger prePrint_modal me-1" data-bs-toggle="modal"><i
                            class="fa-solid fa-file-invoice-dollar" style="padding-right: 5px"></i>Pre Print</button>
                    <button class="btn btn-primary check_out_modal" data-bs-toggle="modal"><i
                            class="fa-solid fa-file-invoice-dollar" style="padding-right: 5px"></i>Check
                        Out</button>
                    {{-- <button type="button" onclick="initCustomerScreen()" class="btn btn-warning">
                        <i class="fa fa-plug"></i> Digit Screen
                    </button> --}}
                </div>
            </div>
            <div class="shadow-sm rounded-3 mb-3">

                <div id="order_invoice_info_label" class="row align-items-center bg-white">
                    <div class="col-10">
                        <label><i class="fa-solid fa-file-invoice"
                                style="padding-left:5px; padding-right: 18px"></i>Info</label>
                    </div>
                    <div class="col-2 text-end">
                        <i class="bx bxs-chevron-down arrow"></i>
                    </div>
                </div>

                <div class="order_invoice_info_container sale_order_details_info_container shadow-sm show_container">

                    <form method="POST" id="orderInvoiceForm">
                        @csrf

                        <div class="row">

                            <!-- LEFT COLUMN -->
                            <div class="sale-order-details-info-left col-md-5">

                                <div class="row mb-3 align-items-center">
                                    <label class="col-4 col-form-label">Invoice Number</label>
                                    <div class="col-8">
                                        <input class="form-control" id="invoice_number" name="invoice_number"
                                            value="INV-{{ $saleLastID }}" readonly>
                                    </div>
                                </div>

                                <div class="row mb-3 align-items-center">
                                    <label class="col-4 col-form-label">Table Name</label>
                                    <div class="col-8">
                                        <input type="hidden" id="table_id" name="table_id"
                                            value="{{ $table[0]['table_id'] }}">

                                        <input class="form-control" id="table_name" value="{{ $table[0]['table_name'] }}"
                                            readonly>
                                    </div>
                                </div>

                                <div class="row mb-3 align-items-center">
                                    <label class="col-4 col-form-label">Order Number</label>
                                    <div class="col-8">
                                        <input class="form-control" id="order_number" name="order_number"
                                            value="{{ $tableOrderNumber }}" readonly>
                                    </div>
                                </div>

                            </div>

                            <!-- RIGHT COLUMN -->
                            <div class="sale-order-details-info-right col-md-5 offset-md-2">

                                <div class="row mb-3 align-items-center">
                                    <label class="col-4 col-form-label">Customer Name</label>
                                    <div class="col-8">
                                        <select class="form-select" id="customer_name" name="customer_name">
                                            <option value="">-----</option>
                                            @foreach ($customers as $customer)
                                                <option value="{{ $customer['customer_id'] }}">
                                                    {{ $customer['customer_name'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-3 align-items-center">
                                    <label class="col-4 col-form-label">Waiter Name</label>
                                    <div class="col-8">
                                        <select class="form-select" id="waiter_name" name="waiter_name">
                                            @foreach ($waiters as $waiter)
                                                <option value="{{ $waiter['id'] }}">
                                                    {{ $waiter['name'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-3 align-items-center">
                                    <label class="col-4 col-form-label">Cashier Name</label>
                                    <div class="col-8">
                                        <input type="hidden" id="cashier_name" name="cashier_name"
                                            value="{{ $cashier['id'] }}">

                                        <input class="form-control" value="{{ $cashier['name'] }}" readonly>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </form>
                </div>

                <div id="order_invoice_list_label" class="row align-items-center bg-white">
                    <div class="col-10">
                        <label><i class="fa-solid fa-table-list" style="padding-left:5px; padding-right: 19px"></i>Order
                            Lists</label>
                    </div>
                    <div class="col-2" style="text-align: right">
                        <i class="bx bxs-chevron-down arrow"></i>
                    </div>
                </div>
                <div class="order_invoice_list_container shadow-sm ">
                    <table id="order_invoice_list" class="table table-striped nowrap" style="width:100%;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Item Name</th>
                                <th>Order Date</th>
                                <th>Order Time</th>
                                <th>Unit</th>
                                <th>Selling Price</th>
                                <th>Promotion Price</th>
                                <th>Qty</th>
                                <th>FOC</th>
                                <th>Ordered By</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $totalAmount = 0;
                                $totalItemDiscountAmt = 0;
                            @endphp
                            @if (count($orderDetails) != 0)
                                @php
                                    $count = 1;
                                    // $totalAmount = 0;
                                    $focDiscount = 0;
                                    // $totalItemDiscountAmt = 0;
                                @endphp
                                @foreach ($orderDetails as $orderDetail)
                                    <tr>
                                        <td style="text-align: center">{{ $count }}</td>
                                        <td>{{ $orderDetail['item_name'] }}</td>
                                        <td>{{ date('d-M-y', strtotime($orderDetail['order_detail_created_at'])) }}</td>
                                        <td>{{ date('h:i A', strtotime($orderDetail['order_detail_created_at'])) }}</td>
                                        <td>{{ $orderDetail['unit_name'] }}</td>
                                        <td>{{ number_format($orderDetail['item_price']) }} MMK</td>
                                        <td>
                                            @if ($orderDetail['promotion_price'] == null)
                                                {{-- && $orderDetail['promotion_price'] == null --}}
                                                No Promo
                                                {{-- @php
                                                $totalItemDiscountAmt +=
                                                    $orderDetail['item_price'] * $orderDetail['quantity'];
                                            @endphp --}}
                                            @elseif ($orderDetail['promotion_price'] != null)
                                                {{ number_format($orderDetail['promotion_price']) }} MMK
                                                {{-- @php
                                                $totalItemDiscountAmt +=
                                                    $orderDetail['item_price'] * $orderDetail['quantity'];
                                            @endphp --}}
                                            @endif
                                        </td>
                                        <td>{{ $orderDetail['quantity'] }}</td>

                                        @if ($orderDetail['is_foc'] == 0)
                                            <td><input class="form-check-input" type="checkbox" onclick="return false;">
                                            </td>
                                        @elseif ($orderDetail['is_foc'] == 1)
                                            <td><input class="form-check-input" type="checkbox" checked
                                                    onclick="return false;">
                                            </td>
                                        @endif
                                        <td>{{ $orderDetail['name'] }}</td>
                                    </tr>
                                    @php
                                        $count++;

                                        $totalAmount += $orderDetail['item_price'] * $orderDetail['quantity'];

                                        if ($orderDetail['is_foc'] == 1) {
                                            $totalItemDiscountAmt +=
                                                $orderDetail['item_price'] * $orderDetail['quantity'];
                                        } elseif ($orderDetail['promotion_price'] != null) {
                                            $totalItemDiscountAmt +=
                                                ($orderDetail['item_price'] - $orderDetail['promotion_price']) *
                                                $orderDetail['quantity'];
                                        }
                                    @endphp
                                @endforeach
                            @endif

                        </tbody>
                    </table>
                    <input type="text" id="totalAmount" name="totalAmount" value={{ $totalAmount }} hidden>
                    <!--Edit Employee Modal -->
                    {{-- <div class="modal fade" id="edit_employee_modal" data-bs-backdrop="static" data-bs-keyboard="false"
                    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header text-center" style="background-color: #512DA8">
                                <h1 class="modal-title fs-5 w-100" id="staticBackdropLabel" style="color: white">Update
                                    Employee
                                </h1>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body" style="margin-left: 20px; margin-right:20px">
                                <form action="{{ route('employee#update') }}" method="POST" id="employeeEditModalForm">
                                    @csrf
                                    <input type="text" name="edit_employee_id" id="edit_employee_id" hidden>
                                    <div class="row mb-3 mt-3">
                                        <div class="col-5">
                                            <label class="form-label">Employee Name <span
                                                    style="color: red">*</span></label>
                                        </div>
                                        <div class="col">
                                            <input class="form-control" type="text" name="edit_employee_name"
                                                id="edit_employee_name">
                                        </div>
                                    </div>
                                    <div class="row mb-3 mt-3">
                                        <div class="col-5">
                                            <label class="form-label">Other Name</label>
                                        </div>
                                        <div class="col">
                                            <input class="form-control" type="text" name="edit_other_name"
                                                id="edit_other_name">
                                        </div>
                                    </div>
                                    <div class="row mb-3 mt-3">
                                        <div class="col-5">
                                            <label class="form-label">Employee Code <span
                                                    style="color: red">*</span></label>
                                        </div>
                                        <div class="col">
                                            <input class="form-control" type="text" name="edit_employee_code"
                                                id="edit_employee_code">
                                        </div>
                                    </div>
                                    <div class="row mb-3 mt-3">
                                        <div class="col-5">
                                            <label class="form-label">Employee Position <span
                                                    style="color: red">*</span></label>
                                        </div>
                                        <div class="col">
                                            <select class="form-select" id="edit_employee_position"
                                                name="edit_employee_position">
                                                @if (count($employeePositions) != 0)
                                                    @foreach ($employeePositions as $employeePosition)
                                                        <option value={{ $employeePosition['employee_position_id'] }}>
                                                            {{ $employeePosition['position_name'] }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row align-items-center mb-3">
                                        <div class="col-5">
                                            <label class="form-label text-danger">Terminate</label>
                                        </div>
                                        <div class="col">
                                            <input class="form-check-input" type="checkbox" name="edit_is_terminate"
                                                id="edit_is_terminate">
                                        </div>
                                    </div>
                                </form>

                            </div>
                            <div class="modal-footer" style="margin-right: 20px">
                                <input type="submit" class="btn custom_btn" value="Update"
                                    form="employeeEditModalForm">
                            </div>
                        </div>
                    </div>
                </div>
                <!--Delete Employee Modal -->
                <div class="modal fade" id="delete_employee_modal" data-bs-backdrop="static" data-bs-keyboard="false"
                    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header text-center" style="background-color: #512DA8">
                                <h1 class="modal-title fs-5 w-100" id="delete_modal_header" style="color: white">
                                </h1>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body" style="margin-left: 20px; margin-right:20px">
                                <form action="{{ route('employee#delete') }}" method="POST"
                                    id="employeeDeleteModalForm">
                                    @csrf
                                    <input type="text" name="delete_employee_id" id="delete_employee_id" hidden>
                                    <div class="row align-items-center mb-3 mt-3">
                                        <div>
                                            <label class="form-label">Are you sure want to delete?</label>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer" style="margin-right: 20px">
                                <input type="submit" class="btn btn-danger" value="Delete"
                                    form="employeeDeleteModalForm">
                            </div>
                        </div>
                    </div>
                </div> --}}
                </div>

                <!--Check Out Modal -->
                <div class="modal fade" id="check_out_modal" data-bs-backdrop="static" data-bs-keyboard="false"
                    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header text-center" style="background-color: #512DA8">
                                <h1 class="modal-title fs-5 w-100" id="check_out_modal_header" style="color: white">
                                </h1>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body" style="margin-left: 20px; margin-right:20px">
                                <form action="{{ route('sale#checkOut') }}" method="POST" id="orderCheckOutForm">
                                    @csrf
                                    <input type="text" id="invoice_number" name="invoice_number" hidden>
                                    <input type="text" id="table_id" name="table_id" hidden>
                                    <input type="text" id="table_order_number" name="table_order_number" hidden>
                                    <input type="text" id="customer_id" name="customer_id" hidden>
                                    <input type="text" id="waiter_id" name="waiter_id" hidden>
                                    <input type="text" id="cashier_id" name="cashier_id" hidden>
                                    <div class="row mb-3 mt-3">
                                        <div class="col-5">
                                            <label class="form-label">Total Amount</label>
                                        </div>
                                        <div class="col">
                                            <input class="form-control text-end muted" type="text" name="total_amount"
                                                id="total_amount" readonly>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row mb-3 mt-3">
                                        <div class="col-5">
                                            <label class="form-label">Service Charges</label>
                                        </div>
                                        <div class="col">
                                            <div class="row">
                                                <div class="col-6" style="padding-right: 5px">
                                                    <input class="form-control text-end" type="text"
                                                        name="service_charges_amount" id="service_charges_amount">
                                                </div>
                                                <div class="col-4" style="padding-left: 5px; padding-right:0px">
                                                    <input class="form-control text-end" type="text"
                                                        name="service_charges_percent" id="service_charges_percent">
                                                </div>
                                                <div class="col-1"
                                                    style="display: flex; justify-content:center; align-items:center">
                                                    %
                                                </div>
                                                <div id="service_charges_error_message"
                                                    style="color: red; display: none; font-size:12px">
                                                    Please
                                                    enter a valid
                                                    number</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3 mt-3">
                                        <div class="col-5">
                                            <label class="form-label">Tax</label>
                                        </div>
                                        <div class="col">
                                            <div class="row">
                                                <div class="col-6" style="padding-right: 5px">
                                                    <input class="form-control text-end" type="text" name="tax_amount"
                                                        id="tax_amount">
                                                </div>
                                                <div class="col-4" style="padding-left: 5px; padding-right:0px">
                                                    <input class="form-control text-end" type="text"
                                                        name="tax_percent" id="tax_percent">
                                                </div>
                                                <div class="col-1"
                                                    style="display: flex; justify-content:center; align-items:center">
                                                    %
                                                </div>
                                                <div id="tax_error_message"
                                                    style="color: red; display: none; font-size:12px">
                                                    Please
                                                    enter a valid
                                                    number</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3 mt-3">
                                        <div class="col-5">
                                            <label class="form-label">Voucher Discount</label>
                                        </div>
                                        <div class="col">
                                            <div class="row">
                                                <div class="col-6" style="padding-right: 5px">
                                                    <input class="form-control text-end" type="text"
                                                        name="voucher_discount_amount" id="voucher_discount_amount">
                                                </div>
                                                <div class="col-4" style="padding-left: 5px; padding-right:0px">
                                                    <input class="form-control text-end" type="text"
                                                        name="voucher_discount_percent" id="voucher_discount_percent">
                                                </div>
                                                <div class="col-1"
                                                    style="display: flex; justify-content:center; align-items:center">
                                                    %
                                                </div>
                                                <div id="voucher_discount_error_message"
                                                    style="color: red; display: none; font-size:12px">
                                                    Please
                                                    enter a valid
                                                    number</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3 mt-3">
                                        <div class="col-5">
                                            <label class="form-label">Item Discount</label>
                                        </div>
                                        <div class="col">
                                            <input class="form-control text-end muted" type="text"
                                                name="item_discount_amt" id="item_discount_amt"
                                                value="{{ $totalItemDiscountAmt }}" readonly>
                                        </div>
                                    </div>
                                    <div class="row mb-3 mt-3 d-flex align-items-center justify-content-center">
                                        <div class="col-5">
                                            <label class="form-label mb-0">Voucher FOC</label>
                                        </div>
                                        <div class="col">
                                            <!-- Hidden input that will be submitted -->
                                            <input type="hidden" name="voucher_foc" id="voucher_foc_value"
                                                value="0">

                                            <input class="form-check-input mt-0" type="checkbox" id="voucher_foc"
                                                style="width: 20px; height: 20px;" onclick="return false;">
                                        </div>
                                    </div>
                                    <div class="row mb-3 mt-3" hidden>
                                        <div class="col-5">
                                            <label class="form-label">Member Card</label>
                                        </div>
                                        <div class="col">
                                            <div class="row">
                                                <div class="col-5" style="padding-right: 5px">
                                                    <input class="form-control" type="text" name="member_card"
                                                        id="member_card">
                                                </div>
                                                <div class="col-3" style="padding-left: 0px; padding-right: 0px">
                                                    <input class="form-control text-end muted" type="text"
                                                        name="member_card_discount_amount"
                                                        id="member_card_discount_amount" style="padding-right: 5px"
                                                        readonly>
                                                </div>
                                                <div class="col-3" style="padding-left: 5px; padding-right:5px">
                                                    <input class="form-control text-end muted" type="text"
                                                        name="member_card_discount_percent"
                                                        id="member_card_discount_percent"
                                                        style="padding-left:0px; padding-right:5px" readonly>
                                                </div>
                                                <div class="col"
                                                    style=" padding:0px ;display: flex; justify-content:center; align-items:center">
                                                    %
                                                </div>
                                                <div id="member_card_error_message"
                                                    style="color: red; display: none; font-size:12px">
                                                    Member Card is Expired / Invalid</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3 mt-3" hidden>
                                        <div class="col-5">
                                            <label class="form-label">Coupon Card</label>
                                        </div>
                                        <div class="col">
                                            <div class="row">
                                                <div class="col-5" style="padding-right: 5px">
                                                    <input class="form-control" type="text" name="coupon_card"
                                                        id="coupon_card">
                                                </div>
                                                <div class="col-3" style="padding-left: 0px; padding-right: 0px">
                                                    <input class="form-control text-end muted" type="text"
                                                        name="coupon_card_discount_amount"
                                                        id="coupon_card_discount_amount" readonly>
                                                </div>
                                                <div class="col-3" style="padding-left: 5px; padding-right:5px">
                                                    <input class="form-control text-end muted" type="text"
                                                        name="coupon_card_discount_percent"
                                                        id="coupon_card_discount_percent"
                                                        style="padding-left:0px; padding-right:5px" readonly>
                                                </div>
                                                <div class="col"
                                                    style=" padding:0px ;display: flex; justify-content:center; align-items:center">
                                                    %
                                                </div>
                                                <div id="coupon_card_error_message"
                                                    style="color: red; display: none; font-size:12px">
                                                    Coupon Card is Expired / Invalid</div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row mb-3 mt-3">
                                        <div class="col-5">
                                            <label class="form-label">Net Amount</label>
                                        </div>
                                        <div class="col">
                                            <input class="form-control text-end muted" type="text" name="net_amount"
                                                id="net_amount" readonly>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row mb-3 mt-3">
                                        <div class="col-5">
                                            <label class="form-label">Online Payment</label>
                                        </div>
                                        <div class="col">
                                            <select class="form-select" id="payment_type" name="payment_type">
                                                @if (count($paymentTypes) != 0)
                                                    @foreach ($paymentTypes as $paymentType)
                                                        <option value={{ $paymentType['payment_type_id'] }}>
                                                            {{ $paymentType['payment_type_name'] }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3 mt-3">
                                        <div class="col-5">
                                            <label class="form-label">Online Paid</label>
                                        </div>
                                        <div class="col">
                                            <input class="form-control text-end muted" type="text" name="online_paid"
                                                id="online_paid" value="0" readonly>
                                            <div id="online_paid_error_message"
                                                style="color: red; display: none; font-size:12px">Please
                                                enter a valid
                                                number</div>
                                        </div>
                                    </div>
                                    <div class="row mb-3 mt-3">
                                        <div class="col-5">
                                            <label class="form-label">Cash Paid</label>
                                        </div>
                                        <div class="col">
                                            <input class="form-control text-end" type="text" name="paid_amount"
                                                id="paid_amount">
                                            <div id="paid_amount_error_message"
                                                style="color: red; display: none; font-size:12px">Please
                                                enter a valid
                                                number</div>
                                        </div>
                                    </div>

                                    <div class="row mb-3 mt-3">
                                        <div class="col-5">
                                            <label class="form-label">Balance</label>
                                        </div>
                                        <div class="col">
                                            <input class="form-control text-end muted" type="text" name="balance"
                                                id="balance" readonly>
                                        </div>
                                    </div>
                                    <div class="row mb-3 mt-3">
                                        <div class="col-5">
                                            <label class="form-label">Change</label>
                                        </div>
                                        <div class="col">
                                            <input class="form-control text-end muted" type="text" name="change"
                                                id="change" readonly>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer" style="margin-right: 20px">
                                {{-- <a href="" class="printBtn"> --}}
                                <button type="submit" id="orderCheckOutFormBtn" class="btn custom_btn"
                                    form="orderCheckOutForm">
                                    Check Out
                                </button>
                                {{-- </a> --}}
                                {{-- id="orderCheckOutFormBtn" --}} {{-- add id to checkout btn for pring page --}}

                                {{-- <input type="submit" class="btn custom_btn" value="Add"
                                form="addOrderItemRemarkForm"> --}}

                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
    <script src="{{ asset('script/links_js/jquery.3.6.4.min.js') }}"></script>
    <script src="{{ asset('script/links_js/jquery.validate.1.19.5.js') }}"></script>
    <script src="{{ asset('script/links_js/jquery.dataTables.1.13.7.min.js') }}"></script>
    <script src="{{ asset('script/links_js/dataTables.bootstrap5_1.13.7.min.js') }}"></script>
    <script src="{{ asset('script/jquery.printPage.js') }}"></script>

    {{-- Normal Order Script --}}
    {{-- <script src="{{ asset('script/order_invoice_script.js') }}"></script> --}}

    {{-- Dual Screem Script --}}
    <script src="{{ asset('script/order_invoice_dual_script.js') }}"></script>

    <script>

        let displayPort;
        let displayWriter;

        async function initCustomerScreen() {
            // 1. Check if we already have an active, open connection
            if (displayPort && displayPort.readable && displayPort.writable) {
                console.log("Port is already open. Reusing connection.");
                if (!displayWriter) {
                    displayWriter = displayPort.writable.getWriter();
                }
                return true;
            }

            try {
                const existingPorts = await navigator.serial.getPorts();

                if (existingPorts.length > 0) {
                    displayPort = existingPorts[0];
                    console.log("Auto-connecting to remembered port...");
                } else {
                    // This triggers the browser popup.
                    // Note: This must be triggered by a user gesture (like your click).
                    displayPort = await navigator.serial.requestPort();
                }

                // 2. Only open if it's not already open (prevents the 'open' error)
                // We check displayPort.opened if available, or use a try-catch
                try {
                    await displayPort.open({ baudRate: 2400 });
                } catch (err) {
                    if (!err.message.includes("already open")) {
                        throw err;
                    }
                }

                displayWriter = displayPort.writable.getWriter();
                return true;

            } catch (err) {
                console.error("Serial Initialization failed:", err);
                return false;
            }
        }

        async function closeCustomerScreen() {
            if (displayWriter) {
                try {
                    const encoder = new TextEncoder();
                    // 1. Send the Clear Screen command (0x0C) directly as a byte
                    // We use Uint8Array because this is a control code, not a text character.
                    await displayWriter.write(new Uint8Array([0x0C]));

                    // 2. Short delay (optional, but sometimes helps older hardware process the clear)
                    await new Promise(r => setTimeout(r, 10));
                    // Sending 0 to clear the display value visually
                    await displayWriter.write(encoder.encode("0\r\n"));

                    await displayWriter.releaseLock();
                } catch (e) {
                    console.error("Error releasing lock:", err);
                }
                displayWriter = null;
            }

            if (displayPort) {
                try {
                    await displayPort.close();
                    console.log("Customer screen disconnected.");
                } catch (e) {
                    console.error("Error closing port:", err);
                }
                displayPort = null; // Important: Clear the reference
            }

            console.log("Port closed and references cleared.");
        }

        // 2. Function to send the number to the hardware
        async function showTotalOnScreen(amount) {
            if (!displayWriter) {
                console.log("Screen not connected yet.");
                return;
            }

            try {
                // Format: Remove currency symbols, keep numbers and dot
                let totalStr = String(amount).replace(/[^0-9.]/g, "");

                // If empty or NaN, show 0
                if(totalStr === "" || isNaN(totalStr)) totalStr = "0";

                // COMMANDS:
                // 0x1B 0x40 = Initialize/Reset (Clears previous junk)
                // \r = Carriage Return (Tells screen to display the number now)

                const encoder = new TextEncoder();

                // Send Reset Command
                await displayWriter.write(new Uint8Array([0x1B, 0x40]));

                // Send Number
                await displayWriter.write(encoder.encode(totalStr + "\r"));

            } catch (err) {
                console.error("Error writing to screen:", err);
                // If error (e.g., cable unplugged), try to release lock so we can reconnect
                if(displayWriter) displayWriter.releaseLock();
            }
        }


        // Listen for the Bootstrap Modal "Shown" event
        const checkOutModal = document.getElementById('check_out_modal');

        // checkOutModal.addEventListener('shown.bs.modal', async function () {
        //     // 1. Get the Net Amount value from the input field
        //     let netAmount = document.getElementById('net_amount').value;

        //     // 2. If Net Amount is empty (because calculation takes a split second),
        //     // try getting Total Amount or wait a moment.
        //     if (!netAmount) {
        //         netAmount = document.getElementById('total_amount').value;
        //     }

        //     // 3. Send to Hardware
        //     // We add a small delay (500ms) to ensure the Serial connection
        //     // is fully established if you just clicked "Connect".
        //     setTimeout(() => {
        //         console.log("Modal Opened. Sending to screen:", netAmount);
        //         // showTotalOnScreen(netAmount);
        //     }, 500);
        // });

        checkOutModal.addEventListener('hidden.bs.modal', function () {
            closeCustomerScreen();
        });

        // $("#orderCheckOutFormBtn").on('click', async function() {
        //     // We call the close function.
        //     // Note: Since the form will submit and reload the page,
        //     // the browser usually force-closes ports, but this ensures a clean break.
        //     await closeCustomerScreen();

        //     // The form submission will proceed automatically after this
        // });

</script>
@endsection
