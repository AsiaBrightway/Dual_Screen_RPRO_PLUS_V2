new DataTable("#order_invoice_list", { scrollX: true, });

let order_invoice_info_container = document.querySelector(
    ".order_invoice_info_container"
);
if (localStorage.getItem("showOrderInvoiceInfoContainer")) {
    order_invoice_info_container.classList.add("show_container");
}

let order_invoice_list_container = document.querySelector(
    ".order_invoice_list_container"
);
if (localStorage.getItem("showOrderInvoiceListContainer")) {
    order_invoice_list_container.classList.add("show_container");
}

let userEditedPaidAmount = false;

let order_invoice_info_label = document.querySelector(
    "#order_invoice_info_label"
);
order_invoice_info_label.addEventListener("click", (e) => {
    order_invoice_info_label.classList.toggle("show");
    let order_invoice_info_container = document.querySelector(
        ".order_invoice_info_container"
    );
    order_invoice_info_container.classList.toggle("show_container");
    if (order_invoice_info_container.classList.contains("show_container")) {
        localStorage.setItem("showOrderInvoiceInfoContainer", "true");
    } else {
        localStorage.removeItem("showOrderInvoiceInfoContainer");
    }
});

let order_invoice_list_label = document.querySelector(
    "#order_invoice_list_label"
);
order_invoice_list_label.addEventListener("click", (e) => {
    order_invoice_list_label.classList.toggle("show");
    let order_invoice_list_container = document.querySelector(
        ".order_invoice_list_container"
    );
    order_invoice_list_container.classList.toggle("show_container");
    if (order_invoice_list_container.classList.contains("show_container")) {
        localStorage.setItem("showOrderInvoiceListContainer", "true");
    } else {
        localStorage.removeItem("showOrderInvoiceListContainer");
    }
});

