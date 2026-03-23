let customer_list = document.querySelector(".customer-list");
if (localStorage.getItem('showMenu')) {
    // category_list.classList.add("showMenu");
    customer_list.classList.add("showMenu");
}

let customer_info_container = document.querySelector('.customer_info_container');
if (localStorage.getItem('showCustomerInfoContainer')) {
    customer_info_container.classList.add("show_container");
}

let customer_list_container = document.querySelector('.customer_list_container');
if (localStorage.getItem('showCustomerListContainer')) {
    customer_list_container.classList.add("show_container");
}

let customer_info_label = document.querySelector('#customer_info_label');
customer_info_label.addEventListener("click", (e) => {
    customer_info_label.classList.toggle('show');
    let customer_info_container = document.querySelector('.customer_info_container');
    customer_info_container.classList.toggle('show_container');
    if (customer_info_container.classList.contains('show_container')) {
        localStorage.setItem('showCustomerInfoContainer', 'true');
    } else {
        localStorage.removeItem('showCustomerInfoContainer');
    }
});

let customer_list_label = document.querySelector('#customer_list_label');
customer_list_label.addEventListener("click", (e) => {
    customer_list_label.classList.toggle('show');
    let customer_list_container = document.querySelector('.customer_list_container');
    customer_list_container.classList.toggle('show_container');
    if (customer_list_container.classList.contains('show_container')) {
        localStorage.setItem('showCustomerListContainer', 'true');
    } else {
        localStorage.removeItem('showCustomerListContainer');
    }
});

new DataTable('#customer_list', {
    scrollX: true,
    columns: [
        { width: "50px" },
        { width: "150px" },
        { width: "130px" },
        { width: "130px" },
        { width: "150px" },
        { width: "80px" },
        { width: "120px" },
        { width: "130px" },
        { width: "150px" },
        { width: "150px" },
        { width: "150px" },
        { width: "150px" },
        { width: "150px" },
        { width: "120px" },
        { width: "80px" },
        { width: "80px" },
    ]
});

$("#clear").click(function() {
    $("#customer_name").val("");
    $("#other_name").val("");
    $("#customer_code").val("");
    $("#customer_type").val("0");
    $("#gender").val("0");
    $("#date_of_birth").val("");
    $("#phone_number").val("");
    $("#email").val("");
    $("#city").val("0");
    $("#township").val("");
    $("#address").val("");
    $("#remark").val("");
    $('#is_discontinued').prop('checked', false);;
});

$('#city').change(function() {
    var city_id = $(this).val();
    $.ajax({
        type: 'GET',
        url: "/admin/city/getTownship",
        data: { 'cityID': city_id },
        success: function(data) {
            $('#township').empty();
            $.each(data, function(key, value) {

                $('#township').append('<option value="' + value
                    .township_id +
                    '">' +
                    value.township_name + '</option>');
            });
        }
    });
});

$(document).on("click", ".delete_customer_modal_dialog", function() {
    var customer_id = $(this).data('customer_id');
    var customer_name = $(this).data('customer_name');
    $("#delete_modal_header").text("Delete '" + customer_name + "'")
    $("#delete_customer_id").val(customer_id);
});
