let card_type_list = document.querySelector(".card-type-list");
if (localStorage.getItem('showMenu')) {
    // category_list.classList.add("showMenu");
    card_type_list.parentElement.parentElement.classList.add("showMenu");
    card_type_list.parentElement.parentElement.parentElement.parentElement.classList.add("showMenu");
}

let member_card_type_info_container = document.querySelector('.member_card_type_info_container');
if (localStorage.getItem('showMemberCardTypeInfoContainer')) {
    member_card_type_info_container.classList.add("show_container");
}

let member_card_type_list_container = document.querySelector('.member_card_type_list_container');
if (localStorage.getItem('showMemberCardTypeListContainer')) {
    member_card_type_list_container.classList.add("show_container");
}

let member_card_type_info_label = document.querySelector('#member_card_type_info_label');
member_card_type_info_label.addEventListener("click", (e) => {
    member_card_type_info_label.classList.toggle('show');
    let member_card_type_info_container = document.querySelector('.member_card_type_info_container');
    member_card_type_info_container.classList.toggle('show_container');
    if (member_card_type_info_container.classList.contains('show_container')) {
        localStorage.setItem('showMemberCardTypeInfoContainer', 'true');
    } else {
        localStorage.removeItem('showMemberCardTypeInfoContainer');
    }
});

let member_card_type_list_label = document.querySelector('#member_card_type_list_label');
member_card_type_list_label.addEventListener("click", (e) => {
    member_card_type_list_label.classList.toggle('show');
    let member_card_type_list_container = document.querySelector('.member_card_type_list_container');
    member_card_type_list_container.classList.toggle('show_container');
    if (member_card_type_list_container.classList.contains('show_container')) {
        localStorage.setItem('showMemberCardTypeListContainer', 'true');
    } else {
        localStorage.removeItem('showMemberCardTypeListContainer');
    }
});

new DataTable('#member_card_type_list', {
    scrollX: true,
    columns: [
        { width: "50px" },
        { width: "150px" },
        { width: "180px" },
        { width: "120px" },
        { width: "150px" },
        { width: "180px" },
        { width: "220px" },
        { width: "120px" },
        { width: "50px" },
        { width: "70px" },
    ]
});

$("#clear").click(function() {
    $("#card_type_name").val('');
    $('#other_name').val('');
    $('#amount_lbl').prop('checked', true);
    $('#percent_lbl').prop('checked', false);
    $('#amount_discount').prop('readonly', false).val("").removeClass('muted');
    $('#percent_discount').prop('readonly', true).val("0").addClass('muted');
    $("#remark").val("");
    $("#is_discontinued").prop('checked', false);
});

$('.discount-type').change(function() {
    if (this.value == "Amount") {
        $('#percent_discount').prop('readonly', true).val("0").addClass('muted');
        $('#amount_discount').prop('readonly', false).val("").removeClass('muted');
        $('#promotion_price').val("");


    } else {
        $('#amount_discount').prop('readonly', true).val("0").addClass('muted');
        $('#percent_discount').prop('readonly', false).val("").removeClass('muted');
        $('#promotion_price').val("");
    }
});

$(document).on("click", ".edit_memberCardType_modal_dialog", function() {
    var member_card_type_id = $(this).data('member_card_type_id');
    var member_card_type_name = $(this).data('member_card_type_name');
    var other_name = $(this).data('other_name');
    var discount_type = $(this).data('discount_type');
    var amount_discount = $(this).data('amount_discount');
    var percent_discount = $(this).data('percent_discount');
    var remark = $(this).data('remark');
    var is_discontinued = $(this).data('is_discontinued');


    $(".modal-body #edit_member_card_type_id").val(member_card_type_id);
    $(".modal-body #edit_member_card_type_name").val(member_card_type_name);
    $(".modal-body #edit_other_name").val(other_name);
    $(".modal-body #edit_amount_discount").val(amount_discount);
    $(".modal-body #edit_percent_discount").val(percent_discount);
    $(".modal-body #edit_remark").val(remark);

    if (discount_type == "Amount") {
        $('#edit_amount_lbl').prop('checked', true);
        $('#edit_percent_lbl').prop('checked', false);

        $('#edit_percent_discount').prop('readonly', true).addClass('muted');
        $('#edit_amount_discount').prop('readonly', false).removeClass('muted');

    } else {
        $('#edit_amount_lbl').prop('checked', false);
        $('#edit_percent_lbl').prop('checked', true);

        $('#edit_amount_discount').prop('readonly', true).addClass('muted');
        $('#edit_percent_discount').prop('readonly', false).removeClass('muted');
    }


    if (is_discontinued == 1) {
        document.getElementById("edit_member_card_type_is_discontinued").checked = true;
    } else {
        document.getElementById("edit_member_card_type_is_discontinued").checked = false;
    }

    $('.edit_discount-type').change(function() {
        if (this.value == "Amount") {
            $('#edit_percent_discount').prop('readonly', true).val("0").addClass('muted');
            $('#edit_amount_discount').prop('readonly', false).val("").removeClass('muted');

        } else {
            $('#edit_amount_discount').prop('readonly', true).val("0").addClass('muted');
            $('#edit_percent_discount').prop('readonly', false).val("").removeClass('muted');
        }
    });

    $(function() {
        $("#memberCardTypeEditModalForm").validate({
            rules: {
                edit_member_card_type_name: {
                    required: true
                },
                edit_amount_discount: {
                    required: true,
                    number: true
                },
                edit_percent_discount: {
                    required: true,
                    number: true
                }


            },
            messages: {
                edit_member_card_type_name: {
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

            }
        });

    });
});

$(document).on("click", ".delete_memberCardType_modal_dialog", function() {
    var member_card_type_id = $(this).data('member_card_type_id');
    var member_card_type_name = $(this).data('member_card_type_name');
    $("#delete_modal_header").text("Delete '" + member_card_type_name + "'")
    $("#delete_member_card_type_id").val(member_card_type_id);
});