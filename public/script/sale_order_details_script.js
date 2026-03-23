new DataTable("#sale_order_details_list", {
	    scrollX: true,
});

//sale_order_details_info_container
let sale_order_details_info_container = document.querySelector(
    ".sale_order_details_info_container"
);
if (localStorage.getItem("showOrderInvoiceInfoContainer")) {
    sale_order_details_info_container.classList.add("show_container");
}

//sale_order_details_list_info_container
let sale_order_details_list_info_container = document.querySelector(
    ".sale_order_details_list_info_container"
);
if (localStorage.getItem("showOrderDetailsListInfoContainer")) {
    sale_order_details_list_info_container.classList.add("show_container");
}

//sale_order_details_list_container
let sale_order_details_list_container = document.querySelector(
    ".sale_order_details_list_container"
);
if (localStorage.getItem("showOrderInvoiceListContainer")) {
    sale_order_details_list_container.classList.add("show_container");
}

//sale_order_details_info_label
let sale_order_details_info_label = document.querySelector(
    "#sale_order_details_info_label"
);
sale_order_details_info_label.addEventListener("click", (e) => {
    sale_order_details_info_label.classList.toggle("show");
    let sale_order_details_info_container = document.querySelector(
        ".sale_order_details_info_container"
    );
    sale_order_details_info_container.classList.toggle("show_container");
    if (
        sale_order_details_info_container.classList.contains("show_container")
    ) {
        localStorage.setItem("showOrderInvoiceInfoContainer", "true");
    } else {
        localStorage.removeItem("showOrderInvoiceInfoContainer");
    }
});

//sale_order_details_list_info_label
let sale_order_details_list_info_label = document.querySelector(
    "#sale_order_details_list_info_label"
);
sale_order_details_list_info_label.addEventListener("click", (e) => {
    sale_order_details_list_info_label.classList.toggle("show");
    let sale_order_details_list_info_container = document.querySelector(
        ".sale_order_details_list_info_container"
    );
    sale_order_details_list_info_container.classList.toggle("show_container");
    if (
        sale_order_details_list_info_container.classList.contains(
            "show_container"
        )
    ) {
        localStorage.setItem("showOrderDetailsListInfoContainer", "true");
    } else {
        localStorage.removeItem("showOrderDetailsListInfoContainer");
    }
});

//sale_order_details_list_label
let sale_order_details_list_label = document.querySelector(
    "#sale_order_details_list_label"
);
sale_order_details_list_label.addEventListener("click", (e) => {
    sale_order_details_list_label.classList.toggle("show");
    let sale_order_details_list_container = document.querySelector(
        ".sale_order_details_list_container"
    );
    sale_order_details_list_container.classList.toggle("show_container");
    if (
        sale_order_details_list_container.classList.contains("show_container")
    ) {
        localStorage.setItem("showOrderInvoiceListContainer", "true");
    } else {
        localStorage.removeItem("showOrderInvoiceListContainer");
    }
});

$(function () {
    $.validator.addMethod(
        "notZeroWaiter",
        function (value, element) {
            return value != 0;
        },
        "Waiter ရွေးရန်လိုအပ်ပါသည်"
    );

    $.validator.addMethod(
        "notZeroCashier",
        function (value, element) {
            return value != 0;
        },
        "Cashier ရွေးရန်လိုအပ်ပါသည်"
    );

    $("#orderInvoiceForm").validate({
        rules: {
            waiter_name: {
                required: true,
                notZeroWaiter: true,
            },
            cashier_name: {
                required: true,
                notZeroCashier: true,
            },
        },
        messages: {
            waiter_name: {
                required: "Waiter ရွေးရန်လိုအပ်ပါသည်",
            },
            cashier_name: {
                required: "Cashier ရွေးရန်လိုအပ်ပါသည်",
            },
        },
    });
    $(".check_out_modal").click(function () {
        if ($("#orderInvoiceForm").valid()) {
            $("#check_out_modal").modal("show");

            var invoiceNumber = $("#invoice_number").val();
            var tableID = $("#table_id").val();
            var tableOrderNumber = $("#order_number").val();
            var customerID = $("#customer_name").val();
            var waiterID = $("#waiter_name").val();
            var cashierID = $("#cashier_name").val();

            $(".modal-body #invoice_number").val(invoiceNumber);
            $(".modal-body #table_id").val(tableID);
            $(".modal-body #table_order_number").val(tableOrderNumber);
            $(".modal-body #customer_id").val(customerID);
            $(".modal-body #waiter_id").val(waiterID);
            $(".modal-body #cashier_id").val(cashierID);
        } else {
            $("#check_out_modal").modal("hide");
        }
    });
});

