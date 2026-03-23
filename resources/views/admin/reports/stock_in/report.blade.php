@extends('layouts.admin.master')
@section('title', 'Stock-In Reports')
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.dataTables.css">

@section('content')
    <section class="home-section">
        <div class="home-title">
            <i class='bx bx-menu'></i>
            <span class="text">Stock-In Reports</span>
        </div>
        <div class="home-content">
            <div class="row">
                <div class="col-3">
                    <div class="stock_in_report_filter_container shadow-sm">
                        <form class="stock_in_report_filter_form" id="stock_in_report_filter_form">
                            <div class="border border-1 p-3 rounded-2">
                                <label class="mb-3 text-primary">Date Info</label>
                                <div class="mb-2">
                                    <label class="form-label">Start Date</label>
                                    <input type="date" class="form-control" id="startDate">
                                </div>
                                <div class="mb-2">
                                    <label class="form-label">End Date</label>
                                    <input type="date" class="form-control" id="endDate">
                                </div>
                            </div>
                            <div class="border border-1 p-3 mt-3 rounded-2">
                                <label class="mb-3 text-primary">Serach By Item Summary</label>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="byItemSummaryCheck">
                                    <label class="form-check-label">By Item Summary</label>
                                </div>
                            </div>
                            <div class="border border-1 p-3 mt-3 rounded-2 d-none" id="search_group">
                                <label class="mb-3 text-primary">Serach By</label>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="bySearchCategory"
                                        name="bySearchCategory">
                                    <label class="form-check-label">By Category</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="bySearchStockItem"
                                        name="bySearchStockItem">
                                    <label class="form-check-label">By Stock Item</label>
                                </div>
                                <select class="form-select mb-2" id="searchBy" name="searchBy">
                                    <option value="0">--Select--</option>
                                </select>
                            </div>
                            {{-- <div style="display: flex; justify-content:end">
                                <button class="btn custom_btn" id="btn_StockReceiveSearch">Search</button>
                            </div> --}}
                        </form>
                        <div style="display: flex; justify-content:end">
                            <button class="btn custom_btn" id="btn_StockReceiveSearch">Search</button>
                        </div>
                    </div>
                </div>
                <div class="col-9">
                    <div class="stock_in_report_container shadow-sm" id="stock_in_report_container">
                        <center>
                            <button class="btn btn-primary" id="btn_summary_print"><i class="fa-solid fa-print"></i>
                                Print</button>
                            <button class="btn btn-success" id="btn_summary_export"><i class="fa-solid fa-file-excel"></i>
                                Excel</button>
                            <button class="btn btn-danger" id="btn_summary_pdf"><i class="fa-solid fa-file-pdf"></i>
                                PDF</button>
                        </center>
                        {{-- <button class="btn btn-danger float-end m-1" id="btn_summary_pdf"><i class="fa-solid fa-file-pdf" style="padding-right: 5px"></i> PDF</button>
                        <button class="btn btn-success float-end m-1" id="btn_summary_export"><i class="fa-solid fa-file-excel" style="padding-right: 5px"></i> Excel</button>
                        <button class="btn btn-primary float-end m-1" id="btn_summary_print"><i class="fa-solid fa-print" style="padding-right: 5px"></i> Print</button> --}}

                        <table id="stock_in_report_list" class="table table-striped nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Receive Date</th>
                                    <th>Receive Voucher</th>
                                    <th>Total Amount</th>
                                    <th>Remark</th>
                                </tr>
                            </thead>
                            <tbody id="stock_in_report_data">
                                @if (count($stock_receive_list) != 0)
                                    @foreach ($stock_receive_list as $detail)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ date('d-m-Y', strtotime($detail->receive_date)) }}</td>
                                            <td>{{ $detail->receive_voucher_number }}</td>
                                            <td>{{ number_format($detail->total_amount) }}</td>
                                            <td>{{ $detail->remark }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>

                        </table>
                        <h5 class="text-end" style="color:#512DA8">Total : <span id="receiveSummaryTotal">0</span></h5>
                    </div>

                    <div class="stock_in_report_container shadow-sm d-none" id="stock_in_byItem_report_container">
                        {{-- <button class="btn btn-danger float-end m-1" id="btn_itemSummary_pdf"><i class="fa-solid fa-file-pdf"></i> PDF</button>
                        <button class="btn btn-success float-end m-1" id="btn_itemSummary_export"><i class="fa-solid fa-file-excel"></i> Excel</button>
                        <button class="btn btn-primary float-end m-1" id="btn_itemSummary_print"><i class="fa-solid fa-print"></i> Print</button> --}}
                        <center>
                            <button class="btn btn-primary" id="btn_itemSummary_print"><i class="fa-solid fa-print"></i>
                                Print</button>
                            <button class="btn btn-success" id="btn_itemSummary_export"><i
                                    class="fa-solid fa-file-excel"></i> Excel</button>
                            <button class="btn btn-danger" id="btn_itemSummary_pdf"><i class="fa-solid fa-file-pdf"></i>
                                PDF</button>
                        </center>
                        <table id="stock_in_report_list_byItem" class="table table-striped nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Receive Date</th>
                                    <th>Receive Voucher</th>
                                    <th>Item Name</th>
                                    <th>Unit</th>
                                    <th>Qty</th>
                                    <th>Unit Cost</th>
                                    <th>Amount</th>
                                    <th>Expire Date</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                        <h5 class="text-end" style="color:#512DA8">Total : <span id="receiveItemSummaryTotal">0</span></h5>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- <script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.js"></script> --}}
    {{-- <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.dataTables.js"></script> --}}
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script> --}}
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script> --}}
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script> --}}
    {{-- <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.html5.min.js"></script> --}}
    {{-- <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.print.min.js"></script> --}}
    {{-- <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script> --}}

    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.14.0/jquery.validate.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.min.js"></script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/printThis/1.15.0/printThis.min.js"></script>
    <script src="https://cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.0.0/jspdf.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tableexport.jquery.plugin@1.30.0/tableExport.min.js"></script>
    <script>
        new DataTable('#stock_in_report_list', {
            scrollX: true,
            scrollY: '400px',
            scrollCollapse: true,
        });

        new DataTable('#stock_in_report_list_byItem', {
            scrollX: true,
            scrollY: '400px',
            scrollCollapse: true,
        });
        //   new DataTable('#stock_in_report_list', {
        //         layout: {
        //             topStart: {
        //                 // dom: 'Bfrtip',
        //                 buttons: ['csv', 'excel', 'pdf', 'print'],
        //                 buttons: [
        //                     {
        //                         extend: 'print',
        //                         text: '<i class="fa-solid fa-print" style="padding-right: 5px"></i> Print',
        //                         titleAttr: 'Copy'
        //                     },
        //                     {
        //                         extend: 'excel',
        //                         text: '<i class="fa-solid fa-file-excel" style="padding-right:5px"></i> Excel',
        //                         titleAttr: 'Excel'
        //                     },
        //                     {
        //                         extend: 'csv',
        //                         text: '<i class="fa-solid fa-file-csv" style="padding-right:5px"></i> CSV',
        //                         titleAttr: 'CSV'
        //                     },
        //                     {
        //                         extend: 'pdf',
        //                         text: '<i class="fa-solid fa-file-pdf" style="padding-right:5px"></i> PDF',
        //                         titleAttr: 'PDF'
        //                     }
        //                 ]
        //             },
        //             customize: function (win) {
        //                 $(win.document.body).find('table').addClass('print_summary');
        //             },
        //         }
        //     });

        var today = new Date();
        var now = today.getFullYear() + '-' + ('0' + (today.getMonth() + 1)).slice(-2) + '-' + ('0' + today.getDate())
            .slice(-2);
        $('#startDate').val(now);
        $('#endDate').val(now);

        $('#byItemSummaryCheck').click(function() {
            var itemCheck = $('#byItemSummaryCheck').is(":checked");
            if (itemCheck) {
                $('#search_group').removeClass('d-none');
                $('#bySearchStockItem').prop("checked", false);
                $('#bySearchCategory').prop('checked', false);
                $('#searchBy').empty();
                $('#searchBy').append('<option value="' + 1 + '">' + '--Select--' + '</option>');
            } else {
                $('#search_group').addClass('d-none');
            }
        })

        $(document).on("click", "#bySearchCategory:checkbox, #bySearchStockItem:checkbox", function() {
            $('#searchBy').empty();
            var chk_checked = $(this).is(":checked");
            var chk_name = $(this).attr('name');
            var checknameList = ["bySearchCategory", "bySearchStockItem"];
            // console.log([chk_checked,chk_name]);
            if ($('#byItemSummaryCheck').is(":checked") && chk_checked && chk_name == checknameList[0]) {
                $('#bySearchStockItem').prop("checked", false);
            } else if ($('#byItemSummaryCheck').is(":checked") && chk_checked && chk_name == checknameList[1]) {
                $('#bySearchCategory').prop('checked', false);
            } else {
                $('#searchBy').append('<option value="' + 1 + '">' + '--Select--' + '</option>');
            }

            if (chk_checked && chk_name != '') {
                var url = (chk_name == checknameList[0]) ? '../reports/bindingMenuCategory' :
                    '../reports/bindingStockItem';
                $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'json',
                    success: function(res) {
                        // console.log(res.success);
                        $('#searchBy').append('<option value="' + 0 + '">' + '--Select--' +
                        '</option>');
                        $.each(res.success, function(key, value) {
                            $('#searchBy').append('<option value="' + value.id + '">' + value
                                .name + '</option>');
                        });
                    }
                });
            }
        });

        $("#btn_StockReceiveSearch").click(function(e) {
            var url = '../reports/stockInReportByFilter';
            var search_id = $('#searchBy :selected').val();
            $filter_data = {
                start_date: $('#startDate').val(),
                end_date: $('#endDate').val(),
                categoryID: 0,
                itemID: 0,
                is_itemSummary: false
            };
            if ($('#byItemSummaryCheck').is(":checked") && $('#bySearchCategory').is(":checked") && search_id > 0) {
                $filter_data.categoryID = search_id;
                $filter_data.is_itemSummary = true;
                $('#stock_in_report_container').addClass('d-none');
                $('#stock_in_byItem_report_container').removeClass('d-none');
            } else if ($('#byItemSummaryCheck').is(":checked") && $('#bySearchStockItem').is(":checked") &&
                search_id > 0) {
                $filter_data.itemID = search_id;
                $filter_data.is_itemSummary = true;
                $('#stock_in_report_container').addClass('d-none');
                $('#stock_in_byItem_report_container').removeClass('d-none');
            } else if ($('#byItemSummaryCheck').is(":checked")) {
                $filter_data.is_itemSummary = true;
                $('#stock_in_report_container').addClass('d-none');
                $('#stock_in_byItem_report_container').removeClass('d-none');
            } else {
                $('#stock_in_byItem_report_container').addClass('d-none');
                $('#stock_in_report_container').removeClass('d-none');
            }

            $.ajax({
                url: url,
                type: 'GET',
                data: $filter_data,
                dataType: 'json',
                success: function(res) {
                    console.log(res.errors);
                    // console.log(res.success);
                    if (res.success[0] != null && res.success[1] == null) {
                        console.log(res.success[0]);
                        $('#stock_in_report_list tbody>tr').empty();
                        $total = 0;
                        $.each(res.success[0], function(key, value) {
                            // console.log(value);
                            var receive_date = new Date(value.receive_date);
                            $('#stock_in_report_list').append(
                                `<tr><td>${key+1}</td><td>${('0' + receive_date.getDate()).slice(-2)+ '-' + ('0' + (receive_date.getMonth() + 1)).slice(-2) + '-' + receive_date.getFullYear()}</td>
                        <td>${value.receive_voucher_number}</td><td>${parseFloat(value.total_amount).toLocaleString()}</td><td>${value.remark == null?'':value.remark}</td></tr>`
                                );
                            $total += parseFloat(value.total_amount.toLocaleString());
                        });
                        $('#receiveSummaryTotal').text($total.toLocaleString());
                    } else {
                        console.log(res.success[1]);
                        $total = 0;
                        $('#stock_in_report_list_byItem tbody>tr').empty();
                        $.each(res.success[1], function(key, value) {
                            // console.log(value);
                            var receive_date = new Date(value.receive_date);
                            var expire_date = new Date(value.expire_date);
                            $('#stock_in_report_list_byItem').append(
                                `<tr><td>${key+1}</td><td>${('0' + receive_date.getDate()).slice(-2)+ '-' + ('0' + (receive_date.getMonth() + 1)).slice(-2) + '-' + receive_date.getFullYear()}</td>
                        <td>${value.receive_voucher_number}</td><td>${value.item_name}</td><td>${value.unit_name}</td>
                        <td>${parseFloat(value.quantity)}</td><td>${parseFloat(value.unit_cost).toLocaleString()}</td><td>${parseFloat(value.amount).toLocaleString()}</td>
                        <td>${('0' + expire_date.getDate()).slice(-2)+ '-' + ('0' + (expire_date.getMonth() + 1)).slice(-2) + '-' + expire_date.getFullYear()}</td></tr>`
                                );
                            $total += parseFloat(value.amount);
                        });
                        $('#receiveItemSummaryTotal').text($total.toLocaleString());
                    }
                }
            });
        });

        $("#btn_summary_print").click(function() {
            $("#stock_in_report_list").printThis({
                debug: false,
                importCSS: true,
                importStyle: false,
                printContainer: true,
                loadCSS: "",
                pageTitle: "",
                removeInline: false,
                printDelay: 1,
                header: "Receive Summary Report",
                footer: "",
                base: false,
                formValues: true,
                canvas: false,
                doctypeString: "",
                removeScripts: false,
                copyTagClasses: false
            });
        });

        $("#btn_itemSummary_print").click(function() {
            $("#stock_in_report_list_byItem").printThis({
                debug: false,
                importCSS: true,
                importStyle: false,
                printContainer: true,
                loadCSS: "",
                pageTitle: "Receive Item Summary Report",
                removeInline: false,
                printDelay: 1,
                header: "Receive Item Summary Report",
                footer: "",
                base: false,
                formValues: true,
                canvas: false,
                doctypeString: "",
                removeScripts: false,
                copyTagClasses: false
            });
        });

        // $('#stock_in_report_list').DataTable().draw();
        // $('#stock_in_report_list_byItem').DataTable().draw();

        // $('#stock_in_report_list_wrapper button.buttons-print').click(function(){
        //     console.log("Testing");
        //     // $('#stock_in_report_list').DataTable().draw();
        //     // $("#stock_in_report_list").printThis();
        //     $('#stock_in_report_list').printThis();
        // })

        $('#btn_summary_export').click(function() {
            $('#stock_in_report_list').table2excel({
                exclude: ".noExl",
                sheetName: "receiveSummaryReport",
                filename: "receiveSummaryReport" + new Date().toISOString().replace(/[\-\:\.]/g, "") +
                    ".xls",
                fileext: ".xls",
                preserveFont: true,
            });
        })

        $('#btn_itemSummary_export').click(function() {
            let filename = "receiveItemSummaryReport" + new Date().toISOString().replace(/[\-\:\.]/g, "");
            $('#stock_in_report_list_byItem').tableExport({
                fileName: filename,
                sheetName: "receiveItemSummaryReport",
                type: 'excel'
            });
            // $('#stock_in_report_list_byItem').tableExport({type:'csv'});
        })

        $('#btn_summary_pdf').click(function() {
            let filename = "receiveSummaryReport" + new Date().toISOString().replace(/[\-\:\.]/g, "");
            $('#stock_in_report_list').tableExport({
                type: 'pdf',
                fileName: filename,
                jspdf: {
                    orientation: 'l',
                    format: 'a4',
                    charset: 'utf-8',
                    margins: {
                        left: 10,
                        right: 10,
                        top: 20,
                        bottom: 20
                    },
                    autotable: {
                        styles: {
                            fillColor: 'inherit',
                            textColor: 'inherit'
                        },
                        tableWidth: 'auto'
                    }
                }
            });
        })

        $('#btn_itemSummary_pdf').click(function() {
            let filename = "receiveItemSummaryReport" + new Date().toISOString().replace(/[\-\:\.]/g, "");
            $('#stock_in_report_list_byItem').tableExport({
                type: 'pdf',
                fileName: filename,
                jspdf: {
                    orientation: 'l',
                    format: 'a4',
                    charset: 'utf-8',
                    margins: {
                        left: 10,
                        right: 10,
                        top: 20,
                        bottom: 20
                    },
                    autotable: {
                        styles: {
                            fillColor: 'inherit',
                            textColor: 'inherit'
                        },
                        tableWidth: 'auto'
                    }
                }
            });
        })
    </script>
@endsection
