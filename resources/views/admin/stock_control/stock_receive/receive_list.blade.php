@extends('layouts.admin.master')
@section('title', 'Receive Lists')

@section('content')
    <style>
        .receive_list {
            table-layout: fixed;
            width: 100%;
        }

        .receive_list th {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
    </style>
    <section class="home-section">
        <div class="home-title">
            <i class='bx bx-menu'></i>
            <span class="text">Receive Lists</span>
        </div>
        <div class="home-content">
            <div style="display: flex; justify-content: end;">
                <form method="GET" action="{{ route('stockControl#stock_receive#receiveListPage') }}">
                    <input type="date" class="form-control w-auto" name="dailyReceiveDate" 
                        value="{{ request()->query('dailyReceiveDate')}}"
                        onchange="this.form.submit()">
                </form>
            </div>
            <div id="receive_list_label" class="row align-items-center bg-white mt-3">
                <div class="col-6">
                    <label><i class="fa-solid fa-table-list" style="padding-left:5px; padding-right: 12px"></i>Receive
                        Lists</label>
                </div>
                <div class="col-6" style="text-align: right">
                    <i class="bx bxs-chevron-down arrow"></i>
                </div>
            </div>
            <div class="receive_list_container shadow-sm show_container">
                <table id="receive_list" class="receive_list table table-striped nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Receive Date</th>
                            <th>Receive Voucher</th>
                            <th>Total Amount</th>
                            <th>Remark</th>
                            <th>View</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody id="stock_receive_list">
                        @if (count($stock_receive_list) != 0)
                            @php
                                $count = 1;
                            @endphp
                            @foreach ($stock_receive_list as $detail)
                                <tr>
                                    <td>{{ $count }}</td>
                                    <td>{{ date('d-m-Y', strtotime($detail->receive_date)) }}</td>
                                    <td>{{ $detail->receive_voucher_number }}</td>
                                    <td>{{ number_format($detail->total_amount) }}</td>
                                    <td style="word-wrap: break-word; white-space:normal;">{{ $detail->remark }}</td>
                                    <td>
                                        <a href="{{ route('stockControl#stock_receive#receiveDetailsPage', ['id' => $detail->stock_receive_id, 'dailyReceiveDate' => request()->query('dailyReceiveDate')]) }}">
                                            <i class="fa-solid fa-eye" style="color: green; cursor: pointer;"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('stockControl#stock_receive#updateStockReceivePage', $detail->stock_receive_id) }}"
                                            id="updateStockReceive" onclick="removeStockReceiveItem()">
                                            <i class="fa-solid fa-pen-to-square"
                                                style="color: orange;cursor: pointer;"></i></a>
                                    </td>
                                    <td>
                                        <a onclick='selectStockReceiveID({{ $detail->stock_receive_id }},"{{ $detail->receive_voucher_number }}")'
                                            data-bs-toggle="modal" data-bs-target="#myModalStockReceiveDelete">
                                            <i class="fa-regular fa-trash-can" style="color: red;cursor: pointer;"></i></a>
                                    </td>
                                </tr>
                                @php
                                    $count++;
                                @endphp
                            @endforeach
                        @endif
                    </tbody>
                </table>

                {{-- Voucher Delete Modal --}}
                <div class="modal fade" id="myModalStockReceiveDelete">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">

                            <div class="modal-header text-white text-center" style="background-color: #512DA8">
                                <h6 class="modal-title w-100">Are you sure want to delete voucher no: <span class="text-white"
                                        id="delete_content"></span> ?</h6>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            {{-- <form action="{{ route('stockControl#stock_receive#receiveListPage#deleteStoreReceive') }}"
                                method="post" enctype="multipart/form-data" id="deleteStockReceiveModalForm">
                                @csrf --}}
                                <div class="modal-body" style="margin-left: 20px; margin-right: 20px;">
                                    <input type="hidden" name="stockReceive_deleteID" id="stockReceive_deleteID">
                                    <input type="text" id="loginUserID" name="loginUserID" value="{{ Auth::User()->id }}"
                                        hidden>
                                    <div class="row align-items-center mb-3 mt-1">
                                        <label class="form-label ps-0">Delete Reason</label>
                                        <textarea class="form-control" type="text" id="delete_reason" name="delete_reason"></textarea>
                                        <span class="text-danger">
                                            <span id="delete_reason_error"></span>
                                        </span>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <input class="btn btn-danger form" type="submit" value="Delete" id="voucher_delete">
                                </div>
                            {{-- </form> --}}
                        </div>
                    </div>
                </div>
                {{-- End Voucher Delete --}}

            </div>
        </div>
    </section>
    <script src="{{ asset('script/links_js/jquery.3.6.4.min.js') }}"></script>
    <script src="{{ asset('script/links_js/jquery.validate.1.19.5.js') }}"></script>
    <script src="{{ asset('script/links_js/jquery.dataTables.1.13.7.min.js') }}"></script>
    <script src="{{ asset('script/links_js/dataTables.bootstrap5_1.13.7.min.js') }}"></script>
    <script src="{{ asset('script/receive_list_script.js') }}"></script>
    <script>
        new DataTable("#receive_list", {
            scrollX: true,
            autoWidth: false,
            columns: [{
                    width: "10px"
                },
                {
                    width: "80px"
                },
                {
                    width: "80px"
                },
                {
                    width: "80px"
                },
                {
                    width: "200px"
                },
                {
                    width: "20px"
                },
                {
                    width: "20px"
                },
                {
                    width: "20px"
                }
            ],
        });

        // setRowNumber_changed();
        window.onload = function() {
            var j = 0;
            var count = $('#stock_receive_list').find('tr').length;
            if (count > 1) {
                $('#stock_receive_list tr').each(function(key, value) {
                    ++j;
                    $('td:first-child', this).text(j);
                });
            }
        }

        function removePurchaseItem() {
            localStorage.removeItem('updateStockReceiveDetail_list');
        }

        function selectStockReceiveID(id, voucherNo) {
            $("#stockReceive_deleteID").val(id);
            document.getElementById("delete_content").innerHTML = voucherNo;
            $('#delete_reason_error').html("");
            $('#delete_reason').removeClass('is-invalid');
        }

        $('#voucher_delete').click(function(e) {
            e.preventDefault();
            
            let deleteLog = {
                stockReceive_deleteID: $('#stockReceive_deleteID').val(),
                delete_reason: $('#delete_reason').val(),  // Match validator field name
                loginUserID: $('#loginUserID').val(),
                _token: $('meta[name="csrf-token"]').attr('content')
            };
            console.log(deleteLog);
            var url = '{{ route('stockControl#stock_receive#receiveListPage#deleteStoreReceive') }}';
            $.ajax({
                type: 'post',
                url: url,
                data: deleteLog,
                success: (response) => {
                    if(response.errors) {
                        $('#delete_reason_error').text(response.errors.delete_reason);
                    } else if (response.success) {
                        location.reload();
                    }
                }
            });
        });


        $('#deleteStockReceiveModalForm').submit(function(e) {
            e.preventDefault();
            var url = $(this).attr("action");
            let formData = new FormData(this);

            $.ajax({
                type: 'POST',
                url: url,
                data: formData,
                contentType: false,
                processData: false,
                success: (response, data) => {
                    // console.log(response);
                    if (response.errors) {
                        $.each(response.errors, function(key, value) {
                            if (key == "delete_reason") {
                                $('#delete_reason_error').html(value);
                                $('#delete_reason').addClass('is-invalid');
                            }
                        });
                    } else if (response.success) {
                        $('#myModalStockReceiveDelete').modal('hide');
                        var url =
                            "{{ route('stockControl#stock_receive#receiveListPage') }}"; //the url I want to redirect to
                        $(location).attr('href', url);
                    }
                }
            });
        });
    </script>
@endsection
