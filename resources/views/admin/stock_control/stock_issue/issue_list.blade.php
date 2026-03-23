@extends('layouts.admin.master')
@section('title', 'Issue Lists')

@section('content')
	<style>
        .issue_list {
            table-layout: fixed;
            width: 100%;
        }

        .issue_list th {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
    </style>
    <section class="home-section">
        <div class="home-title">
            <i class='bx bx-menu'></i>
            <span class="text">Issue Lists</span>
        </div>
        <div class="home-content">
            <div style="display: flex; justify-content: end;">
                <form method="GET" action="{{ route('stockControl#stock_issue#issueListPage') }}">
                    <input type="date" class="form-control w-auto" name="issueDate" 
                        value="{{ request()->query('issueDate')}}"
                        onchange="this.form.submit()">
                </form>
            </div>
            <div id="issue_list_label" class="row align-items-center bg-white mt-3">
                <div class="col-6">
                    <label><i class="fa-solid fa-table-list" style="padding-left:5px; padding-right: 12px"></i>Issue
                        Lists</label>
                </div>
                <div class="col-6" style="text-align: right">
                    <i class="bx bxs-chevron-down arrow"></i>
                </div>
            </div>
            <div class="issue_list_container shadow-sm show_container">
                <table id="issue_list" class="table table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Issue Date</th>
                            <th>Issue Voucher</th>
                            <th>Issue Type</th>
                            <th>Total Quantity</th>
                            <th>Remark</th>
                            <th>View</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody id="issue_data_list">
                        @if (count($stockIssueList) != 0)
                            @foreach ($stockIssueList as $detail)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ date('d-m-Y', strtotime($detail->issue_date)) }}</td>
                                    <td>{{ $detail->issue_voucher_number }}</td>
                                    <td>{{ $detail->issue_type }}</td>
                                    <td>{{ number_format($detail->total_qty) }}</td>
                                    <td>{{ $detail->remark }}</td>
                                    <td>
                                        <a href="{{ route('stockControl#stock_issue#issueDetailsPage', ['id' => $detail->stock_issue_id, 'issueDate' => request()->query('issueDate')]) }}">
                                            <i class="fa-solid fa-eye" style="color: green; cursor: pointer;"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('stockControl#stock_issue#updateStockIssuePage', $detail->stock_issue_id) }}"
                                            id="updateStockIssue" onclick="removeStockIssueItem()">
                                            <i class="fa-solid fa-pen-to-square"
                                                style="color: orange;cursor: pointer;"></i></a>
                                    </td>
                                    <td>
                                        <a onclick='selectStockIssueID({{ $detail->stock_issue_id }},"{{ $detail->issue_voucher_number }}")'
                                            data-bs-toggle="modal" data-bs-target="#myModalStockIssueDelete">
                                            <i class="fa-regular fa-trash-can" style="color: red;cursor: pointer;"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>

            </div>
        </div>
        {{-- Voucher Delete Modal --}}
        <div class="modal fade" id="myModalStockIssueDelete">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <div class="modal-header text-center" style="background-color: #512DA8">
                        <h6 class="modal-title text-white w-100">Are you sure want to delete voucher no: <span class="text-white"
                                id="delete_content"></span> ?</h6>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    {{-- <form action="{{ route('stockControl#stock_issue#issueListPage#deleteStoreIssue') }}" method="post"
                        enctype="multipart/form-data" id="deleteIssueModalForm">
                        @csrf --}}
                        <div class="modal-body" style="margin-left: 20px; margin-right: 20px;">
                            <input type="hidden" name="issue_deleteID" id="issue_deleteID">
                            <input type="text" id="loginUserID" name="loginUserID" value="{{ Auth::User()->id }}"
                                hidden>
                            <div class="row align-items-center mb-3">
                                    <label class="form-label ps-0">Delete Reason</label>

                                    <textarea class="form-control" type="text" id="delete_reason" name="delete_reason"></textarea>
                                    <span class="text-danger">
                                        <span id="delete_reason_error"></span>
                                    </span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input class="btn btn-danger" type="submit" value="Delete" id="issue_delete">
                        </div>
                    {{-- </form> --}}
                </div>
            </div>
        </div>
        {{-- End Voucher Delete --}}
    </section>
    <script src="{{ asset('script/links_js/jquery.3.6.4.min.js') }}"></script>
    <script src="{{ asset('script/links_js/jquery.validate.1.19.5.js') }}"></script>
    <script src="{{ asset('script/links_js/jquery.dataTables.1.13.7.min.js') }}"></script>
    <script src="{{ asset('script/links_js/dataTables.bootstrap5_1.13.7.min.js') }}"></script>
    <script src="{{ asset('script/issue_list_script.js') }}"></script>
    <script>
        new DataTable('#issue_list', {
            scrollX: true,
            columns: [
                {
                    width: "10px"
                },
                {
                    width: "90px"
                },
                {
                    width: "90px"
                },
                {
                    width: "70px"
                },
                {
                    width: "20px"
                },
                {
                    width: "180px"
                },
                {
                    width: "20px"
                },
                {
                    width: "20px"
                },
                {
                    width: "20px"
                },
            ]
        });

        function removeStockIssueItem() {
            localStorage.removeItem('update_issue_detail_list');
        }

        function selectStockIssueID(id, voucherNo) {
            $("#issue_deleteID").val(id);
            document.getElementById("delete_content").innerHTML = voucherNo;
            $('#delete_reason_error').html("");
            $('#delete_reason').removeClass('is-invalid');
        }

        $('#issue_delete').click(function(e) {
            e.preventDefault();

            let issueLog = {
                issue_deleteID: $('#issue_deleteID').val(),
                delete_reason: $('#delete_reason').val(),
                loginUserID: $('#loginUserID').val(),
                _token: $('meta[name="csrf-token"]').attr('content')
            };

            console.log(issueLog);

            var url = '{{ route('stockControl#stock_issue#issueListPage#deleteStoreIssue') }}';
            $.ajax({
                type: 'post',
                url: url,
                data: issueLog,
                success: (response) => {
                    if (response.errors) {
                        $('#delete_reason_error').text(response.errors.delete_reason);
                    } else if (response.success) {
                        location.reload();
                    }
                }
            })
        })

        $('#deleteIssueModalForm').submit(function(e) {
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
                        $('#myModalStockIssueDelete').modal('hide');
                        var url = "{{ route('stockControl#stock_issue#issueListPage') }}";
                        $(location).attr('href', url);
                    }
                }
            });
        });
    </script>
@endsection