// $(".saleOrder_print").click(function () {
//     var saleID = $(this).data("sale-id"); // Note: data attribute with hyphen
//     console.log("Sale ID:", saleID);
    
//     $.ajax({
//         type: "GET",
//         url: "saleOrderPrint",
//         data: {
//             saleID: saleID,
//         },
//         success: function (response) {
//             function printContent(url) {
//                 var printWindow = window.open(url, '_blank'); // Pass url here
//                 if (printWindow) {
//                     printWindow.focus();
//                     // Remove window.close() - this closes the parent window
//                 } else {
//                     console.error("Unable to open the print page. Pop-ups might be blocked.");
//                 }
//             }

//             var printUrl = "/admin/prints/saleOrderPrint/" + response + "/" + saleID;
//             printContent(printUrl);
//         },
//         error: function(xhr, status, error) {
//             console.error("Pre-Print error:", error);
//             alert("Pre-Print failed. Please try again.");
//         }
//     });
// });

$(".saleOrder_print").click(function () {
    var saleID = $(this).data("sale-id");
    console.log("Sale ID:", saleID);
    
    var printUrl = "/admin/prints/saleOrderPrint/" + saleID;
    var printWindow = window.open(printUrl, '_blank');
    
    if (printWindow) {
        printWindow.focus();
    } else {
        console.error("Unable to open the print page. Pop-ups might be blocked.");
    }
});

