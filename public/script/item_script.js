new DataTable("#item_list", {
    scrollX: true,
    columns: [
        { width: "50px" },
        { width: "100px" },
        { width: "150px" },
        { width: "150px" },
        { width: "100px" },
        { width: "80px" },
        { width: "130px" },
        { width: "120px" },
        { width: "100px" },
        { width: "100px" },
        { width: "120px" },
        { width: "70px" },
        { width: "70px" }
    ]
});

$("#clear").click(function () {
    $("#main_category").val("0");
    $("#sub_category").val("0");
    $("#item_type").val("0");
    $("#item_code").val("");
    $("#bar_code").val("");
    $("#item_name").val("");
    $("#other_name").val("");
    $("#item_unit").val("0");
    $("#item_image").val("");
    $("#is_discontinued").prop("checked", false);
});

let item_list = document.querySelector(".item-list");
if (localStorage.getItem("showMenu")) {
    // category_list.classList.add("showMenu");
    item_list.parentElement.parentElement.classList.add("showMenu");
    item_list.parentElement.parentElement.parentElement.parentElement.classList.add(
        "showMenu"
    );
}
let item_info_container = document.querySelector(".item_info_container");
if (localStorage.getItem("showItemInfoContainer")) {
    item_info_container.classList.add("show_container");
}

let item_list_container = document.querySelector(".item_list_container");
if (localStorage.getItem("showItemListContainer")) {
    item_list_container.classList.add("show_container");
}

let item_info_label = document.querySelector("#item_info_label");
item_info_label.addEventListener("click", (e) => {
    item_info_label.classList.toggle("show");
    let item_info_container = document.querySelector(".item_info_container");
    item_info_container.classList.toggle("show_container");
    if (item_info_container.classList.contains("show_container")) {
        localStorage.setItem("showItemInfoContainer", "true");
    } else {
        localStorage.removeItem("showItemInfoContainer");
    }
});

let item_list_label = document.querySelector("#item_list_label");
item_list_label.addEventListener("click", (e) => {
    item_list_label.classList.toggle("show");
    let item_list_container = document.querySelector(".item_list_container");
    item_list_container.classList.toggle("show_container");
    if (item_list_container.classList.contains("show_container")) {
        localStorage.setItem("showItemListContainer", "true");
    } else {
        localStorage.removeItem("showItemListContainer");
    }
});

$("#main_category").change(function () {
    var mainCategory_id = $(this).val();
    $.ajax({
        type: "GET",
        url: "item/item",
        data: { mainCategoryID: mainCategory_id },
        success: function (data) {
            $("#sub_category").empty();
            $.each(data, function (key, value) {
                $("#sub_category").append(
                    '<option value="' +
                    value.category_id +
                    '">' +
                    value.menu_category_name +
                    "</option>"
                );
            });
        },
    });
});

$(document).on("click", ".edit_item_modal_dialog", function () {
    $("#edit_main_category").change(function () {
        var mainCategory_id = $(this).val();
        $.ajax({
            type: "GET",
            url: "item/item",
            data: { mainCategoryID: mainCategory_id },
            success: function (data) {
                $("#edit_sub_category").empty();
                $.each(data, function (key, value) {
                    $("#edit_sub_category").append(
                        '<option value="' +
                        value.category_id +
                        '">' +
                        value.menu_category_name +
                        "</option>"
                    );
                });
            },
        });
    });

    var item_id = $(this).data("item_id");
    var main_category_id = $(this).data("main_category_id");
    var sub_category_id = $(this).data("sub_category_id");
    var item_type_id = $(this).data("item_type_id");
    var item_code = $(this).data("item_code");
    var bar_code = $(this).data("bar_code");
    var item_name = $(this).data("item_name");
    var other_name = $(this).data("item_other_name");
    var unit_id = $(this).data("unit_id");
    var item_is_discontinued = $(this).data("item_is_discontinued");

    $.ajax({
        type: "GET",
        url: "item/item",
        data: { mainCategoryID: main_category_id },
        success: function (data) {
            $("#edit_sub_category").empty();
            $.each(data, function (key, value) {
                $("#edit_sub_category").append(
                    '<option value="' +
                    value.category_id +
                    '" ' +
                    (value.category_id == sub_category_id ?
                        "selected" :
                        "") +
                    ">" +
                    value.menu_category_name +
                    "</option>"
                );
            });
        },
    });

    $(".modal-body #edit_item_id").val(item_id);
    $(".modal-body #edit_item_code").val(item_code);
    $(".modal-body #edit_bar_code").val(bar_code);
    $(".modal-body #edit_item_name").val(item_name);
    $(".modal-body #edit_other_name").val(other_name);

    document.getElementById("edit_main_category").value = main_category_id;
    document.getElementById("edit_item_type").value = item_type_id;
    document.getElementById("edit_item_unit").value = unit_id;

    if (item_is_discontinued == 1) {
        document.getElementById("edit_is_discontinued").checked = true;
    } else {
        document.getElementById("edit_is_discontinued").checked = false;
    }

    $(function () {
        $("#itemEditModalForm").validate({
            rules: {
                edit_item_code: {
                    required: true,
                },
                edit_bar_code: {
                    required: true,
                },
                edit_item_name: {
                    required: true,
                },
            },
            messages: {
                edit_item_code: {
                    required: "Item Code ဖြည့်ရန်လိုအပ်ပါသည်",
                },
                edit_bar_code: {
                    required: "Bar Code ဖြည့်ရန်လိုအပ်ပါသည်",
                },
                edit_item_name: {
                    required: "Item Name ဖြည့်ရန်လိုအပ်ပါသည်",
                },
            },
        });
    });
});
$(document).on("click", ".deleteItemBtn", function () {

    let id = $(this).data("id");
    let name = $(this).data("name");
    let hasOrders = $(this).data("has-orders");

    $("#delete_item_id").val(id);

    if (hasOrders == 1) {
        $("#deleteItemModalTitle").text("Cannot Delete Item");
        $("#deleteItemMessage").html(
            "This item has order records.<br>Please remove orders first."
        );

        $("#confirmItemDeleteBtn").addClass("d-none");
    } else {
        $("#deleteItemModalTitle").text("Delete '" + name + "'");
        $("#deleteItemMessage").text(
            "Are you sure you want to delete this item?"
        );

        $("#confirmItemDeleteBtn").removeClass("d-none");
    }

    $("#deleteItemModal").modal("show");
});

