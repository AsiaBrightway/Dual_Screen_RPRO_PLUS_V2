@extends('layouts.admin.master')
@section('title', 'Supplier Lists')

@section('content')
    <style>
        .supplier_list {
            table-layout: fixed;
            width: 100%;
        }

        .supplier_list th {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
    </style>
    <section class="home-section">
        <div class="home-title">
            <i class='bx bx-menu'></i>
            <span class="text">Supplier Lists</span>
        </div>
        <div class="home-content">

            @if(session('update'))
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

            <div id="supplier_list_label" class="row align-items-center bg-white mt-3">
                <div class="col-6">
                    <label><i class="fa-solid fa-table-list" style="padding-left:5px; padding-right: 12px"></i>Supplier
                        Lists</label>
                </div>
                <div class="col-6" style="text-align: right">
                    <i class="bx bxs-chevron-down arrow"></i>
                </div>
            </div>
            <div class="supplier_list_container shadow-sm show_container">
                <table id="supplier_list" class="supplier_list table table-striped nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Supplier Name</th>
                            <th>Other Name</th>
                            <th>Supplier Code</th>
                            <th>Phone Number</th>
                            <th>Email Address</th>
                            <th>City</th>
                            <th>Township</th>
                            <th>Address</th>
                            <th>Remark</th>
                            <th>Discontinued</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($suppliers) != 0)
                            @php
                                $count = 1;
                            @endphp
                            @foreach ($suppliers as $supplier)
                                <tr>
                                    <td>{{ $count }}</td>
                                    <td>{{ $supplier['supplier_name'] }}</td>
                                    <td>{{ $supplier['supplier_other_name'] }}</td>
                                    <td>{{ $supplier['supplier_code'] }}</td>
                                    <td>{{ $supplier['phone_number'] }}</td>
                                    <td>{{ $supplier['email'] }}</td>
                                    <td style="word-wrap: break-word; white-space:normal;">{{ $supplier['city_name'] }}</td>
                                    <td style="word-wrap: break-word; white-space:normal;">{{ $supplier['township_name'] }}
                                    </td>
                                    <td style="word-wrap: break-word; white-space:normal;">{{ $supplier['address'] }}</td>
                                    <td>{{ $supplier['remark'] }}</td>
                                    @if ($supplier['supplier_is_discontinued'] == 0)
                                        <td><input class="form-check-input" type="checkbox" onclick="return false;"></td>
                                    @elseif ($supplier['supplier_is_discontinued'] == 1)
                                        <td><input class="form-check-input" type="checkbox" checked onclick="return false;">
                                        </td>
                                    @endif
                                    <td><a data-supplier_id="{{ $supplier['supplier_id'] }}"
                                            data-supplier_name="{{ $supplier['supplier_name'] }}"
                                            data-supplier_other_name="{{ $supplier['supplier_other_name'] }}"
                                            data-supplier_code="{{ $supplier['supplier_code'] }}"
                                            data-phone_number="{{ $supplier['phone_number'] }}"
                                            data-email="{{ $supplier['email'] }}"
                                            data-city_id="{{ $supplier['city_id'] }}"
                                            data-township_id="{{ $supplier['township_id'] }}"
                                            data-address="{{ $supplier['address'] }}"
                                            data-remark="{{ $supplier['remark'] }}"
                                            data-supplier_is_discontinued="{{ $supplier['supplier_is_discontinued'] }}"
                                            data-bs-toggle="modal" data-bs-target="#edit_supplier_modal"
                                            class="edit_supplier_modal_dialog"><i class="fa-solid fa-pen"
                                                style="color: blue; cursor: pointer;"></i></a></td>
                                    <td><a data-supplier_id="{{ $supplier['supplier_id'] }}"
                                            data-supplier_name="{{ $supplier['supplier_name'] }}" data-bs-toggle="modal"
                                            data-bs-target="#delete_supplier_modal" class="delete_supplier_modal_dialog"><i
                                                class="fa-regular fa-trash-can" style="color: red;cursor: pointer;"></i></a>
                                    </td>
                                </tr>
                                @php
                                    $count++;
                                @endphp
                            @endforeach
                        @endif
                    </tbody>
                </table>
                <!--Edit Supplier Modal -->
                <div class="modal fade" id="edit_supplier_modal" data-bs-backdrop="static" data-bs-keyboard="false"
                    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header text-center" style="background-color: #512DA8">
                                <h1 class="modal-title fs-5 w-100" id="staticBackdropLabel" style="color: white">Update
                                    Supplier
                                </h1>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body" style="margin-left: 20px; margin-right:20px">
                                <form action="{{ route('supplier#update') }}" method="POST" id="supplierEditModalForm">
                                    @csrf
                                    <input type="text" name="edit_supplier_id" id="edit_supplier_id" hidden>
                                    <div class="row mb-3 mt-3">
                                        <div class="col-5">
                                            <label class="form-label">Supplier Name <span
                                                    style="color: red">*</span></label>
                                        </div>
                                        <div class="col">
                                            <input class="form-control" type="text" name="edit_supplier_name"
                                                id="edit_supplier_name">
                                        </div>
                                    </div>
                                    <div class="row mb-3 mt-3">
                                        <div class="col-5">
                                            <label class="form-label">Other Name</label>
                                        </div>
                                        <div class="col">
                                            <input class="form-control" type="text" name="edit_other_name"
                                                id="edit_other_name">
                                        </div>
                                    </div>
                                    <div class="row mb-3 mt-3">
                                        <div class="col-5">
                                            <label class="form-label">Supplier Code <span
                                                    style="color: red">*</span></label>
                                        </div>
                                        <div class="col">
                                            <input class="form-control" type="text" name="edit_supplier_code"
                                                id="edit_supplier_code">
                                        </div>
                                    </div>
                                    <div class="row mb-3 mt-3">
                                        <div class="col-5">
                                            <label class="form-label">Phone Number <span
                                                    style="color: red">*</span></label>
                                        </div>
                                        <div class="col">
                                            <input class="form-control" type="text" name="edit_phone_number"
                                                id="edit_phone_number">
                                        </div>
                                    </div>
                                    <div class="row mb-3 mt-3">
                                        <div class="col-5">
                                            <label class="form-label">Email Address</label>
                                        </div>
                                        <div class="col">
                                            <input class="form-control" type="text" name="edit_email"
                                                id="edit_email">
                                        </div>
                                    </div>
                                    <div class="row mb-3 mt-3">
                                        <div class="col-5">
                                            <label class="form-label">City <span style="color: red">*</span></label>
                                        </div>
                                        <div class="col">
                                            <select class="form-select" id="edit_city" name="edit_city">
                                                @if (count($cities) != 0)
                                                    @foreach ($cities as $city)
                                                        <option value={{ $city['city_id'] }}>
                                                            {{ $city['city_name'] }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3 mt-3">
                                        <div class="col-5">
                                            <label class="form-label">Township <span style="color: red">*</span></label>
                                        </div>
                                        <div class="col">
                                            <select class="form-select" id="edit_township" name="edit_township">
                                                <!-- Options will be populated based on the selected main category using JavaScript -->
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3 mt-3">
                                        <div class="col-5">
                                            <label class="form-label">Address <span style="color: red">*</span></label>
                                        </div>
                                        <div class="col">
                                            <input class="form-control" type="text" name="edit_address"
                                                id="edit_address">
                                        </div>
                                    </div>
                                    <div class="row mb-3 mt-3">
                                        <div class="col-5">
                                            <label class="form-label">Remark</label>
                                        </div>
                                        <div class="col">
                                            <input class="form-control" type="text" name="edit_remark"
                                                id="edit_remark">
                                        </div>
                                    </div>
                                    <div class="row align-items-center mb-3">
                                        <div class="col-5">
                                            <label class="form-label text-danger">Discontinued</label>
                                        </div>
                                        <div class="col">
                                            <input class="form-check-input" type="checkbox"
                                                name="edit_supplier_is_discontinued" id="edit_supplier_is_discontinued">
                                        </div>
                                    </div>
                                </form>

                            </div>
                            <div class="modal-footer" style="margin-right: 20px">
                                <input type="submit" class="btn custom_btn" value="Update"
                                    form="supplierEditModalForm">
                            </div>
                        </div>
                    </div>
                </div>
                <!--Delete Supplier Modal -->
                <div class="modal fade" id="delete_supplier_modal" data-bs-backdrop="static" data-bs-keyboard="false"
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
                                <form action="{{ route('supplier#delete') }}" method="POST"
                                    id="supplierDeleteModalForm">
                                    @csrf
                                    <input type="text" name="delete_supplier_id" id="delete_supplier_id" hidden>
                                    <div class="row align-items-center mb-3 mt-3">
                                        <div>
                                            <label class="form-label">Are you sure want to delete?</label>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer" style="margin-right: 20px">
                                <input type="submit" class="btn btn-danger" value="Delete"
                                    form="supplierDeleteModalForm">
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
                                    Delete Supplier Card
                                </h2>
                                <p id="deleteMessage" class="success-desc">
                                    Are you sure you want to delete?
                                </p>
                            </div>

                            <form action="{{ route('supplier#delete') }}" method="POST" id="supplierDeleteModalForm">
                                @csrf
                                <input type="hidden" name="delete_supplier_id" id="delete_supplier_id">
                                <div class="d-flex justify-content-center mt-3">
                                    <button type="submit" form="supplierDeleteModalForm" class="btn btn-danger px-4">Delete</button>
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
    <script src="{{ asset('script/supplier_list_script.js') }}"></script>
    <script></script>
@endsection
