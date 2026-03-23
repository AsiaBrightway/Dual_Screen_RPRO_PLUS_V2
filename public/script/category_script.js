let category_list = document.querySelector(".category-list");
if (localStorage.getItem("showMenu")) {
    // category_list.classList.add("showMenu");
    category_list.parentElement.parentElement.classList.add("showMenu");
    category_list.parentElement.parentElement.parentElement.parentElement.classList.add(
        "showMenu"
    );
}
// let bar_container = document.querySelector(".bar_container");
// if (localStorage.getItem("showTable1")) {
//     bar_container.classList.add("show_container");
// }

// let kitchen_container = document.querySelector(".kitchen_container");
// if (localStorage.getItem("showTable2")) {
//     kitchen_container.classList.add("show_container");
// }

// let refrigerator_container = document.querySelector(".refrigerator_container");
// if (localStorage.getItem("showTable3")) {
//     refrigerator_container.classList.add("show_container");
// }

// let service_container = document.querySelector(".service_container");
// if (localStorage.getItem("showTable4")) {
//     service_container.classList.add("show_container");
// }

// let noodle_container = document.querySelector(".noodle_container");
// if (localStorage.getItem("showTable5")) {
//     noodle_container.classList.add("show_container");
// }

// let cuisine_container = document.querySelector(".cuisine_container");
// if (localStorage.getItem("showTable6")) {
//     cuisine_container.classList.add("show_container");
// }

document.addEventListener("DOMContentLoaded", function() {

    // Restore opened accordion
    document.querySelectorAll(".accordion-collapse").forEach((item) => {
        let id = item.id;
        if (localStorage.getItem("open_" + id)) {
            new bootstrap.Collapse(item, { show: true });
        }
    });

    // Save on toggle
    document.querySelectorAll(".accordion-button").forEach(btn => {

        btn.addEventListener("click", function() {
            let target = btn.getAttribute("data-bs-target").replace('#', '');

            if (localStorage.getItem("open_" + target)) {
                localStorage.removeItem("open_" + target);
            } else {
                localStorage.setItem("open_" + target, true);
            }
        });

    });

});

$(document).on("click", ".editCategoryBtn", function() {

    let id = $(this).data("id");
    let name = $(this).data("name");
    let discontinued = $(this).data("discontinued");
    let mainName = $(this).data("main");

    // Fill modal fields
    $("#edit_category_id").val(id);
    $("#edit_category_name").val(name);
    $("#edit_is_discontinued").prop("checked", discontinued == 1);

    // Dynamic title
    $("#editModalTitle").text("Update " + mainName + " Category");

    // Show modal
    $("#editCategoryModal").modal("show");
});

$(document).on("click", ".deleteCategoryBtn", function() {

    let id = $(this).data("id");
    let name = $(this).data("name");
    let hasItems = $(this).data("has-items");

    $("#delete_category_id").val(id);

    if (hasItems == 1) {
        $("#deleteModalTitle").text("Cannot Delete Category");
        $("#deleteMessage").text(
            "This category has menu items. Please remove menu items first."
        );
        $("#confirmDeleteBtn").hide();

    } else {
        $("#deleteModalTitle").text("Delete " + name + " Category");
        $("#deleteMessage").text(
            "Are you sure you want to delete this category?"
        );
        $("#confirmDeleteBtn").show();
    }

    $("#deleteCategoryModal").modal("show");
});



$(document).on("click", ".editMainCategoryBtn", function() {
    let id = $(this).data("id");
    let name = $(this).data("name");
    let discontinued = $(this).data("discontinued");
    console.log(id);
    // Fill modal fields
    $("#edit_main_category_id").val(id);
    $("#edit_main_category_name").val(name);
    $("#edit_main_is_discontinued").prop("checked", discontinued == 1);

    // Change modal title
    $("#mainCategoryEditTitle").text("Update " + name + " Category");
});


$(document).on("click", ".deleteMainCategoryBtn", function() {

    let id = $(this).data("id");
    let name = $(this).data("name");

    $("#delete_main_category_id").val(id);

    $("#deleteMainCategoryTitle").text("Delete " + name + " ?");
    $("#deleteMainCategoryMessage").text("Are you sure you want to delete?");

});

// $("#createMainCategoryForm").on("submit", function(e) {
//     e.preventDefault(); // prevent reload on error

//     let form = $(this);
//     let formData = new FormData(this);

//     $.ajax({
//         url: form.attr("action"),
//         method: "POST",
//         data: formData,
//         processData: false,
//         contentType: false,

//         success: function(response) {
//             // CLEAR errors
//             $("#nameError").html("");
//             form.find("input").removeClass("is-invalid");