// Burmese to English Initial Mapping - Auto-generate Item Code from Item Name
$(document).ready(function () {

    const burmeseMap = {
        'က': 'C', 'ခ': 'K', 'ဂ': 'G', 'ဃ': 'G', 'င': 'N',
        'စ': 'S', 'ဆ': 'S', 'ဇ': 'Z', 'ဈ': 'Z', 'ဉ': 'N', 'ည': 'N',
        'ဋ': 'T', 'ဌ': 'T', 'ဍ': 'D', 'ဎ': 'D', 'ဏ': 'N',
        'တ': 'T', 'ထ': 'H', 'ဒ': 'D', 'ဓ': 'D', 'န': 'N',
        'ပ': 'P', 'ဖ': 'P', 'ဗ': 'B', 'ဘ': 'B', 'မ': 'M',
        'ယ': 'Y', 'ရ': 'Y', 'လ': 'L', 'ဝ': 'W', 'သ': 'T',
        'ဟ': 'H', 'ဠ': 'L', 'အ': 'A'
    };

    // Generate the letter prefix from item name
    function generateCodePrefix(name) {
        let code = '';
        let regex = /[က-အ](?![္်])/gu;
        let matches = name.match(regex);

        if (matches && matches.length > 0) {
            matches.forEach(function (char) {
                code += burmeseMap[char] || '';
            });
        } else {
            let words = name.split(/\s+/);
            words.forEach(function (word) {
                if (word.length > 0) {
                    code += word.charAt(0).toUpperCase();
                }
            });
        }
        return code;
    }

    // Debounce timer variable
    let codeTimer = null;

    // Create form: #item_name -> #item_code
    $('#item_name').on('input', function () {
        let name = $(this).val().trim();

        if (name === '') {
            $('#item_code').val('');
            return;
        }

        let prefix = generateCodePrefix(name);
        if (prefix === '') return;

        // Debounce: wait 300ms after user stops typing before making AJAX call
        clearTimeout(codeTimer);
        codeTimer = setTimeout(function () {
            $.ajax({
                type: "GET",
                url: "item/getNextItemCodeNumber",
                data: { prefix: prefix },
                success: function (data) {
                    $('#item_code').val(prefix + data.next_number);
                }
            });
        }, 300);
    });

    // Edit modal: #edit_item_name -> #edit_item_code, #edit_bar_code
    // Store original values when modal opens
    let originalEditItemCode = '';
    let originalEditItemId = '';

    $(document).on("click", ".edit_item_modal_dialog", function () {
        originalEditItemCode = $(this).data("item_code") || '';
        originalEditItemId = $(this).data("item_id") || '';
    });

    let editCodeTimer = null;

    $('#edit_item_name').on('input', function () {
        let name = $(this).val().trim();

        if (name === '') {
            $('#edit_item_code').val('');
            $('#edit_bar_code').val('');
            return;
        }

        let prefix = generateCodePrefix(name);
        if (prefix === '') return;

        // If the current code already starts with this prefix, keep it unchanged
        if (originalEditItemCode.toString().startsWith(prefix)) {
            $('#edit_item_code').val(originalEditItemCode);
            $('#edit_bar_code').val(originalEditItemCode);
            return;
        }

        // Prefix has changed, get next available number (excluding current item)
        clearTimeout(editCodeTimer);
        editCodeTimer = setTimeout(function () {
            $.ajax({
                type: "GET",
                url: "item/getNextItemCodeNumber",
                data: { prefix: prefix, exclude_id: originalEditItemId },
                success: function (data) {
                    let newCode = prefix + data.next_number;
                    $('#edit_item_code').val(newCode);
                    $('#edit_bar_code').val(newCode);
                }
            });
        }, 300);
    });

});