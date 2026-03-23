let card_list = document.querySelector(".card-list");
if (localStorage.getItem('showMenu')) {
    // category_list.classList.add("showMenu");
    card_list.parentElement.parentElement.classList.add("showMenu");
    card_list.parentElement.parentElement.parentElement.parentElement.classList.add("showMenu");
}

let member_card_info_container = document.querySelector('.member_card_info_container');
if (localStorage.getItem('showMemberCardInfoContainer')) {
    member_card_info_container.classList.add("show_container");
}

let member_card_list_container = document.querySelector('.member_card_list_container');
if (localStorage.getItem('showMemberCardListContainer')) {
    member_card_list_container.classList.add("show_container");
}

let member_card_info_label = document.querySelector('#member_card_info_label');
member_card_info_label.addEventListener("click", (e) => {
    member_card_info_label.classList.toggle('show');
    let member_card_info_container = document.querySelector('.member_card_info_container');
    member_card_info_container.classList.toggle('show_container');
    if (member_card_info_container.classList.contains('show_container')) {
        localStorage.setItem('showMemberCardInfoContainer', 'true');
    } else {
        localStorage.removeItem('showMemberCardInfoContainer');
    }
});

let member_card_list_label = document.querySelector('#member_card_list_label');
member_card_list_label.addEventListener("click", (e) => {
    member_card_list_label.classList.toggle('show');
    let member_card_list_container = document.querySelector('.member_card_list_container');
    member_card_list_container.classList.toggle('show_container');
    if (member_card_info_container.classList.contains('show_container')) {
        localStorage.setItem('showMemberCardListContainer', 'true');
    } else {
        localStorage.removeItem('showMemberCardListContainer');
    }
});

new DataTable('#member_card_list', {
    scrollX: true,
    columns: [
        { width: "50px" },
        { width: "100px" },
        { width: "150px" },
        { width: "130px" },
        { width: "150px" },
        { width: "130px" },
        { width: "150px" },
        { width: "180px" },
        { width: "120px" },
        { width: "120px" },
        { width: "80px" },
        { width: "200px" },
        { width: "120px" },
        { width: "50px" },
        { width: "70px" },
    ]
});

$("#clear").click(function() {
    $("#customer").val('0');
    $('#member_card_type').val('0');
    $('#amount_discount').val('');
    $("#percent_discount").val('');
    $('#card_code').val('');
    $('#create_date').val('');
    $('#expire_date').val('');
    $('#remark').val('');
    $('#is_discontinued').prop('checked', false);;

});
$('#member_card_type').change(function() {
    var member_card_type_id = $(this).val();

    if (member_card_type_id == 0) {
        $('#amount_discount').val("");
        $('#percent_discount').val("");
    } else {
        $.ajax({
            type: 'GET',
            url: "memberCardType/memberCardType",
            data: { 'memberCardTypeID': member_card_type_id },
            success: function(data) {
                $('#amount_discount').empty();
                $('#percent_discount').empty();
                $.each(data, function(key, value) {
                    $('#amount_discount').val(value.amount_discount);
                    $('#percent_discount').val(value.percent_discount);
                });
            }
        });
    }

});


$(document).on("click", ".edit_memberCard_modal_dialog", function() {
    var member_card_id = $(this).data('member_card_id');
    var customer_id = $(this).data('customer_id');
    var member_card_type_id = $(this).data('member_card_type_id');
    var amount_discount = $(this).data('amount_discount');
    var percent_discount = $(this).data('percent_discount');
    var card_code = $(this).data('card_code');
    var create_date = $(this).data('create_date');
    var expire_date = $(this).data('expire_date');
    var remark = $(this).data('remark');
    var member_card_is_discontinued = $(this).data('member_card_is_discontinued');

    $(".modal-body #edit_member_card_id").val(member_card_id);
    $(".modal-body #edit_customer").val(customer_id);
    $(".modal-body #edit_member_card_type").val(member_card_type_id);
    $(".modal-body #edit_amount_discount").val(amount_discount);
    $(".modal-body #edit_percent_discount").val(percent_discount);
    $(".modal-body #edit_card_code").val(card_code);
    $(".modal-body #edit_create_date").val(convertDataFormat(create_date));
    $(".modal-body #edit_expire_date").val(convertDataFormat(expire_date));
    $(".modal-body #edit_remark").val(remark);

    if (member_card_is_discontinued == 1) {
        document.getElementById("edit_member_card_is_discontinued").checked = true;
    } else {
        document.getElementById("edit_member_card_is_discontinued").checked = false;
    }

    function convertDataFormat(dateString) {
        var inputDateString = dateString;
        var inputDate = new Date(inputDateString);

        // Extract year, month, and day components
        var year = inputDate.getFullYear();
        var month = (inputDate.getMonth() + 1).toString().padStart(2, '0'); // Months are 0-based, so add 1
        var day = inputDate.getDate().toString().padStart(2, '0');

        // Format the date in "yyyy-MM-dd" format
        var formattedDate = year + '-' + month + '-' + day;
        return formattedDate;
    }

    $('#edit_member_card_type').change(function() {
        var member_card_type_id = $(this).val();

        if (member_card_type_id == 0) {
            $('#edit_amount_discount').val("");
            $('#edit_percent_discount').val("");
        } else {
            $.ajax({
                type: 'GET',
                url: "memberCardType/memberCardType",
                data: { 'memberCardTypeID': member_card_type_id },
                success: function(data) {
                    $('#edit_amount_discount').empty();
                    $('#edit_percent_discount').empty();
                    $.each(data, function(key, value) {
                        $('#edit_amount_discount').val(value.amount_discount);
                        $('#edit_percent_discount').val(value.percent_discount);
                    });
                }
            });
        }

    });

    $(function() {
        $("#memberCardEditModalForm").validate({
            rules: {
                edit_card_code: {
                    required: true
                },
                edit_create_date: {
                    required: true
                },
                edit_expire_date: {
                    required: true
                },
            },
            messages: {
                edit_card_code: {
                    required: "Card Code ဖြည့်ရန်လိုအပ်ပါသည်",
                },
                edit_create_date: {
                    required: "Create Date ရွေးရန်လိုအပ်ပါသည်",
                },
                edit_expire_date: {
                    required: "Expire Date ရွေးရန်လိုအပ်ပါသည်",
                },

            }
        });

    });
});

$(document).on("click", ".delete_memberCard_modal_dialog", function() {
    var member_card_id = $(this).data('member_card_id');
    var card_code = $(this).data('card_code');
    $("#delete_modal_header").text("Delete '" + card_code + "'")
    $("#delete_member_card_id").val(member_card_id);
});