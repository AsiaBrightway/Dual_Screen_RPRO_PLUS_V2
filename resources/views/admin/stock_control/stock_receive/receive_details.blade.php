@extends('layouts.admin.master')
@section('title', 'Receive Details')

@section('content')
    <style>
        @media (max-width: 765px) {
            .col-sm-12 {
                padding-bottom: 0px;
            }
        }
    </style>
    <section class="home-section">
        <div class="home-title">
            <i class='bx bx-menu'></i>
            <span class="text">Stock Receive</span>
        </div>
        <div class="home-content">
            <div class="row pb-3 align-items-center" style="color:#512DA8; font-weight:bold">
                <div class="col-12 col-md-7">
                    <label>Stock Receive Details</label>
                </div>
                <div class="col-12 col-md-5 text-md-end text-start mt-3 mt-md-0">
                    <a href="{{ route('stockControl#stock_receive#receiveListPage', ['dailyReceiveDate' => $dailyReceiveDate]) }}"><button
                            class="btn btn-warning text-white customBtn-exit" id="btn_Exit"><i
                                class="fa-solid fa-circle-left" style="padding-right: 5px"></i>Back</button></a>
                </div>
            </div>
            <div id="voucher_info_label" class="row align-items-center bg-white">
                <div class="col-10">
                    <label>
                        <i class="fa-regular fa-newspaper" style="padding-left:5px; padding-right: 12px"></i>
                        Receive Voucher Info
                    </label>
                </div>
                <div class="col-2" style="text-align: right">
                    <i class="bx bxs-chevron-down arrow"></i>
                </div>
            </div>
            <div class="voucher_info_container shadow-sm show_container">
                <div class="row">
                    <div class="receive_info_left col-5">
                        <div class="row">
                            <div class="col-4 mb-3">
                                <label class="form-label">Receive Voucher No</label>
                            </div>
                            <div class="col-8">
                                <input type="text" id="receiveID" name="receiveID" value="{{ $selectedReceive[0]['stock_receive_id'] }}" 
                                    hidden>
                                <input type="text" id="loginUserID" name="loginUserID" value="{{ Auth::User()->id }}"
                                    hidden>
                                <input class="form-control" type="text" id="voucher_no" name="voucher_no"
                                    value="{{ $selectedReceive[0]['receive_voucher_number'] }}"
                                    readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <label class="form-label">Receive Date</label>
                            </div>
                            <div class="col-8">
                                <input class="form-control" id="receive_date" name="receive_date" type="date"
                                    value="{{ date('Y-m-d', strtotime($selectedReceive[0]['receive_date'])) }}"
                                    readonly>
                            </div>
                        </div>
                    </div>
                    <div class="receive_info_right col-5 offset-2" >
                        <div class="row">
                            <div class="col-4 col-lg-3 col-xxl-2 mb-3">
                                <label class="form-label">Remark</label>
                            </div>
                            <div class="col-8 col-lg-9 col-xxl-10">
                                <textarea class="form-control" id="remark" name="remark" rows="3" readonly>{{ $selectedReceive[0]['remark'] }}</textarea>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div id="sale_order_details_list_label" class="row align-items-center bg-white">
                <div class="col-10">
                    <label>
                        <i class="fa-solid fa-table-list" style="padding-left:5px; padding-right: 19px"></i>
                        Receive Detail Lists
                    </label>
                </div>
                <div class="col-2" style="text-align: right">
                    <i class="bx bxs-chevron-down arrow"></i>
                </div>
            </div>
            <div class="sale_order_details_list_container shadow-sm" style="padding-top: 10px;" >
                <table id="sale_order_details_list" class="table table-striped nowrap" style="width:100%;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Item Name</th>
                            <th>Item Code</th>
                            <th>Bar Code</th>
                            <th>Unit</th>
                            <th>Quantity</th>
                            <th>Unit Cost</th>
                            <th>Amount</th>
                            <th>Expire Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ count($selectedReceiveDetail) }}</td>
                            <td>{{ $selectedReceiveDetail[0]['item_name'] }}</td>
                            <td>{{ $selectedReceiveDetail[0]['item_code'] }}</td>
                            <td>{{ $selectedReceiveDetail[0]['bar_code'] }}</td>
                            <td>{{ $selectedReceiveDetail[0]['unit_name'] }}</td>
                            <td>{{ number_format($selectedReceiveDetail[0]['quantity']) }}</td>
                            <td>{{ number_format($selectedReceiveDetail[0]['unit_cost']) }} MMK</td>
                            <td>{{ number_format($selectedReceiveDetail[0]['unit_cost'] * $selectedReceiveDetail[0]['quantity']) }} MMK</td>
                            <td>{{ date('Y-m-d', strtotime($selectedReceiveDetail[0]['expire_date'])) }}</td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <script src="{{ asset('script/links_js/jquery.3.6.4.min.js') }}"></script>
    <script src="{{ asset('script/links_js/jquery.validate.1.19.5.js') }}"></script>
    <script src="{{ asset('script/links_js/jquery.dataTables.1.13.7.min.js') }}"></script>
    <script src="{{ asset('script/links_js/dataTables.bootstrap5_1.13.7.min.js') }}"></script>
    <script src="{{ asset('script/receive_details_script.js') }}"></script>
    
@endsection
