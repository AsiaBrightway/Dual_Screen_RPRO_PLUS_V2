@extends('layouts.admin.master')
@section('title', 'Setting')

@section('content')

<section class="home-section">
    <div class="home-title">
        <i class='bx bx-menu'></i>
        <span class="text">Setting</span>
    </div>
    <div class="home-content">
        <div class="row pb-3 align-items-center" style="color:#512DA8; font-weight:bold">
            <div class="col text-md-end text-start">
                <button class="btn btn-danger customBtn-clear" id="clear"><i class="fa-solid fa-eraser"
                        style="padding-right: 5px"></i>Clear</button>
                <button type="button" class="btn btn-primary customBtn-save ms-1 mt-0" id="form_menu_save"><i
                        class="fa-regular fa-floppy-disk" style="padding-right: 5px"></i>Save</button>
            </div>
        </div>
        <div class="user_role_permissions_container">
            <div class="row align-items-center" style="color:#512DA8; font-weight:bold">
                <div class="col ms-4 mt-3">
                    <label>Add User Role Permission</label>
                </div>
            </div>
            <div class="row mb-4 ms-3 mt-3 justify-content-center user-role-permission-container">
                <div class="col-3 setting-label">
                    <label class="col-form-label">User Role</label>
                </div>
                <div class="col-4 setting-text">
                    <select name="user_role" id="user_role" class="form-select">
                        <option value="0">Select User Role --</option>
                        @if (count($userRoles) != 0)
                        @foreach ($userRoles as $userRole)
                        <option value={{ $userRole['user_role_id'] }}>
                            {{ $userRole['user_role_name'] }}
                        </option>
                        @endforeach
                        @endif
                    </select>
                    <div class="user_role_error d-none">
                        <span style="color: red">User Role ရွေးရန်လိုအပ်ပါသည်</span>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row pb-3 align-items-center">
                <div class="col ms-4">
                    <label style="color:#512DA8; font-weight:bold">Add Form-Menus</label>
                </div>
                <div class="col me-4" style="text-align: right">
                    <input class="form-check-input" type="checkbox" id="select_all_check" name="select_all_check">
                    <label class="form-check-label ps-2 text-primary" for="select_all_check"
                        style="font-weight:bold">Select ALL</label>
                </div>
            </div>
            <div class="row ms-3 me-3 mb-3 mt-2">
                <div class="col-12 col-sm-6 col-md-4">
                    <div class="border border-1 p-3 mt-3 rounded-2">
                        <div class="form-check mx-3">
                            <input class="form-check-input" type="checkbox" id="dashboard_check" name="dashboard_check">
                            <label class="form-check-label" for="dashboard_check">Dashboard</label>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-4">
                    <div class="border border-1 p-3 mt-3 rounded-2">
                        <div class="form-check mx-3">
                            <input class="form-check-input toggle-children" type="checkbox" id="store_check"
                                name="store_check" data-target=".store_child_div">
                            <label class="form-check-label" for="store_check">My Store</label>
                        </div>
                        <div class="store_child_div d-none">
                            <div class="form-check mt-3 mb-3 ms-4">
                                <input class="form-check-input" type="checkbox" id="store_dine_in_check"
                                    name="store_dine_in_check">
                                <label class="form-check-label" for="store_dine_in_check">Dine-In</label>
                            </div>
                            <div class="form-check mb-3 ms-4">
                                <input class="form-check-input" type="checkbox" id="store_sale_lists_check"
                                    name="store_sale_lists_check">
                                <label class="form-check-label" for="store_sale_lists_check">Sale-Lists</label>
                            </div>
                            <div class="form-check mb-3 ms-4">
                                <input class="form-check-input" type="checkbox" id="store_reservation_check"
                                    name="store_reservation_check">
                                <label class="form-check-label" for="store_reservation_check">Reservation</label>
                            </div>
                            <div class="form-check mb-3 ms-4">
                                <input class="form-check-input" type="checkbox" id="store_canceled_orders_check"
                                    name="store_canceled_orders_check">
                                <label class="form-check-label" for="store_canceled_orders_check">Canceled Orders</label>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="col-12 col-sm-6 col-md-4">
                    <div class="border border-1 p-3 mt-3 rounded-2">
                        <div class="form-check mx-3">
                            <input class="form-check-input toggle-children" type="checkbox" id="customers_check"
                                name="customers_check" data-target=".customers_child_div">
                            <label class="form-check-label" for="customers_check">Customers</label>
                        </div>
                        <div class="customers_child_div d-none">
                            <div class="form-check mt-3 mb-3 ms-4">
                                <input class="form-check-input" type="checkbox" id="customers_customer_check"
                                    name="customers_customer_check">
                                <label class="form-check-label" for="customers_customer_check">Customer</label>
                            </div>
                            <div class="form-check mb-3 ms-4">
                                <input class="form-check-input" type="checkbox" id="customers_customer_type_check"
                                    name="customers_customer_type_check">
                                <label class="form-check-label" for="customers_customer_type_check">Customer
                                    Type</label>
                            </div>
                        </div>
                    </div>
                </div> --}}
                {{-- <div class="col-12 col-sm-6 col-md-4">
                    <div class="border border-1 p-3 mt-3 rounded-2">
                        <div class="form-check mx-3">
                            <input class="form-check-input" type="checkbox" id="card_check" name="card_check">
                            <label class="form-check-label" for="card_check">Card</label>
                        </div>
                        <div class="card_child_div d-none">
                            <div class="form-check mt-3 mb-3 ms-5">
                                <input class="form-check-input" type="checkbox" id="card_coupon_card_check"
                                    name="card_coupon_card_check">
                                <label class="form-check-label" for="card_coupon_card_check">Coupon Card</label>
                            </div>
                            <div class="form-check mb-3 ms-5">
                                <input class="form-check-input" type="checkbox" id="card_member_card_check"
                                    name="card_member_card_check">
                                <label class="form-check-label" for="card_member_card_check">Member Card</label>
                            </div>
                            <div class="card__member_card_child_div d-none">
                                <div class="form-check mt-3 mb-3" style="margin-left: 75px">
                                    <input class="form-check-input" type="checkbox" id="card_member_card_card_check"
                                        name="card_member_card_card_check">
                                    <label class="form-check-label" for="card_member_card_card_check">Card</label>
                                </div>
                                <div class="form-check mb-3" style="margin-left: 75px">
                                    <input class="form-check-input" type="checkbox"
                                        id="card_member_card_card_type_check" name="card_member_card_card_type_check">
                                    <label class="form-check-label" for="card_member_card_card_type_check">Card
                                        Type</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}
                <div class="col-12 col-sm-6 col-md-4">
                    <div class="border border-1 p-3 mt-3 rounded-2">
                        <div class="form-check mx-3">
                            <input class="form-check-input" type="checkbox" id="suppliers_check"
                                name="suppliers_check">
                            <label class="form-check-label" for="suppliers_check">Suppliers</label>
                        </div>
                        <div class="suppliers_child_div d-none">
                            <div class="form-check mt-3 mb-3 ms-5">
                                <input class="form-check-input" type="checkbox" id="suppliers_supplier_check"
                                    name="suppliers_supplier_check">
                                <label class="form-check-label" for="suppliers_supplier_check">Supplier</label>
                            </div>
                            <div class="form-check mb-3 ms-5">
                                <input class="form-check-input" type="checkbox" id="suppliers_supplier_lists_check"
                                    name="suppliers_supplier_lists_check">
                                <label class="form-check-label" for="suppliers_supplier_lists_check">Supplier
                                    Lists</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-4">
                    <div class="border border-1 p-3 mt-3 rounded-2">
                        <div class="form-check mx-3">
                            <input class="form-check-input" type="checkbox" id="stock_control_check"
                                name="stock_control_check">
                            <label class="form-check-label" for="stock_control_check">Stock Control</label>
                        </div>
                        <div class="stock_control_child_div d-none">
                            <div class="form-check mt-3 mb-3 ms-5">
                                <input class="form-check-input" type="checkbox"
                                    id="stock_control_stock_receive_check" name="stock_control_stock_receive_check">
                                <label class="form-check-label" for="stock_control_stock_receive_check">Stock
                                    Receive</label>
                            </div>
                            <div class="stock_control_stock_receive_child_div d-none">
                                <div class="form-check mt-3 mb-3" style="margin-left: 75px">
                                    <input class="form-check-input" type="checkbox"
                                        id="stock_control_stock_receive_receive_check"
                                        name="stock_control_stock_receive_receive_check">
                                    <label class="form-check-label"
                                        for="stock_control_stock_receive_receive_check">Receive</label>
                                </div>
                                <div class="form-check mb-3" style="margin-left: 75px">
                                    <input class="form-check-input" type="checkbox"
                                        id="stock_control_stock_receive_receive_lists_check"
                                        name="stock_control_stock_receive_receive_lists_check">
                                    <label class="form-check-label"
                                        for="stock_control_stock_receive_receive_lists_check">Receive Lists</label>
                                </div>
                            </div>
                            <div class="form-check mb-3 ms-5">
                                <input class="form-check-input" type="checkbox" id="stock_control_stock_issue_check"
                                    name="stock_control_stock_issue_check">
                                <label class="form-check-label" for="stock_control_stock_issue_check">Stock
                                    Issue</label>
                            </div>
                            <div class="stock_control_stock_issue_child_div d-none">
                                <div class="form-check mt-3 mb-3" style="margin-left: 75px">
                                    <input class="form-check-input" type="checkbox"
                                        id="stock_control_stock_issue_issue_check"
                                        name="stock_control_stock_issue_issue_check">
                                    <label class="form-check-label"
                                        for="stock_control_stock_issue_issue_check">Issue</label>
                                </div>
                                <div class="form-check mb-3" style="margin-left: 75px">
                                    <input class="form-check-input" type="checkbox"
                                        id="stock_control_stock_issue_issue_lists_check"
                                        name="stock_control_stock_issue_issue_lists_check">
                                    <label class="form-check-label"
                                        for="stock_control_stock_issue_issue_lists_check">Issue Lists</label>
                                </div>
                            </div>
                            <div class="form-check mb-3 ms-5">
                                <input class="form-check-input" type="checkbox" id="stock_control_issue_type_check"
                                    name="stock_control_issue_type_check">
                                <label class="form-check-label" for="stock_control_issue_type_check">Issue
                                    Type</label>
                            </div>
                            <div class="form-check mb-3 ms-5">
                                <input class="form-check-input" type="checkbox"
                                    id="stock_control_stock_purchase_check" name="stock_control_stock_purchase_check">
                                <label class="form-check-label" for="stock_control_stock_purchase_check">Stock
                                    Purchase</label>
                            </div>
                            <div class="stock_control_stock_purchase_child_div d-none">
                                <div class="form-check mt-3 mb-3" style="margin-left: 75px">
                                    <input class="form-check-input" type="checkbox"
                                        id="stock_control_stock_purchase_purchase_check"
                                        name="stock_control_stock_purchase_purchase_check">
                                    <label class="form-check-label"
                                        for="stock_control_stock_purchase_purchase_check">Purchase</label>
                                </div>
                                <div class="form-check mb-3" style="margin-left: 75px">
                                    <input class="form-check-input" type="checkbox"
                                        id="stock_control_stock_purchase_purchase_lists_check"
                                        name="stock_control_stock_purchase_purchase_lists_check">
                                    <label class="form-check-label"
                                        for="stock_control_stock_purchase_purchase_lists_check">Purchase Lists</label>
                                </div>
                            </div>
                            <div class="form-check mb-3 ms-5">
                                <input class="form-check-input" type="checkbox"
                                    id="stock_control_stock_balance_check" name="stock_control_stock_balance_check">
                                <label class="form-check-label" for="stock_control_stock_balance_check">
                                    Stock Balance</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-4">
                    <div class="border border-1 p-3 mt-3 rounded-2">
                        <div class="form-check mx-3">
                            <input class="form-check-input" type="checkbox" id="configuration_check"
                                name="configuration_check">
                            <label class="form-check-label" for="configuration_check">Configuration</label>
                        </div>
                        <div class="configuration_child_check d-none">
                            <div class="form-check mt-3 mb-3 ms-5">
                                <input class="form-check-input" type="checkbox" id="configuration_item_check"
                                    name="configuration_item_check">
                                <label class="form-check-label" for="configuration_item_check">Item</label>
                            </div>
                            <div class="configuration_item_child_div d-none">
                                <div class="form-check mt-3 mb-3" style="margin-left: 75px">
                                    <input class="form-check-input" type="checkbox"
                                        id="configuration_item_category_check"
                                        name="configuration_item_category_check">
                                    <label class="form-check-label"
                                        for="configuration_item_category_check">Category</label>
                                </div>
                                <div class="form-check mb-3" style="margin-left: 75px">
                                    <input class="form-check-input" type="checkbox"
                                        id="configuration_item_item_check" name="configuration_item_item_check">
                                    <label class="form-check-label" for="configuration_item_item_check">Item</label>
                                </div>
                                <div class="form-check mb-3" style="margin-left: 75px">
                                    <input class="form-check-input" type="checkbox"
                                        id="configuration_item_unit_check" name="configuration_item_unit_check">
                                    <label class="form-check-label" for="configuration_item_unit_check">Unit</label>
                                </div>
                                <div class="form-check mb-3" style="margin-left: 75px">
                                    <input class="form-check-input" type="checkbox"
                                        id="configuration_item_discount_check"
                                        name="configuration_item_discount_check">
                                    <label class="form-check-label"
                                        for="configuration_item_discount_check">Discount</label>
                                </div>
                                <div class="form-check mb-3" style="margin-left: 75px">
                                    <input class="form-check-input" type="checkbox"
                                        id="configuration_item_price_control_check"
                                        name="configuration_item_price_control_check">
                                    <label class="form-check-label" for="configuration_item_price_control_check">Price
                                        Control</label>
                                </div>
                            </div>
                            <div class="form-check mb-3 ms-5">
                                <input class="form-check-input" type="checkbox" id="configuration_floor_check"
                                    name="configuration_floor_check">
                                <label class="form-check-label" for="configuration_floor_check">Floor</label>
                            </div>
                            <div class="form-check mb-3 ms-5">
                                <input class="form-check-input" type="checkbox" id="configuration_table_check"
                                    name="configuration_table_check">
                                <label class="form-check-label" for="configuration_table_check">Table</label>
                            </div>
                            <div class="form-check mb-3 ms-5">
                                <input class="form-check-input" type="checkbox" id="configuration_location_check"
                                    name="configuration_location_check">
                                <label class="form-check-label" for="configuration_location_check">Location</label>
                            </div>
                            <div class="form-check mb-3 ms-5">
                                <input class="form-check-input" type="checkbox" id="configuration_delivery_check"
                                    name="configuration_delivery_check">
                                <label class="form-check-label" for="configuration_delivery_check">Delivery</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-4">
                    <div class="border border-1 p-3 mt-3 rounded-2">
                        <div class="form-check mx-3">
                            <input class="form-check-input" type="checkbox" id="reports_check" name="reports_check">
                            <label class="form-check-label" for="reports_check">Reports</label>
                        </div>
                        <div class="reports_child_div d-none">
                            <div class="form-check mt-3 mb-3 ms-5">
                                <input class="form-check-input" type="checkbox" id="reports_stock_in_check"
                                    name="reports_stock_in_check">
                                <label class="form-check-label" for="reports_stock_in_check">Stock-In</label>
                            </div>
                            <div class="form-check mb-3 ms-5">
                                <input class="form-check-input" type="checkbox" id="reports_stock_out_check"
                                    name="reports_stock_out_check">
                                <label class="form-check-label" for="reports_stock_out_check">Stock-Out</label>
                            </div>
                            <div class="form-check mt-3 mb-3 ms-5">
                                <input class="form-check-input" type="checkbox" id="reports_purchase_check"
                                    name="reports_purchase_check">
                                <label class="form-check-label" for="reports_purchase_check">Purchase</label>
                            </div>
                            <div class="form-check mt-3 mb-3 ms-5">
                                <input class="form-check-input" type="checkbox" id="reports_sales_check"
                                    name="reports_sales_check">
                                <label class="form-check-label" for="reports_sales_check">Sales</label>
                            </div>
                            <div class="form-check mt-3 mb-3 ms-5">
                                <input class="form-check-input" type="checkbox" id="reports_top_sales_items_check"
                                    name="reports_top_sales_items_check">
                                <label class="form-check-label" for="reports_top_sales_items_check">Top Sales Items</label>
                            </div>
                            {{-- <div class="form-check mt-3 mb-3 ms-5">
                                <input class="form-check-input" type="checkbox" id="reports_multishop_report_check"
                                    name="reports_multishop_report_check">
                                <label class="form-check-label" for="reports_multishop_report_check">MultiShop Report</label>
                            </div> --}}
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-4">
                    <div class="border border-1 p-3 mt-3 rounded-2">
                        <div class="form-check mx-3">
                            <input class="form-check-input" type="checkbox" id="users_check" name="users_check">
                            <label class="form-check-label" for="users_check">Users</label>
                        </div>
                        <div class="users_child_div d-none">
                            <div class="form-check mt-3 mb-3 ms-5">
                                <input class="form-check-input" type="checkbox" id="users_employee_check"
                                    name="users_employee_check">
                                <label class="form-check-label" for="users_employee_check">Employee</label>
                            </div>
                            <div class="users_employee_child_div d-none">
                                <div class="form-check mt-3 mb-3" style="margin-left: 75px">
                                    <input class="form-check-input" type="checkbox"
                                        id="users_employee_employee_check" name="users_employee_employee_check">
                                    <label class="form-check-label"
                                        for="users_employee_employee_check">Employee</label>
                                </div>
                                <div class="form-check mb-3" style="margin-left: 75px">
                                    <input class="form-check-input" type="checkbox"
                                        id="users_employee_employee_position_check"
                                        name="users_employee_employee_position_check">
                                    <label class="form-check-label"
                                        for="users_employee_employee_position_check">Employee Position</label>
                                </div>
                            </div>
                            <div class="form-check mb-3 ms-5">
                                <input class="form-check-input" type="checkbox" id="users_users_check"
                                    name="users_users_check">
                                <label class="form-check-label" for="users_users_check">Users</label>
                            </div>
                            <div class="users_users_child_div d-none">
                                <div class="form-check mt-3 mb-3" style="margin-left: 75px">
                                    <input class="form-check-input" type="checkbox" id="users_users_user_check"
                                        name="users_users_user_check">
                                    <label class="form-check-label" for="users_users_user_check">User</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-4">
                    <div class="border border-1 p-3 mt-3 rounded-2">
                        <div class="form-check mx-3">
                            <input class="form-check-input" type="checkbox" id="setting_check" name="setting_check">
                            <label class="form-check-label" for="setting_check">Setting</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="createSuccessfullyModal" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Creation</h1>
                    {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> --}}
                </div>
                <div class="modal-body">
                    User Role Permission Creation Successfully!
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="successOkBtn">Ok</button>
                </div>
            </div>
        </div>
    </div>
    <form id="settingFormReload" method="GET" action="{{ route('setting#settingPage') }}">
        @csrf
    </form>
</section>
<script src="{{ asset('script/links_js/jquery.3.6.4.min.js') }}"></script>
<script src="{{ asset('script/links_js/jquery.validate.1.19.5.js') }}"></script>
<script src="{{ asset('script/links_js/jquery.dataTables.1.13.7.min.js') }}"></script>
<script src="{{ asset('script/links_js/dataTables.bootstrap5_1.13.7.min.js') }}"></script>
<script src="{{ asset('script/setting_script.js') }}"></script>
@endsection