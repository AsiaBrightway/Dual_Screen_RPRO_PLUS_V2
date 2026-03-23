let employee_list = document.querySelector(".employee-list");
if (localStorage.getItem('showMenu')) {
    // category_list.classList.add("showMenu");
    employee_list.parentElement.parentElement.classList.add("showMenu");
    employee_list.parentElement.parentElement.parentElement.parentElement.classList.add("showMenu");
}

let employee_info_container = document.querySelector('.employee_info_container');
if (localStorage.getItem('showEmployeeInfoContainer')) {
    employee_info_container.classList.add("show_container");
}

let employee_list_container = document.querySelector('.employee_list_container');
if (localStorage.getItem('showEmployeeListContainer')) {
    employee_list_container.classList.add("show_container");
}

let employee_info_label = document.querySelector('#employee_info_label');
employee_info_label.addEventListener("click", (e) => {
    employee_info_label.classList.toggle('show');
    let employee_info_container = document.querySelector('.employee_info_container');
    employee_info_container.classList.toggle('show_container');
    if (employee_info_container.classList.contains('show_container')) {
        localStorage.setItem('showEmployeeInfoContainer', 'true');
    } else {
        localStorage.removeItem('showEmployeeInfoContainer');
    }
});

let employee_list_label = document.querySelector('#employee_list_label');
employee_list_label.addEventListener("click", (e) => {
    employee_list_label.classList.toggle('show');
    let employee_list_container = document.querySelector('.employee_list_container');
    employee_list_container.classList.toggle('show_container');
    if (employee_list_container.classList.contains('show_container')) {
        localStorage.setItem('showEmployeeListContainer', 'true');
    } else {
        localStorage.removeItem('showEmployeeListContainer');
    }
});

new DataTable('#employee_list', {
    scrollX: true
});

$("#clear").click(function() {
    $("#employee_name").val('');
    $('#other_name').val('');
    $('#employee_code').val('');
    $('#employee_position').val('0');
    $('#is_terminate').prop('checked', false);

});

$(document).on("click", ".edit_employee_modal_dialog", function() {
    var employee_id = $(this).data('employee_id');
    var employee_name = $(this).data('employee_name');
    var other_name = $(this).data('other_name');
    var employee_code = $(this).data('employee_code');
    var employee_position_id = $(this).data('employee_position_id');
    var is_terminate = $(this).data('is_terminate');


    $(".modal-body #edit_employee_id").val(employee_id);
    $(".modal-body #edit_employee_name").val(employee_name);
    $(".modal-body #edit_other_name").val(other_name);
    $(".modal-body #edit_employee_code").val(employee_code);
    $(".modal-body #edit_employee_position").val(employee_position_id);


    if (is_terminate == 1) {
        document.getElementById("edit_is_terminate").checked = true;
    } else {
        document.getElementById("edit_is_terminate").checked = false;
    }

    $(function() {
        $("#employeeEditModalForm").validate({
            rules: {
                edit_employee_name: {
                    required: true
                },
                edit_employee_code: {
                    required: true
                },

            },
            messages: {
                edit_employee_name: {
                    required: "Employee Name ဖြည့်ရန်လိုအပ်ပါသည်",
                },
                edit_employee_code: {
                    required: "Employee Code ဖြည့်ရန်လိုအပ်ပါသည်",
                },

            }
        });

    });
});

$(document).on("click", ".delete_employee_modal_dialog", function() {
    var employee_id = $(this).data('employee_id');
    var employee_name = $(this).data('employee_name');
    $("#delete_modal_header").text("Delete '" + employee_name + "'")
    $("#delete_employee_id").val(employee_id);
});
