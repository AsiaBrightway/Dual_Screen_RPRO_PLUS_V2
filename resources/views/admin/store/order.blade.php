@extends('layouts.admin.master')
@section('title', 'Dine-In')

@section('content')
    <style>
        .item_div::-webkit-scrollbar,
        .order_div::-webkit-scrollbar {
            display: none;
        }

        .category_div,
        .item_div,
        .order_div {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .category_div-container {
            position: relative;
            width: 95%;
            margin-left: 15px
        }

        .scroll-arrow {
            position: absolute;
            width: 20px;
            height: 20px;
            z-index: 10;
            cursor: pointer;
            top: 50%;
            transform: translateY(-50%);
            background-size: contain;
            background-repeat: no-repeat;
        }

        .left-arrow {
            left: -10px;
            background-image: url('/img/left-arrow.png');
        }

        .right-arrow {
            right: -10px;
            background-image: url('/img/right-arrow.png');
        }

        .btn-group {
            white-space: nowrap;
        }

        .btn-custom {
            --bs-btn-color: #fff;
            --bs-btn-bg: #6f44d1;
            --bs-btn-border-color: #6f44d1;
            --bs-btn-hover-color: #fff;
            --bs-btn-hover-bg: #6f44d1;
            --bs-btn-hover-border-color: #512DA8;
            --bs-btn-focus-shadow-rgb: 49, 132, 253;
            --bs-btn-active-color: #fff;
            --bs-btn-active-bg: #512DA8;
            --bs-btn-active-border-color: #512DA8;
            --bs-btn-active-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.125);
            --bs-btn-disabled-color: #fff;
            --bs-btn-disabled-bg: #6f44d1;
            --bs-btn-disabled-border-color: #6f44d1;
        }

        .custom-title {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .custom-text {
            margin-left: 0px;
            /* Adjust margin as needed */
        }

        .custom-label {
            margin-left: auto;
            margin-right: 25px;
        }

        /* Existing styles */
        .visually-hidden {
            position: absolute;
            overflow: hidden;
            clip: rect(0 0 0 0);
            height: 1px;
            width: 1px;
            margin: -1px;
            padding: 0;
            border: 0;
        }

        /* Additional styles for gift icon */
        .gift-icon {
            color: rgb(181, 153, 153);
            /* Default color */
        }

        .gift-checkbox:checked+label .gift-icon {
            color: #512DA8;
            /* Change color when checkbox is checked */
        }

        .active-card {
            border: 1px solid #512DA8 !important;
            /* box-shadow: 0 0 15px rgba(81, 45, 168, 0.5); */
            transition: all 0.1s ease-in-out; */
        }

        .category_div {
            overflow-x: hidden;
        }
        .category_content {
            display: flex;
            white-space: nowrap;
            will-change: transform;
        }

    </style>
    <section class="home-section">
        <div class="home-title custom-title">
            <i class='bx bx-menu'></i>
            <span class="text custom-text">Food Items</span>
            <label class="custom-label" style="color:#512DA8; font-weight:bold"><i class="fa-solid fa-calendar-days"
                    style="padding-right: 5px"></i>
                {{ now()->format('l, F j, Y') }}</label>

        </div>
        <div class="home-content">
            <div class="row justify-content-between mt-1">
                <div class="col-6 left_div d-flex flex-column">
                    <div class="row" style="width:100%">
                        <div class="col-7">
                        </div>
                        <div class="col-5 item_search_div">
                            <input type="text" class="form-control item_search_input" name="itemSearch" id="itemSearch"
                                placeholder="&#128269;  Search item here..." style="margin-left: 20px; border-radius:10px">
                        </div>
                    </div>
                    <div class="row mt-2"
                        style="background: white; width:99%; border-radius:10px; margin-right:0px; margin-left:0px">
                        <div class="mt-1">
                            <label style="color:#6f44d1; font-weight:600">+ Choose Category</label>
                        </div>
                        <div class="category_div-container">
                            <div class="scroll-arrow left-arrow" ></div>
                            <div class="category_div d-flex flex-nowrap justify-content-start"
                                style="width:100%; overflow-x: auto; overflow-y: hidden; white-space: nowrap;">
                                <div class="category_content" style="margin-bottom: 7px; min-height: 124px">
                                    @if (count($menuCategories) != 0)
                                        @foreach ($menuCategories as $menuCategory)
                                            <button class="btn m-2 subCategory-button p-0 mb-3 shadow-sm"
                                                style="width: 100px; height: 100px; border-radius: 20px; border: none;"
                                                data-table-value="{{ $menuCategory['category_id'] }}">
                                                <div class="card"
                                                    style="background: white; width:100px; border-radius:20px">
                                                    @if ($menuCategory['menu_category_image'] == null)
                                                        <img src="{{ asset('img/category.png') }}"
                                                            loading="lazy"
                                                            class="card-img-top w-100 " alt="..."
                                                            style="height: 70px; border-top-left-radius: 20px; border-top-right-radius: 20px; object-fit:cover;">
                                                    @else
                                                        <img src="{{ asset('storage/Images/' . $menuCategory['menu_category_image']) }}"
                                                            loading="lazy"
                                                            class="card-img-top w-100 " alt="..."
                                                            style="height: 70px; border-top-left-radius: 20px; border-top-right-radius: 20px; object-fit: cover;">
                                                    @endif

                                                    <div class="card-body d-flex align-items-center justify-content-center" style="height: 40px;">
                                                        <p class="card-text text-break"
                                                            style="font-size:12px; white-space: normal; line-height: 1;">
                                                            {{ $menuCategory['menu_category_name'] }}</p>
                                                    </div>
                                                </div>
                                            </button>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div class="scroll-arrow right-arrow" ></div>
                        </div>

                    </div>
                    <div class="row mt-2"
                        style="background: white; width:99%; border-radius:10px; margin-right:0px; margin-left:0px">
                        <div class="mt-1">
                            <label style="color:#6f44d1; font-weight:600">+ Choose Items</label>
                        </div>
                        <div class="item_div justify-content-around mt-2"
                            style="height:58vh;overflow-y: auto; padding-left:10px;">
                            @if (count($items) != 0)
                                @foreach ($items as $item)
                                    @if ($item['store_qty'] <= 0 && $item['item_type_id'] == 1)
                                        @php
                                            $disabled = '';
                                            $textColor = 'orange';
                                            $item['store_qty'] = 0;
                                        @endphp
                                    @elseif ($item['store_qty'] <= 0)
                                        @php
                                            $disabled = '';
                                            $textColor = 'red';
                                        @endphp
                                    @else
                                        @php
                                            $disabled = '';
                                            $textColor = 'green';
                                        @endphp
                                    @endif
                                    <button class="btn m-2 item-button p-0 shadow-sm"
                                        style="width: 178px; height: 200px; border-radius: 20px; border: none;"
                                        data-item_id="{{ $item['item_id'] }}" data-item_image="{{ $item['item_image'] }}"
                                        data-item_name="{{ $item['item_name'] }}" data-item_price={{ $item['item_price'] }}
                                        {{ $disabled }}>
                                        <div class="card h-100 w-100" style="background: white">
                                            @if ($item['item_image'] == null)
                                                <img src="{{ asset('404_image.png') }}" class="card-img-top w-100" loading="lazy"
                                                    alt="..." style="height: 110px; object-fit: cover;">
                                            @else
                                                <img src="{{ asset('storage/Images/' . $item['item_image']) }}"
                                                    class="card-img-top w-100" loading="lazy" alt="..." style="height: 110px; object-fit: cover;">
                                            @endif
                                            <div class="card-body" style="height: 0px">
                                                <p class="card-title text-muted"
                                                    style="text-align: start; margin-top:-10px;font-size:12px">
                                                    {{ Str::words($item['item_name'], 3, '...') }}</p>
                                                <p class="card-text "
                                                    style="text-align: start; margin-top:-5px; font-size:15px; font-weight:600">
                                                    {{ number_format($item['item_price']) }} MMK
                                                    <br>
                                                    <span style="color: {{ $textColor }}; font-size:12px">
                                                        Store Qty: {{ $item['store_qty'] }}
                                                    </span>
                                                </p>
                                            </div>
                                        </div>
                                    </button>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="row mt-2 flex-grow-1 d-flex align-items-center justify-content-start">
                        <div class="btn-group pb-1" role="group" style="width: 98%; overflow-x:auto; scrollbar-color: #512DA8 white; scrollbar-width: thin;">
                            @if (count($mainCategories) != 0)
                                <input type="radio" class="btn-check" name="btnradio" id="0" autocomplete="off"
                                    value="0" checked>
                                <label class="btn btn-custom" for="0">All</label>
                                @foreach ($mainCategories as $mainCategory)
                                    @if ($mainCategory['main_category_id'] == 1)
                                        <input type="radio" class="btn-check" name="btnradio"
                                            id="{{ $mainCategory['main_category_id'] }}" autocomplete="off"
                                            value="{{ $mainCategory['main_category_id'] }}">
                                        <label class="btn btn-custom "
                                            for="{{ $mainCategory['main_category_id'] }}">{{ $mainCategory['main_category_name'] }}</label>
                                    @else
                                        <input type="radio" class="btn-check" name="btnradio"
                                            id="{{ $mainCategory['main_category_id'] }}" autocomplete="off"
                                            value="{{ $mainCategory['main_category_id'] }}">
                                        <label class="btn btn-custom"
                                            for="{{ $mainCategory['main_category_id'] }}">{{ $mainCategory['main_category_name'] }}</label>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col right_div" style="margin-left:50px;margin-right: 15px">
                    <div class="row justify-content-between pt-2 pb-2" style="background: white; border-radius:10px;">
                        <div class="col" style="text-align:center;">
                            <input type="text" name="user_id" id="user_id" value={{ Auth::user()->id }} hidden>
                            <input type="text" name="table_id" id="table_id" value={{ $table[0]['table_id'] }}
                                hidden>
                            <label id="orderTable" style="font-weight: bold">Table - ({{ $table[0]['table_name'] }}),
                            </label>
                            <label style="font-weight: bold">Order - ({{ $tableOrderValue }})</label>
                            {{-- <select name="table_order_number" id="table_order_number"
                                style="border-radius: 5px; width:40px; text-align:center;">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select> --}}
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="container" style="height: 100%; background:white; border-radius:20px; padding:30px">
                            {{-- <a href="" style="text-decoration: none; color:#512DA8">+ Add
                                Order</a>
                            <hr style="margin:10px 0 10px 0"> --}}
                            <input type="text" id="user_role_id" name="user_role_id"
                                value="{{ Auth::user()->user_role_id }}" hidden>
                            <div class="order_div container" style="height: 68vh; border-radius:20px; overflow-y:auto">
                                @php
                                    $orderTotalAmount = 0;
                                    $userRoleId = Auth::user()->user_role_id;
                                @endphp
                                @if (count($orderDetails) != 0)
                                    @for ($i = 0; $i < count($orderDetails); $i++)
                                        <div class="row" style="align-items: center;">
                                            <div class="col-2 item_qty_input" style="align-self: stretch; display: flex; flex-direction: column; justify-content: space-around;">
                                                <input class="form-control quantity-input muted" type="text"
                                                    value={{ $orderDetails[$i]['quantity'] }} min="1"
                                                    data-order_item_index={{ $i }}
                                                    data-item-id={{ $orderDetails[$i]['item_id'] }} readonly>
                                                <div>
                                                    <a
                                                        class="remark_lbl add_order_item_remark_preview_modal_dialog"
                                                        style="text-decoration: none; cursor: pointer;"
                                                        data-order_item_id={{ $orderDetails[$i]['item_id'] }}
                                                        data-order_item_name={{ $orderDetails[$i]['item_name'] }}
                                                        data-order_item_remark="{{ $orderDetails[$i]['remark'] }}"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#add_order_item_preview_remark_modal"><span
                                                            class="remark_lbl"
                                                            style="font-size: 13px; color:red">+remark</span></a>
                                                </div>
                                            </div>
                                            <div class="col-2">
                                                @if ($orderDetails[$i]['item_image'] == null)
                                                    <img class="item_img" src="{{ asset('404_image.png') }}"
                                                        alt="" loading="lazy"
                                                        style="width: 65px; height: 65px; border-radius: 10px">
                                                @else
                                                    <img class="item_img"
                                                        src=" {{ asset('storage/Images/' . $orderDetails[$i]['item_image']) }}"
                                                        alt="" loading="lazy"
                                                        style="width: 65px; height: 65px; border-radius: 10px">
                                                @endif
                                            </div>
                                            <div class="col-3 dine_in_item_description_div" style="color:#512DA8">
                                                <div class="row"><label class="item_name_lbl"
                                                        style="font-weight:500;">{{ $orderDetails[$i]['item_name'] }}
                                                    </label></div>
                                                <div class="row">
                                                    <label class="original-price text-muted small">{{ number_format($orderDetails[$i]['item_price']) }}</label>
                                                </div>
                                            </div>
                                            <div class="col-1 gift_check_div">
                                                @if ($orderDetails[$i]['is_foc'] == 1 || $orderDetails[$i]['is_foc'] == '1')
                                                    <input id="giftCheckbox{{ $i }}"
                                                        class="form-check-input visually-hidden gift-checkbox"
                                                        type="checkbox"
                                                        value="{{ $orderDetails[$i]['order_detail_id'] }}|{{ $i }}"
                                                        checked>
                                                    <label for="giftCheckbox{{ $i }}"><i
                                                            class="fas fa-gift gift-icon"
                                                            style="cursor: pointer"></i></label>
                                                @else
                                                    <input id="giftCheckbox{{ $i }}"
                                                        class="form-check-input visually-hidden gift-checkbox"
                                                        type="checkbox"
                                                        value="{{ $orderDetails[$i]['order_detail_id'] }}|{{ $i }}">
                                                    <label for="giftCheckbox{{ $i }}"><i
                                                            class="fas fa-gift gift-icon"
                                                            style="cursor: pointer"></i></label>
                                                @endif

                                            </div>
                                            <div class="col-3 price_div">
                                                @if ($orderDetails[$i]['is_foc'] == 1 || $orderDetails[$i]['is_foc'] == '1')
                                                    <label class="item-price" style="color: orange">0 MMK</label>
                                                @else
                                                    <label class="item-price"
                                                        style="color: orange">{{ number_format($orderDetails[$i]['item_price'] * $orderDetails[$i]['quantity']) }}
                                                        MMK</label>
                                                @endif
                                            </div>
                                            <div class="col-1">
                                                @if ($userRoleId != 4)
                                                    <a href="#" data-toggle="tooltip" data-placement="top"
                                                        title="Delete Item"
                                                        data-order_detail_id={{ $orderDetails[$i]['order_detail_id'] }}
                                                        data-order_item_index={{ $i }}
                                                        data-item_id={{ $orderDetails[$i]['item_id'] }}
                                                        data-item_name={{ $orderDetails[$i]['item_name'] }}
                                                        class="delete_item_modal_dialog"><i
                                                            class="fa-solid fa-circle-xmark" style="color: orange"></i>
                                                    </a>
                                                @else
                                                @endif
                                            </div>
                                        </div>
                                        <hr>
                                        @php
                                            if (
                                                $orderDetails[$i]['is_foc'] != 1 ||
                                                $orderDetails[$i]['is_foc'] != '1'
                                            ) {
                                                $orderTotalAmount +=
                                                    $orderDetails[$i]['item_price'] * $orderDetails[$i]['quantity'];
                                            }

                                        @endphp
                                    @endfor
                                    @php
                                        $orderTotalAmount = number_format($orderTotalAmount);
                                    @endphp
                                @endif
                            </div>

                            <hr style="margin:10px 0 10px 0">
                            <div class="container">
                                <div class="row">
                                    <div class="col total_amt_lbl" style="font-size: 20px">
                                        Total
                                    </div>
                                    <div class="col orderTotalAmount_div" style="font-size:20px ;text-align: end">
                                        {{ $orderTotalAmount }} MMK
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <a class="printBtn" style="display: block; width:100%; text-align:center">
                                        <button class="btn" id="placeOrderButton" disabled
                                            style="background: #512DA8; color:white;font-weight:bold; height:45px; width: 100%;">
                                            Place Order
                                        </button>
                                    </a>

                                </div>
                            </div>
                        </div>
                        <!--Delete Item Modal -->
                        <div class="modal fade" id="delete_item_modal" data-bs-backdrop="static"
                            data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header text-center" style="background-color: #512DA8">
                                        <h1 class="modal-title fs-5 w-100" id="delete_modal_header" style="color: white">
                                        </h1>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body" style="margin-left: 20px; margin-right:20px">
                                        {{-- <form method="POST" id="itemDeleteModalForm"> --}}
                                            {{-- @csrf --}}
                                            <input type="text" id="order_detail_id" name="order_detail_id" hidden>
                                            <input type="text" id="order_item_index" name="order_item_index" hidden>
                                            <input type="text" name="item_id" id="item_id" hidden>
                                            <input type="text" name="remark" id="remark" hidden>
                                            <input type="text" name="is_ordered" id="is_ordered" hidden>

                                            <div class="row align-items-center mb-3 mt-3">
                                                <div>
                                                    <label class="form-label">Are you sure want to delete?</label>
                                                </div>
                                            </div>
                                        {{-- </form> --}}
                                    </div>
                                    <div class="modal-footer" style="margin-right: 20px">
                                        <input type="button" class="btn btn-danger delete-item" value="Delete">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--Add Order Item Preview Remark Modal -->
                        <div class="modal fade" id="add_order_item_preview_remark_modal" data-bs-backdrop="static"
                            data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header text-center" style="background-color: #512DA8">
                                        <h1 class="modal-title fs-5 w-100" id="staticBackdropLabel" style="color: white">
                                            Remark
                                        </h1>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body" style="margin-left: 20px; margin-right:20px">
                                        <form method="POST" id="addOrderItemRemarkPreviewForm">
                                            @csrf
                                            <input type="text" name="order_item_id" id="order_item_id" hidden>
                                            <input type="text" name="tableID" id="tableID" hidden>
                                            <input type="text" name="tableOrderValue" id="tableOrderValue" hidden>
                                            <div class="row mb-3 mt-3">
                                                <div class="col-5">
                                                    <label class="form-label">Item Name</label>
                                                </div>
                                                <div class="col">
                                                    <input class="form-control muted" type="text" name="item_name"
                                                        id="item_name" readonly>
                                                </div>
                                            </div>
                                            <div class="row mb-3 mt-3">
                                                <div class="col-5">
                                                    <label class="form-label">Remark<span
                                                            style="color: red">*</span></label>
                                                </div>
                                                <div class="col">
                                                    <textarea class="form-control" name="review_remark" id="review_remark" rows="3" readonly></textarea>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer" style="margin-right: 20px">
                                        <button class="btn custom_btn" data-bs-dismiss="modal">Ok</button>
                                        {{-- <input type="submit" class="btn custom_btn" value="Add"
                                            form="addOrderItemRemarkForm"> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--Add Order Item Remark Modal -->
                        <div class="modal fade" id="add_order_item_remark_modal" data-bs-backdrop="static"
                            data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header text-center" style="background-color: #512DA8">
                                        <h1 class="modal-title fs-5 w-100" id="staticBackdropLabel" style="color: white">
                                            Add Remark
                                        </h1>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body" style="margin-left: 20px; margin-right:20px">
                                        <form method="POST" id="addOrderItemRemarkForm">
                                            @csrf
                                            <input type="text" name="order_item_id" id="order_item_id" hidden>
                                            <input type="text" name="tableID" id="tableID" hidden>
                                            <input type="text" name="tableOrderValue" id="tableOrderValue" hidden>
                                            <div class="row mb-3 mt-3">
                                                <div class="col-5">
                                                    <label class="form-label">Item Name</label>
                                                </div>
                                                <div class="col">
                                                    <input class="form-control muted" type="text" name="item_name"
                                                        id="item_name" readonly>
                                                </div>
                                            </div>
                                            <div class="row mb-3 mt-3">
                                                <div class="col-5">
                                                    <label class="form-label">Remark<span
                                                            style="color: red">*</span></label>
                                                </div>
                                                <div class="col">
                                                    <textarea class="form-control" name="add_remark" id="add_remark" rows="3"></textarea>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer" style="margin-right: 20px">
                                        <button type="submit" class="btn custom_btn"
                                            form="addOrderItemRemarkForm">Add</button>
                                        {{-- <input type="submit" class="btn custom_btn" value="Add"
                                            form="addOrderItemRemarkForm"> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <form id="orderFormReload" method="POST" action="{{ route('store#orderPage') }}">
                    @csrf
                    <input type="hidden" name="tableID" id="tableID">
                    <input type="hidden" name="tableOrderValue" id="tableOrderValue" value={{ $tableOrderValue }}>
                </form>
                <form id="filteredOrderForm" method="POST">
                    @csrf
                    <input type="hidden" name="filteredOrder" id="filteredOrder">
                    <input type="hidden" name="orderID" id="orderID">
                </form>
                <form id="addOrderItemsForm" method="POST">
                    @csrf
                    <input type="hidden" name="unOrderItems" id="unOrderItems">
                    <input type="hidden" name="userID" id="userID">
                    <input type="hidden" name="tableID" id="tableID">
                    <input type="hidden" name="tableOrderNumber" id="tableOrderNumber">
                </form>
                <iframe id="printFrame" style="display: none;"></iframe>
            </div>
        </div>
    </section>
    <!-- QZ Tray Dependencies -->
    <script src="{{ asset('script/links_js/rsvp.min.js') }}"></script>
    <script src="{{ asset('script/links_js/sha256.min.js') }}"></script>
    <script src="{{ asset('script/links_js/qz-tray.js') }}"></script>

    <script src="{{ asset('script/links_js/jquery.3.6.4.min.js') }}"></script>
    <script src="{{ asset('script/links_js/jquery.validate.1.19.5.js') }}"></script>
    <script src="{{ asset('script/links_js/jquery.dataTables.1.13.7.min.js') }}"></script>
    <script src="{{ asset('script/links_js/dataTables.bootstrap5_1.13.7.min.js') }}"></script>
    <script>
        var orderDetailsJson = @json($orderDetails);
        var printer1 = @json(config('shop_name.printer1'));
        var printer2 = @json(config('shop_name.printer2'));

    </script>
    <script src="{{ asset('script/jquery.printPage.js') }}"></script>
    {{-- Dual Order Script --}}
    {{-- <script src="{{ asset('script/dual_order_script.js') }}"></script> --}}

    {{-- Normal Order Script --}}
    <script src="{{ asset('script/order_script.js') }}"></script>
    <script>
        $(document).ready(function() {



            var tableOrderValue = parseInt(@json($tableOrderValue));
            $('#tableOrderValue').val(tableOrderValue);

            $(document).on("click", ".add_order_item_remark_preview_modal_dialog", function() {

                var order_item_id = $(this).data('order_item_id');
                var order_item_name = $(this).data('order_item_name');
                var order_item_remark = $(this).data('order_item_remark');

                var tableID = $('#table_id').val();
                var tableOrderNumber = $('#table_order_number').val();

                $(".modal-body #order_item_id").val(order_item_id);
                $(".modal-body #tableID").val(tableID);
                $(".modal-body #tableOrderValue").val(tableOrderNumber);
                $(".modal-body #item_name").val(order_item_name);
                $(".modal-body #review_remark").val(order_item_remark);

            });

            $(document).ready(function() {
                $('.gift-checkbox').change(function() {
                    var isChecked = $(this).is(':checked');
                    if (isChecked) {
                        $(this).next().find('.gift-icon').addClass('checked');

                    } else {
                        $(this).next().find('.gift-icon').removeClass('checked');
                    }
                });
            });

        });
    </script>


@endsection
