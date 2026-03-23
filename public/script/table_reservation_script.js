// var dine_in = document.querySelector(".reservation");
// if (localStorage.getItem("showMenu")) {
//     dine_in.parentElement.parentElement.classList.add("showMenu");
//     dine_in.parentElement.parentElement.parentElement.parentElement.classList.add(
//         "showMenu"
//     );
// }

var reservation = document.querySelector(".reservation");
if (localStorage.getItem("showMenu")) {
    reservation.parentElement.parentElement.classList.add("showMenu");
}

var today = new Date();
var dd = String(today.getDate()).padStart(2, "0");
var mm = String(today.getMonth() + 1).padStart(2, "0"); //January is 0!
var yyyy = today.getFullYear();

today = yyyy + "-" + mm + "-" + dd;
document.getElementById("reservation_date").value = today;

$("#reservation_date").on("change", function() {
    var reservation_date = $("#reservation_date").val();
    var selected_floor_ID = $('input[name="btnradio"]:checked').val();

    $("#name").val("");
    $("#phone_number").val("");
    $("#number_of_person").val("");
    $("#reservation_time").val("");
    $(".formButton_div").empty();
    $(".formButton_div").append(
        $("<button>", {
            type: "submit",
            class: "btn",
            style: "background: #512DA8; color: white; font-weight: bold; height: 50px;",
            form: "reservationForm",
            id: "reserveBtn",
            text: "Reserve",
        })
    );
    // console.log(selectedFloorID);

    $.ajax({
        type: "GET",
        url: "table/getTableByDate",
        data: {
            reservationDate: reservation_date,
            selectedFloorID: selected_floor_ID,
        },
        success: function(data) {
            $(".table_div").empty();
            $.each(data.tables, function(index, value) {
                var occupied =
                    $.inArray(value.table_id, data.occupiedTables) !== -1;
                var reserved =
                    $.inArray(value.table_id, data.reservationTables) !== -1;
                var disabled = occupied ? "disabled" : "";
                var backgroundColor = occupied ?
                    "red" :
                    reserved ?
                    "orange" :
                    "white";
                var textColor = occupied ?
                    "white" :
                    reserved ?
                    "white" :
                    "black";
                $(".table_div").append(
                    '<button class="btn m-2 table-button" style="width: 105px; height: 105px; border-radius: 20px; background: ' +
                    backgroundColor +
                    "; color: " +
                    textColor +
                    ';" data-table-value="' +
                    value.table_name +
                    '" data-table_id = "' +
                    value.table_id +
                    '" data-floor_id ="' +
                    value.floor_id +
                    '" data-floor_name="' +
                    value.floor_name +
                    '"' +
                    disabled +
                    ">" +
                    value.table_name +
                    "</button>"
                );
            });
        }, // Move the closing parenthesis to here
    });
});
$('input[name="btnradio"]').on("change", function() {
    var selectedFloorID = $('input[name="btnradio"]:checked').val();

    $.ajax({
        type: "GET",
        url: "floor/getTable",
        data: {
            selectedFloorID: selectedFloorID,
        },
        success: function(data) {
            $(".table_div").empty();
            var availableCount = 0;
            var reservedCount = 0;
            var occupiedCount = 0;
            console.log(data);
            $.each(data.tables, function(index, value) {
                var occupied =
                    $.inArray(value.table_id, data.occupiedTables) !== -1;
                var reserved =
                    $.inArray(value.table_id, data.reservationTables) !== -1;
                if (occupied) {
                    occupiedCount++;
                } else if (reserved) {
                    reservedCount++;
                } else {
                    availableCount++;
                }
                var disabled = occupied ? "disabled" : "";
                var backgroundColor = occupied ?
                    "red" :
                    reserved ?
                    "orange" :
                    "white";
                var textColor = occupied ?
                    "white" :
                    reserved ?
                    "white" :
                    "black";
                $(".table_div").append(
                    '<button class="btn m-2 table-button" style="width: 105px; height: 105px; border-radius: 20px; background: ' +
                    backgroundColor +
                    "; color: " +
                    textColor +
                    ';" data-table-value="' +
                    value.table_name +
                    '" data-table_id = "' +
                    value.table_id +
                    '" data-floor_id ="' +
                    value.floor_id +
                    '" data-floor_name="' +
                    value.floor_name +
                    '"' +
                    disabled +
                    ">" +
                    value.table_name +
                    "</button>"
                );
            });
            $('#availableLabel').text("Available - " + availableCount);
            $('#reservationLabel').text("Reservation - " + reservedCount);
            $('#occupiedLabel').text("Occupied - " + occupiedCount);
        }, // Move the closing parenthesis to here
    });
});
var tableName = $("#table_name").val();
if (tableName != "") {
    $("#reserveBtn").prop("disabled", false);
}

