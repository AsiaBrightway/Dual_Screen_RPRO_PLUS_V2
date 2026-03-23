@extends('layouts.admin.master')
@section('title', 'User')

@section('content')
    <section class="home-section">
        <div class="home-title">
            <i class='bx bx-menu'></i>
            <span class="text">User</span>
        </div>
        <div class="home-content">
            <div class="row pb-3 align-items-center" style="color:#512DA8; font-weight:bold">
                <div class="col-12 col-md-7">
                    <label>Add New User</label>
                </div>
                <div class="col-12 col-md-5 text-md-end text-start mt-3 mt-md-0" style="text-align: right">
                    <button class="btn btn-danger customBtn-clear" id="clear"><i class="fa-solid fa-eraser"
                            style="padding-right: 5px"></i>Clear</button>
                    <button type="submit" form="userCreateForm" class="btn btn-primary customBtn-save ms-1 mt-0"><i
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
            
            <div id="user_info_label" class="row align-items-center bg-white">
                <div class="col-10">
                    <label><i class="fa-solid fa-person-circle-plus" style="padding-left:5px; padding-right: 14px"></i>User
                        Info</label>
                </div>
                <div class="col-2" style="text-align: right">
                    <i class="bx bxs-chevron-down arrow"></i>
                </div>
            </div>
            <div class="user_info_container shadow-sm">
                <form action="{{ route('user#create') }}" method="POST" id="userCreateForm">
                    @csrf
                    <div class="row mb-3 justify-content-center">
                        <div class="col-3 user-info-label">
                            <label class="col-form-label">Employee Name <span style="color: red">*</span></label>
                        </div>
                        <div class="col-4 user-info-text">
                            <select class="form-select  @error('employee_name') is-invalid @enderror" id="employee_name"
                                name="employee_name">
                                <option value="0">Select-</option>
                                @if (count($employees) != 0)
                                    @foreach ($employees as $employee)
                                        <option value={{ $employee['employee_id'] }}>
                                            {{ $employee['employee_name'] }}</option>
                                    @endforeach
                                @endif
                            </select>
                            @error('employee_name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            <input type="text" id="employee_name_txt" name="employee_name_txt" hidden>
                        </div>
                    </div>
                    <div class="row mb-3 justify-content-center">
                        <div class="col-3 user-info-label">
                            <label class="col-form-label">Employee Code</label>
                        </div>
                        <div class="col-4 user-info-text">
                            <input class="form-control" type="text" id="employee_code" name="employee_code" disabled>
                        </div>
                    </div>
                    <div class="row mb-3 justify-content-center">
                        <div class="col-3 user-info-label">
                            <label class="col-form-label">User Role <span style="color: red">*</span></label>
                        </div>
                        <div class="col-4 user-info-text">
                            <select class="form-select  @error('user_role') is-invalid @enderror" id="user_role"
                                name="user_role">
                                <option value="0">Select-</option>
                                @if (count($userRoles) != 0)
                                    @foreach ($userRoles as $userRole)
                                        <option value={{ $userRole['user_role_id'] }}>
                                            {{ $userRole['user_role_name'] }}</option>
                                    @endforeach
                                @endif
                            </select>
                            @error('user_role')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3 justify-content-center">
                        <div class="col-3 user-info-label">
                            <label class="col-form-label">User Name <span style="color: red">*</span></label>
                        </div>
                        <div class="col-4 user-info-text">
                            <input class="form-control  @error('user_name') is-invalid @enderror" type="text"
                                id="user_name" name="user_name" value="{{ old('user_name') }}">
                            @error('user_name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3 justify-content-center">
                        <div class="col-3 user-info-label">
                            <label class="col-form-label">Password <span style="color: red">*</span></label>
                        </div>
                        <div class="col-4 user-info-text">
                            <input class="form-control  @error('password') is-invalid @enderror" type="password"
                                id="password" name="password">
                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3 justify-content-center">
                        <div class="col-3 user-info-label">
                            <label class="col-form-label">Confirm Password <span style="color: red">*</span></label>
                        </div>
                        <div class="col-4 user-info-text">
                            <input class="form-control  @error('confirm_password') is-invalid @enderror" type="password"
                                id="confirm_password" name="confirm_password">
                            @error('confirm_password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row align-items-center justify-content-center">
                        <div class="col-3 user-info-label">
                            <label class="col-form-label">Discontinued</label>
                        </div>
                        <div class="col-4 user-info-text">
                            <input class="form-check-input" type="checkbox" id="is_discontinued" name="is_discontinued">
                        </div>
                    </div>
                </form>
            </div>
            <div id="user_list_label" class="row align-items-center bg-white">
                <div class="col-10">
                    <label><i class="fa-solid fa-table-list" style="padding-left:5px; padding-right: 19px"></i>User
                        Lists</label>
                </div>
                <div class="col-2" style="text-align: right">
                    <i class="bx bxs-chevron-down arrow"></i>
                </div>
            </div>
            <div class="user_list_container shadow-sm ">
                <table id="user_list" class="table table-striped nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>User Name</th>
                            <th>Employee Name</th>
                            <th>User Role</th>
                            <th>Discontinued</th>
                            <th>Modified By</th>
                            <th>Created At</th>
                            <th>Updated At</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($users) != 0)
                            @php
                                $count = 1;
                            @endphp
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $count }}</td>
                                    <td>{{ $user['username'] }}</td>
                                    <td>{{ $user['employee_name'] }}</td>
                                    <td>{{ $user['user_role_name'] }}</td>
                                    @if ($user['user_is_discontinued'] == 0)
                                        <td><input class="form-check-input" type="checkbox" onclick="return false;"></td>
                                    @elseif ($user['user_is_discontinued'] == 1)
                                        <td><input class="form-check-input" type="checkbox" checked
                                                onclick="return false;">
                                        </td>
                                    @endif
                                    <td>{{ $user['user_modified_by'] }}</td>
                                    <td>{{ date('d-M-y (H:i A)', strtotime($user['user_created_at'])) }}</td>
                                    <td>{{ date('d-M-y (H:i A)', strtotime($user['user_updated_at'])) }}</td>
                                    <td><a data-user_id="{{ $user['id'] }}" data-user_name="{{ $user['username'] }}"
                                            data-employee_name="{{ $user['employee_name'] }}"
                                            data-employee_code="{{ $user['employee_code'] }}"
                                            data-user_role_id="{{ $user['user_role_id'] }}"
                                            data-is_discontinued="{{ $user['user_is_discontinued'] }}"
                                            data-bs-toggle="modal" data-bs-target="#edit_user_modal"
                                            class="edit_user_modal_dialog"><i class="fa-solid fa-pen"
                                                style="color: blue; cursor: pointer;"></i></a></td>
                                    <td><a data-user_id="{{ $user['id'] }}"
                                            data-employee_name="{{ $user['employee_name'] }}" data-bs-toggle="modal"
                                            data-bs-target="#delete_user_modal" class="delete_user_modal_dialog"><i
                                                class="fa-regular fa-trash-can"
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
                <!--Edit Table Modal -->
                <div class="modal fade" id="edit_user_modal" data-bs-backdrop="static" data-bs-keyboard="false"
                    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header text-center" style="background-color: #512DA8">
                                <h1 class="modal-title fs-5 w-100" id="staticBackdropLabel" style="color: white">Update
                                    User
                                </h1>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body" style="margin-left: 20px; margin-right:20px">
                                <form action="{{ route('user#update') }}" method="POST" id="userEditModalForm">
                                    @csrf
                                    <input type="text" name="edit_user_id" id="edit_user_id" hidden>
                                    <div class="row mb-3 mt-3">
                                        <div class="col-5">
                                            <label class="form-label">Employee Name</label>
                                        </div>
                                        <div class="col">
                                            <input class="form-control" type="text" name="edit_employee_name"
                                                id="edit_employee_name" disabled>
                                        </div>
                                    </div>
                                    <div class="row mb-3 mt-3">
                                        <div class="col-5">
                                            <label class="form-label">Employee Code</label>
                                        </div>
                                        <div class="col">
                                            <input class="form-control" type="text" name="edit_employee_code"
                                                id="edit_employee_code" disabled>
                                        </div>
                                    </div>
                                    <div class="row mb-3 mt-3">
                                        <div class="col-5">
                                            <label class="form-label">User Role</label>
                                        </div>
                                        <div class="col">
                                            <select class="form-select" id="edit_user_role" name="edit_user_role">
                                                @if (count($userRoles) != 0)
                                                    @foreach ($userRoles as $userRole)
                                                        <option value={{ $userRole['user_role_id'] }}>
                                                            {{ $userRole['user_role_name'] }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3 mt-3">
                                        <div class="col-5">
                                            <label class="form-label">User Name</label>
                                        </div>
                                        <div class="col">
                                            <input class="form-control" type="text" name="edit_user_name"
                                                id="edit_user_name" disabled>
                                        </div>
                                    </div>
                                    <div class="row mb-3 mt-3">
                                        <div class="col-5">
                                            <label class="form-label">Change Password <span
                                                    style="color: red">*</span></label>
                                        </div>
                                        <div class="col">
                                            <input class="form-control" type="password" name="edit_password"
                                                id="edit_password">
                                        </div>
                                    </div>
                                    <div class="row mb-3 mt-3">
                                        <div class="col-5">
                                            <label class="form-label">Confirm Password <span
                                                    style="color: red">*</span></label>
                                        </div>
                                        <div class="col">
                                            <input class="form-control" type="password" name="edit_confirm_password"
                                                id="edit_confirm_password">
                                        </div>
                                    </div>
                                    <div class="row align-items-center mb-3">
                                        <div class="col-5">
                                            <label class="form-label text-danger">Discontinued</label>
                                        </div>
                                        <div class="col">
                                            <input class="form-check-input" type="checkbox"
                                                name="edit_user_is_discontinued" id="edit_user_is_discontinued">
                                        </div>
                                    </div>
                                </form>

                            </div>
                            <div class="modal-footer" style="margin-right: 20px">
                                <input type="submit" class="btn custom_btn" value="Update" form="userEditModalForm">
                            </div>
                        </div>
                    </div>
                </div>
                <!--Delete Table Modal -->
                <div class="modal fade" id="delete_user_modal" data-bs-backdrop="static" data-bs-keyboard="false"
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
                                <form action="{{ route('user#delete') }}" method="POST" id="userDeleteModalForm">
                                    @csrf
                                    <input type="text" name="delete_user_id" id="delete_user_id" hidden>
                                    <div class="row align-items-center mb-3 mt-3">
                                        <div>
                                            <label class="form-label">Are you sure want to delete?</label>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer" style="margin-right: 20px">
                                <input type="submit" class="btn btn-danger" value="Delete" form="userDeleteModalForm">
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

                            <form action="{{ route('user#delete') }}" method="POST" id="userDeleteModalForm">
                                @csrf
                                <input type="text" name="delete_user_id" id="delete_user_id" hidden>
                                <div class="d-flex justify-content-center mt-3">
                                    <button type="submit" form="userDeleteModalForm" class="btn btn-danger px-4">Delete</button>
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
    <script src="{{ asset('script/user_script.js') }}"></script>

@endsection
