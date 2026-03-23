<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Menu Catalogue</title>
    {{-- Bootstrap CSS --}}
    <link href="{{ asset('css/links_css/bootstrap.min.css') }}" rel="stylesheet">

    <!--  Data Table CSS -->
    <link rel="stylesheet" href="{{ asset('css/links_css/twitter-bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('css/links_css/dataTables.bootstrap5.min.css') }}">

    {{-- Custom Css --}}
    <link rel="stylesheet" href="{{ asset('css/menu_catalogue_style.css') }}">

    <!-- Boxicons CSS -->
    <link href='{{ asset('css/boxicons-master/css/boxicons.min.css') }}' rel='stylesheet'>

    <!-- FontAwesome CSS -->
    <link rel="stylesheet" href="{{ asset('css/fontawesome/css/all.min.css') }}">

    <!-- logo -->
    <link rel="icon" href="{{ asset('img/rpro_nav_logo_p_big.png') }}" type="image/x-icon">
    <link rel="apple-touch-icon" href="{{ asset('img/apple_touch_icon_180x180.png') }}">
    <style>
        .item_div::-webkit-scrollbar,
        .order_div::-webkit-scrollbar {
            display: none;
        }

        .category_div,
        .item_div,
        .order_div {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .category_div-container {
            position: relative;
            width: calc(100% - 30px);
            margin-left: 15px
        }

        .scroll-arrow {
            position: absolute;
            width: 20px;
            height: 20px;
            z-index: 10;
            cursor: pointer;
            top: 50%;
            transform: translateY(-50%);
            background-size: contain;
            background-repeat: no-repeat;
        }

        .left-arrow {
            left: -10px;
            background-image: url('/img/left-arrow.png');
        }

        .right-arrow {
            right: -10px;
            background-image: url('/img/right-arrow.png');
        }

        .btn-group {
            white-space: nowrap;
        }

        .btn-custom {
            --bs-btn-color: #fff;
            --bs-btn-bg: #6f44d1;
            --bs-btn-border-color: #6f44d1;
            --bs-btn-hover-color: #fff;
            --bs-btn-hover-bg: #6f44d1;
            --bs-btn-hover-border-color: #512DA8;
            --bs-btn-focus-shadow-rgb: 49, 132, 253;
            --bs-btn-active-color: #fff;
            --bs-btn-active-bg: #512DA8;
            --bs-btn-active-border-color: #512DA8;
            --bs-btn-active-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.125);
            --bs-btn-disabled-color: #fff;
            --bs-btn-disabled-bg: #6f44d1;
            --bs-btn-disabled-border-color: #6f44d1;
        }

        .custom-title {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .custom-text {
            margin-left: 30px;
            /* Adjust margin as needed */
        }

        .custom-label {
            margin-left: auto;
            margin-right: 25px;
        }

        /* Existing styles */
        .visually-hidden {
            position: absolute;
            overflow: hidden;
            clip: rect(0 0 0 0);
            height: 1px;
            width: 1px;
            margin: -1px;
            padding: 0;
            border: 0;
        }

        /* Additional styles for gift icon */
        .gift-icon {
            color: whitesmoke;
            /* Default color */
        }

        .gift-checkbox:checked+label .gift-icon {
            color: #512DA8;
            /* Change color when checkbox is checked */
        }

        .item-button:hover {
            cursor: default;
        }

        .active-card {
            border: 1px solid #512DA8 !important;
            /* box-shadow: 0 0 15px rgba(81, 45, 168, 0.5); */
            transition: all 0.1s ease-in-out; */
        }

        .category_div {
            overflow-x: hidden;
        }
        .category_content {
            display: flex;
            white-space: nowrap;
            will-change: transform;
        }

        .category_div {
            /* cursor: grab; */
            user-select: none;
            scrollbar-width: none; /* Firefox */
            -ms-overflow-style: none; /* IE/Edge */
        }

        .category_div::-webkit-scrollbar {
            display: none; /* Chrome, Safari, Opera */
        }

        /* .category_div.active {
            cursor: grabbing;
        } */

        .subCategory-button {
            pointer-events: auto;
        }

        /* Disable native drag for all images inside the category div */
        .category_div img {
            -webkit-user-drag: none;
            -khtml-user-drag: none;
            -moz-user-drag: none;
            -o-user-drag: none;
            user-drag: none;
        }

    </style>
</head>

<body>
    <section class="home-section">
        <div class="home-title custom-title">
            <span class="text custom-text">Store Name</span>
            <label class="custom-label" style="color:#512DA8; font-weight:bold; margin-right: 20px"><i class="fa-solid fa-calendar-days"
                    style="padding-right: 5px"></i>
                {{ now()->format('l, M j, Y') }}
            </label>
        </div>
        <div class="home-content" style="margin-top: 5px">
            <div class="row justify-content-between mt-1">
                <div class="col-12 left_div">
                    <div class="row">
                        <div class="col-0 col-md-6 col-lg-7">
                        </div>
                        <div class="col-12 col-md-6 col-lg-5 item_search_div">
                            <input type="text" class="form-control item_search_input" name="itemSearch"
                                id="itemSearch" placeholder="&#128269;  Search item here..." style="border-radius:10px">
                        </div>
                    </div>
                    <div class="row mt-2"
                        style="background: white; border-radius:10px; margin-right:0px; margin-left:0px">
                        <div class="mt-1">
                            <label style="color:#6f44d1; font-weight:600">+ Choose Category</label>
                        </div>
                        <div style="position: relative; margin-left: 0;">
                            <div class="category_div d-flex flex-nowrap justify-content-start"
                                style="width:100%; overflow-x: auto; overflow-y: hidden; white-space: nowrap;">
                                <div class="category_content" style="margin-bottom: 7px; min-height: 124px">
                                    @if (count($menuCategories) != 0)
                                        @foreach ($menuCategories as $menuCategory)
                                            <button class="btn m-2 subCategory-button p-0 mb-3 shadow-sm"
                                                style="width: 100px; height: 100px; border-radius: 20px; border: none;"
                                                data-table-value="{{ $menuCategory['category_id'] }}">
                                                <div class="card"
                                                    style="background: white; width:100px; border-radius:20px">
                                                    @if ($menuCategory['menu_category_image'] == null)
                                                        <img src="{{ asset('img/category.png') }}"
                                                            class="card-img-top w-100 " alt="..." loading = "lazy"
                                                            style="height: 70px; border-top-left-radius: 20px; border-top-right-radius: 20px">
                                                    @else
                                                        <img src="{{ asset('storage/Images/' . $menuCategory['menu_category_image']) }}"
                                                            class="card-img-top w-100 " alt="..." loading = "lazy"
                                                            style="height: 70px; border-top-left-radius: 20px; border-top-right-radius: 20px; object-fit: contain;">
                                                    @endif

                                                    <div class="card-body d-flex align-items-center justify-content-center" style="height: 40px">
                                                        <p class="card-text text-break"
                                                            style="font-size: 12px; white-space: normal; line-height: 1;">
                                                            {{ $menuCategory['menu_category_name'] }}</p>
                                                    </div>
                                                </div>
                                            </button>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2"
                        style="background: white; border-radius:10px; margin-right:0px; margin-left:0px">
                        <div class="mt-1" style="display: flex; justify-content:space-between">
                            <label style="color:#6f44d1; font-weight:600">+ Choose Items</label>
                            <button class="button-link" style="font-size: 14px">
                                <i class="fa-solid fa-arrow-down-short-wide"></i> Show All
                            </button>
                        </div>
                        {{-- <div class="mt-1">
                            <label style="color:#6f44d1; font-weight:600">+ Choose Items</label>
                        </div> --}}
                        {{-- <div class="item_div justify-content-around mt-2"
                            style="height:58vh;overflow-y: auto; padding-left:10px;">
                            @if (count($items) != 0)
                                @foreach ($items as $item)
                                    <button class="btn m-2 item-button p-0 shadow-sm"
                                        style="width: 178px; height: 200px; border-radius: 20px; border: none;">
                                        <div class="card h-100 w-100" style="background: white">
                                            <img src="{{ asset('storage/Images/' . $item['item_image']) }}"
                                                class="card-img-top w-100 " alt="..." style="height: 110px">
                                            <div class="card-body" style="height: 0px">
                                                <p class="card-title text-muted"
                                                    style="text-align: start; margin-top:-10px;">
                                                    {{ Str::words($item['item_name'], 3, '...') }}</p>
                                                <p class="card-text "
                                                    style="text-align: start; margin-top:-5px; font-size:15px; font-weight:600">
                                                    {{ number_format($item['item_price']) }} MMK
                                                    <br>
                                                </p>
                                            </div>
                                        </div>
                                    </button>
                                @endforeach
                            @endif
                        </div> --}}
                        <div class="item_div mt-2" style="height:58vh; overflow-y: auto; overflow-x: hidden; padding-left:10px; padding-right: 10px; padding-bottom: 10px;">
                            @if (count($items) != 0)
                                <div class="row g-2 item_row"> 
                                    @foreach ($items as $item)
                                        <div class="col-6 col-md-3 col-lg-2">
                                            <button class="btn item-button p-0 w-100"
                                                style="height: 200px; border: none;">
                                                <div class="card h-100 w-100" style="background: white;">
                                                    @if ($item['item_image'] == null)
                                                        <img src="{{ asset('404_image.png') }}"
                                                            class="card-img-top w-100"
                                                            loading = "lazy"
                                                            alt="..." 
                                                            style="height: 110px; object-fit: cover;">
                                                    @else
                                                        <img src="{{ asset('storage/Images/' . $item['item_image']) }}"
                                                            class="card-img-top w-100"
                                                            loading = "lazy"
                                                            alt="..." 
                                                            style="height: 110px; object-fit: contain;">
                                                    @endif
                                                    <div class="card-body" style="height: 0px">
                                                        <p class="card-title text-muted"
                                                            style="text-align: start; margin-top:-10px;">
                                                            {{ Str::words($item['item_name'], 3, '...') }}
                                                        </p>
                                                        <p class="card-text"
                                                            style="text-align: start; margin-top:-5px; font-size:15px; font-weight:600">
                                                            {{ number_format($item['item_price']) }} MMK
                                                        </p>
                                                    </div>
                                                </div>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div style="position: fixed;right:0; bottom:0; margin-right: 10px; margin-bottom:3px">
            <span class="text" style="font-size: 16px">Powered by <a href="http://asiabrightway.com/" target="_blank"
                    style="font-size: 12px">Asia
                    Brightway IT.</a>
            </span>
        </div>
    </section>
</body>
<script src="{{ asset('script/links_js/jquery.3.6.4.min.js') }}"></script>
<script src="{{ asset('script/links_js/jquery.validate.1.19.5.js') }}"></script>
<script src="{{ asset('script/links_js/jquery.dataTables.1.13.7.min.js') }}"></script>
<script src="{{ asset('script/links_js/dataTables.bootstrap5_1.13.7.min.js') }}"></script>
<script src="{{ asset('script/jquery.printPage.js') }}"></script>
<script src="{{ asset('script/menu_catalogue_script.js') }}"></script>

</html>
