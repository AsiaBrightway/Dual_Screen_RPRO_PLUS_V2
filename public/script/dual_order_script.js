$(document).ready(function () {
    var categoryDiv = document.querySelector('.category_div');
    var contentWrapper = categoryDiv.querySelector('.category_content'); // The moving track
    var leftArrow = document.querySelector('.left-arrow');
    var rightArrow = document.querySelector('.right-arrow');

    var isAnimating = false;
    var animationDuration = 300;

    if (contentWrapper.querySelectorAll('.subCategory-button').length <= 6) {
        $('.left-arrow, .right-arrow').hide();
    } else {
        $('.left-arrow, .right-arrow').show();
    }

    // Calculate width of one item + its margin
    function getScrollAmount() {
        var item = contentWrapper.querySelector('.subCategory-button');
        if (!item) return 0;
        var style = window.getComputedStyle(item);
        return item.offsetWidth + parseFloat(style.marginLeft) + parseFloat(style.marginRight);
    }

    function scrollRight() {
        if (isAnimating) return;

        var items = contentWrapper.querySelectorAll('.subCategory-button');
        if (items.length <= 6) return;

        var moveSize = getScrollAmount();
        if (moveSize === 0) return;

        isAnimating = true;

        // Animate the wrapper to the LEFT (visual movement)
        contentWrapper.style.transition = `transform ${animationDuration}ms ease-out`;
        contentWrapper.style.transform = `translateX(-${moveSize}px)`;

        // Wait for animation to finish, then reset
        setTimeout(function () {
            // Stop animation capabilities temporarily
            contentWrapper.style.transition = 'none';

            // Move the first DOM element to the end
            var firstItem = contentWrapper.querySelector('.subCategory-button');
            if (firstItem) contentWrapper.appendChild(firstItem);

            // Instantly reset the transform to 0
            contentWrapper.style.transform = 'translateX(0px)';

            isAnimating = false;
        }, animationDuration);
    }

    function scrollLeft() {
        if (isAnimating) return;

        // SAFETY CHECK: Don't scroll if only 1 item
        var items = contentWrapper.querySelectorAll('.subCategory-button');
        if (items.length <= 6) return;

        var moveSize = getScrollAmount();
        if (moveSize === 0) return;

        isAnimating = true;

        // Move last item to front INSTANTLY, but offset the wrapper so it looks like it hasn't moved yet.
        var items = contentWrapper.querySelectorAll('.subCategory-button');
        var lastItem = items[items.length - 1];

        if (lastItem) {
            contentWrapper.style.transition = 'none'; // No animation for setup
            contentWrapper.insertBefore(lastItem, contentWrapper.firstChild); // Move DOM
            contentWrapper.style.transform = `translateX(-${moveSize}px)`; // Offset position
        }

        // Force a browser reflow (update) so it registers the position change
        void contentWrapper.offsetWidth;

        // ANIMATE: Slide everything back to 0
        setTimeout(function () {
            contentWrapper.style.transition = `transform ${animationDuration}ms ease-out`;
            contentWrapper.style.transform = 'translateX(0px)';
        }, 10);

        // Cleanup lock
        setTimeout(function () {
            isAnimating = false;
        }, animationDuration + 10);
    }

    $(leftArrow).off('click').on('click', scrollLeft);
    $(rightArrow).off('click').on('click', scrollRight);

    //Click Main Category
    $('input[name="btnradio"]').on("change", function () {
        var selectedMainCategoryID = $('input[name="btnradio"]:checked').val();

        $.ajax({
            type: "GET",
            url: "mainCategory/getSubCategory",
            data: {
                selectedMainCategoryID: selectedMainCategoryID,
            },
            success: function (data) {
                $(".category_div").empty();
                $(".category_div").append('<div class="category_content" style="margin-bottom: 7px; min-height: 124px;"></div>'); // Adding category_content div

                if (data.length <= 6) {
                    $('.left-arrow, .right-arrow').hide();
                } else {
                    $('.left-arrow, .right-arrow').show();
                }

                $.each(data, function (key, value) {
                    $(".category_content").append(
                        '<button class="btn m-2 subCategory-button p-0 mb-3 shadow-sm" ' +
                        'style="width: 100px; height: 100px; border-radius: 20px; border: none;" ' +
                        'data-table-value="' + value.category_id + '">' +
                        '<div class="card" style="background: white; width:100px; border-radius:20px">' +
                        '<img src="' + (value.menu_category_image != null ? '/storage/Images/' + value.menu_category_image : '/img/category.png') + '" ' +
                        'class="card-img-top w-100" loading="lazy" alt="..." ' +
                        'style="height: 70px; border-top-left-radius: 20px; border-top-right-radius: 20px">' +
                        '<div class="card-body d-flex align-items-center justify-content-center" style="height: 40px">' +
                        '<p class="card-text text-break" style="font-size:12px; white-space: normal; line-height: 1;">' +
                        value.menu_category_name +
                        '</p>' +
                        '</div>' +
                        '</div>' +
                        '</button>'
                    );
                });

                if (selectedMainCategoryID == 0 || selectedMainCategoryID == "0") {
                    $.ajax({
                        type: "GET",
                        url: "subCategory/getItem",
                        data: {
                            selectedSubCategoryID: 0,
                        },
                        success: function (data) {
                            $(".item_div").empty();
                            $.each(data, function (key, value) {
                                if (value.store_qty <= 0 && value.item_type_id == 1) {
                                    var disabled = "";
                                    var textColor = "orange";
                                    value.store_qty = 0;
                                } else if (value.store_qty <= 0) {
                                    var disabled = "";
                                    var textColor = "red";
                                } else {
                                    var disabled = "";
                                    var textColor = "green"
                                }

                                $(".item_div").append(
                                    ' <button class="btn m-2 item-button p-0 shadow-sm" style="width: 178px; height: 200px; border-radius: 20px; border: none;" data-item_id="' +
                                    value.item_id +
                                    '" data-item_image="' +
                                    value.item_image +
                                    '" data-item_name="' +
                                    value.item_name +
                                    '" data-item_price="' +
                                    value.item_price +
                                    '" ' +
                                    disabled +
                                    ">" +
                                    '<div class="card h-100 w-100" style="background: white">' +
                                    '<img src="' +
                                    (value.item_image != null ? '/storage/Images/' + value.item_image : '/404_image.png') +
                                    '" class="card-img-top w-100" loading="lazy" alt="..." style="height:110px; object-fit: cover">' +
                                    // (value.item_image != null ? 'contain">' : 'cover">') +
                                    '<div class="card-body" style="height:0px">' +
                                    '<p class="card-title" style="text-align: start; margin-top:-10px;font-size:13px; font-weight: 600;">' +
                                    truncateWords(value.item_name, 2, '...') +
                                    "</p>" +
                                    '<p class="card-text" style="text-align: start; margin-top: -5px; font-size: 15px; font-weight: 600">' +
                                    value.item_price.toLocaleString() +
                                    " MMK <br><span style='color:" +
                                    textColor +
                                    ";font-size:12px'>Store Qty: " +
                                    value.store_qty +
                                    "</span></p>" +
                                    '</div>' +
                                    '</div>' +
                                    '</button>'
                                );
                            });


                        },
                    });
                } else {
                    $.ajax({
                        type: "GET",
                        url: "mainCategory/getItem",
                        data: {
                            selectedMainCategoryID: selectedMainCategoryID,
                        },
                        success: function (data) {
                            $(".item_div").empty();
                            $.each(data, function (key, value) {
                                if (value.store_qty <= 0 && value.item_type_id == 1) {
                                    var disabled = "";
                                    var textColor = "orange";
                                    value.store_qty = 0;
                                } else if (value.store_qty <= 0) {
                                    var disabled = "";
                                    var textColor = "red";
                                } else {
                                    var disabled = "";
                                    var textColor = "green"
                                }


                                $(".item_div").append(
                                    ' <button class="btn m-2 item-button p-0 shadow-sm" style="width: 178px; height: 200px; border-radius: 20px; border: none;" data-item_id="' +
                                    value.item_id +
                                    '" data-item_image="' +
                                    value.item_image +
                                    '" data-item_name="' +
                                    value.item_name +
                                    '" data-item_price="' +
                                    value.item_price +
                                    '" ' +
                                    disabled +
                                    ">" +
                                    '<div class="card h-100 w-100" style="background: white">' +
                                    '<img src="' +
                                    (value.item_image != null ? '/storage/Images/' + value.item_image : '/404_image.png') +
                                    '" class="card-img-top w-100" loading="lazy" alt="..." style="height:110px; object-fit: cover">' +
                                    // (value.item_image != null ? 'contain">' : 'cover">') +
                                    '<div class="card-body" style="height:0px">' +
                                    '<p class="card-title" style="text-align: start; margin-top:-10px;font-size:13px; font-weight: 600;">' +
                                    truncateWords(value.item_name, 2, '...') +
                                    "</p>" +
                                    '<p class="card-text" style="text-align: start; margin-top: -5px; font-size: 15px; font-weight: 600">' +
                                    value.item_price.toLocaleString() +
                                    " MMK <br><span style='color:" +
                                    textColor +
                                    ";font-size:12px'>Store Qty: " +
                                    value.store_qty +
                                    "</span></p>" +
                                    '</div>' +
                                    '</div>' +
                                    '</button>'
                                );
                            });


                        },
                    });
                }

                var categoryDiv = document.querySelector('.category_div');
                var contentWrapper = categoryDiv.querySelector('.category_content');
                var leftArrow = document.querySelector('.left-arrow');
                var rightArrow = document.querySelector('.right-arrow');

                var isAnimating = false;
                var animationDuration = 300;

                function getScrollAmount() {
                    var item = contentWrapper.querySelector('.subCategory-button');
                    if (!item) return 0;
                    var style = window.getComputedStyle(item);
                    return item.offsetWidth + parseFloat(style.marginLeft) + parseFloat(style.marginRight);
                }

                function scrollRight() {
                    if (isAnimating) return;

                    // SAFETY CHECK: Don't scroll
                    var items = contentWrapper.querySelectorAll('.subCategory-button');
                    if (items.length <= 6) return;

                    var moveSize = getScrollAmount();
                    if (moveSize === 0) return;

                    isAnimating = true;

                    contentWrapper.style.transition = `transform ${animationDuration}ms ease-out`;
                    contentWrapper.style.transform = `translateX(-${moveSize}px)`;

                    setTimeout(function () {
                        contentWrapper.style.transition = 'none';
                        var firstItem = contentWrapper.querySelector('.subCategory-button');
                        if (firstItem) contentWrapper.appendChild(firstItem);
                        contentWrapper.style.transform = 'translateX(0px)';
                        isAnimating = false;
                    }, animationDuration);
                }

                function scrollLeft() {
                    if (isAnimating) return;

                    var items = contentWrapper.querySelectorAll('.subCategory-button');
                    if (items.length <= 6) return;

                    var moveSize = getScrollAmount();
                    if (moveSize === 0) return;

                    isAnimating = true;

                    var lastItem = items[items.length - 1];

                    if (lastItem) {
                        contentWrapper.style.transition = 'none';
                        contentWrapper.insertBefore(lastItem, contentWrapper.firstChild);
                        contentWrapper.style.transform = `translateX(-${moveSize}px)`;
                    }

                    void contentWrapper.offsetWidth;

                    setTimeout(function () {
                        contentWrapper.style.transition = `transform ${animationDuration}ms ease-out`;
                        contentWrapper.style.transform = 'translateX(0px)';
                    }, 10);

                    setTimeout(function () {
                        isAnimating = false;
                    }, animationDuration + 10);
                }

                // Attach Listeners safely
                $(leftArrow).off('click').on('click', scrollLeft);
                $(rightArrow).off('click').on('click', scrollRight);

            },
        });
    });

    $(document).on("click", ".subCategory-button", function () {
        var subCategory_id = $(this).data("table-value");

        $(".subCategory-button .card").removeClass("active-card");

        $(this).find(".card").addClass("active-card");

        $.ajax({
            type: "GET",
            url: "subCategory/getItem",
            data: {
                selectedSubCategoryID: subCategory_id,
            },
            success: function (data) {
                $(".item_div").empty();
                $.each(data, function (key, value) {
                    console.log(value);
                    if (value.store_qty <= 0 && value.item_type_id == 1) {
                        var disabled = "";
                        var textColor = "orange";
                        value.store_qty = 0;
                    } else if (value.store_qty <= 0) {
                        var disabled = "";
                        var textColor = "red";
                    } else {
                        var disabled = "";
                        var textColor = "green"
                    }

                    $(".item_div").append(
                        ' <button class="btn m-2 item-button p-0 shadow-sm" style="width: 178px; height: 200px; border-radius: 20px; border: none;" data-item_id="' +
                        value.item_id +
                        '" data-item_image="' +
                        value.item_image +
                        '" data-item_name="' +
                        value.item_name +
                        '" data-item_price="' +
                        value.item_price +
                        '" ' +
                        disabled +
                        ">" +
                        '<div class="card h-100 w-100" style="background: white">' +
                        '<img src="' +
                        (value.item_image != null ? '/storage/Images/' + value.item_image : '/404_image.png') +
                        '" class="card-img-top w-100" loading="lazy" alt="..." style="height:110px; object-fit: cover">' +
                        // (value.item_image != null ? 'contain">' : 'cover">') +
                        '<div class="card-body" style="height:0px">' +
                        '<p class="card-title" style="text-align: start; margin-top:-10px;font-size:13px; font-weight: 600;">' +
                        truncateWords(value.item_name, 2, '...') +
                        "</p>" +
                        '<p class="card-text" style="text-align: start; margin-top: -5px; font-size: 15px; font-weight: 600">' +
                        value.item_price.toLocaleString() +
                        " MMK <br><span style='color:" +
                        textColor +
                        ";font-size:12px'>Store Qty: " +
                        value.store_qty +
                        '</span></p>' +
                        "</div>" +
                        "</div>" +
                        "</button>"
                    );
                });
            }, // Move the closing parenthesis to here
        });
    });

    var orderItems = [];
    var tableID = $("#table_id").val();
    var tableOrderNumber = $("#table_order_number").val();
    var userID = $("#user_id").val();

    if (orderDetailsJson.length != 0) {
        var orderDetailID;
        var itemID;
        var itemImage;
        var itemName;
        var itemPrice;
        var itemQty;
        var remark;
        var is_foc;
        orderDetailsJson.forEach((orderDetail) => {
            orderDetailID = orderDetail.order_detail_id;
            itemID = orderDetail.item_id;
            itemImage = orderDetail.item_image;
            itemName = orderDetail.item_name;
            itemPrice = orderDetail.item_price;
            itemQty = orderDetail.quantity;
            remark = orderDetail.remark;
            is_foc = orderDetail.is_foc;
            addOrderItemObj(
                orderDetailID,
                itemID,
                itemImage,
                itemName,
                itemPrice,
                itemQty,
                tableID,
                tableOrderNumber,
                1,
                remark,
                is_foc
            );
        });
    }

    $(document).on("click", ".item-button", function () {
        // console.log(orderDetailsJson);

        var itemID = $(this).data("item_id");
        var itemImage = $(this).data("item_image");
        if (itemImage == "" || itemImage == null) {
            itemImage = null
        }
        var itemName = $(this).data("item_name");
        var itemPrice = $(this).data("item_price");

        var found = false;

        for (var i = 0; i < orderItems.length; i++) {
            if (orderItems[i].orderItemID == itemID) {
                if (orderItems[i].is_ordered == 0) {
                    found = true;
                    orderItems[i].orderItemQuantity++;
                }
            }
        }
        if (!found) {
            addOrderItemObj(
                0,
                itemID,
                itemImage,
                itemName,
                itemPrice,
                1,
                tableID,
                tableOrderNumber,
                0,
                "",
                0
            );
        }
        updateOrderDisplay();
    });

    function addOrderItemObj(
        orderDetailID,
        itemID,
        itemImage,
        itemName,
        itemPrice,
        itemQty,
        tableID,
        tableOrderNumber,
        is_ordered,
        remark,
        is_foc
    ) {
        var orderItemObj = {
            orderDetailID: orderDetailID,
            orderItemID: itemID,
            orderItemImage: itemImage,
            orderItemName: itemName,
            orderItemPrice: itemPrice,
            orderItemQuantity: itemQty,
            orderTableID: tableID,
            orderTableOrderNumber: tableOrderNumber,
            is_ordered: is_ordered,
            orderItemRemark: remark,
            is_foc: is_foc,
        };
        orderItems.push(orderItemObj);
    }
    $(document).on("submit", "#addOrderItemRemarkForm", function (event) {
        event.preventDefault(); // Prevent the default form submission
        var order_item_id = $("#order_item_id").val();

        var addRemark = $("#add_remark").val();
        var found = false;

        for (var i = 0; i < orderItems.length; i++) {
            if (orderItems[i].orderItemID == order_item_id) {
                if (orderItems[i].is_ordered == false) {
                    orderItems[i].orderItemRemark = addRemark;
                }
            }
        }

        // console.log(orderItems);

        // Update the order display after adding the remark
        updateOrderDisplay();

        // Hide the modal
        $("#add_order_item_remark_modal").modal("hide");
    });

    var orderTotalAmount = 0;

    function updateOrderDisplay() {
        let hasUnorderedItems = false;

        orderTotalAmount = 0;
        $(".order_div").empty();
        var userRoleID = $("#user_role_id").val();
        // console.log(orderItems);
        for (var i = 0; i < orderItems.length; i++) {
            let isOrdered = orderItems[i].is_ordered == 1 || orderItems[i].is_ordered == "1" ? 1 : 0;

            if (!isOrdered) {
                hasUnorderedItems = true;
            }

            let readonly = isOrdered ? "readonly" : "";
            let textColor = isOrdered ? "orange" : "black";
            let iconColor = isOrdered ? "orange" : "#512DA8";
            let muted = isOrdered ? "muted" : "";
            let remarkModalTarget = isOrdered ? "#add_order_item_preview_remark_modal" : "#add_order_item_remark_modal";
            let remarkModalClass = isOrdered ? "add_order_item_remark_preview_modal_dialog" : "add_order_item_remark_modal_dialog";

            let imageUrl = orderItems[i].orderItemImage ?
                "/storage/Images/" + orderItems[i].orderItemImage :
                "/404_image.png";

            let deleteButtonHtml = '';
            if (userRoleID != 4) {
                deleteButtonHtml =
                    '<a href="#" class="delete_item_modal_dialog" data-toggle="tooltip" data-placement="top" title="Delete Item" data-bs-toggle="modal" data-bs-target="#delete_item_modal" data-order_item_index="' +
                    i +
                    '" data-order_detail_id="' +
                    orderItems[i].orderDetailID +
                    '" data-item_name ="' +
                    orderItems[i].orderItemName +
                    '" data-item_id="' +
                    orderItems[i].orderItemID +
                    '"><i class="fa-solid fa-circle-xmark" style="color:' +
                    iconColor +
                    '"></i></a>';
            } else {
                if (isOrdered != 1) {
                    deleteButtonHtml =
                        '<a href="#" class="delete_item_modal_dialog" data-toggle="tooltip" data-placement="top" title="Delete Item" data-bs-toggle="modal" data-bs-target="#delete_item_modal" data-order_item_index="' +
                        i +
                        '" data-order_detail_id="' +
                        orderItems[i].orderDetailID +
                        '" data-item_name ="' +
                        orderItems[i].orderItemName +
                        '" data-item_id="' +
                        orderItems[i].orderItemID +
                        '"><i class="fa-solid fa-circle-xmark" style="color:' +
                        iconColor +
                        '"></i></a>';
                }
            }

            $(".order_div").append(
                '<div class="row" style="align-items: center">' +
                '<div class="col-2 item_qty_input" style="align-self: stretch; display: flex; flex-direction: column; justify-content: space-around;">' +
                '<input class="form-control quantity-input ' +
                muted +
                '" type="text" value="' +
                orderItems[i].orderItemQuantity +
                '" min="1" data-order_item_index="' +
                i +
                '" data-item-id="' +
                orderItems[i].orderItemID +
                '"' +
                readonly +
                ">" +
                '<div>' +
                '<a class="remark_lbl ' +
                remarkModalClass +
                '" style="text-decoration: none; cursor: pointer;" data-order_item_id="' +
                orderItems[i].orderItemID +
                '" data-order_item_name="' +
                orderItems[i].orderItemName +
                '" data-bs-toggle="modal" data-bs-target="' +
                remarkModalTarget +
                '"><span class="remark_lbl" style="font-size: 13px; color:red">+remark</span></a>' +
                "</div>" +
                "</div>" +
                '<div class="col-2">' +
                '<img class="item_img" src="' +
                imageUrl +
                '" alt="" loading="lazy" style="width: 65px; height:65px; border-radius: 10px">' +
                "</div>" +
                '<div class="col-3 dine_in_item_description_div" style="color:#512DA8">' +
                '<div class="row">' +
                '<label class="item_name_lbl" style="font-weight:500;">' +
                orderItems[i].orderItemName +
                "</label>" +
                "</div>" +
                '<div class="row">' +
                '<label class="original-price text-muted small">' +
                Number(orderItems[i].orderItemPrice).toLocaleString() +
                "</label>" +
                "</div>" +
                "</div>" +
                '<div class="col-1 gift_check_div">' +
                '<input id="giftCheckbox' +
                i +
                '" class="form-check-input visually-hidden gift-checkbox" type="checkbox" value="' +
                orderItems[i].orderItemID +
                "|" +
                i +
                '" ' +
                (orderItems[i].is_foc == 1 || orderItems[i].is_foc == "1" ? "checked" : "") +
                ">" +
                '<label for="giftCheckbox' +
                i +
                '"><i class="fas fa-gift gift-icon" style="cursor: pointer;"></i></label>' +
                "</div>" +
                '<div class="col-3 price_div">' +
                '<label class="item-price" style="color:' +
                textColor +
                '">' +
                (orderItems[i].is_foc == 1 || orderItems[i].is_foc == "1" ?
                    "0" :
                    (orderItems[i].orderItemPrice * orderItems[i].orderItemQuantity).toLocaleString()) +
                " MMK</label>" +
                "</div>" +
                '<div class="col-1">' +
                deleteButtonHtml +
                "</div>" +
                "</div>" +
                "<hr>"
            );

            orderTotalAmount +=
                orderItems[i].is_foc == 1 || orderItems[i].is_foc == "1" ?
                    0 :
                    orderItems[i].orderItemPrice * orderItems[i].orderItemQuantity;
        }

        $(".orderTotalAmount_div")
            .empty()
            .append(orderTotalAmount.toLocaleString() + " MMK");

        $("#placeOrderButton").prop("disabled", !hasUnorderedItems);
    }
    // Add event listener for quantity change
    $(document).on("input", ".quantity-input", function () {
        // $(".quantity-input").on("input", function() {
        var inputVal = $(this).val();
        var newQuantity = inputVal === "" ? "" : parseInt(inputVal);
        var itemID = $(this).data("item-id");
        var orderItemIndex = $(this).data("order_item_index");

        var cursorStart = this.selectionStart;
        var cursorEnd = this.selectionEnd;

        // Update the corresponding order item's quantity
        for (var i = 0; i < orderItems.length; i++) {
            if (orderItemIndex == i) {
                orderItems[i].orderItemQuantity = isNaN(newQuantity) ? "" : newQuantity;
            }
        }
        console.log(orderItems);
        // Update the order display after changing the quantity
        updateOrderDisplay();

        // Restore focus and cursor position
        var $newInput = $('.quantity-input[data-order_item_index="' + orderItemIndex + '"]');
        if ($newInput.length > 0) {
            $newInput.focus();
            try {
                var currentValLen = $newInput.val().toString().length;
                var safeStart = Math.min(cursorStart, currentValLen);
                var safeEnd = Math.min(cursorEnd, currentValLen);
                $newInput[0].setSelectionRange(safeStart, safeEnd);
            } catch (e) { }
        }
    });

    $(".gift-checkbox").change(function (event) {
        event.preventDefault();
        var value = $(this).val();
        var values = value.split("|");
        var orderDetailID = values[0];
        var orderIndex = values[1];

        // console.log(orderItems.length);

        if ($(this).is(":checked")) {
            for (var i = 0; i < orderItems.length; i++) {
                if (i == orderIndex) {
                    orderItems[i].is_foc = 1;
                }
            }
        } else {
            for (var i = 0; i < orderItems.length; i++) {
                if (i == orderIndex) {
                    orderItems[i].is_foc = 0;
                }
            }
        }
        updateOrderDisplay();
    });

    $(".delete-item").on("click", function (event) {
        event.preventDefault();
        var orderDetailID = $("#order_detail_id").val();
        var orderItemIndex = $("#order_item_index").val();

        if (orderDetailID == 0) {
            orderItems.splice(orderItemIndex, 1);
            updateOrderDisplay();
            $("#delete_item_modal").modal('hide');
        } else {
            console.log("order item delete", orderDetailID)
            var self = $(this); // Store reference to 'this'
            $.ajax({
                type: "GET",
                url: "order/deleteOrderItem",
                data: {
                    orderDetailID: orderDetailID
                },
                success: function (data) {
                    orderItems.splice(orderItemIndex, 1);
                    updateOrderDisplay();
                    $("#delete_item_modal").modal('hide');
                    // $("#placeOrderButton").prop("disabled", true);
                },
            });
        }
    });

    $(document).on(
        "click",
        ".add_order_item_remark_modal_dialog",
        function () {
            var order_item_id = $(this).data("order_item_id");

            var order_item_name = $(this).data("order_item_name");
            var tableID = $("#table_id").val();
            var tableOrderNumber = $("#table_order_number").val();

            $(".modal-body #order_item_id").val(order_item_id);
            $(".modal-body #tableID").val(tableID);
            $(".modal-body #tableOrderValue").val(tableOrderNumber);
            $(".modal-body #item_name").val(order_item_name);

            orderItems.forEach((orderItem) => {
                if (orderItem.orderItemID === order_item_id) {
                    $(".modal-body #add_remark").val(
                        orderItem.orderItemRemark
                    );
                }
            });
        }
    );

    $(function () {
        $("#addOrderItemRemarkForm").validate({
            rules: {
                add_remark: {
                    required: true,
                },
            },
            messages: {
                add_remark: {
                    required: "Remark ဖြည့်ရန်လိုအပ်ပါသည်",
                },
            },
        });
    });
    //remove } updateOrderDisplay
    $(".gift-checkbox").change(function (event) {
        // console.log("hello");
        var value = $(this).val();
        var values = value.split("|");
        var orderDetailID = values[0];
        var orderIndex = values[1];

        $("#orderFormReload #tableID").val(tableID);
        if ($(this).is(":checked")) {
            // Checkbox is checked, perform actions here

            // You can add your code here to handle checkbox checked event

            $.ajax({
                type: "GET",
                url: "order/updateOrderItem",
                data: {
                    orderDetailID: orderDetailID,
                    foc: "checked",
                },
                success: function (data) {
                    var form = document.getElementById("orderFormReload");
                    form.submit();
                },
            });
        } else {
            $.ajax({
                type: "GET",
                url: "order/updateOrderItem",
                data: {
                    orderDetailID: orderDetailID,
                    foc: "unchecked",
                },
                success: function (data) {
                    var form = document.getElementById("orderFormReload");
                    form.submit();
                },
            });
        }
    });

    $(document).on("click", ".delete_item_modal_dialog", function () {
        var orderDetailID = $(this).data("order_detail_id");
        var orderItemIndex = $(this).data("order_item_index");
        var itemID = $(this).data("item_id");
        var itemName = $(this).data("item_name");

        $(".modal-body #order_detail_id").val(orderDetailID);
        $(".modal-body #order_item_index").val(orderItemIndex);
        $(".modal-body #item_id").val(itemID);

        $(".modal-header #delete_modal_header").text(
            "Delete '" + itemName + "'"
        );

        $("#delete_item_modal").modal('show');
    });
    // Add event listener for delete button
    // $(".delete-item").on("click", function(event) {
    //     event.preventDefault();
    //     var orderDetailID = $("#order_detail_id").val();

    //     var orderItemIndex = $("#order_item_index").val();
    //     $("#orderFormReload #tableID").val(tableID);
    //     var tableOrderNumber = $("#orderFormReload #tableOrderValue").val();


    //         $.ajax({
    //             type: "GET",
    //             url: "order/deleteOrderItem",
    //             data: {
    //                 orderDetailID: orderDetailID,
    //             },
    //             success: function(orderID) {
    //                 var form = document.getElementById("orderFormReload");

    //                 // Submit the form
    //                 form.submit();

    //             },
    //         });

    // });

    // QZ Tray Security Setup (Silent Printing)
    qz.security.setCertificatePromise(function (resolve, reject) {
        $.ajax({
            url: "qz/certificate",
            type: "GET",
            success: function (data) {
                resolve(data);
            },
            error: function (err) {
                console.error("Failed to fetch QZ certificate:", err);
                reject(err);
            }
        });
    });

    qz.security.setSignatureAlgorithm("SHA512"); // Required for QZ Tray 2.1+

    qz.security.setSignaturePromise(function (toSign) {
        return function (resolve, reject) {
            $.ajax({
                url: "qz/sign",
                type: "POST",
                data: {
                    request: toSign,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    resolve(data);
                },
                error: function (err) {
                    console.error("Failed to sign QZ request:", err);
                    reject(err);
                }
            });
        };
    });

    // QZ Tray Dual Printer Print Function
    function printToQzTray(htmlContent, printer1, printer2) {
        return qz.websocket.connect()
            .then(function () {
                console.log("QZ Tray connected.");

                // Configure both printers
                var configPrinter1 = qz.configs.create(printer1, {
                    scaleContent: true,
                    margins: { top: 0, right: 0, bottom: 0, left: 0 },
                    units: "mm"
                });
                var configPrinter2 = qz.configs.create(printer2, {
                    scaleContent: true,
                    margins: { top: 0, right: 0, bottom: 0, left: 0 },
                    units: "mm"
                });

                // ESC/POS full paper cut command (base64 of: 0x1D 0x56 0x41 0x03)
                var cutCommand = [{ type: "raw", format: "base64", data: "HVZBAw==" }];

                // Split HTML on page-break divs into separate sections
                var sections = htmlContent.split(/<div[^>]*page-break-after\s*:\s*always[^>]*>.*?<\/div>/i);
                sections = sections.map(function (s) { return s.trim(); }).filter(function (s) { return s.length > 0; });

                // Print each section then send a cut command
                function printSectionsToConfig(config, printerName) {
                    var chain = Promise.resolve();
                    sections.forEach(function (section, idx) {
                        chain = chain
                            .then(function () {
                                return qz.print(config, [{ type: "html", format: "plain", data: section }]);
                            })
                            .then(function () {
                                console.log("Section " + (idx + 1) + " printed to " + printerName);
                                return qz.print(config, cutCommand);
                            });
                    });
                    return chain;
                }

                return printSectionsToConfig(configPrinter1, printer1)
                    .then(function () {
                        console.log("All sections printed to " + printer1);
                        return printSectionsToConfig(configPrinter2, printer2);
                    })
                    .then(function () {
                        console.log("All sections printed to " + printer2);
                    });
            })
            .catch(function (err) {
                console.error("QZ Tray print error:", err);
                alert("Printing failed: " + (err.message || err));
            })
            .finally(function () {
                // Always disconnect when done
                if (qz.websocket.isActive()) {
                    qz.websocket.disconnect()
                        .then(function () {
                            console.log("QZ Tray disconnected.");
                        })
                        .catch(function (err) {
                            console.error("QZ disconnect error:", err);
                        });
                }
            });
    }

    $(document).on("click", "#placeOrderButton", function () {

        if (
            orderTotalAmount != undefined ||
            orderTotalAmount != 0 ||
            orderTotalAmount != "0"
        ) {
            $("#orderFormReload #tableID").val(tableID);
            var tableOrderNumber = $("#orderFormReload #tableOrderValue").val();

            const unOrderItems = orderItems.filter(item => item.is_ordered === 0);

            // Validate that all quantities are valid numbers >= 1
            const hasInvalidQuantity = unOrderItems.some(item => item.orderItemQuantity === "" || isNaN(item.orderItemQuantity) || item.orderItemQuantity < 1);
            if (hasInvalidQuantity) {
                alert("Please enter a valid quantity (1 or more) for all items.");
                return;
            }

            $("#addOrderItemsForm #unOrderItems").val(JSON.stringify(unOrderItems));
            $("#addOrderItemsForm #userID").val(userID);
            $("#addOrderItemsForm #tableID").val(tableID);
            $("#addOrderItemsForm #tableOrderNumber").val(tableOrderNumber);

            var addOrderFormData = $("#addOrderItemsForm").serialize();
            console.log(addOrderFormData);

            // Disable button to prevent double-click
            $("#placeOrderButton").prop("disabled", true).text("Printing...");

            $.ajax({
                type: "POST",
                url: "order/addOrderItem",
                data: addOrderFormData,
                success: function (orderID) {

                    let filteredOrderItems = orderItems.filter(function (item) {
                        return item.is_ordered == "0";
                    });

                    $("#filteredOrderForm #filteredOrder").val(JSON.stringify(filteredOrderItems));
                    $("#filteredOrderForm #orderID").val(orderID);
                    var formData = $("#filteredOrderForm").serialize();

                    $.ajax({
                        type: "POST",
                        url: "order/samplePrint",
                        data: formData,
                        success: function (response) {
                            // Use QZ Tray to print to both printers
                            printToQzTray(response, printer1, printer2)
                                .then(function () {
                                    // Reload the order page after printing
                                    var form = document.getElementById("orderFormReload");
                                    form.submit();
                                })
                                .catch(function () {
                                    // Still reload even if printing fails
                                    var form = document.getElementById("orderFormReload");
                                    form.submit();
                                });
                        },
                        error: function (xhr, status, error) {
                            console.log("AJAX error:", error);
                            alert("An error occurred while printing the order.");
                            $("#placeOrderButton").prop("disabled", false).text("Place Order");
                        }
                    });

                },
                error: function (xhr, status, error) {
                    console.error("Checkout error:", error);
                    alert("Order failed. Please try again.");
                    $("#placeOrderButton").prop("disabled", false).text("Place Order");
                }
            });
        }
    });

    $("#itemSearch").on("input", function () {
        var searchKey = $("#itemSearch").val();

        $.ajax({
            type: "GET",
            url: "searchKey/getItem",
            data: {
                searchKey: searchKey,
            },
            success: function (data) {
                $(".item_div").empty();
                $.each(data, function (key, value) {
                    if (value.store_qty <= 0 && value.item_type_id == 1) {
                        var disabled = "";
                        var textColor = "orange";
                        value.store_qty = 0;
                    } else if (value.store_qty <= 0) {
                        var disabled = "";
                        var textColor = "red";
                    } else {
                        var disabled = "";
                        var textColor = "green";
                    };

                    $(".item_div").append(
                        '<button class="btn m-2 item-button p-0" style="width: 180px; height: 200px; border-radius: 20px; border: none;" data-item_id="' +
                        value.item_id +
                        '" data-item_image="' +
                        value.item_image +
                        '" data-item_name="' +
                        value.item_name +
                        '" data-item_price="' +
                        value.item_price +
                        '" ' +
                        disabled +
                        ">" +
                        '<div class="card h-100 w-100" style="background: white">' +
                        '<img src="' + (value.item_image != null ? '/storage/Images/' + value.item_image : '/404_image.png') +
                        '" class="card-img-top w-100" loading="lazy" alt="..." style="height:110px">' +
                        '<div class="card-body" style="height:0px">' +
                        '<p class="card-title" style="text-align: start; margin-top:-10px;font-size:13px; font-weight: 600;">' +
                        truncateWords(value.item_name, 2, '...') +
                        "</p>" +
                        '<p class="card-text" style="text-align: start; margin-top: -5px; font-size: 15px; font-weight: 600">' +
                        value.item_price.toLocaleString() +
                        " MMK <br><span style='color:" +
                        textColor +
                        ";font-size:12px'>Store Qty: " +
                        value.store_qty +
                        "</span></p>" +
                        "</div>" +
                        "</div>" +
                        "</button>"
                    );
                });
            }, // Move the closing parenthesis to here
        });
    });

    function truncateWords(str, limit, ellipsis = '...') {
        let words = str.split(/\s+/); // Split the string by whitespace
        if (words.length > limit) {
            return words.slice(0, limit).join(' ') + ellipsis;
        }
        return str;
    }
});
