<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FloorController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PrintController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\DineInController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\MenuItemController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TownshipController;
use App\Http\Controllers\UserRoleController;
use App\Http\Controllers\MemberCardController;
use App\Http\Controllers\StockIssueController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\CustomerTypeController;
use App\Http\Controllers\ItemDiscountController;
use App\Http\Controllers\MenuCategoryController;
use App\Http\Controllers\MainCategoryController;
use App\Http\Controllers\StockReceiveController;
use App\Http\Controllers\MemberCardTypeController;
use App\Http\Controllers\StockIssueTypeController;
use App\Http\Controllers\EmployeePositionController;
use App\Http\Controllers\ItemSellingPriceController;
use App\Http\Controllers\MenuCatalogueController;
use App\Models\ItemSellingPrice;
use Mpdf\Mpdf;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;

Route::get('/test-unicode-pdf', function () {

    $defaultConfig = (new ConfigVariables())->getDefaults();
    $fontDirs = $defaultConfig['fontDir'];

    $defaultFontConfig = (new FontVariables())->getDefaults();
    $fontData = $defaultFontConfig['fontdata'];
    $mpdf = new Mpdf([
        'mode' => 'utf-8',
        'default_font' => 'dejavusans', // Use Latin font by default
        'fontDir' => array_merge($fontDirs, [storage_path('fonts')]),
        'fontdata' => $fontData + [
            'notosansmyanmar' => [
                'R'  => 'NotoSansMyanmar-Regular.ttf',
                'B'  => 'NotoSansMyanmar-Bold.ttf',
            ],
            'dejavusans' => [
                'R'  => 'dejavu-sans.book.ttf',
                'B'  => 'dejavu-sans.bold.ttf',
            ],
        ],
    ]);

    $html = '<span style="font-family: notosansmyanmar;">မင်္ဂလာပါ</span> Hello World';
    $mpdf->WriteHTML($html);
    $mpdf->Output();
});


//Login
Route::redirect('/', 'loginPage');
Route::get('loginPage', [AuthController::class, 'loginPage'])->name('auth#loginPage');

Route::post('homePage', [AuthController::class, 'homePage'])->name('auth#homePage');


//Menu_catalogue
Route::get(
    'menu',
    [MenuCatalogueController::class, 'menuCataloguePage']
)->name('catalogue#menuCataloguePage');

