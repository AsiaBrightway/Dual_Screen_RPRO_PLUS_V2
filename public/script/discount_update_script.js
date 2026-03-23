let discount_list = document.querySelector(".discount-list");
if (localStorage.getItem('showMenu')) {
    // category_list.classList.add("showMenu");
    discount_list.parentElement.parentElement.classList.add("showMenu");
    discount_list.parentElement.parentElement.parentElement.parentElement.classList.add("showMenu");
}


var main_category_id = $('#main_category').val();


$('#main_category').change(function() {
    var mainCategory_id = $(this).val();
    $.ajax({
        type: 'GET',
        url: '/admin/item/getSubCategory',
        data: { 'mainCategoryID': mainCategory_id },
        success: function(data) {
            $('#sub_category').empty();
            $.each(data, function(key, value) {

                $('#sub_category').append('<option value="' + value
                    .category_id +
                    '">' +
                    value.menu_category_name + '</option>');
            });
            var subCategory_id = $("#sub_category").val();
            $.ajax({
                type: 'GET',
                url: '/admin/item/getItem',
                data: {
                    'mainCategoryID': mainCategory_id,
                    'subCategoryID': subCategory_id,
                },
                success: function(data) {
                    $('#items').empty();
                    $.each(data, function(key, value) {

                        $('#items').append('<option value="' + value
                            .item_id +
                            '">' +
                            value.item_name + '</option>');
                    });
                    if (subCategory_id == null) {
                        $("#item_price").val("");
                    }
                    var item_id = $("#items").val();
                    $.ajax({
                        type: 'GET',
                        url: '/admin/item/getItemPrice',
                        data: {
                            'itemID': item_id
                        },
                        success: function(data) {
                            $('#item_price').empty();
                            $.each(data, function(key, value) {
                                $("#item_price").val(value.item_price);
                            });
                            if (item_id == null) {
                                $("#item_price").val("");
                            }
                        }
                    });

                }
            });
            if (mainCategory_id == null) {
                $("#item_price").val("");
            }


            $('#amount_lbl').prop('checked', true);
            $('#percent_lbl').prop('checked', false);
            $('#amount_discount').prop('readonly', false).val("").removeClass('muted');
            $('#percent_discount').prop('readonly', true).val("0").addClass('muted');
            $('#promotion_price').val("");

        }
    });


});

$('#sub_category').change(function() {
    var mainCategory_id = $('#main_category').val();
    var subCategory_id = $(this).val();
    $.ajax({
        type: 'GET',
        url: '/admin/item/getItem',
        data: {
            'mainCategoryID': mainCategory_id,
            'subCategoryID': subCategory_id
        },
        success: function(data) {
            $('#items').empty();
            $.each(data, function(key, value) {

                $('#items').append('<option value="' + value
                    .item_id +
                    '">' +
                    value.item_name + '</option>');
            });
            if (subCategory_id == null) {
                $("#item_price").val("");
            }
            var item_id = $("#items").val();
            if (item_id != "") {
                $.ajax({
                    type: 'GET',
                    url: '/admin/item/getItemPrice',
                    data: {
                        'itemID': item_id
                    },
                    success: function(data) {
                        $('#item_price').empty();
                        $.each(data, function(key, value) {
                            $('#item_price').val(value.item_price);
                        });
                        if (item_id == null) {
                            $("#item_price").val("");
                        }
                    }
                });
            } else {
                $('#item_price').val("");
            }
            $('#amount_lbl').prop('checked', true);
            $('#percent_lbl').prop('checked', false);
            $('#amount_discount').prop('readonly', false).val("").removeClass('muted');
            $('#percent_discount').prop('readonly', true).val("0").addClass('muted');
            $('#promotion_price').val("");


        }
    });

});

$('#items').change(function() {
    var item_id = $(this).val();
    $.ajax({
        type: 'GET',
        url: '/admin/item/getItemPrice',
        data: {
            'itemID': item_id
        },
        success: function(data) {
            $('#item_price').empty();
            $.each(data, function(key, value) {
                $('#item_price').val(value.item_price);
            });
            $('#amount_lbl').prop('checked', true);
            $('#percent_lbl').prop('checked', false);
            $('#amount_discount').prop('readonly', false).val("").removeClass('muted');
            $('#percent_discount').prop('readonly', true).val("0").addClass('muted');
            $('#promotion_price').val("");
        }
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

function calculation() {
    var qty = isNaN(parseFloat($('#buy_quantity').val())) ? 0 : parseFloat($('#buy_quantity').val());
    var itemPrice = isNaN(parseFloat($("#item_price").val())) ? 0 : parseFloat($("#item_price").val());
    var amountDiscount = isNaN(parseFloat($('#amount_discount').val())) ? 0 : parseFloat($('#amount_discount').val());
    var percentDiscount = isNaN(parseFloat($('#percent_discount').val())) ? 0 : parseFloat($('#percent_discount').val());

    var promoPercent = (itemPrice * qty) * (percentDiscount / 100);
    var promotionPrice = (itemPrice * qty) - (amountDiscount + promoPercent);

    $("#promotion_price").val(parseInt(promotionPrice));
}