let delivery_list = document.querySelector(".delivery-list");
if (localStorage.getItem('showMenu')) {
    // category_list.classList.add("showMenu");
    delivery_list.parentElement.parentElement.classList.add("showMenu");
}
let delivery_info_container = document.querySelector('.delivery_info_container');
if (localStorage.getItem('showDeliveryInfoContainer')) {
    delivery_info_container.classList.add("show_container");
}

let delivery_list_container = document.querySelector('.delivery_list_container');
if (localStorage.getItem('showDeliveryListContainer')) {
    delivery_list_container.classList.add("show_container");
}

let delivery_info_label = document.querySelector('#delivery_info_label');
delivery_info_label.addEventListener("click", (e) => {
    delivery_info_label.classList.toggle('show');
    let delivery_info_container = document.querySelector('.delivery_info_container');
    delivery_info_container.classList.toggle('show_container');
    if (delivery_info_container.classList.contains('show_container')) {
        localStorage.setItem('showDeliveryInfoContainer', 'true');
    } else {
        localStorage.removeItem('showDeliveryInfoContainer');
    }
});

let delivery_list_label = document.querySelector('#delivery_list_label');
delivery_list_label.addEventListener("click", (e) => {
    delivery_list_label.classList.toggle('show');
    let delivery_list_container = document.querySelector('.delivery_list_container');
    delivery_list_container.classList.toggle('show_container');
    if (delivery_list_container.classList.contains('show_container')) {
        localStorage.setItem('showDeliveryListContainer', 'true');
    } else {
        localStorage.removeItem('showDeliveryListContainer');
    }
});

new DataTable('#delivery_list', {
    scrollX: true,
    columns: [
        { width: "50px" },
        { width: "140px" },
        { width: "150px" },
        { width: "130px" },
        { width: "150px" },
        { width: "200px" },
        { width: "200px" },
        { width: "120px" },
        { width: "50px" },
        { width: "70px" },
    ]
});

$("#clear").click(function() {
    $("#company_name").val('');
    $('#phone_number').val('');
    $("#city").val('0');
    $('#township').val('0');
    $('#address').val('');
    $('#remark').val('');
    $('#is_discontinued').prop('checked', false);
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

$(document).on("click", ".edit_delivery_modal_dialog", function() {
    var delivery_id = $(this).data('delivery_id');
    var company_name = $(this).data('company_name');
    var phone_number = $(this).data('phone_number');
    var city_id = $(this).data('city_id');
    var township_id = $(this).data('township_id');
    var address = $(this).data('address');
    var remark = $(this).data('remark');
    var delivery_is_discontinued = $(this).data('delivery_is_discontinued');


    $(".modal-body #edit_delivery_id").val(delivery_id);
    $(".modal-body #edit_company_name").val(company_name);
    $(".modal-body #edit_phone_number").val(phone_number);
    $(".modal-body #city").val(city_id);
    $(".modal-body #township").val(township_id);
    $(".modal-body #edit_address").val(address);
    $(".modal-body #edit_remark").val(remark);

    if (delivery_is_discontinued == 1) {
        document.getElementById("edit_delivery_is_discontinued").checked = true;
    } else {

        document.getElementById("edit_delivery_is_discontinued").checked = false;
    }
    $.ajax({
        type: 'GET',
        url: "/admin/city/getTownship",
        data: { 'cityID': city_id },
        success: function(data) {
            $('#edit_township').empty();
            $.each(data, function(key, value) {

                $('#edit_township').append(
                    '<option value="' +
                    value.township_id + '" ' +
                    (value.township_id == township_id ? 'selected' :
                        '') +
                    '>' +
                    value.township_name + '</option>');
            });
        }
    });

    $('#edit_city').change(function() {
        var city_id = $(this).val();
        $.ajax({
            type: 'GET',
            url: "/admin/city/getTownship",
            data: { 'cityID': city_id },
            success: function(data) {
                $('#edit_township').empty();
                $.each(data, function(key, value) {

                    $('#edit_township').append('<option value="' + value
                        .township_id +
                        '">' +
                        value.township_name + '</option>');
                });
            }
        });
    });
});

$(function() {
    $("#deliveryFormEdit").validate({
        rules: {
            edit_company_name: {
                required: true,
            },
            edit_phone_number: {
                required: true,
                digits: true,
                rangelength: [7, 11],
            },
            edit_address: {
                required: true,
            },

        },
        messages: {
            edit_company_name: {
                required: "Company Name ဖြည့်ရန်လိုအပ်ပါသည်",
            },
            edit_phone_number: {
                required: "Phone Number ဖြည့်ရန်လိုအပ်ပါသည်",
                digits: "Phone Number သည် ကိန်းဂဏန်းများဖြစ်ရမည်",
                rangelength: "Phone Number သည် မှားယွင်းနေပါသည်",
            },
            edit_address: {
                required: "Address ဖြည့်ရန်လိုအပ်ပါသည်",
            },
        }
    });

});

$(document).on("click", ".delete_delivery_modal_dialog", function() {
    var delivery_id = $(this).data('delivery_id');
    var company_name = $(this).data('company_name');
    $("#delete_modal_header").text("Delete '" + company_name + "'")
    $("#delete_delivery_id").val(delivery_id);
});