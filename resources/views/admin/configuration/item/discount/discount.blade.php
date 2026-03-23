@extends('layouts.admin.master')
@section('title', 'Discount')

@section('content')
    {{-- <style>
        .discount_list {
            table-layout: fixed;
            width: 100%;
        }

        .discount_list th {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
    </style> --}}
    <section class="home-section">
        <div class="home-title">
            <i class='bx bx-menu'></i>
            <span class="text">Discount</span>
        </div>
        <div class="home-content">
            <div class="row pb-3 align-items-center" style="color:#512DA8; font-weight:bold">
                <div class="col-12 col-md-7">
                    <label>Add New Discount</label>
                </div>
                <div class="col-12 col-md-5 text-md-end text-start mt-3 mt-md-0">
                    <button class="btn btn-danger customBtn-clear" id="clear"><i class="fa-solid fa-eraser"
                            style="padding-right: 5px"></i>Clear</button>
                    <button type="submit" form="discountFormCreate" class="btn btn-primary customBtn-save ms-1 mt-0"><i
                            class="fa-regular fa-floppy-disk" style="padding-right: 5px"></i>Save</button>
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
            
            <form action="{{ route('discount#create') }}" method="POST" id="discountFormCreate">
                @csrf
                <div id="discount_info_label" class="row align-items-center bg-white">
                    <div class="col-10">
                        <label><i class="fa-solid fa-tag" style="padding-left:5px; padding-right: 12px"></i>Discount
                            Info</label>
                    </div>
                    <div class="col-2" style="text-align: right">
                        <i class="bx bxs-chevron-down arrow"></i>
                    </div>
                </div>
                <div class="discount_info_container shadow-sm">
                    <div class="row border border-4 border-gray-800" style="border-radius: 10px">
                        <div class="discount-info-left col-6 pt-3 border-end border-4 border-gray-800">
                            <div class="row align-items-center mb-3 justify-content-center">
                                <div class="col-5">
                                    <label class="col-form-label">Main Category <span style="color: red">*</span></label>
                                </div>
                                <div class="col-5">
                                    <select class="form-select  @error('main_category') is-invalid @enderror"
                                        id="main_category" name="main_category">
                                        <option value="0">Select</option>
                                        @if (count($mainCategories) != 0)
                                            @foreach ($mainCategories as $mainCategory)
                                                <option value={{ $mainCategory['main_category_id'] }}>
                                                    {{ $mainCategory['main_category_name'] }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('main_category')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row  mb-3 justify-content-center">
                                <div class="col-5">
                                    <label class="col-form-label">Sub Category <span style="color: red">*</span></label>
                                </div>
                                <div class="col-5">
                                    <select class="form-select @error('sub_category') is-invalid @enderror"
                                        id="sub_category" name="sub_category">
                                        <!-- Options will be populated based on the selected main category using JavaScript -->
                                    </select>
                                    @error('sub_category')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row  mb-3 justify-content-center">
                                <div class="col-5">
                                    <label class="col-form-label">Item Name <span style="color: red">*</span></label>
                                </div>
                                <div class="col-5">
                                    <select class="form-select @error('items') is-invalid @enderror" id="items"
                                        name="items">
                                        <!-- Options will be populated based on the selected main category using JavaScript -->
                                    </select>
                                    @error('items')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row  mb-3 justify-content-center">
                                <div class="col-5">
                                    <label class="col-form-label">Description <span style="color: red">*</span></label>
                                </div>
                                <div class="col-5">
                                    <input class="form-control @error('description') is-invalid @enderror" type="text"
                                        id="description" name="description" value="{{ old('description') }}">
                                    @error('description')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row  mb-3 justify-content-center">
                                <div class="col-5">
                                    <label class="col-form-label">Other Description</label>
                                </div>
                                <div class="col-5">
                                    <input class="form-control" type="text" id="other_description"
                                        name="other_description" value="{{ old('other_description') }}">
                                </div>
                            </div>
                        </div>
                        <div class="discount-info-right col-6 pt-3">
                            <div class="row mb-3 justify-content-center">
                                <div class="col-5">
                                    <label class="col-form-label">Item Price</label>
                                </div>
                                <div class="col-5">
                                    <input class="form-control muted" style="text-align: end" type="text" id="item_price"
                                        name="item_price" readonly>
                                </div>
                            </div>
                            <div class="row mb-3 justify-content-center">
                                <div class="col-5">
                                    <label class="col-form-label">Buy Quantity <span style="color: red">*</span></label>
                                </div>
                                <div class="col-5">
                                    <input class="form-control @error('buy_quantity') is-invalid @enderror muted"
                                        style="text-align: end" type="number" id="buy_quantity" name="buy_quantity"
                                        value="1" min="1" readonly>
                                    @error('buy_quantity')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3 justify-content-center ">
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
                                        style="text-align: end" type="number" name="amount_discount"
                                        id="amount_discount" value="{{ old('amount_discount') }}" min="0">
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
                                        style="text-align: end" type="number" name="percent_discount"
                                        id="percent_discount" value="{{ old('percent_discount', '0') }}" min="0"
                                        readonly>
                                    @error('percent_discount')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3 justify-content-center">
                                <div class="col-5">
                                    <label class="col-form-label">Promotion Price</label>
                                </div>
                                <div class="col-5">
                                    <input class="form-control muted" style="text-align: end" id="promotion_price"
                                        name="promotion_price" type="text">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="discount_details_label" class="row align-items-center bg-white">
                    <div class="col-10">
                        <label><i class="fa-solid fa-tags" style="padding-left:5px; padding-right: 12px"></i>Discount
                            Details</label>
                    </div>
                    <div class="col-2" style="text-align: right">
                        <i class="bx bxs-chevron-down arrow"></i>
                    </div>
                </div>
                <div class="discount_details_container shadow-sm">
                    <div class="row border border-4 border-gray-800" style="border-radius: 10px">
                        <div class="discount-detail-left col-6 pt-3 border-end border-4 border-gray-800">
                            <p style="padding-bottom:10px; color:#512DA8; font-weight:bold">Discount Days</p>
                            <div class="form-check" style="padding-left: 30%; padding-bottom:10px">
                                <input class="form-check-input" type="checkbox" value="1" id="monday"
                                    name="monday">
                                <label class="form-check-label" for="monday">
                                    Monday
                                </label>
                            </div>
                            <div class="form-check" style="padding-left: 30%; padding-bottom:10px">
                                <input class="form-check-input" type="checkbox" value="1" id="tuesday"
                                    name="tuesday">
                                <label class="form-check-label" for="tuesday">
                                    Tuesday
                                </label>
                            </div>
                            <div class="form-check" style="padding-left: 30%; padding-bottom:10px">
                                <input class="form-check-input" type="checkbox" value="1" id="wednesday"
                                    name="wednesday">
                                <label class="form-check-label" for="wednesday">
                                    Wednesday
                                </label>
                            </div>
                            <div class="form-check" style="padding-left: 30%; padding-bottom:10px">
                                <input class="form-check-input" type="checkbox" value="1" id="thursday"
                                    name="thursday">
                                <label class="form-check-label" for="thursday">
                                    Thursday
                                </label>
                            </div>
                            <div class="form-check" style="padding-left: 30%; padding-bottom:10px">
                                <input class="form-check-input" type="checkbox" value="1" id="friday"
                                    name="friday">
                                <label class="form-check-label" for="friday">
                                    Friday
                                </label>
                            </div>
                            <div class="form-check" style="padding-left: 30%; padding-bottom:10px">
                                <input class="form-check-input" type="checkbox" value="1" id="saturday"
                                    name="saturday">
                                <label class="form-check-label" for="saturday">
                                    Saturday
                                </label>
                            </div>
                            <div class="form-check" style="padding-left: 30%; padding-bottom:10px;">
                                <input class="form-check-input" type="checkbox" value="1" id="sunday"
                                    name="sunday">
                                <label class="form-check-label" for="sunday">
                                    Sunday
                                </label>
                            </div>
                        </div>
                        <div class="discount-detail-right col-6 border-4 border-gray-800 ">
                            <div class="row pt-3 pb-3 border-bottom border-4 border-gray-800">
                                <p style="color:#512DA8; font-weight:bold">Date Range</p>
                                <div class="row align-items-center mb-3 justify-content-center">
                                    <div class="col-5">
                                        <label class="col-form-label" style="padding-left: 20%">Start Date</label>
                                    </div>
                                    <div class="col-7">
                                        <input
                                            class="form-control form-control-sm @error('start_date') is-invalid @enderror"
                                            type="date" id="start_date" name="start_date">
                                        @error('start_date')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row align-items-center mb-3 justify-content-center">
                                    <div class="col-5">
                                        <label class="col-form-label" style="padding-left: 20%">End Date</label>
                                    </div>
                                    <div class="col-7">
                                        <input class="form-control form-control-sm @error('end_date') is-invalid @enderror"
                                            type="date" id="end_date" name="end_date">
                                        @error('end_date')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row pt-3">
                                <p style="color:#512DA8; font-weight:bold">Happy Hour</p>
                                <div class="row align-items-center mb-3 justify-content-center">
                                    <div class="col-5">
                                        <label class="col-form-label" style="padding-left: 20%">Start Hour</label>
                                    </div>
                                    <div class="col-7">
                                        <input class="form-control form-control-sm" type="time" id="start_happy_hour"
                                            name="start_happy_hour">
                                    </div>
                                </div>
                                <div class="row align-items-center mb-3 justify-content-center">
                                    <div class="col-5">
                                        <label class="col-form-label" style="padding-left: 20%">End Hour</label>
                                    </div>
                                    <div class="col-7">
                                        <input class="form-control form-control-sm" type="time" id="end_happy_hour"
                                            name="end_happy_hour">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div id="discount_list_label" class="row align-items-center bg-white">
                <div class="col-10">
                    <label><i class="fa-solid fa-table-list" style="padding-left:5px; padding-right: 16px"></i>Discount
                        Lists</label>
                </div>
                <div class="col-2" style="text-align: right">
                    <i class="bx bxs-chevron-down arrow"></i>
                </div>
            </div>
            <div class="discount_list_container shadow-sm">
                <table id="discount_list" class="discount_list table table-striped nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Item Name</th>
                            <th>Description</th>
                            <th>Other Description</th>
                            <th>Item Price</th>
                            <th>Discount Type</th>
                            <th>Amount Discount</th>
                            <th>Percent Discount</th>
                            <th>Promotion Price</th>
                            <th>Mon</th>
                            <th>Tue</th>
                            <th>Wed</th>
                            <th>Thu</th>
                            <th>Fri</th>
                            <th>Sat</th>
                            <th>Sun</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Start Happy Hour</th>
                            <th>End Happy Hour</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($discounts) != 0)
                            @php
                                $count = 1;
                            @endphp
                            @foreach ($discounts as $discount)
                                <tr>
                                    <td style="text-align: center">{{ $count }}</td>
                                    <td style="word-wrap:break-world; white-space:normal;">{{ $discount['item_name'] }}
                                    </td>
                                    <td style="word-wrap:break-world; white-space:normal;">{{ $discount['description'] }}
                                    </td>
                                    <td style="word-wrap:break-world; white-space:normal;">
                                        {{ $discount['other_description'] }}</td>
                                    <td style="text-align: center">{{ number_format($discount['item_price']) }}</td>
                                    <td>{{ $discount['discount_type'] }}</td>
                                    <td style="text-align: center">{{ number_format($discount['amount_discount']) }}</td>
                                    <td style="text-align: center">{{ $discount['percent_discount'] }}</td>
                                    <td style="text-align: center">{{ number_format($discount['promotion_price']) }}</td>
                                    @if ($discount['monday'] == 'null' || $discount['monday'] == null)
                                        <td><input class="form-check-input" type="checkbox" onclick="return false;"></td>
                                    @elseif ($discount['monday'] == 1)
                                        <td><input class="form-check-input" type="checkbox" checked
                                                onclick="return false;">
                                        </td>
                                    @endif
                                    @if ($discount['tuesday'] == 'null' || $discount['tuesday'] == null)
                                        <td><input class="form-check-input" type="checkbox" onclick="return false;"></td>
                                    @elseif ($discount['tuesday'] == 1)
                                        <td><input class="form-check-input" type="checkbox" checked
                                                onclick="return false;">
                                        </td>
                                    @endif
                                    @if ($discount['wednesday'] == 'null' || $discount['wednesday'] == null)
                                        <td><input class="form-check-input" type="checkbox" onclick="return false;"></td>
                                    @elseif ($discount['wednesday'] == 1)
                                        <td><input class="form-check-input" type="checkbox" checked
                                                onclick="return false;">
                                        </td>
                                    @endif
                                    @if ($discount['thursday'] == 'null' || $discount['thursday'] == null)
                                        <td><input class="form-check-input" type="checkbox" onclick="return false;"></td>
                                    @elseif ($discount['thursday'] == 1)
                                        <td><input class="form-check-input" type="checkbox" checked
                                                onclick="return false;">
                                        </td>
                                    @endif
                                    @if ($discount['friday'] == 'null' || $discount['friday'] == null)
                                        <td><input class="form-check-input" type="checkbox" onclick="return false;"></td>
                                    @elseif ($discount['friday'] == 1)
                                        <td><input class="form-check-input" type="checkbox" checked
                                                onclick="return false;">
                                        </td>
                                    @endif
                                    @if ($discount['saturday'] == 'null' || $discount['saturday'] == null)
                                        <td><input class="form-check-input" type="checkbox" onclick="return false;"></td>
                                    @elseif ($discount['saturday'] == 1)
                                        <td><input class="form-check-input" type="checkbox" checked
                                                onclick="return false;">
                                        </td>
                                    @endif
                                    @if ($discount['sunday'] == 'null' || $discount['sunday'] == null)
                                        <td><input class="form-check-input" type="checkbox" onclick="return false;"></td>
                                    @elseif ($discount['sunday'] == 1)
                                        <td><input class="form-check-input" type="checkbox" checked
                                                onclick="return false;">
                                        </td>
                                    @endif
                                    <td>{{ date('d-M-y', strtotime($discount['start_date'])) }}</td>
                                    <td>{{ date('d-M-y', strtotime($discount['end_date'])) }}</td>
                                    <td>{{ date('h:i A', strtotime($discount['start_happy_hour'])) }}</td>
                                    <td>{{ date('h:i A', strtotime($discount['end_happy_hour'])) }}</td>
                                    <td><a href="{{ route('discount#updatePage', $discount['item_discount_id']) }}"><i
                                                class="fa-solid fa-pen" style="color: blue; cursor: pointer;"></i></a>
                                    </td>
                                    <td><a data-item_discount_id="{{ $discount['item_discount_id'] }}"
                                            data-item_name="{{ $discount['item_name'] }}"
                                            data-description="{{ $discount['description'] }}" data-bs-toggle="modal"
                                            data-bs-target="#delete_discount_modal"
                                            class="delete_discount_modal_dialog"><i class="fa-regular fa-trash-can"
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

            </div>
            <!--Delete discount Modal -->
            <div class="modal fade" id="delete_discount_modal" data-bs-backdrop="static" data-bs-keyboard="false"
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
                            <form action="{{ route('discount#delete') }}" method="POST" id="discountDeletModalForm">
                                @csrf
                                <input type="text" name="delete_discount_id" id="delete_discount_id" hidden>
                                <div class="row align-items-center mb-3 mt-3">
                                    <div>
                                        <label class="form-label">Are you sure want to delete?</label>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer" style="margin-right: 20px">
                            <input type="submit" class="btn btn-danger" value="Delete" form="discountDeletModalForm">
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
                                Delete Discount
                            </h2>
                            <p id="deleteMessage" class="success-desc">
                                Are you sure you want to delete?
                            </p>
                        </div>

                        <form action="{{ route('discount#delete') }}" method="POST" id="discountDeletModalForm">
                            @csrf
                            <input type="text" name="delete_discount_id" id="delete_discount_id" hidden>
                            <div class="d-flex justify-content-center mt-3">
                                <button type="submit" form="discountDeletModalForm" class="btn btn-danger px-4">Delete</button>
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
    <script src="{{ asset('script/discount_script.js') }}"></script>

@endsection