$(document).on("click", ".table-button", function() {
    var tableName = $(this).data("table-value");
    var tableID = $(this).data("table_id");

    var reservation_date = $("#reservation_date").val();

    $("button").removeClass("selected");
    $(this).addClass("selected");
    $("#reserveBtn").prop("disabled", false);

    $("#table_id").val(tableID);
    $("#table_name").val(tableName);

    $.ajax({
        type: "GET",
        url: "table/getReservation",
        data: {
            tableID: tableID,
            reservationDate: reservation_date,
        },
        success: function(data) {
            // console.log(data.length);
            $(".formButton_div").empty();
            if (data.length != 0) {
                $("#name").val(data[0]["name"]);
                $("#phone_number").val(data[0]["phone_number"]);
                $("#number_of_person").val(data[0]["number_of_person"]);
                $("#reservation_date").val(data[0]["reservation_date"]);
                $("#reservation_time").val(data[0]["reservation_time"]);
                $(".formButton_div").append(
                    $("<button>", {
                        type: "button",
                        class: "btn",
                        style: "background: orange; color: white; font-weight: bold; height: 50px;",
                        form: "reservationForm",
                        id: "cancel_reserveBtn",
                        text: "Cancel Reserved",
                    })
                );
                $(document).on("click", "#cancel_reserveBtn", function() {
                    $.ajax({
                        type: "GET",
                        url: "reservation/delete",
                        data: {
                            tableID: tableID,
                            reservationDate: reservation_date,
                        },
                        success: function(data) {
                            var form = document.getElementById(
                                "reservationFormReload"
                            );
                            form.submit();
                        }, // Move the closing parenthesis to here
                    });
                });
            } else {
                $("#name").val("");
                $("#phone_number").val("");
                $("#number_of_person").val("");
                $("#reservation_time").val("");
                $(".formButton_div").append(
                    $("<button>", {
                        type: "submit",
                        class: "btn",
                        style: "background: #512DA8; color: white; font-weight: bold; height: 50px;",
                        form: "reservationForm",
                        id: "reserveBtn",
                        text: "Reserve",
                    })
                );
            }
        }, // Move the closing parenthesis to here
    });
});

    // $('#reserveBtn').click(function(e) {
    //     console.log("reserve button");
    //     e.preventDefault();

    //     let reserveLog = {
    //         table_id: $('#tableID').val(),
    //         name: $('#name').val(),
    //         phone_number: $('#phone_number').val(),
    //         number_of_person: $('#number_of_person').val(),
    //         reservation_date: $('#reservation_date').val(),
    //         reservation_time: $('#reservation_time').val()
    //     }

    //     $.ajax({
    //         type: 'post',
    //         url: 'reservation/create',
    //         data: reserveLog,
    //         success: function(response) {
    //             if (response.success) {
    //                 $('#successModal').modal('show');
    //             } else if (response.errors) {
    //                 console.log(response.errors);
    //             }
    //         }
            
    //     })
    // })