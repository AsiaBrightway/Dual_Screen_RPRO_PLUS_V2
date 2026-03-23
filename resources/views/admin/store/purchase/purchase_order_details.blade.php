@extends('layouts.admin.master')
@section('title', 'Purchase Order Details')

@section('content')
    <section class="home-section">

        <div class="home-title custom-title">
            <i class='bx bx-menu'></i>
            <span class="text custom-text">Purchase Order Details</span>

            <label class="custom-label" style="color:#512DA8;font-weight:bold">
                <i class="fa-solid fa-calendar-days me-1"></i>
                {{ now()->format('l, F j, Y') }}
            </label>
        </div>


        <div class="home-content">
            <div class="row pb-3">
                <div class="col text-end">
                    <a
                        href="{{ route('stockControl#stock_purchase#purchaseListPage', [
                            'dailyPurchaseDate' => request('dailyPurchaseDate'),
                        ]) }}">
                        <button class="btn btn-warning text-white customBtn-exit" id="btn_Exit">
                            <i class="fa-solid fa-circle-left me-1"></i>Back
                        </button>
                    </a>

                </div>
            </div>

            <div id="purchase_details_info_label" class="row align-items-center bg-white">
                <div class="col-10">
                    <label>
                        <i class="fa-solid fa-file-invoice" style="padding-left:5px; padding-right: 12px"></i>
                        Info
                    </label>
                </div>
                <div class="col-2 text-end">
                    <i class="bx bxs-chevron-down arrow"></i>
                </div>
            </div>

            <div class="purchase_details_info_container">

                <div class="row">

                    <!-- LEFT -->
                    <div class="receive_info_left col-12 col-lg-5">

                        <!-- Voucher -->
                        <div class="row align-items-center mb-2">
                            <div class="col-5 col-sm-4">
                                <label class="form-label mb-0">Voucher Number</label>
                            </div>
                            <div class="col-7 col-sm-8">
                                <input type="text" class="form-control" value="{{ $purchaseVoucherNumber }}" readonly>
                            </div>
                        </div>

                        <!-- Purchase Date -->
                        <div class="row align-items-center mb-2">
                            <div class="col-5 col-sm-4">
                                <label class="form-label mb-0">Purchase Date</label>
                            </div>
                            <div class="col-7 col-sm-8">
                                <input type="date" class="form-control"
                                    value="{{ date('Y-m-d', strtotime($purchase->purchase_date)) }}" readonly>
                            </div>
                        </div>

                        <!-- Due Date -->
                        <div class="row align-items-center mb-2">
                            <div class="col-5 col-sm-4">
                                <label class="form-label mb-0">Due Date</label>
                            </div>
                            <div class="col-7 col-sm-8">
                                <input type="date" class="form-control"
                                    value="{{ date('Y-m-d', strtotime($purchase->due_date)) }}" readonly>
                            </div>
                        </div>

                    </div>

                    <!-- RIGHT -->
                    <div class="receive_info_right col-12 col-lg-5 offset-lg-2 mt-1 mt-lg-0">

                        <!-- Supplier -->
                        <div class="row align-items-center mb-2">
                            <div class="col-5 col-sm-4">
                                <label class="form-label mb-0">Supplier</label>
                            </div>
                            <div class="col-7 col-sm-8">
                                <input class="form-control" value="{{ $supplier->supplier_name ?? '' }}" readonly>
                            </div>
                        </div>

                        <!-- Remark -->
                        <div class="row mb-2">
                            <div class="col-5 col-sm-4">
                                <label class="form-label mb-0">Remark</label>
                            </div>
                            <div class="col-7 col-sm-8">
                                <textarea class="form-control" rows="3" readonly>{{ $purchase->remark }}</textarea>
                            </div>
                        </div>

                    </div>

                </div>
            </div>





            {{-- ================= ITEM LIST ================= --}}
            <div id="purchase_details_list_label" class="row align-items-center bg-white">
                <div class="col-10">
                    <label>
                        <i class="fa-solid fa-table-list" style="padding-left:5px; padding-right:18px"></i>
                        Order Lists
                    </label>
                </div>
                <div class="col-2 text-end">
                    <i class="bx bxs-chevron-down arrow"></i>
                </div>
            </div>


            <div class="purchase_details_list_container">
                <table id="purchase_item_table" class="table table-striped nowrap" width="100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Item Name</th>
                            <th>Unit</th>
                            <th>Discount</th>
                            <th>Expire Date</th>
                            <th>Unit Cost</th>
                            <th>Qty</th>
                            <th>Amount</th>
                            <th>FOC</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i = 1; @endphp
                        @foreach ($purchaseDetails as $row)
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ $row['item_name'] }}</td>
                                <td>{{ $row['unit_name'] }}</td>
                                <td>{{ $row['batch_number'] }}</td>
                                <td>
                                    {{ \Carbon\Carbon::parse($row['expire_date'])->format('Y-m-d') }}
                                </td>
                                <td>{{ number_format($row['unit_cost']) }}</td>
                                <td>{{ $row['quantity'] }}</td>
                                <td>{{ number_format($row['is_foc'] ? 0 : $row['unit_cost'] * $row['quantity']) }}</td>
                                <td>
                                    <input type="checkbox" {{ $row['is_foc'] ? 'checked' : '' }} disabled>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @php
                $totalNetAmount = $purchase->total_amount - $purchase->total_item_discount;
            @endphp

            {{-- ================= SUMMARY ================= --}}
            <div id="purchase_details_summary_label" class="row align-items-center bg-white">
                <div class="col-10">
                    <label>
                        <i class="fa-solid fa-file-invoice" style="padding-left:5px; padding-right:18px"></i>
                        Order Details
                    </label>
                </div>
                <div class="col-2 text-end">
                    <i class="bx bxs-chevron-down arrow"></i>
                </div>
            </div>

            <div class="purchase_details_summary_container">
                <div class="row">
                    <!-- SUMMARY BOX -->
                    <div class="col-12 col-lg-6 offset-lg-6">

                        <div class="row mb-2">
                            <div class="col-6">Total Amount</div>
                            <div class="col-6">
                                <input class="form-control" value="{{ number_format($purchase->total_amount) }}" readonly>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-6">Total Item Discount</div>
                            <div class="col-6">
                                <input class="form-control" value="{{ number_format($purchase->total_item_discount) }}"
                                    readonly>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-6 fw-bold">Total Net Amount</div>
                            <div class="col-6">
                                <input class="form-control fw-bold text-success"
                                    value="{{ number_format($totalNetAmount) }}" readonly>
                            </div>
                        </div>

                    </div>

                </div>
            </div>



        </div>
    </section>

    {{-- JS --}}
    <script src="{{ asset('script/links_js/jquery.3.6.4.min.js') }}"></script>
    <script src="{{ asset('script/links_js/jquery.dataTables.1.13.7.min.js') }}"></script>
    <script src="{{ asset('script/links_js/dataTables.bootstrap5_1.13.7.min.js') }}"></script>
    <script src="{{ asset('script/jquery.printPage.js') }}"></script>
    <script src="{{ asset('script/purchase_order_details_script.js') }}"></script>
@endsection
