var table_list = document.querySelector(".table-list");
if (localStorage.getItem('showMenu')) {
    table_list.classList.add("showMenu");
    table_list.parentElement.parentElement.classList.add("showMenu");
    table_list.parentElement.parentElement.parentElement.parentElement.classList.add("showMenu");
}
let table_info_container = document.querySelector('.table_info_container');
if (localStorage.getItem('showTableInfoContainer')) {
    table_info_container.classList.add("show_container");
}

let table_list_container = document.querySelector('.table_list_container');
if (localStorage.getItem('showTableListContainer')) {
    table_list_container.classList.add("show_container");
}

let table_info_label = document.querySelector('#table_info_label');
table_info_label.addEventListener("click", (e) => {
    table_info_label.classList.toggle('show');
    let table_info_container = document.querySelector('.table_info_container');
    table_info_container.classList.toggle('show_container');
    if (table_info_container.classList.contains('show_container')) {
        localStorage.setItem('showTableInfoContainer', 'true');
    } else {
        localStorage.removeItem('showTableInfoContainer');
    }
});

let table_list_label = document.querySelector('#table_list_label');
table_list_label.addEventListener("click", (e) => {
    table_list_label.classList.toggle('show');
    let table_list_container = document.querySelector('.table_list_container');
    table_list_container.classList.toggle('show_container');
    if (table_list_container.classList.contains('show_container')) {
        localStorage.setItem('showTableListContainer', 'true');
    } else {
        localStorage.removeItem('showTableListContainer');
    }
});

new DataTable('#table_list', {
    scrollX: true
});

$("#clear").click(function() {
    $("#table_name").val('');
    $('#other_name').val('');
    $("#floor").val('0');
    $('#is_discontinued').prop('checked', false);

});

$(document).on("click", ".edit_table_modal_dialog", function() {
    var table_id = $(this).data('table_id');
    var table_name = $(this).data('table_name');
    var other_name = $(this).data('other_name');
    var floor_id = $(this).data('floor_id');
    var is_discontinued = $(this).data('is_discontinued');


    $(".modal-body #edit_table_id").val(table_id);
    $(".modal-body #edit_table_name").val(table_name);
    $(".modal-body #edit_other_name").val(other_name);
    $(".modal-body #edit_floor").val(floor_id);


    if (is_discontinued == 1) {
        document.getElementById("edit_is_discontinued").checked = true;
    } else {
        document.getElementById("edit_is_discontinued").checked = false;
    }

    $(function() {
        $("#tableEditModalForm").validate({
            rules: {
                edit_table_name: {
                    required: true
                },

            },
            messages: {
                edit_table_name: {
                    required: "Table Name ဖြည့်ရန်လိုအပ်ပါသည်",
                },

            }
        });

    });
});

$(document).on("click", ".delete_table_modal_dialog", function() {
    var table_id = $(this).data('table_id');
    var table_name = $(this).data('table_name');
    $("#delete_modal_header").text("Delete '" + table_name + "'")
    $("#delete_table_id").val(table_id);
});