$(function() {
    $.validator.addMethod(
        "notZeroWaiter",
        function(value, element) {
            return value != 0;
        },
        "Waiter ရွေးရန်လိုအပ်ပါသည်"
    );

    $.validator.addMethod(
        "notZeroCashier",
        function(value, element) {
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
    $("#orderCheckOutForm").on('submit', async function(event) {
        event.preventDefault(); // Prevent form submission

        try {
            await closeCustomerScreen();
        } catch (error) {
            console.error("Error closing customer screen:", error);
        }

        var payment_type_id = $("#payment_type").val();

        try {
            var validator;

            if (payment_type_id != 1) {
                validator = $("#orderCheckOutForm").validate({
                    rules: {
                        online_paid: {
                            required: true,
                        },
                    },
                    messages: {
                        online_paid: {
                            required: "Online Paid Amount is required",
                        },
                    },
                });
            } else {
                validator = $("#orderCheckOutForm").validate({
                    rules: {
                        paid_amount: {
                            required: true,
                        },
                    },
                    messages: {
                        paid_amount: {
                            required: "Paid Amount is required",
                        },
                    },
                });
            }

            if (validator.form()) {
                // Serialize form data
                var formData = $("#orderCheckOutForm").serialize();
                console.log("Form Data:", formData);

                // AJAX request to submit form data
                $.ajax({
                    type: "POST",
                    url: "checkOut",
                    data: formData,
                    success: function(response) {
                        // console.log("Checkout successful:", response);
                        // var printUrl = "/admin/prints/saleOrderPrint/" + response;
                        // $('.printBtn').attr('href', printUrl);
                        // $('.printBtn').printPage();

                        // // Listen for afterprint event
                        // setTimeout(function() {
                        //     console.log("Printing completed");
                        //     window.location.href = "/admin/dineInPage"; // Redirect after printing
                        // }, 1000);

                        // // Trigger click on print button
                        // $('.printBtn')[0].click();
                        function printContent(url) {
                            // Open the print page in a new tab
                            var printWindow = window.open(url, '_blank');

                            if (printWindow) {
                                // Ensure the print tab gains focus
                                printWindow.focus();

                                // Close the current tab
                                window.close();
                            } else {
                                console.error("Unable to open the print page. Pop-ups might be blocked.");
                            }
                        }

                        // Build the print page URL dynamically
                        var printUrl = "/admin/prints/saleOrderPrint/" + response;

                        // Call the print function
                        printContent(printUrl);


                    },
                    error: function(xhr, status, error) {
                        console.error("Checkout error:", error);
                        alert("Checkout failed. Please try again.");
                    }
                });
            }
        } catch (e) {
            console.error('Validation error:', e);
        }
    });

    // $("#orderCheckOutForm").validate({
    //     rules: {
    //         paid_amount: {
    //             required: true,

    //         },
    //     },
    //     messages: {
    //         paid_amount: {
    //             required: "Paid Amount ဖြည့်ရန်လိုအပ်ပါသည်",
    //         },
    //     },
    // });

    // $(".check_out_modal").click(function() {
    //     userEditedPaidAmount = false;
    //     if ($("#orderInvoiceForm").valid()) {
            
    //         $("#check_out_modal").modal("show");

    //         var invoiceNumber = $("#invoice_number").val();
    //         var tableID = $("#table_id").val();
    //         var tableOrderNumber = $("#order_number").val();
    //         var customerID = $("#customer_name").val();
    //         var waiterID = $("#waiter_name").val();
    //         var cashierID = $("#cashier_name").val();

    //         $(".modal-body #invoice_number").val(invoiceNumber);
    //         $(".modal-body #table_id").val(tableID);
    //         $(".modal-body #table_order_number").val(tableOrderNumber);
    //         $(".modal-body #customer_id").val(customerID);
    //         $(".modal-body #waiter_id").val(waiterID);
    //         $(".modal-body #cashier_id").val(cashierID);
    //     } else {
    //         $("#check_out_modal").modal("hide");
    //     }
    // });

    $(".check_out_modal").click(async function(e) {
        // If not connected yet, connect first
        if (!displayWriter) {
            e.preventDefault(); // Stop the modal from opening immediately
            
            let connected = await initCustomerScreen();
            
            if (connected) {
                // If connection successful, proceed to open modal logic
                openCheckOutModalLogic();
            } 
            
        } else {
            // Already connected, just run the logic
            openCheckOutModalLogic();
        }
    });

    // 3. Move your existing modal logic into this helper function
    function openCheckOutModalLogic() {
        userEditedPaidAmount = false;
        
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

            
            // Run calculation once to push the total to the newly connected screen
            calculation(); 
        }
    }

    function calculation() {
        var totalAmount = parseInt($("#totalAmount").val());
        var serviceCharges = parseInt($("#service_charges_amount").val());
        var tax = parseInt($("#tax_amount").val());
        var voucherDiscount = parseInt($("#voucher_discount_amount").val());
        var memberCardDiscountAmount = parseInt($("#member_card_discount_amount").val());
        var memberCardDiscountPercent = parseFloat($("#member_card_discount_percent").val());
        var couponCardDiscountAmount = parseInt($("#coupon_card_discount_amount").val());
        var couponCardDiscountPercent = parseFloat($("#coupon_card_discount_percent").val());

        var netAmount = 0;

        // Convert percentages to amounts
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
            memberCardDiscountPercent = (memberCardDiscountPercent / 100) * totalAmount;
        }
        if (isNaN(couponCardDiscountAmount)) {
            couponCardDiscountAmount = 0;
        }
        if (isNaN(couponCardDiscountPercent)) {
            couponCardDiscountPercent = 0;
        } else {
            couponCardDiscountPercent = (couponCardDiscountPercent / 100) * totalAmount;
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

        showTotalOnScreen(netAmount);

    }
    
});
$(".prePrint_modal").click(function() {
    var tableID = $("#table_id").val();
    var tableOrderNumber = $("#order_number").val();
    var invoiceNumber = $("#invoice_number").val();
    console.log(tableID, tableOrderNumber, invoiceNumber);
    $.ajax({
        type: "GET",
        url: "prePrint",
        data: {
            tableID: tableID,
            tableOrderNumber: tableOrderNumber
        },
        success: function(response) {

            function printContent(url) {
                var printWindow = window.open(url, '_blank');
                if (printWindow) {
                    printWindow.focus();
                    window.close();
                } else {
                    console.error("Unable to open the print page. Pop-ups might be blocked.");
                }
            }
            var printUrl = "/admin/prints/preOrderPrint/" + response + "/" + invoiceNumber;

            printContent(printUrl);
        },
        error: function(xhr, status, error) {
            console.error("Pre-Print error:", error);
            alert("Pre-Print failed. Please try again.");
        }
    });
});

$(document).on("click", ".check_out_modal", function() {
    var tableID = $("#table_id").val();
    var tableName = $("#table_name").val();
    var orderNumber = $("#order_number").val();
    var totalAmount = $("#totalAmount").val();
    var totalItemDiscountAmt = $("#item_discount_amt").val();
    console.log(totalItemDiscountAmt);

    var serviceChargesAmount = 0;
    var serviceChargesPercent = 0;
    var netAmount = 0;

    $(".modal-header #check_out_modal_header").text(
        "Bill Info - " + tableName + " ( " + orderNumber + " )"
    );

    $(".modal-body #total_amount").val(totalAmount);
    netAmount = totalAmount - totalItemDiscountAmt;
    console.log(netAmount);
    $(".modal-body #net_amount").val(netAmount);
    $(".modal-body #paid_amount").val(totalAmount - totalItemDiscountAmt);
    calculation()

    $("#service_charges_amount").on("input", function() {
        let inputVal = $(this).val().replace(/[^0-9.]/g, "");

        if (inputVal === "" || isNaN(parseFloat(inputVal)) || parseFloat(inputVal) === 0) {
            $(this).val("");
            $(this).attr("placeholder", "0");

            $("#service_charges_percent").val("").attr("placeholder", "0");

            let totalAmt = parseFloat(totalAmount) || 0;
            netAmount = totalAmt;

            updateVoucherAmountBasedOnPercent();
            calculation();
            return;
        }

        let serviceChargesAmount = parseFloat(inputVal);

        // Round to whole number
        serviceChargesAmount = Math.round(serviceChargesAmount);

        $(this).val(serviceChargesAmount);
        $(this).removeAttr("placeholder");
        $("#service_charges_percent").removeAttr("placeholder");

        $(this).removeClass("is-invalid");
        $("#service_charges_error_message").hide();

        let totalAmt = parseFloat(totalAmount) || 0;
        let serviceChargesPercent = 0;
        if (totalAmt > 0) serviceChargesPercent = (serviceChargesAmount / totalAmt) * 100;

        // Round net amount to whole number
        netAmount = Math.round(totalAmt + serviceChargesAmount);

        $(".modal-body #service_charges_percent").val(Number(serviceChargesPercent.toFixed(3)));

        updateVoucherAmountBasedOnPercent();
        calculation();
    });

    $("#service_charges_percent").on("input", function() {
        let inputVal = $(this).val().replace(/[^0-9.]/g, "");

        if (inputVal === "" || isNaN(parseFloat(inputVal)) || parseFloat(inputVal) === 0) {
            $(this).val("");
            $(this).attr("placeholder", "0");

            $("#service_charges_amount").val("").attr("placeholder", "0");

            let totalAmt = parseFloat(totalAmount) || 0;
            netAmount = totalAmt;

            updateVoucherAmountBasedOnPercent();
            calculation();
            return;
        }

        let serviceChargesPercent = parseFloat(inputVal);

        // Round percent to 3 decimal places
        serviceChargesPercent = Math.round(serviceChargesPercent * 1000) / 1000;

        $(this).val(serviceChargesPercent);
        $(this).removeAttr("placeholder");
        $("#service_charges_amount").removeAttr("placeholder");

        $(this).removeClass("is-invalid");
        $("#service_charges_error_message").hide();

        let totalAmt = parseFloat(totalAmount) || 0;
        let serviceChargesAmount = totalAmt > 0 ? (serviceChargesPercent / 100) * totalAmt : 0;

        // Round amount to whole number
        serviceChargesAmount = Math.round(serviceChargesAmount);

        // Round net amount to whole number
        netAmount = Math.round(totalAmt + serviceChargesAmount);

        $(".modal-body #service_charges_amount").val(serviceChargesAmount);

        updateVoucherAmountBasedOnPercent();
        calculation();
    });

    $("#tax_amount").on("input", function() {
        let inputVal = $(this).val().replace(/[^0-9.]/g, "");

        if (inputVal === "" || isNaN(parseFloat(inputVal)) || parseFloat(inputVal) === 0) {
            $(this).val("");
            $(this).attr("placeholder", "0");

            $("#tax_percent").val("").attr("placeholder", "0");

            let totalAmt = parseFloat(totalAmount) || 0;
            netAmount = totalAmt;

            updateVoucherAmountBasedOnPercent();
            calculation();
            return;
        }
        let taxAmount = parseFloat(inputVal);
        $(this).val(taxAmount);
        $(this).removeAttr("placeholder");
        $("#tax_percent").removeAttr("placeholder");

        $(this).removeClass("is-invalid");
        $("#tax_error_message").hide();

        let totalAmt = parseFloat(totalAmount) || 0;
        let taxPercent = 0;
        if (totalAmt > 0) taxPercent = (taxAmount / totalAmt) * 100;

        netAmount = totalAmt + taxAmount;

        $(".modal-body #tax_percent").val(Number(taxPercent.toFixed(3)));
        updateVoucherAmountBasedOnPercent();
        calculation();
    });

    $("#tax_percent").on("input", function() {
        let inputVal = $(this).val().replace(/[^0-9.]/g, "");

        if (inputVal === "" || isNaN(parseFloat(inputVal)) || parseFloat(inputVal) === 0) {
            $(this).val("");
            $(this).attr("placeholder", "0");

            $("#tax_amount").val("").attr("placeholder", "0");

            let totalAmt = parseFloat(totalAmount) || 0;
            netAmount = totalAmt;

            updateVoucherAmountBasedOnPercent();
            calculation();
            return;
        }

        let taxPercent = parseFloat(inputVal);

        taxPercent = (taxPercent * 1000) / 1000;

        $(this).val(taxPercent);
        $(this).removeAttr("placeholder");
        $("#tax_amount").removeAttr("placeholder");

        $(this).removeClass("is-invalid");
        $("#tax_error_message").hide();

        let totalAmt = parseFloat(totalAmount) || 0;
        let taxAmount = totalAmt > 0 ? (taxPercent / 100) * totalAmt : 0;

        taxAmount = (taxAmount * 100) / 100;

        netAmount = ((totalAmt + taxAmount) * 100) / 100;

        $(".modal-body #tax_amount").val(taxAmount);

        updateVoucherAmountBasedOnPercent();
        calculation();
    });

    $("#voucher_discount_amount").on("input", function() {
        let inputVal = $(this).val().replace(/[^0-9.]/g, "");

        if (inputVal === "" || isNaN(parseFloat(inputVal)) || parseFloat(inputVal) === 0) {
            $(this).val("");
            $(this).attr("placeholder", "0");

            $("#voucher_discount_percent").val("").attr("placeholder", "0");

            let totalAmt = parseFloat(totalAmount) || 0;
            netAmount = totalAmt;
            calculation();
            return;
        }

        let voucherDiscountAmount = parseFloat(inputVal);

        voucherDiscountAmount = Math.round(voucherDiscountAmount);

        $(this).val(voucherDiscountAmount);
        $(this).removeAttr("placeholder");
        $("#voucher_discount_percent").removeAttr("placeholder");

        $(this).removeClass("is-invalid");
        $("#voucher_discount_error_message").hide();

        let finalNetAmount = getFinalNetAmount();

        let voucherDiscountPercent = 0;
        if (finalNetAmount > 0) voucherDiscountPercent = (voucherDiscountAmount / finalNetAmount) * 100;

        netAmount = Math.round(finalNetAmount - voucherDiscountAmount);

        $(".modal-body #voucher_discount_percent").val(Number(voucherDiscountPercent.toFixed(3)));
        calculation();
    });

    $("#voucher_discount_percent").on("input", function() {
        let inputVal = $(this).val().replace(/[^0-9.]/g, "");

        if (inputVal === "" || isNaN(parseFloat(inputVal)) || parseFloat(inputVal) === 0) {
            $(this).val("");
            $(this).attr("placeholder", "0");

            $("#voucher_discount_amount").val("").attr("placeholder", "0");

            let totalAmt = parseFloat(totalAmount) || 0;
            netAmount = totalAmt;
            calculation();
            return;
        }

        let voucherDiscountPercent = parseFloat(inputVal);

        voucherDiscountPercent = Math.round(voucherDiscountPercent * 1000) / 1000;

        $(this).val(voucherDiscountPercent);
        $(this).removeAttr("placeholder");
        $("#voucher_discount_amount").removeAttr("placeholder");

        $(this).removeClass("is-invalid");
        $("#voucher_discount_error_message").hide();

        let finalNetAmount = getFinalNetAmount();

        let voucherDiscountAmount = finalNetAmount > 0 ? (voucherDiscountPercent / 100) * finalNetAmount : 0;
        // console.log(voucherDiscountAmount)

        voucherDiscountAmount = Math.round(voucherDiscountAmount);

        netAmount = Math.round(finalNetAmount - voucherDiscountAmount);

        $(".modal-body #voucher_discount_amount").val(voucherDiscountAmount);
        calculation();
    });


    $("#member_card").on("input", function() {
        memberCard = $(this).val();
        $(".modal-body #member_card_discount_amount").val("");
        $(".modal-body #member_card_discount_percent").val("");
        $.ajax({
            type: "GET",
            url: "getMemberCardByMemberCardCode",
            data: {
                memberCard: memberCard,
            },
            success: function(data) {
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
    $("#coupon_card").on("input", function() {
        couponCard = $(this).val();
        $(".modal-body #coupon_card_discount_amount").val("");
        $(".modal-body #coupon_card_discount_percent").val("");
        $.ajax({
            type: "GET",
            url: "getCouponCardByCouponCardCode",
            data: {
                couponCard: couponCard,
            },
            success: function(data) {
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

    $("#online_paid").on("input", function() {
        userEditedPaidAmount = true;
        var onlinePaid = $(this).val();
        onlinePaid = onlinePaid.replace(/[^0-9.]/g, "");
        $(this).val(onlinePaid);

        if (isNaN(onlinePaid) || onlinePaid === "") {
            $("#online_paid").addClass("is-invalid");
            $("#online_paid_error_message").show();
        } else {
            $("#online_paid").removeClass("is-invalid");
            $("#online_paid_error_message").hide();

            var netAmount = parseInt($("#net_amount").val()) || 0;
            var onlinePaidValue = parseInt(onlinePaid) || 0;
            var calculatedCashPaid = netAmount - onlinePaidValue;

            $("#paid_amount").val(calculatedCashPaid >= 0 ? calculatedCashPaid : 0);

            calculation();
        }
    });

    $("#paid_amount").on("input", function() {
        userEditedPaidAmount = true;
        var paidAmount = $(this).val();
        paidAmount = paidAmount.replace(/[^0-9.]/g, "");
        $(this).val(paidAmount);

        if (isNaN(paidAmount) || paidAmount === "") {
            $("#paid_amount").addClass("is-invalid");
            $("#paid_amount_error_message").show();
        } else {
            $("#paid_amount").removeClass("is-invalid");
            $("#paid_amount_error_message").hide();

            calculation();
        }
    });

    function getFinalNetAmount() {
        let totalAmt = parseFloat($("#totalAmount").val()) || 0;
        let itemDisc = parseFloat($("#item_discount_amt").val()) || 0;
        let serviceCharge = parseFloat($("#service_charges_amount").val()) || 0;
        let tax = parseFloat($("#tax_amount").val()) || 0;

        return (totalAmt - itemDisc) + serviceCharge + tax;
    }

    function updateVoucherAmountBasedOnPercent() {
        let voucherPercent = parseFloat($("#voucher_discount_percent").val());

        let newFinalNetAmount = getFinalNetAmount();

        if (!isNaN(voucherPercent) && voucherPercent > 0) {

            let newVoucherAmount = (voucherPercent / 100) * newFinalNetAmount;

            $("#voucher_discount_amount").val(Math.round(newVoucherAmount));
        }
    }

    function calculation() {
        var totalAmount = parseInt($("#totalAmount").val());
        var serviceCharges = parseInt($("#service_charges_amount").val());
        var tax = parseInt($("#tax_amount").val());
        var voucherDiscount = parseInt($("#voucher_discount_amount").val());
        var memberCardDiscountAmount = parseInt($("#member_card_discount_amount").val());
        var memberCardDiscountPercent = parseFloat($("#member_card_discount_percent").val());
        var couponCardDiscountAmount = parseInt($("#coupon_card_discount_amount").val());
        var couponCardDiscountPercent = parseFloat($("#coupon_card_discount_percent").val());
        var onlinePaid = parseFloat($('#online_paid').val());
        var paidAmount = parseFloat($("#paid_amount").val());

        var netAmount = 0;
        var balance = 0;

        // Convert percentages to amounts
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
            memberCardDiscountPercent = (memberCardDiscountPercent / 100) * totalAmount;
        }
        if (isNaN(couponCardDiscountAmount)) {
            couponCardDiscountAmount = 0;
        }
        if (isNaN(couponCardDiscountPercent)) {
            couponCardDiscountPercent = 0;
        } else {
            couponCardDiscountPercent = (couponCardDiscountPercent / 100) * totalAmount;
        }
        if (isNaN(onlinePaid)) {
            onlinePaid = 0;
        }
        if (isNaN(paidAmount)) {
            paidAmount = 0;
        }

        netAmount =
            totalAmount - totalItemDiscountAmt +
            serviceCharges +
            tax -
            (voucherDiscount +
                memberCardDiscountAmount +
                memberCardDiscountPercent +
                couponCardDiscountAmount +
                couponCardDiscountPercent);

        $(".modal-body #net_amount").val(netAmount);

        showTotalOnScreen(netAmount);

        if (netAmount <= 0) {
            $(".modal-body #voucher_foc").prop("checked", true);
            $(".modal-body #voucher_foc_value").val("1");
        } else {
            $(".modal-body #voucher_foc").prop("checked", false);
            $(".modal-body #voucher_foc_value").val("0");
        }

        // Auto-fill paid amount only if user hasn't manually edited it
        if (!userEditedPaidAmount) {
            var payment_type = $("#payment_type").val();

            if (payment_type == 1) {
                paidAmount = netAmount;
                $("#paid_amount").val(paidAmount);
                $("#online_paid").val(0);
            } else {
                onlinePaid = netAmount;
                $("#online_paid").val(onlinePaid);
                paidAmount = 0;
                $("#paid_amount").val(0);
            }
        }

        // Calculate balance or change
        balance = netAmount - (paidAmount + onlinePaid);

        if (balance < 0) {
            // If balance is negative, customer paid more (change)
            var change = Math.abs(balance);
            $("#balance").val(0);
            $("#change").val(change);
        } else {
            // Positive balance means amount still owed
            $("#balance").val(balance);
            $("#change").val(0);
        }

        console.log("Total Amount:", totalAmount);
        console.log("Net Amount:", netAmount);
        console.log("Paid Amount:", paidAmount);
        console.log("Online Paid:", onlinePaid);
        console.log("Balance:", balance);
        console.log("----------------------------");
    }

    $("#payment_type").change(function() {
        var payment_type_id = $(this).val();

        // Reset the user edited flag when payment type changes
        userEditedPaidAmount = false;

        if (payment_type_id != 1) {
            // Online Payment selected
            $('#online_paid').removeClass("muted");
            $('#online_paid').prop("readonly", false);

            var netAmount = $('#net_amount').val() || 0;
            $('#online_paid').val(netAmount);
            $('#paid_amount').val("0");
        } else {
            $('#online_paid').addClass("muted");
            $('#online_paid').prop("readonly", true);
            $('#online_paid').val("0");

            var netAmount = $('#net_amount').val() || 0;
            $('#paid_amount').val(netAmount);
        }

        calculation();
    });

});