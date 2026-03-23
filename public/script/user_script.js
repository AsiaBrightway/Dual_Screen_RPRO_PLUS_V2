let user_list = document.querySelector(".user-list");
if (localStorage.getItem('showMenu')) {
    // category_list.classList.add("showMenu");
    user_list.parentElement.parentElement.classList.add("showMenu");
    user_list.parentElement.parentElement.parentElement.parentElement.classList.add("showMenu");
}

let user_info_container = document.querySelector('.user_info_container');
if (localStorage.getItem('showUserInfoContainer')) {
    user_info_container.classList.add("show_container");
}

let user_list_container = document.querySelector('.user_list_container');
if (localStorage.getItem('showUserListContainer')) {
    user_list_container.classList.add("show_container");
}

let user_info_label = document.querySelector('#user_info_label');
user_info_label.addEventListener("click", (e) => {
    user_info_label.classList.toggle('show');
    let user_info_container = document.querySelector('.user_info_container');
    user_info_container.classList.toggle('show_container');
    if (user_info_container.classList.contains('show_container')) {
        localStorage.setItem('showUserInfoContainer', 'true');
    } else {
        localStorage.removeItem('showUserInfoContainer');
    }
});

let user_list_label = document.querySelector('#user_list_label');
user_list_label.addEventListener("click", (e) => {
    user_list_label.classList.toggle('show');
    let user_list_container = document.querySelector('.user_list_container');
    user_list_container.classList.toggle('show_container');
    if (user_list_container.classList.contains('show_container')) {
        localStorage.setItem('showUserListContainer', 'true');
    } else {
        localStorage.removeItem('showUserListContainer');
    }
});

new DataTable('#user_list', {
    scrollX: true
});

$("#clear").click(function() {
    $("#employee_name").val('0');
    $('#employee_code').val('');
    $('#user_role').val('0');
    $('#user_name').val('');
    $('#password').val('');
    $('#confirm_password').val('');
    $("#is_discontinued").prop('checked', false);

});

$('#employee_name').change(function() {
    var employee_id = $(this).val();
    $.ajax({
        type: 'GET',
        url: 'user/getEmployeeCode',
        data: {
            'employee_id': employee_id
        },
        success: function(data) {
            $('#employee_code').val('');
            $('#employee_name_txt').val('');
            $.each(data, function(key, value) {
                $('#employee_code').val(value.employee_code);
                $('#employee_name_txt').val(value.employee_name);
            });

        }
    });

});

$(document).on("click", ".edit_user_modal_dialog", function() {
    var user_id = $(this).data('user_id');
    var user_name = $(this).data('user_name');
    var employee_name = $(this).data('employee_name');
    var employee_code = $(this).data('employee_code');
    var user_role_id = $(this).data('user_role_id');
    var is_discontinued = $(this).data('is_discontinued');

    $(".modal-body #edit_user_id").val(user_id);
    $(".modal-body #edit_employee_name").val(employee_name);
    $(".modal-body #edit_employee_code").val(employee_code);
    $(".modal-body #edit_user_role").val(user_role_id);
    $(".modal-body #edit_user_name").val(user_name);


    if (is_discontinued == 1) {
        document.getElementById("edit_user_is_discontinued").checked = true;
    } else {
        document.getElementById("edit_user_is_discontinued").checked = false;
    }

    $(function() {
        $("#userEditModalForm").validate({
            rules: {
                edit_password: {
                    // required: true,
                    minlength: 6
                },
                edit_confirm_password: {
                    // required: true,
                    equalTo: "#edit_password"
                },

            },
            messages: {
                // edit_password: {
                //     required: "Password ဖြည့်ရန်လိုအပ်ပါသည်",
                // },
                employee_code: {
                    minlength: "Password သည် အနည်းဆုံး ၆လုံး ရှိရပါမည်",
                },
                // edit_confirm_password: {
                //     required: "Confirm Password ဖြည့်ရန်လိုအပ်ပါသည်",
                // },
                edit_confirm_password: {
                    equalTo: "Confirm Password သည် Password နှင့် တူရပါမည်",
                },

            }
        });

    });
});

$(document).on("click", ".delete_user_modal_dialog", function() {
    var user_id = $(this).data('user_id');
    var employee_name = $(this).data('employee_name');
    $("#delete_modal_header").text("Delete '" + employee_name + "' user account!")
    $("#delete_user_id").val(user_id);
});
