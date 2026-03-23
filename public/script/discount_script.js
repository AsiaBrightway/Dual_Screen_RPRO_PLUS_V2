let discount_list = document.querySelector(".discount-list");
if (localStorage.getItem("showMenu")) {
    // category_list.classList.add("showMenu");
    discount_list.parentElement.parentElement.classList.add("showMenu");
    discount_list.parentElement.parentElement.parentElement.parentElement.classList.add(
        "showMenu"
    );
}
let discount_info_container = document.querySelector(
    ".discount_info_container"
);
if (localStorage.getItem("showDiscountInfoContainer")) {
    discount_info_container.classList.add("show_container");
}

let discount_details_container = document.querySelector(
    ".discount_details_container"
);
if (localStorage.getItem("showDiscountDetailsContainer")) {
    discount_details_container.classList.add("show_container");
}

let discount_list_container = document.querySelector(
    ".discount_list_container"
);
if (localStorage.getItem("showDiscountListContainer")) {
    discount_list_container.classList.add("show_container");
}

let discount_info_label = document.querySelector("#discount_info_label");
discount_info_label.addEventListener("click", (e) => {
    discount_info_label.classList.toggle("show");
    let discount_info_container = document.querySelector(
        ".discount_info_container"
    );
    discount_info_container.classList.toggle("show_container");
    if (discount_info_container.classList.contains("show_container")) {
        localStorage.setItem("showDiscountInfoContainer", "true");
    } else {
        localStorage.removeItem("showDiscountInfoContainer");
    }
});

let discount_details_label = document.querySelector("#discount_details_label");
discount_details_label.addEventListener("click", (e) => {
    discount_details_label.classList.toggle("show");
    let discount_details_container = document.querySelector(
        ".discount_details_container"
    );
    discount_details_container.classList.toggle("show_container");
    if (discount_details_container.classList.contains("show_container")) {
        localStorage.setItem("showDiscountDetailsContainer", "true");
    } else {
        localStorage.removeItem("showDiscountDetailsContainer");
    }
});

let discount_list_label = document.querySelector("#discount_list_label");
discount_list_label.addEventListener("click", (e) => {
    discount_list_label.classList.toggle("show");
    let discount_list_container = document.querySelector(
        ".discount_list_container"
    );
    discount_list_container.classList.toggle("show_container");
    if (discount_list_container.classList.contains("show_container")) {
        localStorage.setItem("showDiscountListContainer", "true");
    } else {
        localStorage.removeItem("showDiscountListContainer");
    }
});

new DataTable("#discount_list", {
    scrollX: true,

});

$("#clear").click(function() {
    $("#main_category").val("0");
    $("#sub_category").val("0");
    $("#items").val("0");
    $("#description").val("");
    $("#other_description").val("");
    $("#item_price").val("");
    $("#buy_quantity").val("1");

    $("#amount_lbl").prop("checked", true);
    $("#percent_lbl").prop("checked", false);
    $("#amount_discount").prop("readonly", false).val("").removeClass("muted");
    $("#percent_discount").prop("readonly", true).val("0").addClass("muted");
    $("#promotion_price").val("");

    $("#monday").prop("checked", false);
    $("#tuesday").prop("checked", false);
    $("#wednesday").prop("checked", false);
    $("#thursday").prop("checked", false);
    $("#friday").prop("checked", false);
    $("#saturday").prop("checked", false);
    $("#sunday").prop("checked", false);

    $("#start_date").val("");
    $("#end_date").val("");
    $("#start_happy_hour").val("");
    $("#end_happy_hour").val("");
});

$("#main_category").change(function() {
    var mainCategory_id = $(this).val();
    $.ajax({
        type: "GET",
        url: "item/getSubCategory",
        data: { mainCategoryID: mainCategory_id },
        success: function(data) {
            $("#sub_category").empty();
            $.each(data, function(key, value) {
                $("#sub_category").append(
                    '<option value="' +
                    value.category_id +
                    '">' +
                    value.menu_category_name +
                    "</option>"
                );
            });
            var subCategory_id = $("#sub_category").val();
            $.ajax({
                type: "GET",
                url: "item/getItem",
                data: {
                    mainCategoryID: mainCategory_id,
                    subCategoryID: subCategory_id,
                },
                success: function(data) {
                    $("#items").empty();
                    $.each(data, function(key, value) {
                        $("#items").append(
                            '<option value="' +
                            value.item_id +
                            '">' +
                            value.item_name +
                            "</option>"
                        );
                    });
                    var item_id = $("#items").val();
                    $.ajax({
                        type: "GET",
                        url: "item/getItemPrice",
                        data: {
                            itemID: item_id,
                        },
                        success: function(data) {
                            $("#item_price").empty();
                            $.each(data, function(key, value) {
                                $("#item_price").val(value.item_selling_price);
                            });
                        },
                    });
                },
            });
            if (mainCategory_id == "0") {
                $("#item_price").val("");
            }
            $("#amount_lbl").prop("checked", true);
            $("#percent_lbl").prop("checked", false);
            $("#amount_discount")
                .prop("readonly", false)
                .val("")
                .removeClass("muted");
            $("#percent_discount")
                .prop("readonly", true)
                .val("0")
                .addClass("muted");
            $("#promotion_price").val("");
        },
    });
});

