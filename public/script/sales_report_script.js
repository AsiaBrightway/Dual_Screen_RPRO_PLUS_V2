let reports_list = document.querySelector(".sale-report-right");
if (localStorage.getItem("showMenu")) {
    // category_list.classList.add("showMenu");
    reports_list.classList.add("showMenu");
}
$(document).ready(function () {

    var salesDefaultTable = new DataTable("#sales_report_list_by_default", {
        scrollX: true,
        scrollY: "46vh",
        scrollCollapse: true,
        initComplete: function () {
            setTimeout(function () {
                $(".loading-overlay").fadeOut(300, function () {
                    $(this).remove();
                });
            }, 300);
        }
    });
    var byFOCCheck = 0;
    var byDiscountCheck = 0;
    var byKPayCheck = 0;
    var byDeletedCheck = 0;
    var searchDeletedOrderNew = 0;
    $("#sales_report_list_by_default_wrapper .dataTables_scroll").addClass(
        "sales_report_list_by_default_print"
    );
    var salesSearchTable = new DataTable("#sales_report_list_by_search", {
        scrollX: true,
        scrollY: "46vh",
        scrollCollapse: true,
    });

    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });

    $("#categoryList, #stockItemList, #employeeList").select2({
        width: "100%",
        placeholder: "Select Option",
        dropdownParent: $('body')
    });

    $("#byItemSummaryCheck").click(function () {
        var byItemSummaryCheck = $("#byItemSummaryCheck").is(":checked");
        if (byItemSummaryCheck) {
            $(".searchCategoryCheck_div").removeClass("d-none");
            $(".searchItemCheck_div").removeClass("d-none");
            $(".searchEmployeeCheck_div").removeClass("d-none");
            $(".searchDeletedOrderCheck_div").removeClass("d-none");

            $("#bySearchCategory").click(function () {
                var bySearchCategory = $("#bySearchCategory").is(":checked");
                if (bySearchCategory) {
                    $("#bySearchStockItem").prop("checked", false);
                    $("#bySearchEmployee").prop("checked", false);
                    $(".selectCategory_div").removeClass("d-none");
                    $(".selectItem_div").addClass("d-none");
                    $(".selectEmployee_div").addClass("d-none");
                } else {
                    $(".selectCategory_div").addClass("d-none");
                }
            });

            $("#bySearchStockItem").click(function () {
                var bySearchStockItem = $("#bySearchStockItem").is(":checked");
                if (bySearchStockItem) {
                    $("#bySearchCategory").prop("checked", false);
                    $("#bySearchEmployee").prop("checked", false);
                    $(".selectItem_div").removeClass("d-none");
                    $(".selectCategory_div").addClass("d-none");
                    $(".selectEmployee_div").addClass("d-none");
                } else {
                    $(".selectItem_div").addClass("d-none");
                }
            });

            $("#bySearchEmployee").click(function () {
                var bySearchEmployee = $("#bySearchEmployee").is(":checked");
                if (bySearchEmployee) {
                    $("#bySearchCategory").prop("checked", false);
                    $("#bySearchStockItem").prop("checked", false);
                    $(".selectEmployee_div").removeClass("d-none");
                    $(".selectCategory_div").addClass("d-none");
                    $(".selectItem_div").addClass("d-none");
                } else {
                    $(".selectEmployee_div").addClass("d-none");
                }
            });

            $("#bySearchDeletedOrderCheck").click(function () {
                var bySearchDeletedOrder = $("#bySearchDeletedOrder").is(":checked");
                if (bySearchDeletedOrder) {
                    $("#bySearchCategory").prop("checked", false);
                    $("#bySearchStockItem").prop("checked", false);
                    searchDeletedOrderNew = 1;
                    // $(".selectEmployee_div").removeClass("d-none");
                    // $(".selectCategory_div").addClass("d-none");
                    // $(".selectItem_div").addClass("d-none");
                } else {
                    searchDeletedOrderNew = 0;
                    // $(".selectEmployee_div").addClass("d-none");
                }
            });

        } else {
            $("#bySearchCategory").prop("checked", false);
            $("#bySearchStockItem").prop("checked", false);
            $("#bySearchEmployee").prop("checked", false);
            $("#bySearchDeletedOrder").prop("checked", false);
            $(".selectCategory_div").addClass("d-none");
            $(".selectItem_div").addClass("d-none");
            $(".selectEmployee_div").addClass("d-none");
            $(".searchCategoryCheck_div").addClass("d-none");
            $(".searchItemCheck_div").addClass("d-none");
            $(".searchEmployeeCheck_div").addClass("d-none");
        }
    });

    $("#byFOCCheck").click(function () {
        var byFOCCategory = $("#byFOCCheck").is(":checked");
        if (byFOCCategory) {
            byFOCCheck = 1;
            byDiscountCheck = 0;
            byKPayCheck = 0;
            byDeletedCheck = 0;
            $("#byDiscountCheck").prop("checked", false);
            $("#byKPayCheck").prop("checked", false);
            $("#byDeletedCheck").prop("checked", false);
        } else {
            byFOCCheck = 0;
        }
    });

    $("#byDiscountCheck").click(function () {
        var byFOCCategory = $("#byDiscountCheck").is(":checked");
        if (byFOCCategory) {
            byDiscountCheck = 1;
            byFOCCheck = 0;
            byKPayCheck = 0;
            byDeletedCheck = 0;
            $("#byFOCCheck").prop("checked", false);
            $("#byKPayCheck").prop("checked", false);
            $("#byDeletedCheck").prop("checked", false);
        } else {
            byDiscountCheck = 0;
        }
    });

    $("#byKPayCheck").click(function () {
        var byFOCCategory = $("#byKPayCheck").is(":checked");
        if (byFOCCategory) {
            byKPayCheck = 1;
            byFOCCheck = 0;
            byDiscountCheck = 0;
            byDeletedCheck = 0;
            $("#byFOCCheck").prop("checked", false);
            $("#byDiscountCheck").prop("checked", false);
            $("#byDeletedCheck").prop("checked", false);
        } else {
            byKPayCheck = 0;
        }
    });

    $("#byDeletedCheck").click(function () {
        var byFOCCategory = $("#byDeletedCheck").is(":checked");
        if (byFOCCategory) {
            byDeletedCheck = 1;
            byFOCCheck = 0;
            byDiscountCheck = 0;
            byKPayCheck = 0;
            $("#byFOCCheck").prop("checked", false);
            $("#byDiscountCheck").prop("checked", false);
            $("#byKPayCheck").prop("checked", false);
            console.log("byDeletedCheck 161", byDeletedCheck);
        } else {
            byDeletedCheck = 0;
            console.log("byDeletedCheck 165", byDeletedCheck);
        }
    });

    $("#bySearchCategory").click(function () {
        var bySearchCategory = $("#bySearchCategory").is(":checked");
        if (bySearchCategory) {
            $.ajax({
                type: "GET",
                url: "bindingMenuCategory",
                data: {
                    bySearchCategory: "bySearchCategory",
                },
                success: function (data) {
                    $("#categoryList").empty();
                    $.each(data, function (key, value) {
                        $("#categoryList").append(
                            '<option value="' +
                            value.id +
                            '">' +
                            value.name +
                            "</option>"
                        );
                    });
                }, // Move the closing parenthesis to here
            });
        }
    });

    $("#bySearchStockItem").click(function () {
        var bySearchStockItem = $("#bySearchStockItem").is(":checked");
        if (bySearchStockItem) {
            $.ajax({
                type: "GET",
                url: "bindingStockItem",
                data: {
                    bySearchStockItem: "bySearchStockItem",
                },
                success: function (data) {
                    $("#stockItemList").empty();
                    $.each(data, function (key, value) {
                        $("#stockItemList").append(
                            '<option value="' +
                            value.id +
                            '">' +
                            value.name +
                            "</option>"
                        );
                    });
                }, // Move the closing parenthesis to here
            });
        }
    });

    // -----------------------------
    // GENERIC DROPDOWN LOADER
    // -----------------------------
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

    // --------------------------------
    // CATEGORY CHECKBOX CLICK
    // --------------------------------
    $("#bySearchCategory").click(function () {
        if ($(this).is(":checked")) {
            loadSelect2Dropdown(
                "bindingMenuCategory",
                "#categoryList",
                "#btn_top_sales_search"
            );
        }
    });

    // --------------------------------
    // STOCK ITEM CHECKBOX CLICK
    // --------------------------------
    $("#bySearchStockItem").click(function () {
        if ($(this).is(":checked")) {
            loadSelect2Dropdown(
                "bindingStockItem",
                "#stockItemList",
                "#btn_top_sales_search"
            );
        }
    });

    // --------------------------------
    // EMPLOYEE CHECKBOX CLICK
    // --------------------------------
    $("#bySearchEmployee").click(function () {
        if ($(this).is(":checked")) {
            loadSelect2Dropdown(
                "bindingEmployee",
                "#employeeList",
                "#btn_top_sales_search"
            );
        }
    });

    // --------------------------------
    // INITIAL SELECT2 LOAD (DEFAULT)
    // --------------------------------
    // $(document).ready(function() {
    //     $("#categoryList, #stockItemList, #employeeList").select2({
    //         width: "100%",
    //         placeholder: "Select Option",
    //         dropdownParent: $('body')
    //     });
    // });

    $("#bySearchDeletedOrder").click(function () {
        var bySearchDeletedOrder = $("#bySearchDeletedOrder").is(":checked");
        if (bySearchDeletedOrder) {
            searchDeletedOrderNew = 1;
        } else searchDeletedOrderNew = 0;
    });

    // When FOC checkboxes are clicked
    $("#byFOCCheck, #byDiscountCheck, #byKPayCheck, #byDeletedCheck").on("change", function () {
        if ($("#byFOCCheck").is(":checked") || $("#byDiscountCheck").is(":checked") || $("#byKPayCheck").is(":checked") || $("#byDeletedCheck").is(":checked")) {
            $(".checkItemSummary").addClass("d-none"); // hide item summary
        } else {
            $(".checkItemSummary").removeClass("d-none"); // show back
        }
    });

    // When Item Summary checkbox is clicked
    $("#byItemSummaryCheck").on("change", function () {
        if ($(this).is(":checked")) {
            $(".checkByFOC").addClass("d-none"); // hide FOC summary
        } else {
            $(".checkByFOC").removeClass("d-none"); // show back
        }
    });

    $("#btn_sales_search").click(function () {
        var $btn = $(this);
        $btn.prop('disabled', true);
        $(".sales_table").append('<div class="loading-overlay"><span>Loading...</span></div>');

        $(".report_by_default_container").addClass("d-none");
        $(".report_by_search_container").removeClass("d-none");

        if ($.fn.DataTable.isDataTable("#sales_report_list_by_search")) {
            salesSearchTable.destroy();
        }
        if ($.fn.DataTable.isDataTable("#sales_report_list_by_default")) {
            salesDefaultTable.destroy();
        }

        var start_date = $("#startDate").val() || new Date().toISOString().split('T')[0];
        var end_date = $("#endDate").val() || new Date().toISOString().split('T')[0];
        var byItemSummaryCheck = $("#byItemSummaryCheck").is(":checked");
        var bySearchCategory = $("#bySearchCategory").is(":checked");
        var bySearchStockItem = $("#bySearchStockItem").is(":checked");
        var bySearchEmployee = $("#bySearchEmployee").is(":checked");
        var bySearchDeletedOrder = $("#bySearchDeletedOrder").is(":checked");
        var searchCategoryID = 0;
        var searchStockID = 0;
        var searchEmployeeID = 0;
        var isCheckedItemSummary = 0;
        var searchDeletedOrderID = $("#bySearchDeletedOrder").is(":checked");;
        // var byFOCCheck = $("#byFOCCheck").is(":checked");
        // var byDiscountCheck = $("#byDiscountCheck").is(":checked");
        // var byKPayCheck = $("#byKPayCheck").is(":checked");
        // var byDeletedCheck = $("#byDeletedCheck").is(":checked");
        // var byDeletedOrderCheck = $("#byDeletedOrderCheck").is(":checked");

        console.log("Start Date:", start_date);
        console.log("End Date:", end_date);
        console.log("By Deleted Summary:", Number(byDeletedCheck));

        if (byItemSummaryCheck) {
            isCheckedItemSummary = 1;

            if (bySearchCategory) {
                searchCategoryID = $("#categoryList").val();
            }
            if (bySearchStockItem) {
                searchStockID = $("#stockItemList").val();
            }
            if (bySearchEmployee) {
                searchEmployeeID = $("#employeeList").val();
            }
            if (bySearchDeletedOrder) {
                searchDeletedOrderID = searchDeletedOrderNew;
                $(".report_by_search_container").empty();
                $(".report_by_search_container").append(
                    '<table id="sales_report_list_by_search" class="display nowrap" style="width:100%;"></table>'
                );

                $("#sales_report_list_by_search").append(
                    `<thead>
                            <tr>
                                <th>No</th>
                                <th>Item Name</th>
                                <th>Category</th>
                                <th>Unit</th>
                                <th>Qty</th>
                            </tr>
                        </thead>
                        <tbody class="body_data">
                        </tbody>`
                );
                $.ajax({
                    type: "GET",
                    url: "salesReportBySearch",
                    data: {
                        startDate: start_date,
                        endDate: end_date,
                        isCheckedItemSummary: isCheckedItemSummary,
                        searchCategoryID: searchCategoryID,
                        searchStockID: searchStockID,
                        searchEmployeeID: searchEmployeeID,
                        searchDeletedOrderID: searchDeletedOrderID,
                    },
                    success: function (data) {
                        $totalAmount = 0;
                        $totalPromo = 0;
                        $.each(data, function (key, value) {
                            $(".body_data").append(
                                `<tr>
                                        <td>${key + 1}</td>
                                        <td style="word-wrap:break-world; white-space:normal;">${value.item_name}</td>
                                        <td>${value.menu_category_name}</td>
                                        <td>${value.unit_name}</td>
                                        <td>${value.quantity}</td>
                                    </tr>`
                            );

                        });
                        $("#total_amount").text(0);
                        $("#total_promo").text(0);
                        $("#total_online_payment").text(0);

                        salesSearchTable = new DataTable(
                            "#sales_report_list_by_search", {
                            scrollX: true,
                            scrollY: "46vh",
                            scrollCollapse: true,
                        }
                        );
                        $(
                            "#sales_report_list_by_search_wrapper .dataTables_scroll"
                        ).addClass("sales_report_list_by_search_print");
                    },
                    error: function (err) {
                        console.error("Error fetching data", err);
                        salesSearchTable.destroy();
                        salesSearchTable = new DataTable(
                            "#sales_report_list_by_search", {
                            scrollX: true,
                            scrollY: "46vh",
                            scrollCollapse: true,
                        }
                        );
                    },
                });
            } else {
                $(".report_by_search_container").empty();
                $(".report_by_search_container").append(
                    '<table id="sales_report_list_by_search" class="display nowrap" style="width:100%;"></table>'
                );

                $("#sales_report_list_by_search").append(
                    `<thead>
                                <tr>
                                    <th>No</th>
                                    <th>Voucher No:</th>
                                    <th>Item Name</th>
                                    <th>Category</th>
                                    <th>Unit</th>
                                    <th>Unit Cost</th>
                                    <th>Sale Price</th>
                                    <th>Item Promo</th>
                                    <th>Qty</th>
                                    <th>Amount</th>
                                    <th>FOC</th>
                                    <th>Order Date</th>
                                </tr>
                            </thead>
                            <tbody class="body_data">
                            </tbody>`
                );
                $.ajax({
                    type: "GET",
                    url: "salesReportBySearch",
                    data: {
                        startDate: start_date,
                        endDate: end_date,
                        isCheckedItemSummary: isCheckedItemSummary,
                        searchCategoryID: searchCategoryID,
                        searchStockID: searchStockID,
                    },
                    success: function (data) {
                        // console.log(data);
                        $totalAmount = 0;
                        $totalPromo = 0;
                        $totalCost = 0;
                        $totalNetAmount = 0;
                        $.each(data.sales_report_list, function (key, value) {
                            $(".body_data").append(
                                `<tr>
                                            <td>${key + 1}</td>
                                            <td>${value.sale_voucher_number}</td>
                                            <td style="word-wrap:break-world; white-space:normal;">${value.item_name}</td>
                                            <td data-toggle="tooltip" title="${value.menu_category_name}" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 105px" >${value.menu_category_name}</td>
                                            <td>${value.unit_name}</td>
                                            <td style="text-align:end; padding-right:25px">${Number(value.unit_cost)}</td>
                                            <td style="text-align:end; padding-right:25px">${Number(value.item_selling_price)}</td>
                                            <td style="text-align:end; padding-right:25px">${value.promotion_price != null ? Number(value.item_selling_price - value.promotion_price) : 0}</td>
                                            <td>${value.quantity}</td>
                                            <td style="text-align:end; padding-right:25px">
                                                ${value.promotion_price != null
                                    ? value.promotion_price * value.quantity
                                    : value.quantity * value.item_selling_price
                                }
                                            </td>
                                            <td>
                                                ${value.is_foc == 0
                                    ? '<input class="form-check-input" type="checkbox" onclick="return false;">'
                                    : '<input class="form-check-input" type="checkbox" checked onclick="return false;">'
                                }
                                            </td>
                                            <td>${convertDataFormat(value.order_time)}</td>
                                        </tr>`
                            );

                            if (value.promotion_price != null) {
                                $totalAmount += parseInt(
                                    value.quantity * value.promotion_price
                                );
                                $totalPromo += parseInt((value.item_selling_price - value.promotion_price) * value.quantity);
                            } else {
                                $totalAmount += parseInt(
                                    value.quantity * value.item_selling_price
                                );
                                $totalPromo += 0;
                            }
                            // $totalCost += parseInt(value.quantity * value.unit_cost);

                            $('[data-toggle="tooltip"]').tooltip();
                        });
                        $total_sale_cost = parseInt(data.total_sale_cost);
                        $total_cash_payment = parseInt(data.total_cash_payment);

                        $('#left-total').hide();
                        $('#total_net_text').text("Total Amount");
                        $('#total_net_amount').text($totalAmount.toLocaleString());
                        $("#total_cost").text($total_sale_cost.toLocaleString());
                        $('#total_net_profit').text(($totalAmount - $total_sale_cost).toLocaleString());
                        salesSearchTable = new DataTable(
                            "#sales_report_list_by_search", {
                            scrollX: true,
                            scrollY: "46vh",
                            scrollCollapse: true,
                        }
                        );
                        $(
                            "#sales_report_list_by_search_wrapper .dataTables_scroll"
                        ).addClass("sales_report_list_by_search_print");

                        $(".loading-overlay").remove();
                    },
                    error: function (err) {
                        console.error("Error fetching data", err);
                        salesSearchTable.destroy();
                        salesSearchTable = new DataTable(
                            "#sales_report_list_by_search", {
                            scrollX: true,
                            scrollY: "46vh",
                            scrollCollapse: true,
                        }
                        );
                    },
                    complete: function () {
                        $btn.prop('disabled', false);
                    }
                });
            }

        } else {
            $(".report_by_default_container").removeClass("d-none");
            $(".report_by_search_container").addClass("d-none");

            $(".report_by_default_container").append(
                '<table id="sales_report_list_by_default" class="display nowrap" style="width:100%;"></table>'
            );

            $("#sales_report_list_by_default").html(
                `<thead>
                    <tr>
                        <th>No</th>
                        <th>Voucher No:</th>
                        <th>Floor Name</th>
                        <th>Table Name</th>
                        <th>Table Order No:</th>                     
                        <th>Total Amount</th>
                        <th>Item Promo</th>
                        <th>Voucher Promo</th>
                        <th>Service</th>
                        <th>Tax</th>
                        <th>Net Amount</th>
                        <th>Cash</th>
                        <th>Online</th>
                        <th>Balance</th>
                        <th>Change</th>
                        <th>Customer Name</th>
                        <th>Waiter Name</th>
                        <th>Cashier Name</th>
                        <th>Order Date</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>`
            );

            $.ajax({
                type: "GET",
                url: "salesReportBySearch",
                data: {
                    startDate: start_date,
                    endDate: end_date,
                    isCheckedItemSummary: 0,
                    searchCategoryID: 0,
                    searchStockID: 0,
                    searchEmployeeID: 0,
                    searchDeletedOrderID: 0,
                    isFOCSummary: byFOCCheck,
                    isDiscountSummary: byDiscountCheck,
                    isKPaySummary: byKPayCheck,
                    isDeletedSummary: byDeletedCheck
                },
                success: function (data) {
                    console.log(data);
                    $totalAmount = 0;
                    $totalPromo = 0;
                    $totalOnlinePayment = 0;

                    $totalServiceCharge = 0;
                    $totalTax = 0;
                    $totalNetAmount = 0;

                    $.each(data.sales_report_list, function (key, value) {
                        $("#sales_report_list_by_default tbody").append(
                            `<tr>
                                <td>${key + 1}</td>
                                <td>${value.sale_voucher_number}</td>
                                <td>${value.floor_name}</td>
                                <td>${value.table_name}</td>
                                <td>${value.table_order_number}</td>                              
                                <td style="text-align:end; padding-right:25px">${Number(value.total_amount)}</td>
                                <td style="text-align:end; padding-right:25px">${Math.abs(Number(value.total_item_promo_amount))}</td>
                                <td style="text-align:end; padding-right:25px">${Number(value.voucher_discount_amount)}</td>
                                <td style="text-align:end; padding-right:25px">${Number(value.service_charges_amount)}</td>
                                <td style="text-align:end; padding-right:25px">${Number(value.tax_amount)}</td>
                                <td style="text-align:end; padding-right:25px">${Number(value.net_amount)}</td>
                                <td style="text-align:end; padding-right:25px">${Number(value.paid_amount)}</td>
                                <td style="text-align:end; padding-right:25px">${Number(value.online_paid)}</td>
                                <td style="text-align:end; padding-right:25px">${Number(value.balance_amount)}</td>
                                <td style="text-align:end; padding-right:25px">${Number(value.change_amount)}</td>
                                <td>${value.customer_name ?? '-----'}</td>
                                <td>${value.waiter_name ?? '-----'}</td>
                                <td data-toggle="tooltip" data-placement="top" title="${value.cashier_name}" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 130px">${value.cashier_name}</td>
                                <td>${convertDataFormat(value.order_date)}</td>
                            </tr>`
                        );

                        $totalAmount += parseInt(value.total_amount);
                        $totalNetAmount += parseInt(value.net_amount);
                        $totalOnlinePayment += parseInt(value.online_paid);

                        if (value.total_item_promo_amount != null && value.total_item_promo_amount != 0) {
                            $totalPromo += Math.abs(parseInt(value.total_item_promo_amount));
                        } else {
                            $totalPromo += 0;
                        }
                        if (value.voucher_discount_amount != null && value.voucher_discount_amount != 0) {
                            $totalPromo += parseInt(value.voucher_discount_amount);
                        } else {
                            $totalPromo += 0;
                        }

                        if (value.service_charges_amount != null && value.service_charges_amount != 0) {
                            $totalServiceCharge += parseInt(value.service_charges_amount);
                        } else {
                            $totalServiceCharge += 0;
                        }

                        if (value.tax_amount != null && value.tax_amount != 0) {
                            $totalTax += parseInt(value.tax_amount);
                        } else {
                            $totalTax += 0;
                        }
                    });

                    $total_sale_cost = parseInt(data.total_sale_cost);
                    $total_cash_payment = parseInt(data.total_cash_payment);
                    console.log("total_cost", $total_sale_cost);

                    $('#left-total').show();
                    $("#total_cost").text($total_sale_cost.toLocaleString());
                    $("#total_amount").text($totalAmount.toLocaleString());
                    $("#total_online_payment").text($totalOnlinePayment.toLocaleString());

                    $('#total_cash_payment').text($total_cash_payment.toLocaleString());
                    $("#total_promo").text(Math.abs($totalPromo).toLocaleString());
                    $('#total_service').text($totalServiceCharge.toLocaleString());
                    $('#total_tax').text($totalTax.toLocaleString());
                    $('#total_net_text').text("Total Net Amount");
                    $('#total_net_amount').text($totalNetAmount.toLocaleString());
                    $('#total_net_profit').text(($totalNetAmount - $total_sale_cost).toLocaleString());
                    salesDefaultTable = new DataTable(
                        "#sales_report_list_by_default", {
                        scrollX: true,
                        scrollY: "46vh",
                        scrollCollapse: true,
                    }
                    );
                    $(
                        "#sales_report_list_by_default_wrapper .dataTables_scroll"
                    ).addClass("sales_report_list_by_default_print");

                    $(".loading-overlay").remove();
                    // --- IMPORTANT: Re-initialize tooltips here ---
                    $('[data-toggle="tooltip"]').tooltip();
                },
                error: function (err) {
                    console.error("Error fetching data", err);
                    salesDefaultTable.destroy();
                    salesDefaultTable = new DataTable(
                        "#sales_report_list_by_default", {
                        scrollX: true,
                        scrollY: "46vh",
                        scrollCollapse: true,
                    }
                    );
                },
                complete: function () {
                    $btn.prop('disabled', false);
                }
            });
        }
    });

    // $("#btn_itemSummary_print").click(function() {
    //     var byItemSummaryCheck = $("#byItemSummaryCheck").is(":checked");

    //     var totalAmount = $("#total_amount").val();
    //     var totalOnlinePayment = $("#total_online_payment").val();
    //     var totalDiscount = $("#total_promo").val();

    //     var printOptions = {
    //         debug: false,
    //         importCSS: true,
    //         importStyle: false,
    //         printContainer: true,
    //         loadCSS: "",
    //         pageTitle: "Sales Summary Report",
    //         removeInline: false,
    //         printDelay: 1,
    //         // header: "Sales Summary Report",
    //         footer:
    //         `<div style="margin-top: 20px; border-top: 0.5px solid #000; padding-top: 10px;">
    //             <table style="width: 100%; font-size: 14px;">
    //                 <tr>
    //                     <td style="text-align: right; padding-right: 20px;">Total Amount :</td>
    //                     <td style="text-align: left; width: 150px;">${totalAmount}</td>
    //                 </tr>
    //                 <tr>
    //                     <td style="text-align: right; padding-right: 20px;">Total Online Payment :</td>
    //                     <td style="text-align: left; width: 150px;">${totalOnlinePayment}</td>
    //                 </tr>
    //                 <tr>
    //                     <td style="text-align: right; padding-right: 20px;">Total Discount :</td>
    //                     <td style="text-align: left; width: 150px;">${totalDiscount}</td>
    //                 </tr>
    //             </table>
    //         </div>`,
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
    //                 // $("#sales_report_list_by_default th:nth-child(17)").hide();
    //                 $("#sales_report_list_by_default td:nth-child(17)").hide();
    //                 $("#sales_report_list_by_default td:nth-child(11)").hide();
    //                 $("#sales_report_list_by_default td:nth-child(5)").hide()

    //                 $("#sales_report_list_by_default_wrapper thead").empty();
    //                 $("#sales_report_list_by_default thead").empty();
    //                 $("#sales_report_list_by_default thead").html(
    //                     `<tr>
    //                             <th>No</th>
    //                             <th>Voc:</th>
    //                             <th>Floor</th>
    //                             <th>Table</th>
    //                             <th>Order</th>
    //                             <th>Waiter</th>
    //                             <th>Cashier</th>
    //                             <th>Date</th>
    //                             <th>Total</th>
    //                             <th>VPromo</th>
    //                             <th>Net</th>
    //                             <th>Paid</th>
    //                             <th>Bal</th>
    //                             <th>Change</th>
    //                         </tr>`
    //                 );
    //                 $("#sales_report_list_by_default th").css(
    //                     "font-size",
    //                     "12px"
    //                 );
    //                 $("#sales_report_list_by_default td").css(
    //                     "font-size",
    //                     "12px"
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
    //                 $("#sales_report_list_by_default td:nth-child(17)").show();
    //                 $("#sales_report_list_by_default td:nth-child(11)").show();
    //                 $("#sales_report_list_by_default td:nth-child(5)").show()

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

    $("#btn_itemSummary_print").click(function () {
        // Gather filter parameters
        var startDate = $("#startDate").val();
        var endDate = $("#endDate").val();
        var byItemSummaryCheck = $("#byItemSummaryCheck").is(":checked") ? 1 : 0;

        // Only get value if checkbox is checked
        var categoryList = $("#bySearchCategory").is(":checked") ? $("#categoryList").val() : 0;
        var stockItemList = $("#bySearchStockItem").is(":checked") ? $("#stockItemList").val() : 0;

        var isFOCSummary = $('#byFOCCheck').is(':checked') ? 1 : 0;
        var isDiscountSummary = $('#byDiscountCheck').is(':checked') ? 1 : 0;
        var isKPaySummary = $('#byKPayCheck').is(':checked') ? 1 : 0;
        var isDeletedSummary = $('#byDeletedCheck').is(':checked') ? 1 : 0;

        // Build URL with query parameters
        var printUrl = "/admin/prints/salesReportPrint" +
            "?startDate=" + startDate +
            "&endDate=" + endDate +
            "&isCheckedItemSummary=" + byItemSummaryCheck +
            "&searchCategoryID=" + categoryList +
            "&searchStockID=" + stockItemList +
            "&isFOCSummary=" + isFOCSummary +
            "&isDiscountSummary=" + isDiscountSummary +
            "&isKPaySummary=" + isKPaySummary +
            "&isDeletedSummary=" + isDeletedSummary;

        // Open in new window
        function printContent(url) {
            var printWindow = window.open(url, '_blank');
            if (printWindow) {
                printWindow.focus();
            } else {
                console.error("Unable to open the print page. Pop-ups might be blocked.");
                alert("Please allow pop-ups for this site.");
            }
        }

        printContent(printUrl);
    });

    $('#excel_export').click(function (e) {
        e.preventDefault();

        const startDate = $('#startDate').val();
        const endDate = $('#endDate').val();

        const isFOCSummary = $('#byFOCCheck').is(':checked') ? 1 : 0;
        const isDiscountSummary = $('#byDiscountCheck').is(':checked') ? 1 : 0;
        const isKPaySummary = $('#byKPayCheck').is(':checked') ? 1 : 0;
        const isDeletedSummary = $('#byDeletedCheck').is(':checked') ? 1 : 0;
        const isCheckedItemSummary = $('#byItemSummaryCheck').is(':checked') ? 1 : 0;

        const searchCategoryID = $("#bySearchCategory").is(":checked") ? $('#categoryList').val() : '0';
        const searchStockID = $("#bySearchStockItem").is(":checked") ? $('#stockItemList').val() : '0';

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

    $('#pdf_export').click(function (e) {
        e.preventDefault();

        // Get values from inputs
        const startDate = $('#startDate').val() || new Date().toISOString().slice(0, 10); // fallback today
        const endDate = $('#endDate').val() || new Date().toISOString().slice(0, 10);

        const isFOCSummary = $('#byFOCCheck').is(':checked') ? 1 : 0;
        const isDiscountSummary = $('#byDiscountCheck').is(':checked') ? 1 : 0;
        const isKPaySummary = $('#byKPayCheck').is(':checked') ? 1 : 0;
        const isDeletedSummary = $('#byDeletedCheck').is(':checked') ? 1 : 0;
        const isCheckedItemSummary = $('#byItemSummaryCheck').is(':checked') ? 1 : 0;

        const searchCategoryID = $('#bySearchCategory').is(':checked') ? $('#categoryList').val() : 0;
        const searchStockID = $('#bySearchStockItem').is(':checked') ? $('#stockItemList').val() : 0;

        // Build URL
        const url = new URL($(this).attr('href'), window.location.origin);
        url.searchParams.set('startDate', startDate);
        url.searchParams.set('endDate', endDate);
        url.searchParams.set('isFOCSummary', isFOCSummary);
        url.searchParams.set('isDiscountSummary', isDiscountSummary);
        url.searchParams.set('isKPaySummary', isKPaySummary);
        url.searchParams.set('isDeletedSummary', isDeletedSummary);
        url.searchParams.set('isCheckedItemSummary', isCheckedItemSummary);
        url.searchParams.set('searchCategoryID', searchCategoryID);
        url.searchParams.set('searchStockID', searchStockID);

        // Redirect to PDF URL
        window.location.href = url.toString();
    });

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