//             // Close modal
//             $("#create_main_category_modal").modal("hide");

//         },

//         error: function(xhr) {
//             let errors = xhr.responseJSON.errors;

//             // Clear old errors
//             $("#nameError").html("");
//             form.find("input").removeClass("is-invalid");

//             // Show validation error without reload
//             if (errors && errors.main_category_name) {
//                 $("input[name='main_category_name']").addClass("is-invalid");
//                 $("#nameError").html(errors.main_category_name[0]);
//             }
//         }
//     });

// });

$('#create_main_category_modal').on('hidden.bs.modal', function() {

    // Clear Laravel error messages
    $(this).find('.invalid-feedback').html('');

    // Remove red border
    $(this).find('.is-invalid').removeClass('is-invalid');

    // Optional: clear input values
    $(this).find('input').each(function() {
        if ($(this).attr('type') !== 'hidden' && $(this).attr('name') !== '_token') {

            if ($(this).attr('type') === 'checkbox') {
                $(this).prop('checked', false);
            } else {
                $(this).val('');
            }

        }
    });
});

// $("#editMainCategoryForm").on("submit", function(e) {
//     e.preventDefault(); // prevent reload on error

//     let form = $(this);
//     let formData = new FormData(this);

//     $.ajax({
//         url: form.attr("action"),
//         method: "POST",
//         data: formData,
//         processData: false,
//         contentType: false,

//         success: function(response) {

//             // Clear errors
//             $("#editNameError").html("");
//             $("#edit_main_category_name").removeClass("is-invalid");

//             // Close modal
//             $("#mainCategoryEditModal").modal("hide");
//         },

//         error: function(xhr) {
//             let errors = xhr.responseJSON.errors;

//             // Clear old error
//             $("#editNameError").html("");
//             $("#edit_main_category_name").removeClass("is-invalid");

//             // Show error under input
//             if (errors && errors.main_category_name) {
//                 $("#edit_main_category_name").addClass("is-invalid");
//                 $("#editNameError").html(errors.main_category_name[0]);
//             }
//         }
//     });

// });

$('#mainCategoryEditModal').on('hidden.bs.modal', function() {

    // Clear Laravel/AJAX error messages
    $(this).find('.invalid-feedback').html('');

    // Remove red borders
    $(this).find('.is-invalid').removeClass('is-invalid');

    // Optional: clear input values
    $(this).find('input').each(function() {
        if ($(this).attr('type') !== 'hidden' && $(this).attr('name') !== '_token') {

            if ($(this).attr('type') === 'checkbox') {
                $(this).prop('checked', false);
            } else {
                $(this).val('');
            }

        }
    });

});

// ---------------------------
// MODAL FORM HANDLER
// ---------------------------

// Save original values when modal opens
$(document).on('show.bs.modal', '.modal', function() {
    const modal = $(this);

    // Save inputs, selects, textareas
    modal.find('input, select, textarea').each(function() {
        const type = $(this).attr('type');
        const name = $(this).attr('name');

        if (name !== '_token' && type !== 'hidden' && type !== 'submit' && type !== 'button') {
            const value = (type === 'checkbox') ? $(this).prop('checked') : $(this).val();
            $(this).data('original-value', value);
        }
    });

    // Save buttons (like update/save)
    modal.find('button[type="submit"]').each(function() {
        $(this).data('original-value', $(this).val());
    });
});

// Reset modal on close
$(document).on('hidden.bs.modal', '.modal', function() {
    const modal = $(this);

    // Clear error messages and red borders
    modal.find('.invalid-feedback').html('');
    modal.find('.is-invalid').removeClass('is-invalid');

    // Reset inputs, selects, textareas
    modal.find('input, select, textarea').each(function() {
        const type = $(this).attr('type');
        const name = $(this).attr('name');

        if (type === 'hidden' || name === '_token') return;
        if (type === 'submit' || type === 'button') return;

        if (type === 'checkbox') {
            $(this).prop('checked', $(this).data('original-value') || false);
        } else if (type === 'file') {
            $(this).val(null); // always clear file inputs
        } else {
            $(this).val($(this).data('original-value') || '');
        }
    });

    // Restore buttons
    modal.find('button[type="submit"]').each(function() {
        $(this).val($(this).data('original-value'));
    });
});

// ---------------------------
// CREATE MENU CATEGORY AJAX
// ---------------------------
// $(document).on('submit', '.createMenuCategoryForm', function(e) {
//     e.preventDefault();
//     const form = $(this);
//     const modal = form.closest('.modal');
//     const formData = new FormData(this);

