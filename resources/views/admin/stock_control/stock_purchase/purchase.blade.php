@extends('layouts.admin.master')
@section('title', 'Stock Purchase')

@section('content')
    <section class="home-section">
        <div class="home-title">
            <i class='bx bx-menu'></i>
            <span class="text">Stock Purchase</span>
        </div>
        <div class="home-content">
            <div class="row pb-3 align-items-center" style="color:#512DA8; font-weight:bold">
                <div class="col-12 col-md-7">
                    <label>Add New Stock Purchase</label>
                </div>
                <div class="col-12 col-md-5 text-md-end text-start mt-3 mt-md-0">
                    <button class="btn btn-danger customBtn-clear" id="btn_New"><i class="fa-solid fa-eraser"
                            style="padding-right: 5px"></i>Clear</button>
                    <button class="btn btn-primary customBtn-save ms-1 mt-0" id="btn_Save"><i
                            class="fa-regular fa-floppy-disk" style="padding-right: 5px"></i>Save</button>
                </div>
            </div>
            <div id="voucher_info_label" class="row align-items-center bg-white">
                <div class="col-10">
                    <label><i class="fa-regular fa-newspaper" style="padding-left:5px; padding-right: 12px"></i>Voucher
                        Info</label>
                </div>
                <div class="col-2" style="text-align: right">
                    <i class="bx bxs-chevron-down arrow"></i>
                </div>
            </div>
            <div class="voucher_info_container shadow-sm show_container">
                <div class="row">
                    <div class="col-4 voucher_info_left">
                        <div class="row">
                            <div class="col-5 mb-3">
                                <label class="form-label">Voucher No</label>
                            </div>
                            <div class="col-7">
                                <input type="text" id="loginUserID" name="loginUserID" value="{{ Auth::User()->id }}"
                                    hidden>
                                <input class="form-control" type="text" id="voucher_no" value="{{ $voucherNo }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-5">
                                <label class="form-label">Date</label>
                            </div>
                            <div class="col-7">
                                <input class="form-control" type="date" id="purchase_date" name="purchase_date">
                            </div>
                        </div>
                    </div>
                    <div class="col-5 voucher_info_mid">
                        <div class="row">
                            <div class="col-5 mb-3">
                                <label class="form-label">Supplier Name</label>
                            </div>
                            <div class="col-7">
                                <select class="form-select" id="supplier_name" name="supplier_name">
                                    <option>--Select--</option>
                                    @if ($supplierList->count() > 0)
                                        @foreach ($supplierList as $supplier)
                                            <option value="{{ $supplier->supplier_id }}">{{ $supplier->supplier_name }}
                                            </option>
                                        @endForeach
                                    @endif
                                </select>
                                <span class="text-danger">
                                    <span id="supplier_name_error"></span>
                                </span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-5 mb-3">
                                <label class="form-label">Due Date</label>
                            </div>
                            <div class="col-7">
                                <input class="form-control" type="date" id="due_date" name="due_date">
                            </div>
                        </div>
                    </div>
                    <div class="col-3 voucher_info_right">
                        <textarea class="form-control" rows="3" placeholder="Remarks" id="remark" name="remark"></textarea>
                    </div>
                </div>
            </div>
            <div id="item_details_info_label" class="row align-items-center bg-white">
                <div class="col-10">
                    <label><i class="fa-solid fa-cookie-bite" style="padding-left:5px; padding-right: 13px"></i>Item
                        Details
                        Info</label>
                </div>
                <div class="col-2" style="text-align: right">
                    <i class="bx bxs-chevron-down arrow"></i>
                </div>
            </div>
            <div class="item_details_info_container shadow-sm show_container" style="overflow-y:auto">
                <div class="row">
                    <div class="item_detail_left col-5">
                        {{-- <span class="text-danger">
                            <h5 id="purchase_detaillist_error"></h5>
                        </span> --}}
                        <div class="row mb-3">
                            <div class="col-5">
                                <label class="form-label">Item Name</label>
                            </div>
                            <div class="col-7">
                                <div class="row">
                                    <div class="col-8">
                                        <select class="form-select" id="item_name" name="item_name">
                                            <option value="0">--Select--</option>
                                            @if ($itemList->count() > 0)
                                                @foreach ($itemList as $item)
                                                    <option value="{{ $item->item_id }}">{{ $item->item_name }}</option>
                                                @endForeach
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
                                    <option value="0">--Select--</option>
                                    @if ($itemList->count() > 0)
                                        @foreach ($itemList as $item)
                                            <option value="{{ $item->item_id }}">{{ $item->item_code }}</option>
                                        @endForeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-5">
                                <label class="form-label">Bar Code</label>
                            </div>
                            <div class="col-7">
                                <input class="form-control muted" type="text" id="barcode" name="barcode" readonly>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-5">
                                <label class="form-label">Unit</label>
                            </div>
                            <div class="col-7">
                                <select class="form-select" id="unit_id" name="unit_id" readonly>
                                </select>
                                {{-- <input class="form-control" type="text" id="unit_id" readonly> --}}
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
                                <input class="form-control" type="number" id="qty" name="qty"
                                    required="required" min="1" onkeypress="return /[0-9]/i.test(event.key)"
                                    value="1" min="1">
                                <span class="text-danger">
                                    <span id="qty_error" style="font-size: 13px"></span>
                                </span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-5">
                                <label class="form-label">Unit Cost</label>
                            </div>
                            <div class="col-7">
                                <input class="form-control" type="text" id="unit_cost" name="unit_cost"
                                    required="required" onkeypress="return /[0-9]/i.test(event.key)">
                                <span class="text-danger">
                                    <span id="unit_cost_error" style="font-size: 13px"></span>
                                </span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-5">
                                <label class="form-label">Amount</label>
                            </div>
                            <div class="col-7">
                                <input class="form-control muted" type="text" id="amount" name="amount"
                                    placeholder="0" readonly>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-5">
                                <label class="form-label">Discount</label>
                            </div>
                            <div class="col-7">
                                <input class="form-control" type="text" id="discount" name="discount"
                                    value="0" placeholder="0" required="required"
                                    onkeypress="return /[0-9]/i.test(event.key)">
                                <span class="text-danger">
                                    <span id="discount_error"></span>
                                </span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-5">
                                <label class="form-label">Net Amount</label>
                            </div>
                            <div class="col-7">
                                <input class="form-control muted" type="text" id="net_amount" name="net_amount"
                                    placeholder="0" readonly>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-5">
                                <label class="form-label">Expire Date</label>
                            </div>
                            <div class="col-7">
                                <input class="form-control" type="date" id="expire_date" name="expire_date">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-5">
                                <label class="form-label" for="is_foc">FOC</label>
                            </div>
                            <div class="col-7">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_foc">
                                </div>
                            </div>
                        </div>
                        <div>
                            <button class="btn btn-primary mt-2" id="btn_Add"><i class="fa-solid fa-plus"
                                    style="padding-right: 5px"></i>Add</button>
                            <button class="btn btn-warning text-white mt-2" id="btn_Remove"><i class="fa-solid fa-minus"
                                    style="padding-right: 5px"></i>Remove</button>
                            <button class="btn btn-danger mt-2" id="btn_Clear"><i class="fa-solid fa-eraser"
                                    style="padding-right: 5px"></i>Clear</button>
                        </div>
                    </div>
                    <div class="item_detail_right col-7">
                        <div class=".table-responsive border border-2"
                            style="height:580px; overflow-x:auto; white-space:nowrap; padding-left:10px; padding-right:10px; border-radius:10px;">
                            <table class="table table-hover">
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
                                        <th scope="col">Discount</th>
                                        <th scope="col">Net Amount</th>
                                        <th scope="col">FOC</th>
                                        <th scope="col">Expire Date</th>
                                    </tr>
                                </thead>
                                <tbody id="purchase_detail">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div id="voucher_details_info_label" class="row align-items-center bg-white">
                <div class="col-10">
                    <label><i class="fa-brands fa-stack-overflow"
                            style="padding-left:5px; padding-right: 12px"></i>Voucher Details
                        Info</label>
                </div>
                <div class="col-2" style="text-align: right">
                    <i class="bx bxs-chevron-down arrow"></i>
                </div>
            </div>
            <div class="voucher_details_info_container shadow-sm show_container">
                <div class="row">
                    <div class="voucher_detail_info_left col-6">
                        <div class="row">
                            <div class="col-5 mb-3">
                                <label class="form-label">Total Amount</label>
                            </div>
                            <div class="col-7">
                                <input class="form-control muted" type="text" id="totalAmount" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="voucher_detail_info_right col-6">
                        <div class="row">
                            <div class="col-5 mb-3">
                                <label class="form-label">Total Item Discount</label>
                            </div>
                            <div class="col-7">
                                <input class="form-control muted" type="text" id="totalItemDiscount" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Item Deletail Validation Modal --}}
        <div class="modal fade" id="checkErrorModal" tabindex="-1" role="dialog" aria-hidden="true"
            data-bs-keyboard="false" data-bs-backdrop="static">
            <div class="modal-dialog modal-dialog-centered" role="document">
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
        {{-- <div class="modal fade modal-sm" id="successModal" tabindex="-1" aria-labelledby="successModalLabel"
            data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #512DA8">
                        <h1 class="modal-title fs-5 w-100" id="staticBackdropLabel" style="color: white">Success
                        </h1>
                    </div>
                    <div class="modal-body">
                        <h6 id="success_text"><i class="fa-solid fa-circle-exclamation text-primary"></i></h6>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn custom_btn" data-bs-dismiss="modal"
                            id="btn_successOK">OK</button>
                    </div>
                </div>
            </div>
        </div> --}}
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
                            Purchase record was saved successfully.
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
                        <form action="{{ route('purchase#itemCreate') }}" method="POST" id="itemCreateModalForm"
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
                                    <input class="form-control" type="text" id="create_item_code"
                                        name="create_item_code">
                                    <div class="invalid-feedback" style="font-size: 0.82rem;"></div>
                                </div>
                            </div>
                            {{-- <div class="row mb-3 justify-content-center">
                                <div class="col-5">
                                    <label class="col-form-label">Bar Code <span style="color: red">*</span></label>
                                </div>
                                <div class="col-6">
                                    <input class="form-control" type="text" id="create_bar_code"
                                        name="create_bar_code">
                                </div>
                            </div> --}}
                            <div class="row mb-3 justify-content-center">
                                <div class="col-5">
                                    <label class="col-form-label">Item Name <span style="color: red">*</span></label>
                                </div>
                                <div class="col-6">
                                    <input class="form-control" type="text" id="create_item_name"
                                        name="create_item_name">
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
                                    <input class="form-control" value="0" type="text" id="create_unit_cost"
                                        name="create_unit_cost">
                                    <div class="invalid-feedback" style="font-size: 0.82rem;"></div>
                                </div>
                            </div>
                            <div class="row mb-3 justify-content-center">
                                <div class="col-5">
                                    <label class="col-form-label">Selling Price <span style="color: red">*</span></label>
                                </div>
                                <div class="col-6">
                                    <input class="form-control" type="text" id="create_item_selling_price"
                                        name="create_item_selling_price">
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
    <script src="{{ asset('script/purchase_script.js') }}"></script>
    <script>
        $(document).ready(function() {
            var purchaseDetailList = [];
            var tableDoubleClick = false;
            var foc_status;
            var today = new Date();
            var now = today.getFullYear() + '-' + ('0' + (today.getMonth() + 1)).slice(-2) + '-' + ('0' + today
                    .getDate())
                .slice(-2);

            $('#purchase_date').val(now);
            $('#due_date').val(now);
            $('#expire_date').val(now);

            $(document).on("change", "#item_name, #item_code", ItemSelectedChange);

            //**************item selected change****************
            function ItemSelectedChange() {
                let itemID = $(this).val();
                let items = {!! json_encode($itemList->toArray()) !!};
                const selected_item = items.filter(x => x.item_id == itemID);
                if (itemID > 0) {
                    $('#item_name').val(selected_item[0].item_id);
                    $('#item_code').val(selected_item[0].item_id);
                    $('#barcode').val(selected_item[0].bar_code);
                    $('#unit_id').empty();
                    $('#unit_id').append('<option value="' + selected_item[0].unit_id + '">' + selected_item[0]
                        .unit_name +
                        '</option>');
                    $('#unit_id').val(selected_item[0].unit_id);
                    $('#qty').val(1);
                    $('#is_foc').prop('checked', false);

                    $.ajax({
                        url: "checkStoreQty",
                        type: "get",
                        data: {
                            itemID: itemID,
                            unitID: $('#unit_id').val()
                        },
                        contentType: 'application/json; charset=utf-8',
                        success: function(response) {
                            console.log(response);
                            let Store_Qty = response.success;
                            let Unit_Cost = response.unitCost;
                            $('#store_Qty').val(Store_Qty);
                            $('#unit_cost').val(Unit_Cost);
                        }
                    });
                } else {
                    $('#item_name').val(0);
                    $('#item_code').val(0);
                    $('#barcode').val(null);
                    $('#unit_id').empty();
                    $('#qty').val(null);
                    $('#is_foc').prop('checked', false);
                }

                CalculateItemDetail();
            }
            $(document).on("input", "#qty, #unit_cost, #tax, #discount", CalculateItemDetail);

            function CalculateItemDetail() {
                var _qty = $('#qty').val();
                var _unitCost = $('#unit_cost').val();
                var _discount = $('#discount').val();
                var _amount = (_qty * _unitCost);
                $('#amount').val(_amount.toLocaleString());
                $('#net_amount').val((_amount - _discount).toLocaleString());
            };

            var itemDetailList = {
                set: function(purchase_detail_entry) {
                    purchaseDetailList.push(purchase_detail_entry);
                    var expire_date = new Date(purchase_detail_entry.expire_date);
                    $('#purchase_detail').append(
                        `<tr><td>${purchase_detail_entry.no}</td>
                                <td style="word-wrap:break-world; white-space:normal;">${purchase_detail_entry.item_name}</td><td>${purchase_detail_entry.item_code}</td><td>${purchase_detail_entry.barcode}</td><td>${purchase_detail_entry.unit_name}</td>
                                <td>${purchase_detail_entry.qty}</td><td>${purchase_detail_entry.unit_cost}</td><td>${purchase_detail_entry.amount}</td>
                                <td>${purchase_detail_entry.discount}</td><td>${purchase_detail_entry.net_amount}</td><td><input type="checkbox" ${purchase_detail_entry.foc?'checked':'unchecked'} disabled></td><td>${('0' + expire_date.getDate()).slice(-2)+ '/' + ('0' + (expire_date.getMonth() + 1)).slice(-2) + '/' + expire_date.getFullYear()}</td></tr>`
                    );
                    setRowNumber_changed();
                    return purchase_detail_entry;
                },
                update: function(purchase_detail_entry) {
                    var isFOC = $('#is_foc').is(":checked");
                    var qty = 0;

                    // if (!tableDoubleClick) {
                    //     if (isFOC != foc_status) {
                    //         var selecteddetail = purchaseDetailList.filter(x => x
                    //             .itemID == $(
                    //                 '#item_name').val() &&
                    //             x.unit_id == purchase_detail_entry.unit_id && x
                    //             .foc == isFOC);
                    //         if (selecteddetail != undefined) {
                    //             $('#error_text').empty();
                    //             $('#error_text').text('Item သည် တူနေပါသည်!');
                    //             $('#checkErrorModal').modal('show');
                    //             // return;
                    //         }
                    //     }
                    // }
                    selecteddetail = purchaseDetailList.filter(x => x.itemID == $('#item_name')
                        .val() && x
                        .unit_id == purchase_detail_entry.unit_id && x.foc == isFOC);

                    if (selecteddetail.length != 0) {
                        if (!tableDoubleClick) {
                            qty = selecteddetail[0].qty;
                            purchase_detail_entry.qty = parseInt(qty) + parseInt(purchase_detail_entry.qty);
                            purchase_detail_entry.amount = parseInt(purchase_detail_entry.unit_cost) *
                                parseInt(purchase_detail_entry.qty);
                            purchase_detail_entry.net_amount = parseInt(purchase_detail_entry.amount) -
                                parseInt(purchase_detail_entry.discount);
                        }
                    }
                    index = purchaseDetailList.findIndex(x => x == selecteddetail[0]);
                    purchaseDetailList.splice(index, 1); //remove object with specific index
                    purchaseDetailList.splice(index, 0,
                        purchase_detail_entry); //insert object with specific index

                    var tbody = document.getElementById("purchase_detail");
                    if (tbody.rows.length) {
                        tbody.deleteRow(index);
                    }
                    var table = document.getElementById("purchase_detail");
                    var row = table.insertRow(index);
                    var j = 0;
                    $.each(purchase_detail_entry, function(key, value) {
                        if (key != "itemID" && key != "unit_id") {
                            var cell = row.insertCell(j);
                            if (key == "foc") {
                                var chkbox = document.createElement('input');
                                chkbox.type = "checkbox";
                                chkbox.checked = value;
                                chkbox.disabled = true;
                                cell.appendChild(chkbox);
                            } else if (key == "expire_date") {
                                let expire_date = new Date(value);
                                cell.innerHTML = ('0' + expire_date.getDate()).slice(-2) + '/' + (
                                        '0' + (
                                            expire_date.getMonth() + 1)).slice(-2) + '/' +
                                    expire_date.getFullYear();
                            } else {
                                cell.innerHTML = value;
                            }
                            j++;
                        }
                    });
                    setRowNumber_changed();
                    return purchase_detail_entry;
                },
            };
            // -----------------------Double Click----------------------------
            // $('#purchase_detail').on('dblclick', 'tr', function() {
            //     tableDoubleClick = true;
            //     var $rowClicked = $(this);
            //     var itemcode = $rowClicked.find('td:eq(2)').text();
            //     var foc = $rowClicked.find("input:checkbox").is(':checked');
            //     if (foc) {
            //         $('#btn_Add').html('<i class="fa-solid fa-plus" style="padding-right: 5px"></i>Update');
            //         $('#btn_Add').prop('disabled', true);
            //     } else {
            //         $('#btn_Add').prop('disabled', false);
            //         $('#btn_Add').html('<i class="fa-solid fa-plus" style="padding-right: 5px"></i>Update');
            //     }

            //     var selecteddetail = purchaseDetailList.filter(x => x.item_code == itemcode && x.foc ==
            //         foc);
            //     if (selecteddetail[0] != undefined) {
            //         selecteddetail = selecteddetail[0];
            //         $('#item_name').val(selecteddetail.itemID);
            //         $('#item_code').val(selecteddetail.itemID);
            //         $('#barcode').val(selecteddetail.barcode);
            //         $('#unit_id').append('<option value="' + selecteddetail.unit_id + '">' + selecteddetail
            //             .unit_name +
            //             '</option>');
            //         $('#unit_id').val(selecteddetail.unit_id);
            //         $('#qty').val(selecteddetail.qty);
            //         $('#unit_cost').val(selecteddetail.unit_cost);
            //         $('#discount').val(selecteddetail.discount);
            //         CalculateItemDetail();
            //         $('#expire_date').val(selecteddetail.expire_date);
            //         foc_status = selecteddetail.foc;
            //         $("#is_foc").prop("checked", foc_status);
            //         $('#item_name').prop('disabled', true);
            //         $('#item_code').prop('disabled', true);
            //         $('#unit_id').prop('disabled', true);
            //     }
            // });

            //*************Double Click**************************
            // Add event listener to tbody for double-click
            let lastTap = 0;

            function handleRowClick($rowClicked) {
                tableDoubleClick = true;
                var itemcode = $rowClicked.find('td:eq(2)').text();
                var foc = $rowClicked.find("input:checkbox").is(':checked');
                if (foc) {
                    $('#btn_Add').html('<i class="fa-solid fa-plus" style="padding-right: 5px"></i>Update');
                    $('#btn_Add').prop('disabled', true);
                } else {
                    $('#btn_Add').prop('disabled', false);
                    $('#btn_Add').html('<i class="fa-solid fa-plus" style="padding-right: 5px"></i>Update');
                }

                var selecteddetail = purchaseDetailList.filter(x => x.item_code == itemcode && x.foc ==
                    foc);
                if (selecteddetail[0] != undefined) {
                    selecteddetail = selecteddetail[0];
                    $('#item_name').val(selecteddetail.itemID);
                    $('#item_code').val(selecteddetail.itemID);
                    $('#barcode').val(selecteddetail.barcode);
                    $('#unit_id').append('<option value="' + selecteddetail.unit_id + '">' + selecteddetail
                        .unit_name +
                        '</option>');
                    $('#unit_id').val(selecteddetail.unit_id);
                    $('#qty').val(selecteddetail.qty);
                    $('#unit_cost').val(selecteddetail.unit_cost);
                    $('#discount').val(selecteddetail.discount);
                    CalculateItemDetail();
                    $('#expire_date').val(selecteddetail.expire_date);
                    foc_status = selecteddetail.foc;
                    $("#is_foc").prop("checked", foc_status);
                    $('#item_name').prop('disabled', true);
                    $('#item_code').prop('disabled', true);
                    $('#unit_id').prop('disabled', true);
                }
            }
            // Handle double-click on desktop
            $('#purchase_detail').on('dblclick', 'tr', function(event) {
                handleRowClick($(this));
            });

            // Handle double-tap on touch devices
            $('#purchase_detail').on('touchstart', 'tr', function(event) {
                let currentTime = new Date().getTime();
                let tapLength = currentTime - lastTap;
                if (tapLength < 500 && tapLength > 0) { // Adjust timing as necessary
                    // Double-tap detected
                    handleRowClick($(this));
                }
                lastTap = currentTime;
            });
            // -----------------------Click Add Button----------------------------
            $('#btn_Add').click(function(e) {
                var purchase_detail_entry = {
                    no: '',
                    itemID: $('#item_name :selected').val(),
                    item_name: $('#item_name :selected').text(),
                    item_code: $('#item_code :selected').text(),
                    barcode: $('#barcode').val(),
                    unit_id: $('#unit_id :selected').val(),
                    unit_name: $('#unit_id :selected').text(),
                    store_qty: $('#store_Qty').val(),
                    qty: $('#qty').val(),
                    unit_cost: $('#unit_cost').val(),
                    amount: $('#amount').val(),
                    discount: $('#discount').val(),
                    net_amount: $('#net_amount').val(),
                    foc: $('#is_foc').is(":checked"),
                    expire_date: $('#expire_date').val()
                };
                $.ajax({
                    url: "checkPurchaseDetailValidation",
                    type: "get",
                    data: purchase_detail_entry,
                    contentType: 'application/json; charset=utf-8',
                    success: function(response) {
                        clearError();
                        if (response.errors) {
                            $.each(response.errors, function(key, value) {
                                if (key == "itemID") {
                                    $('#item_name_error').html(value);
                                    $('#item_name').addClass('is-invalid');
                                }
                                if (key == "calculated_qty") {
                                    $('#qty_error').html(value);
                                    $('#qty').addClass('is-invalid');
                                }
                                if (key == "unit_cost") {
                                    $('#unit_cost_error').html(value);
                                    $('#unit_cost').addClass('is-invalid');
                                }
                                if (key == "discount") {
                                    $('#discount_error').html(value);
                                    $('#discount').addClass('is-invalid');
                                }
                            });
                        } else if (response.success) {
                            if (tableDoubleClick) {
                                var selecteddetail = purchaseDetailList.filter(x => x
                                    .itemID ==
                                    purchase_detail_entry.itemID && x.unit_id ==
                                    purchase_detail_entry
                                    .unit_id);
                            } else {
                                var selecteddetail = purchaseDetailList.filter(x => x
                                    .itemID ==
                                    purchase_detail_entry.itemID && x.unit_id ==
                                    purchase_detail_entry
                                    .unit_id && x.foc == purchase_detail_entry.foc);
                            }
                            if (selecteddetail[0] != undefined) {
                                itemDetailList.update(purchase_detail_entry);
                            } else if (selecteddetail[0] == undefined) {
                                itemDetailList.set(purchase_detail_entry);
                            }
                            $('#btn_Add').html(
                                '<i class="fa-solid fa-plus" style="padding-right: 5px"></i>Add'
                            );
                            $('#btn_Add').prop('disabled', false);
                            clearDetail();
                            calculateTotal();
                            tableDoubleClick = false;
                        }
                    },
                });
            });
            // -----------------------Click Remove Button----------------------------
            $('#btn_Remove').click(function() {
                tableDoubleClick = false;
                $('#btn_Add').prop('disabled', false);
                $('#btn_Add').html('<i class="fa-solid fa-plus" style="padding-right: 5px"></i>Add');
                var selecteddetail = purchaseDetailList.filter(x => x.itemID == $('#item_name').val() && x
                    .unit_id == $('#unit_id :selected').val() && x.foc == $('#is_foc').is(":checked"));
                if (selecteddetail[0] != undefined) {
                    index = purchaseDetailList.findIndex(x => x == selecteddetail[0]);
                    purchaseDetailList.splice(index, 1);

                    // console.log(index);
                    var tbody = document.getElementById("purchase_detail");
                    if (tbody.rows.length) {
                        tbody.deleteRow(index);
                    }
                    clearDetail();
                    setRowNumber_changed();
                }
            });

            // -----------------------Click Save Button----------------------------
            $('#btn_Save').click(function() {
                console.log("In Btn Save");

                tableDoubleClick = false;
                if (purchaseDetailList.length == 0) {
                    $('#error_text').empty();
                    $('#error_text').text('You need to set item detail !');
                    $('#checkErrorModal').modal('show');
                    return;
                }
                let totalAmt = $('#totalAmount').val().replace(/\D/g, '');
                let total_discount = $('#totalItemDiscount').val().replace(/\D/g, '');
                var purchase_master_entry = {
                    loginUserID: $('#loginUserID').val(),
                    voucher_no: $('#voucher_no').val(),
                    purchase_date: $('#purchase_date').val(),
                    due_date: $('#due_date').val(),
                    supplier_name: $('#supplier_name :selected').val(),
                    due_date: $('#due_date').val(),
                    remark: $('#remark').val(),
                    totalAmount: totalAmt,
                    tax: 0,
                    transportCharges: 0,
                    otherCharges: 0,
                    totalDiscount: total_discount,
                    payment: 0,
                    purchase_detailList: purchaseDetailList
                };
                $.ajax({
                    url: "savePurchase",
                    type: "get",
                    data: purchase_master_entry,
                    contentType: 'application/json; charset=utf-8',
                    success: function(response) {
                        clearError();
                        if (response.errors) {
                            // console.log(response.errors);
                            if (typeof(response.errors) === 'object') {
                                $.each(response.errors, function(key, value) {
                                    if (key == "supplier_name") {
                                        $('#supplier_name_error').html(value);
                                        $('#supplier_name').addClass('is-invalid');
                                    }
                                });
                            }
                        } else if (response.success) {
                            $('#success_text').html(response.success);
                            $('#successModal').modal('show');
                        }
                    }
                });
            });

            function setRowNumber_changed() {
                var j = 0;
                $('#purchase_detail tr').each(function(key, value) {
                    ++j;
                    $('td:first-child', this).text(j);
                });
            }

            $('#btn_Clear').click(function() {
                $('#btn_Add').prop('disabled', false);
                $('#btn_Add').html('<i class="fa-solid fa-plus" style="padding-right: 5px"></i>Add');
                clearDetail();
                clearError();
                tableDoubleClick = false;
            });

            $('#btn_New').click(function() {
                location.reload();
                tableDoubleClick = false;
            });

            // $(document).on("click", "#btn_CheckOk", function() {
            //     location.reload();
            // });

            $('#btn_successOK').click(function() {
                location.reload();
            })

            function clearDetail() {
                $('#item_name').val(0);
                $('#item_code').val(0);
                $('#barcode').val(null);
                $('#unit_id').empty();
                $('#qty').val(null);
                $('#unit_cost').val('');
                $('#amount').val(0);
                $('#discount').val(0);
                $('#net_amount').val(0);
                $('#expire_date').val(now);
                $("#is_foc").prop("checked", false);
                foc_status = false;
                $('#item_name').prop('disabled', false);
                $('#item_code').prop('disabled', false);
                $('#unit_id').prop('disabled', false);

                clearError();
            }

            function clearError() {
                $('#item_name').removeClass('is-invalid');
                $('#item_name_error').html("");
                $('#qty').removeClass('is-invalid');
                $('#qty_error').html("");
                $('#unit_cost').removeClass('is-invalid');
                $('#unit_cost_error').html("");
                $('#discount').removeClass('is-invalid');
                $('#discount_error').html("");
                $('#supplier_name').removeClass('is-invalid');
                $('#supplier_name_error').html("");
                $('#purchase_detaillist_error').html("");
            }

            $(document).on("keyup", "#transportCharges, #otherCharges, #tax, #voucherDiscount, #payment",
                calculateTotal);

            $(document).on("input",
                "#unit_cost, #discount, #transportCharges, #otherCharges, #tax, #voucherDiscount, #payment",
                function() {
                    if (this.value == "" || this.value == null) {
                        this.value = 0;
                    }
                });

            function calculateTotal() {
                let total_amount = purchaseDetailList.reduce((accumulator, x) => {
                    return accumulator + parseFloat(x.qty * x.unit_cost);
                }, 0);
                let total_itemDiscount = purchaseDetailList.reduce((accumulator, x) => {
                    return accumulator + parseFloat(x.discount);
                }, 0);
                total_itemDiscount = isNaN(total_itemDiscount) ? 0 : total_itemDiscount;
                $('#totalAmount').val(total_amount.toLocaleString());
                $('#totalItemDiscount').val(total_itemDiscount.toLocaleString());
            }

            $('#is_foc').click(function(e) {
                if (tableDoubleClick) {
                    var itemcode = $('#item_code :selected').text();
                    var selecteddetail = purchaseDetailList.filter(x => x.item_code == itemcode);
                    if (selecteddetail[0] != undefined) {
                        selecteddetail = selecteddetail[0];
                        if (this.checked == true) {
                            $('#unit_cost').val(0);
                            $('#discount').val(0);
                        } else {
                            $('#unit_cost').val(selecteddetail.unit_cost);
                            $('#discount').val(selecteddetail.discount);
                        }
                    }
                } else {
                    if (this.checked == true) {
                        $('#unit_cost').val(0);
                        $('#discount').val(0);
                    } else {
                        $('#unit_cost').val('');
                        $('#discount').val('');
                    }
                }

                CalculateItemDetail();

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
