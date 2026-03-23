let coupon_card_list = document.querySelector(".coupon-card-list");
if (localStorage.getItem("showMenu")) {
    // category_list.classList.add("showMenu");
    coupon_card_list.parentElement.parentElement.classList.add("showMenu");
}

let coupon_card_info_container = document.querySelector(
    ".coupon_card_info_container"
);
if (localStorage.getItem("showCouponCardInfoContainer")) {
    coupon_card_info_container.classList.add("show_container");
}

let coupon_card_list_container = document.querySelector(
    ".coupon_card_list_container"
);
if (localStorage.getItem("showCouponCardListContainer")) {
    coupon_card_list_container.classList.add("show_container");
}

let coupon_card_info_label = document.querySelector("#coupon_card_info_label");
coupon_card_info_label.addEventListener("click", (e) => {
    coupon_card_info_label.classList.toggle("show");
    let coupon_card_info_container = document.querySelector(
        ".coupon_card_info_container"
    );
    coupon_card_info_container.classList.toggle("show_container");
    if (coupon_card_info_container.classList.contains("show_container")) {
        localStorage.setItem("showCouponCardInfoContainer", "true");
    } else {
        localStorage.removeItem("showCouponCardInfoContainer");
    }
});

let coupon_card_list_label = document.querySelector("#coupon_card_list_label");
coupon_card_list_label.addEventListener("click", (e) => {
    coupon_card_list_label.classList.toggle("show");
    let coupon_card_list_container = document.querySelector(
        ".coupon_card_list_container"
    );
    coupon_card_list_container.classList.toggle("show_container");
    if (coupon_card_list_container.classList.contains("show_container")) {
        localStorage.setItem("showCouponCardListContainer", "true");
    } else {
        localStorage.removeItem("showCouponCardListContainer");
    }
});

new DataTable("#coupon_card_list", {
    scrollX: true,
});

$(".discount-type").change(function() {
    if (this.value == "Amount") {
        $("#percent_discount")
            .prop("readonly", true)
            .val("0")
            .addClass("muted");
        $("#amount_discount")
            .prop("readonly", false)
            .val("")
            .removeClass("muted");
    } else {
        $("#amount_discount").prop("readonly", true).val("0").addClass("muted");
        $("#percent_discount")
            .prop("readonly", false)
            .val("")
            .removeClass("muted");
    }
});

$("#coupon_generate").change(function() {
    if ($("#coupon_generate").prop("checked")) {
        $("#coupon_count")
            .prop("readonly", false)
            .val("1")
            .removeClass("muted");
    } else {
        $("#coupon_count").prop("readonly", true).val("1").addClass("muted");
    }
});

$(document).on("click", ".edit_coupon_modal_dialog", function() {
    var coupon_id = $(this).data("coupon_id");
    var coupon_code = $(this).data("coupon_code");
    var coupon_name = $(this).data("coupon_name");
    var discount_type = $(this).data("discount_type");
    var amount_discount = $(this).data("amount_discount");
    var percent_discount = $(this).data("percent_discount");
    var min_order_amount = $(this).data("min_order_amount");
    var expire_date = $(this).data("expire_date");
    var is_discontinued = $(this).data("is_discontinued");

    $(".modal-body #edit_coupon_card_id").val(coupon_id);
    $(".modal-body #edit_coupon_code").val(coupon_code);
    $(".modal-body #edit_coupon_name").val(coupon_name);

    if (discount_type == "Amount") {
        $("#edit_amount_lbl").prop("checked", true);
        $("#edit_percent_lbl").prop("checked", false);

        $("#edit_percent_discount").prop("readonly", true).addClass("muted");
        $("#edit_amount_discount").prop("readonly", false).removeClass("muted");
    } else {
        $("#edit_amount_lbl").prop("checked", false);
        $("#edit_percent_lbl").prop("checked", true);

        $("#edit_amount_discount").prop("readonly", true).addClass("muted");
        $("#edit_percent_discount")
            .prop("readonly", false)
            .removeClass("muted");
    }

    $(".modal-body #edit_amount_discount").val(amount_discount);
    $(".modal-body #edit_percent_discount").val(percent_discount);
    $(".modal-body #edit_min_order_amount").val(min_order_amount);
    $(".modal-body #edit_expire_date").val(convertDataFormat(expire_date));

    function convertDataFormat(dateString) {
        var inputDateString = dateString;
        var inputDate = new Date(inputDateString);

        // Extract year, month, and day components
        var year = inputDate.getFullYear();
        var month = (inputDate.getMonth() + 1).toString().padStart(2, "0"); // Months are 0-based, so add 1
        var day = inputDate.getDate().toString().padStart(2, "0");

        // Format the date in "yyyy-MM-dd" format
        var formattedDate = year + "-" + month + "-" + day;
        return formattedDate;
    }

    if (is_discontinued == 1) {
        document.getElementById(
            "edit_coupon_card_is_discontinued"
        ).checked = true;
    } else {
        document.getElementById(
            "edit_coupon_card_is_discontinued"
        ).checked = false;
    }

    $(".edit_discount-type").change(function() {
        if (this.value == "Amount") {
            $("#edit_percent_discount")
                .prop("readonly", true)
                .val("0")
                .addClass("muted");
            $("#edit_amount_discount")
                .prop("readonly", false)
                .val("")
                .removeClass("muted");
        } else {
            $("#edit_amount_discount")
                .prop("readonly", true)
                .val("0")
                .addClass("muted");
            $("#edit_percent_discount")
                .prop("readonly", false)
                .val("")
                .removeClass("muted");
        }
    });

    $(function() {
        $("#couponCardEditModalForm").validate({
            rules: {
                edit_coupon_name: {
                    required: true,
                },
                edit_amount_discount: {
                    required: true,
                    number: true,
                },
                edit_percent_discount: {
                    required: true,
                    number: true,
                },
                edit_min_order_amount: {
                    required: true,
                    number: true,
                },
                edit_expire_date: {
                    required: true,
                },
            },
            messages: {
                edit_coupon_name: {
                    required: "Membar Card Type Name ဖြည့်ရန်လိုအပ်ပါသည်",
                },
                edit_amount_discount: {
                    required: "Amount Discount ဖြည့်ရန်လိုအပ်ပါသည်",
                    number: "Amount Discount သည် Number ဖြစ်ရပါမည်",
                },
                edit_percent_discount: {
                    required: "Percent Discount ဖြည့်ရန်လိုအပ်ပါသည်",
                    number: "Percent Discount သည် Number ဖြစ်ရပါမည်",
                },
                edit_min_order_amount: {
                    required: "Min-Order Amount ဖြည့်ရန်လိုအပ်ပါသည်",
                    number: "Min-Order Amount သည် Number ဖြစ်ရပါမည်",
                },
                edit_expire_date: {
                    required: "Expire Date ရွေးရန်လိုအပ်ပါသည်",
                },
            },
        });
    });
});

$(document).on("click", ".delete_coupon_modal_dialog", function() {
    var coupon_id = $(this).data("coupon_id");
    var coupon_code = $(this).data("coupon_code");
    $("#delete_modal_header").text(
        "Delete '" + coupon_code + "'"
    );
    $("#delete_coupon_card_id").val(coupon_id);
});

setTimeout(function() {
    const message = document.getElementById('flash-message');
    if (message) {
        message.style.transition = "opacity 0.5s ease";
        message.style.opacity = "0";

        // Remove from DOM after fade out is done
        setTimeout(() => message.remove(), 500); 
    }
}, 3000);