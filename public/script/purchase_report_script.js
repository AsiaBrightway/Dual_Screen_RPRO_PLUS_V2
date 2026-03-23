let reports_list = document.querySelector(".purchase-report-right");
if (localStorage.getItem("showMenu")) {
    // category_list.classList.add("showMenu");
    reports_list.classList.add("showMenu");
}
$(document).ready(function() {
    var purchaseDefaultTable = new DataTable(
        "#purchase_report_list_by_default", {
            scrollX: true,
            columns: [
                { width: "50px" },
                { width: "130px" },
                { width: "160px" },
                { width: "130px" },
                { width: "120px" },
                { width: "160px" },
                { width: "130px" },
                { width: "80px" },
                { width: "120px" },
                { width: "100px" },
                { width: "120px" },
                { width: "80px" },
                { width: "100px" },
                { width: "250px" },
            ]
        }
    );
    var purchaseSearchTable = new DataTable("#purchase_report_list_by_search", {
        scrollX: true
    });
    $("#purchase_report_list_by_default_wrapper .dataTables_scroll").addClass(
        "purchase_report_list_by_default_print"
    );
    $("#byItemSummaryCheck").click(function() {
        var byItemSummaryCheck = $("#byItemSummaryCheck").is(":checked");
        if (byItemSummaryCheck) {
            $(".searchCategoryCheck_div").removeClass("d-none");
            $(".searchItemCheck_div").removeClass("d-none");
            $(".searchSupplierCheck_div").removeClass("d-none");

            $("#bySearchCategory").click(function() {
                var bySearchCategory = $("#bySearchCategory").is(":checked");
                if (bySearchCategory) {
                    $("#bySearchStockItem").prop("checked", false);
                    $("#bySearchSupplier").prop("checked", false);
                    $(".selectCategory_div").removeClass("d-none");
                    $(".selectItem_div").addClass("d-none");
                    $(".selectSupplier_div").addClass("d-none");
                } else {
                    $(".selectCategory_div").addClass("d-none");
                }
            });
            $("#bySearchStockItem").click(function() {
                var bySearchStockItem = $("#bySearchStockItem").is(":checked");
                if (bySearchStockItem) {
                    $("#bySearchCategory").prop("checked", false);
                    $("#bySearchSupplier").prop("checked", false);
                    $(".selectItem_div").removeClass("d-none");
                    $(".selectCategory_div").addClass("d-none");
                    $(".selectSupplier_div").addClass("d-none");
                } else {
                    $(".selectItem_div").addClass("d-none");
                }
            });
            $("#bySearchSupplier").click(function() {
                var bySearchSupplier = $("#bySearchSupplier").is(":checked");
                if (bySearchSupplier) {
                    $("#bySearchCategory").prop("checked", false);
                    $("#bySearchStockItem").prop("checked", false);
                    $(".selectSupplier_div").removeClass("d-none");
                    $(".selectCategory_div").addClass("d-none");
                    $(".selectItem_div").addClass("d-none");
                } else {
                    $(".selectSupplier_div").addClass("d-none");
                }
            });
        } else {
            $("#bySearchCategory").prop("checked", false);
            $("#bySearchStockItem").prop("checked", false);
            $('#bySearchSupplier').prop("checked", false);
            $(".selectCategory_div").addClass("d-none");
            $(".selectItem_div").addClass("d-none");
            $(".searchCategoryCheck_div").addClass("d-none");
            $(".searchItemCheck_div").addClass("d-none");
            $(".searchSupplierCheck_div").addClass("d-none");
            $(".selectSupplier_div").addClass("d-none");
        }
    });


    function loadSelect2Dropdown(url, targetSelector) {
        $.ajax({
            type: "GET",
            url: url,
            success: function(data) {

                const $dropdown = $(targetSelector);
                $dropdown.empty();

                // Fill dropdown
                $.each(data, function(key, value) {
                    $dropdown.append(
                        `<option value="${value.id}">${value.name}</option>`
                    );
                });

                // Destroy previous Select2 if exists
                if ($dropdown.hasClass("select2-hidden-accessible")) {
                    $dropdown.select2('destroy');
                }

                // Reinitialize Select2
                $dropdown.select2({
                    width: "100%",
                    placeholder: "Select Option",
                    dropdownParent: $('body')
                });

                // Select first option
                $dropdown.val($dropdown.find("option:first").val()).trigger("change");
            }
        });
    }

    // ------------------------------
    // Purchase Search Filters
    // ------------------------------

    // Category
    $("#bySearchCategory").click(function() {
        if ($(this).is(":checked")) {
            loadSelect2Dropdown("bindingMenuCategory", "#categoryList");
        }
    });

    // Item
    $("#bySearchStockItem").click(function() {
        if ($(this).is(":checked")) {
            loadSelect2Dropdown("bindingStockItem", "#stockItemList");
        }
    });

    // Supplier
    $("#bySearchSupplier").click(function() {
        if ($(this).is(":checked")) {
            loadSelect2Dropdown("bindingSupplier", "#supplierList");
        }
    });


    function money(val) {
        return Number(parseFloat(val || 0).toFixed(2));
    }

    $("#btn_purchase_search").click(function() {
        $(".report_by_default_container").addClass("d-none");
        $(".report_by_search_container").removeClass("d-none");

        var start_date = $("#startDate").val();
        var end_date = $("#endDate").val();
        var byItemSummaryCheck = $("#byItemSummaryCheck").is(":checked");
        var bySearchCategory = $("#bySearchCategory").is(":checked");
        var bySearchStockItem = $("#bySearchStockItem").is(":checked");
        var bySearchSupplier = $("#bySearchSupplier").is(":checked");

        var searchCategoryID = 0;
        var searchStockID = 0;
        var searchSupplierID = 0;
        var isCheckedItemSummary = 0;

        if (byItemSummaryCheck) {
            isCheckedItemSummary = 1;
            $("#totalNetAmountWrapper").addClass("d-none");
            if (bySearchCategory) searchCategoryID = $("#categoryList").val();
            if (bySearchStockItem) searchStockID = $("#stockItemList").val();
            if (bySearchSupplier) searchSupplierID = $("#supplierList").val();




            $.ajax({
                type: "GET",
                url: "purchaseReportBySearch",
                data: {
                    startDate: start_date,
                    endDate: end_date,
                    isCheckedItemSummary,
                    searchCategoryID,
                    searchStockID,
                    searchSupplierID,
                },
                success: function(data) {
                    let totalAmount = 0;
                    let totalNetAmount = 0;

                    purchaseSearchTable.clear();

                    $.each(data, function(key, value) {
                        let amount = money(value.amount);

                        totalAmount += amount;
                        totalNetAmount += amount;

                        purchaseSearchTable.row.add([
                            key + 1,
                            convertDataFormat(value.purchase_date),
                            value.purchase_voucher_number,
                            value.supplier_name,
                            value.item_name,
                            value.menu_category_name,
                            value.unit_name,
                            money(value.quantity),
                            money(value.unit_cost),
                            amount,
                            convertDataFormat(value.expire_date)
                        ]);
                    });

                    // 🔹 Table redraw
                    purchaseSearchTable.draw();

                    // 🔹 Totals
                    $("#total_amount").val(totalAmount.toLocaleString());
                    $("#total_net_amount").val(totalNetAmount.toLocaleString());

                    // 🔹 Print class
                    $("#purchase_report_list_by_search_wrapper .dataTables_scroll")
                        .addClass("purchase_report_list_by_search_print");
                },
                error: function(err) {
                    console.error("Error fetching data", err);
                },
            });

        } else {
            $("#totalNetAmountWrapper").removeClass("d-none");
            $(".report_by_default_container").removeClass("d-none");
            $(".report_by_search_container").addClass("d-none");

            $.ajax({
                type: "GET",
                url: "purchaseReportBySearch",
                data: {
                    startDate: start_date,
                    endDate: end_date,
                    isCheckedItemSummary: 0,
                    searchCategoryID: 0,
                    searchStockID: 0,
                    searchSupplierID: 0,
                },
                success: function(data) {
                    let totalAmount = 0;
                    let totalNetAmount = 0;

                    purchaseDefaultTable.clear();

                    $.each(data, function(key, value) {

                        let netAmount =
                            money(value.total_amount) +
                            money(value.transport_charges) +
                            money(value.other_charges) +
                            money(value.tax) -
                            money(value.discount_amount);

                        let balance = netAmount - money(value.paid_amount);
                        balance = balance < 0 ? 0 : balance;

                        totalAmount += money(value.total_amount);
                        totalNetAmount += netAmount;

                        purchaseDefaultTable.row.add([
                            key + 1,
                            convertDataFormat(value.purchase_date),
                            value.purchase_voucher_number,
                            value.supplier_name,
                            money(value.total_amount),
                            money(value.transport_charges),
                            money(value.other_charges),
                            money(value.tax),
                            money(value.discount_amount),
                            netAmount,
                            money(value.paid_amount),
                            balance,
                            convertDataFormat(value.due_date),
                            value.remark ? value.remark : ""
                        ]);
                    });

                    purchaseDefaultTable.draw();

                    $("#total_amount").val(totalAmount.toLocaleString());
                    $("#total_net_amount").val(totalNetAmount.toLocaleString());
                },
                error: function(err) {
                    console.error("Error fetching data", err);
                },
            });

        }
    });

    $("#btn_purchase_search").trigger("click");

    function renderPrintTable(tableId, headerHtml, columnMap) {

        // Reset header
        $(`${tableId}_wrapper thead`).empty();
        $(`${tableId} thead`).html(headerHtml);

        // Rebuild table rows
        $(`${tableId} tbody tr`).each(function() {
            const td = $(this).find("td");

            let rowHtml = "";
            columnMap.forEach(i => {
                rowHtml += `<td>${td.eq(i).html()}</td>`;
            });

            $(this).html(rowHtml);
        });

        // Table base style
        $(tableId).css({
            "border-collapse": "collapse",
            "width": "100%"
        });

        // Cell style - remove vertical borders, keep horizontal
        $(`${tableId} th, ${tableId} td`).css({
            "font-size": "8px",
            "padding": "4px 6px",
            "white-space": "nowrap",

            "border-top": "none",
            "border-bottom": "none",
            "border-left": "none",
            "border-right": "none"
        });

        $(`${tableId} th`).css({
            "font-weight": "600",
            "text-align": "left",
            "color": "#fff",
            "padding": "8px",
            "background": "#512DA8"
        });
        $(`${tableId} td`).css({
            "border-bottom": "1px solid #DDD",
            "padding": "8px",
        });
    }


    $("#btn_itemSummary_print").click(function() {

        const byItemSummaryCheck = $("#byItemSummaryCheck").is(":checked");

        if ($("#print-style").length === 0) {
            $("<style>", {
                id: "print-style",
                text: `
@media print {

    @page {
        size: A4;
        margin: 10mm;
    }

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
        border-bottom: 1px solid #fa1dfa;
        color: #512DA8;
    }

    table {
        width: 100% !important;
        border-collapse: collapse;
        margin-top: 8px;
    }

    thead th {
        background: #512DA8 !important;
        color: #ffffff !important;
        font-size: 11px;
        padding: 6px;
        border: 1px solid #000;
        text-align: center;
    }

    tbody td {
        font-size: 10.5px;
        padding: 5px;
        border: 1px solid #000;
    }

    tbody tr:nth-child(even) {
        background: #f2f2f2 !important;
    }

    tfoot td {
        font-weight: bold;
        border-top: 2px solid #000;
        padding: 6px;
    }
}
`
            }).appendTo("head");
        }


        const printOptions = {
            importCSS: true,
            importStyle: true,
            printContainer: true,
            pageTitle: "",
            header: `<div class="print-header">Purchase Summary Report</div>`,

            beforePrint: function() {

                if (byItemSummaryCheck) {

                    renderPrintTable(
                        "#purchase_report_list_by_search",
                        `
                    <tr>
                        <th>No</th>
                        <th>Voucher No</th>
                        <th>Supplier Name</th>
                        <th>Purchase Date</th>
                        <th>Expire Date</th>
                        <th>Item Name</th>
                        <th>Category</th>
                        <th>Unit</th>
                        <th>Qty</th>
                        <th>Unit Cost</th>
                        <th>Amount</th>
                    </tr>
                    `, [0, 2, 3, 1, 10, 4, 5, 6, 7, 8, 9]
                    );

                } else {

                    renderPrintTable(
                        "#purchase_report_list_by_default",
                        `
                    <tr>
                        <th>No</th>
                        <th>Voucher No</th>
                        <th>Supplier Name</th>
                        <th>Purchase Date</th>
                        <th>Due Date</th>
                        <th>Total Amount</th>
                        <th>Transport Charges</th>
                        <th>Other Charges</th>
                        <th>Tax</th>
                        <th>Discount</th>
                        <th>Paid Amount</th>
                        <th>Balance</th>
                        <th>Net Amount</th>
                        <th>Remark</th>
                    </tr>
                    `, [0, 2, 3, 1, 12, 4, 5, 6, 7, 8, 10, 11, 9, 13]
                    );
                }
            },

            afterPrint: function() {
                location.reload();
            }
        };

        if (byItemSummaryCheck) {
            $(".purchase_report_list_by_search_print").printThis(printOptions);
        } else {
            $("#purchase_report_list_by_default").printThis(printOptions);
        }
    });


    $('#purchase_excel_export').click(function(e) {
        e.preventDefault();

        const startDate = $('#startDate').val();
        const endDate = $('#endDate').val();

        const isCheckedItemSummary = $('#byItemSummaryCheck').is(':checked') ? 1 : 0;

        const searchCategoryID = $("#bySearchCategory").is(":checked") ?
            $('#categoryList').val() :
            0;

        const searchStockID = $("#bySearchStockItem").is(":checked") ?
            $('#stockItemList').val() :
            0;

        const searchSupplierID = $("#bySearchSupplier").is(":checked") ?
            $('#supplierList').val() :
            0;

        const baseUrl = this.href;
        const url = new URL(baseUrl);

        url.searchParams.append('startDate', startDate);
        url.searchParams.append('endDate', endDate);
        url.searchParams.append('isCheckedItemSummary', isCheckedItemSummary);
        url.searchParams.append('searchCategoryID', searchCategoryID);
        url.searchParams.append('searchStockID', searchStockID);
        url.searchParams.append('searchSupplierID', searchSupplierID);

        console.log('Excel Export SupplierID:', searchSupplierID);

        window.location.href = url.toString();
    });



    $("#btn_itemSummary_excel").click(function() {
        var byItemSummaryCheck = $("#byItemSummaryCheck").is(":checked");
        var todayDate = new Date();

        let formattedDate = formatDate(todayDate);
        let filename = "PurchaseReport-" + formattedDate;

        if (byItemSummaryCheck) {
            $("#purchase_report_list_by_search").tableExport({
                fileName: filename,
                sheetName: "PurchaseReport",
                type: "excel",
            });
        } else {
            $("#purchase_report_list_by_default").tableExport({
                fileName: filename,
                sheetName: "PurchaseReport",
                type: "excel",
            });
        }
    });
    $('#purchase_pdf_export').click(function(e) {
        e.preventDefault();

        // Get dates (fallback to today)
        const today = new Date().toISOString().slice(0, 10);
        const startDate = $('#startDate').val() || today;
        const endDate = $('#endDate').val() || today;

        // Item summary
        const isCheckedItemSummary = $('#byItemSummaryCheck').is(':checked') ? 1 : 0;

        // Filters
        const searchCategoryID = $('#bySearchCategory').is(':checked') ?
            ($('#categoryList').val() || 0) :
            0;

        const searchStockID = $('#bySearchStockItem').is(':checked') ?
            ($('#stockItemList').val() || 0) :
            0;

        const searchSupplierID = $('#bySearchSupplier').is(':checked') ?
            ($('#supplierList').val() || 0) :
            0;

        // Build URL (href should be /reports/purchase/pdf)
        const url = new URL($(this).attr('href'), window.location.origin);

        url.searchParams.set('startDate', startDate);
        url.searchParams.set('endDate', endDate);
        url.searchParams.set('isCheckedItemSummary', isCheckedItemSummary);
        url.searchParams.set('searchCategoryID', searchCategoryID);
        url.searchParams.set('searchStockID', searchStockID);
        url.searchParams.set('searchSupplierID', searchSupplierID);

        // Redirect to PDF
        window.location.href = url.toString();
    });


    $("#btn_itemSummary_pdf").click(function() {
        var byItemSummaryCheck = $("#byItemSummaryCheck").is(":checked");
        var todayDate = new Date();

        let formattedDate = formatDate(todayDate);
        let filename = "PurchaseReport-" + formattedDate;

        if (byItemSummaryCheck) {
            $("#purchase_report_list_by_search").tableExport({
                type: "pdf",
                fileName: filename,
                jspdf: {
                    orientation: "l",
                    format: "a4",
                    charset: "utf-8",
                    margins: { left: 10, right: 10, top: 20, bottom: 20 },
                    autotable: {
                        styles: { fillColor: "inherit", textColor: "inherit" },
                        tableWidth: "auto",
                    },
                },
            });
        } else {
            $("#purchase_report_list_by_default").tableExport({
                type: "pdf",
                fileName: filename,
                jspdf: {
                    orientation: "l",
                    format: "a4",
                    charset: "utf-8",
                    margins: { left: 10, right: 10, top: 20, bottom: 20 },
                    autotable: {
                        styles: { fillColor: "inherit", textColor: "inherit" },
                        tableWidth: "auto",
                    },
                },
            });
        }
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
