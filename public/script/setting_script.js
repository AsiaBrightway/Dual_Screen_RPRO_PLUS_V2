// let delivery_list = document.querySelector(".delivery-list");
// if (localStorage.getItem("showMenu")) {
//     delivery_list.parentElement.parentElement.classList.add("showMenu");
// }
$(document).ready(function () {
    var dashboard_check_value = 0;

    var store_check_value = 0;
    var store_dine_in_check_value = 0;
    var store_sale_lists_check_value = 0;
    var store_reservation_check_value = 0;
    var store_canceled_orders_value = 0;

    var customers_check_value = 0;
    var customers_customer_check_value = 0;
    var customers_customer_type_check_value = 0;

    var stock_control_check_value = 0;
    var stock_control_stock_receive_check_value = 0;
    var stock_control_stock_receive_receive_check_value = 0;
    var stock_control_stock_receive_receive_lists_check_value = 0;
    var stock_control_stock_issue_check_value = 0;
    var stock_control_stock_issue_issue_check_value = 0;
    var stock_control_stock_issue_issue_lists_check_value = 0;
    var stock_control_issue_type_check_value = 0;
    var stock_control_stock_purchase_check_value = 0;
    var stock_control_stock_purchase_purchase_check_value = 0;
    var stock_control_stock_purchase_purchase_lists_check_value = 0;

    var card_check_value = 0;
    var card_coupon_card_check_value = 0;
    var card_member_card_check_value = 0;
    var card_member_card_card_check_value = 0;
    var card_member_card_card_type_check_value = 0;

    var users_check_value = 0;
    var users_employee_check_value = 0;
    var users_employee_employee_check_value = 0;
    var users_employee_employee_position_check_value = 0;
    var users_users_check_value = 0;
    var users_users_user_check_value = 0;

    var suppliers_check_value = 0;
    var suppliers_supplier_check_value = 0;
    var suppliers_supplier_lists_check_value = 0;

    var configuration_check_value = 0;
    var configuration_item_check_value = 0;
    var configuration_item_category_check_value = 0;
    var configuration_item_item_check_value = 0;
    var configuration_item_unit_check_value = 0;
    var configuration_item_discount_check_value = 0;
    var configuration_item_price_control_check_value = 0;
    var configuration_floor_check_value = 0;
    var configuration_table_check_value = 0;
    var configuration_location_check_value = 0;
    var configuration_delivery_check_value = 0;

    var reports_check_value = 0;
    var reports_stock_in_check_value = 0;
    var reports_stock_out_check_value = 0;
    var reports_purchase_check_value = 0;
    var reports_sales_check_value = 0;
    var reports_top_sales_items_check_value = 0;
    // var reports_multishop_report_check_value = 0;

    var setting_check_value = 0;

    $("#clear").click(function () {
        $("#select_all_check").prop("checked", false);
        $("#dashboard_check").prop("checked", false);

        $("#store_check").prop("checked", false);
        $("#store_dine_in_check").prop("checked", false);
        $("#store_sale_lists_check").prop("checked", false);
        $("#store_reservation_check").prop("checked", false);
        $("#store_canceled_orders_check").prop("checked", false);

        $("#customers_check").prop("checked", false);
        $("#customers_customer_check").prop("checked", false);
        $("#customers_customer_type_check").prop("checked", false);

        $("#stock_control_check").prop("checked", false);
        $("#stock_control_stock_receive_check").prop("checked", false);
        $("#stock_control_stock_receive_receive_check").prop("checked", false);
        $("#stock_control_stock_receive_receive_lists_check").prop(
            "checked",
            false
        );
        $("#stock_control_stock_issue_check").prop("checked", false);
        $("#stock_control_stock_issue_issue_check").prop("checked", false);
        $("#stock_control_stock_issue_issue_lists_check").prop(
            "checked",
            false
        );
        $("#stock_control_issue_type_check").prop("checked", false);
        $("#stock_control_stock_purchase_check").prop("checked", false);
        $("#stock_control_stock_purchase_purchase_check").prop(
            "checked",
            false
        );
        $("#stock_control_stock_purchase_purchase_lists_check").prop(
            "checked",
            false
        );

        $("#card_check").prop("checked", false);
        $("#card_coupon_card_check").prop("checked", false);
        $("#card_member_card_check").prop("checked", false);

        $("#users_check").prop("checked", false);
        $("#users_employee_check").prop("checked", false);
        $("#users_employee_employee_check").prop("checked", false);
        $("#users_employee_employee_position_check").prop("checked", false);
        $("#users_users_check").prop("checked", false);
        $("#users_users_user_check").prop("checked", false);

        $("#suppliers_check").prop("checked", false);
        $("#suppliers_supplier_check").prop("checked", false);
        $("#suppliers_supplier_lists_check").prop("checked", false);

        $("#configuration_check").prop("checked", false);
        $("#configuration_item_check").prop("checked", false);
        $("#configuration_item_category_check").prop("checked", false);
        $("#configuration_item_item_check").prop("checked", false);
        $("#configuration_item_unit_check").prop("checked", false);
        $("#configuration_item_discount_check").prop("checked", false);
        $("#configuration_item_price_control_check").prop("checked", false);
        $("#configuration_floor_check").prop("checked", false);
        $("#configuration_table_check").prop("checked", false);
        $("#configuration_location_check").prop("checked", false);
        $("#configuration_delivery_check").prop("checked", false);

        $("#reports_check").prop("checked", false);
        $("#reports_stock_in_check").prop("checked", false);
        $("#reports_stock_out_check").prop("checked", false);
        $("#reports_purchase_check").prop("checked", false);
        $("#reports_sales_check").prop("checked", false);
        $("#reports_top_sales_items_check").prop("checked", false);
        // $("#reports_multishop_report_check").prop("checked", false);

        $("#setting_check").prop("checked", false);

        $(".store_child_div").addClass("d-none");
        $(".customers_child_div").addClass("d-none");
        $(".stock_control_stock_receive_child_div").addClass("d-none");
        $(".stock_control_stock_issue_child_div").addClass("d-none");
        $(".stock_control_stock_purchase_child_div").addClass("d-none");
        $(".stock_control_child_div").addClass("d-none");
        $(".card_child_div").addClass("d-none");
        $(".users_employee_child_div").addClass("d-none");
        $(".users_users_child_div").addClass("d-none");
        $(".users_child_div").addClass("d-none");
        $(".suppliers_child_div").addClass("d-none");
        $(".configuration_item_child_div").addClass("d-none");
        $(".configuration_child_check").addClass("d-none");
        $(".reports_child_div").addClass("d-none");
    });
    $("#select_all_check").click(function () {
        var select_all_check = $("#select_all_check").is(":checked");
        if (select_all_check) {
            dashboard_check_value = 1;

            store_check_value = 1;
            store_dine_in_check_value = 1;
            store_sale_lists_check_value = 1;
            store_reservation_check_value = 1;
            store_canceled_orders_value = 1;

            customers_check_value = 1;
            customers_customer_check_value = 1;
            customers_customer_type_check_value = 1;

            stock_control_check_value = 1;
            stock_control_stock_receive_check_value = 1;
            stock_control_stock_receive_receive_check_value = 1;
            stock_control_stock_receive_receive_lists_check_value = 1;
            stock_control_stock_issue_check_value = 1;
            stock_control_stock_issue_issue_check_value = 1;
            stock_control_stock_issue_issue_lists_check_value = 1;
            stock_control_issue_type_check_value = 1;
            stock_control_stock_purchase_check_value = 1;
            stock_control_stock_purchase_purchase_check_value = 1;
            stock_control_stock_purchase_purchase_lists_check_value = 1;
            stock_control_stock_balance_check_value = 1;

            card_check_value = 1;
            card_coupon_card_check_value = 1;
            card_member_card_check_value = 1;
            card_member_card_card_check_value = 1;
            card_member_card_card_type_check_value = 1;

            users_check_value = 1;
            users_employee_check_value = 1;
            users_employee_employee_check_value = 1;
            users_employee_employee_position_check_value = 1;
            users_users_check_value = 1;
            users_users_user_check_value = 1;

            suppliers_check_value = 1;
            suppliers_supplier_check_value = 1;
            suppliers_supplier_lists_check_value = 1;

            configuration_check_value = 1;
            configuration_item_check_value = 1;
            configuration_item_category_check_value = 1;
            configuration_item_item_check_value = 1;
            configuration_item_unit_check_value = 1;
            configuration_item_discount_check_value = 1;
            configuration_item_price_control_check_value = 1;
            configuration_floor_check_value = 1;
            configuration_table_check_value = 1;
            configuration_location_check_value = 1;
            configuration_delivery_check_value = 1;

            reports_check_value = 1;
            reports_stock_in_check_value = 1;
            reports_stock_out_check_value = 1;
            reports_purchase_check_value = 1;
            reports_sales_check_value = 1;
            reports_top_sales_items_check_value = 1;
            // reports_multishop_report_check_value = 1;

            setting_check_value = 1;

            $("#dashboard_check").prop("checked", true);

            $("#store_check").prop("checked", true);
            $("#store_dine_in_check").prop("checked", true);
            $("#store_sale_lists_check").prop("checked", true);
            $("#store_reservation_check").prop("checked", true);
            $("#store_canceled_orders_check").prop("checked", true);

            $("#customers_check").prop("checked", true);
            $("#customers_customer_check").prop("checked", true);
            $("#customers_customer_type_check").prop("checked", true);

            $("#stock_control_check").prop("checked", true);
            $("#stock_control_stock_receive_check").prop("checked", true);
            $("#stock_control_stock_receive_receive_check").prop(
                "checked",
                true
            );
            $("#stock_control_stock_receive_receive_lists_check").prop(
                "checked",
                true
            );
            $("#stock_control_stock_issue_check").prop("checked", true);
            $("#stock_control_stock_issue_issue_check").prop("checked", true);
            $("#stock_control_stock_issue_issue_lists_check").prop(
                "checked",
                true
            );
            $("#stock_control_issue_type_check").prop("checked", true);
            $("#stock_control_stock_purchase_check").prop("checked", true);
            $("#stock_control_stock_purchase_purchase_check").prop(
                "checked",
                true
            );
            $("#stock_control_stock_purchase_purchase_lists_check").prop(
                "checked",
                true
            );
            $("#stock_control_stock_balance_check").prop("checked", true);

            $("#card_check").prop("checked", true);
            $("#card_coupon_card_check").prop("checked", true);
            $("#card_member_card_check").prop("checked", true);
            $("#card_member_card_card_check").prop("checked", true);
            $("#card_member_card_card_type_check").prop("checked", true);

            $("#users_check").prop("checked", true);
            $("#users_employee_check").prop("checked", true);
            $("#users_employee_employee_check").prop("checked", true);
            $("#users_employee_employee_position_check").prop("checked", true);
            $("#users_users_check").prop("checked", true);
            $("#users_users_user_check").prop("checked", true);

            $("#suppliers_check").prop("checked", true);
            $("#suppliers_supplier_check").prop("checked", true);
            $("#suppliers_supplier_lists_check").prop("checked", true);

            $("#configuration_check").prop("checked", true);
            $("#configuration_item_check").prop("checked", true);
            $("#configuration_item_category_check").prop("checked", true);
            $("#configuration_item_item_check").prop("checked", true);
            $("#configuration_item_unit_check").prop("checked", true);
            $("#configuration_item_discount_check").prop("checked", true);
            $("#configuration_item_price_control_check").prop("checked", true);
            $("#configuration_floor_check").prop("checked", true);
            $("#configuration_table_check").prop("checked", true);
            $("#configuration_location_check").prop("checked", true);
            $("#configuration_delivery_check").prop("checked", true);

            $("#reports_check").prop("checked", true);
            $("#reports_stock_in_check").prop("checked", true);
            $("#reports_stock_out_check").prop("checked", true);
            $("#reports_purchase_check").prop("checked", true);
            $("#reports_sales_check").prop("checked", true);
            $("#reports_top_sales_items_check").prop("checked", true);
            // $("#reports_multishop_report_check").prop("checked", true);

            $("#setting_check").prop("checked", true);

            $(".store_child_div").removeClass("d-none");
            $(".customers_child_div").removeClass("d-none");
            $(".stock_control_stock_receive_child_div").removeClass("d-none");
            $(".stock_control_stock_issue_child_div").removeClass("d-none");
            $(".stock_control_stock_purchase_child_div").removeClass("d-none");
            $(".stock_control_child_div").removeClass("d-none");
            $(".card_child_div").removeClass("d-none");
            $(".card__member_card_child_div").removeClass("d-none");
            $(".users_employee_child_div").removeClass("d-none");
            $(".users_users_child_div").removeClass("d-none");
            $(".users_child_div").removeClass("d-none");
            $(".suppliers_child_div").removeClass("d-none");
            $(".configuration_item_child_div").removeClass("d-none");
            $(".configuration_child_check").removeClass("d-none");
            $(".reports_child_div").removeClass("d-none");
        } else {
            dashboard_check_value = 0;

            store_check_value = 0;
            store_dine_in_check_value = 0;
            store_sale_lists_check_value = 0;
            store_reservation_check_value = 0;
            store_canceled_orders_value = 0;

            customers_check_value = 0;
            customers_customer_check_value = 0;
            customers_customer_type_check_value = 0;

            stock_control_check_value = 0;
            stock_control_stock_receive_check_value = 0;
            stock_control_stock_receive_receive_check_value = 0;
            stock_control_stock_receive_receive_lists_check_value = 0;
            stock_control_stock_issue_check_value = 0;
            stock_control_stock_issue_issue_check_value = 0;
            stock_control_stock_issue_issue_lists_check_value = 0;
            stock_control_issue_type_check_value = 0;
            stock_control_stock_purchase_check_value = 0;
            stock_control_stock_purchase_purchase_check_value = 0;
            stock_control_stock_purchase_purchase_lists_check_value = 0;
            stock_control_stock_balance_check_value = 0;

            card_check_value = 0;
            card_coupon_card_check_value = 0;
            card_member_card_check_value = 0;
            card_member_card_card_check_value = 0;
            card_member_card_card_type_check_value = 0;

            users_check_value = 0;
            users_employee_check_value = 0;
            users_employee_employee_check_value = 0;
            users_employee_employee_position_check_value = 0;
            users_users_check_value = 0;
            users_users_user_check_value = 0;

            suppliers_check_value = 0;
            suppliers_supplier_check_value = 0;
            suppliers_supplier_lists_check_value = 0;

            configuration_check_value = 0;
            configuration_item_check_value = 0;
            configuration_item_category_check_value = 0;
            configuration_item_item_check_value = 0;
            configuration_item_unit_check_value = 0;
            configuration_item_discount_check_value = 0;
            configuration_item_price_control_check_value = 0;
            configuration_floor_check_value = 0;
            configuration_table_check_value = 0;
            configuration_location_check_value = 0;
            configuration_delivery_check_value = 0;

            reports_check_value = 0;
            reports_stock_in_check_value = 0;
            reports_stock_out_check_value = 0;
            reports_purchase_check_value = 0;
            reports_sales_check_value = 0;
            // reports_multishop_report_check_value = 0;
            reports_top_sales_items_check_value = 0;

            setting_check_value = 0;
            $("#dashboard_check").prop("checked", false);

            $("#store_check").prop("checked", false);
            $("#store_dine_in_check").prop("checked", false);
            $("#store_sale_lists_check").prop("checked", false);
            $("#store_reservation_check").prop("checked", false);
            $("#store_canceled_orders_check").prop("checked", false);

            $("#customers_check").prop("checked", false);
            $("#customers_customer_check").prop("checked", false);
            $("#customers_customer_type_check").prop("checked", false);

            $("#stock_control_check").prop("checked", false);
            $("#stock_control_stock_receive_check").prop("checked", false);
            $("#stock_control_stock_receive_receive_check").prop(
                "checked",
                false
            );
            $("#stock_control_stock_receive_receive_lists_check").prop(
                "checked",
                false
            );
            $("#stock_control_stock_issue_check").prop("checked", false);
            $("#stock_control_stock_issue_issue_check").prop("checked", false);
            $("#stock_control_stock_issue_issue_lists_check").prop(
                "checked",
                false
            );
            $("#stock_control_issue_type_check").prop("checked", false);
            $("#stock_control_stock_purchase_check").prop("checked", false);
            $("#stock_control_stock_purchase_purchase_check").prop(
                "checked",
                false
            );
            $("#stock_control_stock_purchase_purchase_lists_check").prop(
                "checked",
                false
            );
            $("#stock_control_stock_balance_check").prop("checked", false);

            $("#card_check").prop("checked", false);
            $("#card_coupon_card_check").prop("checked", false);
            $("#card_member_card_check").prop("checked", false);
            $("#card_member_card_card_check").prop("checked", false);
            $("#card_member_card_card_type_check").prop("checked", false);

            $("#users_check").prop("checked", false);
            $("#users_employee_check").prop("checked", false);
            $("#users_employee_employee_check").prop("checked", false);
            $("#users_employee_employee_position_check").prop("checked", false);
            $("#users_users_check").prop("checked", false);
            $("#users_users_user_check").prop("checked", false);

            $("#suppliers_check").prop("checked", false);
            $("#suppliers_supplier_check").prop("checked", false);
            $("#suppliers_supplier_lists_check").prop("checked", false);

            $("#configuration_check").prop("checked", false);
            $("#configuration_item_check").prop("checked", false);
            $("#configuration_item_category_check").prop("checked", false);
            $("#configuration_item_item_check").prop("checked", false);
            $("#configuration_item_unit_check").prop("checked", false);
            $("#configuration_item_discount_check").prop("checked", false);
            $("#configuration_item_price_control_check").prop("checked", false);
            $("#configuration_floor_check").prop("checked", false);
            $("#configuration_table_check").prop("checked", false);
            $("#configuration_location_check").prop("checked", false);
            $("#configuration_delivery_check").prop("checked", false);

            $("#reports_check").prop("checked", false);
            $("#reports_stock_in_check").prop("checked", false);
            $("#reports_stock_out_check").prop("checked", false);
            $("#reports_purchase_check").prop("checked", false);
            $("#reports_sales_check").prop("checked", false);
            $("#reports_top_sales_items_check").prop("checked", false);
            // $("#reports_multishop_report_check").prop("checked", false);

            $("#setting_check").prop("checked", false);

            $(".store_child_div").addClass("d-none");
            $(".customers_child_div").addClass("d-none");
            $(".stock_control_stock_receive_child_div").addClass("d-none");
            $(".stock_control_stock_issue_child_div").addClass("d-none");
            $(".stock_control_stock_purchase_child_div").addClass("d-none");
            $(".stock_control_child_div").addClass("d-none");
            $(".card_child_div").addClass("d-none");
            $(".card__member_card_child_div").addClass("d-none");
            $(".users_employee_child_div").addClass("d-none");
            $(".users_users_child_div").addClass("d-none");
            $(".users_child_div").addClass("d-none");
            $(".suppliers_child_div").addClass("d-none");
            $(".configuration_item_child_div").addClass("d-none");
            $(".configuration_child_check").addClass("d-none");
            $(".reports_child_div").addClass("d-none");
        }
    });
    $("#dashboard_check").click(function () {
        $("#select_all_check").prop("checked", false);
        var dashboard_check = $("#dashboard_check").is(":checked");
        if (dashboard_check) {
            dashboard_check_value = 1;
        } else {
            dashboard_check_value = 0;
        }
    });
    $("#store_check").click(function () {
        $("#select_all_check").prop("checked", false);
        var store_check = $("#store_check").is(":checked");
        if (store_check) {
            $(".store_child_div").removeClass("d-none");
            store_check_value = 1;
            $("#store_dine_in_check").click(function () {
                $("#select_all_check").prop("checked", false);
                var store_dine_in_check = $("#store_dine_in_check").is(
                    ":checked"
                );
                if (store_dine_in_check) {
                    store_dine_in_check_value = 1;
                } else {
                    store_dine_in_check_value = 0;
                }
            });
            $("#store_sale_lists_check").click(function () {
                $("#select_all_check").prop("checked", false);
                var store_sale_lists_check = $("#store_sale_lists_check").is(
                    ":checked"
                );
                if (store_sale_lists_check) {
                    store_sale_lists_check_value = 1;
                } else {
                    store_sale_lists_check_value = 0;
                }
            });
            $("#store_reservation_check").click(function () {
                $("#select_all_check").prop("checked", false);
                var store_reservation_check = $("#store_reservation_check").is(
                    ":checked"
                );
                if (store_reservation_check) {
                    store_reservation_check_value = 1;
                } else {
                    store_reservation_check_value = 0;
                }
            });
            $("#store_canceled_orders_check").click(function () {
                $("#select_all_check").prop("checked", false);
                var store_canceled_orders_check = $("#store_canceled_orders_check").is(
                    ":checked"
                );
                if (store_canceled_orders_check) {
                    store_canceled_orders_check_value = 1;
                } else {
                    store_canceled_orders_check_value = 0;
                }
            })
        } else {
            store_check_value = 0;
            store_dine_in_check_value = 0;
            store_sale_lists_check_value = 0;
            store_reservation_check_value = 0;
            store_canceled_orders_value = 0;
            $("#store_dine_in_check").prop("checked", false);
            $("#store_sale_lists_check").prop("checked", false);
            $("#store_reservation_check").prop("checked", false);
            $("#store_canceled_orders_check").prop("checked", false);
            $(".store_child_div").addClass("d-none");
        }
    });
    $("#store_dine_in_check").click(function () {
        $("#select_all_check").prop("checked", false);
        var store_dine_in_check = $("#store_dine_in_check").is(":checked");
        if (store_dine_in_check) {
            store_dine_in_check_value = 1;
        } else {
            store_dine_in_check_value = 0;
        }
    });
    $("#store_sale_lists_check").click(function () {
        $("#select_all_check").prop("checked", false);
        var store_sale_lists_check = $("#store_sale_lists_check").is(
            ":checked"
        );
        if (store_sale_lists_check) {
            store_sale_lists_check_value = 1;
        } else {
            store_sale_lists_check_value = 0;
        }
    });
    $("#store_reservation_check").click(function () {
        $("#select_all_check").prop("checked", false);
        var store_reservation_check = $("#store_reservation_check").is(
            ":checked"
        );
        if (store_reservation_check) {
            store_reservation_check_value = 1;
        } else {
            store_reservation_check_value = 0;
        }
    });
    $("#store_canceled_orders_check").click(function () {
        $("#select_all_check").prop("checked", false);
        var store_canceled_orders_check = $("#store_canceled_orders_check").is(
            ":checked"
        );
        if (store_canceled_orders_check) {
            store_canceled_orders_value = 1;
        } else {
            store_canceled_orders_value = 0;
        }
    });
    $("#customers_check").click(function () {
        $("#select_all_check").prop("checked", false);
        var customers_check = $("#customers_check").is(":checked");
        if (customers_check) {
            $(".customers_child_div").removeClass("d-none");
            customers_check_value = 1;
            $("#customers_customer_check").click(function () {
                $("#select_all_check").prop("checked", false);
                var customers_customer_check = $(
                    "#customers_customer_check"
                ).is(":checked");
                if (customers_customer_check) {
                    customers_customer_check_value = 1;
                } else {
                    customers_customer_check_value = 0;
                }
            });
            $("#customers_customer_type_check").click(function () {
                $("#select_all_check").prop("checked", false);
                var customers_customer_type_check = $(
                    "#customers_customer_type_check"
                ).is(":checked");
                if (customers_customer_type_check) {
                    customers_customer_type_check_value = 1;
                } else {
                    customers_customer_type_check_value = 0;
                }
            });
        } else {
            customers_check_value = 0;
            customers_customer_check_value = 0;
            customers_customer_type_check_value = 0;
            $("#customers_customer_check").prop("checked", false);
            $("#customers_customer_type_check").prop("checked", false);
            $(".customers_child_div").addClass("d-none");
        }
    });
    $("#customers_customer_check").click(function () {
        $("#select_all_check").prop("checked", false);
        var customers_customer_check = $("#customers_customer_check").is(
            ":checked"
        );
        if (customers_customer_check) {
            customers_customer_check_value = 1;
        } else {
            customers_customer_check_value = 0;
        }
    });
    $("#customers_customer_type_check").click(function () {
        $("#select_all_check").prop("checked", false);
        var customers_customer_type_check = $(
            "#customers_customer_type_check"
        ).is(":checked");
        if (customers_customer_type_check) {
            customers_customer_type_check_value = 1;
        } else {
            customers_customer_type_check_value = 0;
        }
    });
    $("#stock_control_check").click(function () {
        $("#select_all_check").prop("checked", false);
        var stock_control_check = $("#stock_control_check").is(":checked");
        if (stock_control_check) {
            $(".stock_control_child_div").removeClass("d-none");
            stock_control_check_value = 1;
            $("#stock_control_stock_receive_check").click(function () {
                $("#select_all_check").prop("checked", false);
                var stock_control_stock_receive_check = $(
                    "#stock_control_stock_receive_check"
                ).is(":checked");
                if (stock_control_stock_receive_check) {
                    $(".stock_control_stock_receive_child_div").removeClass(
                        "d-none"
                    );
                    stock_control_stock_receive_check_value = 1;
                    $("#stock_control_stock_receive_receive_check").click(
                        function () {
                            var stock_control_stock_receive_receive_check = $(
                                "#stock_control_stock_receive_receive_check"
                            ).is(":checked");
                            if (stock_control_stock_receive_receive_check) {
                                stock_control_stock_receive_receive_check_value = 1;
                            } else {
                                stock_control_stock_receive_receive_check_value = 0;
                            }
                        }
                    );
                    $("#stock_control_stock_receive_receive_lists_check").click(
                        function () {
                            var stock_control_stock_receive_receive_lists_check =
                                $(
                                    "#stock_control_stock_receive_receive_lists_check"
                                ).is(":checked");
                            if (
                                stock_control_stock_receive_receive_lists_check
                            ) {
                                stock_control_stock_receive_receive_lists_check_value = 1;
                            } else {
                                stock_control_stock_receive_receive_lists_check_value = 0;
                            }
                        }
                    );
                } else {
                    stock_control_stock_receive_check_value = 0;
                    stock_control_stock_receive_receive_check_value = 0;
                    stock_control_stock_receive_receive_lists_check_value = 0;
                    $("#stock_control_stock_receive_receive_check").prop(
                        "checked",
                        false
                    );
                    $("#stock_control_stock_receive_receive_lists_check").prop(
                        "checked",
                        false
                    );
                    $(".stock_control_stock_receive_child_div").addClass(
                        "d-none"
                    );
                }
            });
            $("#stock_control_stock_issue_check").click(function () {
                $("#select_all_check").prop("checked", false);
                var stock_control_stock_issue_check = $(
                    "#stock_control_stock_issue_check"
                ).is(":checked");
                if (stock_control_stock_issue_check) {
                    $(".stock_control_stock_issue_child_div").removeClass(
                        "d-none"
                    );
                    stock_control_stock_issue_check_value = 1;
                    $("#stock_control_stock_issue_issue_check").click(
                        function () {
                            var stock_control_stock_issue_issue_check = $(
                                "#stock_control_stock_issue_issue_check"
                            ).is(":checked");
                            if (stock_control_stock_issue_issue_check) {
                                stock_control_stock_issue_issue_check_value = 1;
                            } else {
                                stock_control_stock_issue_issue_check_value = 0;
                            }
                        }
                    );
                    $("#stock_control_stock_issue_issue_lists_check").click(
                        function () {
                            var stock_control_stock_issue_issue_lists_check = $(
                                "#stock_control_stock_issue_issue_lists_check"
                            ).is(":checked");
                            if (stock_control_stock_issue_issue_lists_check) {
                                stock_control_stock_issue_issue_lists_check_value = 1;
                            } else {
                                stock_control_stock_issue_issue_lists_check_value = 0;
                            }
                        }
                    );
                } else {
                    stock_control_stock_issue_check_value = 0;
                    stock_control_stock_issue_issue_check_value = 0;
                    stock_control_stock_issue_issue_lists_check_value = 0;
                    $("#stock_control_stock_issue_issue_check").prop(
                        "checked",
                        false
                    );
                    $("#stock_control_stock_issue_issue_lists_check").prop(
                        "checked",
                        false
                    );
                    $(".stock_control_stock_issue_child_div").addClass(
                        "d-none"
                    );
                }
            });
            $("#stock_control_issue_type_check").click(function () {
                $("#select_all_check").prop("checked", false);
                var stock_control_issue_type_check = $(
                    "#stock_control_issue_type_check"
                ).is(":checked");
                if (stock_control_issue_type_check) {
                    stock_control_issue_type_check_value = 1;
                } else {
                    stock_control_issue_type_check_value = 0;
                }
            });
            $("#stock_control_stock_purchase_check").click(function () {
                $("#select_all_check").prop("checked", false);
                var stock_control_stock_purchase_check = $(
                    "#stock_control_stock_purchase_check"
                ).is(":checked");
                if (stock_control_stock_purchase_check) {
                    $(".stock_control_stock_purchase_child_div").removeClass(
                        "d-none"
                    );
                    stock_control_stock_purchase_check_value = 1;
                    $("#stock_control_stock_purchase_purchase_check").click(
                        function () {
                            var stock_control_stock_purchase_purchase_check = $(
                                "#stock_control_stock_purchase_purchase_check"
                            ).is(":checked");
                            if (stock_control_stock_purchase_purchase_check) {
                                stock_control_stock_purchase_purchase_check_value = 1;
                            } else {
                                stock_control_stock_purchase_purchase_check_value = 0;
                            }
                        }
                    );
                    $(
                        "#stock_control_stock_purchase_purchase_lists_check"
                    ).click(function () {
                        var stock_control_stock_purchase_purchase_lists_check =
                            $(
                                "#stock_control_stock_purchase_purchase_lists_check"
                            ).is(":checked");
                        if (stock_control_stock_purchase_purchase_lists_check) {
                            stock_control_stock_purchase_purchase_lists_check_value = 1;
                        } else {
                            stock_control_stock_purchase_purchase_lists_check_value = 0;
                        }
                    });
                } else {
                    stock_control_stock_purchase_check_value = 0;
                    stock_control_stock_purchase_purchase_check_value = 0;
                    stock_control_stock_purchase_purchase_lists_check_value = 0;
                    $("#stock_control_stock_purchase_purchase_check").prop(
                        "checked",
                        false
                    );
                    $(
                        "#stock_control_stock_purchase_purchase_lists_check"
                    ).prop("checked", false);
                    $(".stock_control_stock_purchase_child_div").addClass(
                        "d-none"
                    );
                }
            });
            $("#stock_control_stock_balance_check").click(function () {
                var stock_control_stock_balance_check = $(
                    "#stock_control_stock_balance_check"
                ).is(":checked");
                if (stock_control_stock_balance_check) {
                    stock_control_stock_balance_check_value = 1;
                } else {
                    stock_control_stock_balance_check_value = 0;
                }
            });
        } else {
            stock_control_check_value = 0;
            stock_control_stock_receive_check_value = 0;
            stock_control_stock_receive_receive_check_value = 0;
            stock_control_stock_receive_receive_lists_check_value = 0;
            stock_control_stock_issue_check_value = 0;
            stock_control_stock_issue_issue_check_value = 0;
            stock_control_stock_issue_issue_lists_check_value = 0;
            stock_control_issue_type_check_value = 0;
            stock_control_stock_purchase_check_value = 0;
            stock_control_stock_purchase_purchase_check_value = 0;
            stock_control_stock_purchase_purchase_lists_check_value = 0;
            stock_control_stock_balance_check_value = 0;

            $("#stock_control_stock_receive_check").prop("checked", false);
            $("#stock_control_stock_receive_receive_check").prop(
                "checked",
                false
            );
            $("#stock_control_stock_receive_receive_lists_check").prop(
                "checked",
                false
            );
            $("#stock_control_stock_issue_check").prop("checked", false);
            $("#stock_control_stock_issue_issue_check").prop("checked", false);
            $("#stock_control_stock_issue_issue_lists_check").prop(
                "checked",
                false
            );
            $("#stock_control_issue_type_check").prop("checked", false);
            $("#stock_control_stock_purchase_check").prop("checked", false);
            $("#stock_control_stock_purchase_purchase_check").prop(
                "checked",
                false
            );
            $("#stock_control_stock_purchase_purchase_lists_check").prop(
                "checked",
                false
            );
            $("#stock_control_stock_balance_check").prop("checked", false);

            $(".stock_control_stock_receive_child_div").addClass("d-none");
            $(".stock_control_stock_issue_child_div").addClass("d-none");
            $(".stock_control_stock_purchase_child_div").addClass("d-none");
            $(".stock_control_child_div").addClass("d-none");
        }
    });
    $("#stock_control_stock_receive_check").click(function () {
        $("#select_all_check").prop("checked", false);
        var stock_control_stock_receive_check = $(
            "#stock_control_stock_receive_check"
        ).is(":checked");
        if (stock_control_stock_receive_check) {
            $(".stock_control_stock_receive_child_div").removeClass("d-none");
            stock_control_stock_receive_check_value = 1;
            $("#stock_control_stock_receive_receive_check").click(function () {
                var stock_control_stock_receive_receive_check = $(
                    "#stock_control_stock_receive_receive_check"
                ).is(":checked");
                if (stock_control_stock_receive_receive_check) {
                    stock_control_stock_receive_receive_check_value = 1;
                } else {
                    stock_control_stock_receive_receive_check_value = 0;
                }
            });
            $("#stock_control_stock_receive_receive_lists_check").click(
                function () {
                    var stock_control_stock_receive_receive_lists_check = $(
                        "#stock_control_stock_receive_receive_lists_check"
                    ).is(":checked");
                    if (stock_control_stock_receive_receive_lists_check) {
                        stock_control_stock_receive_receive_lists_check_value = 1;
                    } else {
                        stock_control_stock_receive_receive_lists_check_value = 0;
                    }
                }
            );
        } else {
            stock_control_stock_receive_check_value = 0;
            stock_control_stock_receive_receive_check_value = 0;
            stock_control_stock_receive_receive_lists_check_value = 0;
            $("#stock_control_stock_receive_receive_check").prop(
                "checked",
                false
            );
            $("#stock_control_stock_receive_receive_lists_check").prop(
                "checked",
                false
            );
            $(".stock_control_stock_receive_child_div").addClass("d-none");
        }
    });
    $("#stock_control_stock_receive_receive_check").click(function () {
        var stock_control_stock_receive_receive_check = $(
            "#stock_control_stock_receive_receive_check"
        ).is(":checked");
        if (stock_control_stock_receive_receive_check) {
            stock_control_stock_receive_receive_check_value = 1;
        } else {
            stock_control_stock_receive_receive_check_value = 0;
        }
    });
    $("#stock_control_stock_receive_receive_lists_check").click(function () {
        var stock_control_stock_receive_receive_lists_check = $(
            "#stock_control_stock_receive_receive_lists_check"
        ).is(":checked");
        if (stock_control_stock_receive_receive_lists_check) {
            stock_control_stock_receive_receive_lists_check_value = 1;
        } else {
            stock_control_stock_receive_receive_lists_check_value = 0;
        }
    });
    $("#stock_control_stock_issue_check").click(function () {
        $("#select_all_check").prop("checked", false);
        var stock_control_stock_issue_check = $(
            "#stock_control_stock_issue_check"
        ).is(":checked");
        if (stock_control_stock_issue_check) {
            $(".stock_control_stock_issue_child_div").removeClass("d-none");
            stock_control_stock_issue_check_value = 1;
            $("#stock_control_stock_issue_issue_check").click(function () {
                var stock_control_stock_issue_issue_check = $(
                    "#stock_control_stock_issue_issue_check"
                ).is(":checked");
                if (stock_control_stock_issue_issue_check) {
                    stock_control_stock_issue_issue_check_value = 1;
                } else {
                    stock_control_stock_issue_issue_check_value = 0;
                }
            });
            $("#stock_control_stock_issue_issue_lists_check").click(
                function () {
                    var stock_control_stock_issue_issue_lists_check = $(
                        "#stock_control_stock_issue_issue_lists_check"
                    ).is(":checked");
                    if (stock_control_stock_issue_issue_lists_check) {
                        stock_control_stock_issue_issue_lists_check_value = 1;
                    } else {
                        stock_control_stock_issue_issue_lists_check_value = 0;
                    }
                }
            );
        } else {
            stock_control_stock_issue_check_value = 0;
            stock_control_stock_issue_issue_check_value = 0;
            stock_control_stock_issue_issue_lists_check_value = 0;
            $("#stock_control_stock_issue_issue_check").prop("checked", false);
            $("#stock_control_stock_issue_issue_lists_check").prop(
                "checked",
                false
            );
            $(".stock_control_stock_issue_child_div").addClass("d-none");
        }
    });
    $("#stock_control_stock_issue_issue_check").click(function () {
        var stock_control_stock_issue_issue_check = $(
            "#stock_control_stock_issue_issue_check"
        ).is(":checked");
        if (stock_control_stock_issue_issue_check) {
            stock_control_stock_issue_issue_check_value = 1;
        } else {
            stock_control_stock_issue_issue_check_value = 0;
        }
    });
    $("#stock_control_stock_issue_issue_lists_check").click(function () {
        var stock_control_stock_issue_issue_lists_check = $(
            "#stock_control_stock_issue_issue_lists_check"
        ).is(":checked");
        if (stock_control_stock_issue_issue_lists_check) {
            stock_control_stock_issue_issue_lists_check_value = 1;
        } else {
            stock_control_stock_issue_issue_lists_check_value = 0;
        }
    });
    $("#stock_control_issue_type_check").click(function () {
        $("#select_all_check").prop("checked", false);
        var stock_control_issue_type_check = $(
            "#stock_control_issue_type_check"
        ).is(":checked");
        if (stock_control_issue_type_check) {
            stock_control_issue_type_check_value = 1;
        } else {
            stock_control_issue_type_check_value = 0;
        }
    });
    $("#stock_control_stock_purchase_check").click(function () {
        $("#select_all_check").prop("checked", false);
        var stock_control_stock_purchase_check = $(
            "#stock_control_stock_purchase_check"
        ).is(":checked");
        if (stock_control_stock_purchase_check) {
            $(".stock_control_stock_purchase_child_div").removeClass("d-none");
            stock_control_stock_purchase_check_value = 1;
            $("#stock_control_stock_purchase_purchase_check").click(
                function () {
                    var stock_control_stock_purchase_purchase_check = $(
                        "#stock_control_stock_purchase_purchase_check"
                    ).is(":checked");
                    if (stock_control_stock_purchase_purchase_check) {
                        stock_control_stock_purchase_purchase_check_value = 1;
                    } else {
                        stock_control_stock_purchase_purchase_check_value = 0;
                    }
                }
            );
            $("#stock_control_stock_purchase_purchase_lists_check").click(
                function () {
                    var stock_control_stock_purchase_purchase_lists_check = $(
                        "#stock_control_stock_purchase_purchase_lists_check"
                    ).is(":checked");
                    if (stock_control_stock_purchase_purchase_lists_check) {
                        stock_control_stock_purchase_purchase_lists_check_value = 1;
                    } else {
                        stock_control_stock_purchase_purchase_lists_check_value = 0;
                    }
                }
            );
        } else {
            stock_control_stock_purchase_check_value = 0;
            stock_control_stock_purchase_purchase_check_value = 0;
            stock_control_stock_purchase_purchase_lists_check_value = 0;
            $("#stock_control_stock_purchase_purchase_check").prop(
                "checked",
                false
            );
            $("#stock_control_stock_purchase_purchase_lists_check").prop(
                "checked",
                false
            );
            $(".stock_control_stock_purchase_child_div").addClass("d-none");
        }
    });
    $("#stock_control_stock_purchase_purchase_check").click(function () {
        var stock_control_stock_purchase_purchase_check = $(
            "#stock_control_stock_purchase_purchase_check"
        ).is(":checked");
        if (stock_control_stock_purchase_purchase_check) {
            stock_control_stock_purchase_purchase_check_value = 1;
        } else {
            stock_control_stock_purchase_purchase_check_value = 0;
        }
    });
    $("#stock_control_stock_purchase_purchase_lists_check").click(function () {
        var stock_control_stock_purchase_purchase_lists_check = $(
            "#stock_control_stock_purchase_purchase_lists_check"
        ).is(":checked");
        if (stock_control_stock_purchase_purchase_lists_check) {
            stock_control_stock_purchase_purchase_lists_check_value = 1;
        } else {
            stock_control_stock_purchase_purchase_lists_check_value = 0;
        }
    });
    $("#stock_control_stock_balance_check").click(function () {
        var stock_control_stock_balance_check = $(
            "#stock_control_stock_balance_check"
        ).is(":checked");
        if (stock_control_stock_balance_check) {
            stock_control_stock_balance_check_value = 1;
        } else {
            stock_control_stock_balance_check_value = 0;
        }
    });
    $("#card_check").click(function () {
        $("#select_all_check").prop("checked", false);
        var card_check = $("#card_check").is(":checked");
        if (card_check) {
            $(".card_child_div").removeClass("d-none");
            card_check_value = 1;
            $("#card_coupon_card_check").click(function () {
                $("#select_all_check").prop("checked", false);
                var card_coupon_card_check = $("#card_coupon_card_check").is(
                    ":checked"
                );
                if (card_coupon_card_check) {
                    card_coupon_card_check_value = 1;
                } else {
                    card_coupon_card_check_value = 0;
                }
            });
            $("#card_member_card_check").click(function () {
                $("#select_all_check").prop("checked", false);
                var card_member_card_check = $("#card_member_card_check").is(
                    ":checked"
                );
                if (card_member_card_check) {
                    $(".card__member_card_child_div").removeClass("d-none");
                    card_member_card_check_value = 1;
                    $("#card_member_card_card_check").click(function () {
                        var card_member_card_card_check = $(
                            "#card_member_card_card_check"
                        ).is(":checked");
                        if (card_member_card_card_check) {
                            card_member_card_card_check_value = 1;
                        } else {
                            card_member_card_card_check_value = 0;
                        }
                    });
                    $("#card_member_card_card_type_check").click(function () {
                        var card_member_card_card_type_check = $(
                            "#card_member_card_card_type_check"
                        ).is(":checked");
                        if (card_member_card_card_type_check) {
                            card_member_card_card_type_check_value = 1;
                        } else {
                            card_member_card_card_type_check_value = 0;
                        }
                    });
                } else {
                    card_member_card_check_value = 0;
                    card_member_card_card_check_value = 0;
                    card_member_card_card_type_check_value = 0;
                    $("#card_member_card_card_check").prop("checked", false);
                    $("#card_member_card_card_type_check").prop(
                        "checked",
                        false
                    );
                    $(".card__member_card_child_div").addClass("d-none");
                }
            });
        } else {
            card_check_value = 1;
            card_coupon_card_check_value = 0;
            card_member_card_check_value = 0;
            card_member_card_card_check_value = 0;
            card_member_card_card_type_check_value = 0;
            $("#card_coupon_card_check").prop("checked", false);
            $("#card_member_card_check").prop("checked", false);
            $(".card_child_div").addClass("d-none");
        }
    });
    $("#card_coupon_card_check").click(function () {
        $("#select_all_check").prop("checked", false);
        var card_coupon_card_check = $("#card_coupon_card_check").is(
            ":checked"
        );
        if (card_coupon_card_check) {
            card_coupon_card_check_value = 1;
        } else {
            card_coupon_card_check_value = 0;
        }
    });
    $("#card_member_card_check").click(function () {
        $("#select_all_check").prop("checked", false);
        var card_member_card_check = $("#card_member_card_check").is(
            ":checked"
        );
        if (card_member_card_check) {
            $(".card__member_card_child_div").removeClass("d-none");
            card_member_card_check_value = 1;
            $("#card_member_card_card_check").click(function () {
                var card_member_card_card_check = $(
                    "#card_member_card_card_check"
                ).is(":checked");
                if (card_member_card_card_check) {
                    card_member_card_card_check_value = 1;
                } else {
                    card_member_card_card_check_value = 0;
                }
            });
            $("#card_member_card_card_type_check").click(function () {
                var card_member_card_card_type_check = $(
                    "#card_member_card_card_type_check"
                ).is(":checked");
                if (card_member_card_card_type_check) {
                    card_member_card_card_type_check_value = 1;
                } else {
                    card_member_card_card_type_check_value = 0;
                }
            });
        } else {
            card_member_card_check_value = 0;
            card_member_card_card_check_value = 0;
            card_member_card_card_type_check_value = 0;
            $("#card_member_card_card_check").prop("checked", false);
            $("#card_member_card_card_type_check").prop("checked", false);
            $(".card__member_card_child_div").addClass("d-none");
        }
    });
    $("#card_member_card_card_check").click(function () {
        var card_member_card_card_check = $("#card_member_card_card_check").is(
            ":checked"
        );
        if (card_member_card_card_check) {
            card_member_card_card_check_value = 1;
        } else {
            card_member_card_card_check_value = 0;
        }
    });
    $("#card_member_card_card_type_check").click(function () {
        var card_member_card_card_type_check = $(
            "#card_member_card_card_type_check"
        ).is(":checked");
        if (card_member_card_card_type_check) {
            card_member_card_card_type_check_value = 1;
        } else {
            card_member_card_card_type_check_value = 0;
        }
    });
    $("#users_check").click(function () {
        $("#select_all_check").prop("checked", false);
        var users_check = $("#users_check").is(":checked");
        if (users_check) {
            $(".users_child_div").removeClass("d-none");
            users_check_value = 1;
            $("#users_employee_check").click(function () {
                $("#select_all_check").prop("checked", false);
                var users_employee_check = $("#users_employee_check").is(
                    ":checked"
                );
                if (users_employee_check) {
                    $(".users_employee_child_div").removeClass("d-none");
                    users_employee_check_value = 1;
                    $("#users_employee_employee_check").click(function () {
                        var users_employee_employee_check = $(
                            "#users_employee_employee_check"
                        ).is(":checked");
                        if (users_employee_employee_check) {
                            users_employee_employee_check_value = 1;
                        } else {
                            users_employee_employee_check_value = 0;
                        }
                    });
                    $("#users_employee_employee_position_check").click(
                        function () {
                            var users_employee_employee_position_check = $(
                                "#users_employee_employee_position_check"
                            ).is(":checked");
                            if (users_employee_employee_position_check) {
                                users_employee_employee_position_check_value = 1;
                            } else {
                                users_employee_employee_position_check_value = 0;
                            }
                        }
                    );
                } else {
                    users_employee_check_value = 0;
                    users_employee_employee_check_value = 0;
                    users_employee_employee_position_check_value = 0;
                    $("#users_employee_employee_check").prop("checked", false);
                    $("#users_employee_employee_position_check").prop(
                        "checked",
                        false
                    );
                    $(".users_employee_child_div").addClass("d-none");
                }
            });
            $("#users_users_check").click(function () {
                $("#select_all_check").prop("checked", false);
                var users_users_check = $("#users_users_check").is(":checked");
                if (users_users_check) {
                    $(".users_users_child_div").removeClass("d-none");
                    users_users_check_value = 1;
                    $("#users_users_user_check").click(function () {
                        var users_users_user_check = $(
                            "#users_users_user_check"
                        ).is(":checked");
                        if (users_users_user_check) {
                            users_users_user_check_value = 1;
                        } else {
                            users_users_user_check_value = 0;
                        }
                    });
                    $("#users_users_user_role_check").click(function () {
                        var users_users_user_role_check = $(
                            "#users_users_user_role_check"
                        ).is(":checked");
                        if (users_users_user_role_check) {
                            users_users_user_role_check_value = 1;
                        } else {
                            users_users_user_role_check_value = 0;
                        }
                    });
                } else {
                    users_users_check_value = 0;
                    users_users_user_check_value = 0;
                    users_users_user_role_check_value = 0;
                    $("#users_users_user_check").prop("checked", false);
                    $("#users_users_user_role_check").prop("checked", false);
                    $(".users_users_child_div").addClass("d-none");
                }
            });
        } else {
            users_check_value = 0;
            users_employee_check_value = 0;
            users_employee_employee_check_value = 0;
            users_employee_employee_position_check_value = 0;
            users_users_check = 0;
            users_users_user_check_value = 0;
            users_users_user_role_check_value = 0;
            $("#users_employee_check").prop("checked", false);
            $("#users_employee_employee_check").prop("checked", false);
            $("#users_employee_employee_position_check").prop("checked", false);
            $("#users_users_check").prop("checked", false);
            $("#users_users_user_check").prop("checked", false);
            $("#users_users_user_role_check").prop("checked", false);
            $(".users_employee_child_div").addClass("d-none");
            $(".users_users_child_div").addClass("d-none");
            $(".users_child_div").addClass("d-none");
        }
    });
    $("#users_employee_check").click(function () {
        $("#select_all_check").prop("checked", false);
        var users_employee_check = $("#users_employee_check").is(":checked");
        if (users_employee_check) {
            $(".users_employee_child_div").removeClass("d-none");
            users_employee_check_value = 1;
            $("#users_employee_employee_check").click(function () {
                var users_employee_employee_check = $(
                    "#users_employee_employee_check"
                ).is(":checked");
                if (users_employee_employee_check) {
                    users_employee_employee_check_value = 1;
                } else {
                    users_employee_employee_check_value = 0;
                }
            });
            $("#users_employee_employee_position_check").click(function () {
                var users_employee_employee_position_check = $(
                    "#users_employee_employee_position_check"
                ).is(":checked");
                if (users_employee_employee_position_check) {
                    users_employee_employee_position_check_value = 1;
                } else {
                    users_employee_employee_position_check_value = 0;
                }
            });
        } else {
            users_employee_check_value = 0;
            users_employee_employee_check_value = 0;
            users_employee_employee_position_check_value = 0;
            $("#users_employee_employee_check").prop("checked", false);
            $("#users_employee_employee_position_check").prop("checked", false);
            $(".users_employee_child_div").addClass("d-none");
        }
    });
    $("#users_employee_employee_check").click(function () {
        var users_employee_employee_check = $(
            "#users_employee_employee_check"
        ).is(":checked");
        if (users_employee_employee_check) {
            users_employee_employee_check_value = 1;
        } else {
            users_employee_employee_check_value = 0;
        }
    });
    $("#users_employee_employee_position_check").click(function () {
        var users_employee_employee_position_check = $(
            "#users_employee_employee_position_check"
        ).is(":checked");
        if (users_employee_employee_position_check) {
            users_employee_employee_position_check_value = 1;
        } else {
            users_employee_employee_position_check_value = 0;
        }
    });
    $("#users_users_check").click(function () {
        $("#select_all_check").prop("checked", false);
        var users_users_check = $("#users_users_check").is(":checked");
        if (users_users_check) {
            $(".users_users_child_div").removeClass("d-none");
            users_users_check_value = 1;
            $("#users_users_user_check").click(function () {
                var users_users_user_check = $("#users_users_user_check").is(
                    ":checked"
                );
                if (users_users_user_check) {
                    users_users_user_check_value = 1;
                } else {
                    users_users_user_check_value = 0;
                }
            });
        } else {
            users_users_check_value = 0;
            users_users_user_check_value = 0;
            $("#users_users_user_check").prop("checked", false);
            $(".users_users_child_div").addClass("d-none");
        }
    });
    $("#users_users_user_check").click(function () {
        var users_users_user_check = $("#users_users_user_check").is(
            ":checked"
        );
        if (users_users_user_check) {
            users_users_user_check_value = 1;
        } else {
            users_users_user_check_value = 0;
        }
    });
    $("#suppliers_check").click(function () {
        $("#select_all_check").prop("checked", false);
        var suppliers_check = $("#suppliers_check").is(":checked");
        if (suppliers_check) {
            $(".suppliers_child_div").removeClass("d-none");
            suppliers_check_value = 1;
            $("#suppliers_supplier_check").click(function () {
                $("#select_all_check").prop("checked", false);
                var suppliers_supplier_check = $(
                    "#suppliers_supplier_check"
                ).is(":checked");
                if (suppliers_supplier_check) {
                    suppliers_supplier_check_value = 1;
                } else {
                    suppliers_supplier_check_value = 0;
                }
            });
            $("#suppliers_supplier_lists_check").click(function () {
                $("#select_all_check").prop("checked", false);
                var suppliers_supplier_lists_check = $(
                    "#suppliers_supplier_lists_check"
                ).is(":checked");
                if (suppliers_supplier_lists_check) {
                    suppliers_supplier_lists_check_value = 1;
                } else {
                    suppliers_supplier_lists_check_value = 0;
                }
            });
        } else {
            suppliers_check_value = 0;
            suppliers_supplier_check_value = 0;
            suppliers_supplier_lists_check_value = 0;
            $("#suppliers_supplier_check").prop("checked", false);
            $("#suppliers_supplier_lists_check").prop("checked", false);
            $(".suppliers_child_div").addClass("d-none");
        }
    });
    $("#suppliers_supplier_check").click(function () {
        $("#select_all_check").prop("checked", false);
        var suppliers_supplier_check = $("#suppliers_supplier_check").is(
            ":checked"
        );
        if (suppliers_supplier_check) {
            suppliers_supplier_check_value = 1;
        } else {
            suppliers_supplier_check_value = 0;
        }
    });
    $("#suppliers_supplier_lists_check").click(function () {
        $("#select_all_check").prop("checked", false);
        var suppliers_supplier_lists_check = $(
            "#suppliers_supplier_lists_check"
        ).is(":checked");
        if (suppliers_supplier_lists_check) {
            suppliers_supplier_lists_check_value = 1;
        } else {
            suppliers_supplier_lists_check_value = 0;
        }
    });
    $("#configuration_check").click(function () {
        $("#select_all_check").prop("checked", false);
        var configuration_check = $("#configuration_check").is(":checked");
        if (configuration_check) {
            $(".configuration_child_check").removeClass("d-none");
            configuration_check_value = 1;
            $("#configuration_item_check").click(function () {
                $("#select_all_check").prop("checked", false);
                var configuration_item_check = $(
                    "#configuration_item_check"
                ).is(":checked");
                if (configuration_item_check) {
                    $(".configuration_item_child_div").removeClass("d-none");
                    configuration_item_check_value = 1;
                    $("#configuration_item_category_check").click(function () {
                        var configuration_item_category_check = $(
                            "#configuration_item_category_check"
                        ).is(":checked");
                        if (configuration_item_category_check) {
                            configuration_item_category_check_value = 1;
                        } else {
                            configuration_item_category_check_value = 0;
                        }
                    });
                    $("#configuration_item_item_check").click(function () {
                        var configuration_item_item_check = $(
                            "#configuration_item_item_check"
                        ).is(":checked");
                        if (configuration_item_item_check) {
                            configuration_item_item_check_value = 1;
                        } else {
                            configuration_item_item_check_value = 0;
                        }
                    });
                    $("#configuration_item_unit_check").click(function () {
                        var configuration_item_unit_check = $(
                            "#configuration_item_unit_check"
                        ).is(":checked");
                        if (configuration_item_unit_check) {
                            configuration_item_unit_check_value = 1;
                        } else {
                            configuration_item_unit_check_value = 0;
                        }
                    });
                    $("#configuration_item_discount_check").click(function () {
                        var configuration_item_discount_check = $(
                            "#configuration_item_discount_check"
                        ).is(":checked");
                        if (configuration_item_discount_check) {
                            configuration_item_discount_check_value = 1;
                        } else {
                            configuration_item_discount_check_value = 0;
                        }
                    });
                    $("#configuration_item_price_control_check").click(function () {
                        var configuration_item_price_control_check = $(
                            "#configuration_item_price_control_check"
                        ).is(":checked");
                        if (configuration_item_price_control_check) {
                            configuration_item_price_control_check_value = 1;
                        } else {
                            configuration_item_price_control_check_value = 0;
                        }
                    });
                } else {
                    configuration_item_check_value = 0;
                    configuration_item_category_check_value = 0;
                    configuration_item_item_check_value = 0;
                    configuration_item_unit_check_value = 0;
                    configuration_item_discount_check_value = 0;
                    configuration_item_price_control_check_value = 0;
                    $("#configuration_item_category_check").prop(
                        "checked",
                        false
                    );
                    $("#configuration_item_item_check").prop("checked", false);
                    $("#configuration_item_unit_check").prop("checked", false);
                    $("#configuration_item_discount_check").prop(
                        "checked",
                        false
                    );
                    $("#configuration_item_price_control_check").prop(
                        "checked",
                        false
                    );
                    $(".configuration_item_child_div").addClass("d-none");
                }
            });
            $("#configuration_floor_check").click(function () {
                $("#select_all_check").prop("checked", false);
                var configuration_floor_check = $(
                    "#configuration_floor_check"
                ).is(":checked");
                if (configuration_floor_check) {
                    configuration_floor_check_value = 1;
                } else {
                    configuration_floor_check_value = 0;
                }
            });
            $("#configuration_table_check").click(function () {
                $("#select_all_check").prop("checked", false);
                var configuration_table_check = $(
                    "#configuration_table_check"
                ).is(":checked");
                if (configuration_table_check) {
                    configuration_table_check_value = 1;
                } else {
                    configuration_table_check_value = 0;
                }
            });
            $("#configuration_location_check").click(function () {
                $("#select_all_check").prop("checked", false);
                var configuration_location_check = $(
                    "#configuration_location_check"
                ).is(":checked");
                if (configuration_location_check) {
                    configuration_location_check_value = 1;
                } else {
                    configuration_location_check_value = 0;
                }
            });
            $("#configuration_delivery_check").click(function () {
                $("#select_all_check").prop("checked", false);
                var configuration_delivery_check = $(
                    "#configuration_delivery_check"
                ).is(":checked");
                if (configuration_delivery_check) {
                    configuration_delivery_check_value = 1;
                } else {
                    configuration_delivery_check_value = 0;
                }
            });
        } else {
            configuration_check_value = 0;
            configuration_item_check_value = 0;
            configuration_item_category_check_value = 0;
            configuration_item_item_check_value = 0;
            configuration_item_unit_check_value = 0;
            configuration_item_discount_check_value = 0;
            configuration_item_price_control_check_value = 0;
            configuration_floor_check_value = 0;
            configuration_table_check_value = 0;
            configuration_location_check_value = 0;
            configuration_delivery_check_value = 0;
            $("#configuration_item_check").prop("checked", false);
            $("#configuration_item_category_check").prop("checked", false);
            $("#configuration_item_item_check").prop("checked", false);
            $("#configuration_item_unit_check").prop("checked", false);
            $("#configuration_item_discount_check").prop("checked", false);
            $("#configuration_item_price_control_check").prop("checked", false);
            $("#configuration_floor_check").prop("checked", false);
            $("#configuration_table_check").prop("checked", false);
            $("#configuration_location_check").prop("checked", false);
            $("#configuration_delivery_check").prop("checked", false);

            $(".configuration_item_child_div").addClass("d-none");
            $(".configuration_child_check").addClass("d-none");
        }
    });
    $("#configuration_item_check").click(function () {
        $("#select_all_check").prop("checked", false);
        var configuration_item_check = $("#configuration_item_check").is(
            ":checked"
        );
        if (configuration_item_check) {
            $(".configuration_item_child_div").removeClass("d-none");
            configuration_item_check_value = 1;
            $("#configuration_item_category_check").click(function () {
                var configuration_item_category_check = $(
                    "#configuration_item_category_check"
                ).is(":checked");
                if (configuration_item_category_check) {
                    configuration_item_category_check_value = 1;
                } else {
                    configuration_item_category_check_value = 0;
                }
            });
            $("#configuration_item_item_check").click(function () {
                var configuration_item_item_check = $(
                    "#configuration_item_item_check"
                ).is(":checked");
                if (configuration_item_item_check) {
                    configuration_item_item_check_value = 1;
                } else {
                    configuration_item_item_check_value = 0;
                }
            });
            $("#configuration_item_unit_check").click(function () {
                var configuration_item_unit_check = $(
                    "#configuration_item_unit_check"
                ).is(":checked");
                if (configuration_item_unit_check) {
                    configuration_item_unit_check_value = 1;
                } else {
                    configuration_item_unit_check_value = 0;
                }
            });
            $("#configuration_item_discount_check").click(function () {
                var configuration_item_discount_check = $(
                    "#configuration_item_discount_check"
                ).is(":checked");
                if (configuration_item_discount_check) {
                    configuration_item_discount_check_value = 1;
                } else {
                    configuration_item_discount_check_value = 0;
                }
            });
            $("#configuration_item_price_control_check").click(function () {
                var configuration_item_price_control_check = $(
                    "#configuration_item_price_control_check"
                ).is(":checked");
                if (configuration_item_price_control_check) {
                    configuration_item_price_control_check_value = 1;
                } else {
                    configuration_item_price_control_check_value = 0;
                }
            });
        } else {
            configuration_item_check_value = 0;
            configuration_item_category_check_value = 0;
            configuration_item_item_check_value = 0;
            configuration_item_unit_check_value = 0;
            configuration_item_discount_check_value = 0;
            configuration_item_price_control_check_value = 0;
            $("#configuration_item_category_check").prop("checked", false);
            $("#configuration_item_item_check").prop("checked", false);
            $("#configuration_item_unit_check").prop("checked", false);
            $("#configuration_item_discount_check").prop("checked", false);
            $("#configuration_item_price_control_check").prop("checked", false);
            $(".configuration_item_child_div").addClass("d-none");
        }
    });
    $("#configuration_item_category_check").click(function () {
        var configuration_item_category_check = $(
            "#configuration_item_category_check"
        ).is(":checked");
        if (configuration_item_category_check) {
            configuration_item_category_check_value = 1;
        } else {
            configuration_item_category_check_value = 0;
        }
    });
    $("#configuration_item_item_check").click(function () {
        var configuration_item_item_check = $(
            "#configuration_item_item_check"
        ).is(":checked");
        if (configuration_item_item_check) {
            configuration_item_item_check_value = 1;
        } else {
            configuration_item_item_check_value = 0;
        }
    });
    $("#configuration_item_unit_check").click(function () {
        var configuration_item_unit_check = $(
            "#configuration_item_unit_check"
        ).is(":checked");
        if (configuration_item_unit_check) {
            configuration_item_unit_check_value = 1;
        } else {
            configuration_item_unit_check_value = 0;
        }
    });
    $("#configuration_item_discount_check").click(function () {
        var configuration_item_discount_check = $(
            "#configuration_item_discount_check"
        ).is(":checked");
        if (configuration_item_discount_check) {
            configuration_item_discount_check_value = 1;
        } else {
            configuration_item_discount_check_value = 0;
        }
    });
    $("#configuration_floor_check").click(function () {
        $("#select_all_check").prop("checked", false);
        var configuration_floor_check = $("#configuration_floor_check").is(
            ":checked"
        );
        if (configuration_floor_check) {
            configuration_floor_check_value = 1;
        } else {
            configuration_floor_check_value = 0;
        }
    });
    $("#configuration_table_check").click(function () {
        $("#select_all_check").prop("checked", false);
        var configuration_table_check = $("#configuration_table_check").is(
            ":checked"
        );
        if (configuration_table_check) {
            configuration_table_check_value = 1;
        } else {
            configuration_table_check_value = 0;
        }
    });
    $("#configuration_location_check").click(function () {
        $("#select_all_check").prop("checked", false);
        var configuration_location_check = $(
            "#configuration_location_check"
        ).is(":checked");
        if (configuration_location_check) {
            configuration_location_check_value = 1;
        } else {
            configuration_location_check_value = 0;
        }
    });
    $("#configuration_delivery_check").click(function () {
        $("#select_all_check").prop("checked", false);
        var configuration_delivery_check = $(
            "#configuration_delivery_check"
        ).is(":checked");
        if (configuration_delivery_check) {
            configuration_delivery_check_value = 1;
        } else {
            configuration_delivery_check_value = 0;
        }
    });
    $("#reports_check").click(function () {
        $("#select_all_check").prop("checked", false);
        var reports_check = $("#reports_check").is(":checked");
        if (reports_check) {
            $(".reports_child_div").removeClass("d-none");
            reports_check_value = 1;
            $("#reports_stock_in_check").click(function () {
                $("#select_all_check").prop("checked", false);
                var reports_stock_in_check = $("#reports_stock_in_check").is(
                    ":checked"
                );
                if (reports_stock_in_check) {
                    reports_stock_in_check_value = 1;
                } else {
                    reports_stock_in_check_value = 0;
                }
            });
            $("#reports_stock_out_check").click(function () {
                $("#select_all_check").prop("checked", false);
                var reports_stock_out_check = $("#reports_stock_out_check").is(
                    ":checked"
                );
                if (reports_stock_out_check) {
                    reports_stock_out_check_value = 1;
                } else {
                    reports_stock_out_check_value = 0;
                }
            });
            $("#reports_purchase_check").click(function () {
                $("#select_all_check").prop("checked", false);
                var reports_purchase_check = $("#reports_purchase_check").is(
                    ":checked"
                );
                if (reports_purchase_check) {
                    reports_purchase_check_value = 1;
                } else {
                    reports_purchase_check_value = 0;
                }
            });
            $("#reports_sales_check").click(function () {
                $("#select_all_check").prop("checked", false);
                var reports_sales_check = $("#reports_sales_check").is(
                    ":checked"
                );
                if (reports_sales_check) {
                    reports_sales_check_value = 1;
                } else {
                    reports_sales_check_value = 0;
                }
            });
            $("#reports_top_sales_items_check").click(function () {
                $("#select_all_check").prop("checked", false);
                var reports_top_sales_items_check = $(
                    "#reports_top_sales_items_check"
                ).is(":checked");
                if (reports_top_sales_items_check) {
                    reports_top_sales_items_check_value = 1;
                } else {
                    reports_top_sales_items_check_value = 0;
                }
            });
            // $("#reports_multishop_report_check").click(function () {
            //     $("#select_all_check").prop("checked", false);
            //     var reports_multishop_report_check = $(
            //         "#reports_multishop_report_check"
            //     ).is(":checked");
            //     if (reports_multishop_report_check) {
            //         reports_multishop_report_check_value = 1;
            //     } else {
            //         reports_multishop_report_check_value = 0;
            //     }
            // });
        } else {
            reports_check_value = 0;
            reports_stock_in_check_value = 0;
            reports_stock_out_check_value = 0;
            reports_purchase_check_value = 0;
            reports_sales_check_value = 0;
            reports_top_sales_items_check_value = 0;
            // reports_multishop_report_check_value = 0;

            $("#reports_stock_in_check").prop("checked", false);
            $("#reports_stock_out_check").prop("checked", false);
            $("#reports_purchase_check").prop("checked", false);
            $("#reports_sales_check").prop("checked", false);
            $("#reports_top_sales_items_check").prop("checked", false);
            // $("#reports_multishop_report_check").prop("checked", false);
            $(".reports_child_div").addClass("d-none");
        }
    });
    $("#reports_stock_in_check").click(function () {
        $("#select_all_check").prop("checked", false);
        var reports_stock_in_check = $("#reports_stock_in_check").is(
            ":checked"
        );
        if (reports_stock_in_check) {
            reports_stock_in_check_value = 1;
        } else {
            reports_stock_in_check_value = 0;
        }
    });
    $("#reports_stock_out_check").click(function () {
        $("#select_all_check").prop("checked", false);
        var reports_stock_out_check = $("#reports_stock_out_check").is(
            ":checked"
        );
        if (reports_stock_out_check) {
            reports_stock_out_check_value = 1;
        } else {
            reports_stock_out_check_value = 0;
        }
    });
    $("#reports_purchase_check").click(function () {
        $("#select_all_check").prop("checked", false);
        var reports_purchase_check = $("#reports_purchase_check").is(
            ":checked"
        );
        if (reports_purchase_check) {
            reports_purchase_check_value = 1;
        } else {
            reports_purchase_check_value = 0;
        }
    });
    $("#reports_sales_check").click(function () {
        $("#select_all_check").prop("checked", false);
        var reports_sales_check = $("#reports_sales_check").is(":checked");
        if (reports_sales_check) {
            reports_sales_check_value = 1;
        } else {
            reports_sales_check_value = 0;
        }
    });
    $("#reports_top_sales_items_check").click(function () {
        $("#select_all_check").prop("checked", false);
        var reports_top_sales_items_check = $("#reports_top_sales_items_check").is(
            ":checked"
        );
        if (reports_top_sales_items_check) {
            reports_top_sales_items_check_value = 1;
        } else {
            reports_top_sales_items_check_value = 0;
        }
    });
    // $("#reports_multishop_report_check").click(function () {
    //     $("#select_all_check").prop("checked", false);
    //     var reports_multishop_report_check = $("#reports_multishop_report_check").is(
    //         ":checked"
    //     );
    //     if (reports_multishop_report_check) {
    //         reports_multishop_report_check_value = 1;
    //     } else {
    //         reports_multishop_report_check_value = 0;
    //     }
    // });
    $("#setting_check").click(function () {
        $("#select_all_check").prop("checked", false);
        var setting_check = $("#setting_check").is(":checked");
        if (setting_check) {
            setting_check_value = 1;
        } else {
            setting_check_value = 0;
        }
    });
    $("#form_menu_save").click(function () {
        var userRole = $("#user_role").val();

        var form_menu_permissions = [{
            role_id: userRole,
            form_menu_id: 1,
            is_used: dashboard_check_value,
        },
        {
            role_id: userRole,
            form_menu_id: 2,
            is_used: store_check_value,
        },
        {
            role_id: userRole,
            form_menu_id: 3,
            is_used: store_dine_in_check_value,
        },
        {
            role_id: userRole,
            form_menu_id: 4,
            is_used: store_sale_lists_check_value,
        },
        {
            role_id: userRole,
            form_menu_id: 5,
            is_used: store_reservation_check_value,
        },
        {
            role_id: userRole,
            form_menu_id: 53,
            is_used: store_canceled_orders_value,
        },
        {
            role_id: userRole,
            form_menu_id: 6,
            is_used: customers_check_value,
        },
        {
            role_id: userRole,
            form_menu_id: 7,
            is_used: customers_customer_check_value,
        },
        {
            role_id: userRole,
            form_menu_id: 8,
            is_used: customers_customer_type_check_value,
        },
        {
            role_id: userRole,
            form_menu_id: 9,
            is_used: stock_control_check_value,
        },
        {
            role_id: userRole,
            form_menu_id: 10,
            is_used: stock_control_stock_receive_check_value,
        },
        {
            role_id: userRole,
            form_menu_id: 11,
            is_used: stock_control_stock_receive_receive_check_value,
        },
        {
            role_id: userRole,
            form_menu_id: 12,
            is_used: stock_control_stock_receive_receive_lists_check_value,
        },
        {
            role_id: userRole,
            form_menu_id: 13,
            is_used: stock_control_stock_issue_check_value,
        },
        {
            role_id: userRole,
            form_menu_id: 14,
            is_used: stock_control_stock_issue_issue_check_value,
        },
        {
            role_id: userRole,
            form_menu_id: 15,
            is_used: stock_control_stock_issue_issue_lists_check_value,
        },
        {
            role_id: userRole,
            form_menu_id: 16,
            is_used: stock_control_issue_type_check_value,
        },
        {
            role_id: userRole,
            form_menu_id: 17,
            is_used: stock_control_stock_purchase_check_value,
        },
        {
            role_id: userRole,
            form_menu_id: 18,
            is_used: stock_control_stock_purchase_purchase_check_value,
        },
        {
            role_id: userRole,
            form_menu_id: 19,
            is_used: stock_control_stock_purchase_purchase_lists_check_value,
        },
        {
            role_id: userRole,
            form_menu_id: 20,
            is_used: card_check_value,
        },
        {
            role_id: userRole,
            form_menu_id: 21,
            is_used: card_coupon_card_check_value,
        },
        {
            role_id: userRole,
            form_menu_id: 22,
            is_used: card_member_card_check_value,
        },
        {
            role_id: userRole,
            form_menu_id: 23,
            is_used: card_member_card_card_check_value,
        },
        {
            role_id: userRole,
            form_menu_id: 24,
            is_used: card_member_card_card_type_check_value,
        },
        {
            role_id: userRole,
            form_menu_id: 25,
            is_used: users_check_value,
        },
        {
            role_id: userRole,
            form_menu_id: 26,
            is_used: users_employee_check_value,
        },
        {
            role_id: userRole,
            form_menu_id: 27,
            is_used: users_employee_employee_check_value,
        },
        {
            role_id: userRole,
            form_menu_id: 28,
            is_used: users_employee_employee_position_check_value,
        },
        {
            role_id: userRole,
            form_menu_id: 29,
            is_used: users_users_check_value,
        },
        {
            role_id: userRole,
            form_menu_id: 30,
            is_used: users_users_user_check_value,
        },
        {
            role_id: userRole,
            form_menu_id: 31,
            is_used: suppliers_check_value,
        },
        {
            role_id: userRole,
            form_menu_id: 32,
            is_used: suppliers_supplier_check_value,
        },
        {
            role_id: userRole,
            form_menu_id: 33,
            is_used: suppliers_supplier_lists_check_value,
        },
        {
            role_id: userRole,
            form_menu_id: 34,
            is_used: configuration_check_value,
        },
        {
            role_id: userRole,
            form_menu_id: 35,
            is_used: configuration_item_check_value,
        },
        {
            role_id: userRole,
            form_menu_id: 36,
            is_used: configuration_item_category_check_value,
        },
        {
            role_id: userRole,
            form_menu_id: 37,
            is_used: configuration_item_item_check_value,
        },
        {
            role_id: userRole,
            form_menu_id: 38,
            is_used: configuration_item_unit_check_value,
        },
        {
            role_id: userRole,
            form_menu_id: 39,
            is_used: configuration_item_discount_check_value,
        },
        {
            role_id: userRole,
            form_menu_id: 40,
            is_used: configuration_item_price_control_check_value,
        },
        {
            role_id: userRole,
            form_menu_id: 41,
            is_used: configuration_floor_check_value,
        },
        {
            role_id: userRole,
            form_menu_id: 42,
            is_used: configuration_table_check_value,
        },
        {
            role_id: userRole,
            form_menu_id: 43,
            is_used: configuration_location_check_value,
        },
        {
            role_id: userRole,
            form_menu_id: 44,
            is_used: configuration_delivery_check_value,
        },
        {
            role_id: userRole,
            form_menu_id: 45,
            is_used: reports_check_value,
        },
        {
            role_id: userRole,
            form_menu_id: 46,
            is_used: reports_stock_in_check_value,
        },
        {
            role_id: userRole,
            form_menu_id: 47,
            is_used: reports_stock_out_check_value,
        },
        {
            role_id: userRole,
            form_menu_id: 48,
            is_used: reports_purchase_check_value,
        },
        {
            role_id: userRole,
            form_menu_id: 49,
            is_used: reports_sales_check_value,
        },
        {
            role_id: userRole,
            form_menu_id: 50,
            is_used: reports_top_sales_items_check_value,
        },
        {
            role_id: userRole,
            form_menu_id: 51,
            is_used: setting_check_value,
        },
        {
            role_id: userRole,
            form_menu_id: 52,
            is_used: stock_control_stock_balance_check_value
        },
            // {
            //     role_id: userRole,
            //     form_menu_id: 53,
            //     is_used: reports_multishop_report_check_value
            // },

        ];
        if (userRole != 0) {
            $(".user_role_error").addClass("d-none");
            $.ajax({
                type: "GET",
                url: "setting/addUserRolePermission",
                data: {
                    form_menu_permissions: form_menu_permissions,
                    user_role_id: userRole,
                },
                success: function (data) {
                    showModal();
                },
                // Move the closing parenthesis to here
            });
        } else {
            $(".user_role_error").removeClass("d-none");
        }
    });

    function showModal() {
        var myModal = new bootstrap.Modal(
            document.getElementById("createSuccessfullyModal")
        );
        myModal.show();
    }
    $("#successOkBtn").click(function () {
        var form = document.getElementById("settingFormReload");
        form.submit();
    });

    $("#user_role").change(function () {
        var user_role = $(this).val();
        $.ajax({
            type: "GET",
            url: "setting/getUserRoleForms",
            data: { user_role: user_role },
            success: function (data) {
                // Initialize an object to store the states of each checkbox
                const formMenuStates = {};

                // Populate the formMenuStates object with the permissions from the data
                $.each(data, function (key, value) {
                    formMenuStates[value.form_menu_id] = true;
                });
                console.log(formMenuStates)

                // Define an array of all the form menu IDs and their corresponding checkbox IDs
                const formMenuCheckboxMap = [
                    { id: 1, checkboxId: "#dashboard_check" },
                    {
                        id: 2,
                        checkboxId: "#store_check",
                        childDiv: ".store_child_div",
                    },
                    { id: 3, checkboxId: "#store_dine_in_check" },
                    { id: 4, checkboxId: "#store_sale_lists_check" },
                    { id: 5, checkboxId: "#store_reservation_check" },
                    { id: 53, checkboxId: "#store_canceled_orders_check" },
                    {
                        id: 6,
                        checkboxId: "#customers_check",
                        childDiv: ".customers_child_div",
                    },
                    { id: 7, checkboxId: "#customers_customer_check" },
                    { id: 8, checkboxId: "#customers_customer_type_check" },
                    {
                        id: 9,
                        checkboxId: "#stock_control_check",
                        childDiv: ".stock_control_child_div",
                    },
                    {
                        id: 10,
                        checkboxId: "#stock_control_stock_receive_check",
                        childDiv: ".stock_control_stock_receive_child_div",
                    },
                    {
                        id: 11,
                        checkboxId: "#stock_control_stock_receive_receive_check",
                    },
                    {
                        id: 12,
                        checkboxId: "#stock_control_stock_receive_receive_lists_check",
                    },
                    {
                        id: 13,
                        checkboxId: "#stock_control_stock_issue_check",
                        childDiv: ".stock_control_stock_issue_child_div",
                    },
                    {
                        id: 14,
                        checkboxId: "#stock_control_stock_issue_issue_check",
                    },
                    {
                        id: 15,
                        checkboxId: "#stock_control_stock_issue_issue_lists_check",
                    },
                    { id: 16, checkboxId: "#stock_control_issue_type_check" },
                    {
                        id: 17,
                        checkboxId: "#stock_control_stock_purchase_check",
                        childDiv: ".stock_control_stock_purchase_child_div",
                    },
                    {
                        id: 18,
                        checkboxId: "#stock_control_stock_purchase_purchase_check",
                    },
                    {
                        id: 19,
                        checkboxId: "#stock_control_stock_purchase_purchase_lists_check",
                    },
                    {
                        id: 20,
                        checkboxId: "#card_check",
                        childDiv: ".card_child_div",
                    },
                    { id: 21, checkboxId: "#card_coupon_card_check" },
                    {
                        id: 22,
                        checkboxId: "#card_member_card_check",
                        childDiv: ".card__member_card_child_div",
                    },
                    { id: 23, checkboxId: "#card_member_card_card_check" },
                    { id: 24, checkboxId: "#card_member_card_card_type_check" },
                    {
                        id: 25,
                        checkboxId: "#users_check",
                        childDiv: ".users_child_div",
                    },
                    {
                        id: 26,
                        checkboxId: "#users_employee_check",
                        childDiv: ".users_employee_child_div",
                    },
                    { id: 27, checkboxId: "#users_employee_employee_check" },
                    {
                        id: 28,
                        checkboxId: "#users_employee_employee_position_check",
                    },
                    {
                        id: 29,
                        checkboxId: "#users_users_check",
                        childDiv: ".users_users_child_div",
                    },
                    { id: 30, checkboxId: "#users_users_user_check" },
                    {
                        id: 31,
                        checkboxId: "#suppliers_check",
                        childDiv: ".suppliers_child_div",
                    },
                    { id: 32, checkboxId: "#suppliers_supplier_check" },
                    { id: 33, checkboxId: "#suppliers_supplier_lists_check" },
                    {
                        id: 34,
                        checkboxId: "#configuration_check",
                        childDiv: ".configuration_child_check",
                    },
                    {
                        id: 35,
                        checkboxId: "#configuration_item_check",
                        childDiv: ".configuration_item_child_div",
                    },
                    {
                        id: 36,
                        checkboxId: "#configuration_item_category_check",
                    },
                    { id: 37, checkboxId: "#configuration_item_item_check" },
                    { id: 38, checkboxId: "#configuration_item_unit_check" },
                    {
                        id: 39,
                        checkboxId: "#configuration_item_discount_check",
                    },
                    {
                        id: 40,
                        checkboxId: "#configuration_item_price_control_check",
                    },
                    { id: 41, checkboxId: "#configuration_floor_check" },
                    { id: 42, checkboxId: "#configuration_table_check" },
                    { id: 43, checkboxId: "#configuration_location_check" },
                    { id: 44, checkboxId: "#configuration_delivery_check" },
                    {
                        id: 45,
                        checkboxId: "#reports_check",
                        childDiv: ".reports_child_div",
                    },
                    { id: 46, checkboxId: "#reports_stock_in_check" },
                    { id: 47, checkboxId: "#reports_stock_out_check" },
                    { id: 48, checkboxId: "#reports_purchase_check" },
                    { id: 49, checkboxId: "#reports_sales_check" },
                    { id: 50, checkboxId: "#reports_top_sales_items_check" },
                    { id: 51, checkboxId: "#setting_check" },
                    { id: 52, checkboxId: "#stock_control_stock_balance_check" },
                    // { id: 53, checkboxId: "#reports_multishop_report_check" },
                    // Add other mappings here
                ];

                // Function to toggle visibility of child divs
                function toggleChildDivs(parentCheckbox, childDiv) {
                    if (childDiv) {
                        if ($(parentCheckbox).is(":checked")) {
                            $(childDiv).removeClass("d-none");
                        } else {
                            $(childDiv).addClass("d-none");
                        }
                    }
                }

                // Function to set checkbox values and toggle child divs
                function setCheckboxState(menu, isChecked) {
                    $(menu.checkboxId).prop("checked", isChecked);

                    if (menu.checkboxId === "#dashboard_check") {
                        dashboard_check_value = isChecked ? 1 : 0;
                    }
                    if (menu.checkboxId === "#store_check") {
                        store_check_value = isChecked ? 1 : 0;
                    }
                    if (menu.checkboxId === "#store_dine_in_check") {
                        store_dine_in_check_value = isChecked ? 1 : 0;
                    }
                    if (menu.checkboxId === "#store_sale_lists_check") {
                        store_sale_lists_check_value = isChecked ? 1 : 0;
                    }
                    if (menu.checkboxId === "#store_reservation_check") {
                        store_reservation_check_value = isChecked ? 1 : 0;
                    }
                    if (menu.checkboxId === "#store_canceled_orders_check") {
                        store_canceled_orders_value = isChecked ? 1 : 0;
                    }
                    if (menu.checkboxId === "#customers_check") {
                        customers_check_value = isChecked ? 1 : 0;
                    }
                    if (menu.checkboxId === "#customers_customer_check") {
                        customers_customer_check_value = isChecked ? 1 : 0;
                    }
                    if (menu.checkboxId === "#customers_customer_type_check") {
                        customers_customer_type_check_value = isChecked ? 1 : 0;
                    }
                    if (menu.checkboxId === "#stock_control_check") {
                        stock_control_check_value = isChecked ? 1 : 0;
                    }
                    if (
                        menu.checkboxId === "#stock_control_stock_receive_check"
                    ) {
                        stock_control_stock_receive_check_value = isChecked ?
                            1 :
                            0;
                    }
                    if (
                        menu.checkboxId ===
                        "#stock_control_stock_receive_receive_check"
                    ) {
                        stock_control_stock_receive_receive_check_value =
                            isChecked ? 1 : 0;
                    }
                    if (
                        menu.checkboxId ===
                        "#stock_control_stock_receive_receive_lists_check"
                    ) {
                        stock_control_stock_receive_receive_lists_check_value =
                            isChecked ? 1 : 0;
                    }
                    if (
                        menu.checkboxId === "#stock_control_stock_issue_check"
                    ) {
                        stock_control_stock_issue_check_value = isChecked ?
                            1 :
                            0;
                    }
                    if (
                        menu.checkboxId ===
                        "#stock_control_stock_issue_issue_check"
                    ) {
                        stock_control_stock_issue_issue_check_value = isChecked ?
                            1 :
                            0;
                    }
                    if (
                        menu.checkboxId ===
                        "#stock_control_stock_issue_issue_lists_check"
                    ) {
                        stock_control_stock_issue_issue_lists_check_value =
                            isChecked ? 1 : 0;
                    }
                    if (menu.checkboxId === "#stock_control_issue_type_check") {
                        stock_control_issue_type_check_value = isChecked ?
                            1 :
                            0;
                    }
                    if (
                        menu.checkboxId ===
                        "#stock_control_stock_purchase_check"
                    ) {
                        stock_control_stock_purchase_check_value = isChecked ?
                            1 :
                            0;
                    }
                    if (
                        menu.checkboxId ===
                        "#stock_control_stock_purchase_purchase_check"
                    ) {
                        stock_control_stock_purchase_purchase_check_value =
                            isChecked ? 1 : 0;
                    }
                    if (
                        menu.checkboxId ===
                        "#stock_control_stock_purchase_purchase_lists_check"
                    ) {
                        stock_control_stock_purchase_purchase_lists_check_value =
                            isChecked ? 1 : 0;
                    }
                    if (menu.checkboxId === "#card_check") {
                        card_check_value = isChecked ? 1 : 0;
                    }
                    if (menu.checkboxId === "#card_coupon_card_check") {
                        card_coupon_card_check_value = isChecked ? 1 : 0;
                    }
                    if (menu.checkboxId === "#card_member_card_check") {
                        card_member_card_check_value = isChecked ? 1 : 0;
                    }
                    if (menu.checkboxId === "#card_member_card_card_check") {
                        card_member_card_card_check_value = isChecked ? 1 : 0;
                    }
                    if (
                        menu.checkboxId === "#card_member_card_card_type_check"
                    ) {
                        card_member_card_card_type_check_value = isChecked ?
                            1 :
                            0;
                    }
                    if (menu.checkboxId === "#users_check") {
                        users_check_value = isChecked ? 1 : 0;
                    }
                    if (menu.checkboxId === "#users_employee_check") {
                        users_employee_check_value = isChecked ? 1 : 0;
                    }
                    if (menu.checkboxId === "#users_employee_employee_check") {
                        users_employee_employee_check_value = isChecked ? 1 : 0;
                    }
                    if (
                        menu.checkboxId ===
                        "#users_employee_employee_position_check"
                    ) {
                        users_employee_employee_position_check_value = isChecked ?
                            1 :
                            0;
                    }
                    if (menu.checkboxId === "#users_users_check") {
                        users_users_check_value = isChecked ? 1 : 0;
                    }
                    if (menu.checkboxId === "#users_users_user_check") {
                        users_users_user_check_value = isChecked ? 1 : 0;
                    }
                    if (menu.checkboxId === "#suppliers_check") {
                        suppliers_check_value = isChecked ? 1 : 0;
                    }
                    if (menu.checkboxId === "#suppliers_supplier_check") {
                        suppliers_supplier_check_value = isChecked ? 1 : 0;
                    }
                    if (menu.checkboxId === "#suppliers_supplier_lists_check") {
                        suppliers_supplier_lists_check_value = isChecked ?
                            1 :
                            0;
                    }
                    if (menu.checkboxId === "#configuration_check") {
                        configuration_check_value = isChecked ? 1 : 0;
                    }
                    if (menu.checkboxId === "#configuration_item_check") {
                        configuration_item_check_value = isChecked ? 1 : 0;
                    }
                    if (
                        menu.checkboxId === "#configuration_item_category_check"
                    ) {
                        configuration_item_category_check_value = isChecked ?
                            1 :
                            0;
                    }
                    if (menu.checkboxId === "#configuration_item_item_check") {
                        configuration_item_item_check_value = isChecked ? 1 : 0;
                    }
                    if (menu.checkboxId === "#configuration_item_unit_check") {
                        configuration_item_unit_check_value = isChecked ? 1 : 0;
                    }
                    if (
                        menu.checkboxId === "#configuration_item_discount_check"
                    ) {
                        configuration_item_discount_check_value = isChecked ?
                            1 :
                            0;
                    }
                    if (
                        menu.checkboxId === "#configuration_item_price_control_check"
                    ) {
                        configuration_item_price_control_check_value = isChecked ?
                            1 :
                            0;
                    }
                    if (menu.checkboxId === "#configuration_floor_check") {
                        configuration_floor_check_value = isChecked ? 1 : 0;
                    }
                    if (menu.checkboxId === "#configuration_table_check") {
                        configuration_table_check_value = isChecked ? 1 : 0;
                    }
                    if (menu.checkboxId === "#configuration_location_check") {
                        configuration_location_check_value = isChecked ? 1 : 0;
                    }
                    if (menu.checkboxId === "#configuration_delivery_check") {
                        configuration_delivery_check_value = isChecked ? 1 : 0;
                    }
                    if (menu.checkboxId === "#reports_check") {
                        reports_check_value = isChecked ? 1 : 0;
                    }
                    if (menu.checkboxId === "#reports_stock_in_check") {
                        reports_stock_in_check_value = isChecked ? 1 : 0;
                    }
                    if (menu.checkboxId === "#reports_stock_out_check") {
                        reports_stock_out_check_value = isChecked ? 1 : 0;
                    }
                    if (menu.checkboxId === "#reports_purchase_check") {
                        reports_purchase_check_value = isChecked ? 1 : 0;
                    }
                    if (menu.checkboxId === "#reports_sales_check") {
                        reports_purchase_check_value = isChecked ? 1 : 0;
                    }
                    if (menu.checkboxId === "#setting_check") {
                        setting_check_value = isChecked ? 1 : 0;
                    }
                    if (menu.checkboxId === "#reports_top_sales_items_check") {
                        reports_top_sales_items_check_value = isChecked ? 1 : 0;
                    }
                    if (menu.checkboxId === "#stock_control_stock_balance_check") {
                        stock_control_stock_balance_check_value = isChecked ? 1 : 0;
                    }
                    // if (menu.checkboxId === "#reports_multishop_report_check") {
                    //     reports_multishop_report_check_value = isChecked ? 1 : 0;
                    // }

                    toggleChildDivs(menu.checkboxId, menu.childDiv);
                }

                // Iterate over the formMenuCheckboxMap and set the checkbox states
                $.each(formMenuCheckboxMap, function (index, menu) {
                    const isChecked = formMenuStates[menu.id] || false;
                    setCheckboxState(menu, isChecked);
                });
            },
        });
    });
});
