var supplier_list = document.querySelector(".supplier-list");
if (localStorage.getItem("showMenu")) {
    supplier_list.classList.add("showMenu");
}
new DataTable("#supplier_list", {
    scrollX: true,
    columns: [
        { width: "50px" },
        { width: "130px" },
        { width: "130px" },
        { width: "130px" },
        { width: "150px" },
        { width: "180px" },
        { width: "180px" },
        { width: "180px" },
        { width: "200px" },
        { width: "150px" },
        { width: "120px" },
        { width: "80px" },
        { width: "80px" }
    ]
});

$(document).on("click", ".edit_supplier_modal_dialog", function() {
    console.log("Hello");
    $("#edit_city").change(function() {
        var city_id = $(this).val();

        $.ajax({
            type: "GET",
            url: "/admin/city/getTownship",
            data: { cityID: city_id },
            success: function(data) {
                $("#edit_township").empty();
                $.each(data, function(key, value) {
                    $("#edit_township").append(
                        '<option value="' +
                        value.township_id +
                        '">' +
                        value.township_name +
                        "</option>"
                    );
                });
            },
        });
    });

    var supplier_id = $(this).data("supplier_id");
    var supplier_name = $(this).data("supplier_name");
    var supplier_other_name = $(this).data("supplier_other_name");
    var supplier_code = $(this).data("supplier_code");
    var phone_number = $(this).data("phone_number");
    var email = $(this).data("email");
    var city_id = $(this).data("city_id");
    var township_id = $(this).data("township_id");
    var address = $(this).data("address");
    var remark = $(this).data("remark");
    var supplier_is_discontinued = $(this).data("supplier_is_discontinued");

    // console.log(city_id);
    // console.log(township_id);

    $.ajax({
        type: "GET",
        url: "/admin/city/getTownship",
        data: { cityID: city_id },
        success: function(data) {
            $("#edit_township").empty();
            $.each(data, function(key, value) {
                $("#edit_township").append(
                    '<option value="' +
                    value.township_id +
                    '" ' +
                    (value.township_id == township_id ? "selected" : "") +
                    ">" +
                    value.township_name +
                    "</option>"
                );
            });
        },
    });

    $(".modal-body #edit_supplier_id").val(supplier_id);
    $(".modal-body #edit_supplier_name").val(supplier_name);
    $(".modal-body #edit_other_name").val(supplier_other_name);
    $(".modal-body #edit_supplier_code").val(supplier_code);
    $(".modal-body #edit_phone_number").val(phone_number);
    $(".modal-body #edit_email").val(email);
    $(".modal-body #edit_city").val(city_id);
    $(".modal-body #edit_township").val(township_id);
    $(".modal-body #edit_address").val(address);
    $(".modal-body #edit_remark").val(remark);

    if (supplier_is_discontinued == 1) {
        document.getElementById("edit_supplier_is_discontinued").checked = true;
    } else {
        document.getElementById(
            "edit_supplier_is_discontinued"
        ).checked = false;
    }

    $(function() {
        $("#supplierEditModalForm").validate({
            rules: {
                edit_supplier_name: {
                    required: true,
                },
                edit_supplier_code: {
                    required: true,
                },
                edit_phone_number: {
                    required: true,
                },
                edit_city: {
                    required: true,
                },
                edit_township: {
                    required: true,
                },
                edit_address: {
                    required: true,
                },
            },
            messages: {
                edit_supplier_name: {
                    required: "Supplier Name ဖြည့်ရန်လိုအပ်ပါသည်",
                },
                edit_supplier_code: {
                    required: "Supplier Code ဖြည့်ရန်လိုအပ်ပါသည်",
                },
                edit_phone_number: {
                    required: "Phone Number ဖြည့်ရန်လိုအပ်ပါသည်",
                },
                edit_city: {
                    required: "City ရွေးရန်လိုအပ်ပါသည်",
                },
                edit_township: {
                    required: "Township ရွေးရန်လိုအပ်ပါသည်",
                },
                edit_address: {
                    required: "Address ဖြည့်ရန်လိုအပ်ပါသည်",
                },
            },
        });
    });
});

$(document).on("click", ".delete_supplier_modal_dialog", function() {
    var supplier_id = $(this).data("supplier_id");
    var supplier_name = $(this).data("supplier_name");
    $("#delete_modal_header").text(
        "Delete '" + supplier_name + "'"
    );
    $("#delete_supplier_id").val(supplier_id);
});