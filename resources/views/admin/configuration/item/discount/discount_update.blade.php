@extends('layouts.admin.master')
@section('title', 'Discount')

@section('content')
    <section class="home-section">
        <div class="home-title">
            <i class='bx bx-menu'></i>
            <span class="text">Discount</span>
        </div>
        <div class="home-content">
            <div class="row pb-3 align-items-center" style="color:#512DA8; font-weight:bold">
                <div class="col">
                    <label>Update Discount</label>
                </div>
                <div class="col" style="text-align: right">
                    <button class="btn btn-danger customBtn-updateclear"><i class="fa-solid fa-eraser"
                            style="padding-right: 5px"></i>Clear</button>
                    <button type="submit" form="discountFormUpdate" class="btn btn-primary customBtn-update"><i
                            class="fa-regular fa-floppy-disk" style="padding-right: 5px"></i>Update</button>
                </div>
            </div>
            <form action="{{ route('discount#update') }}" method="POST" id="discountFormUpdate">
                @csrf
                <div id="discount_info_label" class="row align-items-center bg-white">
                    <div class="col-10">
                        <label><i class="fa-solid fa-tag" style="padding-left:5px; padding-right: 12px"></i>Discount
                            Info</label>
                    </div>
                    <div class="col-2" style="text-align: right">
                        <i class="bx bxs-chevron-down arrow"></i>
                    </div>
                </div>
                <div class="discount_info_container shadow-sm show_container">
                    <div class="row border-4 border-gray-800" style="border-radius: 10px">
                        <div class="discount-info-left col-6 pt-3 border-end border-4 border-gray-800">
                            <div class="row align-items-center mb-3 justify-content-center">
                                <input class="form-control" type="text" id="edit_discount_id" name="edit_discount_id"
                                    value="{{ $updateData[0]['item_discount_id'] }}" hidden>
                                <div class="col-5">
                                    <label class="col-form-label">Main Category <span style="color: red">*</span></label>
                                </div>
                                <div class="col-5">
                                    <select class="form-select" id="main_category" name="main_category">
                                        @if (count($mainCategories) != 0)
                                            @foreach ($mainCategories as $mainCategory)
                                                <option value={{ $mainCategory['main_category_id'] }}>
                                                    {{ $mainCategory['main_category_name'] }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="row  mb-3 justify-content-center">
                                <div class="col-5">
                                    <label class="col-form-label">Sub Category <span style="color: red">*</span></label>
                                </div>
                                <div class="col-5">
                                    <select class="form-select" id="sub_category" name="sub_category">
                                        @if (count($subCategories) != 0)
                                            @foreach ($subCategories as $subCategory)
                                                <option value={{ $subCategory['category_id'] }}>
                                                    {{ $subCategory['menu_category_name'] }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="row  mb-3 justify-content-center">
                                <div class="col-5">
                                    <label class="col-form-label">Item Name <span style="color: red">*</span></label>
                                </div>
                                <div class="col-5">
                                    <select class="form-select" id="items" name="items">
                                        @if (count($items) != 0)
                                            @foreach ($items as $item)
                                                <option value={{ $item['item_id'] }}>
                                                    {{ $item['item_name'] }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="row  mb-3 justify-content-center">
                                <div class="col-5">
                                    <label class="col-form-label">Description <span style="color: red">*</span></label>
                                </div>
                                <div class="col-5">
                                    <input class="form-control @error('description') is-invalid @enderror" type="text"
                                        id="description" name="description">
                                    @error('description')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row  mb-3 justify-content-center">
                                <div class="col-5">
                                    <label class="col-form-label">Other Description</label>
                                </div>
                                <div class="col-5">
                                    <input class="form-control" type="text" id="other_description"
                                        name="other_description">
                                </div>
                            </div>
                        </div>
                        <div class="discount-info-right col-6 pt-3">
                            <div class="row mb-3 justify-content-center">
                                <div class="col-5">
                                    <label class="col-form-label">Item Price</label>
                                </div>
                                <div class="col-5">
                                    <input class="form-control muted" style="text-align: end" type="text" id="item_price"
                                        name="item_price" readonly>
                                </div>
                            </div>
                            <div class="row mb-3 justify-content-center">
                                <div class="col-5">
                                    <label class="col-form-label">Buy Quantity <span style="color: red">*</span></label>
                                </div>
                                <div class="col-5">
                                    <input class="form-control @error('buy_quantity') is-invalid @enderror muted"
                                        style="text-align: end" type="number" id="buy_quantity" name="buy_quantity"
                                        value="1" min="1" readonly>
                                    @error('buy_quantity')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3 justify-content-center ">
                                <div class="col-5">
                                    <label class="col-form-label">Discount Type</label>
                                </div>
                                <div class="col-5">
                                    <div class="form-check">
                                        <input class="form-check-input discount-type" type="radio"
                                            name="radio_discount_type" id="amount_lbl" value="Amount" checked>
                                        <label class="form-check-label" for="amount_lbl">
                                            Amount
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input discount-type" type="radio"
                                            name="radio_discount_type" id="percent_lbl" value="Percent">
                                        <label class="form-check-label" for="percent_lbl">
                                            Percent %
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3 justify-content-center">
                                <div class="col-5">
                                    <label class="col-form-label">Amount Discount <span
                                            style="color: red">*</span></label>
                                </div>
                                <div class="col-5">
                                    <input class="form-control @error('amount_discount') is-invalid @enderror"
                                        style="text-align: end" type="number" name="amount_discount"
                                        id="amount_discount" min="0">
                                    @error('amount_discount')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3 justify-content-center">
                                <div class="col-5">
                                    <label class="col-form-label">Percent Discount <span
                                            style="color: red">*</span></label>
                                </div>
                                <div class="col-5">
                                    <input class="form-control muted @error('percent_discount') is-invalid @enderror"
                                        style="text-align: end" type="number" name="percent_discount"
                                        id="percent_discount" value="0" min="0" readonly>
                                    @error('percent_discount')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3 justify-content-center">
                                <div class="col-5">
                                    <label class="col-form-label">Promotion Price</label>
                                </div>
                                <div class="col-5">
                                    <input class="form-control muted" style="text-align: end" id="promotion_price"
                                        name="promotion_price" type="text">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="discount_details_label" class="row align-items-center bg-white">
                    <div class="col-10">
                        <label><i class="fa-solid fa-tags" style="padding-left:5px; padding-right: 12px"></i>Discount
                            Details</label>
                    </div>
                    <div class="col-2" style="text-align: right">
                        <i class="bx bxs-chevron-down arrow"></i>
                    </div>
                </div>
                <div class="discount_details_container shadow-sm show_container">
                    <div class="row border-4 border-gray-800" style="border-radius: 10px">
                        <div class="discount-detail-left col-6 pt-3 border-end border-4 border-gray-800">
                            <p style="padding-bottom:10px; color:#512DA8; font-weight:bold">Discount Days</p>
                            <div class="form-check" style="padding-left: 30%; padding-bottom:10px">
                                <input class="form-check-input" type="checkbox" value="1" id="monday"
                                    name="monday">
                                <label class="form-check-label" for="monday">
                                    Monday
                                </label>
                            </div>
                            <div class="form-check" style="padding-left: 30%; padding-bottom:10px">
                                <input class="form-check-input" type="checkbox" value="1" id="tuesday"
                                    name="tuesday">
                                <label class="form-check-label" for="tuesday">
                                    Tuesday
                                </label>
                            </div>
                            <div class="form-check" style="padding-left: 30%; padding-bottom:10px">
                                <input class="form-check-input" type="checkbox" value="1" id="wednesday"
                                    name="wednesday">
                                <label class="form-check-label" for="wednesday">
                                    Wednesday
                                </label>
                            </div>
                            <div class="form-check" style="padding-left: 30%; padding-bottom:10px">
                                <input class="form-check-input" type="checkbox" value="1" id="thursday"
                                    name="thursday">
                                <label class="form-check-label" for="thursday">
                                    Thursday
                                </label>
                            </div>
                            <div class="form-check" style="padding-left: 30%; padding-bottom:10px">
                                <input class="form-check-input" type="checkbox" value="1" id="friday"
                                    name="friday">
                                <label class="form-check-label" for="friday">
                                    Friday
                                </label>
                            </div>
                            <div class="form-check" style="padding-left: 30%; padding-bottom:10px">
                                <input class="form-check-input" type="checkbox" value="1" id="saturday"
                                    name="saturday">
                                <label class="form-check-label" for="saturday">
                                    Saturday
                                </label>
                            </div>
                            <div class="form-check" style="padding-left: 30%; padding-bottom:10px;">
                                <input class="form-check-input" type="checkbox" value="1" id="sunday"
                                    name="sunday">
                                <label class="form-check-label" for="sunday">
                                    Sunday
                                </label>
                            </div>
                        </div>
                        <div class="discount-detail-right col-6 border-4 border-gray-800 ">
                            <div class="row pt-3 pb-3 border-bottom border-4 border-gray-800">
                                <p style="color:#512DA8; font-weight:bold">Date Range</p>
                                <div class="row align-items-center mb-3 justify-content-center">
                                    <div class="col-5">
                                        <label class="col-form-label" style="padding-left: 20%">Start Date</label>
                                    </div>
                                    <div class="col-7">
                                        <input
                                            class="form-control form-control-sm @error('start_date') is-invalid @enderror"
                                            type="date" id="start_date" name="start_date">
                                        @error('start_date')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row align-items-center mb-3 justify-content-center">
                                    <div class="col-5">
                                        <label class="col-form-label" style="padding-left: 20%">End Date</label>
                                    </div>
                                    <div class="col-7">
                                        <input class="form-control form-control-sm @error('end_date') is-invalid @enderror"
                                            type="date" id="end_date" name="end_date">
                                        @error('end_date')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row pt-3">
                                <p style="color:#512DA8; font-weight:bold">Happy Hour</p>
                                <div class="row align-items-center mb-3 justify-content-center">
                                    <div class="col-5">
                                        <label class="col-form-label" style="padding-left: 20%">Start Hour</label>
                                    </div>
                                    <div class="col-7">
                                        <input class="form-control form-control-sm" type="time" id="start_happy_hour"
                                            name="start_happy_hour">
                                    </div>
                                </div>
                                <div class="row align-items-center mb-3 justify-content-center">
                                    <div class="col-5">
                                        <label class="col-form-label" style="padding-left: 20%">End Hour</label>
                                    </div>
                                    <div class="col-7">
                                        <input class="form-control form-control-sm" type="time" id="end_happy_hour"
                                            name="end_happy_hour">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <script src="{{ asset('script/links_js/jquery.3.6.4.min.js') }}"></script>
    <script src="{{ asset('script/links_js/jquery.validate.1.19.5.js') }}"></script>
    <script src="{{ asset('script/links_js/jquery.dataTables.1.13.7.min.js') }}"></script>
    <script src="{{ asset('script/links_js/dataTables.bootstrap5_1.13.7.min.js') }}"></script>
    <script src="{{ asset('script/discount_update_script.js') }}"></script>
    <script>
        var compactData = @json($updateData);

        $("#item_price").val(compactData[0]['item_price']);
        $('#description').val(compactData[0]['description']);
        $('#other_description').val(compactData[0]['other_description']);
        $('#item_price').val(compactData[0]['item_price']);
        $('#buy_quantity').val(compactData[0]['buy_quantity']);
        if (compactData[0]['discount_type'] == "Amount") {
            $('#amount_lbl').prop('checked', true);
        } else {
            $('#percent_lbl').prop('checked', true);
        }
        $('#amount_discount').val(parseInt(compactData[0]['amount_discount']));
        $('#percent_discount').val(parseInt(compactData[0]['percent_discount']));
        $('#promotion_price').val(parseInt(compactData[0]['promotion_price']));

        if (compactData[0]['monday'] == "1") {
            $('#monday').prop('checked', true);
        }
        if (compactData[0]['tuesday'] == "1") {
            $('#tuesday').prop('checked', true);
        }
        if (compactData[0]['wednesday'] == "1") {
            $('#wednesday').prop('checked', true);
        }
        if (compactData[0]['thursday'] == "1") {
            $('#thursday').prop('checked', true);
        }
        if (compactData[0]['friday'] == "1") {
            $('#friday').prop('checked', true);
        }
        if (compactData[0]['saturday'] == "1") {
            $('#saturday').prop('checked', true);
        }
        if (compactData[0]['sunday'] == "1") {
            $('#sunday').prop('checked', true);
        }


        $('#start_date').val(convertDataFormat(compactData[0]['start_date']));
        $('#end_date').val(convertDataFormat(compactData[0]['end_date']));

        function convertDataFormat(dateString) {
            var inputDateString = dateString;
            var inputDate = new Date(inputDateString);

            // Extract year, month, and day components
            var year = inputDate.getFullYear();
            var month = (inputDate.getMonth() + 1).toString().padStart(2, '0'); // Months are 0-based, so add 1
            var day = inputDate.getDate().toString().padStart(2, '0');

            // Format the date in "yyyy-MM-dd" format
            var formattedDate = year + '-' + month + '-' + day;
            return formattedDate;
        }

        $('#start_happy_hour').val(compactData[0]['start_happy_hour']);
        $('#end_happy_hour').val(compactData[0]['end_happy_hour']);
    </script>

@endsection
