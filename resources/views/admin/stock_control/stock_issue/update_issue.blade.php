@extends('layouts.admin.master')
@section('title', 'Stock Issue')

@section('content')
    <section class="home-section">
        <div class="home-title">
            <i class='bx bx-menu'></i>
            <span class="text">Stock Issue</span>
        </div>
        <div class="home-content">
            <div class="row pb-3 align-items-center" style="color:#512DA8; font-weight:bold">
                <div class="col-7">
                    <label>Update Stock Issue</label>
                </div>
                <div class="col" style="text-align: right">
                    <a href="{{ route('stockControl#stock_issue#issueListPage') }}"><button
                            class="btn btn-warning text-white customBtn-exit" id="btn_Exit"><i class="fa-solid fa-circle-left"
                                style="padding-right: 5px"></i>Back</button></a>
                    <button class="btn btn-primary customBtn-update" id="btn_Save"><i class="fa-regular fa-floppy-disk"
                            style="padding-right: 5px"></i>Update</button>
                </div>
            </div>
            <div id="issue_info_label" class="row align-items-center bg-white">
                <div class="col-10">
                    <label><i class="fa-regular fa-newspaper" style="padding-left:5px; padding-right: 12px"></i>Voucher
                        Info</label>
                </div>
                <div class="col-2" style="text-align: right">
                    <i class="bx bxs-chevron-down arrow"></i>
                </div>
            </div>
            <div class="voucher_info_container shadow-sm show_container">
                <div class="row">
                    <div class="issue_info_left col-5">
                        <div class="row">
                            <div class="col-4 mb-3">
                                <label class="form-label">Voucher No</label>
                            </div>
                            <div class="col-8">
                                <input type="text" id="issueID" name="issueID"
                                    value="{{ $selectedStockIssue[0]['stock_issue_id'] }}" hidden>
                                <input type="text" id="loginUserID" name="loginUserID" value="{{ Auth::User()->id }}"
                                    hidden>
                                <input class="form-control" type="text" id="voucher_no" name="voucher_no"
                                    value="{{ $selectedStockIssue[0]['issue_voucher_number'] }}" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4 mb-3">
                                <label class="form-label">Date</label>
                            </div>
                            <div class="col-8">
                                <input class="form-control" id="issue_date" name="issue_date" type="date"
                                    value="{{ date('Y-m-d', strtotime($selectedStockIssue[0]['issue_date'])) }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4 mb-3">
                                <label class="col-form-label">Issue Type</label>
                            </div>
                            <div class="col-8">
                                <select class="form-select" id="issue_type">
                                    <option>--Select--</option>
                                    @if ($stockIssueTypeList->count() > 0)
                                        @foreach ($stockIssueTypeList as $type)
                                            <option value="{{ $type->issue_type_id }}"
                                                {{ $selectedStockIssue[0]['issue_type_id'] == $type->issue_type_id ? 'selected="selected"' : '' }}>
                                                {{ $type->issue_type_name_1 }}</option>
                                        @endForeach
                                    @endif
                                </select>
                                <span class="text-danger">
                                    <span id="issue_type_error"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="issue_info_right col-5 offset-2">
                        <textarea class="form-control" id="remark" name="remark" rows="3" placeholder="Remarks"
                            value="{{ $selectedStockIssue[0]['remark'] }}">{{ $selectedStockIssue[0]['remark'] }}</textarea>
                    </div>

                </div>
            </div>
            <div id="issue_details_list_label" class="row align-items-center bg-white">
                <div class="col-10">
                    <label><i class="fa-solid fa-cookie-bite" style="padding-left:5px; padding-right: 13px"></i>Item
                        Details
                        Info</label>
                </div>
                <div class="col-2" style="text-align: right">
                    <i class="bx bxs-chevron-down arrow"></i>
                </div>
            </div>
            <div class="item_details_info_container shadow-sm show_container" style="overflow-y:auto">
                <div class="row">
                    <div class="item_detail_left col-5">
                        <div class="row mb-3">
                            <div class="col-5">
                                <label class="form-label">Item Name</label>
                            </div>
                            <div class="col-7">
                                <select class="form-select select-option" id="item_name" name="item_name">
                                    @if (count($itemList) != 0)
                                        <option value="0">--Select One--</option>
                                        @foreach ($itemList as $item)
                                            <option value="{{ $item->item_id }}">{{ $item->item_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <span class="text-danger">
                                    <span id="item_name_error"></span>
                                </span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-5">
                                <label class="form-label">Item Code</label>
                            </div>
                            <div class="col-7">
                                <select class="form-select" id="item_code" name="item_code">
                                    @if (count($itemList) != 0)
                                        <option value="0">--Select One--</option>
                                        @foreach ($itemList as $item)
                                            <option value="{{ $item->item_id }}">{{ $item->item_code }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-5">
                                <label class="form-label">Bar Code</label>
                            </div>
                            <div class="col-7">
                                <input class="form-control" id="barcode" name="barcode" type="text" readonly>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-5">
                                <label class="form-label">Unit</label>
                            </div>
                            <div class="col-7">
                                <select class="form-select" id="unit_name" name="unit_name" readonly>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-5">
                                <label class="form-label">Store Quantity</label>
                            </div>
                            <div class="col-7">
                                <input class="form-control" id="store_Qty" name="store_Qty" type="text"
                                    value="0" readonly>
                                <span class="text-danger">
                                    <span id="store_Qty_error"></span>
                                </span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-5">
                                <label class="form-label">Quantity</label>
                            </div>
                            <div class="col-7">
                                <input class="form-control" id="quantity" name="quantity" type="number"
                                    onkeypress="return /[0-9]/i.test(event.key)" value="1">
                                <input class="form-control" id="unit_cost" name="unit_cost" type="number" hidden>
                                <span class="text-danger">
                                    <span id="quantity_error"></span>
                                </span>
                            </div>
                        </div>
                        <input class="form-control" id="batch_number" name="batch_number" type="text" hidden>
                        <input class="form-control" id="expire_date" name="expire_date" type="text" hidden>
                        <input class="form-control" id="item_type" name="item_type" type="text" hidden>

                        <div>
                            <button class="btn btn-primary mt-2" id="btn_Add"><i class="fa-solid fa-plus"
                                    style="padding-right: 5px"></i>Add</button>
                            <button class="btn btn-warning text-white mt-2" id="btn_Remove"><i class="fa-solid fa-minus"
                                    style="padding-right: 5px"></i>Remove</button>
                            <button class="btn btn-danger mt-2" id="btn_ClearDetail"><i class="fa-solid fa-eraser"
                                    style="padding-right: 5px"></i>Clear</button>
                        </div>
                    </div>
                    <div class="item_detail_right col-7">
                        <div class=".table-responsive border border-2"
                            style="height:480px; overflow-x:auto; white-space:nowrap; padding-left:10px; padding-right:10px; border-radius:10px;">
                            <table class="table table-hover stockissueTable" id="dataTable">
                                <thead class="sticky-top">
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Item Name</th>
                                        <th scope="col">Item Code</th>
                                        <th scope="col">Bar Code</th>
                                        <th scope="col">Batch No</th>
                                        <th scope="col">Unit</th>
                                        <th scope="col">Quantity</th>
                                        <th scope="col">Type</th>
                                        <th scope="col">Expire Date</th>
                                    </tr>
                                </thead>
                                <tbody id="issue_detail">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Item Deletail Validation Modal --}}
        <div class="modal fade modal-sm" id="checkErrorModal" tabindex="-1" role="dialog" aria-hidden="true"
            data-bs-keyboard="false" data-bs-backdrop="static">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #512DA8">
                        <h5 class="modal-title fs-5 w-100" style="color: white">Warning</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-danger"><i class="fa-solid fa-triangle-exclamation text-danger me-2"></i><span
                                id="error_text"></span></p>
                    </div>
                    <div class="modal-footer bg-secondary-subtle">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal"
                            id="btn_CheckOk">OK</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Success Modal --}}
        {{-- <div class="modal fade modal-sm" id="successModal" tabindex="-1" aria-labelledby="successModalLabel"
            data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #512DA8">
                        <h5 class="modal-title fs-5 w-100" id="successModalLabel" style="color: white">Success</h5>
                    </div>
                    <div class="modal-body">
                        <h6 id="success_text"><i class="fa-solid fa-circle-exclamation text-primary"></i> Update
                            successful!</h6>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn custom_btn" data-bs-dismiss="modal"
                            id="btn_successOK">OK</button>
                    </div>
                </div>
            </div>
        </div> --}}

        {{-- Success Modal --}}
        <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel"
            data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="success-card">      
                    <button id="btn_successOK" class="btn-cross-custom" data-bs-dismiss="modal">
                        <i class="fa-solid fa-x"></i>
                    </button>
                    
                    <div class="icon-wrapper">
                        <div class="icon-circle">
                            <i class="fa-solid fa-check"></i>
                        </div>
                    </div>
                    
                    <div class="text-content">
                        <h2 class="success-title">
                            Success
                        </h2>
                        <p class="success-desc">
                            Issue record was updated successfully.
                        </p>
                    </div>
                    
                </div>
            </div>
        </div>

    </section>
    <script src="{{ asset('script/links_js/jquery.3.6.4.min.js') }}"></script>
    <script src="{{ asset('script/links_js/jquery.validate.1.19.5.js') }}"></script>
    <script src="{{ asset('script/links_js/jquery.dataTables.1.13.7.min.js') }}"></script>
    <script src="{{ asset('script/links_js/dataTables.bootstrap5_1.13.7.min.js') }}"></script>
    <script src="{{ asset('script/issue_script.js') }}"></script>
    <script>
        $(document).ready(function() {
            var selectedIssueDetailList = {!! json_encode($selectedStockIssuesDetailList->toArray()) !!};
            if (selectedIssueDetailList.length > 0) {
                $.each(selectedIssueDetailList, function(key, value) {
                    var expire_date = new Date(value.expire_date);
                    $('#issue_detail').append(
                        `<tr>
                            <td>${value.no}</td>
                            <td style="word-wrap:break-world; white-space:normal;">${value.item_name}</td>
                            <td>${value.item_code}</td><td>${value.barcode}</td><td>${value.batch_number}</td>
                            <td>${value.unit_name}</td>
                            <td>${parseFloat(value.quantity)}</td>
                            <td>${value.issue_type}</td>
                            <td>${('0' + expire_date.getDate()).slice(-2)+ '/' + ('0' + (expire_date.getMonth() + 1)).slice(-2) + '/' + expire_date.getFullYear()}</td>
                        </tr>`
                    );
                })
                setRowNumber_changed();
            };
            //-------Date------//
            var today = new Date();
            var now = today.getFullYear() + '-' + ('0' + (today.getMonth() + 1)).slice(-2) + '-' + ('0' + today
                    .getDate())
                .slice(-2);
            $('#issue_date').val(now);
            $('#expire_date').val(now);

            var itemDetailList = {
                set: function(issue_detail_entry) {
                    if (selectedIssueDetailList == null) selectedIssueDetailList = [];
                    selectedIssueDetailList.push(issue_detail_entry);
                    var expire_date = new Date(issue_detail_entry.expire_date);
                    $('#issue_detail').append(
                        `<tr>
                            <td>${issue_detail_entry.no}</td>
                            <td style="word-wrap:break-world; white-space:normal;">${issue_detail_entry.item_name}</td>
                            <td>${issue_detail_entry.item_code}</td>
                            <td>${issue_detail_entry.barcode}</td>
                            <td>${issue_detail_entry.batch_number}</td>
                            <td>${issue_detail_entry.unit_name}</td>
                            <td>${issue_detail_entry.quantity}</td>
                            <td>${issue_detail_entry.issue_type}</td>
                            <td>${('0' + expire_date.getDate()).slice(-2)+ '/' + ('0' + (expire_date.getMonth() + 1)).slice(-2) + '/' + expire_date.getFullYear()}</td></tr>`
                    );

                    setRowNumber_changed();
                    return issue_detail_entry;
                },
            };

            //*************Double Click**************************
            // Add event listener to tbody for double-click
            let lastTap = 0;

            // $('#issue_detail').on('dblclick', 'tr', function() {
            //     var $rowClicked = $(this);
            //     var itemcode = $rowClicked.find('td:eq(2)').text();
            //     var batch_number = $rowClicked.find('td:eq(4)').text();
            //     var selecteddetail = selectedIssueDetailList.filter(x => x.item_code == itemcode && x
            //         .batch_number ==
            //         batch_number);
            //     if (selecteddetail[0] != undefined) {
            //         selecteddetail = selecteddetail[0];
            //         oldIssueDetailList = {!! json_encode($selectedStockIssuesDetailList->toArray()) !!};
            //         $_StoreQty = oldIssueDetailList.filter(x => x.item_code == itemcode && x.is_update ==
            //             selecteddetail
            //             .is_update).reduce((accumulator, x) => {
            //             return accumulator + parseFloat(x.quantity);
            //         }, 0) ?? 0;

            //         $('#item_name').val(selecteddetail.itemID);
            //         $('#item_code').val(selecteddetail.itemID);
            //         $('#barcode').val(selecteddetail.barcode);

            //         $('#unit_name').append('<option value="' + selecteddetail.unitID + '">' + selecteddetail
            //             .unit_name +
            //             '</option>');
            //         $('#unit_name').val(selecteddetail.unitID);
            //         $('#unit_cost').val(selecteddetail.unit_cost);

            //         $('#quantity').val(parseFloat(selecteddetail.quantity));
            //         $('#expire_date').val(selecteddetail.expire_date);

            //         $('#item_name').prop('disabled', true);
            //         $('#item_code').prop('disabled', true);
            //         $('#unit_name').prop('disabled', true);
            //         $('#btn_Add').prop('disabled', true);

            //         let dbl_url = '{{ route('stockControl#stock_issue#checkStoreQty') }}';
            //         $.ajax({
            //             url: dbl_url,
            //             type: "get",
            //             data: {
            //                 itemID: selecteddetail.itemID,
            //                 unitID: selecteddetail.unitID
            //             },
            //             contentType: 'application/json; charset=utf-8',
            //             success: function(response) {
            //                 let Store_Qty = parseFloat(response.success);
            //                 Store_Qty += $_StoreQty;
            //                 // Store_Qty += (selecteddetail.is_update==1)?$_StoreQty:0;
            //                 $('#store_Qty').val(Store_Qty);
            //             }
            //         });
            //     }
            // });

            function handleRowClick($rowClicked) {
                var itemcode = $rowClicked.find('td:eq(2)').text();
                var batch_number = $rowClicked.find('td:eq(4)').text();
                var expire_date = $rowClicked.find('td:eq(8)').text();
                // var item_type = $rowClicked.find('td:eq(7)').text();
                console.log("Batch Number", batch_number);
                
                var selecteddetail = selectedIssueDetailList.filter(x => x.item_code == itemcode && x.batch_number == batch_number);
                console.log("Selected Detail ", selecteddetail);
                if (selecteddetail[0] != undefined) {
                    selecteddetail = selecteddetail[0];
                    oldIssueDetailList = {!! json_encode($selectedStockIssuesDetailList->toArray()) !!};
                    $_StoreQty = oldIssueDetailList.filter(x => x.item_code == itemcode && x.is_update ==
                        selecteddetail
                        .is_update).reduce((accumulator, x) => {
                        return accumulator + parseFloat(x.quantity);
                    }, 0) ?? 0;

                    console.log(selecteddetail.itemID);
                    $('#item_name').val(selecteddetail.itemID);
                    console.log("Item Name ",  $('#item_name').val());
                    $('#item_code').val(selecteddetail.itemID);
                    $('#barcode').val(selecteddetail.barcode);
                    $('#batch_number').val(selecteddetail.batch_number);

                    $('#unit_name').append('<option value="' + selecteddetail.unitID + '">' + selecteddetail
                        .unit_name +
                        '</option>');
                    $('#unit_name').val(selecteddetail.unitID);
                    $('#unit_cost').val(selecteddetail.unit_cost);

                    $('#quantity').val(parseFloat(selecteddetail.quantity));
                    $('#expire_date').val(expire_date);
                    $('#item_type').val(selecteddetail.issue_type);
                    console.log("Expire Date", expire_date);
                    console.log("Issue Type", selecteddetail.issue_type);

                    $('#item_name').prop('disabled', true);
                    $('#item_code').prop('disabled', true);
                    $('#unit_name').prop('disabled', true);
                    // $('#btn_Add').prop('disabled', true);
                    $('#btn_Add').html('<i class="fa-solid fa-plus" style="padding-right: 5px"></i>Update');

                    let dbl_url = '{{ route('stockControl#stock_issue#checkStoreQty') }}';
                    $.ajax({
                        url: dbl_url,
                        type: "get",
                        data: {
                            itemID: selecteddetail.itemID,
                            unitID: selecteddetail.unitID
                        },
                        contentType: 'application/json; charset=utf-8',
                        success: function(response) {
                            let Store_Qty = parseFloat(response.success);
                            Store_Qty += $_StoreQty;
                            // Store_Qty += (selecteddetail.is_update==1)?$_StoreQty:0;
                            $('#store_Qty').val(Store_Qty);
                        }
                    });
                }
            }
            // Handle double-click on desktop
            $('#issue_detail').on('dblclick', 'tr', function(event) {
                handleRowClick($(this));
            });
            // Handle double-tap on touch devices
            $('#issue_detail').on('touchstart', 'tr', function(event) {
                let currentTime = new Date().getTime();
                let tapLength = currentTime - lastTap;
                if (tapLength < 500 && tapLength > 0) { // Adjust timing as necessary
                    // Double-tap detected
                    handleRowClick($(this));
                }
                lastTap = currentTime;
            });

            //**************item selected change****************
            $(document).on("change", "#item_name, #item_code", ItemSelectedChange);

            function ItemSelectedChange() {
                let itemID = $(this).val();
                let items = {!! json_encode($itemList->toArray()) !!};
                const selected_item = items.filter(x => x.item_id == itemID);
                if (itemID > 0) {
                    $('#item_name').val(selected_item[0].item_id);
                    $('#item_code').val(selected_item[0].item_id);
                    $('#barcode').val(selected_item[0].bar_code);

                    $('#unit_name').empty();
                    $('#unit_name').append('<option value="' + selected_item[0].unit_id + '">' + selected_item[0]
                        .unit_name +
                        '</option>');
                    $('#unit_name').val(selected_item[0].unit_id);

                    let check_url = '{{ route('stockControl#stock_issue#checkStoreQty') }}';
                    $.ajax({
                        url: check_url,
                        type: "get",
                        data: {
                            itemID: selected_item[0].item_id,
                            unitID: selected_item[0].unit_id
                        },
                        contentType: 'application/json; charset=utf-8',
                        success: function(response) {
                            let Store_Qty = response.success;
                            $('#store_Qty').val(Store_Qty);
                        }
                    });

                } else {
                    $('#item_name').val(0);
                    $('#item_code').val(0);
                    $('#barcode').val(null);

                    $('#unit_name').empty();
                    $('#store_Qty').val(0);
                }
            };

            //*************Add Button****************************
            // $('#btn_Add').click(function(e) {

            //     var item_id =  $('#item_name :selected').val();
            //     var item_name = $('#item_name :selected').text();
            //     var item_code = $('#item_code :selected').text();
            //     var batch_number = $('#batch_number').val();
            //     var bar_code = $('#barcode').val();
            //     var unit_id = $('#unit_name :selected').val();
            //     var unit_name = $('#unit_name :selected').text();
            //     var quantity = $('#quantity').val();
            //     var is_update = 0;
            //     var issue_type = '';
            //     var expire_date = $('#expire_date').val();
            //     // var tableRows = document.querySelectorAll('#tableBody tr');

            //     var tableRows = document.querySelectorAll("#issue_detail tr");
            //     var rowCount = tableRows.length + 1;
            //     var tableBody = document.getElementById("issue_detail");

            //     // var issue_detail_entry = {
            //     //     itemID: $('#item_name :selected').val(),
            //     //     item_name: $('#item_name :selected').text(),
            //     //     item_code: $('#item_code :selected').text(),
            //     //     batch_number: 0,
            //     //     barcode: $('#barcode').val(),
            //     //     unitID: $('#unit_name :selected').val(),
            //     //     unit_name: $('#unit_name :selected').text(),
            //     //     quantity: $('#quantity').val(),
            //     //     is_update: 0,
            //     //     issue_type: ''
            //     // };

            //     var dataToStore = {
            //         'item_id' : item_id,
            //         'item_name' : item_name,
            //         'item_code' : item_code,
            //         'batch_number' : batch_number,
            //         'bar_code' : bar_code,
            //         'unit_id' : unit_id,
            //         'unit_name' : unit_name,
            //         'quantity' : quantity,
            //         'is_update' : is_update,
            //         'issue_type' : issue_type
            //     }

            //     console.log(selectedIssueDetailList);
            //     var item_selected = selectedIssueDetailList.filter(x => x.item_id == item_id);
            //         var expireDate = new Date(expire_date);
            //         if (item_selected[0] != undefined) {
            //             index = selectedIssueDetailList.findIndex(x => x == item_selected[0]);
            //             selectedIssueDetailList.splice(index, 1); //remove object with specific index
            //             selectedIssueDetailList.splice(index, 0,
            //                 dataToStore); //insert object with specific index
            //             localStorage.setItem('updateStockIssueDetail_list', JSON.stringify(
            //                 selectedIssueDetailList));
            //             tableBody.deleteRow(index);
            //             var newRow = tableBody.insertRow(index);
            //             newRow.innerHTML =
            //                 `<td>${index+1}</td><td style="display:none">${itemID}</td><td style="word-wrap:break-world; white-space:normal;">${item_name}</td><td>${item_code}</td><td>${bar_code}</td><td style="display:none">${batch_number}</td><td>${unit_name}</td>
            //     <td>${quantity}</td><td>${issue_type}</td><td>${('0' + expireDate.getDate()).slice(-2)+ '/' + ('0' + (expireDate.getMonth() + 1)).slice(-2) + '/' + expireDate.getFullYear()}</td>`;
            //         } else {
            //             var newRow = tableBody.insertRow();
            //             newRow.innerHTML =
            //                 `<td>${rowCount}</td><td style="display:none">${item_id}</td><td style="word-wrap:break-world; white-space:normal;">${item_name}</td><td>${item_code}</td><td>${bar_code}</td><td style="display:none">${batch_number}</td><td>${unit_name}</td>
            //     <td>${quantity}</td><td>${issue_type}</td><td>${('0' + expireDate.getDate()).slice(-2)+ '/' + ('0' + (expireDate.getMonth() + 1)).slice(-2) + '/' + expireDate.getFullYear()}</td>`;

            //             selectedIssueDetailList.push(dataToStore);
            //         }
            //         clearDetail();
            //         $('#btn_Add').html('<i class="fa-solid fa-plus" style="padding-right: 5px"></i>Add');

            //     // let add_url = '{{ route('stockControl#stock_issue#checkIssueDetailValidation') }}';
            //     // $.ajax({
            //     //     url: add_url,
            //     //     type: "get",
            //     //     data: issue_detail_entry,
            //     //     // contentType: 'application/json; charset=utf-8',
            //     //     success: function(response) {
            //     //         clearError();
            //     //         if (response.errors) {
            //     //             $.each(response.errors, function(key, value) {
            //     //                 if (key == "itemID") {
            //     //                     $('#item_name_error').html(value);
            //     //                     $('#item_name').addClass('is-invalid');
            //     //                 }
            //     //                 if (key == "quantity") {
            //     //                     $('#quantity_error').html(value);
            //     //                     $('#quantity').addClass('is-invalid');
            //     //                 }
            //     //             });
            //     //         } else if (response.success) {
            //     //             let resultList = response.success;

            //     //             let storeQty = resultList[0];
            //     //             let selectedItemList = resultList[1];

            //     //             oldIssueDetailList = {!! json_encode($selectedStockIssuesDetailList->toArray()) !!};
            //     //             $_StoreQty = oldIssueDetailList.filter(x => x.itemID ==
            //     //                 issue_detail_entry
            //     //                 .itemID).reduce((accumulator, x) => {
            //     //                 return accumulator + parseFloat(x.quantity);
            //     //             }, 0) ?? 0;
            //     //             storeQty += $_StoreQty;

            //     //             if (storeQty == 0 || issue_detail_entry.quantity > storeQty) {
            //     //                 $('#store_Qty_error').html("Not enough quantity!");
            //     //                 $('#store_Qty').addClass('is-invalid');
            //     //             } else {
            //     //                 var issue_Qty = parseFloat($('#quantity').val());
            //     //                 var selecteddetail = selectedIssueDetailList.filter(x => x
            //     //                     .itemID ==
            //     //                     issue_detail_entry.itemID && x.unitID ==
            //     //                     issue_detail_entry.unitID);
            //     //                 if (selecteddetail[0] != undefined) {
            //     //                     $('#item_name_error').html("Duplicate Item!");
            //     //                     $('#item_name').addClass('is-invalid');
            //     //                     return;
            //     //                 } else if (selecteddetail[0] == undefined) {
            //     //                     for (var item of selectedItemList) {
            //     //                         var balanceQty = item.receiveQty + item.purchaseQty;
            //     //                         if (parseFloat(balanceQty) >= issue_Qty) {
            //     //                             issue_detail_entry.batch_number = item.batch_number;
            //     //                             issue_detail_entry.quantity = issue_Qty;
            //     //                             issue_detail_entry.expire_date = item.expire_date;
            //     //                             issue_detail_entry.issue_type = item.type;
            //     //                             issue_Qty = 0;
            //     //                             itemDetailList.set(issue_detail_entry);
            //     //                             break;
            //     //                         } else if (parseFloat(balanceQty) < issue_Qty) {
            //     //                             issue_detail_entry.batch_number = item.batch_number;
            //     //                             issue_detail_entry.quantity = parseFloat(item
            //     //                                 .BalanceQty);
            //     //                             issue_Qty = issue_Qty - parseFloat(balanceQty);
            //     //                             issue_detail_entry.expire_date = item.expire_date;
            //     //                             issue_detail_entry.issue_type = item.type;
            //     //                             itemDetailList.set(issue_detail_entry);
            //     //                         }
            //     //                     };
            //     //                 }
            //     //             }
            //     //             clearDetail();
            //     //         }
            //     //     },
            //     // });
            // });
            $('#btn_Add').click(function(e) {
                try {
                    // Validation
                    clearError();
                    var hasErrorCount = 0;
                    
                    if ($('#item_name').val() == 0) {
                        $('#item_name_error').html("You need to select item!");
                        $('#item_name').addClass('is-invalid');
                        hasErrorCount += 1;
                    }
                    
                    if ($('#quantity').val() == "" || $('#quantity').val() == "0") {
                        $('#quantity_error').html("You need to enter quantity!");
                        $('#quantity').addClass('is-invalid');
                        hasErrorCount += 1;
                    }
                    
                    if (hasErrorCount > 0) {
                        return;
                    }

                    // Get form values
                    var item_id = $('#item_name').val();
                    var item_name = $('#item_name :selected').text();
                    var item_code = $('#item_code :selected').text();
                    var bar_code = $('#barcode').val();
                    var unit_id = $('#unit_name').val();
                    var unit_name = $('#unit_name :selected').text();
                    var quantity = $('#quantity').val();
                    var unit_cost = $('#unit_cost').val();
                    var issue_type = $('#issue_type :selected').text();
                    var batch_number = $('#batch_number').val();
                    var expire_date = $('#expire_date').val();
                    var item_type = $('#item_type').val();
                    console.log("expire date in add", expire_date);
                    
                    var tableBody = document.getElementById('issue_detail');
                    
                    // Create data object
                    var dataToStore = {
                        'no': tableBody.rows.length + 1,
                        'itemID': item_id,
                        'item_name': item_name,
                        'item_code': item_code,
                        'batch_number': batch_number,
                        'barcode': bar_code,
                        'unitID': unit_id,
                        'unit_name': unit_name,
                        'quantity': parseFloat(quantity),
                        'unit_cost': parseFloat(unit_cost),
                        'is_update': 0,
                        'issue_type': item_type,
                        'expire_date': expire_date
                    };

                    console.log(selectedIssueDetailList);

                    // Check if item already exists
                    var item_selected = selectedIssueDetailList.filter(x => x.itemID == item_id);
                    
                    if (item_selected[0] != undefined) {
                        // Update existing item
                        index = selectedIssueDetailList.findIndex(x => x == item_selected[0]);
                        selectedIssueDetailList.splice(index, 1);
                        selectedIssueDetailList.splice(index, 0, dataToStore);
                        
                        tableBody.deleteRow(index);
                        var newRow = tableBody.insertRow(index);
                        newRow.innerHTML = `
                            <td>${index + 1}</td>
                            <td style="word-wrap:break-word; white-space:normal;">${item_name}</td>
                            <td>${item_code}</td>
                            <td>${bar_code}</td>
                            <td>${batch_number}</td>
                            <td>${unit_name}</td>
                            <td>${parseFloat(quantity)}</td>
                            <td>${item_type}</td>
                            <td>${expire_date}</td>
                        `;
                    } else {
                        // Add new item
                        var newRow = tableBody.insertRow();
                        newRow.innerHTML = `
                            <td>${tableBody.rows.length}</td>
                            <td style="word-wrap:break-word; white-space:normal;">${item_name}</td>
                            <td>${item_code}</td>
                            <td>${bar_code}</td>
                            <td>${batch_number}</td>
                            <td>${unit_name}</td>
                            <td>${parseFloat(quantity)}</td>
                            <td>${item_type}</td>
                            <td>${expire_date}</td>
                        `;
                        
                        selectedIssueDetailList.push(dataToStore);
                    }
                    
                    clearDetail();
                    setRowNumber_changed();
                    $('#btn_Add').html('<i class="fa-solid fa-plus" style="padding-right: 5px"></i>Add');
                    
                } catch (err) {
                    console.log(err);
                    return;
                }
            });


            //----------Remove Button Click -------------//
            $('#btn_Remove').click(function() {
                var selecteddetail = selectedIssueDetailList.filter(x => x.itemID == $(
                        '#item_name :selected').val() &&
                    x.unitID == $('#unit_name :selected').val());
                if (selecteddetail != undefined) {
                    for (let i = 0; i < selecteddetail.length; i++) {
                        index = selectedIssueDetailList.findIndex(x => x == selecteddetail[i]);
                        selectedIssueDetailList.splice(index, 1);

                        var tbody = document.getElementById("issue_detail");
                        if (tbody.rows.length) {
                            tbody.deleteRow(index);
                        }
                    }
                    clearDetail();
                    setRowNumber_changed();
                }
            });

            //-----------Save Button Click --//
            $('#btn_Save').click(function() {

                if (selectedIssueDetailList.length == 0) {
                    $('#error_text').empty();
                    $('#error_text').text('You need to set item detail !');
                    $('#checkErrorModal').modal('show');
                    return;
                }
                let total_qty = selectedIssueDetailList.reduce((accumulator, x) => {
                    return accumulator + parseFloat(x.quantity);
                }, 0);

                var issue_master_entry = {
                    issueID: $('#issueID').val(),
                    loginUserID: $('#loginUserID').val(),
                    voucher_no: $('#voucher_no').val(),
                    issue_date: $('#issue_date').val(),
                    issue_type: $('#issue_type :selected').val(),
                    remark: $('#remark').val(),
                    totalQty: total_qty,
                    issue_detailList: selectedIssueDetailList
                };

                var url = '{{ route('stockControl#stock_issue#updateStockIssue') }}';
                $.ajax({
                    url: url,
                    type: "get",
                    data: issue_master_entry,
                    contentType: 'application/json; charset=utf-8',
                    success: function(response) {
                        clearError();
                        if (response.errors) {
                            // console.log(response.errors);
                            if (typeof(response.errors) === 'object') {
                                $.each(response.errors, function(key, value) {
                                    if (key == "issue_type") {
                                        $('#issue_type_error').html(value);
                                        $('#issue_type').addClass('is-invalid');
                                    }
                                    if (key == "storeQty") {
                                        $('#error_text').empty();
                                        $('#error_text').text(value);
                                        $('#checkErrorModal').modal('show');
                                        return;
                                    }
                                });
                            }
                        } else if (response.success) {
                            console.log(response.success);
                            $('#success_text').html(response.success);
                            $('#successModal').modal('show');
                        }
                    }
                });
            });

            function clearDetail() {
                $('#item_name').val(0);
                $('#item_code').val(0);
                $('#barcode').val(null);
                $('#unit_name').empty();
                $('#quantity').val(1);
                $('#store_Qty').val(0);
                $('#unit_cost').val(0);
                $('#item_name').prop('disabled', false);
                $('#item_code').prop('disabled', false);
                $('#unit_name').prop('disabled', false);
                $('#btn_Add').prop('disabled', false);

                clearError();
            }

            function clearError() {
                $('#item_name').removeClass('is-invalid');
                $('#item_name_error').html("");

                $('#quantity').removeClass('is-invalid');
                $('#quantity_error').html("");

                $('#store_Qty').removeClass('is-invalid');
                $('#store_Qty_error').html("");

                $('#issue_type').removeClass('is-invalid');
                $('#issue_type_error').html("");

                $('#issue_detaillist_error').html("");
            }

            $('#btn_Exit').click(function() {

            });

            $('#btn_ClearDetail').click(function() {
                clearDetail();
                clearError();
            });

            $('#btn_successOK').click(function() {
                window.close();
                var url = "{{ route('stockControl#stock_issue#issueListPage') }}";
                $(location).attr('href', url);
            });

            function setRowNumber_changed() {
                var j = 0;
                $('#issue_detail tr').each(function(key, value) {
                    ++j;
                    $('td:first-child', this).text(j);
                });
            }
        });
    </script>
@endsection
