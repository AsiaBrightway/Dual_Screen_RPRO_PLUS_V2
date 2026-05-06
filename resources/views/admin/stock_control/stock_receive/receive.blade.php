@extends('layouts.admin.master')
@section('title', 'Stock Receive')

@section('content')
    <section class="home-section">
        <div class="home-title">
            <i class='bx bx-menu'></i>
            <span class="text">Stock Receive</span>
        </div>
        <div class="home-content" style="height: 100% !important">
            <div class="row pb-3 align-items-center" style="color:#512DA8; font-weight:bold">
                <div class="col-12 col-md-7">
                    <label>Add New Stock Receive</label>
                </div>
                <div class="col-12 col-md-5 text-md-end text-start mt-3 mt-md-0">
                    <button class="btn btn-danger customBtn-clear" id="btn_New"><i class="fa-solid fa-eraser"
                            style="padding-right: 5px"></i>Clear</button>
                    <button class="btn btn-primary customBtn-save ms-1 mt-0" id="btn_Save"><i class="fa-regular fa-floppy-disk"
                            style="padding-right: 5px"></i>Save</button>
                </div>
            </div>

            @if(session('success'))
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

            <div id="receive_info_label" class="row align-items-center bg-white">
                <div class="col-10">
                    <label><i class="fa-regular fa-newspaper" style="padding-left:5px; padding-right: 12px"></i>Receive
                        Info</label>
                </div>
                <div class="col-2" style="text-align: right">
                    <i class="bx bxs-chevron-down arrow"></i>
                </div>
            </div>
            <div class="receive_info_container shadow-sm show_container">
                <div class="row">
                    <div class="receive_info_left col-5">
                        <div class="row">
                            <div class="col-4 mb-3">
                                <label class="form-label">Voucher No</label>
                            </div>
                            <div class="col-8">
                                <input type="text" id="receiveID" name="receiveID" hidden>
                                <input type="text" id="loginUserID" name="loginUserID" value="{{ Auth::User()->id }}"
                                    hidden>
                                <input class="form-control" type="text" id="voucher_no" name="voucher_no"
                                    value="{{ $voucherNumber }}" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <label class="form-label">Date</label>
                            </div>
                            <div class="col-8">
                                <input class="form-control" id="receive_date" name="receive_date" type="date">
                            </div>
                        </div>
                    </div>
                    <div class="receive_info_right col-5 offset-2">
                        <textarea class="form-control" id="remark" name="remark" rows="3" placeholder="Remarks"></textarea>
                    </div>
                </div>
            </div>
            <div id="item_details_info_label" class="row align-items-center bg-white">
                <div class="col-10">
                    <label><i class="fa-solid fa-cookie-bite" style="padding-left:5px; padding-right: 13px"></i>Item Details Info</label>
                </div>
                <div class="col-2" style="text-align: right">
                    <i class="bx bxs-chevron-down arrow"></i>
                </div>
            </div>
            <div class="item_details_info_container shadow-sm show_container" style="margin-bottom:90px; overflow-y:auto">
                <div class="row">
                    <div class="item_detail_left col-5">
                        <div class="row mb-3">
                            <div class="col-5">
                                <label class="form-label">Item Name</label>
                            </div>
                            <div class="col-7">
                                <div class="row">
                                    <div class="col-8">
                                        <select class="form-select select-option" id="item_name" name="item_name">
                                            @if (count($menu_item) != 0)
                                                <option value="0">--Select One--</option>
                                                @foreach ($menu_item as $item)
                                                    <option value="{{ $item->item_id }}">{{ $item->item_name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <span class="text-danger">
                                            <span id="item_name_error"></span>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <button class="btn btn-primary" id="btn_AddItem" data-bs-toggle="modal"
                                            data-bs-target="#add_item_modal"><i class="fa-solid fa-plus"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-5">
                                <label class="form-label">Item Code</label>
                            </div>
                            <div class="col-7">
                                <select class="form-select" id="item_code" name="item_code">
                                    @if (count($menu_item) != 0)
                                        <option value="0">--Select One--</option>
                                        @foreach ($menu_item as $item)
                                            <option value="{{ $item->item_id }}">{{ $item->item_code }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-5">
                                <label class="form-label">Bar Code</label>
                            </div>
                            <div class="col-7">
                                <input class="form-control muted" id="bar_code" name="bar_code" type="text"
                                    readonly>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-5">
                                <label class="form-label">Unit</label>
                            </div>
                            <div class="col-7">
                                {{--  <input class="form-control" id="unit_name" name="unit_name" type="text" disabled>  --}}
                                <select class="form-select" id="unit_name" name="unit_name" readonly>
                                    {{-- @if (count($menu_item) != 0)
                                    <option value="0">--Select One--</option>
                                    @foreach ($menu_item as $item)
                                    <option value="{{$item->unit_id}}">{{$item->unit_name}}</option>
                                    @endforeach
                                    @endif --}}
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-5">
                                <label class="form-label">Store Quantity</label>
                            </div>
                            <div class="col-7">
                                <input class="form-control muted" id="store_Qty" name="store_Qty" type="text"
                                    value="0" readonly>
                                <span class="text-danger">
                                    <span id="store_Qty_error"></span>
                                </span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-5">
                                <label class="form-label">Quantity</label>
                            </div>
                            <div class="col-7">
                                <input class="form-control" id="quantity" name="quantity" type="number"
                                    required="required" min="1" onkeypress="return /[0-9]/i.test(event.key)"
                                    value="1" min="1">
                                <span class="text-danger">
                                    <span id="quantity_error" style="font-size: 13px"></span>
                                </span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-5">
                                <label class="form-label">Unit Cost</label>
                            </div>
                            <div class="col-7">
                                <input class="form-control" id="unit_cost" name="unit_cost" type="text"
                                    required="required" onkeypress="return /[0-9]/i.test(event.key)">
                                <span class="text-danger">
                                    <span id="unit_cost_error"></span>
                                </span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-5">
                                <label class="form-label">Amount</label>
                            </div>
                            <div class="col-7">
                                <input class="form-control muted" id="amount" name="amount" type="text"
                                    placeholder="0" readonly>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-5">
                                <label class="form-label">Expire Date</label>
                            </div>
                            <div class="col-7">
                                <input class="form-control" id="expire_date" name="expire_date" type="date">
                            </div>
                        </div>
                        <div>
                            <button class="btn btn-primary mt-2" id="btn_Add"><i class="fa-solid fa-plus"
                                    style="padding-right: 5px"></i>Add</button>
                            <button class="btn btn-warning text-white mt-2" id="btn_Remove"><i class="fa-solid fa-minus"
                                    style="padding-right: 5px"></i>Remove</button>
                            <button class="btn btn-danger mt-2" id="btn_ClearDetail"><i class="fa-solid fa-eraser"
                                    style="padding-right: 5px"></i>Clear</button>
                        </div>
                    </div>
                    <div class="item_detail_right col-7">
                        <div class=".table-responsive border border-2"
                            style="height:52vh; overflow-x:auto; white-space:nowrap; padding-left:10px; padding-right:10px; border-radius:10px;">
                            <table class="table table-hover stockReceiveTable" id="dataTable">
                                <thead class="sticky-top">
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Item Name</th>
                                        <th scope="col">Item Code</th>
                                        <th scope="col">Bar Code</th>
                                        <th scope="col">Unit</th>
                                        <th scope="col">Quantity</th>
                                        <th scope="col">Unit Cost</th>
                                        <th scope="col">Amount</th>
                                        <th scope="col">Expire Date</th>
                                    </tr>
                                </thead>
                                <tbody id="tableBody">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Item Detail Validation modal --}}
        <div class="modal fade" id="checkValidationModal" tabindex="-1"
            aria-labelledby="checkValidationModalLabel" data-bs-backdrop="static" data-bs-keyboard="false"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="success-card">      
                    <button class="btn-cross-custom" data-bs-dismiss="modal">
                        <i class="fa-solid fa-x"></i>
                    </button>
                    
                    <div class="icon-wrapper">
                        <div class="error-icon-circle">
                            <i class="fa-solid fa-exclamation-triangle"></i>
                        </div>
                    </div>
                    
                    <div class="text-content">
                        <h2 class="success-title">
                            Submission Failed
                        </h2>
                        <p class="success-desc">
                            You need to set item details first!
                        </p>
                    </div>
                    
                </div>
            </div>
        </div>

        {{-- Success Modal --}}
        <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel"
            data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="success-card">      
                    <button id="btn_successOK" class="btn-cross-custom" data-bs-dismiss="modal">
                        <i class="fa-solid fa-x"></i>
                    </button>
                    
                    <div class="icon-wrapper">
                        <div class="icon-circle">
                            <i class="fa-solid fa-check"></i>
                        </div>
                    </div>
                    
                    <div class="text-content">
                        <h2 class="success-title">
                            Success
                        </h2>
                        <p class="success-desc">
                            Receive record was saved successfully.
                        </p>
                    </div>
                    
                </div>
            </div>
        </div>

        <!--Add item Modal -->
        <div class="modal fade" id="add_item_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header text-center" style="background-color: #512DA8">
                        <h1 class="modal-title fs-5 w-100" id="staticBackdropLabel" style="color: white">Add
                            Item
                        </h1>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>

                    </div>
                    <div class="modal-body" style="margin-left: 20px; margin-right:20px">
                        <form action="{{ route('receive#itemCreate') }}" method="POST" id="itemCreateModalForm"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row mb-3 justify-content-center">
                                <div class="col-5">
                                    <label class="col-form-label">Main Category <span style="color: red">*</span></label>
                                </div>
                                <div class="col-6">
                                    <select class="form-select" id="create_main_category" name="create_main_category">
                                        <option value="0">Select-</option>
                                        @if (count($mainCategories) != 0)
                                            @foreach ($mainCategories as $mainCategory)
                                                <option value={{ $mainCategory['main_category_id'] }}>
                                                    {{ $mainCategory['main_category_name'] }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <div class="invalid-feedback" style="font-size: 0.75rem;"></div>
                                </div>
                            </div>
                            <div class="row mb-3 justify-content-center">
                                <div class="col-5">
                                    <label class="col-form-label">Sub Category <span style="color: red">*</span></label>
                                </div>
                                <div class="col-6">
                                    <select class="form-select" id="create_sub_category" name="create_sub_category">
                                        <!-- Options will be populated based on the selected main category using JavaScript -->
                                    </select>
                                    <div class="invalid-feedback" style="font-size: 0.75rem;"></div>
                                </div>
                            </div>
                            <div class="row mb-3 justify-content-center">
                                <div class="col-5">
                                    <label class="col-form-label">Item Type <span style="color: red">*</span></label>
                                </div>
                                <div class="col-6">
                                    <select class="form-select" id="create_item_type" name="create_item_type">
                                        @if (count($itemTypes) != 0)
                                            @foreach ($itemTypes as $itemType)
                                                <option value={{ $itemType['item_type_id'] }}>
                                                    {{ $itemType['item_type_name'] }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3 justify-content-center">
                                <div class="col-5">
                                    <label class="col-form-label">Item Code <span style="color: red">*</span></label>
                                </div>
                                <div class="col-6">
                                    {{-- <input class="form-control @error('create_item_code') is-invalid @enderror" type="text" id="create_item_code"
                                        name="create_item_code" value="{{ old('create_item_code') }}">
                                    @error('create_item_code')
                                        <div class="invalid-feedback" style="font-size: 0.82rem;">
                                            {{ $message }}
                                        </div>
                                    @enderror --}}
                                    <input class="form-control" type="text" id="create_item_code" name="create_item_code">
                                    <div class="invalid-feedback" style="font-size: 0.82rem;"></div>
                                </div>
                            </div>
                            {{-- <div class="row mb-3 justify-content-center">
                                <div class="col-5">
                                    <label class="col-form-label">Bar Code <span style="color: red">*</span></label>
                                </div>
                                <div class="col-6">
                                    <input class="form-control" type="text" id="create_bar_code" name="create_bar_code">
                                    <div class="invalid-feedback" style="font-size: 0.82rem;"></div>
                                </div>
                            </div> --}}
                            <div class="row mb-3 justify-content-center">
                                <div class="col-5">
                                    <label class="col-form-label">Item Name <span style="color: red">*</span></label>
                                </div>
                                <div class="col-6">
                                    <input class="form-control" type="text" id="create_item_name" name="create_item_name">
                                    <div class="invalid-feedback" style="font-size: 0.82rem;"></div>
                                </div>
                            </div>
                            <div class="row mb-3 justify-content-center">
                                <div class="col-5">
                                    <label class="col-form-label">Other Name</label>
                                </div>
                                <div class="col-6">
                                    <input class="form-control" type="text" id="create_other_name"
                                        name="create_other_name">
                                </div>
                            </div>
                            <div class="row mb-3 justify-content-center">
                                <div class="col-5">
                                    <label class="col-form-label">Item Unit <span style="color: red">*</span></label>
                                </div>
                                <div class="col-6">
                                    <select class="form-select" id="create_item_unit" name="create_item_unit">
                                        @if (count($units) != 0)
                                            @foreach ($units as $unit)
                                                <option value={{ $unit['unit_id'] }}>
                                                    {{ $unit['unit_name'] }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3 justify-content-center">
                                <div class="col-5">
                                    <label class="col-form-label">Item Image</label>
                                </div>
                                <div class="col-6">
                                    <input type="file" class="form-control form-control-sm" id="create_item_image"
                                        name="create_item_image">
                                </div>
                            </div>
                            <div class="row mb-3 justify-content-center">
                                <div class="col-5">
                                    <label class="col-form-label">Unit Cost</label>
                                </div>
                                <div class="col-6">
                                    <input class="form-control" value="0" type="text" id="create_unit_cost" name="create_unit_cost">
                                    <div class="invalid-feedback" style="font-size: 0.82rem;"></div>
                                </div>
                            </div>
                            <div class="row mb-3 justify-content-center">
                                <div class="col-5">
                                    <label class="col-form-label">Selling Price <span style="color: red">*</span></label>
                                </div>
                                <div class="col-6">
                                    <input class="form-control" type="text" id="create_item_selling_price" name="create_item_selling_price">
                                    <div class="invalid-feedback" style="font-size: 0.82rem;"></div>
                                </div>
                            </div>
                            <div class="row justify-content-center">
                                <div class="col-5">
                                    <label class="col-form-label">Discontinued</label>
                                </div>
                                <div class="col-6">
                                    <input class="form-check-input" type="checkbox" name="create_is_discontinued"
                                        id="create_is_discontinued">
                                </div>
                            </div>
                        </form>

                    </div>
                    <div class="modal-footer" style="margin-right: 20px">
                        <input type="submit" class="btn custom_btn" value="Create" form="itemCreateModalForm">
                    </div>

                </div>
            </div>
        </div>


    </section>
    <script src="{{ asset('script/links_js/jquery.3.6.4.min.js') }}"></script>
    <script src="{{ asset('script/links_js/jquery.validate.1.19.5.js') }}"></script>
    <script src="{{ asset('script/links_js/jquery.dataTables.1.13.7.min.js') }}"></script>
    <script src="{{ asset('script/links_js/dataTables.bootstrap5_1.13.7.min.js') }}"></script>
    <script src="{{ asset('script/receive_script.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            var receiveDetailList = [];
            //*********************date*********************
            var date = new Date();
            var day = date.getDate();
            var month = date.getMonth() + 1; // month is zero-based
            var year = date.getFullYear();

            if (month < 10) month = "0" + month;
            if (day < 10) day = "0" + day;
            var today = year + "-" + month + "-" + day;
            document.getElementById('receive_date').value = today;
            document.getElementById('expire_date').value = today;

            //*****************Total Amount*****************
            function getTotal() {
                var unit_cost = document.getElementById("unit_cost").value;
                var quantity = document.getElementById("quantity").value;
                if (unit_cost && quantity) {
                    var total = unit_cost * quantity;
                    document.getElementById("amount").value = total;
                } else {
                    document.getElementById("amount").value = "0";
                }

            };

            $('#quantity').on('input', function() {
                getTotal($(this).val());
            });
            $('#unit_cost').keyup(getTotal);

            $(document).on("change", "#item_name, #item_code", SelectedItemChange);

            //**************item selected change****************
            function SelectedItemChange() {
                let itemID = $(this).val();
                var menu_item = @json($menu_item->toArray());
                var selected_item = menu_item.filter(x => x.item_id == itemID);
                if (itemID > 0) {
                    $('#item_name').val(selected_item[0].item_id);
                    $('#item_code').val(selected_item[0].item_id);
                    $('#bar_code').val(selected_item[0].bar_code);
                    $('#unit_name').empty();
                    $('#unit_name').append('<option value="' + selected_item[0].unit_id + '">' + selected_item[0]
                        .unit_name +
                        '</option>');
                    $('#unit_name').val(selected_item[0].unit_id);
                    // Reset unit_cost and amount immediately when a new item is selected
                    $('#unit_cost').val('');
                    $('#amount').val('');
                    $.ajax({
                        url: "checkStoreQty",
                        type: "get",
                        data: {
                            itemID: itemID,
                            unitID: $('#unit_name').val()
                        },
                        contentType: 'application/json; charset=utf-8',
                        success: function(response) {
                            console.log(response);
                            let Store_Qty = response.success;
                            let Unit_Cost = response.unitCost;
                            $('#store_Qty').val(Store_Qty);
                            $('#unit_cost').val(Unit_Cost);
                            // Recalculate amount after unit_cost is loaded
                            getTotal();
                        }
                    });
                    clearTextError();
                }
            };

            //*************Clear Button****************************
            function clearDetail() {
                $('#item_name').val(0);
                $('#item_code').val(0);
                $('#bar_code').val('');
                $('#unit_name').val('');
                $('#store_Qty').val('0');
                $('#quantity').val('1');
                $('#unit_cost').val('');
                $('#amount').val('');
                $('#remark').val();
                $('#expire_date').val(today);

                $('#item_name').prop('disabled', false);
                $('#item_code').prop('disabled', false);
                $('#unit_name').prop('disabled', false);
                $('#btn_Add').html('<i class="fa-solid fa-plus" style="padding-right: 5px"></i>Add');
                clearTextError();
            };
            $('#btn_ClearDetail').click(clearDetail);

            //**********************clear span text*************************
            function clearTextError() {
                $('#item_name').removeClass('is-invalid');
                $('#item_name_error').html("");

                $('#quantity').removeClass('is-invalid');
                $('#quantity_error').html("");

                $('#unit_cost').removeClass('is-invalid');
                $('#unit_cost_error').html("");
            };

            //**********************Validation*************************
            function validationDetail() {
                clearTextError();

                var hasErrorCount = 0;
                if ($('#item_name').val() == 0) {
                    $('#item_name_error').html("Item Name ရွေးရန်လိုအပ်ပါသည်");
                    $('#item_name').focus();
                    $('#item_name').addClass('is-invalid');
                    hasErrorCount += 1;
                }
                if ($('#quantity').val() == "" || $('#quantity').val() == "0") {
                    $('#quantity_error').html("Quantity ဖြည့်ရန်လိုအပ်ပါသည်");
                    $('#quantity').focus();
                    $('#quantity').addClass('is-invalid');
                    hasErrorCount += 1;
                } else {
                    var storeQty = $('#store_Qty').val();
                    var receiveQty = $('#quantity').val();
                    var calculated_qty = parseInt(storeQty) + parseInt(receiveQty);

                    if (calculated_qty < 0) {
                        $('#quantity_error').html("Receive Qty သည် Store Qty ကို ပြန်ဖြည့်ရန် မလုံလောက်ပါ။");
                        $('#quantity').focus();
                        $('#quantity').addClass('is-invalid');
                        hasErrorCount += 1;
                    }
                }
                if ($('#unit_cost').val() == "") {
                    $('#unit_cost_error').html("Unit Cost ဖြည့်ရန်လိုအပ်ပါသည်");
                    $('#unit_cost').focus();
                    $('#unit_cost').addClass('is-invalid');
                    hasErrorCount += 1;
                }
                if (hasErrorCount > 0) {
                    throw ("Invalid!");
                }
                // if ($('#unit_cost').val() == "" || $('#unit_cost').val() == "0") {
                //     $('#unit_cost_error').html("Unit Cost ဖြည့်ရန်လိုအပ်ပါသည်");
                //     $('#unit_cost').focus();
                //     $('#unit_cost').addClass('is-invalid');
                //     hasErrorCount += 1;
                // }
                // if (hasErrorCount > 0) {
                //     throw ("Invalid!");
                // }
            };
            //**********************rowCount*************************
            function rowCount() {
                var i = 0;
                var tRows = document.querySelectorAll('#tableBody tr');
                $(tRows).each(function(key, value) {
                    ++i;
                    $('td:first-child', this).text(i);
                });
            };

            //*************Double Click**************************
            // Add event listener to tbody for double-click
            let lastTap = 0;

            function handleRowClick($rowClicked) {
                var itemcode = $rowClicked.find('td:eq(3)').text();
                var selecteddetail = receiveDetailList.filter(x => x.item_code == itemcode);
                var rowData = selecteddetail[0];

                // Populate input fields with the data from the clicked row
                document.getElementById('item_name').value = rowData.item_id; // item_name
                document.getElementById('item_code').value = rowData.item_id; // item_code
                document.getElementById('bar_code').value = rowData.bar_code; // bar_code
                document.getElementById('unit_name').value = rowData.unit_id; // unit_name
                document.getElementById('store_Qty').value = rowData.store_qty; // store_Qty
                document.getElementById('quantity').value = rowData.quantity; // quantity
                document.getElementById('unit_cost').value = rowData.unit_cost; // unit_cost
                document.getElementById('amount').value = rowData.quantity * rowData.unit_cost; // amount
                document.getElementById('expire_date').value = rowData.expire_date; // expire_date

                // Update UI states
                $('#item_name').prop('disabled', true);
                $('#item_code').prop('disabled', true);
                $('#unit_name').prop('disabled', true);
                $('#btn_Remove').prop('disabled', false);
                $('#btn_Add').html('<i class="fa-solid fa-plus" style="padding-right: 5px"></i>Update');
            }

            // Handle double-click on desktop
            $('#tableBody').on('dblclick', 'tr', function(event) {
                handleRowClick($(this));
            });

            // Handle double-tap on touch devices
            $('#tableBody').on('touchstart', 'tr', function(event) {
                let currentTime = new Date().getTime();
                let tapLength = currentTime - lastTap;
                if (tapLength < 500 && tapLength > 0) { // Adjust timing as necessary
                    // Double-tap detected
                    handleRowClick($(this));
                }
                lastTap = currentTime;
            });
            //*************Add Button****************************
            document.getElementById('btn_Add').addEventListener('click', function() {
                try {
                    validationDetail();
                    var item_id = document.getElementById('item_name').value;
                    var item_name = $("#item_name option:selected").text();
                    var item_code = $("#item_code option:selected").text();
                    var bar_code = document.getElementById('bar_code').value;
                    var unit_id = document.getElementById('unit_name').value;
                    var unit_name = $("#unit_name option:selected").text();
                    var store_qty = document.getElementById('store_Qty').value;
                    var quantity = document.getElementById('quantity').value;
                    var unit_cost = document.getElementById('unit_cost').value;
                    var amount = document.getElementById('amount').value;
                    var expire_date = document.getElementById('expire_date').value;
                    var tableRows = document.querySelectorAll('#tableBody tr');

                    //Add data to table
                    var row_count = tableRows.length + 1;
                    var tableBody = document.getElementById('tableBody');

                    // Save data to local storage
                    var dataToStore = {
                        "item_id": item_id,
                        "item_name": item_name,
                        "item_code": item_code,
                        "bar_code": bar_code,
                        "unit_id": unit_id,
                        "unit_name": unit_name,
                        "store_qty": store_qty,
                        "quantity": quantity,
                        "unit_cost": unit_cost,
                        "amount": amount,
                        "expire_date": expire_date
                    };

                    var item_selected = receiveDetailList.filter(x => x.item_id == item_id);
                    var expireDate = new Date(expire_date);
                    if (item_selected[0] != undefined) {
                        index = receiveDetailList.findIndex(x => x == item_selected[0]);
                        receiveDetailList.splice(index, 1); //remove object with specific index
                        receiveDetailList.splice(index, 0, dataToStore); //insert object with specific index
                        tableBody.deleteRow(index);
                        var newRow = tableBody.insertRow(index);
                        newRow.innerHTML =
                            `<td>${index+1}</td><td style="display:none">${item_id}</td><td style="word-wrap:break-world; white-space:normal;">${item_name}</td><td>${item_code}</td><td>${bar_code}</td><td style="display:none">${unit_id}</td><td>${unit_name}</td>
                            <td>${quantity}</td><td>${unit_cost}</td><td>${amount}</td><td>${('0' + expireDate.getDate()).slice(-2)+ '/' + ('0' + (expireDate.getMonth() + 1)).slice(-2) + '/' + expireDate.getFullYear()}</td>`;

                    } else {
                        var newRow = tableBody.insertRow();
                        newRow.innerHTML =
                            `<td>${row_count}</td><td style="display:none">${item_id}</td><td style="word-wrap:break-world; white-space:normal;">${item_name}</td><td>${item_code}</td><td>${bar_code}</td><td style="display:none">${unit_id}</td><td>${unit_name}</td>
                            <td>${quantity}</td><td>${unit_cost}</td><td>${amount}</td><td>${('0' + expireDate.getDate()).slice(-2)+ '/' + ('0' + (expireDate.getMonth() + 1)).slice(-2) + '/' + expireDate.getFullYear()}</td>`;
                        receiveDetailList.push(dataToStore);
                    }
                    clearDetail();
                    $('#btn_Add').html('<i class="fa-solid fa-plus" style="padding-right: 5px"></i>Add');
                } catch (err) {
                    return;
                }
            });
            //*************btn_Remove****************************
            document.getElementById('btn_Remove').addEventListener('click', function() {
                var item_id = document.getElementById('item_name').value;
                var selecteddetail = receiveDetailList.filter(x => x.item_id ==
                    item_id); //filter(x=>x.itemID == $('#item_name').val());
                if (selecteddetail[0] != undefined) {
                    index = receiveDetailList.findIndex(x => x == selecteddetail[0]);
                    receiveDetailList.splice(index, 1);
                    var tbody = document.getElementById("tableBody");
                    if (tbody.rows.length) {
                        tbody.deleteRow(index);
                    }
                    clearDetail();
                    rowCount();
                }
                $('#btn_Add').html('<i class="fa-solid fa-plus" style="padding-right: 5px"></i>Add');
            });

            //*************Save Button****************************
            document.getElementById('btn_Save').addEventListener('click', function() {
                if (receiveDetailList.length == 0) {
                    $('#checkValidationModal').modal('show');
                    return;
                }
                //reduce is summation , accumulator is current summ value
                let total_amount = receiveDetailList.reduce((accumulator, x) => {
                    return accumulator + parseFloat(x.quantity * x.unit_cost);
                }, 0);

                var masterData = {
                    receiveID: $('#receiveID').val(),
                    voucherNo: $('#voucher_no').val(),
                    receiveDate: $('#receive_date').val(),
                    remark: $('#remark').val(),
                    totalAmt: total_amount,
                    detailList: receiveDetailList,
                    modifiedBy: $('#loginUserID').val()
                };
                // Send data to backend
                $.ajax({
                    url: 'createStockReceive',
                    method: 'get',
                    data: masterData,
                    contentType: 'application/json; charset=utf-8',
                    success: function(response) {
                        clearTextError();
                        if (response.success) {
                            $('#success_text').html(response.success);
                            $('#successModal').modal('show');
                        } else if (response.errors) {
                            console.log(response.errors);
                        }
                    }
                })
            });
            $('#btn_successOK').click(function() {
                location.reload();
            })

            //*************Detail Clear Button****************************
            document.getElementById('btn_New').addEventListener('click', function() {
                location.reload();
            });
        });

        $("#create_main_category").change(function() {
            var mainCategory_id = $(this).val();
            $.ajax({
                type: "GET",
                url: "item/item",
                data: {
                    mainCategoryID: mainCategory_id
                },
                success: function(data) {
                    $("#create_sub_category").empty();
                    $.each(data, function(key, value) {
                        $("#create_sub_category").append(
                            '<option value="' +
                            value.category_id +
                            '">' +
                            value.menu_category_name +
                            "</option>"
                        );
                    });
                },
            });
        });
        // $(function() {
        //     $("#itemCreateModalForm").validate({
        //         rules: {
        //             create_main_category: {
        //                 required: true,
        //                 min: 1 // assumes `0` is the "not selected" value
        //             },
        //             create_sub_category: {
        //                 required: true,
        //             },
        //             create_item_code: {
        //                 required: true,
        //                 remote: {
        //                     type: "Get",
        //                     url: "item/checkUniqueItemCode", // Backend route to check uniqueness
        //                     data: {
        //                         item_code: function() {
        //                             return $("#create_item_code").val();
        //                         }
        //                     }
        //                 }
        //             },
        //             create_bar_code: {
        //                 required: true,
        //                 remote: {
        //                     type: "Get",
        //                     url: "item/checkUniqueBarCode", // Backend route to check uniqueness
        //                     data: {
        //                         bar_code: function() {
        //                             return $("#create_bar_code").val();
        //                         }
        //                     }
        //                 }
        //             },
        //             create_item_name: {
        //                 required: true,
        //                 remote: {
        //                     type: "Get",
        //                     url: "item/checkUniqueItemName", // Backend route to check uniqueness
        //                     data: {
        //                         item_name: function() {
        //                             return $("#create_item_name").val();
        //                         }
        //                     }
        //                 }
        //             },
        //             create_item_image: {
        //                 extension: "jpg|jpeg|png"
        //             }
        //         },
        //         messages: {
        //             create_main_category: {
        //                 required: "Main Category ရွေးရန်လိုအပ်ပါသည်",
        //                 min: "Main Category ရွေးရန်လိုအပ်ပါသည်"
        //             },
        //             create_sub_category: {
        //                 required: "Sub Category ရွေးရန်လိုအပ်ပါသည်",
        //             },
        //             create_item_code: {
        //                 required: "Item Code ဖြည့်ရန်လိုအပ်ပါသည်",
        //                 remote: "Item Code တူနေပါသည်"
        //             },
        //             create_bar_code: {
        //                 required: "Bar Code ဖြည့်ရန်လိုအပ်ပါသည်",
        //                 remote: "Bar Code တူနေပါသည်"
        //             },
        //             create_item_name: {
        //                 required: "Item Name ဖြည့်ရန်လိုအပ်ပါသည်",
        //                 remote: "Item Name တူနေပါသည်"
        //             },
        //             create_item_image: {
        //                 extension: "Image သည် JPG, JPEG, PNG Format သာဖြစ်ရပါမည်"
        //             }
        //         }
        //     });
        // });

        $('#itemCreateModalForm').on('submit', function(e) {
            e.preventDefault();

            // Create FormData object (needed for file uploads)
            var formData = new FormData(this);

            // Clear previous errors
            $('#itemCreateModalForm input, #itemCreateModalForm select').removeClass('is-invalid');
            $('.invalid-feedback').text('');

            $.ajax({
                url: "{{ route('receive#itemCreate') }}",
                type: 'POST',
                data: formData,
                contentType: false, // Required for FormData
                processData: false, // Required for FormData
                success: function(response) {

                    $('#add_item_modal').modal('hide'); // Close modal
                    $('#itemCreateModalForm')[0].reset(); // Reset form   
                    
                    location.reload(); 
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        
                        $.each(errors, function(key, value) {
                            var input = $('#' + key);
                            
                            input.addClass('is-invalid');
                            
                            input.next('.invalid-feedback').text(value[0]).show();
                            
                            input.parent().find('.invalid-feedback').text(value[0]).show();
                        });
                    } else {
                        alert('Something went wrong. Please try again.');
                    }
                }
            });
        });

        $('#itemCreateModalForm input, #itemCreateModalForm select').on('input change', function() {
            $(this).removeClass('is-invalid');
            $(this).parent().find('.invalid-feedback').hide();
        });

    </script>

@endsection
