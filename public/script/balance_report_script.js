let reports_list = document.querySelector(".sale-report-right");
if (localStorage.getItem("showMenu")) {
    // category_list.classList.add("showMenu");
    reports_list.classList.add("showMenu");
}
$(document).ready(function () {
    var salesDefaultTable = new DataTable("#sales_report_list_by_default", {
        scrollX: true,
    });

    $("#sales_report_list_by_default_wrapper .dataTables_scroll").addClass(
        "sales_report_list_by_default_print"
    );
    // var salesSearchTable = new DataTable("#sales_report_list_by_search", {
    //     scrollX: true,
    // });

    // $("#byItemSummaryCheck").click(function() {
    //     var byItemSummaryCheck = $("#byItemSummaryCheck").is(":checked");
    //     if (byItemSummaryCheck) {
    //         $(".searchCategoryCheck_div").removeClass("d-none");
    //         $("#bySearchCategory").click(function() {
    //             var bySearchCategory = $("#bySearchCategory").is(":checked");
    //             if (bySearchCategory) {
    //                 $(".selectCategory_div").removeClass("d-none");
    //             } else {
    //                 $(".selectCategory_div").addClass("d-none");
    //             }
    //         });

    //     } else {
    //         $("#bySearchCategory").prop("checked", false);
    //         $(".selectCategory_div").addClass("d-none");
    //         $(".searchCategoryCheck_div").addClass("d-none");
    //     }
    // });

    function loadSelect2Dropdown(url, targetSelector, autoSearchBtn = null) {

        $.ajax({
            type: "GET",
            url: url,
            success: function (data) {

                const $dropdown = $(targetSelector);
                $dropdown.empty();

                // Fill options
                $.each(data, function (key, value) {
                    $dropdown.append(`<option value="${value.id}">${value.name}</option>`);
                });

                // Destroy Select2 if already applied
                if ($dropdown.hasClass("select2-hidden-accessible")) {
                    $dropdown.select2('destroy');
                }

                // Reinitialize Select2
                $dropdown.select2({
                    width: '100%',
                    placeholder: "Select Option",
                    dropdownParent: $('body') // FIX upward dropdown
                });

                // Select first option
                let firstValue = $dropdown.find("option:first").val();
                $dropdown.val(firstValue).trigger("change");

                // Auto-run search button
                if (autoSearchBtn) {
                    $dropdown.on("change", function () {
                        $(autoSearchBtn).click();
                    });
                }
            }
        });
    }
    $("#btn_itemSummary_print").click(function () {

        // ===== Clone & Clean Table =====
        const $tableClone = $("#sales_report_list_by_default").clone();

        // remove datatable styles
        $tableClone
            .removeClass("dataTable no-footer")
            .removeAttr("style");

        $tableClone.find("*").removeAttr("style");

        // ===== Rebuild Header =====
        const headerHtml = `
        <thead>
            <tr>
                <th>No</th>
                <th>Item Name</th>
                <th>Category</th>
                <th>Unit</th>
                <th>Unit Cost</th>
                <th>Sale Price</th>
                <th>Purchased</th>
                <th>Received</th>
                <th>Sold</th>
                <th>Issued</th>
                <th>Balance</th>
                <th>Amount</th>
            </tr>
        </thead>
    `;

        $tableClone.find("thead").remove();
        $tableClone.prepend(headerHtml);

        // ===== Print Wrapper =====
        const $printWrapper = $("<div class='print-area'>");
        $printWrapper.append(`<div class="print-header">Stock Balance Report</div>`);
        $printWrapper.append($tableClone);

        // ===== Shared Print Style =====
        if ($("#print-style").length === 0) {
            $("<style>", {
                id: "print-style",
                text: `
@media print {

    @page { size: A4; margin: 10mm; }

    * {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    body {
        margin: 0;
        font-family: Arial, sans-serif;
    }

    .print-header {
        text-align: center;
        font-size: 15px;
        font-weight: bold;
        margin-bottom: 12px;
        padding: 6px 0;
        color: #512DA8;
    }

    table {
        width: 100% !important;
        border-collapse: collapse !important;
        margin-top: 8px;
    }

    thead { display: table-header-group; }

    thead th {
        background: #512DA8 !important;
        color: #fff !important;
        font-size: 11px;
        padding: 8px;
        border: none !important;
        text-align: center;
    }

    tbody td {
        font-size: 10.5px;
        padding: 8px;
        border-bottom: 1px solid #DDD;
        text-align: center;
    }

    tbody tr:nth-child(even) {
        background: #f2f2f2 !important;
    }

    tr { page-break-inside: avoid; }

    .dataTables_length,
    .dataTables_filter,
    .dataTables_info,
    .dataTables_paginate,
    .table_buttons_container,
    .stock_out_filter {
        display: none !important;
    }
}
`
            }).appendTo("head");
        }

        // ===== Print =====
        $printWrapper.printThis({
            importCSS: true,
            importStyle: true,
            printContainer: true,
            pageTitle: "",
            printDelay: 300,
            afterPrint: function () {
                location.reload();
            }
        });
    });




    // --------------------------------
    // CATEGORY CHECKBOX CLICK (FIXED)
    // --------------------------------
    $("#bySearchCategory").click(function () {

        const isChecked = $(this).is(":checked");

        // Show / hide category div
        if (isChecked) {
            $(".selectCategory_div").removeClass("d-none");
        } else {
            $(".selectCategory_div").addClass("d-none");
            return; // Stop if unchecked
        }

        // Load dropdown using your reusable function
        loadSelect2Dropdown(
            "bindingMenuCategory",
            "#categoryList",
            "#btn_top_sales_search" // auto search button
        );
    });


    // When Item Summary checkbox is clicked
    // $("#byItemSummaryCheck").on("change", function () {
    //     if ($(this).is(":checked")) {
    //         $(".checkByFOC").addClass("d-none"); // hide FOC summary
    //     } else {
    //         $(".checkByFOC").removeClass("d-none"); // show back
    //     }
    // });


    // $("#btn_sales_search").click(function() {
    //     var date = $("#date").val();
    //     var byItemSummaryCheck = $("#byItemSummaryCheck").is(":checked");
    //     var bySearchCategory = $("#bySearchCategory").is(":checked");
    //     var searchCategoryID = bySearchCategory ? $("#categoryList").val() : 0;
    //     var isCheckedItemSummary = byItemSummaryCheck ? 1 : 0;

    //     // build request
    //     $.ajax({
    //         type: "GET",
    //         url: "modifyBalanceReport",
    //         data: {
    //             date: date,
    //             isCheckedItemSummary: isCheckedItemSummary,
    //             searchCategoryID: searchCategoryID,
    //         },
    //         success: function(data) {
    //             // Ensure tbody exists (don't replace the table or thead)
    //             var $tbody = $("#sales_report_list_by_default tbody.body_data");
    //             if ($tbody.length === 0) {
    //                 // If your server returns whole table structure sometimes, ensure a tbody exists
    //                 $("#sales_report_list_by_default").append('<tbody class="body_data"></tbody>');
    //                 $tbody = $("#sales_report_list_by_default tbody.body_data");
    //             }
    //             console.log

    //             // If you used DataTable API, use its rows update (preferred)
    //             // Convert AJAX response into an array of row arrays
    //             var rows = data.map(function(value, idx) {
    //                 return [
    //                     idx + 1,
    //                     value.item_name || '',
    //                     value.menu_category_name || '',
    //                     value.unit_name || '',
    //                     value.weighted_unit_cost ?? '',
    //                     value.sale_price ?? '',
    //                     value.purchased_qty ?? 0,
    //                     value.received_qty ?? 0,
    //                     value.sold_qty ?? 0,
    //                     value.issued_qty ?? 0,
    //                     value.balance_qty ?? 0,
    //                     value.amount ?? 0
    //                 ];
    //             });

    //             // If DataTable instance exists, update it via API to avoid DOM hacks
    //             if (typeof salesDefaultTable !== "undefined" && $.fn.DataTable.isDataTable("#sales_report_list_by_default")) {
    //                 salesDefaultTable.clear();
    //                 salesDefaultTable.rows.add(rows);
    //                 salesDefaultTable.draw(false);
    //             } else {
    //                 // Fallback: populate tbody manually
    //                 $tbody.empty();
    //                 let totalAmount = 0;

    //                 $.each(data, function(key, value) {
    //                     let amount = parseFloat(value.amount);
    //                 if (!isFinite(amount)) amount = 0;
    //     // truncate to integer (you asked for integers)
    //     amount = Math.trunc(amount);

    //     // accumulate total
    //     totalAmount += amount;
    //                     $tbody.append(
    //                         `<tr>
    //                             <td>${key + 1}</td>
    //                             <td>${value.item_name ?? ''}</td>
    //                             <td>${value.menu_category_name ?? ''}</td>
    //                             <td>${value.unit_name ?? ''}</td>
    //                             <td>${value.weighted_unit_cost ?? ''}</td>
    //                             <td>${value.sale_price ?? ''}</td>
    //                             <td>${value.purchased_qty ?? 0}</td>
    //                             <td>${value.received_qty ?? 0}</td>
    //                             <td>${value.sold_qty ?? 0}</td>
    //                             <td>${value.issued_qty ?? 0}</td>
    //                             <td>${value.balance_qty ?? 0}</td>
    //                             <td>${value.amount ?? 0}</td>
    //                         </tr>`
    //                     );
    //                 });

    //                 const formattedTotal = totalAmount.toLocaleString(); // e.g. "1,234"
    //                 console.log(formattedTotal)
    // $('#total_cost').val(formattedTotal);
    //             }
    //         },
    //         error: function(err) {
    //             console.error("Error fetching data", err);
    //         },
    //     });
    // });

    $("#btn_sales_search").click(function () {
        var date = $("#date").val();
        // var byItemSummaryCheck = $("#byItemSummaryCheck").is(":checked");
        var bySearchCategory = $("#bySearchCategory").is(":checked");
        var searchCategoryID = bySearchCategory ? $("#categoryList").val() : 0;
        // var isCheckedItemSummary = byItemSummaryCheck ? 1 : 0;

        // helpers
        function intVal(v) {
            var n = Number(v);
            return Number.isFinite(n) ? Math.round(n) : 0;
        }

        function formatInt(n) {
            return (Number.isFinite(n) ? n : 0).toLocaleString(); // thousands separator
        }

        $.ajax({
            type: "GET",
            url: "balance",
            data: {
                date: date,
                // isCheckedItemSummary: isCheckedItemSummary,
                searchCategoryID: searchCategoryID,
            },
            success: function (data) {
                // Ensure tbody exists (don't replace the table or thead)
                var $tbody = $("#sales_report_list_by_default tbody.body_data");
                if ($tbody.length === 0) {
                    $("#sales_report_list_by_default").append('<tbody class="body_data"></tbody>');
                    $tbody = $("#sales_report_list_by_default tbody.body_data");
                }

                // Build rows array and compute totalAmount (integers)
                var totalAmount = 0;
                // var rows = data.map(function(value, idx) {
                //     // compute integer values for desired fields
                //     var weighted_unit_cost_i = intVal(value.weighted_unit_cost);
                //     var purchased_qty_i = intVal(value.purchased_qty);
                //     var received_qty_i = intVal(value.received_qty);
                //     var sold_qty_i = intVal(value.sold_qty);
                //     var issued_qty_i = intVal(value.issued_qty);
                //     var balance_qty_i = intVal(value.balance_qty);
                //     var batch_number_i = intVal(value.batch_number);
                //     var isp_unit_cost_i = intVal(value.isp_unit_cost);
                //     var total_in_qty_i = intVal(value.total_in_qty ?? (purchased_qty_i + received_qty_i));
                //     var total_out_qty_i = intVal(value.total_out_qty ?? (sold_qty_i + issued_qty_i));
                //     var amount_i = intVal(value.amount);

                //     // accumulate total
                //     totalAmount += amount_i;

                //     // sale_price keep as 2 decimals string (change if you want integers)
                //     var sale_price_fmt = intVal(value.sale_price);

                //     // return an array for DataTable or manual rendering
                //     return [
                //         idx + 1,
                //         value.item_name || '',
                //         value.menu_category_name || '',
                //         value.unit_name || '',
                //         weighted_unit_cost_i,
                //         batch_number_i,
                //         sale_price_fmt,
                //         purchased_qty_i,
                //         received_qty_i,
                //         sold_qty_i,
                //         issued_qty_i,
                //         balance_qty_i,
                //         amount_i
                //     ];
                // });

                var rows = data.map(function (value, idx) {
                    // raw values from server
                    var raw_weighted = value.weighted_unit_cost;
                    var raw_purchased = value.purchased_qty;
                    var raw_received = value.received_qty;
                    var raw_sold = value.sold_qty;
                    var raw_issued = value.issued_qty;
                    var raw_balance = value.balance_qty;
                    var raw_batch = value.batch_number;
                    var raw_isp = value.isp_unit_cost;
                    var raw_sale_price = value.sale_price;
                    var raw_amount = value.amount;

                    // helpers to parse to numbers (use Number rather than custom intVal if you want)
                    function toInt(x) {
                        var n = Number(x);
                        return Number.isFinite(n) ? Math.round(n) : 0;
                    }


                    var weighted_unit_cost_i = intVal(raw_weighted);
                    var purchased_qty_i = toInt(raw_purchased);
                    var received_qty_i = toInt(raw_received);
                    var sold_qty_i = toInt(raw_sold);
                    var issued_qty_i = toInt(raw_issued);
                    var balance_qty_i = toInt(raw_balance);
                    var isp_unit_cost_i = toInt(raw_isp);
                    var sale_price_i = toInt(raw_sale_price);
                    var amount_i = toInt(raw_amount);

                    // decide batch display value: treat 0, "0", null, "", undefined, or letter "o" as "undefined"
                    var batch_display;
                    if (
                        // raw_batch === null ||
                        // raw_batch === undefined ||
                        // String(raw_batch).trim() === '' ||
                        // String(raw_batch).toLowerCase() === 'o' ||
                        String(raw_batch) === '0' ||
                        Number(raw_batch) === 0
                    ) {
                        batch_display = 'undefined';
                    } else {
                        batch_display = raw_batch;
                    }

                    totalAmount += amount_i;

                    return [
                        idx + 1,
                        value.item_name || '',
                        value.menu_category_name || '',
                        value.unit_name || '',
                        weighted_unit_cost_i,
                        // batch_display,            // <-- use display value here (string or number)
                        sale_price_i,
                        purchased_qty_i,
                        received_qty_i,
                        sold_qty_i,
                        issued_qty_i,
                        balance_qty_i,
                        amount_i
                    ];
                });

                // Update DataTable if present
                if (typeof salesDefaultTable !== "undefined" && $.fn.DataTable.isDataTable("#sales_report_list_by_default")) {
                    salesDefaultTable.clear();
                    salesDefaultTable.rows.add(rows);
                    salesDefaultTable.draw(false);

                    // Update total input (format integer with thousands separator)
                    $('#total_cost').val(formatInt(totalAmount));
                } else {
                    // Populate tbody manually and update total
                    $tbody.empty();

                    rows.forEach(function (r) {
                        // r is an array in the same column order - map them into cells
                        $tbody.append(
                            `<tr>
                            <td>${r[0]}</td>
                            <td>${r[1]}</td>
                            <td>${r[2]}</td>
                            <td>${r[3]}</td>
                            <td>${r[4]}</td>
                            <td>${r[5]}</td>
                            <td>${r[6]}</td>
                            <td>${r[7]}</td>
                            <td>${r[8]}</td>
                            <td>${r[9]}</td>
                            <td>${r[10]}</td>
                            <td>${r[11]}</td>
                        </tr>`
                        );
                    });

                    $('#total_cost').val(formatInt(totalAmount));
                }
            },
            error: function (err) {
                console.error("Error fetching data", err);
            },
        });
    });


    // $("#btn_itemSummary_print").click(function() {
    //     var byItemSummaryCheck = $("#byItemSummaryCheck").is(":checked");
    //     var printOptions = {
    //         debug: false,
    //         importCSS: true,
    //         importStyle: false,
    //         printContainer: true,
    //         loadCSS: "",
    //         pageTitle: "Sales Summary Report",
    //         removeInline: false,
    //         printDelay: 1,
    //         header: "Sales Summary Report",
    //         footer: "",
    //         base: false,
    //         formValues: true,
    //         canvas: false,
    //         doctypeString: "",
    //         removeScripts: false,
    //         copyTagClasses: false,
    //         beforePrint: function() {
    //             if (byItemSummaryCheck) {
    //                 $("#sales_report_list_by_search_wrapper thead").empty();
    //                 $("#sales_report_list_by_search thead").empty();
    //                 $("#sales_report_list_by_search thead").html(
    //                     `<tr>
    //                         <th>No</th>
    //                         <th>Voc:</th>
    //                         <th>Item</th>
    //                         <th>Category</th>
    //                         <th>Unit</th>
    //                         <th>Sale Price</th>
    //                         <th>Item Promo</th>
    //                         <th>Qty</th>
    //                         <th>Amount</th>
    //                         <th>FOC</th>
    //                         <th>Ordered By</th>
    //                         <th>Date</th>
    //                     </tr>`
    //                 );
    //                 $("#sales_report_list_by_search th").css(
    //                     "font-size",
    //                     "10px"
    //                 );
    //                 $("#sales_report_list_by_search td").css(
    //                     "font-size",
    //                     "10px"
    //                 );
    //             } else {
    //                 $("#sales_report_list_by_default_wrapper thead").empty();
    //                 $("#sales_report_list_by_default thead").empty();
    //                 $("#sales_report_list_by_default thead").html(
    //                     `<tr>
    //                             <th>No</th>
    //                             <th>Voc:</th>
    //                             <th>Floor</th>
    //                             <th>Table</th>
    //                             <th>Order</th>
    //                             <th>Cus</th>
    //                             <th>Waiter</th>
    //                             <th>Cashier</th>
    //                             <th>Date</th>
    //                             <th>Total</th>
    //                             <th>Promo</th>
    //                             <th>Net</th>
    //                             <th>Paid</th>
    //                             <th>Bal</th>
    //                             <th>Change</th>
    //                             <th>Deli</th>
    //                         </tr>`
    //                 );
    //                 $("#sales_report_list_by_default th").css(
    //                     "font-size",
    //                     "8px"
    //                 );
    //                 $("#sales_report_list_by_default td").css(
    //                     "font-size",
    //                     "8px"
    //                 );
    //             }
    //         },
    //         afterPrint: function() {
    //             if (byItemSummaryCheck) {
    //                 $("#sales_report_list_by_search_wrapper thead").empty();
    //                 $("#sales_report_list_by_search thead").empty();
    //                 $("#sales_report_list_by_search thead").html(
    //                     `<tr>
    //                         <th>No</th>
    //                         <th>Voucher No:</th>
    //                         <th>Item Name</th>
    //                         <th>Category</th>
    //                         <th>Unit</th>
    //                         <th>Sale Price</th>
    //                         <th>Item Promo</th>
    //                         <th>Qty</th>
    //                         <th>Amount</th>
    //                         <th>FOC</th>
    //                         <th>Ordered By</th>
    //                         <th>Order Date</th>
    //                     </tr>`
    //                 );
    //                 $("#sales_report_list_by_search th").css("font-size", "");
    //                 $("#sales_report_list_by_search td").css("font-size", "");
    //             } else {
    //                 $("#sales_report_list_by_default_wrapper thead").empty();
    //                 $("#sales_report_list_by_default thead").empty();
    //                 $("#sales_report_list_by_default thead").html(
    //                     `<tr>
    //                             <th>No</th>
    //                             <th>Voucher No:</th>
    //                             <th>Floor Name</th>
    //                             <th>Table Name</th>
    //                             <th>Table Order No:</th>
    //                             <th>Customer Name</th>
    //                             <th>Waiter Name</th>
    //                             <th>Cashier Name</th>
    //                             <th>Order Date</th>
    //                             <th>Total Amount</th>
    //                             <th>Item Promo</th>
    //                             <th>Net Amount</th>
    //                             <th>Paid Amount</th>
    //                             <th>Balance</th>
    //                             <th>Change</th>
    //                             <th>Delivery Charges</th>
    //                         </tr>`
    //                 );
    //                 $("#sales_report_list_by_default th").css("font-size", "");
    //                 $("#sales_report_list_by_default td").css("font-size", "");
    //             }
    //             location.reload();
    //         },
    //     };

    //     if (byItemSummaryCheck) {
    //         $(".sales_report_list_by_search_print").printThis(printOptions);
    //     } else {
    //         $(".sales_report_list_by_default_print").printThis(printOptions);
    //     }
    // });
    // prepare footer for excel export in sale report
    function formatLabel(key) {
        return key.replace(/_/g, ' ')
            .replace(/\b\w/g, l => l.toUpperCase()); // "total_amount" -> "Total Amount"
    }

    // function addTotalsFooter($table) {
    //     $table.find('tfoot').remove(); // clear any previous footers

    //     const cols = $table.find('thead th').length;
    //     const totals = {
    //         total_cost: $('#total_cost').val() || '0',
    //         total_amount: $('#total_amount').val() || '0',
    //         total_online_payment: $('#total_online_payment').val() || '0',
    //         total_promo: $('#total_promo').val() || '0'
    //     };

    //     const $tfoot = $('<tfoot/>');

    //     $.each(totals, function(key, val) {
    //         const $tr = $('<tr/>');
    //         // label cell spans all but last column so the amount sits in the final column
    //         $tr.append($('<td/>', { colspan: cols - 1, text: formatLabel(key) }));
    //         $tr.append($('<td/>', { text: val, style: 'text-align:start; padding-right:25px' }));
    //         $tfoot.append($tr);
    //     });

    //     $table.append($tfoot);
    //     console.log('test excel');
    // }


    $("#btn_itemSummary_excel").click(function () {

        var byItemSummaryCheck = $("#byItemSummaryCheck").is(":checked");
        var todayDate = new Date();

        let formattedDate = formatDate(todayDate);
        let filename = "BalanceReport-" + formattedDate;

        const selector = byItemSummaryCheck ?
            "#sales_report_list_by_search" :
            "#sales_report_list_by_default";

        const table = $(selector).DataTable();

        const oldLength = table.page.len();

        table.page.len(-1).draw();

        setTimeout(function () {

            // export after showing all rows
            $(selector).tableExport({
                fileName: filename,
                sheetName: "BalanceReport",
                type: "excel",
            });
            table.page.len(oldLength).draw();

        }, 300); // small delay to allow redraw
    });

    $('#balance_pdf_export').click(function (e) {
        e.preventDefault();

        // date (fallback = today)
        const today = new Date().toISOString().slice(0, 10);
        const date = $('#date').val() || today;

        // category filter
        const searchCategoryID = $('#bySearchCategory').is(':checked') ?
            ($('#categoryList').val() || 0) :
            0;

        const url = new URL($(this).attr('href'), window.location.origin);
        url.searchParams.set('date', date);
        url.searchParams.set('searchCategoryID', searchCategoryID);

        window.open(url.toString(), '_blank');
    });


    $('#test_export_link').click(function (e) {
        e.preventDefault();

        const startDate = $('#startDate').val();
        const endDate = $('#endDate').val();

        const isFOCSummary = $('#byFOCCheck').is(':checked') ? 1 : 0;
        const isDiscountSummary = $('#byDiscountCheck').is(':checked') ? 1 : 0;
        const isKPaySummary = $('#byKPayCheck').is(':checked') ? 1 : 0;
        const isDeletedSummary = $('#byDeletedCheck').is(':checked') ? 1 : 0;
        const isCheckedItemSummary = $('#byItemSummaryCheck').is(':checked') ? 1 : 0;

        const searchCategoryID = $('#categoryList').val() || '0';
        const searchStockID = $('#stockItemList').val() || '0';

        const baseUrl = this.href;
        const url = new URL(baseUrl);

        url.searchParams.append('startDate', startDate);
        url.searchParams.append('endDate', endDate);
        url.searchParams.append('isFOCSummary', isFOCSummary);
        url.searchParams.append('isDiscountSummary', isDiscountSummary);
        url.searchParams.append('isKPaySummary', isKPaySummary);
        url.searchParams.append('isDeletedSummary', isDeletedSummary);
        url.searchParams.append('isCheckedItemSummary', isCheckedItemSummary);
        url.searchParams.append('searchCategoryID', searchCategoryID);
        url.searchParams.append('searchStockID', searchStockID);

        window.location.href = url.toString();
    })

    $("#btn_itemSummary_pdf").click(function () {
        var byItemSummaryCheck = $("#byItemSummaryCheck").is(":checked");
        var todayDate = new Date();

        let formattedDate = formatDate(todayDate);
        let filename = "SalesReport-" + formattedDate;

        const selector = byItemSummaryCheck ? "#sales_report_list_by_search" : "#sales_report_list_by_default";
        const $table = $(selector);

        addTotalsFooter($table); // add footer in table

        $table.tableExport({
            type: "pdf",
            fileName: filename,
            jspdf: {
                orientation: "l",
                format: "a4",
                charset: "utf-8",
                margins: { left: 10, right: 10 },
                autotable: {
                    styles: { fillColor: "inherit", textColor: "inherit" },
                    tableWidth: "auto",
                },
            },
        });
        $table.find('tfoot').remove();

        // if (byItemSummaryCheck) {
        //     $("#sales_report_list_by_search").tableExport({
        //         type: "pdf",
        //         fileName: filename,
        //         jspdf: {
        //             orientation: "l",
        //             format: "a4",
        //             charset: "utf-8",
        //             margins: { left: 10, right: 10, top: 20, bottom: 20 },
        //             autotable: {
        //                 styles: { fillColor: "inherit", textColor: "inherit" },
        //                 tableWidth: "auto",
        //             },
        //         },
        //     });
        // } else {
        //     $("#sales_report_list_by_default").tableExport({
        //         type: "pdf",
        //         fileName: filename,
        //         jspdf: {
        //             orientation: "l",
        //             format: "a4",
        //             charset: "utf-8",
        //             margins: { left: 10, right: 10, top: 20, bottom: 20 },
        //             autotable: {
        //                 styles: { fillColor: "inherit", textColor: "inherit" },
        //                 tableWidth: "auto",
        //             },
        //         },
        //     });
        // }
    });

    // Function to format the date as YYYY_MM_DD-HH_MM_SS
    function formatDate(date) {
        let year = date.getFullYear();
        let month = String(date.getMonth() + 1).padStart(2, "0"); // Months are zero-indexed
        let day = String(date.getDate()).padStart(2, "0");
        let hours = String(date.getHours()).padStart(2, "0");
        let minutes = String(date.getMinutes()).padStart(2, "0");
        let seconds = String(date.getSeconds()).padStart(2, "0");
        return `${year}_${month}_${day}-${hours}_${minutes}_${seconds}`;
    }

    function convertDataFormat(dateString) {
        var datePart = dateString.split(" ")[0];

        // Split the date part by "-"
        var parts = datePart.split("-");

        // Create a Date object (months are zero-based, so we subtract 1 from the month)
        var dateObject = new Date(parts[0], parts[1] - 1, parts[2]);

        // Get the day
        var day = dateObject.getDate().toString().padStart(2, "0");

        // Get the month abbreviation
        var monthAbbreviation = dateObject.toLocaleString("default", {
            month: "short",
        });

        // Get the full year
        var year = dateObject.getFullYear();

        // Form the formatted date string
        var formattedDate = day + "-" + monthAbbreviation + "-" + year;
        return formattedDate;
    }
});