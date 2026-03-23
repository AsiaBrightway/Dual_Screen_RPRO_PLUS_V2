@extends('layouts.admin.master')
@section('title', 'Price Control')

@section('content')
    <section class="home-section">
        <div class="home-title">
            <i class='bx bx-menu'></i>
            <span class="text">Price Control</span>
        </div>
        <div class="home-content">
            <div class="row pb-3 align-items-center" style="color:#512DA8; font-weight:bold">
                <div class="col-12 col-md-7">
                    <label>Sale Item Price</label>
                </div>
                <div class="col-12 col-md-5 text-md-end text-start mt-3 mt-md-0">
					<button class="btn btn-success" id="btn_excel"><i class="fa-solid fa-file-excel"></i>
                        Excel</button>
                    {{-- <button class="btn btn-danger customBtn-clear" id="clear"><i class="fa-solid fa-eraser"
                            style="padding-right: 5px"></i>Clear</button>
                    <button type="submit" form="priceControlFormCreate" class="btn btn-primary customBtn-save"><i
                            class="fa-regular fa-floppy-disk" style="padding-right: 5px"></i>Save</button> --}}
                </div>
            </div>
            @if(session('update'))
                <div id="flash-message" class="alert alert-success alert-dismissible d-flex align-items-center fade show">
                    <i class="fas fa-edit"></i>
                    <strong class="mx-2">Updated!</strong> {{ session('update') }}
                </div>
            @endif
            {{-- <div id="price_control_info_label" class="row align-items-center bg-white">
                <div class="col-10">
                    <label><i class="fa-solid fa-cookie-bite" style="padding-left:5px; padding-right: 12px"></i>Sale Item
                        Info</label>
                </div>
                <div class="col-2" style="text-align: right">
                    <i class="bx bxs-chevron-down arrow"></i>
                </div>
            </div>
            <div class="price_control_info_container shadow-sm">
                <form action="{{ route('priceControl#create') }}" method="POST" id="priceControlFormCreate">
                    @csrf
                    <div class="row mb-3 justify-content-center">
                        <div class="col-3 price-control-label">
                            <label class="col-form-label">Item Name <span style="color: red">*</span></label>
                        </div>
                        <div class="col-4 price-control-text">
                            <select class="form-select @error('item_name') is-invalid @enderror" id="item_name"
                                name="item_name">
                                <option value="0" {{ old('item_name') == 0 ? 'selected' : '' }}>Select-</option>
                                @if (count($stock_items) != 0)
                                    @foreach ($stock_items as $stock_item)
                                        <option value="{{ $stock_item['item_id'] }}"
                                            {{ old('item_name') == $stock_item['item_id'] ? 'selected' : '' }}>
                                            {{ $stock_item['item_name'] }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            @error('item_name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3 justify-content-center">
                        <div class="col-3 price-control-label">
                            <label class="col-form-label">Main Category</label>
                        </div>
                        <div class="col-4 price-control-text">
                            <input class="form-control muted" type="text" id="main_category" name="main_category"
                                value="{{ old('main_category') }}" readonly>
                        </div>
                    </div>
                    <div class="row mb-3 justify-content-center">
                        <div class="col-3 price-control-label">
                            <label class="col-form-label">Sub Category</label>
                        </div>
                        <div class="col-4 price-control-text">
                            <input class="form-control muted" type="text" id="sub_category" name="sub_category"
                                value="{{ old('sub_category') }}" readonly>
                        </div>
                    </div>
                    <div class="row mb-3 justify-content-center">
                        <div class="col-3 price-control-label">
                            <label class="col-form-label">Unit</label>
                        </div>
                        <div class="col-4 price-control-text">
                            <input class="form-control muted" type="text" id="unit_name" name="unit_name"
                                value="{{ old('unit_name') }}" readonly>
                            <input type="text" id="unit_id" name="unit_id" hidden>
                        </div>
                    </div>
                    <div class="row mb-3 justify-content-center">
                        <div class="col-3 price-control-label">
                            <label class="col-form-label">Unit Cost</label>
                        </div>
                        <div class="col-4 price-control-text">
                            <input class="form-control muted" type="text" id="unit_cost" name="unit_cost"
                                value="{{ old('unit_cost') }}" readonly>
                        </div>
                    </div>
                    <div class="row mb-3 justify-content-center">
                        <div class="col-3 price-control-label">
                            <label class="col-form-label">Selling Price</label>
                        </div>
                        <div class="col-4 price-control-text">
                            <input class="form-control @error('selling_price') is-invalid @enderror" type="text"
                                id="selling_price" name="selling_price" value="{{ old('selling_price') }}">
                            @error('selling_price')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </form>
            </div> --}}
            <div id="price_control_list_label" class="row align-items-center bg-white">
                <div class="col-10">
                    <label><i class="fa-solid fa-table-list" style="padding-left:5px; padding-right: 16px"></i>Sale Item
                        Price Lists</label>
                </div>
                <div class="col-2" style="text-align: right">
                    <i class="bx bxs-chevron-down arrow"></i>
                </div>
            </div>
            <div class="price_control_list_container shadow-sm">
                <table id="item_sale_price_list" class="table table-striped nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Item Name</th>
                            <th>Unit Name</th>
                            <th>Unit Cost</th>
                            <th>Selling Price</th>
                            <th>Edit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($itemSellingPrices) != 0)
                            @php
                                $count = 1;
                            @endphp
                            @foreach ($itemSellingPrices as $itemSellingPrice)
                                <tr>
                                    <td>{{ $count }}</td>
                                    <td>{{ $itemSellingPrice['item_name'] }}</td>
                                    <td>{{ $itemSellingPrice['unit_name'] }}</td>
                                    <td>{{ round($itemSellingPrice['unit_cost']) }}</td>
                                    <td>{{ $itemSellingPrice['item_selling_price'] }}</td>
                                    <td><a data-item_selling_price_id="{{ $itemSellingPrice['item_selling_price_id'] }}"
                                            data-item_id="{{ $itemSellingPrice['item_id'] }}"
                                            data-item_name="{{ $itemSellingPrice['item_name'] }}"
                                            data-main_category="{{ $itemSellingPrice['main_category_name'] }}"
                                            data-sub_category="{{ $itemSellingPrice['sub_category_name'] }}"
                                            data-unit_name="{{ $itemSellingPrice['unit_name'] }}"
                                            data-unit_cost="{{ $itemSellingPrice['unit_cost'] }}"
                                            data-selling_price="{{ $itemSellingPrice['item_selling_price'] }}"
                                            data-bs-toggle="modal" data-bs-target="#edit_saleItemPrice_modal"
                                            class="edit_saleItemPrice_modal_dialog"><i class="fa-solid fa-pen-to-square"
                                                style="color: orange;cursor: pointer;"></i></a></td>
                                </tr>
                                @php
                                    $count++;
                                @endphp
                            @endforeach
                        @endif
                    </tbody>
                </table>
                <!--Edit Customer Type Modal -->
                <div class="modal fade" id="edit_saleItemPrice_modal" data-bs-backdrop="static" data-bs-keyboard="false"
                    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header text-center" style="background-color: #512DA8">
                                <h1 class="modal-title fs-5 w-100" id="staticBackdropLabel" style="color: white">Update
                                    Sale Item Price
                                </h1>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body" style="margin-left: 20px; margin-right:20px">
                                <form action="{{ route('priceControl#update') }}" method="POST"
                                    id="saleItemPriceEditModalForm">
                                    @csrf
                                    <input type="text" name="edit_item_selling_price_id"
                                        id="edit_item_selling_price_id" hidden>
                                    <div class="row mb-3 mt-3">
                                        <div class="col-6">
                                            <label class="form-label">Item Name <span style="color: red">*</span></label>
                                        </div>
                                        <div class="col">
                                            <input class="form-control muted" type="text" name="edit_item_name"
                                                id="edit_item_name" readonly>
                                        </div>
                                    </div>
                                    <div class="row mb-3 mt-3">
                                        <div class="col-6">
                                            <label class="form-label">Main Category</label>
                                        </div>
                                        <div class="col">
                                            <input class="form-control muted" type="text" name="edit_main_category"
                                                id="edit_main_category" readonly>
                                        </div>
                                    </div>
                                    <div class="row mb-3 mt-3">
                                        <div class="col-6">
                                            <label class="form-label">Sub Category</label>
                                        </div>
                                        <div class="col">
                                            <input class="form-control muted" type="text" name="edit_sub_category"
                                                id="edit_sub_category" readonly>
                                        </div>
                                    </div>
                                    <div class="row mb-3 mt-3">
                                        <div class="col-6">
                                            <label class="form-label">Unit</label>
                                        </div>
                                        <div class="col">
                                            <input class="form-control muted" type="text" name="edit_unit"
                                                id="edit_unit" readonly>
                                        </div>
                                    </div>
                                    <div class="row mb-3 mt-3">
                                        <div class="col-6">
                                            <label class="form-label">Unit Cost</label>
                                        </div>
                                        <div class="col">
                                            <input class="form-control" type="text" name="edit_unit_cost"
                                                id="edit_unit_cost" >
                                        </div>
                                    </div>
                                    <div class="row mb-3 mt-3">
                                        <div class="col-6">
                                            <label class="form-label">Selling Price</label>
                                        </div>
                                        <div class="col">
                                            <input class="form-control" type="text" name="edit_selling_price"
                                                id="edit_selling_price">
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer" style="margin-right: 20px">
                                <input type="submit" class="btn custom_btn" value="Update"
                                    form="saleItemPriceEditModalForm">
                            </div>
                        </div>
                    </div>
                </div>
                <!--Delete Customer Type Modal -->
                <div class="modal fade" id="delete_customerType_modal" data-bs-backdrop="static"
                    data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header text-center" style="background-color: #512DA8">
                                <h1 class="modal-title fs-5 w-100" id="delete_modal_header" style="color: white">
                                </h1>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                    aria-label="Close"></button>

                            </div>
                            <div class="modal-body" style="margin-left: 20px; margin-right:20px">
                                <form action="{{ route('customerType#delete') }}" method="POST"
                                    id="customerTypeDeleteModalForm">
                                    @csrf
                                    <input type="text" name="delete_customer_type_id" id="delete_customer_type_id"
                                        hidden>
                                    <div class="row align-items-center mb-3 mt-3">
                                        <div>
                                            <label class="form-label">Are you sure want to delete?</label>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer" style="margin-right: 20px">
                                <input type="submit" form="customerTypeDeleteModalForm" class="btn btn-danger"
                                    value="Delete">
                            </div>

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
    <script src="{{ asset('script/links_js/xlsx.full.min.js') }}"></script>
    <script src="{{ asset('script/links_js/tableExport.1.10.25.min.js') }}"></script>
    <script src="{{ asset('script/price_control_script.js') }}"></script>
@endsection
