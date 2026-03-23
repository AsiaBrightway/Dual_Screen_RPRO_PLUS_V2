@extends('layouts.admin.master')
@section('title', 'Category')

@section('content')
    <style>
        /* Whole accordion item rounded */
        .accordion-item {
            border-radius: 10px !important;
            overflow: hidden;
            border: none !important;
        }

        /* Button when collapsed */
        .accordion-button.collapsed {
            border-radius: 10px !important;
        }

        /* Button when expanded */
        .accordion-button:not(.collapsed) {
            border-top-left-radius: 10px !important;
            border-top-right-radius: 10px !important;
        }

        /* Optional – background & shadow like your screenshot */
        .accordion-button {
            background-color: #f6f8ff !important;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
    </style>
    <section class="home-section">
        <div class="home-title">
            <i class='bx bx-menu'></i>
            <span class="text">Category</span>
        </div>
        <div class="home-content">
            <div class="row pb-3 align-items-center" style="color:#512DA8; font-weight:bold">
                <div class="col-7">
                    <button class="btn custom_btn" data-bs-toggle="modal" data-bs-target="#create_main_category_modal">
                        Create Main Category
                    </button>

                </div>
                <div class="col" style="text-align: right">
                    {{-- <button class="btn btn-danger"><i class="fa-solid fa-eraser"
                            style="padding-right: 5px"></i>Clear</button>
                    <button class="btn btn-primary"><i class="fa-regular fa-floppy-disk"
                            style="padding-right: 5px"></i>Save</button> --}}
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

            @if ($errors->any())
                <div id="flash-message" class="alert alert-warning alert-dismissible d-flex align-items-center fade show">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <strong class="mx-2">Warning!</strong>
                    @foreach ($errors->all() as $error)
                        {{ $error }}
                    @endforeach
                </div>
            @endif

            {{-- main category loop start --}}
            <div class="accordion" id="categoryAccordion">
                @foreach ($mainCategories as $main)
                    <div class="accordion-item mb-2">
                        <h2 class="accordion-header" id="heading{{ $main->main_category_id }}">
                            <button class="accordion-button collapsed d-flex align-items-center" type="button"
                                data-bs-toggle="collapse" data-bs-target="#collapse{{ $main->main_category_id }}"
                                aria-expanded="false" aria-controls="collapse{{ $main->main_category_id }}">

                                <i class="fa-solid fa-kitchen-set me-2"></i>
                                <div class="d-flex flex-column">
                                    <span>
                                        {{ $main->main_category_name }}
                                    </span>
                                    @if ($main->is_discontinued == 1)
                                        <span class="text-danger fw-bold"
                                            style="font-size: 11px; line-height: 1; margin-top:2px;">
                                            (Discontinued)
                                        </span>
                                    @endif
                                </div>
                            </button>
                        </h2>

                        <div id="collapse{{ $main->main_category_id }}" class="accordion-collapse collapse"
                            data-bs-parent="#categoryAccordion">

                            <div class="accordion-body">

                                <table class="table table-striped data-table" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Image</th>
                                            <th>Category Name</th>
                                            <th>Discontinued</th>
                                            <th>Edit</th>
                                            <th>Delete</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @php $count = 1; @endphp

                                        @foreach ($menuCategories[$main->main_category_id] ?? [] as $row)
                                            <tr>
                                                <td>{{ $count++ }}</td>

                                                <td>
                                                    @if ($row->menu_category_image == null)
                                                        <img src="{{ asset('404_image.png') }}" width="60"
                                                            height="60">
                                                    @else
                                                        <img src="{{ asset('storage/Images/' . $row->menu_category_image) }}"
                                                            width="60" height="60" class="img-thumbnail shadow-sm">
                                                    @endif
                                                </td>

                                                <td>{{ $row->menu_category_name }}</td>

                                                <td>
                                                    <input type="checkbox" {{ $row->is_discontinued ? 'checked' : '' }}
                                                        onclick="return false;">
                                                </td>

                                                <td>
                                                    <i class="fa-solid fa-pen text-primary editCategoryBtn"
                                                        style="cursor:pointer;" data-id="{{ $row->category_id }}"
                                                        data-name="{{ $row->menu_category_name }}"
                                                        data-discontinued="{{ $row->is_discontinued }}"
                                                        data-main="{{ $main->main_category_name }}">
                                                    </i>

                                                </td>

                                                <td>
                                                    <i class="fa-regular fa-trash-can text-danger deleteCategoryBtn"
                                                        style="cursor:pointer;" data-id="{{ $row->category_id }}"
                                                        data-name="{{ $row->menu_category_name }}"
                                                        data-main="{{ $main->main_category_name }}"
                                                        data-has-items="{{ $row->menu_item_count > 0 ? 1 : 0 }}">
                                                    </i>
                                                </td>


                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-between mt-3 align-items-center">
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-primary editMainCategoryBtn"
                                            data-id="{{ $main->main_category_id }}"
                                            data-name="{{ $main->main_category_name }}"
                                            data-discontinued="{{ $main->is_discontinued }}" data-bs-toggle="modal"
                                            data-bs-target="#mainCategoryEditModal">
                                            Edit Main Category
                                        </button>

                                        @if (count($menuCategories[$main->main_category_id] ?? []) == 0)
                                            <button class="btn btn-danger deleteMainCategoryBtn"
                                                data-id="{{ $main->main_category_id }}"
                                                data-name="{{ $main->main_category_name }}" data-bs-toggle="modal"
                                                data-bs-target="#mainCategoryDeleteModal">
                                                Delete Main Category
                                            </button>
                                        @else
                                            <button class="btn btn-danger" disabled>
                                                Delete Main Category
                                            </button>
                                        @endif
                                    </div>

                                    <button class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#newCategoryModal{{ $main->main_category_id }}">
                                        New Category
                                    </button>

                                </div>

                            </div>
                        </div>

                        <!-- DELETE CATEGORY MODAL -->
                        <div class="modal fade" id="deleteCategoryModal" data-bs-backdrop="static" data-bs-keyboard="false"
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
                                        <h2 id="deleteModalTitle" class="success-title">
                                            Delete Category
                                        </h2>
                                        <p id="deleteMessage" class="success-desc"></p>
                                    </div>

                                    <form action="{{ route('menuCategory#delete') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="delete_category_id" id="delete_category_id">

                                        <div class="d-flex justify-content-center gap-2 mt-3">
                                            {{-- DELETE --}}
                                            <button type="submit" id="confirmDeleteBtn" class="btn btn-danger px-4">
                                                Delete
                                            </button>

                                            {{-- CANCEL --}}
                                            {{-- <button type="button" id="cancelDeleteBtn" class="btn btn-secondary px-4"
                                                data-bs-dismiss="modal">
                                                Cancel
                                            </button> --}}
                                        </div>
                                    </form>


                                </div>
                            </div>
                        </div>


                        <!-- EDIT MENU CATEGORY MODAL -->
                        <div class="modal fade" id="editCategoryModal" data-bs-backdrop="static"
                            data-bs-keyboard="false" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">

                                    <div class="modal-header text-center" style="background-color:#512DA8;">
                                        <h1 class="modal-title fs-5 w-100 text-white" id="editModalTitle">Update
                                            Category</h1>
                                        <button type="button" class="btn-close btn-close-white"
                                            data-bs-dismiss="modal"></button>
                                    </div>

                                    <form id="editMenuCategoryForm" action="{{ route('menuCategory#update') }}"
                                        method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="edit_category_id" id="edit_category_id">

                                        <div class="modal-body" style="margin-left:20px;margin-right:20px">

                                            <!-- CATEGORY NAME -->
                                            <div class="row mb-3 mt-3">
                                                <div class="col-5">
                                                    <label class="form-label">Category Name <span
                                                            class="text-danger">*</span></label>
                                                </div>
                                                <div class="col">
                                                    <input type="text" name="edit_category_name"
                                                        id="edit_category_name" class="form-control">
                                                    <div class="invalid-feedback d-block" id="editCategoryNameError">
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- CATEGORY IMAGE -->
                                            <div class="row mb-3 mt-3">
                                                <div class="col-5">
                                                    <label class="form-label">Category Image</label>
                                                </div>
                                                <div class="col">
                                                    <input type="file" name="edit_menu_category_image"
                                                        class="form-control form-control-sm">
                                                </div>
                                            </div>

                                            <!-- DISCONTINUED -->
                                            <div class="row mb-3">
                                                <div class="col-5">
                                                    <label class="form-label text-danger">Discontinued</label>
                                                </div>
                                                <div class="col">
                                                    <input type="checkbox" id="edit_is_discontinued"
                                                        name="is_discontinued" class="form-check-input">
                                                </div>
                                            </div>

                                        </div>

                                        <div class="modal-footer" style="margin-right:20px;">
                                            <input type="submit" class="btn custom_btn" value="Update">
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>

                        <!-- CREATE MENU CATEGORY MODAL -->
                        <div class="modal fade" id="newCategoryModal{{ $main->main_category_id }}"
                            data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                            aria-labelledby="staticBackdropLabel" aria-hidden="true">

                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">

                                    <!-- HEADER -->
                                    <div class="modal-header text-center" style="background-color: #512DA8">
                                        <h1 class="modal-title fs-5 w-100 text-white">
                                            {{ $main->main_category_name }}
                                        </h1>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>

                                    <!-- CREATE FORM -->
                                    <form class="createMenuCategoryForm" action="{{ route('menuCategory#create') }}"
                                        method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="main_category_id"
                                            value="{{ $main->main_category_id }}">

                                        <div class="modal-body" style="margin-left:20px;margin-right:20px">

                                            <!-- CATEGORY NAME -->
                                            <div class="row mb-3 mt-3">
                                                <div class="col-5">
                                                    <label class="form-label">Category Name <span
                                                            class="text-danger">*</span></label>
                                                </div>
                                                <div class="col">
                                                    <input type="text" name="menu_category_name" class="form-control">
                                                    <div class="invalid-feedback d-block menuCategoryError"></div>
                                                </div>
                                            </div>

                                            <!-- CATEGORY IMAGE -->
                                            <div class="row mb-3 mt-3">
                                                <div class="col-5">
                                                    <label class="form-label">Category Image</label>
                                                </div>
                                                <div class="col">
                                                    <input type="file" name="menu_category_image"
                                                        class="form-control form-control-sm">
                                                </div>
                                            </div>

                                            <!-- DISCONTINUED -->
                                            <div class="row mb-3 align-items-center">
                                                <div class="col-5">
                                                    <label class="form-label text-danger">Discontinued</label>
                                                </div>
                                                <div class="col">
                                                    <input type="checkbox" name="is_discontinued"
                                                        class="form-check-input">
                                                </div>
                                            </div>

                                        </div>

                                        <div class="modal-footer" style="margin-right:20px;">
                                            <input type="submit" class="btn custom_btn" value="Create">
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                        <!-- EDIT MAIN CATEGORY MODAL -->
                        <div class="modal fade" id="mainCategoryEditModal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">

                                    <div class="modal-header" style="background:#512DA8; color:white;">
                                        <h5 class="modal-title" id="mainCategoryEditTitle">Update Category</h5>
                                        <button type="button" class="btn-close btn-close-white"
                                            data-bs-dismiss="modal"></button>
                                    </div>

                                    <!-- AJAX UPDATE FORM -->
                                    <form id="editMainCategoryForm" action="{{ route('mainCategory#update') }}"
                                        method="POST">
                                        @csrf
                                        <input type="hidden" name="edit_main_category_id" id="edit_main_category_id">

                                        <div class="modal-body" style="margin-left:20px;margin-right:20px">

                                            <!-- CATEGORY NAME (Same design as CREATE form) -->
                                            <div class="row mb-3 mt-3">
                                                <div class="col-5">
                                                    <label class="form-label">Main Category Name <span
                                                            class="text-danger">*</span></label>
                                                </div>
                                                <div class="col">
                                                    <input type="text" name="main_category_name"
                                                        id="edit_main_category_name" class="form-control mb-3">

                                                    <!-- AJAX error -->
                                                    <div class="invalid-feedback d-block" id="editNameError"></div>
                                                </div>
                                            </div>
                                            <!-- DISCONTINUED -->
                                            <div class="row mb-3 align-items-center">
                                                <div class="col-5">
                                                    <label class="form-label text-danger">Discontinued</label>
                                                </div>
                                                <div class="col">
                                                    <input type="checkbox" name="is_discontinued"
                                                        id="edit_main_is_discontinued" class="form-check-input">
                                                </div>
                                            </div>
                                        </div>


                                        <div class="modal-footer">
                                            <button type="submit" class="btn custom_btn">Update</button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>



                        <!-- DELETE MAIN CATEGORY MODAL -->
                        <div class="modal fade" id="mainCategoryDeleteModal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                {{-- <div class="modal-content">

                                    <div class="modal-header text-white" style="background:#512DA8;">
                                        <h5 class="modal-title" id="deleteMainCategoryTitle">Delete Main Category
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white"
                                            data-bs-dismiss="modal"></button>
                                    </div>

                                    <form action="{{ route('mainCategory#delete') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="delete_main_category_id"
                                            id="delete_main_category_id">

                                        <div class="modal-body">
                                            <p id="deleteMainCategoryMessage" class="text-dark fw-bold"></p>
                                            <p class="text-danger">Warning: This action cannot be undone!</p>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </div>

                                    </form>

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
                                        <h2 id="deleteMainCategoryTitle" class="success-title">
                                            Delete Main Category
                                        </h2>
                                        <p class="success-desc">
                                            Warning: This action cannot be undone!
                                        </p>
                                    </div>
                                    <form action="{{ route('mainCategory#delete') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="delete_main_category_id"
                                            id="delete_main_category_id">
                                        <div class="d-flex justify-content-center mt-3">
                                            <button type="submit" class="btn btn-danger px-4">Delete</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Create Main Category Modal -->
            <div class="modal fade" id="create_main_category_modal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">

                        <div class="modal-header" style="background:#512DA8; color:white;">
                            <h5 class="modal-title">Create Main Category</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>

                        <form id="createMainCategoryForm" action="{{ route('mainCategory#create') }}" method="POST">
                            @csrf

                            {{-- <input type="hidden" name="main_category_id" value="{{ $main->main_category_id }}"> --}}

                            <div class="modal-body" style="margin-left:20px;margin-right:20px">

                                <!-- CATEGORY NAME -->
                                <div class="row mb-3 mt-3">
                                    <div class="col-5">
                                        <label class="form-label">
                                            Main Category Name <span class="text-danger">*</span>
                                        </label>
                                    </div>
                                    <div class="col">
                                        <input type="text" name="main_category_name" class="form-control">
                                        <div class="invalid-feedback d-block" id="nameError"></div>
                                    </div>
                                </div>

                                <!-- DISCONTINUED -->
                                <div class="row mb-3 align-items-center">
                                    <div class="col-5">
                                        <label class="form-label text-danger">Discontinued</label>
                                    </div>
                                    <div class="col">
                                        <input type="checkbox" name="is_discontinued" class="form-check-input">
                                    </div>
                                </div>

                            </div>

                            <input type="hidden" name="from_create" value="1">

                            <div class="modal-footer">
                                <button type="submit" class="btn custom_btn">Create</button>
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
    <script src="{{ asset('script/category_script.js') }}"></script>

@endsection