//     $.ajax({
//         url: form.attr('action'),
//         method: 'POST',
//         data: formData,
//         processData: false,
//         contentType: false,
//         success: function() {
//             form.find('.menuCategoryError').html('');
//             form.find('input').removeClass('is-invalid');
//             modal.modal('hide');

//         },
//         error: function(xhr) {
//             if (xhr.status === 422) {
//                 const errors = xhr.responseJSON.errors;
//                 form.find('.menuCategoryError').html('');
//                 form.find('input').removeClass('is-invalid');

//                 if (errors && errors.menu_category_name) {
//                     form.find("input[name='menu_category_name']").addClass('is-invalid');
//                     form.find('.menuCategoryError').html(errors.menu_category_name[0]);
//                 }
//             }
//         }
//     });
// });

// ---------------------------
// EDIT MENU CATEGORY AJAX
// ---------------------------
// $(document).on('submit', '#editMenuCategoryForm', function(e) {
//     e.preventDefault();
//     const form = $(this);
//     const formData = new FormData(this);

//     $.ajax({
//         url: form.attr('action'),
//         method: 'POST',
//         data: formData,
//         processData: false,
//         contentType: false,
//         success: function() {
//             $('#edit_category_name').removeClass('is-invalid');
//             $('#editCategoryNameError').html('');
//             $('#editCategoryModal').modal('hide');

//         },
//         error: function(xhr) {
//             if (xhr.status === 422) {
//                 const errors = xhr.responseJSON.errors;
//                 $('#edit_category_name').removeClass('is-invalid');
//                 $('#editCategoryNameError').html('');

//                 if (errors.edit_category_name) {
//                     $('#edit_category_name').addClass('is-invalid');
//                     $('#editCategoryNameError').html(errors.edit_category_name[0]);
//                 }
//             }
//         }
//     });
// });
// bar_label.addEventListener("click", (e) => {
//     bar_label.classList.toggle("show");
//     let bar_container = document.querySelector(".bar_container");
//     bar_container.classList.toggle("show_container");
//     if (bar_container.classList.contains("show_container")) {
//         localStorage.setItem("showTable1", "true");
//     } else {
//         localStorage.removeItem("showTable1");
//     }
// });

// let kitchen_label = document.querySelector("#kitchen_label");
// kitchen_label.addEventListener("click", (e) => {
//     kitchen_label.classList.toggle("show");
//     let kitchen_container = document.querySelector(".kitchen_container");
//     kitchen_container.classList.toggle("show_container");
//     if (kitchen_container.classList.contains("show_container")) {
//         localStorage.setItem("showTable2", "true");
//     } else {
//         localStorage.removeItem("showTable2");
//     }
// });

// let refrigerator_label = document.querySelector("#refrigerator_label");
// refrigerator_label.addEventListener("click", (e) => {
//     refrigerator_label.classList.toggle("show");
//     let refrigerator_container = document.querySelector(
//         ".refrigerator_container"
//     );
//     refrigerator_container.classList.toggle("show_container");
//     if (refrigerator_container.classList.contains("show_container")) {
//         localStorage.setItem("showTable3", "true");
//     } else {
//         localStorage.removeItem("showTable3");
//     }
// });

// let service_label = document.querySelector("#service_label");
// service_label.addEventListener("click", (e) => {
//     service_label.classList.toggle("show");
//     let service_container = document.querySelector(".service_container");
//     service_container.classList.toggle("show_container");
//     if (service_container.classList.contains("show_container")) {
//         localStorage.setItem("showTable4", "true");
//     } else {
//         localStorage.removeItem("showTable4");
//     }
// });

// let noodle_label = document.querySelector("#noodle_label");
// noodle_label.addEventListener("click", (e) => {
//     noodle_label.classList.toggle("show");
//     let noodle_container = document.querySelector(".noodle_container");
//     noodle_container.classList.toggle("show_container");
//     if (noodle_container.classList.contains("show_container")) {
//         localStorage.setItem("showTable5", "true");
//     } else {
//         localStorage.removeItem("showTable5");
//     }
// });

// let cuisine_label = document.querySelector("#cuisine_label");
// cuisine_label.addEventListener("click", (e) => {
//     cuisine_label.classList.toggle("show");
//     let cuisine_container = document.querySelector(".cuisine_container");
//     cuisine_container.classList.toggle("show_container");
//     if (cuisine_container.classList.contains("show_container")) {
//         localStorage.setItem("showTable6", "true");
//     } else {
//         localStorage.removeItem("showTable6");
//     }
// });

$(".data-table").each(function() {
    $(this).DataTable({
        scrollX: true,
        pageLength: 10,
        ordering: false,
        lengthChange: true,
        searching: true
    });
});