$("#sub_category").change(function() {
    var mainCategory_id = $("#main_category").val();
    var subCategory_id = $(this).val();
    $.ajax({
        type: "GET",
        url: "item/getItem",
        data: {
            mainCategoryID: mainCategory_id,
            subCategoryID: subCategory_id,
        },
        success: function(data) {
            $("#items").empty();
            $.each(data, function(key, value) {
                $("#items").append(
                    '<option value="' +
                    value.item_id +
                    '">' +
                    value.item_name +
                    "</option>"
                );
            });
            var item_id = $("#items").val();
            if (item_id != "") {
                $.ajax({
                    type: "GET",
                    url: "item/getItemPrice",
                    data: {
                        itemID: item_id,
                    },
                    success: function(data) {
                        $("#item_price").empty();
                        $.each(data, function(key, value) {
                            $("#item_price").val(value.item_selling_price);
                        });
                    },
                });
            } else {
                $("#item_price").val("");
            }
            $("#amount_lbl").prop("checked", true);
            $("#percent_lbl").prop("checked", false);
            $("#amount_discount")
                .prop("readonly", false)
                .val("")
                .removeClass("muted");
            $("#percent_discount")
                .prop("readonly", true)
                .val("0")
                .addClass("muted");
            $("#promotion_price").val("");
        },
    });
});

$("#items").change(function() {
    var item_id = $(this).val();
    $.ajax({
        type: "GET",
        url: "item/getItemPrice",
        data: {
            itemID: item_id,
        },
        success: function(data) {
            $("#item_price").empty();
            $.each(data, function(key, value) {
                $("#item_price").val(value.item_selling_price);
            });
            $("#amount_lbl").prop("checked", true);
            $("#percent_lbl").prop("checked", false);
            $("#amount_discount")
                .prop("readonly", false)
                .val("")
                .removeClass("muted");
            $("#percent_discount")
                .prop("readonly", true)
                .val("0")
                .addClass("muted");
            $("#promotion_price").val("");
        },
    });
});
$("#buy_quantity").on("input", function() {
    calculation();
});
$("#amount_discount").on("input", function() {
    calculation();
});
$("#percent_discount").on("input", function() {
    calculation();
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
        $("#promotion_price").val("");
    } else {
        $("#amount_discount").prop("readonly", true).val("0").addClass("muted");
        $("#percent_discount")
            .prop("readonly", false)
            .val("")
            .removeClass("muted");
        $("#promotion_price").val("");
    }
});

$(document).on("click", ".delete_discount_modal_dialog", function() {
    var item_discount_id = $(this).data("item_discount_id");
    var item_name = $(this).data("item_name");
    var description = $(this).data("description");
    $("#delete_modal_header").text(
        "Delete '" + description + "' of '" + item_name + "'"
    );
    $("#delete_discount_id").val(item_discount_id);
});

function calculation() {
    var qty = isNaN(parseFloat($('#buy_quantity').val())) ? 0 : parseFloat($('#buy_quantity').val());
    var itemPrice = isNaN(parseFloat($("#item_price").val())) ? 0 : parseFloat($("#item_price").val());
    var amountDiscount = isNaN(parseFloat($('#amount_discount').val())) ? 0 : parseFloat($('#amount_discount').val());
    var percentDiscount = isNaN(parseFloat($('#percent_discount').val())) ? 0 : parseFloat($('#percent_discount').val());

    var promoPercent = (itemPrice * qty) * (percentDiscount / 100);
    var promotionPrice = (itemPrice * qty) - (amountDiscount + promoPercent);

    $("#promotion_price").val(parseInt(promotionPrice));
}