Route::get('searchKey/getItem', [MenuCatalogueController::class, 'getItemBySearchKey']);
Route::get('subCategory/getItem', [MenuCatalogueController::class, 'getItemBySubCategory']);
//revmoe verified route group
// Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
Route::middleware(['auth:sanctum', config('jetstream.auth_session')])->group(function () {
    //Dashboard
    Route::get('dashboardPage', [AuthController::class, 'dashboardPage'])->name('auth#dashboardPage');

    Route::view('homepage', 'abw.company_info')->name('companyInfoPage');

    Route::group(['prefix' => 'admin', 'middleware' => 'admin_auth'], function () {

        Route::get('printVoucher', [VoucherController::class, 'printVoucher']);

        //Store -> Dine_in
        Route::get('dineInPage', [DineInController::class, 'dineInPage'])->name('store#dineInPage');

        //Store -> Order
        Route::post('orderPage', [OrderController::class, 'orderPage'])->name('store#orderPage');

        //Store -> Reservation
        Route::get('reservationPage', [ReservationController::class, 'reservationPage'])->name('store#reservationPage');
        Route::get('table/getReservation', [ReservationController::class, 'getReservationByTableID']);
        Route::get('table/getTableByDate', [ReservationController::class, 'getTableByDate']);
        Route::post('reservation/create', [ReservationController::class, 'createReservation'])->name('reservation#create');
        Route::get('reservation/delete', [ReservationController::class, 'deleteReservationByTableID']);

        //Customer->customer/customer_type
        Route::get('customerPage', [CustomerController::class, 'customerPage'])->name('customer#customerPage');
        Route::get('customerTypePage', [CustomerTypeController::class, 'customerTypePage'])->name('customer#customerTypePage');

        //Item Name validation
        Route::get('item/checkUniqueItemName', [MenuItemController::class, 'checkUniqueItemName']);

        //Item Code validation
        Route::get('item/checkUniqueItemCode', [MenuItemController::class, 'checkUniqueItemCode']);

        //Item Code validation
        Route::get('item/checkUniqueBarCode', [MenuItemController::class, 'checkUniqueBarCode']);

        //Item Code next number
        Route::get('item/getNextItemCodeNumber', [MenuItemController::class, 'getNextItemCodeNumber']);

        //Stock Control->stock_receive->receive/receive_list
        Route::post('receive/itemCreate', [StockReceiveController::class, 'createReceiveItem'])->name('receive#itemCreate');
        Route::post('purchase/itemCreate', [PurchaseController::class, 'createPurchaseItem'])->name('purchase#itemCreate');

        Route::get('receivePage', [StockReceiveController::class, 'receivePage'])->name('stockControl#stock_receive#receivePage');
        Route::get('receiveListPage', [StockReceiveController::class, 'receiveListPage'])->name('stockControl#stock_receive#receiveListPage');
        Route::get('receiveDetailsPage/{id}', [StockReceiveController::class, 'receiveDetailsPage'])->name('stockControl#stock_receive#receiveDetailsPage');
        Route::get('createStockReceive', [StockReceiveController::class, 'createStockReceive']);
        // Route::get('delete/{id}', [StoreReceiveController::class, 'delete'])->name('stockControl#stock_receive#receiveListPage#delete');
        Route::post('deleteStoreReceive', [StockReceiveController::class, 'deleteStoreReceive'])->name('stockControl#stock_receive#receiveListPage#deleteStoreReceive');
        Route::get('updateStockReceivePage/{id}', [StockReceiveController::class, 'updateStockReceivePage'])->name('stockControl#stock_receive#updateStockReceivePage');
        Route::get('updateStockReceive', [StockReceiveController::class, 'updateStockReceive'])->name('stockControl#stock_receive#updateStockReceive');
        Route::get('checkCancelReason', [PurchaseController::class, 'checkCancelReason']);

        //Stock Control->stock_issue->issue/issue_list
        Route::get('issuePage', [StockIssueController::class, 'issuePage'])->name('stockControl#stock_issue#issuePage');
        Route::get('issueListPage', [StockIssueController::class, 'issueListPage'])->name('stockControl#stock_issue#issueListPage');
        Route::get('issueDetailsPage/{id}', [StockIssueController::class, 'issueDetailsPage'])->name('stockControl#stock_issue#issueDetailsPage');
        Route::get('checkStoreQty', [StockIssueController::class, 'checkStoreQty'])->name('stockControl#stock_issue#checkStoreQty');;
        Route::get('checkIssueDetailValidation', [StockIssueController::class, 'checkIssueDetailValidation'])->name('stockControl#stock_issue#checkIssueDetailValidation');;;
        Route::get('createStockIssue', [StockIssueController::class, 'createStockIssue']);
        Route::post('deleteStoreIssue', [StockIssueController::class, 'deleteStoreIssue'])->name('stockControl#stock_issue#issueListPage#deleteStoreIssue');
        Route::get('updateStockIssuePage/{id}', [StockIssueController::class, 'updateStockIssuePage'])->name('stockControl#stock_issue#updateStockIssuePage');
        Route::get('updateStockIssue', [StockIssueController::class, 'updateStockIssue'])->name('stockControl#stock_issue#updateStockIssue');
        Route::get('checkCancelReason', [StockIssueController::class, 'checkCancelReason']);

        //Stock Control->Issue Type
        Route::get('issueTypePage', [StockIssueTypeController::class, 'issueTypePage'])->name('stockControl#issue_type');
        Route::post('createStockIssueType', [StockIssueTypeController::class, 'createStockIssueType'])->name('stockControl#issue_type#createStockIssueType');
        Route::get('updateStockIssueTypeModal', [StockIssueTypeController::class, 'updateStockIssueTypeModal'])->name('stockControl#issue_type#updateStockIssueTypeModal');
        Route::post('updateStockIssueType', [StockIssueTypeController::class, 'updateStockIssueType'])->name('stockControl#issue_type#updateStockIssueType');

        //Stock Control->stock_purchase->purchase/purchase_list
        Route::get('purcahsePage', [PurchaseController::class, 'purchasePage'])->name('stockControl#stock_purchase#purchasePage');
        Route::get('purcahseListPage', [PurchaseController::class, 'purchaseListPage'])->name('stockControl#stock_purchase#purchaseListPage');
        Route::get('checkPurchaseDetailValidation', [PurchaseController::class, 'checkPurchaseDetailValidation']);
        Route::get('savePurchase', [PurchaseController::class, 'savePurchase']);
        Route::get('updatePurchasePage/{id}', [PurchaseController::class, 'updatePurchasePage'])->name('stockControl#stock_purchase#updatePurchasePage');
        Route::get('updateCheckPurchaseDetailValidation', [PurchaseController::class, 'updateCheckPurchaseDetailValidation'])->name('stockControl#stock_purchase#updateCheckPurchaseDetailValidation');
        Route::get('updatePurchase', [PurchaseController::class, 'updatePurchase'])->name('stockControl#stock_purchase#updatePurchase');
        Route::post('deleteSelectedPurchase', [PurchaseController::class, 'deleteSelectedPurchase'])->name('stockControl#stock_purchase#purchaseListPage#deleteSelectedPurchase');
        Route::get('GetPaidLog', [PurchaseController::class, 'GetPaidLog'])->name('stockControl#stock_purchase#purchaseListPage#GetPaidLog');
        Route::get('createPaymentLog', [PurchaseController::class, 'createPaymentLog'])->name('stockControl#stock_purchase#purchaseListPage#createPaymentLog');
        Route::get('GetPaidEditLog', [PurchaseController::class, 'GetPaidEditLog'])->name('stockControl#stock_purchase#purchaseListPage#GetPaidEditLog');
        Route::get('editPaymentLog', [PurchaseController::class, 'editPaymentLog'])->name('stockControl#stock_purchase#purchaseListPage#editPaymentLog');
        Route::get('deletePaidLog', [PurchaseController::class, 'deletePaidLog'])->name('stockControl#stock_purchase#purchaseListPage#deletePaidLog');
        Route::get('purchase/purchaseOrderDetails/{purchase_id}', [PurchaseController::class, 'purchaseOrderDetails'])->name('purchase#purchaseOrderDetails');


        //Card->coupon_card
        Route::get('couponCardPage', [CouponController::class, 'couponCardPage'])->name('card#couponCardPage');

        //Card->member_card->card/card_type
        Route::get('memberCardPage', [MemberCardController::class, 'memberCardPage'])->name('card#memberCard#memberCardPage');
        Route::get('memberCardTypePage', [MemberCardTypeController::class, 'memberCardTypePage'])->name('card#memberCard#memberCardTypePage');

        //User->employee->employee/employee_position
        Route::get('employeePage', [EmployeeController::class, 'employeePage'])->name('user#employee#employeePage');
        Route::get('employeePositionPage', [EmployeePositionController::class, 'employeePositionPage'])->name('user#employee#employeePositionPage');

        //User->users->user/user_role
        Route::get('userPage', [UserController::class, 'userPage'])->name('user#users#userPage');
        Route::get('userRolePage', [UserRoleController::class, 'userRolePage'])->name('user#users#userRolePage');

        //Supplier->supplier/supplier_list
        Route::get('supplierPage', [SupplierController::class, 'supplierPage'])->name('supplier#supplierPage');
        Route::get('supplierListPage', [SupplierController::class, 'supplierListPage'])->name('supplier#supplierListPage');

        //Config->item->category/item/unit/discount/Price control
        Route::get('itemPage', [MenuItemController::class, 'itemPage'])->name('config#item#itemPage');
        Route::get('unitPage', [UnitController::class, 'unitPage'])->name('config#item#unitPage');
        Route::get('discountPage', [ItemDiscountController::class, 'discountPage'])->name('config#item#discountPage');
        Route::get('priceControlPage', [ItemSellingPriceController::class, 'priceControlPage'])->name('config#item#priceControlPage');

        //Config->floor/table/location/delivery
        Route::get('floorPage', [FloorController::class, 'floorPage'])->name('config#floorPage');
        Route::get('tablePage', [TableController::class, 'tablePage'])->name('config#tablePage');
        Route::get('locationPage', [LocationController::class, 'locationPage'])->name('config#locationPage');
        Route::get('deliveryPage', [DeliveryController::class, 'deliveryPage'])->name('config#deliveryPage');

        //Bar Category Create/ Update/ Delete
        Route::post('barCategory/create', [MenuCategoryController::class, 'createBarCategory'])->name('barCategory#create');
        Route::post('barCategory/update', [MenuCategoryController::class, 'updateBarCategory'])->name('barCategory#update');
        Route::post('barCategory/delete', [MenuCategoryController::class, 'deleteBarCategory'])->name('barCategory#delete');

        //Kitchen Category Create/ Update/ Delete
        Route::post('kitchenCategory/create', [MenuCategoryController::class, 'createKitchenCategory'])->name('kitchenCategory#create');
        Route::post('kitchenCategory/update', [MenuCategoryController::class, 'updateKitchenCategory'])->name('kitchenCategory#update');
        Route::post('kitchenCategory/delete', [MenuCategoryController::class, 'deleteKitchenCategory'])->name('kitchenCategory#delete');

        //Refrigerator Category Create/ Update/ Delete
        Route::post('refrigeratorCategory/create', [MenuCategoryController::class, 'createRefrigeratorCategory'])->name('refrigeratorCategory#create');
        Route::post('refrigeratorCategory/update', [MenuCategoryController::class, 'updateRefrigeratorCategory'])->name('refrigeratorCategory#update');
        Route::post('refrigeratorCategory/delete', [MenuCategoryController::class, 'deleteRefrigeratorCategory'])->name('refrigeratorCategory#delete');

        // Main Category Create / Update / Delete
        Route::get('mainCategoryPage', [MainCategoryController::class, 'index'])
            ->name('mainCategoryPage');
        Route::post('mainCategory/create', [MainCategoryController::class, 'store'])
            ->name('mainCategory#create');
        Route::post('/main-category/update', [MainCategoryController::class, 'update'])
            ->name('mainCategory#update');
        Route::post('/main-category/delete', [MainCategoryController::class, 'delete'])
            ->name('mainCategory#delete');


        // Menu Category Create / Update / Delete
        Route::get('menuCategoryPage', [MenuCategoryController::class, 'categoryPage'])->name('menuCategoryPage');
        Route::post('/menuCategory/create', [MenuCategoryController::class, 'createMenuCategory'])
            ->name('menuCategory#create');
        Route::post('/menuCategory/update', [MenuCategoryController::class, 'updateMenuCategory'])
            ->name('menuCategory#update');
        Route::post('/menu-category/delete', [MenuCategoryController::class, 'deleteMenuCategory'])
            ->name('menuCategory#delete');





        //Service Category Create/ Update/ Delete
        Route::post('serviceCategory/create', [MenuCategoryController::class, 'createServiceCategory'])->name('serviceCategory#create');
        Route::post('serviceCategory/update', [MenuCategoryController::class, 'updateServiceCategory'])->name('serviceCategory#update');
        Route::post('serviceCategory/delete', [MenuCategoryController::class, 'deleteServiceCategory'])->name('serviceCategory#delete');

        //noodle Category Create/ Update/ Delete
        Route::post('noodleCategory/create', [MenuCategoryController::class, 'createnoodleCategory'])->name('noodleCategory#create');
        Route::post('noodleCategory/update', [MenuCategoryController::class, 'updatenoodleCategory'])->name('noodleCategory#update');
        Route::post('noodleCategory/delete', [MenuCategoryController::class, 'deletenoodleCategory'])->name('noodleCategory#delete');

        //cuisine Category Create/ Update/ Delete
        Route::post('cuisineCategory/create', [MenuCategoryController::class, 'createcuisineCategory'])->name('cuisineCategory#create');
        Route::post('cuisineCategory/update', [MenuCategoryController::class, 'updatecuisineCategory'])->name('cuisineCategory#update');
        Route::post('cuisineCategory/delete', [MenuCategoryController::class, 'deletecuisineCategory'])->name('cuisineCategory#delete');

        //Item->dropdown
        Route::get('item/item', [MenuItemController::class, 'getSubCategoryByMainCategory']);
        Route::get('item/itemDetails', [MenuItemController::class, 'getItemDetailsByItem']);

        //Item Create/ Update/ Delete
        Route::post('item/create', [MenuItemController::class, 'createItem'])->name('item#create');
        Route::post('item/update', [MenuItemController::class, 'updateItem'])->name('item#update');
        Route::post('item/delete', [MenuItemController::class, 'deleteItem'])->name('item#delete');

        //Unit Create/ Update/ Delete
        Route::post('unit/create', [UnitController::class, 'createUnit'])->name('unit#create');
        Route::post('unit/update', [UnitController::class, 'updateUnit'])->name('unit#update');
        Route::post('unit/delete', [UnitController::class, 'deleteUnit'])->name('unit#delete');

        //Discount/Get Dropdown data
        Route::get('item/getSubCategory', [ItemDiscountController::class, 'getSubCategoryByMainCategory']);
        Route::get('item/getItem', [ItemDiscountController::class, 'getItemByMainCategoryAndSubCategory']);
        Route::get('item/getItemPrice', [ItemDiscountController::class, 'getItemPriceByItemID']);

        //Discount Create/ Update /Delete
        Route::post('discount/create', [ItemDiscountController::class, 'createDiscount'])->name('discount#create');
        Route::get('discount/updatePage/{item_discount_id}', [ItemDiscountController::class, 'discountUpdatePage'])->name('discount#updatePage');
        Route::post('discount/update', [ItemDiscountController::class, 'updateDiscount'])->name('discount#update');
        Route::post('discount/delete', [ItemDiscountController::class, 'deleteDiscount'])->name('discount#delete');

        //Price Control Create/ Update / Delete


        //Floor Create/ Update/ Delete
        Route::post('floor/create', [FloorController::class, 'createFloor'])->name('floor#create');
        Route::post('floor/update', [FloorController::class, 'updateFloor'])->name('floor#update');
        Route::post('floor/delete', [FloorController::class, 'deleteFloor'])->name('floor#delete');

        //Table Create/ Update/ Delete
        Route::post('table/create', [TableController::class, 'createTable'])->name('table#create');
        Route::post('table/update', [TableController::class, 'updateTable'])->name('table#update');
        Route::post('table/delete', [TableController::class, 'deleteTable'])->name('table#delete');

        //City Create/ Update/ Delete
        Route::post('city/create', [CityController::class, 'createCity'])->name('city#create');
        Route::post('city/update', [CityController::class, 'updateCity'])->name('city#update');
        Route::post('city/delete', [CityController::class, 'deleteCity'])->name('city#delete');
        Route::get('city/checkCityName', [CityController::class, 'checkCityName'])->name('checkCityName');


        //Township Create/ Update/ Delete
        Route::post('township/create', [TownshipController::class, 'createTownship'])->name('township#create');
        Route::post('township/update', [TownshipController::class, 'updateTownship'])->name('township#update');
        Route::post('township/delete', [TownshipController::class, 'deleteTownship'])->name('township#delete');
        Route::get('township/checkTownshipName', [TownshipController::class, 'checkTownshipName'])->name('checkTownshipName');

        //City->dropdown
        Route::get('city/getTownship', [DeliveryController::class, 'getTownshipByCity']);

        //Delivery Create/ Update/ Delete
        Route::post('delivery/create', [DeliveryController::class, 'createDelivery'])->name('delivery#create');
        Route::post('delivery/update', [DeliveryController::class, 'updateDelivery'])->name('delivery#update');
        Route::post('delivery/delete', [DeliveryController::class, 'deleteDelivery'])->name('delivery#delete');

        //Customer Create/ Update/ Delete
        Route::post('customer/create', [CustomerController::class, 'createCutomer'])->name('customer#create');
        Route::get('customer/updatePage/{customer_id}', [CustomerController::class, 'customerUpdatePage'])->name('customer#updatePage');
        Route::post('customer/update', [CustomerController::class, 'updateCutomer'])->name('customer#update');
        Route::post('customer/delete', [CustomerController::class, 'deleteCutomer'])->name('customer#delete');

        //Customer Type Create/ Update/ Delete
        Route::post('customerType/create', [CustomerTypeController::class, 'createCutomerType'])->name('customerType#create');
        Route::post('customerType/update', [CustomerTypeController::class, 'updateCutomerType'])->name('customerType#update');
        Route::post('customerType/delete', [CustomerTypeController::class, 'deleteCutomerType'])->name('customerType#delete');

        //Selling Price Create / Update / Delete
        Route::post('priceControl/create', [ItemSellingPriceController::class, 'createPriceControl'])->name('priceControl#create');
        Route::post('priceControl/update', [ItemSellingPriceController::class, 'updatePriceControl'])->name('priceControl#update');

        //Coupon Card Create/ Update/ Delete
        Route::post('couponCard/create', [CouponController::class, 'createCouponCard'])->name('couponCard#create');
        Route::post('couponCard/update', [CouponController::class, 'updateCouponCard'])->name('couponCard#update');
        Route::post('couponCard/delete', [CouponController::class, 'deleteCouponCard'])->name('couponCard#delete');

        //MemberCard Type->dropdown
        Route::get('memberCardType/memberCardType', [MemberCardController::class, 'getMemberCardTypeByMemberCardTypeID']);

        //Member Card Create/ Update/ Delete
        Route::post('memberCard/create', [MemberCardController::class, 'createMemberCard'])->name('memberCard#create');
        Route::post('memberCard/update', [MemberCardController::class, 'updateMemberCard'])->name('memberCard#update');
        Route::post('memberCard/delete', [MemberCardController::class, 'deleteMemberCard'])->name('memberCard#delete');

        //Member Card Type Create/ Update/ Delete
        Route::post('memberCardType/create', [MemberCardTypeController::class, 'createMemberCardType'])->name('memberCardType#create');
        Route::post('memberCardType/update', [MemberCardTypeController::class, 'updateMemberCardType'])->name('memberCardType#update');
        Route::post('memberCardType/delete', [MemberCardTypeController::class, 'deleteMemberCardType'])->name('memberCardType#delete');


        //Employees Create/ Update/ Delete
        Route::post('employee/create', [EmployeeController::class, 'createEmployee'])->name('employee#create');
        Route::post('employee/update', [EmployeeController::class, 'updateEmployee'])->name('employee#update');
        Route::post('employee/delete', [EmployeeController::class, 'deleteEmployee'])->name('employee#delete');

        //Employees Position Create/ Update/ Delete
        Route::post('employeePosition/create', [EmployeePositionController::class, 'createEmployeePosition'])->name('employeePosition#create');
        Route::post('employeePosition/update', [EmployeePositionController::class, 'updateEmployeePosition'])->name('employeePosition#update');
        Route::post('employeePosition/delete', [EmployeePositionController::class, 'deleteEmployeePosition'])->name('employeePosition#delete');

        //User Create/ Update/ Delete
        Route::get('user/getEmployeeCode', [UserController::class, 'getEmpoyeeCodeByEmployeeID']);
        Route::post('user/create', [UserController::class, 'createUser'])->name('user#create');
        Route::post('user/update', [UserController::class, 'updateUser'])->name('user#update');
        Route::post('user/delete', [UserController::class, 'deleteUser'])->name('user#delete');

        //User Role Create/ Update/ Delete
        Route::post('userRole/create', [UserRoleController::class, 'createUserRole'])->name('userRole#create');
        Route::post('userRole/update', [UserRoleController::class, 'updateUserRole'])->name('userRole#update');
        Route::post('userRole/delete', [UserRoleController::class, 'deleteUserRole'])->name('userRole#delete');

        //Supplier Create/ Update/ Delete
        Route::post('supplier/create', [SupplierController::class, 'createSupplier'])->name('supplier#create');
        Route::post('supplier/update', [SupplierController::class, 'updateSupplier'])->name('supplier#update');
        Route::post('supplier/delete', [SupplierController::class, 'deleteSupplier'])->name('supplier#delete');

        Route::post('table/merge', [DineInController::class, 'tableMerge'])->name('table#merge');

        Route::get('floor/getTable', [DineInController::class, 'getTableByFloorID']);
        Route::get('floor/getTableOnly', [DineInController::class, 'getTableByFloorIDOnly']);
        Route::get('table/getOrder', [DineInController::class, 'getOrderByTableIDAndOrderNumber']);
        Route::get('table/getOrderSummary', [DineInController::class, 'getOrderSummaryByTableID']);
        Route::get('dineIn/updateOrderItem', [DineInController::class, 'updateOrderItem']);
        Route::get('dineIn/updateOrderItemQty', [DineInController::class, 'updateOrderItemQty']);
        Route::get('dineIn/deleteOrderItem', [DineInController::class, 'deleteOrderItem']);

        Route::get('mainCategory/getSubCategory', [OrderController::class, 'getSubCategoryByMainCategory']);
        Route::get('subCategory/getItem', [OrderController::class, 'getItemBySubCategory']);
        Route::get('mainCategory/getItem', [OrderController::class, 'getItemByMainCategory']);
        Route::post('order/addOrderItem', [OrderController::class, 'addOrderItem']);
        Route::get('searchKey/getItem', [OrderController::class, 'getItemBySearchKey']);
        Route::get('order/deleteOrderItem', [OrderController::class, 'deleteOrderItem']);
        Route::get('order/updateOrderItem', [OrderController::class, 'updateOrderItem']);

        Route::get('saleListPage', [SalesController::class, 'saleListPage'])->name('sale#saleListPage');
        Route::post('sale/orderInvoice', [SalesController::class, 'orderInvoice'])->name('sale#orderInvoice');
        Route::get('sale/saleOrderDetails/{sale_id}', [SalesController::class, 'saleOrderDetails'])->name('sale#saleOrderDetails');
        Route::get('sale/getMemberCardByMemberCardCode', [SalesController::class, 'getMemberCardByMemberCardCode']);
        Route::get('sale/getCouponCardByCouponCardCode', [SalesController::class, 'getCouponCardByCouponCardCode']);
        Route::post('sale/checkOut', [SalesController::class, 'checkOut'])->name('sale#checkOut');
        Route::get('sale/prePrint', [SalesController::class, 'prePrint'])->name('sale#prePrint');
        Route::get('sale/dailyPrint', [SalesController::class, 'dailyPrint'])->name('dailyPrint');
        Route::post('sale/delete', [SalesController::class, 'deleteSale'])->name('sale#delete');

        //Reports->Stock_In/Stock_Out

        Route::get('reports/stockInReportByFilter', [ReportsController::class, 'stockInReportByFilter'])->name('reports#stockInReportByFilter');
        Route::get('reports/stock_out', [ReportsController::class, 'stockOutReport'])->name('reports#stock_out');
        Route::get('reports/stockOutReportByFilter', [ReportsController::class, 'stockOutReportByFilter'])->name('reports#stockOutReportByFilter');
        Route::get('reports/purchase', [ReportsController::class, 'purchaseReport'])->name('reports#purchase');
        Route::get('reports/purchaseReportByFilter', [ReportsController::class, 'purchaseReportByFilter'])->name('reports#purchaseReportByFilter');
        Route::get('reports/sales', [ReportsController::class, 'salesReport'])->name('reports#sales');
        Route::get('reports/top-sale', [ReportsController::class, 'topSaleReport'])->name('reports#topSales');
        // New Balance Report
        Route::get('reports/balance', [ReportsController::class, 'balanceReport'])->name('reports#balance');
        Route::get(
            '/reports/balance/export-pdf',
            [ReportsController::class, 'exportBalancePdf']
        )->name('reports.balance.export.pdf');
        // Route::get('reports/modifyBalanceReport', [ReportsController::class, 'modifyBalanceReport']);
        Route::get('users/saleExport', [ReportsController::class, 'excelSaleExport'])->name('sales.export.excel');
        // Route::get('users/searchExport', [ReportsController::class, 'searchExport'])->name('searchExport');

        //Reports->Binding select'
        Route::get('reports/stock_in', [ReportsController::class, 'stockInReport'])->name('reports#stock_in');
        Route::get('reports/bindingMenuCategory', [ReportsController::class, 'bindingMenuCategory']);
        Route::get('reports/bindingStockItem', [ReportsController::class, 'bindingStockItem']);
        Route::get('reports/bindingStockIssue', [ReportsController::class, 'bindingStockIssue']);
        Route::get('reports/bindingSupplier', [ReportsController::class, 'bindingSupplier']);
        Route::get('reports/bindingEmployee', [ReportsController::class, 'bindingEmployee']);

        Route::get('reports/stockInReportBySearch', [ReportsController::class, 'stockInReportBySearch']);
        Route::get('reports/stockOutReportBySearch', [ReportsController::class, 'stockOutReportBySearch']);
        Route::get('reports/purchaseReportBySearch', [ReportsController::class, 'purchaseReportBySearch']);
        // Route::get('reports/salesReportBySearch', [ReportsController::class, 'salesReportBySearch']);
        Route::get('reports/salesReportBySearch', [ReportsController::class, 'modifySaleReportBySearch']);
        Route::get('reports/topSalesReportBySearch', [ReportsController::class, 'modifyTopSaleReportBySearch']);
        Route::get('reports/render-top-sale-search', [ReportsController::class, 'renderTopSaleSearch'])
            ->name('render.top.sale.search');
        Route::get('reports/shopsReport', [ReportsController::class, 'shopsReport'])->name('reports#shopsReport');

        Route::get('/sales-report/sale-export-pdf', [ReportsController::class, 'exportSalePdf'])->name('sales.export.pdf');
        Route::get('users/purchaseExport', [ReportsController::class, 'excelPurchaseExport'])->name('purchase.export.excel');
        Route::get('/purchase-report/purchase-export-pdf', [ReportsController::class, 'exportPurchasePdf'])->name('purchase.export.pdf');



        Route::get('settingPage', [SettingController::class, 'settingPage'])->name('setting#settingPage');
        Route::get('setting/addUserRolePermission', [SettingController::class, 'addUserRolePermission']);
        Route::get('setting/getUserRoleForms', [SettingController::class, 'getUserRoleForms']);

        //Print
        Route::get('prints/saleOrderPrint/{sale_id}', [PrintController::class, 'saleOrderPrint'])->name('prints#saleOrderPrint');
        Route::get('prints/preOrderPrint/{order_id}/{invoiceNumber}', [PrintController::class, 'preOrderPrint'])->name('prints#preOrderPrint');
        Route::get('prints/checkOutPrint/{table_id}/{table_order_number}', [PrintController::class, 'checkOutPrint'])->name('prints#checkOutPrint');
        Route::get('prints/orderPrint/{orderID}/{filteredOrderItems}', [PrintController::class, 'orderPrint'])->name('prints#orderPrint');
        Route::get('prints/salesReportPrint', [PrintController::class, 'salesReportPrint'])->name('prints#salesReportPrint');

        Route::post('order/samplePrint', [PrintController::class, 'samplePrint'])->name('order#samplePrint');
        Route::post('order/sampleDeletePrint', [PrintController::class, 'sampleDeletePrint'])->name('order#sampleDeletePrint');

        // QZ Tray silent printing
        Route::get('qz/certificate', [PrintController::class, 'qzCertificate'])->name('qz#certificate');
        Route::post('qz/sign', [PrintController::class, 'qzSign'])->name('qz#sign');
    });
});
