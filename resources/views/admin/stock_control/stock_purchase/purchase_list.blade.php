@extends('layouts.admin.master')
@section('title', 'Purchase Lists')

@section('content')
    <style>
        .purchase_list {
            table-layout: fixed;
            width: 100%;
        }

        .purchase_list th {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .purchase_list_container {
            width: 100%;
            overflow-x: auto;
            /* horizontal scroll */
            overflow-y: auto;
            /* vertical scroll */
            max-height: 550px;
            /* adjust height as you want */
        }

        #purchase_list {
            min-width: 1800px;
            /* force horizontal scroll */
            white-space: nowrap;
        }

        #purchase_list td:nth-child(4),
        #purchase_list td:nth-child(14) {
            white-space: normal;
            word-break: break-word;
        }
    </style>
    <section class="home-section">
        <div class="home-title">
            <i class='bx bx-menu'></i>
            <span class="text">Purchase Lists</span>
        </div>
        <div class="home-content">
            <div style="display: flex; justify-content: end;">
                <form method="GET" action="{{ route('stockControl#stock_purchase#purchaseListPage') }}">
                    <input type="date" class="form-control w-auto" name="dailyPurchaseDate"
                        value="{{ request()->query('dailyPurchaseDate') }}" onchange="this.form.submit()">
                </form>
            </div>
            <div id="purchase_list_label" class="row align-items-center bg-white mt-3">
                <div class="col-6">
                    <label><i class="fa-solid fa-table-list" style="padding-left:5px; padding-right: 12px"></i>Purchase
                        Lists</label>
                </div>
                <div class="col-6" style="text-align: right">
                    <i class="bx bxs-chevron-down arrow"></i>
                </div>
            </div>
            <div class="purchase_list_container shadow-sm show_container">
                <table id="purchase_list" class="purchase_list table table-striped nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Purchase Date</th>
                            <th>Purchase Voucher</th>
                            <th>Supplier</th>
                            <th>Total Amount</th>
                            <th>Transport Charges</th>
                            <th>Other Charges</th>
                            <th>Tax</th>
                            <th>Total Discount</th>
                            <th>Net Amount</th>
                            <th>Paid Amount</th>
                            <th>Balance Amount</th>
                            <th>Due Date</th>
                            <th>Remark</th>
                            <th>Paid Action</th>
                            <th>View</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody id="purchase_data_list">
                        @foreach ($purchaseList as $detail)
                            @php
                                $bgColor = 'transparent'; // or '' or '#fff'

                                if ($detail->paid_amount == 0) {
                                    $bgColor = 'red';
                                } elseif (
                                    $detail->paid_amount > 0 &&
                                    $detail->paid_amount <
                                        $detail->total_amount +
                                            $detail->transport_charges +
                                            $detail->other_charges +
                                            $detail->tax -
                                            $detail->discount_amount
                                ) {
                                    $bgColor = 'orange';
                                } elseif (
                                    $detail->total_amount +
                                        $detail->transport_charges +
                                        $detail->other_charges +
                                        $detail->tax -
                                        $detail->discount_amount -
                                        $detail->paid_amount ==
                                    0
                                ) {
                                    $bgColor = 'green';
                                } else {
                                    $bgColor = 'brown';
                                }
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ date('d-m-Y', strtotime($detail->purchase_date)) }}</td>
                                <td>{{ $detail->purchase_voucher_number }}</td>
                                <td style="word-wrap: break-word; white-space:normal;">{{ $detail->supplier_name }}</td>
                                <td>{{ number_format($detail->total_amount) }}</td>
                                <td>{{ number_format($detail->transport_charges) }}</td>
                                <td>{{ number_format($detail->other_charges) }}</td>
                                <td>{{ number_format($detail->tax) }}</td>
                                <td>{{ number_format($detail->discount_amount) }}</td>
                                <td>{{ number_format($detail->total_amount + $detail->transport_charges + $detail->other_charges + $detail->tax - $detail->discount_amount) }}
                                </td>
                                <td>{{ number_format($detail->paid_amount) }}</td>
                                <td style="background-color: {{ $bgColor }}; color:white;">
                                    {{ number_format($detail->total_amount + $detail->transport_charges + $detail->other_charges + $detail->tax - $detail->discount_amount - $detail->paid_amount) }}
                                </td>
                                <td>{{ date('d-m-Y', strtotime($detail->due_date)) }}</td>
                                <td style="word-wrap: break-word; white-space:normal;">{{ $detail->remark }}</td>
                                <td>
                                    <a onclick="showPaymentModel({{ $detail->purchase_id }})"><i
                                            class="fa-solid fa-sack-dollar"
                                            style="color: rgb(40, 172, 40);cursor: pointer;padding-right:10px"></i></a>
                                    {{-- <a data-bs-toggle="modal" data-bs-target="#show_paid_modal" id="btn_paidEdit" onclick="showPaidModel({{ $detail->purchase_id }})"><i class="fa-solid fa-sack-dollar"
                                    style="color: rgb(40, 172, 40);cursor: pointer;"></i></a> --}}
                                    <a onclick="showPaidEditModel({{ $detail->purchase_id }})"><i
                                            class="fa-solid fa-pen-to-square"
                                            style="color: orange;cursor: pointer;padding-right:10px"></i></a>
                                    <a onclick="showPaidDeleteModel({{ $detail->purchase_id }})"><i
                                            class="fa-regular fa-trash-can" style="color: red;cursor: pointer;"></i></a>
                                </td>
                                <td>
                                    <a
                                        href="{{ route('purchase#purchaseOrderDetails', [
                                            'purchase_id' => $detail->purchase_id,
                                            'dailyPurchaseDate' => request('dailyPurchaseDate'),
                                        ]) }}">
                                        <i class="fa-solid fa-eye" style="color: green; cursor: pointer;"></i>
                                    </a>

                                </td>

                                <td>
                                    <a href="{{ route('stockControl#stock_purchase#updatePurchasePage', $detail->purchase_id) }}"
                                        id="updatePurchase" onclick="removePurchaseItem()"><i
                                            class="fa-solid fa-pen-to-square"
                                            style="color: orange;cursor: pointer;"></i></a>
                                </td>
                                <td>
                                    <a onclick='selectPurchaseID({{ $detail->purchase_id }},"{{ $detail->purchase_voucher_number }}")'
                                        data-bs-toggle="modal" data-bs-target="#myModalPurchaseDelete"><i
                                            class="fa-regular fa-trash-can" style="color: red;cursor: pointer;"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!--Show Payment Modal -->
                <div class="modal fade" id="show_paid_modal" data-bs-backdrop="static" data-bs-keyboard="false"
                    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header text-center" style="background-color: #512DA8">
                                <h1 class="modal-title fs-5 w-100" id="delete_modal_header" style="color: white">
                                    Payment Form
                                </h1>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body" style="margin-left: 20px; margin-right:20px">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="row mb-3 mt-3">
                                            <div class="col-5">
                                                <label class="form-label">Voucher No</label>
                                            </div>
                                            <div class="col">
                                                <input type="hidden" name="purchaseID" id="purchaseID">
                                                <input class="form-control muted" type="text" name="voucher_no"
                                                    id="voucher_no" readonly>
                                            </div>
                                        </div>
                                        <div class="row mb-3 mt-3">
                                            <div class="col-5">
                                                <label class="form-label">Supplier Name</label>
                                            </div>
                                            <div class="col">
                                                <input class="form-control muted" type="text" name="supplier_name"
                                                    id="supplier_name" readonly>
                                            </div>
                                        </div>
                                        <div class="row mb-3 mt-3">
                                            <div class="col-5">
                                                <label class="form-label">Due Date </label>
                                            </div>
                                            <div class="col">
                                                <input class="form-control muted" type="date" name="due_date"
                                                    id="due_date" readonly>
                                            </div>
                                        </div>
                                        <div class="row mb-3 mt-3">
                                            <div class="col-5">
                                                <label class="form-label">Pay Date </label>
                                            </div>
                                            <div class="col">
                                                <input class="form-control" type="date" name="pay_date"
                                                    id="pay_date">
                                            </div>
                                        </div>
                                        <div class="row mb-3 mt-3">
                                            <div class="col-5">
                                                <label class="form-label">Total Item Discount</label>
                                            </div>
                                            <div class="col">
                                                <input class="form-control text-end muted" type="text"
                                                    name="total_item_discount" id="total_item_discount" readonly>
                                            </div>
                                        </div>
                                        <div class="row mb-3 mt-3">
                                            <div class="col-5">
                                                <label class="form-label">Voucher Discount</label>
                                            </div>
                                            <div class="col">
                                                <input class="form-control text-end" type="text"
                                                    name="voucher_discount" id="voucher_discount" value="0">
                                                <span class="text-danger">
                                                    <span id="voucher_discount_error"></span>
                                                </span>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-6">
                                        <div class="row mb-3 mt-3">
                                            <div class="col-6">
                                                <label class="form-label">Total Amount</label>
                                            </div>
                                            <div class="col">
                                                <input class="form-control text-end muted" type="text"
                                                    name="opening_total" id="opening_total" readonly>
                                            </div>
                                        </div>
                                        <div class="row mb-3 mt-3">
                                            <div class="col-6">
                                                <label class="form-label">Tax</label>
                                            </div>
                                            <div class="col">
                                                <input class="form-control text-end" type="text" name="tax"
                                                    id="tax" value="0">
                                                <span class="text-danger">
                                                    <span id="tax_error"></span>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="row mb-3 mt-3">
                                            <div class="col-6">
                                                <label class="form-label">Transport Charges </label>
                                            </div>
                                            <div class="col">
                                                <input class="form-control text-end" type="text"
                                                    name="transport_charges" id="transport_charges" value="0">
                                                <span class="text-danger">
                                                    <span id="transport_charges_error"></span>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="row mb-3 mt-3">
                                            <div class="col-6">
                                                <label class="form-label">Other Charges</label>
                                            </div>
                                            <div class="col">
                                                <input class="form-control text-end" type="text" name="other_charges"
                                                    id="other_charges" value="0">
                                                <span class="text-danger">
                                                    <span id="other_charges_error"></span>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="row mb-3 mt-3">
                                            <div class="col-6">
                                                <label class="form-label">Net Amount</label>
                                            </div>
                                            <div class="col">
                                                <input class="form-control text-end muted" type="text"
                                                    name="net_amount" id="net_amount" value="0" readonly>
                                            </div>
                                        </div>
                                        <div class="row mb-3 mt-3">
                                            <div class="col-6">
                                                <label class="form-label">Pay Amount</label>
                                            </div>
                                            <div class="col">
                                                <input class="form-control text-end" type="text" name="pay_amount"
                                                    id="pay_amount" value="0">
                                                <span class="text-danger">
                                                    <span id="pay_amount_error"></span>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="row mb-3 mt-3">
                                            <div class="col-6">
                                                <label class="form-label">Balance</label>
                                            </div>
                                            <div class="col">
                                                <input class="form-control text-end muted" type="text" name="balance"
                                                    id="balance" value="0" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer" style="margin-right: 20px">
                                <input type="submit" class="btn btn-success form" value="Save" id="payment_save">
                            </div>
                        </div>
                    </div>
                </div>
                {{-- End Payment Modal --}}

                <!--Show Paid Edit Modal -->
                <div class="modal fade" id="show_paid_edit_modal" data-bs-backdrop="static" data-bs-keyboard="false"
                    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                            <div class="modal-header text-center" style="background-color: #512DA8">
                                <h1 class="modal-title fs-5 w-100" id="delete_modal_header" style="color: white">
                                    Paid Edit Form
                                </h1>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body" style="margin-left: 20px; margin-right:20px">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="row mb-3 mt-3">
                                            <div class="col-5">
                                                <label class="form-label">Voucher No </label>
                                            </div>
                                            <div class="col">
                                                <input type="hidden" name="purchase_payment_log_id"
                                                    id="purchase_payment_log_id">
                                                <input class="form-control muted" type="text" name="edit_voucher_no"
                                                    id="edit_voucher_no" readonly>
                                            </div>
                                        </div>
                                        <div class="row mb-3 mt-3">
                                            <div class="col-5">
                                                <label class="form-label">Supplier Name</label>
                                            </div>
                                            <div class="col">
                                                <input class="form-control muted" type="text"
                                                    name="edit_supplier_name" id="edit_supplier_name" readonly>
                                            </div>
                                        </div>
                                        <div class="row mb-3 mt-3">
                                            <div class="col-5">
                                                <label class="form-label">Due Date </label>
                                            </div>
                                            <div class="col">
                                                <input class="form-control muted" type="date" name="edit_due_date"
                                                    id="edit_due_date" readonly>
                                            </div>
                                        </div>
                                        <div class="row mb-3 mt-3">
                                            <div class="col-5">
                                                <label class="form-label">Paid Date </label>
                                            </div>
                                            <div class="col">
                                                <input class="form-control" type="date" name="edit_paid_date"
                                                    id="edit_paid_date">
                                            </div>
                                        </div>
                                        <div class="row mb-3 mt-3">
                                            <div class="col-5">
                                                <label class="form-label">Total Item Discount</label>
                                            </div>
                                            <div class="col">
                                                <input class="form-control text-end muted" type="text"
                                                    name="edit_total_item_discount" id="edit_total_item_discount"
                                                    readonly>
                                            </div>
                                        </div>
                                        <div class="row mb-3 mt-3">
                                            <div class="col-5">
                                                <label class="form-label">Voucher Discount</label>
                                            </div>
                                            <div class="col">
                                                <input class="form-control text-end" type="text"
                                                    name="edit_voucher_discount" id="edit_voucher_discount">
                                                <span class="text-danger">
                                                    <span id="edit_voucher_discount_error"></span>
                                                </span>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-6">
                                        <div class="row mb-3 mt-3">
                                            <div class="col-6">
                                                <label class="form-label">Total Amount</label>
                                            </div>
                                            <div class="col">
                                                <input class="form-control text-end muted" type="text"
                                                    name="edit_opening_total" id="edit_opening_total" readonly>
                                            </div>
                                        </div>
                                        <div class="row mb-3 mt-3">
                                            <div class="col-6">
                                                <label class="form-label">Tax</label>
                                            </div>
                                            <div class="col">
                                                <input class="form-control text-end" type="text" name="edit_tax"
                                                    id="edit_tax" value="0">
                                                <span class="text-danger">
                                                    <span id="edit_tax_error"></span>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="row mb-3 mt-3">
                                            <div class="col-6">
                                                <label class="form-label">Transport Charges </label>
                                            </div>
                                            <div class="col">
                                                <input class="form-control text-end" type="text"
                                                    name="edit_transport_charges" id="edit_transport_charges"
                                                    value="0">
                                                <span class="text-danger">
                                                    <span id="edit_transport_charges_error"></span>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="row mb-3 mt-3">
                                            <div class="col-6">
                                                <label class="form-label">Other Charges</label>
                                            </div>
                                            <div class="col">
                                                <input class="form-control text-end" type="text"
                                                    name="edit_other_charges" id="edit_other_charges" value="0">
                                                <span class="text-danger">
                                                    <span id="edit_other_charges_error"></span>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="row mb-3 mt-3">
                                            <div class="col-6">
                                                <label class="form-label">Net Amount</label>
                                            </div>
                                            <div class="col">
                                                <input class="form-control text-end muted" type="text"
                                                    name="edit_net_amount" id="edit_net_amount" readonly>
                                            </div>
                                        </div>
                                        <div class="row mb-3 mt-3">
                                            <div class="col-6">
                                                <label class="form-label">Paid Amount</label>
                                            </div>
                                            <div class="col">
                                                <input class="form-control text-end" type="text"
                                                    name="edit_paid_amount" id="edit_paid_amount">
                                                <span class="text-danger">
                                                    <span id="edit_paid_amount_error"></span>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="row mb-3 mt-3">
                                            <div class="col-6">
                                                <label class="form-label">Balance</label>
                                            </div>
                                            <div class="col">
                                                <input class="form-control text-end muted" type="text"
                                                    name="edit_balance" id="edit_balance" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer" style="margin-right: 20px">
                                <input type="submit" class="btn btn-warning text-white" value="Update"
                                    form="supplierPaidEditModalForm" id="paid_edit_save">
                            </div>
                        </div>
                    </div>
                </div>
                {{-- End Paid Edit Modal --}}

                <!--Show Paid Delete Modal -->
                <div class="modal fade" id="show_paid_delete_modal" data-bs-backdrop="static" data-bs-keyboard="false"
                    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                            <div class="modal-header text-center" style="background-color: #512DA8">
                                <h1 class="modal-title fs-5 w-100" id="delete_modal_header" style="color: white">
                                    Paid Delete Form
                                </h1>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body" style="margin-left: 20px; margin-right:20px">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="row mb-3 mt-3">
                                            <div class="col-5">
                                                <label class="form-label">Voucher No </label>
                                            </div>
                                            <div class="col">
                                                <input type="hidden" name="d_purchase_payment_log_id"
                                                    id="d_purchase_payment_log_id">
                                                <input class="form-control muted" type="text" name="show_voucher_no"
                                                    id="show_voucher_no" readonly>
                                            </div>
                                        </div>
                                        <div class="row mb-3 mt-3">
                                            <div class="col-5">
                                                <label class="form-label">Supplier Name</label>
                                            </div>
                                            <div class="col">
                                                <input class="form-control muted" type="text"
                                                    name="show_supplier_name" id="show_supplier_name" readonly>
                                            </div>
                                        </div>
                                        <div class="row mb-3 mt-3">
                                            <div class="col-5">
                                                <label class="form-label">Due Date </label>
                                            </div>
                                            <div class="col">
                                                <input class="form-control muted" type="date" name="show_due_date"
                                                    id="show_due_date" readonly>
                                            </div>
                                        </div>
                                        <div class="row mb-3 mt-3">
                                            <div class="col-5">
                                                <label class="form-label">Paid Date </label>
                                            </div>
                                            <div class="col">
                                                <input class="form-control" type="date" name="show_paid_date"
                                                    id="show_paid_date" readonly>
                                            </div>
                                        </div>
                                        <div class="row mb-3 mt-3">
                                            <div class="col-5">
                                                <label class="form-label">Total Item Discount</label>
                                            </div>
                                            <div class="col">
                                                <input class="form-control text-end muted" type="text"
                                                    name="show_total_item_discount" id="show_total_item_discount"
                                                    readonly>
                                            </div>
                                        </div>
                                        <div class="row mb-3 mt-3">
                                            <div class="col-5">
                                                <label class="form-label">Voucher Discount</label>
                                            </div>
                                            <div class="col">
                                                <input class="form-control text-end" type="text"
                                                    name="show_voucher_discount" id="show_voucher_discount" readonly>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-6">
                                        <div class="row mb-3 mt-3">
                                            <div class="col-6">
                                                <label class="form-label">Total Amount</label>
                                            </div>
                                            <div class="col">
                                                <input class="form-control text-end muted" type="text"
                                                    name="show_opening_total" id="show_opening_total" readonly>
                                            </div>
                                        </div>
                                        <div class="row mb-3 mt-3">
                                            <div class="col-6">
                                                <label class="form-label">Tax</label>
                                            </div>
                                            <div class="col">
                                                <input class="form-control text-end" type="text" name="show_tax"
                                                    id="show_tax" value="0" readonly>
                                            </div>
                                        </div>
                                        <div class="row mb-3 mt-3">
                                            <div class="col-6">
                                                <label class="form-label">Transport Charges </label>
                                            </div>
                                            <div class="col">
                                                <input class="form-control text-end" type="text"
                                                    name="show_transport_charges" id="show_transport_charges"
                                                    value="0" readonly>
                                            </div>
                                        </div>
                                        <div class="row mb-3 mt-3">
                                            <div class="col-6">
                                                <label class="form-label">Other Charges</label>
                                            </div>
                                            <div class="col">
                                                <input class="form-control text-end" type="text"
                                                    name="show_other_charges" id="show_other_charges" value="0"
                                                    readonly>
                                            </div>
                                        </div>
                                        <div class="row mb-3 mt-3">
                                            <div class="col-6">
                                                <label class="form-label">Net Amount</label>
                                            </div>
                                            <div class="col">
                                                <input class="form-control text-end muted" type="text"
                                                    name="show_net_amount" id="show_net_amount" readonly>
                                            </div>
                                        </div>
                                        <div class="row mb-3 mt-3">
                                            <div class="col-6">
                                                <label class="form-label">Paid Amount</label>
                                            </div>
                                            <div class="col">
                                                <input class="form-control text-end" type="text"
                                                    name="show_paid_amount" id="show_paid_amount" readonly>
                                            </div>
                                        </div>
                                        <div class="row mb-3 mt-3">
                                            <div class="col-6">
                                                <label class="form-label">Balance</label>
                                            </div>
                                            <div class="col">
                                                <input class="form-control text-end muted" type="text"
                                                    name="show_balance" id="show_balance" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer" style="margin-right: 20px">
                                <input type="submit" class="btn btn-danger" value="Delete"
                                    form="supplierPaidDeleteModalForm" id="paid_delete">
                            </div>
                        </div>
                    </div>
                </div>
                {{-- End Paid Delete Modal --}}

                {{-- Voucher Delete Modal --}}
                <div class="modal fade" id="myModalPurchaseDelete">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">

                            <div class="modal-header text-center" style="background-color: #512DA8;">
                                <h6 class="modal-title w-100 text-white">Are you sure want to delete voucher no: <span
                                        class="text-white" id="delete_content"></span> ?</h6>
                                <button type="button" class="btn-close btn-close-white"
                                    data-bs-dismiss="modal"></button>
                            </div>
                            {{-- <form
                                action="{{ route('stockControl#stock_purchase#purchaseListPage#deleteSelectedPurchase') }}"
                                method="post" enctype="multipart/form-data" id="deletePurchaseModalForm">
                                @csrf --}}
                            <div class="modal-body" style="margin-left: 20px; margin-right: 20px">
                                <input type="hidden" name="purchase_deleteID" id="purchase_deleteID">
                                <input type="text" id="loginUserID" name="loginUserID"
                                    value="{{ Auth::User()->id }}" hidden>
                                <div class="row align-items-center mb-3">
                                    <label class="form-label ps-0">Delete Reason</label>
                                    <textarea class="form-control" type="text" id="cancel_reason" name="cancel_reason"></textarea>
                                    <span class="text-danger">
                                        <span id="cancel_reason_error"></span>
                                    </span>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <input class="btn btn-danger" type="submit" value="Delete" id="purchase_delete">
                            </div>
                            {{-- </form> --}}
                        </div>
                    </div>
                </div>
                {{-- End Voucher Delete --}}
            </div>
        </div>
    </section>
    <script src="{{ asset('script/links_js/jquery.3.6.4.min.js') }}"></script>
    <script src="{{ asset('script/links_js/jquery.validate.1.19.5.js') }}"></script>
    <script src="{{ asset('script/links_js/jquery.dataTables.1.13.7.min.js') }}"></script>
    <script src="{{ asset('script/links_js/dataTables.bootstrap5_1.13.7.min.js') }}"></script>
    <script src="{{ asset('script/purchase_list_script.js') }}"></script>
    <script>
        new DataTable('#purchase_list', {
            scrollX: true,
            autoWidth: false,
            language: {
                emptyTable: "No data available"
            },
            columns: [{
                    width: "50px"
                },
                {
                    width: "120px"
                },
                {
                    width: "160px"
                },
                {
                    width: "130px"
                },
                {
                    width: "130px"
                },
                {
                    width: "160px"
                },
                {
                    width: "130px"
                },
                {
                    width: "110px"
                },
                {
                    width: "120px"
                },
                {
                    width: "120px"
                },
                {
                    width: "110px"
                },
                {
                    width: "150px"
                },
                {
                    width: "100px"
                },
                {
                    width: "200px"
                },
                {
                    width: "110px"
                },
                {
                    width: "70px"
                },
                {
                    width: "80px"
                },
                {
                    width: "80px"
                },
            ]
        });



        // setRowNumber_changed();
        window.onload = function() {
            var j = 0;
            var count = $('#purchase_data_list').find('tr').length;
            if (count > 1) {
                $('#purchase_data_list tr').each(function(key, value) {
                    ++j;
                    $('td:first-child', this).text(j);
                });
            }
        }

        function removePurchaseItem() {
            localStorage.removeItem('update_purchase_detail_list');
        }

        function selectPurchaseID(id, voucherNo) {
            $("#purchase_deleteID").val(id);
            document.getElementById("delete_content").innerHTML = voucherNo;
            $('#cancel_reason_error').html("");
            $('#cancel_reason').removeClass('is-invalid');
        }

        $('#purchase_delete').click(function(e) {
            e.preventDefault();

            let purchaseDeleteLog = {
                purchase_deleteID: $('#purchase_deleteID').val(),
                delete_reason: $('#cancel_reason').val(),
                loginUserID: $('#loginUserID').val(),
                _token: $('meta[name="csrf-token"]').attr('content')
            };
            var url = '{{ route('stockControl#stock_purchase#purchaseListPage#deleteSelectedPurchase') }}';
            $.ajax({
                type: 'post',
                url: url,
                data: purchaseDeleteLog,
                success: (response) => {
                    if (response.errors) {
                        $('#cancel_reason_error').text(response.errors.delete_reason);
                    } else if (response.success) {
                        location.reload();
                    }
                }
            });
        })

        $('#deletePurchaseModalForm').submit(function(e) {
            e.preventDefault();

            var url = $(this).attr("action");
            let formData = new FormData(this);

            $.ajax({
                type: 'POST',
                url: url,
                data: formData,
                contentType: false,
                processData: false,
                success: (response, data) => {
                    // console.log(response);
                    if (response.errors) {
                        $.each(response.errors, function(key, value) {
                            if (key == "cancel_reason") {
                                $('#cancel_reason_error').html(value);
                                $('#cancel_reason').addClass('is-invalid');
                            }
                        });
                    } else if (response.success) {
                        $('#myModalPurchaseDelete').modal('hide');
                        var url =
                            "{{ route('stockControl#stock_purchase#purchaseListPage') }}"; //the url I want to redirect to
                        $(location).attr('href', url);
                    }
                }
            });
        });

        function showPaymentModel($id) {
            clearError();
            var url = '{{ route('stockControl#stock_purchase#purchaseListPage#GetPaidLog') }}';
            $.ajax({
                url: url,
                type: "get",
                data: {
                    purchaseID: $id
                },
                contentType: 'application/json; charset=utf-8',
                success: function(response) {
                    console.log(response.errors);
                    var paid_log = response.success;
                    paid_log = paid_log[0];
                    console.log(paid_log);
                    if (parseFloat(paid_log.balance) > 0) {
                        $('#show_paid_modal').modal('show');
                        // $('#opening_total').val(parseFloat(paid_log.total_amount).toLocaleString());
                        // $('#total_item_discount').val(parseFloat(paid_log.total_item_discount).toLocaleString());
                        $('#purchaseID').val(paid_log.purchase_id);
                        $('#opening_total').val(parseFloat(paid_log.balance) + parseFloat(paid_log
                            .total_item_discount));
                        $('#total_item_discount').val(parseFloat(paid_log.total_item_discount));
                        $('#voucher_no').val(paid_log.purchase_voucher_number);
                        $('#supplier_name').val(paid_log.supplier_name);
                        var dueDate = new Date(paid_log.due_date);
                        $('#due_date').val(dueDate.getFullYear() + '-' + ('0' + (dueDate.getMonth() + 1)).slice(
                            -2) + '-' + ('0' + dueDate.getDate()).slice(-2));
                        var paidDate = new Date();
                        $('#pay_date').val(paidDate.getFullYear() + '-' + ('0' + (paidDate.getMonth() + 1))
                            .slice(-2) + '-' + ('0' + paidDate.getDate()).slice(-2));
                        $('#pay_amount').val(0);
                        CalculateAmountForShowPaidModel();
                    }
                }
            });
        }

        function CalculateAmountForShowPaidModel() {
            let total_amount = ($('#opening_total').val() == '') ? 0 : parseFloat($('#opening_total').val().replace(/\D/g,
                ''));
            let tax = ($('#tax').val() == '') ? 0 : parseFloat($('#tax').val());
            let transport_charges = ($('#transport_charges').val() == '') ? 0 : parseFloat($('#transport_charges').val());
            let other_charges = ($('#other_charges').val() == '') ? 0 : parseFloat($('#other_charges').val());
            let total_itemDiscount = ($('#total_item_discount').val() == '') ? 0 : parseFloat($('#total_item_discount')
                .val().replace(/\D/g, ''));
            let voucher_discount = ($('#voucher_discount').val() == '') ? 0 : parseFloat($('#voucher_discount').val());
            let payment = ($('#pay_amount').val() == '') ? 0 : parseFloat($('#pay_amount').val());
            let net_Amt = (total_amount + tax + transport_charges + other_charges) - (total_itemDiscount +
                voucher_discount);
            let balance = net_Amt - payment;
            // $('#net_amount').val(net_Amt.toLocaleString());
            // $('#balance').val(balance.toLocaleString());
            $('#net_amount').val(net_Amt);
            $('#balance').val(balance);
        }

        $(document).on("keyup", "#transport_charges, #other_charges, #tax, #voucher_discount, #pay_amount",
            CalculateAmountForShowPaidModel);

        $(document).on("keyup",
            "#edit_transport_charges, #edit_other_charges, #edit_tax, #edit_voucher_discount, #edit_paid_amount",
            CalculateAmountForShowPaidEditModel);

        $(document).on("input",
            "#transport_charges, #other_charges, #tax, #voucher_discount, #pay_amount,#edit_transport_charges, #edit_other_charges, #edit_tax, #edit_voucher_discount, #edit_pay_amount",
            function() {
                if (this.value == "" || this.value == null) {
                    this.value = 0;
                }
            });

        $('#payment_save').click(function(e) {
            var total = parseFloat($('#opening_total').val()) - parseFloat($('#total_item_discount').val())
            var paidLog = {
                purchaseID: $('#purchaseID').val(),
                pay_date: $('#pay_date').val(),
                voucher_discount: $('#voucher_discount').val(),
                opening_total: total,
                tax: $('#tax').val(),
                transport_charges: $('#transport_charges').val(),
                other_charges: $('#other_charges').val(),
                pay_amount: $('#pay_amount').val(),
                net_amount: $('#net_amount').val(),
                balance: $('#balance').val()
            };
            console.log(paidLog);
            // var url = $(this).attr("action");
            // $('#opening_total').val($('#opening_total').val().replace(/\D/g,''));
            // $('#net_amount').val($('#net_amount').val().replace(/\D/g,''));
            // $('#balance').val($('#balance').val().replace(/\D/g,''));
            // let formData = new FormData(this);

            var url = '{{ route('stockControl#stock_purchase#purchaseListPage#createPaymentLog') }}';
            $.ajax({
                type: 'get',
                url: url,
                data: paidLog,
                // contentType: 'application/json; charset=utf-8',
                success: (response) => {
                    if (response.errors) {
                        console.log(response.errors);
                        if (typeof(response.errors) === 'object') {
                            $.each(response.errors, function(key, value) {
                                if (key == "pay_amount") {
                                    $('#pay_amount_error').html(value);
                                    $('#pay_amount').addClass('is-invalid');
                                } else if (key == "voucher_discount") {
                                    $('#voucher_discount_error').html(value);
                                    $('#voucher_discount').addClass('is-invalid');
                                } else if (key == "tax") {
                                    $('#tax_error').html(value);
                                    $('#tax').addClass('is-invalid');
                                } else if (key == "transport_charges") {
                                    $('#transport_charges_error').html(value);
                                    $('#transport_charges').addClass('is-invalid');
                                } else if (key == "other_charges") {
                                    $('#other_charges_error').html(value);
                                    $('#other_charges').addClass('is-invalid');
                                }
                            });
                        }
                    } else if (response.success) {
                        console.log(response.success);
                        location.reload();
                    }

                }
            });
        });

        function showPaidEditModel($id) {
            clearError();
            var url = '{{ route('stockControl#stock_purchase#purchaseListPage#GetPaidEditLog') }}';
            $.ajax({
                url: url,
                type: "get",
                data: {
                    purchaseID: $id
                },
                contentType: 'application/json; charset=utf-8',
                success: function(response) {
                    console.log(response.errors);
                    var paid_edit_log = response.success;
                    paid_edit_log = paid_edit_log[0];
                    console.log(paid_edit_log);
                    if (paid_edit_log != undefined) {
                        $('#show_paid_edit_modal').modal('show');
                        $('#purchase_payment_log_id').val(paid_edit_log.purchase_payment_log_id);
                        $('#edit_voucher_no').val(paid_edit_log.purchase_voucher_number);
                        $('#edit_supplier_name').val(paid_edit_log.supplier_name);
                        var dueDateEdit = new Date(paid_edit_log.due_date);
                        $('#edit_due_date').val(dueDateEdit.getFullYear() + '-' + ('0' + (dueDateEdit
                            .getMonth() + 1)).slice(-2) + '-' + ('0' + dueDateEdit.getDate()).slice(-2));
                        var paidEditDate = new Date(paid_edit_log.paid_date);
                        $('#edit_paid_date').val(paidEditDate.getFullYear() + '-' + ('0' + (paidEditDate
                            .getMonth() + 1)).slice(-2) + '-' + ('0' + paidEditDate.getDate()).slice(-
                            2));

                        $('#edit_opening_total').val(parseFloat(paid_edit_log.total_amount));
                        $('#edit_total_item_discount').val(parseFloat(paid_edit_log.total_item_discount));
                        $('#edit_voucher_discount').val(parseFloat(paid_edit_log.voucher_discount));
                        $('#edit_tax').val(parseFloat(paid_edit_log.tax));
                        $('#edit_transport_charges').val(parseFloat(paid_edit_log.transport_charges));
                        $('#edit_other_charges').val(parseFloat(paid_edit_log.other_charges));
                        $('#edit_paid_amount').val(parseFloat(paid_edit_log.paid_amount));
                        // $('#edit_net_amount').val(parseFloat(paid_edit_log.net_amount));
                        // $('#edit_balance').val(parseFloat(paid_edit_log.balance));

                        CalculateAmountForShowPaidEditModel();
                    }
                }
            });
        }

        function CalculateAmountForShowPaidEditModel() {
            let edit_total_amount = ($('#edit_opening_total').val() == '') ? 0 : parseFloat($('#edit_opening_total').val()
                .replace(/\D/g, ''));
            let edit_tax = ($('#edit_tax').val() == '') ? 0 : parseFloat($('#edit_tax').val().replace(/\D/g, ''));
            let edit_transport_charges = ($('#edit_transport_charges').val() == '') ? 0 : parseFloat($(
                '#edit_transport_charges').val().replace(/\D/g, ''));
            let edit_other_charges = ($('#edit_other_charges').val() == '') ? 0 : parseFloat($('#edit_other_charges').val()
                .replace(/\D/g, ''));
            let edit_total_itemDiscount = ($('#edit_total_item_discount').val() == '') ? 0 : parseFloat($(
                '#edit_total_item_discount').val().replace(/\D/g, ''));
            let edit_voucher_discount = ($('#edit_voucher_discount').val() == '') ? 0 : parseFloat($(
                '#edit_voucher_discount').val().replace(/\D/g, ''));
            let edit_payment = ($('#edit_paid_amount').val() == '') ? 0 : parseFloat($('#edit_paid_amount').val().replace(
                /\D/g, ''));
            let edit_net_Amt = (edit_total_amount + edit_tax + edit_transport_charges + edit_other_charges) - (
                edit_total_itemDiscount + edit_voucher_discount);
            let edit_balance = edit_net_Amt - edit_payment;
            $('#edit_net_amount').val(edit_net_Amt);
            $('#edit_balance').val(edit_balance);
        }


        $('#paid_edit_save').click(function(e) {
            var edit_totalAmt = parseFloat($('#edit_opening_total').val());
            var paidEditLog = {
                purchase_payment_log_id: $('#purchase_payment_log_id').val(),
                pay_date: $('#edit_paid_date').val(),
                voucher_discount: $('#edit_voucher_discount').val(),
                opening_total: edit_totalAmt,
                tax: $('#edit_tax').val(),
                transport_charges: $('#edit_transport_charges').val(),
                other_charges: $('#edit_other_charges').val(),
                pay_amount: $('#edit_paid_amount').val(),
                net_amount: parseFloat($('#edit_net_amount').val()),
                balance: $('#edit_balance').val()
            };
            // console.log(paidLog);
            var url = '{{ route('stockControl#stock_purchase#purchaseListPage#editPaymentLog') }}';
            $.ajax({
                type: 'get',
                url: url,
                data: paidEditLog,
                // contentType: 'application/json; charset=utf-8',
                success: (response) => {
                    if (response.errors) {
                        console.log(response.errors);
                        if (typeof(response.errors) === 'object') {
                            $.each(response.errors, function(key, value) {
                                if (key == "pay_amount") {
                                    $('#edit_paid_amount_error').html(value);
                                    $('#edit_paid_amount').addClass('is-invalid');
                                } else if (key == "voucher_discount") {
                                    $('#edit_voucher_discount_error').html(value);
                                    $('#edit_voucher_discount').addClass('is-invalid');
                                } else if (key == "tax") {
                                    $('#edit_tax_error').html(value);
                                    $('#edit_tax').addClass('is-invalid');
                                } else if (key == "transport_charges") {
                                    $('#edit_transport_charges_error').html(value);
                                    $('#edit_transport_charges').addClass('is-invalid');
                                } else if (key == "other_charges") {
                                    $('#edit_other_charges_error').html(value);
                                    $('#edit_other_charges').addClass('is-invalid');
                                }
                            });
                        }
                    } else if (response.success) {
                        console.log(response.success);
                        location.reload();
                    }

                }
            });
        });

        function showPaidDeleteModel($id) {
            var url = '{{ route('stockControl#stock_purchase#purchaseListPage#GetPaidEditLog') }}';
            $.ajax({
                url: url,
                type: "get",
                data: {
                    purchaseID: $id
                },
                contentType: 'application/json; charset=utf-8',
                success: function(response) {
                    console.log(response.errors);
                    var paid_edit_log = response.success;
                    paid_edit_log = paid_edit_log[0];
                    console.log(paid_edit_log);
                    if (paid_edit_log != undefined) {
                        $('#show_paid_delete_modal').modal('show');
                        $('#d_purchase_payment_log_id').val(paid_edit_log.purchase_payment_log_id);
                        $('#show_voucher_no').val(paid_edit_log.purchase_voucher_number);
                        $('#show_supplier_name').val(paid_edit_log.supplier_name);
                        var d_dueDateEdit = new Date(paid_edit_log.due_date);
                        $('#show_due_date').val(d_dueDateEdit.getFullYear() + '-' + ('0' + (d_dueDateEdit
                            .getMonth() + 1)).slice(-2) + '-' + ('0' + d_dueDateEdit.getDate()).slice(-
                            2));
                        var d_paidEditDate = new Date(paid_edit_log.paid_date);
                        $('#show_paid_date').val(d_paidEditDate.getFullYear() + '-' + ('0' + (d_paidEditDate
                            .getMonth() + 1)).slice(-2) + '-' + ('0' + d_paidEditDate.getDate()).slice(-
                            2));

                        $('#show_opening_total').val(parseFloat(paid_edit_log.total_amount));
                        $('#show_total_item_discount').val(parseFloat(paid_edit_log.total_item_discount));
                        $('#show_voucher_discount').val(parseFloat(paid_edit_log.voucher_discount));
                        $('#show_tax').val(parseFloat(paid_edit_log.tax));
                        $('#show_transport_charges').val(parseFloat(paid_edit_log.transport_charges));
                        $('#show_other_charges').val(parseFloat(paid_edit_log.other_charges));
                        $('#show_paid_amount').val(parseFloat(paid_edit_log.paid_amount));
                        CalculateAmountForShowPaidDeleteModel();
                    }
                }
            });
        }

        function CalculateAmountForShowPaidDeleteModel() {
            let show_total_amount = ($('#show_opening_total').val() == '') ? 0 : parseFloat($('#show_opening_total').val()
                .replace(/\D/g, ''));
            let show_tax = ($('#show_tax').val() == '') ? 0 : parseFloat($('#show_tax').val().replace(/\D/g, ''));
            let show_transport_charges = ($('#show_transport_charges').val() == '') ? 0 : parseFloat($(
                '#show_transport_charges').val().replace(/\D/g, ''));
            let show_other_charges = ($('#show_other_charges').val() == '') ? 0 : parseFloat($('#show_other_charges').val()
                .replace(/\D/g, ''));
            let show_total_itemDiscount = ($('#show_total_item_discount').val() == '') ? 0 : parseFloat($(
                '#show_total_item_discount').val().replace(/\D/g, ''));
            let show_voucher_discount = ($('#show_voucher_discount').val() == '') ? 0 : parseFloat($(
                '#show_voucher_discount').val().replace(/\D/g, ''));
            let show_payment = ($('#show_paid_amount').val() == '') ? 0 : parseFloat($('#show_paid_amount').val().replace(
                /\D/g, ''));
            let show_net_Amt = (show_total_amount + show_tax + show_transport_charges + show_other_charges) - (
                show_total_itemDiscount + show_voucher_discount);
            let show_balance = show_net_Amt - show_payment;
            $('#show_net_amount').val(show_net_Amt);
            $('#show_balance').val(show_balance);
        }

        $('#paid_delete').click(function(e) {
            var url = '{{ route('stockControl#stock_purchase#purchaseListPage#deletePaidLog') }}';
            $.ajax({
                type: 'get',
                url: url,
                data: {
                    purchase_payment_log_id: $('#d_purchase_payment_log_id').val()
                },
                // contentType: 'application/json; charset=utf-8',
                success: (response) => {
                    if (response.errors) {
                        console.log(response.errors);
                    } else if (response.success) {
                        console.log(response.success);
                        location.reload();
                    }
                }
            });
        });

        function clearError() {
            $('#pay_amount').removeClass('is-invalid');
            $('#pay_amount_error').html("");

            $('#voucher_discount').removeClass('is-invalid');
            $('#voucher_discount_error').html("");

            $('#tax').removeClass('is-invalid');
            $('#tax_error').html("");

            $('#transport_charges').removeClass('is-invalid');
            $('#transport_charges_error').html("");

            $('#other_charges').removeClass('is-invalid');
            $('#other_charges_error').html("");

            $('#edit_pay_amount').removeClass('is-invalid');
            $('#edit_pay_amount_error').html("");

            $('#edit_voucher_discount').removeClass('is-invalid');
            $('#edit_voucher_discount_error').html("");

            $('#edit_tax').removeClass('is-invalid');
            $('#edit_tax_error').html("");

            $('#edit_transport_charges').removeClass('is-invalid');
            $('#edit_transport_charges_error').html("");

            $('#edit_other_charges').removeClass('is-invalid');
            $('#edit_other_charges_error').html("");
        }
    </script>
@endsection
