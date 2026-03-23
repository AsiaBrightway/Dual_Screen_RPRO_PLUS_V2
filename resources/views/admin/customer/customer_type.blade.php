@extends('layouts.admin.master')
@section('title', 'Customer Type')

@section('content')
    <section class="home-section">
        <div class="home-title">
            <i class='bx bx-menu'></i>
            <span class="text">Customer Type</span>
        </div>
        <div class="home-content">
            <div class="row pb-3 align-items-center" style="color:#512DA8; font-weight:bold;">
                <div class="col-12 col-md-7">
                    <label>Add New Customer Type</label>
                </div>

                <div class="col-12 col-md-5 text-md-end text-start mt-3 mt-md-0">
                    <button class="btn btn-danger customBtn-clear" id="clear"><i class="fa-solid fa-eraser"
                            style="padding-right: 5px"></i>Clear</button>
                    <button type="submit" form="customerTypeFormCreate" class="btn btn-primary customBtn-save ms-1 mt-0"><i
                            class="fa-regular fa-floppy-disk" style="padding-right: 5px"></i>Save</button>
                </div>
            </div>

            @if(session('success'))
                <div id="flash-message" class="alert alert-success alert-dismissible d-flex align-items-center fade show">
                    <i class="fa-solid fa-circle-check"></i>
                    <strong class="mx-2">Success!</strong> {{ session('success') }}
                </div>
            @endif
            
            <div id="customer_type_info_label" class="row align-items-center bg-white">
                <div class="col-10">
                    <label><i class="fa-solid fa-user-tie" style="padding-left:5px; padding-right: 12px"></i>Customer Type
                        Info</label>
                </div>
                <div class="col-2" style="text-align: right">
                    <i class="bx bxs-chevron-down arrow"></i>
                </div>
            </div>
            <div class="customer_type_info_container shadow-sm">
                <form action="{{ route('customerType#create') }}" method="POST" id="customerTypeFormCreate">
                    @csrf
                    <div class="row mb-3 justify-content-center">
                        <div class="col-3 customer-info-label">
                            <label class="col-form-label">Customer Type Name <span style="color: red">*</span></label>
                        </div>
                        <div class="col-4 customer-info-text">
                            <input class="form-control @error('customer_type_name') is-invalid @enderror" type="text"
                                id="customer_type_name" name="customer_type_name" value="{{ old('customer_type_name') }}">
                            @error('customer_type_name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3 justify-content-center">
                        <div class="col-3 customer-info-label">
                            <label class="col-form-label">Other Name</label>
                        </div>
                        <div class="col-4 customer-info-text">
                            <input class="form-control" type="text" id="other_name" name="other_name"
                                value="{{ old('other_name') }}">
                        </div>
                    </div>
                    <div class="row mb-3 justify-content-center">
                        <div class="col-3 customer-info-label">
                            <label class="col-form-label">Customer Type Code <span style="color: red">*</span></label>
                        </div>
                        <div class="col-4 customer-info-text">
                            <input class="form-control @error('customer_type_code') is-invalid @enderror" type="text"
                                id="customer_type_code" name="customer_type_code" value="{{ old('customer_type_code') }}">
                            @error('customer_type_code')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row align-items-center justify-content-center">
                        <div class="col-3 customer-info-label">
                            <label class="col-form-label">Discontinued</label>
                        </div>
                        <div class="col-4 customer-info-text">
                            <input class="form-check-input" type="checkbox" name="is_discontinued" id="is_discontinued">
                        </div>
                    </div>
                </form>
            </div>
            <div id="customer_type_list_label" class="row align-items-center bg-white">
                <div class="col-10">
                    <label><i class="fa-solid fa-table-list" style="padding-left:5px; padding-right: 16px"></i>Customer
                        Type Lists</label>
                </div>
                <div class="col-2" style="text-align: right">
                    <i class="bx bxs-chevron-down arrow"></i>
                </div>
            </div>
            <div class="customer_type_list_container shadow-sm">
                <table id="customer_type_list" class="table table-striped nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Cutomer Type Name</th>
                            <th>Other Name</th>
                            <th>Customer Type Code</th>
                            <th>Discontinued</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($customerTypes) != 0)
                            @php
                                $count = 1;
                            @endphp
                            @foreach ($customerTypes as $customerType)
                                <tr>
                                    <td>{{ $count }}</td>
                                    <td>{{ $customerType['customer_type_name'] }}</td>
                                    <td>{{ $customerType['other_name'] }}</td>
                                    <td>{{ $customerType['customer_type_code'] }}</td>
                                    @if ($customerType['is_discontinued'] == 0)
                                        <td><input class="form-check-input" type="checkbox" onclick="return false;"></td>
                                    @elseif ($customerType['is_discontinued'] == 1)
                                        <td><input class="form-check-input" type="checkbox" checked onclick="return false;">
                                        </td>
                                    @endif
                                    <td><a data-customer_type_id="{{ $customerType['customer_type_id'] }}"
                                            data-customer_type_name="{{ $customerType['customer_type_name'] }}"
                                            data-other_name="{{ $customerType['other_name'] }}"
                                            data-customer_type_code="{{ $customerType['customer_type_code'] }}"
                                            data-is_discontinued="{{ $customerType['is_discontinued'] }}"
                                            data-bs-toggle="modal" data-bs-target="#edit_customerType_modal"
                                            class="edit_customerType_modal_dialog"><i class="fa-solid fa-pen"
                                                style="color: blue; cursor: pointer;"></i></a></td>
                                    <td><a data-customer_type_id="{{ $customerType['customer_type_id'] }}"
                                            data-customer_type_name="{{ $customerType['customer_type_name'] }}"
                                            data-bs-toggle="modal" data-bs-target="#delete_customerType_modal"
                                            class="delete_customerType_modal_dialog"><i class="fa-regular fa-trash-can"
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
                <!--Edit Customer Type Modal -->
                <div class="modal fade" id="edit_customerType_modal" data-bs-backdrop="static" data-bs-keyboard="false"
                    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header text-center" style="background-color: #512DA8">
                                <h1 class="modal-title fs-5 w-100" id="staticBackdropLabel" style="color: white">Update
                                    Customer Type
                                </h1>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body" style="margin-left: 20px; margin-right:20px">
                                <form action="{{ route('customerType#update') }}" method="POST"
                                    id="customerTypeEditModalForm">
                                    @csrf
                                    <input type="text" name="edit_customer_type_id" id="edit_customer_type_id" hidden>
                                    <div class="row mb-3 mt-3">
                                        <div class="col-6">
                                            <label class="form-label">Customer Type Name <span
                                                    style="color: red">*</span></label>
                                        </div>
                                        <div class="col">
                                            <input class="form-control" type="text" name="edit_customer_type_name"
                                                id="edit_customer_type_name">
                                        </div>
                                    </div>
                                    <div class="row mb-3 mt-3">
                                        <div class="col-6">
                                            <label class="form-label">Other Name</label>
                                        </div>
                                        <div class="col">
                                            <input class="form-control" type="text" name="edit_other_name"
                                                id="edit_other_name">
                                        </div>
                                    </div>
                                    <div class="row mb-3 mt-3">
                                        <div class="col-6">
                                            <label class="form-label">Customer Type Code <span
                                                    style="color: red">*</span></label>
                                        </div>
                                        <div class="col">
                                            <input class="form-control" type="text" name="edit_customer_type_code"
                                                id="edit_customer_type_code">
                                        </div>
                                    </div>
                                    <div class="row align-items-center mb-3">
                                        <div class="col-6">
                                            <label class="form-label text-danger">Discontinued</label>
                                        </div>
                                        <div class="col">
                                            <input class="form-check-input" type="checkbox"
                                                name="edit_customer_type_is_discontinued"
                                                id="edit_customer_type_is_discontinued">
                                        </div>
                                    </div>
                                </form>

                            </div>
                            <div class="modal-footer" style="margin-right: 20px">
                                <input type="submit" class="btn custom_btn" value="Update"
                                    form="customerTypeEditModalForm">
                            </div>
                        </div>
                    </div>
                </div>
                <!--Delete Customer Type Modal -->
                <div class="modal fade" id="delete_customerType_modal" data-bs-backdrop="static"
                    data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        {{-- <div class="modal-content">
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
                                    Delete Customer Type
                                </h2>
                                <p class="success-desc">
                                    Are you sure you want to delete?
                                </p>
                            </div>

                            <form action="{{ route('customerType#delete') }}" method="POST" id="customerTypeDeleteModalForm">
                                @csrf
                                <input type="text" name="delete_customer_type_id" id="delete_customer_type_id" hidden>
                                <div class="d-flex justify-content-center mt-3">
                                    <button type="submit" form="customerTypeDeleteModalForm" class="btn btn-danger px-4">Delete</button>
                                </div>
                            </form>
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
    <script src="{{ asset('script/customer_type_script.js') }}"></script>
@endsection
