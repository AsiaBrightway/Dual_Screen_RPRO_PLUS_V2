let priceControl_list = document.querySelector(".priceControl-list");
if (localStorage.getItem('showMenu')) {
    // category_list.classList.add("showMenu");
    priceControl_list.classList.add("showMenu");
}

// let price_control_info_container = document.querySelector('.price_control_info_container');
// if (localStorage.getItem('showCustomerTypeInfoContainer')) {
//     price_control_info_container.classList.add("show_container");
// }

let price_control_list_container = document.querySelector('.price_control_list_container');
if (localStorage.getItem('showCustomerTypeListContainer')) {
    price_control_list_container.classList.add("show_container");
}

// let price_control_info_label = document.querySelector('#price_control_info_label');
// price_control_info_label.addEventListener("click", (e) => {
//     price_control_info_label.classList.toggle('show');
//     let price_control_info_container = document.querySelector('.price_control_info_container');
//     price_control_info_container.classList.toggle('show_container');
//     if (price_control_info_container.classList.contains('show_container')) {
//         localStorage.setItem('showCustomerTypeInfoContainer', 'true');
//     } else {
//         localStorage.removeItem('showCustomerTypeInfoContainer');
//     }
// });

let price_control_list_label = document.querySelector('#price_control_list_label');
price_control_list_label.addEventListener("click", (e) => {
    price_control_list_label.classList.toggle('show');
    let price_control_list_container = document.querySelector('.price_control_list_container');
    price_control_list_container.classList.toggle('show_container');
    if (price_control_list_container.classList.contains('show_container')) {
        localStorage.setItem('showCustomerTypeListContainer', 'true');
    } else {
        localStorage.removeItem('showCustomerTypeListContainer');
    }
});

new DataTable('#item_sale_price_list', {
    scrollX: true,
});
function formatDate(date) {
    let year = date.getFullYear();
    let month = String(date.getMonth() + 1).padStart(2, "0"); // Months are zero-indexed
    let day = String(date.getDate()).padStart(2, "0");
    let hours = String(date.getHours()).padStart(2, "0");
    let minutes = String(date.getMinutes()).padStart(2, "0");
    let seconds = String(date.getSeconds()).padStart(2, "0");
    return `${year}_${month}_${day}-${hours}_${minutes}_${seconds}`;
}
$("#btn_excel").click(function() {
    var todayDate = new Date();
    let formattedDate = formatDate(todayDate);
    let filename = "PriceControl-" + formattedDate;

    // Temporarily remove pagination and other DataTable features
    var table = $("#item_sale_price_list").DataTable();

    // Destroy the DataTable to revert to a plain HTML table
    table.destroy();

    // Export the full table
    $("#item_sale_price_list").tableExport({
        fileName: filename,
        sheetName: "SalesReport",
        type: "excel",
        ignoreColumn: [5]
    });

    // Reinitialize DataTable after export
    $("#item_sale_price_list").DataTable({
        // Reapply your original DataTable configuration here
    });
});
$("#clear").click(function() {
    $("#customer_type_name").val("");
    $("#other_name").val("");
    $("#customer_type_code").val("");
    $('#is_discontinued').prop('checked', false);;
});

$("#item_name").change(function() {
    var item_id = $(this).val();
    $.ajax({
        type: "GET",
        url: "item/itemDetails",
        data: { itemID: item_id },
        success: function(data) {
            $("#main_category").empty();
            $("#sub_category").empty();
            $("#unit_name").empty();
            $("#unit_cost").empty();
            $('#selling_price').val('');
            $("#main_category").val(data.mainCategoryName);
            $("#sub_category").val(data.subCategoryName);
            $("#unit_name").val(data.unitName);
            $("#unit_cost").val(Math.floor(data.unitCost));
            $('#unit_id').val(data.unitID);

        },
    });
});

$(document).on("click", ".edit_saleItemPrice_modal_dialog", function() {
    var item_selling_price_id = $(this).data('item_selling_price_id');
    var item_name = $(this).data('item_name');
    var main_category = $(this).data('main_category');
    var sub_category = $(this).data('sub_category');
    var unit_name = $(this).data('unit_name');
    var unit_cost = $(this).data('unit_cost');
    var selling_price = $(this).data('selling_price');


    $(".modal-body #edit_item_selling_price_id").val(item_selling_price_id);
    $(".modal-body #edit_item_name").val(item_name);
    $(".modal-body #edit_main_category").val(main_category);
    $(".modal-body #edit_sub_category").val(sub_category);
    $(".modal-body #edit_unit").val(unit_name);
    $(".modal-body #edit_unit_cost").val(Number(unit_cost));
    $(".modal-body #edit_selling_price").val(selling_price);

});

// $(document).on("click", ".delete_customerType_modal_dialog", function() {
//     var customer_type_id = $(this).data('customer_type_id');
//     var customer_type_name = $(this).data('customer_type_name');
//     $(".modal-header #delete_modal_header").text("Delete '" + customer_type_name + "'")
//     $(".modal-body #delete_customer_type_id").val(customer_type_id);
// });

$(function() {
    $("#saleItemPriceEditModalForm").validate({
        rules: {
            edit_selling_price: {
                required: true,
                number: true,
                min: function() {
                    return parseFloat($("#edit_unit_cost").val());
                }
            }
        },
        messages: {
            edit_selling_price: {
                required: "Selling Price ဖြည့်ရန်လိုအပ်ပါသည်",
                number: "Selling Price သည် Number ဖြစ်ရပါမည်",
                min: "Selling Price သည် အနည်းဆုံး Unit Cost ရှိရပါမည်",
            }
        }
    });

});
