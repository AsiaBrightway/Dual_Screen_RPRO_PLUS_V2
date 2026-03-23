let reports_list = document.querySelector(".stock-out-report-right");
if (localStorage.getItem("showMenu")) {
    // category_list.classList.add("showMenu");
    reports_list.classList.add("showMenu");
}
$(document).ready(function() {
    $("#byItemSummaryCheck").click(function() {
        var byItemSummaryCheck = $("#byItemSummaryCheck").is(":checked");
        if (byItemSummaryCheck) {
            $(".searchCategoryCheck_div").removeClass("d-none");
            $(".searchItemCheck_div").removeClass("d-none");
            $(".searchIssueTypeCheck_div").removeClass("d-none");

            $("#bySearchCategory").click(function() {
                var bySearchCategory = $("#bySearchCategory").is(":checked");
                if (bySearchCategory) {
                    $("#bySearchStockItem").prop("checked", false);
                    $("#bySearchIssueType").prop("checked", false);
                    $(".selectCategory_div").removeClass("d-none");
                    $(".selectItem_div").addClass("d-none");
                    $(".selectIssueType_div").addClass("d-none");
                } else {
                    $(".selectCategory_div").addClass("d-none");
                }
            });
            $("#bySearchStockItem").click(function() {
                var bySearchStockItem = $("#bySearchStockItem").is(":checked");
                if (bySearchStockItem) {
                    $("#bySearchCategory").prop("checked", false);
                    $("#bySearchIssueType").prop("checked", false);
                    $(".selectItem_div").removeClass("d-none");
                    $(".selectCategory_div").addClass("d-none");
                    $(".selectIssueType_div").addClass("d-none");
                } else {
                    $(".selectItem_div").addClass("d-none");
                }
            });
            $("#bySearchIssueType").click(function() {
                var bySearchIssueType = $("#bySearchIssueType").is(":checked");
                if (bySearchIssueType) {
                    $("#bySearchCategory").prop("checked", false);
                    $("#bySearchStockItem").prop("checked", false);
                    $(".selectIssueType_div").removeClass("d-none");
                    $(".selectCategory_div").addClass("d-none");
                    $(".selectItem_div").addClass("d-none");
                } else {
                    $(".selectIssueType_div").addClass("d-none");
                }
            });
        } else {
            $("#bySearchCategory").prop("checked", false);
            $("#bySearchStockItem").prop("checked", false);
            $(".selectCategory_div").addClass("d-none");
            $(".selectItem_div").addClass("d-none");
            $(".searchCategoryCheck_div").addClass("d-none");
            $(".searchItemCheck_div").addClass("d-none");
            $(".searchIssueTypeCheck_div").addClass("d-none");
        }
    });

    var stockOutDefaultTable = new DataTable(
        "#stock_out_report_list_by_default", {
            scrollX: true,
            columns: [
                { width: "50px" },
                { width: "100px" },
                { width: "100px" },
                { width: "100px" },
                { width: "150px" },
                { width: "250px" },
            ]
        }
    );
    var stockOutSearchTable = new DataTable(
        "#stock_out_report_list_by_search", {
            scrollX: true,
        }
    );
    // Reusable function for all dropdowns
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

    // ✔ FIXED: Only one handler per checkbox

    $("#bySearchCategory").click(function() {
        if ($(this).is(":checked")) {
            loadSelect2Dropdown("bindingMenuCategory", "#categoryList");
        }
    });

    $("#bySearchStockItem").click(function() {
        if ($(this).is(":checked")) {
            loadSelect2Dropdown("bindingStockItem", "#stockItemList");
        }
    });

    $("#bySearchIssueType").click(function() {
        if ($(this).is(":checked")) {
            loadSelect2Dropdown("bindingStockIssue", "#issueTypeList");
        }
    });

    $("#btn_stock_out_search").click(function() {
        $(".report_by_default_container").addClass("d-none");
        $(".report_by_search_container").removeClass("d-none");

        if ($.fn.DataTable.isDataTable("#stock_out_report_list_by_search")) {
            stockOutSearchTable.destroy();
        }
        if ($.fn.DataTable.isDataTable("#stock_out_report_list_by_default")) {
            stockOutDefaultTable.destroy();
        }

        var start_date = $("#startDate").val();
        var end_date = $("#endDate").val();
        var byItemSummaryCheck = $("#byItemSummaryCheck").is(":checked");
        var bySearchCategory = $("#bySearchCategory").is(":checked");
        var bySearchStockItem = $("#bySearchStockItem").is(":checked");
        var bySearchIssueType = $("#bySearchIssueType").is(":checked");
        var searchCategoryID = 0;
        var searchStockID = 0;
        var searchIssueTypeID = 0;
        var isCheckedItemSummary = 0;

        if (byItemSummaryCheck) {
            isCheckedItemSummary = 1;

            if (bySearchCategory) {
                searchCategoryID = $("#categoryList").val();
            }
            if (bySearchStockItem) {
                searchStockID = $("#stockItemList").val();
            }
            if (bySearchIssueType) {
                searchIssueTypeID = $("#issueTypeList").val();
            }

            $(".report_by_search_container").empty();
            $(".report_by_search_container").append(
                '<table id="stock_out_report_list_by_search" class="display nowrap" style="width:100%;"></table>'
            );

            $("#stock_out_report_list_by_search").append(
                `<thead>
                    <tr>
                        <th>No</th>
                        <th>Issue Date</th>
                        <th>Voucher</th>
                        <th>Item Name</th>
                        <th>Category</th>
                        <th>Issue Type</th>
                        <th>Unit</th>
                        <th>Qty</th>
                        <th>Expire Date</th>
                    </tr>
                </thead>
                <tbody class="body_data">
                </tbody>`
            );
            $.ajax({
                type: "GET",
                url: "stockOutReportBySearch",
                data: {
                    startDate: start_date,
                    endDate: end_date,
                    isCheckedItemSummary: isCheckedItemSummary,
                    searchCategoryID: searchCategoryID,
                    searchStockID: searchStockID,
                    searchIssueTypeID: searchIssueTypeID,
                },
                success: function(data) {
                    $totalQuantity = 0;
                    $.each(data, function(key, value) {
                        $(".body_data").append(
                            `<tr>
                                <td>${key + 1}</td>
                                <td>${convertDataFormat(value.issue_date)}</td>
                                <td>${value.issue_voucher_number}</td>
                                <td style="word-wrap:break-world; white-space:normal;">${value.item_name}</td>
                                <td>${value.menu_category_name}</td>
                                <td>${value.issue_type_name}</td>
                                <td>${value.unit_name}</td>
                                <td>${Number(value.quantity)}</td>
                                <td>${convertDataFormat(value.expire_date)}</td>
                            </tr>`
                        );
                        $totalQuantity += parseInt(value.quantity);
                    });
                    $("#total_amount").val($totalQuantity.toLocaleString());
                    stockOutSearchTable = new DataTable(
                        "#stock_out_report_list_by_search", {
                            scrollX: true,
                        }
                    );
                    $(
                        "#stock_out_report_list_by_search_wrapper .dataTables_scroll"
                    ).addClass("stock_out_report_list_by_search_print");
                },
                error: function(err) {
                    console.error("Error fetching data", err);
                    stockOutSearchTable.destroy();
                    stockOutSearchTable = new DataTable(
                        "#stock_out_report_list_by_search", {
                            scrollX: true,
                        }
                    );
                },
            });
        } else {
            $(".report_by_default_container").removeClass("d-none");
            $(".report_by_search_container").addClass("d-none");

            $(".report_by_default_container").append(
                '<table id="stock_out_report_list_by_default" class="display nowrap" style="width:100%;"></table>'
            );

            $("#stock_out_report_list_by_default").html(
                `<thead>
                    <tr>
                        <th>No</th>
                        <th>Issue Date</th>
                        <th>Issue Voucher</th>
                        <th>Issue Type</th>
                        <th>Total Quantity</th>
                        <th>Remark</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>`
            );
            $.ajax({
                type: "GET",
                url: "stockOutReportBySearch",
                data: {
                    startDate: start_date,
                    endDate: end_date,
                    isCheckedItemSummary: 0,
                    searchCategoryID: 0,
                    searchStockID: 0,
                    searchIssueTypeID: 0,
                },
                success: function(data) {
                    $totalQuantity = 0;
                    $.each(data, function(key, value) {
                        $("#stock_out_report_list_by_default tbody").append(
                            `<tr>
                                <td>${key + 1}</td>
                                <td>${convertDataFormat(value.issue_date)}</td>
                                <td>${value.issue_voucher_number}</td>
                                <td>${value.issue_type}</td>
                                <td>${Number(value.total_qty)}</td>
                                <td>${value.remark ? "null" : ""}</td>
                            </tr>`
                        );
                        $totalQuantity += parseInt(value.total_qty);
                    });
                    // $("#total_amount").val($totalAmount.toLocaleString());
                    stockOutDefaultTable = new DataTable(
                        "#stock_out_report_list_by_default", {
                            scrollX: true,
                            columns: [
                                { width: "50px" },
                                { width: "100px" },
                                { width: "100px" },
                                { width: "100px" },
                                { width: "150px" },
                                { width: "250px" },
                            ]
                        }
                    );
                },
                error: function(err) {
                    console.error("Error fetching data", err);
                    stockOutDefaultTable.destroy();
                    stockOutDefaultTable = new DataTable(
                        "#stock_out_report_list_by_default", {
                            scrollX: true,
                        }
                    );
                },
            });
        }
    });

    $("#btn_itemSummary_print").click(function() {
        var byItemSummaryCheck = $("#byItemSummaryCheck").is(":checked");
        var printOptions = {
            debug: false,
            importCSS: true,
            importStyle: false,
            printContainer: true,
            loadCSS: "",
            pageTitle: "Stock Out Summary Report",
            removeInline: false,
            printDelay: 1,
            header: "Stock Out Summary Report",
            footer: "",
            base: false,
            formValues: true,
            canvas: false,
            doctypeString: "",
            removeScripts: false,
            copyTagClasses: false,
            beforePrint: function() {
                // Adjust the table width before printing
                if (byItemSummaryCheck) {
                    $("#stock_out_report_list_by_search_wrapper thead").empty();
                    $("#stock_out_report_list_by_search thead").empty();
                    $("#stock_out_report_list_by_search thead").html(
                        `<tr>
                            <th>No</th>
                            <th>Issue</th>
                            <th>Voc</th>
                            <th>Item</th>
                            <th>Category</th>
                            <th>Type</th>
                            <th>Unit</th>
                            <th>Qty</th>
                            <th>Expire</th>
                        </tr>`
                    );
                    $("#stock_out_report_list_by_search th").css(
                        "font-size",
                        "10px"
                    );
                    $("#stock_out_report_list_by_search td").css(
                        "font-size",
                        "10px"
                    );
                } else {
                    $("#stock_out_report_list_by_default_wrapper thead").empty();
                    $("#stock_out_report_list_by_default thead").empty();
                    $("#stock_out_report_list_by_default thead").html(
                        `<tr>
                            <th>No</th>
                            <th>Issue Date</th>
                            <th>Issue Voucher</th>
                            <th>Issue Type</th>
                            <th>Total Quantity</th>
                            <th>Remark</th>
                        </tr>`
                    );
                    $("#stock_out_report_list_by_default th").css("font-size", "10px");
                    $("#stock_out_report_list_by_default td").css("font-size", "10px");
                }
            },
            afterPrint: function() {
                if (byItemSummaryCheck) {
                    $("#stock_out_report_list_by_search_wrapper thead").empty();
                    $("#stock_out_report_list_by_search thead").empty();
                    $("#stock_out_report_list_by_search thead").html(
                        `<tr>
                            <th>No</th>
                            <th>Issue Date</th>
                            <th>Voucher</th>
                            <th>Item Name</th>
                            <th>Category</th>
                            <th>Issue Type</th>
                            <th>Unit</th>
                            <th>Qty</th>
                            <th>Expire Date</th>
                        </tr>`
                    );
                    $("#stock_out_report_list_by_search th").css("font-size", "");
                    $("#stock_out_report_list_by_search td").css("font-size", "");
                } else {
                    $("#stock_out_report_list_by_default_wrapper thead").empty();
                    $("#stock_out_report_list_by_default thead").empty();
                    $("#stock_out_report_list_by_default thead").html(
                        `<tr>
                            <th>No</th>
                            <th>Issue Date</th>
                            <th>Issue Voucher</th>
                            <th>Issue Type</th>
                            <th>Total Quantity</th>
                            <th>Remark</th>
                        </tr>`
                    );
                    $("#stock_out_report_list_by_default th").css("font-size", "");
                    $("#stock_out_report_list_by_default td").css("font-size", "");
                }
                if (byItemSummaryCheck) {
                    $(".stock_out_report_list_by_search_print").css(
                        "width",
                        ""
                    );
                } else {
                    $("#stock_out_report_list_by_default").css("width", "");
                }
                setTimeout(function() {
                    location.reload();
                }, 5000);
                // location.reload();
            },
        };

        if (byItemSummaryCheck) {
            $(".stock_out_report_list_by_search_print").printThis(printOptions);
        } else {
            $("#stock_out_report_list_by_default").printThis(printOptions);
        }
    });

    $("#btn_itemSummary_excel").click(function() {
        var byItemSummaryCheck = $("#byItemSummaryCheck").is(":checked");
        var todayDate = new Date();

        let formattedDate = formatDate(todayDate);
        let filename = "StockOutReport-" + formattedDate;

        if (byItemSummaryCheck) {
            $("#stock_out_report_list_by_search").tableExport({
                fileName: filename,
                sheetName: "StockOutReport",
                type: "excel",
            });
        } else {
            $("#stock_out_report_list_by_default").tableExport({
                fileName: filename,
                sheetName: "StockOutReport",
                type: "excel",
            });
        }
    });

    $("#btn_itemSummary_pdf").click(function() {
        var byItemSummaryCheck = $("#byItemSummaryCheck").is(":checked");
        var todayDate = new Date();

        let formattedDate = formatDate(todayDate);
        let filename = "StockOutReport-" + formattedDate;

        if (byItemSummaryCheck) {
            $("#stock_out_report_list_by_search").tableExport({
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
            $("#stock_out_report_list_by_default").tableExport({
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
