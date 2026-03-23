let sale_list = document.querySelector(".sale-lists");
if (localStorage.getItem("showMenu")) {
    // category_list.classList.add("showMenu");
    // sale_list.parentElement.parentElement.classList.add("showMenu");
    // sale_list.parentElement.parentElement.parentElement.parentElement.classList.add(
    //     "showMenu"
    // );
    sale_list.parentElement.parentElement.classList.add("showMenu");
}

let sale_list_label = document.querySelector("#sale_list_label");
sale_list_label.addEventListener("click", (e) => {
    sale_list_label.classList.toggle("show");
    let sale_list_container = document.querySelector(".sale_list_container");
    sale_list_container.classList.toggle("show_container");
});

new DataTable("#sale_list", {
    scrollX: true,
});



$(document).on("click", ".delete_sale_modal_dialog", function () {
    var sale_voucher_number = $(this).data("sale_voucher_number");
    var sale_id = $(this).data("sale_id");
    $(".modal-header #delete_modal_header").text(
        "Are you sure want to delete '" + sale_voucher_number + "' ?"
    );
    $(".modal-body #delete_sale_id").val(sale_id);
});
$(function () {
    $("#saleDeleteModalForm").validate({
        rules: {
            sale_delete_reason: {
                required: true,
            },
        },
        messages: {
            sale_delete_reason: {
                required: "Delete Reason ဖြည့်ရန်လိုအပ်ပါသည်",
            },
        },
    });
});

$("#btn_dailyPrint").click(function () {
    var dailyPrintDate = $("#dailyPrintDate").val();
    if (dailyPrintDate == '') {
        console.log("It is empty");
    } else {
        $.ajax({
            type: "GET",
            url: "sale/dailyPrint",
            data: { dailyPrintDate: dailyPrintDate },
            success: function (data) {
                // Open a new print window
                var printWindow = window.open('', '_blank');
                if (printWindow) {
                    // Write the HTML content to the print window
                    printWindow.document.open();
                    printWindow.document.write(data);
                    printWindow.document.close();

                    // Wait for the content to load
                    printWindow.onload = function () {
                        printWindow.print();
                        printWindow.onafterprint = function () {
                            printWindow.close();
                        };
                    };
                } else {
                    console.error("Pop-up blocked. Please enable pop-ups for this site.");
                }
            },
            error: function (xhr, status, error) {
                console.error('Print request failed:', error);
            },
        });
    }
});