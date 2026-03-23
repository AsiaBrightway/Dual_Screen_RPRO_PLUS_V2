@extends('layouts.admin.master')
@section('title', 'Unit')

@section('content')
    <section class="home-section">
        <div class="home-title">
            <i class='bx bx-menu'></i>
            <span class="text">Unit</span>
        </div>
        <div class="home-content">
            <div class="row pb-3 align-items-center" style="color:#512DA8; font-weight:bold">
                <div class="col-12 col-md-7">
                    <label>Add New Unit</label>
                </div>
                <div class="col-12 col-md-5 text-md-end text-start mt-3 mt-md-0">
                    <button class="btn btn-danger customBtn-clear" id="clear"><i class="fa-solid fa-eraser"
                            style="padding-right: 5px"></i>Clear</button>
                    <button type="submit" form="unitCreateForm" class="btn btn-primary customBtn-save ms-1 mt-0"><i
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
            
            <div id="unit_info_label" class="row align-items-center bg-white">
                <div class="col-10">
                    <label><i class="fa-brands fa-unity" style="padding-left:5px; padding-right: 18px"></i>Unit
                        Info</label>
                </div>
                <div class="col-2" style="text-align: right">
                    <i class="bx bxs-chevron-down arrow"></i>
                </div>
            </div>
            <div class="unit_info_container shadow-sm">
                <form action="{{ route('unit#create') }}" method="POST" id="unitCreateForm">
                    @csrf
                    <div class="row mb-3 justify-content-center">
                        <div class="col-2 unit-info-label">
                            <label class="col-form-label">Unit Name <span style="color: red">*</span></label>
                        </div>
                        <div class="col-3 unit-info-text">
                            <input class="form-control @error('unit_name') is-invalid @enderror" type="text"
                                id="unit_name" name="unit_name" value="{{ old('unit_name') }}">
                            @error('unit_name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3 justify-content-center">
                        <div class="col-2 unit-info-label">
                            <label class="col-form-label">Other Name <span style="color: red">*</span></label>
                        </div>
                        <div class="col-3 unit-info-text">
                            <input class="form-control @error('other_name') is-invalid @enderror" type="text"
                                id="other_name" name="other_name" value="{{ old('other_name') }}">
                            @error('other_name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-2 unit-info-label">
                            <label class="col-form-label">Discontinued</label>
                        </div>
                        <div class="col-3 unit-info-text">
                            <input class="form-check-input" type="checkbox" name="is_discontinued" id="is_discontinued">
                        </div>
                    </div>
                </form>
            </div>
            <div id="unit_list_label" class="row align-items-center bg-white">
                <div class="col-10">
                    <label><i class="fa-solid fa-table-list" style="padding-left:5px; padding-right: 19px"></i>Unit
                        Lists</label>
                </div>
                <div class="col-2" style="text-align: right">
                    <i class="bx bxs-chevron-down arrow"></i>
                </div>
            </div>
            <div class="unit_list_container shadow-sm ">
                <table id="unit_list" class="table table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Unit Name</th>
                            <th>Other Name</th>
                            <th>Discontinued</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($units) != 0)
                            @php
                                $count = 1;
                            @endphp
                            @foreach ($units as $unit)
                                <tr>
                                    <td>{{ $count }}</td>
                                    <td>{{ $unit->unit_name }}</td>
                                    <td>{{ $unit->other_name }}</td>
                                    @if ($unit->is_discontinued == 0)
                                        <td><input class="form-check-input" type="checkbox" onclick="return false;"></td>
                                    @elseif ($unit->is_discontinued == 1)
                                        <td><input class="form-check-input" type="checkbox" checked onclick="return false;">
                                        </td>
                                    @endif
                                    <td><a data-unit_id="{{ $unit->unit_id }}" data-unit_name="{{ $unit->unit_name }}"
                                            data-other_name="{{ $unit->other_name }}"
                                            data-is_discontinued="{{ $unit->is_discontinued }}" data-bs-toggle="modal"
                                            data-bs-target="#edit_unit_modal" class="edit_unit_modal_dialog"><i
                                                class="fa-solid fa-pen" style="color: blue; cursor: pointer;"></i></a></td>
                                    <td><a data-unit_id="{{ $unit->unit_id }}" data-unit_name="{{ $unit->unit_name }}"
                                            data-bs-toggle="modal" data-bs-target="#delete_unit_modal"
                                            data-has-items="{{ $unit->menu_item_count > 0 ? 1 : 0 }}"
                                            class="delete_unit_modal_dialog"><i class="fa-regular fa-trash-can"
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
                <!--Edit Bar Modal -->
                <div class="modal fade" id="edit_unit_modal" data-bs-backdrop="static" data-bs-keyboard="false"
                    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header text-center" style="background-color: #512DA8">
                                <h1 class="modal-title fs-5 w-100" id="staticBackdropLabel" style="color: white">Update
                                    Unit
                                </h1>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body" style="margin-left: 20px; margin-right:20px">
                                <form action="{{ route('unit#update') }}" method="POST" id="unitEditModalForm">
                                    @csrf
                                    <input type="text" name="edit_unit_id" id="edit_unit_id" hidden>
                                    <div class="row mb-3 mt-3">
                                        <div class="col-5">
                                            <label class="form-label">Unit Name <span style="color: red">*</span></label>
                                        </div>
                                        <div class="col">
                                            <input class="form-control" type="text" name="edit_unit_name"
                                                id="edit_unit_name">
                                        </div>
                                    </div>
                                    <div class="row mb-3 mt-3">
                                        <div class="col-5">
                                            <label class="form-label">Other Name <span style="color: red">*</span></label>
                                        </div>
                                        <div class="col">
                                            <input class="form-control" type="text" name="edit_other_name"
                                                id="edit_other_name">
                                        </div>
                                    </div>
                                    <div class="row align-items-center mb-3">
                                        <div class="col-5">
                                            <label class="form-label text-danger">Discontinued</label>
                                        </div>
                                        <div class="col">
                                            <input class="form-check-input" type="checkbox" name="edit_is_discontinued"
                                                id="edit_is_discontinued">
                                        </div>
                                    </div>
                                </form>

                            </div>
                            <div class="modal-footer" style="margin-right: 20px">
                                <input type="submit" class="btn custom_btn" value="Update" form="unitEditModalForm">
                            </div>
                        </div>
                    </div>
                </div>
                <!--Delete Bar Modal -->
                <div class="modal fade" id="delete_unit_modal" data-bs-backdrop="static" data-bs-keyboard="false"
                    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
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
                            
                            <div class="text-content">
                                <h2 id="delete_modal_header" class="success-title">
                                    Delete Unit
                                </h2>
                                <p id="deleteMessage" class="success-desc">
                                    Are you sure you want to delete?
                                </p>
                            </div>

                            <form action="{{ route('unit#delete') }}" method="POST" id="unitFormDelete">
                                @csrf
                                <input type="text" name="delete_unit_id" id="delete_unit_id" hidden>
                                <div class="d-flex justify-content-center mt-3">
                                    <button type="submit" id="confirmFormDelete" form="unitFormDelete" class="btn btn-danger px-4">Delete</button>
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
    <script src="{{ asset('script/unit_script.js') }}"></script>

@endsection
