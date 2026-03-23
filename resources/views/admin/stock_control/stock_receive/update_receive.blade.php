@extends('layouts.admin.master')
@section('title', 'Stock Receive')

@section('content')
    <section class="home-section">
        <div class="home-title">
            <i class='bx bx-menu'></i>
            <span class="text">Stock Receive</span>
        </div>
        <div class="home-content">
            <div class="row pb-3 align-items-center" style="color:#512DA8; font-weight:bold">
                <div class="col-7">
                    <label>Update Stock Receive</label>
                </div>
                <div class="col" style="text-align: right">
                    <a href="{{ route('stockControl#stock_receive#receiveListPage') }}"><button
                            class="btn btn-warning text-white customBtn-exit" id="btn_Exit"><i
                                class="fa-solid fa-circle-left" style="padding-right: 5px"></i>Back</button></a>
                    <button class="btn btn-primary customBtn-update" id="btn_Save"><i class="fa-regular fa-floppy-disk"
                            style="padding-right: 5px"></i>Update</button>
                </div>
            </div>
            <div id="voucher_info_label" class="row align-items-center bg-white">
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
                    <div class="receive_info_left col-5">
                        <div class="row">
                            <div class="col-4 mb-3">
                                <label class="form-label">Voucher No</label>
                            </div>
                            <div class="col-8">
                                <input type="text" id="receiveID" name="receiveID"
                                    value="{{ $selectedReceive[0]['stock_receive_id'] }}" hidden>
                                <input type="text" id="loginUserID" name="loginUserID" value="{{ Auth::User()->id }}"
                                    hidden>
                                <input class="form-control" type="text" id="voucher_no" name="voucher_no"
                                    value="{{ $selectedReceive[0]['receive_voucher_number'] }}" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <label class="form-label">Date</label>
                            </div>
                            <div class="col-8">
                                <input class="form-control" id="receive_date" name="receive_date" type="date"
                                    value="{{ date('Y-m-d', strtotime($selectedReceive[0]['receive_date'])) }}">
                            </div>
                        </div>
                    </div>
                    <div class="receive_info_right col-5 offset-2">
                        <textarea class="form-control" id="remark" name="remark" rows="3" placeholder="Remarks">{{ $selectedReceive[0]['remark'] }}</textarea>
                    </div>

                </div>
            </div>
            <div id="item_details_info_label" class="row align-items-center bg-white">
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
                                    @if (count($menu_item) != 0)
                                        <option value="0">--Select One--</option>
                                        @foreach ($menu_item as $item)
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
                                    @if (count($menu_item) != 0)
                                        <option value="0">--Select One--</option>
                                        @foreach ($menu_item as $item)
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
                                <input class="form-control" id="bar_code" name="bar_code" type="text" readonly>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-5">
                                <label class="form-label">Unit</label>
                            </div>
                            <div class="col-7">
                                <select class="form-select" id="unit_name" name="unit_name">
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-5">
                                <label class="form-label">Quantity</label>
                            </div>
                            <div class="col-7">
                                <input class="form-control" id="quantity" name="quantity" type="number"
                                    required="required" min="1" onkeypress="return /[0-9]/i.test(event.key)"
                                    value="1">
                                <span class="text-danger">
                                    <span id="quantity_error"></span>
                                </span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-5">
                                <label class="form-label">Unit Cost</label>
                            </div>
                            <div class="col-7">
                                <input class="form-control" id="unit_cost" name="unit_cost" type="text"
                                    placeholder="0" required="required" onkeypress="return /[0-9]/i.test(event.key)">
                                <span class="text-danger">
                                    <span id="unit_cost_error"></span>
                                </span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-5">
                                <label class="form-label">Amount</label>
                            </div>
                            <div class="col-7">
                                <input class="form-control muted" id="amount" name="amount" type="text"
                                    placeholder="0" readonly>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-5">
                                <label class="form-label">Expire Date</label>
                            </div>
                            <div class="col-7">
                                <input class="form-control" id="expire_date" name="expire_date" type="date">
                            </div>
                        </div>
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
                        <div class=".table-responsive border"
                            style="height:480px; overflow-x:auto; white-space:nowrap; padding-left:10px; padding-right:10px; border-radius:10px;">
                            <table class="table table-hover stockReceiveTable" id="dataTable">
                                <thead class="sticky-top">
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Item Name</th>
                                        <th scope="col">Item Code</th>
                                        <th scope="col">Bar Code</th>
                                        <th scope="col">Unit</th>
                                        <th scope="col">Quantity</th>
                                        <th scope="col">Unit Cost</th>
                                        <th scope="col">Amount</th>
                                        <th scope="col">Expire Date</th>
                                    </tr>
                                </thead>
                                <tbody id="tableBody">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Item Detail Validation modal --}}
        <div class="modal fade modal-sm" id="checkValidationModal" tabindex="-1"
            aria-labelledby="checkValidationModalLabel" data-bs-backdrop="static" data-bs-keyboard="false"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #512DA8">
                        <h5 class="modal-title fs-5 w-100" id="checkValidationModalLabel" style="color: white">Warning
                        </h5>
                    </div>
                    <div class="modal-body">
                        <span class="text-danger"><i class="fa-solid fa-triangle-exclamation text-danger me-2"></i>You
                            need to
                            set item detail !</span>
                    </div>
                    <div class="modal-footer bg-secondary-subtle">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">OK</button>
                    </div>
                </div>
            </div>
        </div>

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
                            Receive record was updated successfully.
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
    <script src="{{ asset('script/receive_script.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            var selectedReceiveDetailList = {!! json_encode($selectedReceiveDetail->toArray()) !!};
            if (selectedReceiveDetailList.length > 0) {
                $.each(selectedReceiveDetailList, function(key, value) {
                    var expireDate = new Date(value.expire_date);
                    var tableBody = document.getElementById('tableBody');
                    var formattedExpireDate = ('0' + expireDate.getDate()).slice(-2) + '/' + ('0' + (
                        expireDate
                        .getMonth() + 1)).slice(-2) + '/' + expireDate.getFullYear();
                    $('#tableBody').append(
                        `<tr><td>${tableBody.rows.length+1}</td><td style="display:none">${value.item_id}</td><td style="word-wrap:break-world; white-space:normal;">${value.item_name}</td><td>${value.item_code}</td>
                                    <td>${value.bar_code}</td><td style="display:none">${value.unit_id}</td><td>${value.unit_name}</td><td>${parseFloat(value.quantity)}</td>
                                    <td>${parseFloat(value.unit_cost)}</td><td>${parseFloat(value.amount)}</td><td>${formattedExpireDate}</td></tr>`
                    );
                })
            }
            //*********************date*********************
            var date = new Date();
            var day = date.getDate();
            var month = date.getMonth() + 1; // month is zero-based
            var year = date.getFullYear();

            if (month < 10) month = "0" + month;
            if (day < 10) day = "0" + day;
            var today = year + "-" + month + "-" + day;
            document.getElementById('receive_date').value = today;
            document.getElementById('expire_date').value = today;

            //*****************Total Amount*****************
            function getTotal() {
                var unit_cost = document.getElementById("unit_cost").value;
                var quantity = document.getElementById("quantity").value;
                if (unit_cost && quantity) {
                    var total = unit_cost * quantity;
                    document.getElementById("amount").value = total;
                } else {
                    document.getElementById("amount").value = "0";
                }

            };

            $('#quantity').on('input', function() {
                getTotal($(this).val());
            });
            $('#unit_cost').keyup(getTotal);

            //**************item selected change****************
            function SelectedItemChange() {
                let itemID = $(this).val();
                var menu_item = @json($menu_item->toArray());
                var selected_item = menu_item.filter(x => x.item_id == itemID);
                if (itemID > 0) {
                    $('#item_name').val(selected_item[0].item_id);
                    $('#item_code').val(selected_item[0].item_id);
                    $('#bar_code').val(selected_item[0].bar_code);

                    $('#unit_name').empty();
                    $('#unit_name').append('<option value="' + selected_item[0].unit_id + '">' + selected_item[0]
                        .unit_name +
                        '</option>');
                    $('#unit_name').val(selected_item[0].unit_id);
                    $('#unit_name').val(selected_item[0].unit_id);

                    clearTextError();
                    getTotal();
                }
            };
            $(document).on("change", "#item_name, #item_code", SelectedItemChange);

            //*************Clear Button****************************
            function clearDetail() {
                $('#item_name').val(0);
                $('#item_code').val(0);
                $('#bar_code').val('');
                $('#unit_name').val('');
                $('#quantity').val('1');
                $('#unit_cost').val('');
                $('#amount').val('');
                $('#remark').val();
                $('#expire_date').val(today);

                $('#item_name').prop('disabled', false);
                $('#item_code').prop('disabled', false);
                $('#unit_name').prop('disabled', false);
                $('#btn_Remove').prop('disabled', true);
                $('#btn_Add').html('<i class="fa-solid fa-plus" style="padding-right: 5px"></i>Add');
                clearTextError();
            };

            $('#btn_ClearDetail').click(clearDetail);
            //**********************clear span text*************************
            function clearTextError() {
                $('#item_name').removeClass('is-invalid');
                $('#item_name_error').html("");

                $('#quantity').removeClass('is-invalid');
                $('#quantity_error').html("");

                $('#unit_cost').removeClass('is-invalid');
                $('#unit_cost_error').html("");
            };

            //**********************Validation*************************
            function validationDetail() {
                clearTextError();
                var hasErrorCount = 0;
                if ($('#item_name').val() == 0) {
                    $('#item_name_error').html("You need to select item!");
                    $('#item_name').focus();
                    $('#item_name').addClass('is-invalid');
                    hasErrorCount += 1;
                }
                if ($('#quantity').val() == "" || $('#quantity').val() == "0") {
                    $('#quantity_error').html("You need to enter quantity!");
                    $('#quantity').focus();
                    $('#quantity').addClass('is-invalid');
                    hasErrorCount += 1;
                }
                if ($('#unit_cost').val() == "") {
                    $('#unit_cost_error').html("You need to enter cost!");
                    $('#unit_cost').focus();
                    $('#unit_cost').addClass('is-invalid');
                    hasErrorCount += 1;
                }
                if (hasErrorCount > 0) {
                    throw ("Invalid!");
                }
            };
            //**********************rowCount*************************
            function rowCount() {
                var i = 0;
                var tRows = document.querySelectorAll('#tableBody tr');
                $(tRows).each(function(key, value) {
                    ++i;
                    $('td:first-child', this).text(i);
                });
            };

            //*************Double Click**************************
            // Add event listener to tbody for double-click
            let lastTap = 0;

            function handleRowClick($rowClicked) {
                var itemcode = $rowClicked.find('td:eq(3)').text();
                var selecteddetail = selectedReceiveDetailList.filter(x => x.item_code == itemcode);
                var rowData = selecteddetail[0];

                // Populate input fields with the data from the clicked row
                document.getElementById('item_name').value = rowData.item_id;
                document.getElementById('item_code').value = rowData.item_id;
                document.getElementById('bar_code').value = rowData.bar_code;

                $('#unit_name').append('<option value="' + selecteddetail[0].unit_id + '">' +
                    selecteddetail[0].unit_name + '</option>');
                $('#unit_name').val(selecteddetail[0].unit_id);

                document.getElementById('quantity').value = parseFloat(rowData.quantity);
                document.getElementById('unit_cost').value = parseFloat(rowData.unit_cost);
                document.getElementById('amount').value = parseFloat(rowData.amount);

                var expireDate = new Date(selecteddetail[0].expire_date);
                $('#expire_date').val(expireDate.getFullYear() + '-' + ('0' + (expireDate.getMonth() + 1)).slice(-
                        2) +
                    '-' + ('0' + expireDate.getDate()).slice(-2));

                $('#item_name').prop('disabled', true);
                $('#item_code').prop('disabled', true);
                $('#unit_name').prop('disabled', true);
                $('#btn_Remove').prop('disabled', false);
                $('#btn_Add').html('<i class="fa-solid fa-plus" style="padding-right: 5px"></i>Update');
            }

            // Handle double-click on desktop
            $('#tableBody').on('dblclick', 'tr', function(event) {
                handleRowClick($(this));
            });

            // Handle double-tap on touch devices
            $('#tableBody').on('touchstart', 'tr', function(event) {
                let currentTime = new Date().getTime();
                let tapLength = currentTime - lastTap;
                if (tapLength < 500 && tapLength > 0) { // Adjust timing as necessary
                    // Double-tap detected
                    handleRowClick($(this));
                }
                lastTap = currentTime;
            });

            //*************Add Button****************************
            document.getElementById('btn_Add').addEventListener('click', function() {
                try {
                    validationDetail();
                    var item_id = document.getElementById('item_name').value;
                    var item_name = $("#item_name option:selected").text();
                    var item_code = $("#item_code option:selected").text();
                    var bar_code = document.getElementById('bar_code').value;
                    var unit_id = document.getElementById('unit_name').value;
                    var unit_name = $("#unit_name option:selected").text();
                    var quantity = document.getElementById('quantity').value;
                    var unit_cost = document.getElementById('unit_cost').value;
                    var amount = document.getElementById('amount').value;
                    var expire_date = document.getElementById('expire_date').value;
                    var tableRows = document.querySelectorAll('#tableBody tr');

                    //Add data to table
                    var row_count = tableRows.length + 1;
                    var tableBody = document.getElementById('tableBody');

                    // Save data to local storage
                    var dataToStore = {
                        "item_id": item_id,
                        "item_name": item_name,
                        "item_code": item_code,
                        "bar_code": bar_code,
                        "unit_id": unit_id,
                        "unit_name": unit_name,
                        "quantity": quantity,
                        "unit_cost": unit_cost,
                        "amount": amount,
                        "expire_date": expire_date
                    };

                    // Get existing data from local storage or initialize an empty array

                    var item_selected = selectedReceiveDetailList.filter(x => x.item_id == item_id);
                    var expireDate = new Date(expire_date);
                    if (item_selected[0] != undefined) {
                        index = selectedReceiveDetailList.findIndex(x => x == item_selected[0]);
                        selectedReceiveDetailList.splice(index, 1); //remove object with specific index
                        selectedReceiveDetailList.splice(index, 0,
                            dataToStore); //insert object with specific index
                        localStorage.setItem('updateStockReceiveDetail_list', JSON.stringify(
                            selectedReceiveDetailList));
                        tableBody.deleteRow(index);
                        var newRow = tableBody.insertRow(index);
                        newRow.innerHTML =
                            `<td>${index+1}</td><td style="display:none">${item_id}</td><td style="word-wrap:break-world; white-space:normal;">${item_name}</td><td>${item_code}</td><td>${bar_code}</td><td style="display:none">${unit_id}</td><td>${unit_name}</td>
                <td>${quantity}</td><td>${unit_cost}</td><td>${amount}</td><td>${('0' + expireDate.getDate()).slice(-2)+ '/' + ('0' + (expireDate.getMonth() + 1)).slice(-2) + '/' + expireDate.getFullYear()}</td>`;
                    } else {
                        var newRow = tableBody.insertRow();
                        newRow.innerHTML =
                            `<td>${row_count}</td><td style="display:none">${item_id}</td><td style="word-wrap:break-world; white-space:normal;">${item_name}</td><td>${item_code}</td><td>${bar_code}</td><td style="display:none">${unit_id}</td><td>${unit_name}</td>
                <td>${quantity}</td><td>${unit_cost}</td><td>${amount}</td><td>${('0' + expireDate.getDate()).slice(-2)+ '/' + ('0' + (expireDate.getMonth() + 1)).slice(-2) + '/' + expireDate.getFullYear()}</td>`;

                        selectedReceiveDetailList.push(dataToStore);
                    }
                    clearDetail();
                    $('#btn_Add').html('<i class="fa-solid fa-plus" style="padding-right: 5px"></i>Add');
                } catch (err) {
                    return;
                }

            });
            //*************btn_Remove****************************
            document.getElementById('btn_Remove').addEventListener('click', function() {
                var item_id = document.getElementById('item_name').value;
                var selecteddetail = selectedReceiveDetailList.filter(x => x.item_id ==
                    item_id); //filter(x=>x.itemID == $('#item_name').val());
                if (selecteddetail[0] != undefined) {
                    index = selectedReceiveDetailList.findIndex(x => x == selecteddetail[0]);
                    selectedReceiveDetailList.splice(index, 1);
                    localStorage.setItem("updateStockReceiveDetail_list", JSON.stringify(
                        selectedReceiveDetailList));
                    var tbody = document.getElementById("tableBody");
                    if (tbody.rows.length) {
                        tbody.deleteRow(index);
                    }
                    clearDetail();
                    rowCount();
                }
                $('#btn_Add').html('<i class="fa-solid fa-plus" style="padding-right: 5px"></i>Add');
            });

            //*************Save Button****************************
            document.getElementById('btn_Save').addEventListener('click', function() {
                if (selectedReceiveDetailList.length == 0) {
                    $('#checkValidationModal').modal('show');
                    return;
                }

                //reduce is summation , accumulator is current summ value
                let total_amount = selectedReceiveDetailList.reduce((accumulator, x) => {
                    return accumulator + parseFloat(x.quantity * x.unit_cost);
                }, 0);

                var masterData = {
                    receiveID: $('#receiveID').val(),
                    voucherNo: $('#voucher_no').val(),
                    receiveDate: $('#receive_date').val(),
                    remark: $('#remark').val(),
                    totalAmt: total_amount,
                    detailList: selectedReceiveDetailList,
                    modifiedBy: $('#loginUserID').val()
                };
                var url = '{{ route('stockControl#stock_receive#updateStockReceive') }}';
                // Send data to backend
                $.ajax({
                    url: url,
                    method: 'get',
                    data: masterData,
                    contentType: 'application/json; charset=utf-8',
                    success: function(response) {
                        clearTextError();
                        if (response.success) {
                            $('#success_text').html(response.success);
                            $('#successModal').modal('show');
                        } else if (response.errors) {
                            console.log(response.errors);
                        }
                    }
                })

            });
            $('#btn_successOK').click(function() {
                window.close();
                var url = "{{ route('stockControl#stock_receive#receiveListPage') }}";
                $(location).attr('href', url);
            })

        });
    </script>

@endsection
