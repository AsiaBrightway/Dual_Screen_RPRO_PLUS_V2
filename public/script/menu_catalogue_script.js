$(document).ready(function() {

    const slider = $('.category_div');
    let isDown = false;
    let startX;
    let scrollLeft;
    let hasMoved = false;

    slider.on('mousedown', function(e) {
        isDown = true;
        hasMoved = false;
        startX = e.pageX - slider.offset().left;
        scrollLeft = slider.scrollLeft();
        // slider.css('cursor', 'grabbing');
    });

    slider.on('mousemove', function(e) {
        if (!isDown) return;
        e.preventDefault();
        hasMoved = true;
        const x = e.pageX - slider.offset().left;
        const walk = (x - startX) * 2;
        slider.scrollLeft(scrollLeft - walk);
    });

    slider.on('mouseup', function() {
        isDown = false;
        // slider.css('cursor', 'grab');
    });

    slider.on('mouseleave', function() {
        isDown = false;
        // slider.css('cursor', 'grab');
    });

    // Prevent button clicks if dragged
    $('.subCategory-button').on('click', function(e) {
        if (hasMoved) {
            e.preventDefault();
            e.stopPropagation();
            hasMoved = false;
            return false;
        }
    });

    //Click Main Category
    $(document).on("click", ".button-link", function() {
        // clearInterval(scrollInterval);

        $(".subCategory-button .card").removeClass("active-card");

        $(this).find(".card").addClass("active-card");

        $.ajax({
            type: "GET",
            url: "subCategory/getItem",
            data: {
                selectedSubCategoryID: 0,
            },
            success: function(data) {
                $(".item_row").empty();
                $.each(data, function(key, value) {
                    var disabled = value.store_qty <= 0 ? "" : "";
                    var textColor = value.store_qty <= 0 ? "red" : "green";

                    var imageSrc = (value.item_image == null || value.item_image === "") 
                        ? "/404_image.png" 
                        : "/storage/Images/" + value.item_image;

                    $(".item_row").append(
                        '<div class="col-6 col-md-3 col-lg-2">' +
                        '<button class="btn item-button p-0 w-100" style="height: 200px; border: none;" data-item_id="' +
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
                        '<img src="' + imageSrc + '" class="card-img-top w-100" alt="..." style="height:110px; object-fit: cover;">' +
                        '<div class="card-body" style="height:0px">' +
                        '<p class="card-title text-muted" style="text-align: start; margin-top:-10px">' +
                        truncateWords(value.item_name, 3, '...') +
                        "</p>" +
                        '<p class="card-text" style="text-align: start; margin-top: -5px; font-size: 15px; font-weight: 600">' +
                        value.item_price.toLocaleString() +
                        " MMK <br></p>" +
                        "</div>" +
                        "</div>" +
                        "</button>" +
                        "</div>"
                    );
                });
            },
        });

    });

    $(document).on("click", ".subCategory-button", function() {
        var subCategory_id = $(this).data("table-value");

        $(".subCategory-button .card").removeClass("active-card");

        $(this).find(".card").addClass("active-card");

        $.ajax({
            type: "GET",
            url: "subCategory/getItem",
            data: {
                selectedSubCategoryID: subCategory_id,
            },
            success: function(data) {
                $(".item_row").empty();
                $.each(data, function(key, value) {
                    if (value.store_qty <= 0) {
                        var disabled = "";
                        var textColor = "red";
                    } else {
                        var disabled = "";
                        var textColor = "green";
                    }

                    var imageSrc = (value.item_image == null || value.item_image === "") 
                        ? "/404_image.png" 
                        : "/storage/Images/" + value.item_image;
                    
                    $(".item_row").append(
                        '<div class="col-6 col-md-3 col-lg-2">' +
                        '<button class="btn item-button p-0 w-100" style="height: 200px; border: none;" data-item_id="' +
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
                        '<img src="' + imageSrc + '" class="card-img-top w-100" alt="..." style="height:110px; object-fit: cover;">' +
                        '<div class="card-body" style="height:0px">' +
                        '<p class="card-title text-muted" style="text-align: start; margin-top:-10px">' +
                        truncateWords(value.item_name, 3, '...') +
                        "</p>" +
                        '<p class="card-text" style="text-align: start; margin-top: -5px; font-size: 15px; font-weight: 600">' +
                        value.item_price.toLocaleString() +
                        " MMK <br></p>" +
                        "</div>" +
                        "</div>" +
                        "</button>" +
                        "</div>"
                    );
                });
            }, // Move the closing parenthesis to here
        });
    });

    $("#itemSearch").on("input", function() {
        var searchKey = $("#itemSearch").val();

        $.ajax({
            type: "GET",
            url: "searchKey/getItem",
            data: {
                searchKey: searchKey,
            },
            success: function(data) {
                $(".item_row").empty();
                $.each(data, function(key, value) {
                    if (value.store_qty <= 0) {
                        var disabled = "";
                        var textColor = "red";
                    } else {
                        var disabled = "";
                        var textColor = "green";
                    }

                    var imageSrc = (value.item_image == null || value.item_image === "") 
                        ? "/404_image.png" 
                        : "/storage/Images/" + value.item_image;
                    
                    $(".item_row").append(
                        '<div class="col-6 col-md-3 col-lg-2">' +
                        '<button class="btn item-button p-0 w-100" style="height: 200px; border: none;" data-item_id="' +
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
                        '<img src="' + imageSrc + '" class="card-img-top w-100" alt="..." style="height:110px; object-fit: cover;">' +
                        '<div class="card-body" style="height:0px">' +
                        '<p class="card-title text-muted" style="text-align: start; margin-top:-10px">' +
                        truncateWords(value.item_name, 3, '...') +
                        "</p>" +
                        '<p class="card-text" style="text-align: start; margin-top: -5px; font-size: 15px; font-weight: 600">' +
                        value.item_price.toLocaleString() +
                        " MMK <br></p>" +
                        "</div>" +
                        "</div>" +
                        "</button>" +
                        "</div>"
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