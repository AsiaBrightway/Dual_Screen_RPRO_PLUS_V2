@extends('layouts.admin.master')
@section('title', 'Employee')

@section('content')
    <section class="home-section">
        <div class="home-title">
            <i class='bx bx-menu'></i>
            <span class="text">Employee</span>
        </div>
        <div class="home-content">
            <div class="row pb-3 align-items-center" style="color:#512DA8; font-weight:bold">
                <div class="col-12 col-md-7">
                    <label>Add New Employee</label>
                </div>
                <div class="col-12 col-md-5 text-md-end text-start mt-3 mt-md-0" style="text-align: right">
                    <button class="btn btn-danger customBtn-clear" id="clear"><i class="fa-solid fa-eraser"
                            style="padding-right: 5px"></i>Clear</button>
                    <button type="submit" form="employeeCreateForm" class="btn btn-primary customBtn-save ms-1 mt-0"><i
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
            
            <div id="employee_info_label" class="row align-items-center bg-white">
                <div class="col-10">
                    <label><i class="fa-solid fa-user-pen" style="padding-left:5px; padding-right: 18px"></i>Employee
                        Info</label>
                </div>
                <div class="col-2" style="text-align: right">
                    <i class="bx bxs-chevron-down arrow"></i>
                </div>
            </div>
            <div class="employee_info_container shadow-sm">
                <form action="{{ route('employee#create') }}" method="POST" id="employeeCreateForm">
                    @csrf
                    <div class="row mb-3 justify-content-center">
                        <div class="col-3 employee-info-label">
                            <label class="col-form-label">Employee Name <span style="color: red">*</span></label>
                        </div>
                        <div class="col-4 employee-info-text">
                            <input class="form-control @error('employee_name') is-invalid @enderror" type="text"
                                id="employee_name" name="employee_name" value="{{ old('employee_name') }}">
                            @error('employee_name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row align-items-center mb-3 justify-content-center">
                        <div class="col-3 employee-info-label">
                            <label class="col-form-label">Other Name</label>
                        </div>
                        <div class="col-4 employee-info-text">
                            <input class="form-control" type="text" id="other_name" name="other_name"
                                value="{{ old('other_name') }}">
                        </div>
                    </div>
                    <div class="row mb-3 justify-content-center">
                        <div class="col-3 employee-info-label">
                            <label class="col-form-label">Employee Code <span style="color: red">*</span></label>
                        </div>
                        <div class="col-4 employee-info-text">
                            <input class="form-control @error('employee_code') is-invalid @enderror" type="text"
                                id="employee_code" name="employee_code">
                            @error('employee_code')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3 justify-content-center">
                        <div class="col-3 employee-info-label">
                            <label class="col-form-label">Employee Position <span style="color: red">*</span></label>
                        </div>
                        <div class="col-4 employee-info-text">
                            <select class="form-select  @error('employee_position') is-invalid @enderror"
                                id="employee_position" name="employee_position">
                                <option value="0">Select-</option>
                                @if (count($employeePositions) != 0)
                                    @foreach ($employeePositions as $employeePosition)
                                        <option value={{ $employeePosition['employee_position_id'] }}>
                                            {{ $employeePosition['position_name'] }}</option>
                                    @endforeach
                                @endif
                            </select>
                            @error('employee_position')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row align-items-center justify-content-center">
                        <div class="col-3 employee-info-label">
                            <label class="col-form-label">Terminate</label>
                        </div>
                        <div class="col-4 employee-info-text">
                            <input class="form-check-input" type="checkbox" id="is_terminate" name="is_terminate">
                        </div>
                    </div>
                </form>
            </div>
            <div id="employee_list_label" class="row align-items-center bg-white">
                <div class="col-10">
                    <label><i class="fa-solid fa-table-list" style="padding-left:5px; padding-right: 19px"></i>Employee
                        Lists</label>
                </div>
                <div class="col-2" style="text-align: right">
                    <i class="bx bxs-chevron-down arrow"></i>
                </div>
            </div>
            <div class="employee_list_container shadow-sm ">
                <table id="employee_list" class="table table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Employee Name</th>
                            <th>Other Name</th>
                            <th>Employee Code</th>
                            <th>Employee Position</th>
                            <th>Terminate</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($employees) != 0)
                            @php
                                $count = 1;
                            @endphp
                            @foreach ($employees as $employee)
                                <tr>
                                    <td>{{ $count }}</td>
                                    <td>{{ $employee['employee_name'] }}</td>
                                    <td>{{ $employee['employee_other_name'] }}</td>
                                    <td>{{ $employee['employee_code'] }}</td>
                                    <td>{{ $employee['position_name'] }}</td>
                                    @if ($employee['is_terminate'] == 0)
                                        <td><input class="form-check-input" type="checkbox" onclick="return false;"></td>
                                    @elseif ($employee['is_terminate'] == 1)
                                        <td><input class="form-check-input" type="checkbox" checked onclick="return false;">
                                        </td>
                                    @endif
                                    <td><a data-employee_id="{{ $employee['employee_id'] }}"
                                            data-employee_name="{{ $employee['employee_name'] }}"
                                            data-other_name="{{ $employee['employee_other_name'] }}"
                                            data-employee_code="{{ $employee['employee_code'] }}"
                                            data-employee_position_id="{{ $employee['employee_position_id'] }}"
                                            data-is_terminate="{{ $employee['is_terminate'] }}" data-bs-toggle="modal"
                                            data-bs-target="#edit_employee_modal" class="edit_employee_modal_dialog"><i
                                                class="fa-solid fa-pen" style="color: blue; cursor: pointer;"></i></a>
                                    </td>
                                    <td><a data-employee_id="{{ $employee['employee_id'] }}"
                                            data-employee_name="{{ $employee['employee_name'] }}" data-bs-toggle="modal"
                                            data-bs-target="#delete_employee_modal"
                                            class="delete_employee_modal_dialog"><i class="fa-regular fa-trash-can"
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
                <!--Edit Employee Modal -->
                <div class="modal fade" id="edit_employee_modal" data-bs-backdrop="static" data-bs-keyboard="false"
                    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header text-center" style="background-color: #512DA8">
                                <h1 class="modal-title fs-5 w-100" id="staticBackdropLabel" style="color: white">Update
                                    Employee
                                </h1>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body" style="margin-left: 20px; margin-right:20px">
                                <form action="{{ route('employee#update') }}" method="POST" id="employeeEditModalForm">
                                    @csrf
                                    <input type="text" name="edit_employee_id" id="edit_employee_id" hidden>
                                    <div class="row mb-3 mt-3">
                                        <div class="col-5">
                                            <label class="form-label">Employee Name <span
                                                    style="color: red">*</span></label>
                                        </div>
                                        <div class="col">
                                            <input class="form-control" type="text" name="edit_employee_name"
                                                id="edit_employee_name">
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
                                            <label class="form-label">Employee Code <span
                                                    style="color: red">*</span></label>
                                        </div>
                                        <div class="col">
                                            <input class="form-control muted" type="text" name="edit_employee_code"
                                                id="edit_employee_code" readonly>
                                        </div>
                                    </div>
                                    <div class="row mb-3 mt-3">
                                        <div class="col-5">
                                            <label class="form-label">Employee Position <span
                                                    style="color: red">*</span></label>
                                        </div>
                                        <div class="col">
                                            <select class="form-select" id="edit_employee_position"
                                                name="edit_employee_position">
                                                @if (count($employeePositions) != 0)
                                                    @foreach ($employeePositions as $employeePosition)
                                                        <option value={{ $employeePosition['employee_position_id'] }}>
                                                            {{ $employeePosition['position_name'] }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row align-items-center mb-3">
                                        <div class="col-5">
                                            <label class="form-label text-danger">Terminate</label>
                                        </div>
                                        <div class="col">
                                            <input class="form-check-input" type="checkbox" name="edit_is_terminate"
                                                id="edit_is_terminate">
                                        </div>
                                    </div>
                                </form>

                            </div>
                            <div class="modal-footer" style="margin-right: 20px">
                                <input type="submit" class="btn custom_btn" value="Update"
                                    form="employeeEditModalForm">
                            </div>
                        </div>
                    </div>
                </div>
                <!--Delete Employee Modal -->
                <div class="modal fade" id="delete_employee_modal" data-bs-backdrop="static" data-bs-keyboard="false"
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
                                <form action="{{ route('employee#delete') }}" method="POST"
                                    id="employeeDeleteModalForm">
                                    @csrf
                                    <input type="text" name="delete_employee_id" id="delete_employee_id" hidden>
                                    <div class="row align-items-center mb-3 mt-3">
                                        <div>
                                            <label class="form-label">Are you sure want to delete?</label>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer" style="margin-right: 20px">
                                <input type="submit" class="btn btn-danger" value="Delete"
                                    form="employeeDeleteModalForm">
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
                                    Delete Member Card
                                </h2>
                                <p id="deleteMessage" class="success-desc">
                                    Are you sure you want to delete?
                                </p>
                            </div>

                            <form action="{{ route('employee#delete') }}" method="POST"
                                    id="employeeDeleteModalForm">
                                @csrf
                                <input type="text" name="delete_employee_id" id="delete_employee_id" hidden>
                                <div class="d-flex justify-content-center mt-3">
                                    <button type="submit" form="employeeDeleteModalForm" class="btn btn-danger px-4">Delete</button>
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
    <script src="{{ asset('script/employee_script.js') }}"></script>

@endsection
