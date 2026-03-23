let customer_list = document.querySelector(".customer-list");
if (localStorage.getItem('showMenu')) {
    // category_list.classList.add("showMenu");
    customer_list.classList.add("showMenu");
}

let customer_type_info_container = document.querySelector('.customer_type_info_container');
if (localStorage.getItem('showCustomerTypeInfoContainer')) {
    customer_type_info_container.classList.add("show_container");
}

let customer_type_list_container = document.querySelector('.customer_type_list_container');
if (localStorage.getItem('showCustomerTypeListContainer')) {
    customer_type_list_container.classList.add("show_container");
}

let customer_type_info_label = document.querySelector('#customer_type_info_label');
customer_type_info_label.addEventListener("click", (e) => {
    customer_type_info_label.classList.toggle('show');
    let customer_type_info_container = document.querySelector('.customer_type_info_container');
    customer_type_info_container.classList.toggle('show_container');
    if (customer_type_info_container.classList.contains('show_container')) {
        localStorage.setItem('showCustomerTypeInfoContainer', 'true');
    } else {
        localStorage.removeItem('showCustomerTypeInfoContainer');
    }
});

let customer_type_list_label = document.querySelector('#customer_type_list_label');
customer_type_list_label.addEventListener("click", (e) => {
    customer_type_list_label.classList.toggle('show');
    let customer_type_list_container = document.querySelector('.customer_type_list_container');
    customer_type_list_container.classList.toggle('show_container');
    if (customer_type_list_container.classList.contains('show_container')) {
        localStorage.setItem('showCustomerTypeListContainer', 'true');
    } else {
        localStorage.removeItem('showCustomerTypeListContainer');
    }
});

new DataTable('#customer_type_list', {
    scrollX: true,
});

$("#clear").click(function() {
    $("#customer_type_name").val("");
    $("#other_name").val("");
    $("#customer_type_code").val("");
    $('#is_discontinued').prop('checked', false);;
});

$(document).on("click", ".edit_customerType_modal_dialog", function() {
    var customer_type_id = $(this).data('customer_type_id');
    var customer_type_name = $(this).data('customer_type_name');
    var other_name = $(this).data('other_name');
    var customer_type_code = $(this).data('customer_type_code');
    var is_discontinued = $(this).data('is_discontinued');

    $(".modal-body #edit_customer_type_id").val(customer_type_id);
    $(".modal-body #edit_customer_type_name").val(customer_type_name);
    $(".modal-body #edit_other_name").val(other_name);
    $(".modal-body #edit_customer_type_code").val(customer_type_code);
    if (is_discontinued == 1) {
        document.getElementById("edit_customer_type_is_discontinued").checked = true;
    } else {
        document.getElementById("edit_customer_type_is_discontinued").checked = false;
    }
});

$(document).on("click", ".delete_customerType_modal_dialog", function() {
    var customer_type_id = $(this).data('customer_type_id');
    var customer_type_name = $(this).data('customer_type_name');
    $("#delete_modal_header").text("Delete '" + customer_type_name + "'")
    $("#delete_customer_type_id").val(customer_type_id);
});

$(function() {
    $("#customerTypeEditModalForm").validate({
        rules: {
            edit_customer_type_name: {
                required: true,
            },
            edit_customer_type_code: {
                required: true,
            },
        },
        messages: {
            edit_customer_type_name: {
                required: "Customer Type Name ဖြည့်ရန်လိုအပ်ပါသည်",
            },
            edit_customer_type_code: {
                required: "Customer Type Code ဖြည့်ရန်လိုအပ်ပါသည်",
            },
        }
    });

});