$(document).on("click", ".check_out_modal", function () {
    var tableID = $("#table_id").val();
    var tableName = $("#table_name").val();
    var orderNumber = $("#order_number").val();
    var totalAmount = $("#totalAmount").val();

    var serviceChargesAmount = 0;
    var serviceChargesPercent = 0;
    var netAmount = 0;

    $(".modal-header #check_out_modal_header").text(
        "Bill Info - " + tableName + " ( " + orderNumber + " )"
    );

    $(".modal-body #total_amount").val(totalAmount);
    $(".modal-body #net_amount").val(totalAmount);

    $("#service_charges_amount").on("input", function () {
        serviceChargesAmount = $(this).val();
        serviceChargesAmount = serviceChargesAmount.replace(/[^0-9.]/g, "");
        $(this).val(serviceChargesAmount);

        if (isNaN(parseInt(serviceChargesAmount))) {
            $("#service_charges_amount").addClass("is-invalid");
            $("#service_charges_error_message").show();
        } else {
            $("#service_charges_amount").removeClass("is-invalid");
            $("#service_charges_error_message").hide();
            $("#service_charges_percent").removeClass("is-invalid");
            $("#service_charges_error_message").hide();
            serviceChargesPercent =
                (parseInt(serviceChargesAmount) / parseInt(totalAmount)) * 100;
            netAmount = parseInt(totalAmount) + parseInt(serviceChargesAmount);

            $(".modal-body #service_charges_percent").val(
                Number(serviceChargesPercent.toFixed(3))
            );
            calculation();
        }
    });
    $("#service_charges_percent").on("input", function () {
        serviceChargesPercent = $(this).val();
        serviceChargesPercent = serviceChargesPercent.replace(/[^0-9.]/g, "");
        $(this).val(serviceChargesPercent);

        if (isNaN(parseInt(serviceChargesPercent))) {
            $("#service_charges_percent").addClass("is-invalid");
            $("#service_charges_error_message").show();
        } else {
            $("#service_charges_amount").removeClass("is-invalid");
            $("#service_charges_error_message").hide();
            $("#service_charges_percent").removeClass("is-invalid");

            serviceChargesAmount =
                (parseInt(serviceChargesPercent) / 100) * parseInt(totalAmount);
            netAmount = parseInt(totalAmount) + parseInt(serviceChargesAmount);

            $(".modal-body #service_charges_amount").val(serviceChargesAmount);
            calculation();
        }
    });
    $("#tax_amount").on("input", function () {
        taxAmount = $(this).val();
        taxAmount = taxAmount.replace(/[^0-9.]/g, "");
        $(this).val(taxAmount);

        if (isNaN(parseInt(taxAmount))) {
            $("#tax_amount").addClass("is-invalid");
            $("#tax_error_message").show();
        } else {
            $("#tax_amount").removeClass("is-invalid");
            $("#tax_error_message").hide();
            $("#tax_percent").removeClass("is-invalid");
            taxPercent = (parseInt(taxAmount) / parseInt(totalAmount)) * 100;
            netAmount = parseInt(netAmount) + parseInt(taxAmount);

            $(".modal-body #tax_percent").val(Number(taxPercent.toFixed(3)));
            calculation();
        }
    });
    $("#tax_percent").on("input", function () {
        taxPercent = $(this).val();
        taxPercent = taxPercent.replace(/[^0-9.]/g, "");
        $(this).val(taxPercent);

        if (isNaN(parseInt(taxPercent))) {
            $("#tax_percent").addClass("is-invalid");
            $("#tax_error_message").show();
        } else {
            $("#tax_amount").removeClass("is-invalid");
            $("#tax_error_message").hide();
            $("#tax_percent").removeClass("is-invalid");

            taxAmount = (parseInt(taxPercent) / 100) * parseInt(totalAmount);
            netAmount = parseInt(totalAmount) + parseInt(taxAmount);

            $(".modal-body #tax_amount").val(taxAmount);
            calculation();
        }
    });
    $("#voucher_discount_amount").on("input", function () {
        voucherDiscountAmount = $(this).val();
        voucherDiscountAmount = voucherDiscountAmount.replace(/[^0-9.]/g, "");
        $(this).val(voucherDiscountAmount);

        if (isNaN(parseInt(voucherDiscountAmount))) {
            $("#voucher_discount_amount").addClass("is-invalid");
            $("#voucher_discount_error_message").show();
        } else {
            $("#voucher_discount_amount").removeClass("is-invalid");
            $("#voucher_discount_error_message").hide();
            $("#voucher_discount_percent").removeClass("is-invalid");

            voucherDiscountPercent =
                (parseInt(voucherDiscountAmount) / parseInt(totalAmount)) * 100;
            netAmount = parseInt(netAmount) + parseInt(voucherDiscountAmount);

            $(".modal-body #voucher_discount_percent").val(
                Number(voucherDiscountPercent.toFixed(3))
            );
            calculation();
        }
    });
    $("#voucher_discount_percent").on("input", function () {
        voucherDiscountPercent = $(this).val();
        voucherDiscountPercent = voucherDiscountPercent.replace(/[^0-9.]/g, "");
        $(this).val(voucherDiscountPercent);

        if (isNaN(parseInt(voucherDiscountPercent))) {
            $("#voucher_discount_percent").addClass("is-invalid");
            $("#voucher_discount_error_message").show();
        } else {
            $("#voucher_discount_amount").removeClass("is-invalid");
            $("#voucher_discount_error_message").hide();
            $("#voucher_discount_percent").removeClass("is-invalid");

            voucherDiscountAmount =
                (parseInt(voucherDiscountPercent) / 100) *
                parseInt(totalAmount);
            netAmount = parseInt(totalAmount) + parseInt(voucherDiscountAmount);

            $(".modal-body #voucher_discount_amount").val(
                voucherDiscountAmount
            );
            calculation();
        }
    });

    $("#member_card").on("input", function () {
        memberCard = $(this).val();
        $(".modal-body #member_card_discount_amount").val("");
        $(".modal-body #member_card_discount_percent").val("");
        $.ajax({
            type: "GET",
            url: "getMemberCardByMemberCardCode",
            data: {
                memberCard: memberCard,
            },
            success: function (data) {
                if (data.length != 0) {
                    $("#member_card").css("border-color", "");
                    $("#member_card_error_message").hide();
                    if (data[0]["discount_type"] == "Amount") {
                        $(".modal-body #member_card_discount_amount").val(
                            parseInt(data[0]["amount_discount"])
                        );
                    } else if (data[0]["discount_type"] == "Percent") {
                        $(".modal-body #member_card_discount_percent").val(
                            data[0]["percent_discount"]
                        );
                    }
                    calculation();
                } else {
                    $("#member_card").css("border-color", "red");
                    $("#member_card_error_message").show();
                }
            },
        });
        calculation();
    });
    $("#coupon_card").on("input", function () {
        couponCard = $(this).val();
        $(".modal-body #coupon_card_discount_amount").val("");
        $(".modal-body #coupon_card_discount_percent").val("");
        $.ajax({
            type: "GET",
            url: "getCouponCardByCouponCardCode",
            data: {
                couponCard: couponCard,
            },
            success: function (data) {
                if (data.length != 0) {
                    $("#coupon_card").css("border-color", "");
                    $("#coupon_card_error_message").hide();
                    if (data[0]["discount_type"] == "Amount") {
                        $(".modal-body #coupon_card_discount_amount").val(
                            parseInt(data[0]["amount_discount"])
                        );
                    } else if (data[0]["discount_type"] == "Percent") {
                        $(".modal-body #coupon_card_discount_percent").val(
                            data[0]["percent_discount"]
                        );
                    }
                    calculation();
                } else {
                    $("#coupon_card").css("border-color", "red");
                    $("#coupon_card_error_message").show();
                }
            },
        });
        calculation();
    });
    $("#paid_amount").on("input", function () {
        var paidAmount = $(this).val();
        paidAmount = paidAmount.replace(/[^0-9.]/g, "");
        $(this).val(paidAmount);
        if (isNaN(paidAmount)) {
            $("#paid_amount").addClass("is-invalid");
            $("#paid_amount_error_message").show();
        } else {
            $("#paid_amount").removeClass("is-invalid");
            $("#paid_amount_error_message").hide();
            calculation();
        }
    });

    function calculation() {
        var totalAmount = parseInt($("#totalAmount").val());
        var serviceCharges = parseInt($("#service_charges_amount").val());
        var tax = parseInt($("#tax_amount").val());
        var voucherDiscount = parseInt($("#voucher_discount_amount").val());
        var memberCardDiscountAmount = parseInt(
            $("#member_card_discount_amount").val()
        );
        var memberCardDiscountPercent = parseFloat(
            $("#member_card_discount_percent").val()
        );
        var couponCardDiscountAmount = parseInt(
            $("#coupon_card_discount_amount").val()
        );
        var couponCardDiscountPercent = parseFloat(
            $("#coupon_card_discount_percent").val()
        );
        var paidAmount = parseInt($("#paid_amount").val());

        var netAmount = 0;
        var balance = 0;

        if (isNaN(serviceCharges)) {
            serviceCharges = 0;
        }
        if (isNaN(tax)) {
            tax = 0;
        }
        if (isNaN(voucherDiscount)) {
            voucherDiscount = 0;
        }
        if (isNaN(memberCardDiscountAmount)) {
            memberCardDiscountAmount = 0;
        }
        if (isNaN(memberCardDiscountPercent)) {
            memberCardDiscountPercent = 0;
        } else {
            memberCardDiscountPercent =
                (memberCardDiscountPercent / 100) * totalAmount;
        }
        if (isNaN(couponCardDiscountAmount)) {
            couponCardDiscountAmount = 0;
        }
        if (isNaN(couponCardDiscountPercent)) {
            couponCardDiscountPercent = 0;
        } else {
            couponCardDiscountPercent =
                (couponCardDiscountPercent / 100) * totalAmount;
        }
        if (isNaN(paidAmount)) {
            paidAmount = 0;
        }

        netAmount =
            totalAmount +
            serviceCharges +
            tax -
            (voucherDiscount +
                memberCardDiscountAmount +
                memberCardDiscountPercent +
                couponCardDiscountAmount +
                couponCardDiscountPercent);
        $(".modal-body #net_amount").val(netAmount);

        balance = netAmount - paidAmount;

        if (balance < 0) {
            // If negative, change it to positive
            var change = Math.abs(balance);
            // Display the positive value in the UI
            $(".modal-body #balance").val(0);
            $(".modal-body #change").val(change);
        } else {
            $(".modal-body #balance").val(balance);
            $(".modal-body #change").val(0);
        }
    }
});
