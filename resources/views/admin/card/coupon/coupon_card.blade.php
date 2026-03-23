@extends('layouts.admin.master')
@section('title', 'Coupon Card')

@section('content')
    <section class="home-section">
        <div class="home-title">
            <i class='bx bx-menu'></i>
            <span class="text">Coupon Card</span>
        </div>
        <div class="home-content">
            <div class="row pb-3 align-items-center" style="color:#512DA8; font-weight:bold">
                <div class="col-12 col-md-7">
                    <label>Add New Coupon Card</label>
                </div>
                <div class="col-12 col-md-5 text-md-end text-start mt-3 mt-md-0">
                    <button class="btn btn-danger customBtn-clear" id="clear"><i class="fa-solid fa-eraser"
                            style="padding-right: 5px"></i>Clear</button>
                    <button type="submit" form="couponCardCreateForm" class="btn btn-primary customBtn-save ms-1 mt-0"><i
                            class="fa-regular fa-floppy-disk" style="padding-right: 5px"></i>Save</button>
                </div>
            </div>

            @if (session('success'))
                <div id="flash-message" class="alert alert-success alert-dismissible d-flex align-items-center fade show">
                    <i class="fa-solid fa-circle-check"></i>
                    <strong class="mx-2">Success!</strong> {{ session('success') }}
                </div>
            @elseif(session('update'))
                <div id="flash-message" class="alert alert-success alert-dismissible d-flex align-items-center fade show">
                    <i class="fas fa-edit"></i>
                    <strong class="mx-2">Updated!</strong> {{ session('update') }}
                </div>
            @elseif(session('delete'))
                <div id="flash-message" class="alert alert-danger alert-dismissible d-flex align-items-center fade show">
                    <i class="fa-solid fa-trash"></i>
                    <strong class="mx-2">Deleted!</strong> {{ session('delete') }}
                </div>
            @endif

            <div id="coupon_card_info_label" class="row align-items-center bg-white">
                <div class="col-10">
                    <label><i class="fa-solid fa-ticket" style="padding-left:5px; padding-right: 18px"></i>Coupon
                        Card
                        Info</label>
                </div>
                <div class="col-2" style="text-align: right">
                    <i class="bx bxs-chevron-down arrow"></i>
                </div>
            </div>
            <div class="coupon_card_info_container shadow-sm">
                <form action="{{ route('couponCard#create') }}" method="POST" id="couponCardCreateForm">
                    @csrf
                    <div class="row border border-4 border-gray-800" style="border-radius: 10px">
                        <div class="coupon-left col-6 pt-4 pb-4 border-end border-4 border-gray-800">
                            <div class="row align-items-center mb-3 justify-content-center">
                                <div class="col-5">
                                    <label class="col-form-label">Coupon Generate</label>
                                </div>
                                <div class="col-5">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" id="coupon_generate" name="coupon_generate"
                                            type="checkbox" role="switch">
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3 justify-content-center">
                                <div class="col-5">
                                    <label class="col-form-label">Coupon Count <span style="color: red">*</span></label>
                                </div>
                                <div class="col-5">
                                    <input class="form-control @error('coupon_count') is-invalid @enderror muted"
                                        type="number" id="coupon_count" name="coupon_count" style="text-align: end"
                                        value="1" readonly>
                                    @error('coupon_count')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3 justify-content-center">
                                <div class="col-5">
                                    <label class="col-form-label">Coupon Code</label>
                                </div>
                                <div class="col-5">
                                    <input class="form-control  muted" type="text" id="coupon_code" name="coupon_code"
                                        style="text-align: end" readonly>
                                </div>
                            </div>
                            <div class="row mb-3 justify-content-center">
                                <div class="col-5">
                                    <label class="col-form-label">Coupon Name <span style="color: red">*</span></label>
                                </div>
                                <div class="col-5">
                                    <input class="form-control @error('coupon_name') is-invalid @enderror" type="text"
                                        id="coupon_name" name="coupon_name" value="{{ old('coupon_name') }}">
                                    @error('coupon_name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                        </div>
                        <div class="coupon-right col-6 pt-4 pb-4">
                            <div class="row mb-3 align-items-baseline justify-content-center ">
                                <div class="col-5">
                                    <label class="col-form-label">Discount Type</label>
                                </div>
                                <div class="col-5">
                                    <div class="form-check">
                                        <input class="form-check-input discount-type" type="radio"
                                            name="radio_discount_type" id="amount_lbl" value="Amount" checked>
                                        <label class="form-check-label" for="amount_lbl">
                                            Amount
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input discount-type" type="radio"
                                            name="radio_discount_type" id="percent_lbl" value="Percent">
                                        <label class="form-check-label" for="percent_lbl">
                                            Percent %
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3 justify-content-center">
                                <div class="col-5">
                                    <label class="col-form-label">Amount Discount <span
                                            style="color: red">*</span></label>
                                </div>
                                <div class="col-5">
                                    <input class="form-control @error('amount_discount') is-invalid @enderror"
                                        style="text-align: end" type="text" name="amount_discount"
                                        id="amount_discount" value="{{ old('amount_discount') }}">
                                    @error('amount_discount')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3 justify-content-center">
                                <div class="col-5">
                                    <label class="col-form-label">Percent Discount <span
                                            style="color: red">*</span></label>
                                </div>
                                <div class="col-5">
                                    <input class="form-control muted @error('percent_discount') is-invalid @enderror"
                                        style="text-align: end" type="text" name="percent_discount"
                                        id="percent_discount" value="{{ old('percent_discount', '0') }}" readonly>
                                    @error('percent_discount')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3 justify-content-center">
                                <div class="col-5">
                                    <label class="col-form-label">Min-Order Amount <span
                                            style="color: red">*</span></label>
                                </div>
                                <div class="col-5">
                                    <input class="form-control @error('min_order_amount') is-invalid @enderror"
                                        type="text" name="min_order_amount" id="min_order_amount"
                                        value="{{ old('min_order_amount') }}">
                                    @error('min_order_amount')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3 justify-content-center">
                                <div class="col-5">
                                    <label class="col-form-label">Expire Date <span style="color: red">*</span></label>
                                </div>
                                <div class="col-5">
                                    <input class="form-control @error('expire_date') is-invalid @enderror" type="date"
                                        id="expire_date" name="expire_date" value="{{ old('expire_date') }}">
                                    @error('expire_date')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                        </div>
                    </div>
                </form>
            </div>
            <div id="coupon_card_list_label" class="row align-items-center bg-white">
                <div class="col-6">
                    <label><i class="fa-solid fa-table-list" style="padding-left:5px; padding-right: 19px"></i>Coupon Card
                        Lists</label>
                </div>
                <div class="col-6" style="text-align: right">
                    <i class="bx bxs-chevron-down arrow"></i>
                </div>
            </div>
            <div class="coupon_card_list_container shadow-sm ">
                <table id="coupon_card_list" class="table table-striped nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Coupon Code</th>
                            <th>Coupon Name</th>
                            <th>Discount Type</th>
                            <th>Amount Discount</th>
                            <th>Percent Discount</th>
                            <th>Min-Order Amount</th>
                            <th>Expire Date</th>
                            <th>Expired</th>
                            <th>Used</th>
                            <th>Discontinued</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($coupons) != 0)
                            @php
                                $count = 1;
                            @endphp
                            @foreach ($coupons as $coupon)
                                <tr>
                                    <td>{{ $count }}</td>
                                    <td>{{ $coupon['coupon_code'] }}</td>
                                    <td>{{ $coupon['coupon_name'] }}</td>
                                    <td>{{ $coupon['discount_type'] }}</td>
                                    <td>{{ $coupon['amount_discount'] }}</td>
                                    <td>{{ $coupon['percent_discount'] }}</td>
                                    <td>{{ $coupon['min_order_amount'] }}</td>
                                    <td>{{ date('d-M-Y', strtotime($coupon['expire_date'])) }}</td>
                                    @php
                                        $expireDate = \Carbon\Carbon::parse($coupon['expire_date']); // Replace this with your actual expiry date
                                    @endphp
                                    @if (\Carbon\Carbon::now()->gt($expireDate))
                                        <td><input class="form-check-input" type="checkbox" checked
                                                onclick="return false;" style="background-color: red; border-color:red">
                                        </td>
                                    @else
                                        <td><input class="form-check-input" type="checkbox" onclick="return false;"></td>
                                    @endif
                                    @if ($coupon['is_used'] == 0)
                                        <td><input class="form-check-input" type="checkbox" onclick="return false;"></td>
                                    @elseif ($coupon['is_used'] == 1)
                                        <td><input class="form-check-input" type="checkbox" checked
                                                onclick="return false;">
                                        </td>
                                    @endif
                                    @if ($coupon['is_discontinued'] == 0)
                                        <td><input class="form-check-input" type="checkbox" onclick="return false;"></td>
                                    @elseif ($coupon['is_discontinued'] == 1)
                                        <td><input class="form-check-input" type="checkbox" checked
                                                onclick="return false;">
                                        </td>
                                    @endif
                                    <td><a data-coupon_id="{{ $coupon['coupon_id'] }}"
                                            data-coupon_code="{{ $coupon['coupon_code'] }}"
                                            data-coupon_name="{{ $coupon['coupon_name'] }}"
                                            data-discount_type="{{ $coupon['discount_type'] }}"
                                            data-amount_discount="{{ $coupon['amount_discount'] }}"
                                            data-percent_discount="{{ $coupon['percent_discount'] }}"
                                            data-min_order_amount="{{ $coupon['min_order_amount'] }}"
                                            data-expire_date="{{ $coupon['expire_date'] }}"
                                            data-is_discontinued="{{ $coupon['is_discontinued'] }}"
                                            data-bs-toggle="modal" data-bs-target="#edit_coupon_modal"
                                            class="edit_coupon_modal_dialog"><i class="fa-solid fa-pen"
                                                style="color: blue; cursor: pointer;"></i></a></td>
                                    <td><a data-coupon_id="{{ $coupon['coupon_id'] }}"
                                            data-coupon_code="{{ $coupon['coupon_code'] }}" data-bs-toggle="modal"
                                            data-bs-target="#delete_coupon_modal" class="delete_coupon_modal_dialog"><i
                                                class="fa-regular fa-trash-can"
                                                style="color: red;cursor: pointer;"></i></a>
                                    </td>
                                </tr>
                                @php
                                    $count++;
                                @endphp
                            @endforeach
                        @endif
                    </tbody>
                </table>
                <!--Edit Coupon Card Modal -->
                <div class="modal fade" id="edit_coupon_modal" data-bs-backdrop="static" data-bs-keyboard="false"
                    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header text-center" style="background-color: #512DA8">
                                <h1 class="modal-title fs-5 w-100" id="staticBackdropLabel" style="color: white">Update
                                    Coupon
                                </h1>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body" style="margin-left: 20px; margin-right:20px">
                                <form action="{{ route('couponCard#update') }}" method="POST"
                                    id="couponCardEditModalForm">
                                    @csrf
                                    <input type="text" name="edit_coupon_card_id" id="edit_coupon_card_id" hidden>
                                    <div class="row mb-3 mt-3">
                                        <div class="col-5">
                                            <label class="form-label">Coupon Code</label>
                                        </div>
                                        <div class="col">
                                            <input class="form-control muted" type="text" name="edit_coupon_code"
                                                id="edit_coupon_code" readonly>
                                        </div>
                                    </div>
                                    <div class="row mb-3 mt-3">
                                        <div class="col-5">
                                            <label class="form-label">Coupon Name <span
                                                    style="color: red">*</span></label>
                                        </div>
                                        <div class="col">
                                            <input class="form-control" type="text" name="edit_coupon_name"
                                                id="edit_coupon_name">
                                        </div>
                                    </div>
                                    <div class="row mb-3 mt-3">
                                        <div class="col-5">
                                            <label class="col-form-label">Discount Type</label>
                                        </div>
                                        <div class="col">
                                            <div class="form-check">
                                                <input class="form-check-input edit_discount-type" type="radio"
                                                    name="edit_radio_discount_type" id="edit_amount_lbl" value="Amount"
                                                    checked>
                                                <label class="form-check-label" for="edit_amount_lbl">
                                                    Amount
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input edit_discount-type" type="radio"
                                                    name="edit_radio_discount_type" id="edit_percent_lbl"
                                                    value="Percent">
                                                <label class="form-check-label" for="edit_percent_lbl">
                                                    Percent %
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row align-items-center mb-3">
                                        <div class="col-5">
                                            <label class="col-form-label">Amount Discount <span
                                                    style="color: red">*</span></label>
                                        </div>
                                        <div class="col">
                                            <input class="form-control" style="text-align: end" type="text"
                                                name="edit_amount_discount" id="edit_amount_discount">
                                        </div>
                                    </div>
                                    <div class="row align-items-center mb-3">
                                        <div class="col-5">
                                            <label class="col-form-label">Percent Discount <span
                                                    style="color: red">*</span></label>
                                        </div>
                                        <div class="col">
                                            <input class="form-control" style="text-align: end" type="text"
                                                name="edit_percent_discount" id="edit_percent_discount">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-5">
                                            <label class="col-form-label">Min-Order Amount <span
                                                    style="color: red">*</span></label>
                                        </div>
                                        <div class="col">
                                            <input class="form-control" style="text-align: end" type="text"
                                                name="edit_min_order_amount" id="edit_min_order_amount">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-5">
                                            <label class="col-form-label">Expire Date <span
                                                    style="color: red">*</span></label>
                                        </div>
                                        <div class="col">
                                            <input class="form-control" type="date" id="edit_expire_date"
                                                name="edit_expire_date">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-5">
                                            <label class="form-label text-danger">Discontinued</label>
                                        </div>
                                        <div class="col">
                                            <input class="form-check-input" type="checkbox"
                                                name="edit_coupon_card_is_discontinued"
                                                id="edit_coupon_card_is_discontinued">
                                        </div>
                                    </div>
                                </form>

                            </div>
                            <div class="modal-footer" style="margin-right: 20px">
                                <input type="submit" class="btn custom_btn" value="Update"
                                    form="couponCardEditModalForm">
                            </div>
                        </div>
                    </div>
                </div>
                <!--Delete Coupon Card Modal -->
                <div class="modal fade" id="delete_coupon_modal" data-bs-backdrop="static" data-bs-keyboard="false"
                    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        {{-- <div class="modal-content">
                            <div class="modal-header text-center" style="background-color: #512DA8">
                                <h1 class="modal-title fs-5 w-100" id="delete_modal_header" style="color: white">
                                </h1>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body" style="margin-left: 20px; margin-right:20px">
                                <form action="{{ route('couponCard#delete') }}" method="POST"
                                    id="couponCardDeleteModalForm">
                                    @csrf
                                    <input type="text" name="delete_coupon_card_id" id="delete_coupon_card_id" hidden>
                                    <div class="row align-items-center mb-3 mt-3">
                                        <div>
                                            <label class="form-label">Are you sure want to delete?</label>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer" style="margin-right: 20px">
                                <input type="submit" class="btn btn-danger" value="Delete"
                                    form="couponCardDeleteModalForm">
                            </div>
                        </div> --}}
                        <div class="success-card" style="padding-bottom: 1.5rem;">
                            <button class="btn-cross-custom" data-bs-dismiss="modal">
                                <i class="fa-solid fa-x"></i>
                            </button>

                            <div class="icon-wrapper">
                                <div class="error-icon-circle">
                                    <i class="fa-solid fa-exclamation-triangle"></i>
                                </div>
                            </div>

                            <div class="text-content">
                                <h2 id="delete_modal_header" class="success-title">
                                    Submission Failed
                                </h2>
                                <p class="success-desc">
                                    Are you sure you want to delete?
                                </p>
                            </div>

                            <form action="{{ route('couponCard#delete') }}" method="POST"
                                id="couponCardDeleteModalForm">
                                @csrf
                                <input type="text" name="delete_coupon_card_id" id="delete_coupon_card_id" hidden>
                                <div class="d-flex justify-content-center mt-3">
                                    <button type="submit" class="btn btn-danger px-4">Delete</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="{{ asset('script/links_js/jquery.3.7.1.min.js') }}"></script>
    <script src="{{ asset('script/links_js/jquery.dataTables.1.13.7.min.js') }}"></script>
    <script src="{{ asset('script/links_js/dataTables.bootstrap5_1.13.7.min.js') }}"></script>
    <script src="{{ asset('script/links_js/jquery.1.11.1.min.js') }}"></script>
    <script src="{{ asset('script/links_js/jquery.validate.1.19.5.js') }}"></script>
    <script src="{{ asset('script/coupon_card_script.js') }}"></script>
    <script>
        var couponLastID = parseInt(@json($couponLastID));
        couponLastID = couponLastID + 1;

        $('#coupon_code').val("CP-" + couponLastID);

        $("#clear").click(function() {
            $("#coupon_generate").prop('checked', false);
            $('#coupon_count').prop('readonly', true).val("1").addClass('muted');
            $('#coupon_code').val("CP-" + couponLastID);
            $("#coupon_name").val("");
            $('#amount_lbl').prop('checked', true);
            $('#percent_lbl').prop('checked', false);
            $('#amount_discount').prop('readonly', false).val("").removeClass('muted');
            $('#percent_discount').prop('readonly', true).val("0").addClass('muted');
            $("#min_order_amount").val("");
            $("#expire_date").val("");
        });

        $('#coupon_generate').change(function() {
            var coupon_generate = document.getElementById("coupon_generate");
            var switchState = coupon_generate.checked;

            if (switchState) {
                $('#coupon_count').on('input', function() {
                    var couponCount = $(this).val();
                    var totalCoupon = parseInt(couponLastID) + parseInt(couponCount - 1);
                    if (couponCount != "") {
                        if (couponCount == 1) {
                            $('#coupon_code').val("CP-" + couponLastID);
                        } else {
                            $('#coupon_code').val("CP-" + couponLastID + " to " + "CP-" + totalCoupon);
                        }

                    }

                });
            } else {
                $('#coupon_code').val("CP-" + couponLastID);
            }

        });
    </script>
@endsection
