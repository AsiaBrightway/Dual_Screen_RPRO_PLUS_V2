@extends('layouts.admin.master')
@section('title', 'Item')

@section('content')
<style>
    .item_list {
        table-layout: fixed;
        width: 100%;
    }

    .item_list th {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
</style>
<section class="home-section">
    <div class="home-title">
        <i class='bx bx-menu'></i>
        <span class="text">Item</span>
    </div>
    <div class="home-content" style="margin-left: 40px;">
        <div class="row pb-3 align-items-center" style="color:#512DA8; font-weight:bold">
            <div class="col-12 col-md-7">
                <label>Add New Item</label>
            </div>
            <div class="col-12 col-md-5 text-md-end text-start mt-3 mt-md-0">
                <button class="btn btn-danger customBtn-clear" id="clear"><i class="fa-solid fa-eraser"
                        style="padding-right: 5px"></i>Clear</button>
                <button type="submit" class="btn btn-primary customBtn-save ms-1 mt-0" form="itemCreateForm"><i
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

        <div id="item_info_label" class="row align-items-center bg-white">
            <div class="col-10">
                <label><i class="fa-solid fa-cookie-bite" style="padding-left:5px; padding-right: 12px"></i>Item
                    Info</label>
            </div>
            <div class="col-2" style="text-align: right">
                <i class="bx bxs-chevron-down arrow"></i>
            </div>
        </div>
        <div class="item_info_container shadow-sm">
            <form action="{{ route('item#create') }}" method="POST" enctype="multipart/form-data" id="itemCreateForm">
                @csrf
                <div class="row border-4 border-gray-800" style="border-radius: 10px">
                    <div class="item-info-left col-6 pt-4 pb-4 border-end border-4 border-gray-800">
                        <div class="row mb-3 justify-content-center">
                            <div class="col-4">
                                <label class="col-form-label">Main Category <span style="color: red">*</span></label>
                            </div>
                            <div class="col-6">
                                <select class="form-select  @error('main_category') is-invalid @enderror"
                                    id="main_category" name="main_category">
                                    <option value="0">Select-</option>
                                    @if (count($mainCategories) != 0)
                                    @foreach ($mainCategories as $mainCategory)
                                    <option value={{ $mainCategory['main_category_id'] }}>
                                        {{ $mainCategory['main_category_name'] }}
                                    </option>
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
                        <div class="row mb-3 justify-content-center">
                            <div class="col-4">
                                <label class="col-form-label">Sub Category <span style="color: red">*</span></label>
                            </div>
                            <div class="col-6">
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
                        <div class="row mb-3 justify-content-center">
                            <div class="col-4">
                                <label class="col-form-label">Item Type</label>
                            </div>
                            <div class="col-6">
                                <select class="form-select" id="item_type" name="item_type">
                                    @if (count($itemTypes) != 0)
                                    @foreach ($itemTypes as $itemType)
                                    <option value={{ $itemType['item_type_id'] }}>
                                        {{ $itemType['item_type_name'] }}
                                    </option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3 justify-content-center">
                            <div class="col-4">
                                <label class="col-form-label">Item Code <span style="color: red">*</span></label>
                            </div>
                            <div class="col-6">
                                <input class="form-control bg-light @error('item_code') is-invalid @enderror" type="text"
                                    id="item_code" name="item_code" value="{{ old('item_code') }}">
                                @error('item_code')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        {{-- <div class="row mb-3 justify-content-center">
                                <div class="col-4">
                                    <label class="col-form-label">Bar Code <span style="color: red">*</span></label>
                                </div>
                                <div class="col-6">
                                    <input class="form-control @error('bar_code') is-invalid @enderror" type="text"
                                        id="bar_code" name="bar_code" value="{{ old('bar_code') }}">
                        @error('bar_code')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div> --}}
                <div class="row mb-3 justify-content-center">
                    <div class="col-4">
                        <label class="col-form-label">Unit Cost</label>
                    </div>
                    <div class="col-6 price-control-text">
                        <input class="form-control" type="text" value="0" id="unit_cost"
                            name="unit_cost" value="{{ old('unit_cost') }}">
                    </div>
                </div>
                <div class="row mb-3 justify-content-center">
                    <div class="col-4">
                        <label class="col-form-label">Selling Price <span style="color: red">*</span></label>
                    </div>
                    <div class="col-6 price-control-text">
                        <input class="form-control @error('selling_price') is-invalid @enderror" type="text"
                            id="selling_price" name="selling_price" value="{{ old('selling_price') }}">
                        @error('selling_price')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
        </div>
        <div class="item-info-right col-6 pt-4 pb-4">
            <div class="row mb-3 justify-content-center">
                <div class="col-4">
                    <label class="col-form-label">Item Name <span style="color: red">*</span></label>
                </div>
                <div class="col-6">
                    <input class="form-control @error('item_name') is-invalid @enderror" type="text"
                        id="item_name" name="item_name" value="{{ old('item_name') }}">
                    @error('item_name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
            </div>
            <div class="row mb-3 justify-content-center">
                <div class="col-4">
                    <label class="col-form-label">Other Name</label>
                </div>
                <div class="col-6">
                    <input class="form-control" type="text" id="other_name" name="other_name"
                        value="{{ old('other_name') }}">

                </div>
            </div>
            <div class="row mb-3 justify-content-center">
                <div class="col-4">
                    <label class="col-form-label">Item Unit <span style="color: red">*</span></label>
                </div>
                <div class="col-6">
                    <select class="form-select" id="item_unit" name="item_unit">
                        @if (count($units) != 0)
                        @foreach ($units as $unit)
                        <option value={{ $unit['unit_id'] }}>
                            {{ $unit['unit_name'] }}
                        </option>
                        @endforeach
                        @endif
                    </select>
                </div>
            </div>

            <div class="row mb-3 justify-content-center">
                <div class="col-4">
                    <label class="col-form-label">Item Image</label>
                </div>
                <div class="col-6">
                    <input type="file"
                        class="form-control form-control-sm @error('item_image') is-invalid @enderror"
                        id="item_image" name="item_image">
                    @error('item_image')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
            </div>
            <div class="row align-items-center justify-content-center">
                <div class="col-4">
                    <label class="col-form-label">Discontinued</label>
                </div>
                <div class="col-6">
                    <input class="form-check-input" type="checkbox" name="is_discontinued"
                        id="is_discontinued">
                </div>
            </div>
        </div>
    </div>
    </form>
    </div>
    <div id="item_list_label" class="row align-items-center bg-white">
        <div class="col-10">
            <label><i class="fa-solid fa-table-list" style="padding-left:5px; padding-right: 16px"></i>Item
                Lists</label>
        </div>
        <div class="col-2" style="text-align: right">
            <i class="bx bxs-chevron-down arrow"></i>
        </div>
    </div>
    <div class="item_list_container shadow-sm">
        <table id="item_list" class="item_list table table-striped nowrap" style="width:100%">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Image</th>
                    <th>Item Name</th>
                    {{-- <th>Other Name</th> --}}
                    <th>Item Code</th>
                    <th>Main Category</th>
                    <th>Sub Category</th>
                    <th>Item Unit</th>
                    <th>Item Type</th>
                    <th>Discontinued</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                @if (count($items) != 0)
                @php
                $count = 1;
                @endphp
                @foreach ($items as $item)
                <tr>
                    <td style="text-align: center">{{ $count }}</td>
                    @if ($item['item_image'] == null)
                    <td> <img src="{{ asset('404_image.png') }}" alt=""
                            class="img-thumbnail shadow-sm" style="width:60px; height:60px"></td>
                    @else
                    <td><img src="{{ asset('storage/Images/' . $item['item_image']) }}"
                            alt="" class="img-thumbnail shadow-sm"
                            style="width:60px; height:60px"></td>
                    @endif
                    <td style="word-wrap:break-world; white-space:normal;">{{ $item['item_name'] }}</td>
                    <td>{{ $item['item_code'] }}</td>
                    <td>{{ $item['main_category_name'] }}</td>
                    <td>{{ $item['menu_category_name'] }}</td>
                    <td>{{ $item['unit_name'] }}</td>
                    <td>{{ $item['item_type_name'] }}</td>
                    @if ($item['item_is_discontinued'] == 0)
                    <td style="text-align: center"><input class="form-check-input" type="checkbox"
                            onclick="return false;"></td>
                    @elseif ($item['item_is_discontinued'] == 1)
                    <td style="text-align: center"><input class="form-check-input" type="checkbox"
                            checked onclick="return false;">
                    </td>
                    @endif
                    <td><a data-item_id="{{ $item['item_id'] }}"
                            data-main_category_id="{{ $item['main_category_id'] }}"
                            data-sub_category_id="{{ $item['sub_category_id'] }}"
                            data-item_type_id="{{ $item['item_type_id'] }}"
                            data-item_code="{{ $item['item_code'] }}"
                            data-bar_code="{{ $item['bar_code'] }}"
                            data-item_name="{{ $item['item_name'] }}"
                            data-item_other_name="{{ $item['item_other_name'] }}"
                            data-unit_id="{{ $item['unit_id'] }}"
                            data-item_is_discontinued="{{ $item['item_is_discontinued'] }}"
                            data-bs-toggle="modal" data-bs-target="#edit_item_modal"
                            class="edit_item_modal_dialog"><i class="fa-solid fa-pen"
                                style="color: blue; cursor: pointer;"></i></a>
                    </td>
                    <td>
                        <a href="javascript:void(0)" class="deleteItemBtn"
                            data-id="{{ $item['item_id'] }}" data-name="{{ $item['item_name'] }}"
                            data-has-orders="{{ $item['has_orders'] }}">
                            <i class="fa-regular fa-trash-can" style="color:red;cursor:pointer;"></i>
                        </a>
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
    <!--Edit item Modal -->
    <div class="modal fade" id="edit_item_modal" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header text-center" style="background-color: #512DA8">
                    <h1 class="modal-title fs-5 w-100" id="staticBackdropLabel" style="color: white">
                        Update Item
                    </h1>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>

                </div>
                <div class="modal-body" style="margin-left: 20px; margin-right:20px">
                    <form action="{{ route('item#update') }}" method="POST" id="itemEditModalForm"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="text" name="edit_item_id" id="edit_item_id" hidden>
                        <div class="row mb-3 justify-content-center">
                            <div class="col-5">
                                <label class="col-form-label">Main Category <span
                                        style="color: red">*</span></label>
                            </div>
                            <div class="col-6">
                                <select class="form-select" id="edit_main_category" name="edit_main_category">
                                    @if (count($mainCategories) != 0)
                                    @foreach ($mainCategories as $mainCategory)
                                    <option value={{ $mainCategory['main_category_id'] }}>
                                        {{ $mainCategory['main_category_name'] }}
                                    </option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3 justify-content-center">
                            <div class="col-5">
                                <label class="col-form-label">Sub Category <span
                                        style="color: red">*</span></label>
                            </div>
                            <div class="col-6">
                                <select class="form-select" id="edit_sub_category" name="edit_sub_category">
                                    <!-- Options will be populated based on the selected main category using JavaScript -->
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3 justify-content-center">
                            <div class="col-5">
                                <label class="col-form-label">Item Type <span style="color: red">*</span></label>
                            </div>
                            <div class="col-6">
                                <select class="form-select" id="edit_item_type" name="edit_item_type">
                                    @if (count($itemTypes) != 0)
                                    @foreach ($itemTypes as $itemType)
                                    <option value={{ $itemType['item_type_id'] }}>
                                        {{ $itemType['item_type_name'] }}
                                    </option>
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
                                <input class="form-control bg-light" type="text" id="edit_item_code"
                                    name="edit_item_code">
                            </div>
                        </div>
                        <div class="row mb-3 justify-content-center">
                            <div class="col-5">
                                <label class="col-form-label">Bar Code <span style="color: red">*</span></label>
                            </div>
                            <div class="col-6">
                                <input class="form-control bg-light" type="text" id="edit_bar_code"
                                    name="edit_bar_code">
                            </div>
                        </div>
                        <div class="row mb-3 justify-content-center">
                            <div class="col-5">
                                <label class="col-form-label">Item Name <span style="color: red">*</span></label>
                            </div>
                            <div class="col-6">
                                <input class="form-control" type="text" id="edit_item_name"
                                    name="edit_item_name">
                            </div>
                        </div>
                        <div class="row mb-3 justify-content-center">
                            <div class="col-5">
                                <label class="col-form-label">Other Name</label>
                            </div>
                            <div class="col-6">
                                <input class="form-control" type="text" id="edit_other_name"
                                    name="edit_other_name">
                            </div>
                        </div>
                        <div class="row mb-3 justify-content-center">
                            <div class="col-5">
                                <label class="col-form-label">Item Unit <span style="color: red">*</span></label>
                            </div>
                            <div class="col-6">
                                <select class="form-select" id="edit_item_unit" name="edit_item_unit">
                                    @if (count($units) != 0)
                                    @foreach ($units as $unit)
                                    <option value={{ $unit['unit_id'] }}>
                                        {{ $unit['unit_name'] }}
                                    </option>
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
                                <input type="file" class="form-control form-control-sm" id="edit_item_image"
                                    name="edit_item_image">
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-5">
                                <label class="col-form-label">Discontinued</label>
                            </div>
                            <div class="col-6">
                                <input class="form-check-input" type="checkbox" name="edit_is_discontinued"
                                    id="edit_is_discontinued">
                            </div>
                        </div>
                    </form>

                </div>
                <div class="modal-footer" style="margin-right: 20px">
                    <input type="submit" class="btn custom_btn" value="Update" form="itemEditModalForm">
                </div>

            </div>
        </div>
    </div>
    <!--Delete item Modal -->
    <div class="modal fade" id="deleteItemModal" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">
            <div class="success-card" style="padding-bottom: 1.5rem;">

                <button class="btn-cross-custom" data-bs-dismiss="modal">
                    <i class="fa-solid fa-x"></i>
                </button>

                <div class="icon-wrapper">
                    <div class="error-icon-circle">
                        <i class="fa-solid fa-exclamation-triangle"></i>
                    </div>
                </div>

                <div class="text-content text-center">
                    <h2 id="deleteItemModalTitle" class="success-title">
                        Delete Item
                    </h2>
                    <p id="deleteItemMessage" class="success-desc">
                        Are you sure you want to delete?
                    </p>
                </div>

                <form action="{{ route('item#delete') }}" method="POST">
                    @csrf
                    <input type="hidden" name="delete_item_id" id="delete_item_id">

                    <div class="d-flex justify-content-center gap-2 mt-3">
                        <button type="submit" id="confirmItemDeleteBtn" class="btn btn-danger px-4">
                            Delete
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>




    {{-- <div class="modal fade" id="cannot_delete_modal" tabindex="-1">
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
                                Cannot Delete Item
                            </h2>
                            <p class="success-desc">
                                This item has order records.<br>
                                Please remove orders first.
                            </p>
                        </div>

                        <div class="d-flex justify-content-center mt-3">
                            <button class="btn btn-secondary px-4" data-bs-dismiss="modal">
                                OK
                            </button>
                        </div>
                    </div>
                </div>
            </div> --}}

    </div>
</section>

<script src="{{ asset('script/links_js/jquery.3.6.4.min.js') }}"></script>
<script src="{{ asset('script/links_js/jquery.validate.1.19.5.js') }}"></script>
<script src="{{ asset('script/links_js/jquery.dataTables.1.13.7.min.js') }}"></script>
<script src="{{ asset('script/links_js/dataTables.bootstrap5_1.13.7.min.js') }}"></script>
<script src="{{ asset('script/item_script.js') }}"></script>

@endsection