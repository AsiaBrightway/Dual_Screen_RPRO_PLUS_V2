@extends('layouts.admin.master')
@section('title', 'Floor')

@section('content')
    <section class="home-section">
        <div class="home-title">
            <i class='bx bx-menu'></i>
            <span class="text">Floor</span>
        </div>
        <div class="home-content">
            <div class="row pb-3 align-items-center" style="color:#512DA8; font-weight:bold">
                <div class="col-12 col-md-7">
                    <label>Add New Floor</label>
                </div>
                <div class="col-12 col-md-5 text-md-end text-start mt-3 mt-md-0">
                    <button class="btn btn-danger customBtn-clear" id="clear"><i class="fa-solid fa-eraser"
                            style="padding-right: 5px"></i>Clear</button>
                    <button type="submit" form="floorFormCreate" class="btn btn-primary customBtn-save ms-1 mt-0"><i
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

            <div id="floor_info_label" class="row align-items-center bg-white">
                <div class="col-10">
                    <label><i class="fa-solid fa-layer-group" style="padding-left:5px; padding-right: 18px"></i>Floor
                        Info</label>
                </div>
                <div class="col-2" style="text-align: right">
                    <i class="bx bxs-chevron-down arrow"></i>
                </div>
            </div>
            <div class="floor_info_container shadow-sm">
                <form action="{{ route('floor#create') }}" method="POST" id="floorFormCreate">
                    @csrf
                    <div class="row mb-3 justify-content-center">
                        <div class="col-2 floor-label">
                            <label class="col-form-label">Floor Name <span style="color: red">*</span></label>
                        </div>
                        <div class="col-3 floor-text">
                            <input class="form-control @error('floor_name') is-invalid @enderror" type="text"
                                id="floor_name" name="floor_name" value="{{ old('floor_name') }}">
                            @error('floor_name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3 justify-content-center">
                        <div class="col-2 floor-label">
                            <label class="col-form-label">Other Name</label>
                        </div>
                        <div class="col-3 floor-text">
                            <input class="form-control" type="text" id="other_name" name="other_name"
                                value="{{ old('other_name') }}">
                        </div>
                    </div>
                    <div class="row mb-3 justify-content-center">
                        <div class="col-2 floor-label">
                            <label class="col-form-label">Floor Code <span style="color: red">*</span></label>
                        </div>
                        <div class="col-3 floor-text">
                            <input class="form-control @error('floor_code') is-invalid @enderror" type="text"
                                id="floor_code" name="floor_code" value="{{ old('floor_code') }}">
                            @error('floor_code')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-2 floor-label">
                            <label class="col-form-label">Discontinued</label>
                        </div>
                        <div class="col-3 floor-text">
                            <input class="form-check-input" type="checkbox" name="is_discontinued" id="is_discontinued">
                        </div>
                    </div>
                </form>
            </div>
            <div id="floor_list_label" class="row align-items-center bg-white">
                <div class="col-10">
                    <label><i class="fa-solid fa-table-list" style="padding-left:5px; padding-right: 19px"></i>Floor
                        Lists</label>
                </div>
                <div class="col-2" style="text-align: right">
                    <i class="bx bxs-chevron-down arrow"></i>
                </div>
            </div>
            <div class="floor_list_container shadow-sm ">
                <table id="floor_list" class="table table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Floor Name</th>
                            <th>Other Name</th>
                            <th>Floor Code</th>
                            <th>Discontinued</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($floors) != 0)
                            @php
                                $count = 1;
                            @endphp
                            @foreach ($floors as $floor)
                                <tr>
                                    <td>{{ $count }}</td>
                                    <td>{{ $floor['floor_name'] }}</td>
                                    <td>{{ $floor['other_name'] }}</td>
                                    <td>{{ $floor['floor_code'] }}</td>
                                    @if ($floor['is_discontinued'] == 0)
                                        <td><input class="form-check-input" type="checkbox" onclick="return false;"></td>
                                    @elseif ($floor['is_discontinued'] == 1)
                                        <td><input class="form-check-input" type="checkbox" checked onclick="return false;">
                                        </td>
                                    @endif
                                    <td><a data-floor_id="{{ $floor['floor_id'] }}"
                                            data-floor_name="{{ $floor['floor_name'] }}"
                                            data-other_name="{{ $floor['other_name'] }}"
                                            data-floor_code="{{ $floor['floor_code'] }}"
                                            data-is_discontinued="{{ $floor['is_discontinued'] }}" data-bs-toggle="modal"
                                            data-bs-target="#edit_floor_modal" class="edit_floor_modal_dialog"><i
                                                class="fa-solid fa-pen" style="color: blue; cursor: pointer;"></i></a></td>
                                    <td><a data-floor_id="{{ $floor['floor_id'] }}"
                                            data-floor_name="{{ $floor['floor_name'] }}" data-bs-toggle="modal"
                                            data-bs-target="#delete_floor_modal" class="delete_floor_modal_dialog"><i
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
                <!--Edit Floor Modal -->
                <div class="modal fade" id="edit_floor_modal" data-bs-backdrop="static" data-bs-keyboard="false"
                    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header text-center" style="background-color: #512DA8">
                                <h1 class="modal-title fs-5 w-100" id="staticBackdropLabel" style="color: white">Update
                                    Floor
                                </h1>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body" style="margin-left: 20px; margin-right:20px">
                                <form action="{{ route('floor#update') }}" method="POST" id="floorEditModalForm">
                                    @csrf
                                    <input type="text" name="edit_floor_id" id="edit_floor_id" hidden>
                                    <div class="row mb-3 mt-3">
                                        <div class="col-5">
                                            <label class="form-label">Floor Name <span style="color: red">*</span></label>
                                        </div>
                                        <div class="col">
                                            <input class="form-control" type="text" name="edit_floor_name"
                                                id="edit_floor_name">
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
                                            <label class="form-label">Floor Code <span style="color: red">*</span></label>
                                        </div>
                                        <div class="col">
                                            <input class="form-control" type="text" name="edit_floor_code"
                                                id="edit_floor_code">
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
                                <input type="submit" class="btn custom_btn" value="Update" form="floorEditModalForm">
                            </div>
                        </div>
                    </div>
                </div>
                <!--Delete Floor Modal -->
                <div class="modal fade" id="delete_floor_modal" data-bs-backdrop="static" data-bs-keyboard="false"
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
                                <form action="{{ route('floor#delete') }}" method="POST" id="floorDeleteModalForm">
                                    @csrf
                                    <input type="text" name="delete_floor_id" id="delete_floor_id" hidden>
                                    <div class="row align-items-center mb-3 mt-3">
                                        <div>
                                            <label class="form-label">Are you sure want to delete?</label>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer" style="margin-right: 20px">
                                <input type="submit" class="btn btn-danger" value="Delete" form="floorDeleteModalForm">
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

                            <form action="{{ route('floor#delete') }}" method="POST" id="floorDeleteModalForm">
                                @csrf
                                <input type="text" name="delete_floor_id" id="delete_floor_id" hidden>
                                <div class="d-flex justify-content-center mt-3">
                                    <button type="submit" form="floorDeleteModalForm" class="btn btn-danger px-4">Delete</button>
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
    <script src="{{ asset('script/floor_script.js') }}"></script>

@endsection
