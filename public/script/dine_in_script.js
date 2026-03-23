    var dine_in = document.querySelector(".dine-in");

    if (localStorage.getItem("showMenu")) {
        dine_in.parentElement.parentElement.classList.add("showMenu");
    }

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
                        '">' +
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

    $(document).on("click", "#order-summary", function() {
        // var tableID = $(this).data("table_id");
        var tableID = $('#order-summary-table-id').val();
        console.log(tableID);

        $.ajax({
            type: "GET",
            url: "table/getOrderSummary",
            data: {
                tableID: tableID,
                tableOrderNumber: 1,
            },
            success: function(data) {
                console.log(data);
                populateItemSummary(data);
                $("#order_summary_modal").modal("show");
            }
        });
    });

    $('#order_summary_modal').on('hidden.bs.modal', function () {
        $(this).find('.modal-body').html('');
        $('#order-summary').prop('checked', false);
    });

    function populateItemSummary(items) {
        let tableRows = '';
        let totalQty = 0;
        let totalAmount = 0;

        items.forEach(function(item) {
            tableRows += `
                <tr>
                    <td>${item.item_name}</td>
                    <td class="text-center">${item.total_quantity}</td>
                    <td class="text-end">${parseInt(item.total_price).toLocaleString()}</td>
                </tr>
            `;
            totalQty += parseInt(item.total_quantity);
            totalAmount += parseInt(item.total_price);
        });

        const summaryTable = `
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th class="text-center">Qty</th>
                        <th class="text-end">Price</th>
                    </tr>
                </thead>
                <tbody>
                    ${tableRows}
                </tbody>
                <tfoot class="table-light">
                    <tr class="fw-semibold">
                        <td>Total</td>
                        <td class="text-center">${totalQty}</td>
                        <td class="text-end">${totalAmount.toLocaleString()}</td>
                    </tr>
                </tfoot>
            </table>
        `;

        $('#order_summary_modal .modal-body').html(summaryTable);
    }

    $(document).on("click", ".table-button", function() {
        var buttonValue = $(this).data("table-value");
        var tableID = $(this).data("table_id");

        $('#order-summary-table-id').val(tableID);
        $('.checkbox-group').removeClass('hidden');

        $("button").removeClass("selected");
        $(this).addClass("selected");

        $("#orderTableID").val(tableID);
        $("#orderTableOrderNumber").val(1);

        $("#floorID_merge").val($(this).data("floor_id"));
        $("#floorName_merge").val($(this).data("floor_name"));

        $("#orderTable").text("Table (" + buttonValue + "), ");
        $("#tableID").val(tableID);
        $("#tableName").val(buttonValue);
        $("#tableOrderValue").val(1);
        $("#addOrderLink").prop("disabled", false);
        $("#addOrderLink").css("cursor", "pointer");

        $.ajax({
            type: "GET",
            url: "table/getOrder",
            data: {
                tableID: tableID,
                tableOrderNumber: 1,
            },
            success: function(data) {

                orderDisplay(data);
            },
        });
    });

    function orderDisplay(data) {

        var orderTotalAmount = 0;
        var userRoleID = $("#user_role_id").val();
        //userRoleID = 4 is "waiter"
        $(".order_div").empty();

        if (data.length != 0) {
            if (userRoleID != 4) {
                $("#checkOutBtn").prop("disabled", false);

            } else {
                $("#checkOutBtn").prop("disabled", true);
            }

            $("#tableMergeLink").prop("disabled", false);
            $("#tableMergeLink").css("cursor", "pointer");
        } else {
            $("#checkOutBtn").prop("disabled", true);
            $("#tableMergeLink").prop("disabled", true);
            $("#tableMergeLink").css("cursor", "default");
        }

        for (var i = 0; i < data.length; i++) {

            let deleteButtonHtml = '';
            let quantityButtonHtml;
            if (userRoleID != 4) {
                deleteButtonHtml =
                    '<a href="#" class="delete_item_modal_dialog" data-toggle="tooltip" data-placement="top" title="Delete Item" data-bs-toggle="modal" data-bs-target="#delete_item_modal" data-order_item_index="' +
                    i +
                    '" data-order_detail_id="' +
                    data[i].order_detail_id +
                    '" data-item_name ="' +
                    data[i].item_name +
                    '" data-quantity ="' +
                    data[i].quantity +
                    '" data-item_id="' +
                    data[i].item_id +
                    '"><i class="fa-solid fa-circle-xmark" style="color:#512DA8"></i></a>';
                quantityButtonHtml =
                    '<input class="form-control quantity-input muted" type="number" value="' +
                    data[i]["quantity"] +
                    '" min="1" data-order_item_index="' +
                    i +
                    '" data-item-id="' +
                    data[i]["item_id"] +
                    '" readonly>' +
                    '<div style="display:flex; justify-content:end; margin-top:5px">' + '<button class="button-link" id="qtyEdit" style="font-size:12px">Edit</button>' + '</div>';
            } else {
                quantityButtonHtml =
                    '<input class="form-control quantity-input muted" type="number" value="' +
                    data[i]["quantity"] +
                    '" min="1" data-order_item_index="' +
                    i +
                    '" data-item-id="' +
                    data[i]["item_id"] +
                    '" readonly>';
            }

            var imageUrl = data[i]["item_image"] == null ?
                "/404_image.png" :
                "/storage/Images/" + data[i]["item_image"];
            $(".order_div").append(
                '<div class="row" style="align-items: center">' +
                '<div class="col-2 item_qty_input">' +
                quantityButtonHtml +
                "</div>" +
                '<div class="col-2">' +
                '<img class="item_img" loading="lazy" src="' +
                imageUrl +
                '" alt="" style="width: 65px; height:65px; border-radius: 10px">' +
                "</div>" +
                '<div class="col-3 dine_in_item_description_div " style="color:#512DA8">' +
                '<div class="row">' +
                "<label class='item_name_lbl'>" +
                data[i]["item_name"] +
                "</label>" +
                "</div>" +
                '<div class="row">' +
                '<a class="remark_lbl add_order_item_remark_preview_modal_dialog" style="text-decoration: none; cursor: pointer;" data-order_item_id="' +
                data[i]["item_id"] +
                '" data-order_item_name="' +
                data[i]["item_name"] +
                '" data-order_item_remark="' +
                data[i]["remark"] +
                '" data-bs-toggle="modal" data-bs-target="#add_order_item_preview_remark_modal"><span class="remark_lbl" style="font-size: 13px; color:red">+remark</span></a>' +
                "</div>" +
                "</div>" +
                '<div class="col-1 gift_check_div ">' +
                '<input id="giftCheckbox' +
                i +
                '" class="form-check-input visually-hidden gift-checkbox" type="checkbox" value="' +
                data[i].order_detail_id +
                "|" +
                i +
                '" ' +
                (data[i].is_foc == 1 || data[i].is_foc == "1" ?
                    "checked" :
                    "") +
                ">" +
                '<label for="giftCheckbox' +
                i +
                '"><i class="fas fa-gift gift-icon" style="cursor: pointer;"></i></label>' +
                "</div>" +
                '<div class="col-3 price_div">' +
                '<label class="item-price">' +
                (data[i].is_foc == 1 || data[i].is_foc == "1" ?
                    "0" :
                    (
                        data[i].item_price * data[i].quantity
                    ).toLocaleString()) +
                " MMK</label>" +
                "</div>" +
                '<div class="col-1">' +
                deleteButtonHtml +
                "</div>" +
                "</div>" +
                "<hr>"
            );
            orderTotalAmount +=
                data[i].is_foc == 1 || data[i].is_foc == "1" ?
                0 :
                data[i].item_price * data[i].quantity;
            // orderTotalAmount += data[i]['item_price'] * data[i]['quantity'];
        }
        $(document).on('click', '#qtyEdit', function() {
            const quantityInput = $(this).closest('.item_qty_input').find('.quantity-input');

            quantityInput.removeAttr('readonly').removeClass('muted').focus();
            $(this).attr('id', 'qtySave').text('Save');
        });
        $(document).on('click', '#qtySave', function() {
            const quantityInput = $(this).closest('.item_qty_input').find('.quantity-input');

            const updatedQuantity = quantityInput.val();
            const orderDetailId = $(this)
                .closest('.row')
                .find('.delete_item_modal_dialog')
                .data('order_detail_id');

            if (updatedQuantity != 0) {
                $.ajax({
                    type: "GET",
                    url: "dineIn/updateOrderItemQty",
                    data: {
                        orderDetailID: orderDetailId,
                        updatedQuantity: updatedQuantity,
                    },
                    success: function(data) {
                        orderDisplay(data);
                    },
                    error: function(error) {
                        console.error("Failed to update quantity:", error);
                        alert("Failed to update the quantity. Please try again.");
                    }
                });
                // Save changes
                quantityInput.attr('readonly', true).addClass('muted');
                $(this).attr('id', 'qtyEdit').text('Edit');
            } else {
                quantityInput.focus();
            }

        });

        $(".orderTotalAmount_div")
            .empty()
            .append(orderTotalAmount.toLocaleString() + " MMK");
        $(".gift-checkbox").on("click", function(event) {
            var value = $(this).val();
            var values = value.split("|");
            var orderDetailID = values[0];
            var orderIndex = values[1];

            if ($(this).is(":checked")) {
                $.ajax({
                    type: "GET",
                    url: "dineIn/updateOrderItem",
                    data: {
                        orderDetailID: orderDetailID,
                        foc: "checked",
                    },
                    success: function(data) {
                        orderDisplay(data);
                    },
                });
            } else {
                $.ajax({
                    type: "GET",
                    url: "dineIn/updateOrderItem",
                    data: {
                        orderDetailID: orderDetailID,
                        foc: "unchecked",
                    },
                    success: function(data) {
                        orderDisplay(data);
                    },
                });
            }
        });
    }

    const qty = document.getElementById('deleteQty');

    qty.addEventListener('keydown', (e) => {
        const blocked = ['e', 'E', '+', '-', '.'];
        // allow navigation keys: Backspace, Tab, Arrow keys, Delete, Home, End
        if (blocked.includes(e.key)) e.preventDefault();
    });

    qty.addEventListener('input', () => {
        const itemQuantity = parseInt(qty.max, 10);

        if (qty.value === '') return;
        const v = parseInt(qty.value, 10);
        if (isNaN(v)) { qty.value = ''; return; }
        if (v < 1) qty.value = 1;
        if (v > itemQuantity) qty.value = itemQuantity;
    });

    // $(document).on("click", ".delete_item_modal_dialog", function() {
    //     qty.value = '';

    //     var orderDetailID = $(this).data("order_detail_id");
    //     var orderItemIndex = $(this).data("order_item_index");
    //     var itemID = $(this).data("item_id");
    //     var itemName = $(this).data("item_name");
    //     var itemQuantity = $(this).data("quantity");
    //     // var calculatedQty = itemQuantity - qty.value;
    //     // var qty =$("#deleteQty").val();
    //     // console.log("Value" + qty);
    //     // console.log(calculatedQty)

    //     $(".modal-body #order_detail_id").val(orderDetailID);
    //     $(".modal-body #order_item_index").val(orderItemIndex);
    //     $(".modal-body #item_id").val(itemID);
    //     $(".modal-body #quantity").val(itemQuantity);

    //     $(".modal-body #deleteQty").prop('max', itemQuantity);
    //     $(".modal-body #order_quantity").text(itemQuantity);

    //     $(".modal-header #delete_modal_header").text("Delete '" + itemName + "'");

    //     $(".delete-item").one("click", function(event) {
    //         // console.log(qty.value);

    //         $.ajax({
    //             type: "GET",
    //             url: "dineIn/deleteOrderItem",
    //             data: {
    //                 orderDetailID: orderDetailID,
    //                 deleteQty: qty.value,
    //             },
    //             success: function(data) {

    //                 //new code for delete print
    //                 var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    //                 $.ajax({
    //                     type: "POST",
    //                     url: "order/sampleDeletePrint",
    //                     data: {
    //                         orderDetailDelete: JSON.stringify(data.orderDetailDelete),
    //                         orderID: data.orderID,
    //                         order: data.order,
    //                         _token: csrfToken,

    //                     },
    //                     success: function(response) {
    //                         var printFrame = document.getElementById("printFrame");

    //                         // Write the response (printable HTML) into the iframe
    //                         var printDocument = printFrame.contentWindow || printFrame.contentDocument;
    //                         if (printDocument.document) printDocument = printDocument.document;

    //                         printDocument.open();
    //                         printDocument.write(response); // Response should contain printable HTML
    //                         printDocument.close();

    //                         // Trigger print on the iframe
    //                         // printFrame.contentWindow.focus();
    //                         // printFrame.contentWindow.print();

    //                         // Show print dialog and then close the window after printin
    //                         if (data.orderDetails.length != 0) {
    //                             orderDisplay(data.orderDetails);
    //                             $(".modal-body #order_detail_id").val();
    //                             $(".modal-body #order_item_index").val();
    //                             $(".modal-body #item_id").val();
    //                             $(".modal-body #quantity").val();
    //                             $("#delete_item_modal").modal("hide");
    //                         } else {
    //                             orderDisplay(data.orderDetails);
    //                             setTimeout(function() {
    //                                 location.reload();
    //                             }, 500);
    //                         }

    //                     }
    //                 });
    //             }
    //         });
    //     });
    // });

    $('#deleteItemModal').on('click', function () {
        qty.value = '';
    });

    $(document).on("click", ".delete_item_modal_dialog", function() {
        qty.value = '';

        var orderDetailID = $(this).data("order_detail_id");
        var orderItemIndex = $(this).data("order_item_index");
        var itemID = $(this).data("item_id");
        var itemName = $(this).data("item_name");
        var itemQuantity = $(this).data("quantity");
        // var calculatedQty = itemQuantity - qty.value;
        // var qty =$("#deleteQty").val();
        // console.log("Value" + qty);
        // console.log(calculatedQty)

        $(".modal-body #order_detail_id").val(orderDetailID);
        $(".modal-body #order_item_index").val(orderItemIndex);
        $(".modal-body #item_id").val(itemID);
        $(".modal-body #quantity").val(itemQuantity);

        $(".modal-body #deleteQty").prop('max', itemQuantity);
        $(".modal-body #order_quantity").text(itemQuantity);

        $(".modal-header #delete_modal_header").text("Delete '" + itemName + "'");

    });

    $(document).on("click", ".delete-item", function(event) {

        var orderDetailID =  $(".modal-body #order_detail_id").val();
        var deleteQty = $(".modal-body #deleteQty").val();

        // console.log(orderDetailID);
        // console.log(quantity);

        $.ajax({
            type: "GET",
            url: "dineIn/deleteOrderItem",
            data: {
                orderDetailID: orderDetailID,
                deleteQty: deleteQty,
            },
            success: function(data) {

                //new code for delete print
                var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                $.ajax({
                    type: "POST",
                    url: "order/sampleDeletePrint",
                    data: {
                        orderDetailDelete: JSON.stringify(data.orderDetailDelete),
                        orderID: data.orderID,
                        order: data.order,
                        _token: csrfToken,

                    },
                    success: function(response) {
                        var printFrame = document.getElementById("printFrame");

                        // Write the response (printable HTML) into the iframe
                        var printDocument = printFrame.contentWindow || printFrame.contentDocument;
                        if (printDocument.document) printDocument = printDocument.document;

                        printDocument.open();
                        printDocument.write(response); // Response should contain printable HTML
                        printDocument.close();

                        // Trigger print on the iframe
                        // printFrame.contentWindow.focus();
                        // printFrame.contentWindow.print();

                        // Show print dialog and then close the window after printin
                        if (data.orderDetails.length != 0) {
                            orderDisplay(data.orderDetails);
                            $(".modal-body #order_detail_id").val();
                            $(".modal-body #order_item_index").val();
                            $(".modal-body #item_id").val();
                            $(".modal-body #quantity").val();
                            $("#delete_item_modal").modal("hide");
                        } else {
                            orderDisplay(data.orderDetails);
                            setTimeout(function() {
                                location.reload();
                            }, 500);
                        }

                    }
                });
            }
        });
    });

    $("#tableOrderValue").change(function() {
        var tableID = $("#tableID").val();
        var tableOrderNumber = $(this).val();
        $("#orderTableID").val(tableID);
        $("#orderTableOrderNumber").val(tableOrderNumber);
        $.ajax({
            type: "GET",
            url: "table/getOrder",
            data: {
                tableID: tableID,
                tableOrderNumber: tableOrderNumber,
            },
            success: function(data) {
                orderDisplay(data);
            }, // Move the closing parenthesis to here
        });
    });

    $(document).on("click", "#tableMergeLink", function() {
        var fromFloorID = $("#floorID_merge").val();
        var fromFloorName = $("#floorName_merge").val();
        var fromTableID = $("#tableID").val();
        var fromTableName = $("#tableName").val();
        var fromTableOrder = $("#tableOrderValue").val();

        $(".modal-body #from_floor_ID").val(fromFloorID);
        $(".modal-body #from_table_ID").val(fromTableID);

        $(".modal-body #from_floor").val(fromFloorName);
        $(".modal-body #from_table").val(fromTableName);
        $(".modal-body #from_table_order").val(fromTableOrder);

        $("#to_floor_id").change(function() {
            var floorID = $(this).val();
            $.ajax({
                type: "GET",
                url: "floor/getTableOnly",
                data: {
                    floorID: floorID,
                },
                success: function(data) {
                    $("#to_table_id").empty();
                    $.each(data, function(key, value) {
                        $("#to_table_id").append(
                            '<option value="' +
                            value.table_id +
                            '">' +
                            value.table_name +
                            "</option>"
                        );
                    });
                }, // Move the closing parenthesis to here
            });
        });

        $(function() {
            $.validator.addMethod(
                "notZeroFloor",
                function(value, element) {
                    return value != 0;
                },
                "To Floor ရွေးရန်လိုအပ်ပါသည်"
            );

            $.validator.addMethod(
                "notZeroTable",
                function(value, element) {
                    return value != 0;
                },
                "To Table ရွေးရန်လိုအပ်ပါသည်"
            );

            $.validator.addMethod(
                "notZeroTableOrder",
                function(value, element) {
                    return value != 0;
                },
                "To Table Order ရွေးရန်လိုအပ်ပါသည်"
            );

            $("#tableMergeForm").validate({
                rules: {
                    to_floor_id: {
                        required: true,
                        notZeroFloor: true,
                    },
                    to_table_id: {
                        required: true,
                        notZeroTable: true,
                    },
                    to_table_order_id: {
                        required: true,
                        notZeroTableOrder: true,
                    },
                },
                messages: {
                    to_floor_id: {
                        required: "To Floor ရွေးရန်လိုအပ်ပါသည်",
                    },
                    to_table_id: {
                        required: "To Table ရွေးရန်လိုအပ်ပါသည်",
                    },
                    to_table_order_id: {
                        required: "To Table Order ရွေးရန်လိုအပ်ပါသည်",
                    },
                },
            });
        });
        // $(function() {
        //     $("#tableMergeForm").validate({
        //         rules: {
        //             to_floor_id: {
        //                 required: true,
        //                 not_in: 0
        //             },
        //             to_table_id: {
        //                 required: true,
        //                 not_in: 0
        //             },
        //             to_table_order_id: {
        //                 required: true,
        //                 not_in: 0
        //             },

        //         },
        //         messages: {
        //             to_floor_id: {
        //                 required: "To Floor ဖြည့်ရန်လိုအပ်ပါသည်",
        //                 not_in: "To Floor ဖြည့်ရန်လိုအပ်ပါသည်"

        //             },
        //             to_table_id: {
        //                 required: "To Table ဖြည့်ရန်လိုအပ်ပါသည်",
        //                 not_in: "To Table ဖြည့်ရန်လိုအပ်ပါသည်"
        //             },
        //             to_table_order_id: {
        //                 required: "To Table Order ဖြည့်ရန်လိုအပ်ပါသည်",
        //                 not_in: "To Table Order ဖြည့်ရန်လိုအပ်ပါသည်"
        //             },
        //         }
        //     });

        // });
    });

    //  let supplier_info_label = document.querySelector('#supplier_info_label');
//  supplier_info_label.addEventListener("click", (e) => {
//      supplier_info_label.classList.toggle('show');
//      let supplier_info_container = document.querySelector('.supplier_info_container');
//      supplier_info_container.classList.toggle('show_container');
//  });

//  let supplier_list_label = document.querySelector('#supplier_list_label');
//  supplier_list_label.addEventListener("click", (e) => {
//      supplier_list_label.classList.toggle('show');
//      let supplier_list_container = document.querySelector('.supplier_list_container');
//      supplier_list_container.classList.toggle('show_container');
//  });
