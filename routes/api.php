<?php

use App\Http\Controllers\Api\DineInController;
use App\Http\Controllers\Api\FloorController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\SalesOrderController;
use App\Http\Controllers\Api\TableController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\CompanyInfoController;
use App\Http\Controllers\Api\MainCategoryController;
use App\Http\Controllers\Api\ReportController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::post('/users/login', [UserController::class, 'login']); // User Login
Route::get('/daily-shop-report', [ReportController::class, 'dailyShopReport']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/floors', [FloorController::class, 'index']); // Get Active floors
    Route::get('/tables/{id}', [TableController::class, 'index']); // Get Active Tables
    Route::get('/getcompanyinfo', [CompanyInfoController::class, 'getcompanyinfo']); // Get Active Tables
    Route::get('/menucategories', [OrderController::class, 'getcategories']); // Get Active Tables
    Route::get('/getitemsbycategory/{id}', [OrderController::class, 'getitemsbycategory']); // Get Active Items
    Route::get('/getItemBySearchKey/{id}', [OrderController::class, 'getItemBySearchKey']); // Search Items
    Route::get('/getorder', [OrderController::class, 'getorder']); // Search Items
    Route::post('/addorderitem', [OrderController::class, 'addorderitem']); // Search Items
    Route::delete('/deleteorderitem/{id}', [OrderController::class, 'deleteorderitem']); // Search Items

    Route::get('/salesorder', [SalesOrderController::class, 'salesOrder']); // Search Items

    Route::post('/tableMerge', [DineInController::class, 'tableMerge']); // Search Items


    // Route::get('/getMemberCardByMemberCardCode', [SalesOrderController::class, 'getMemberCardByMemberCardCode']); // Search Items
    // Route::get('/getCouponCardByCouponCardCode', [SalesOrderController::class, 'getCouponCardByCouponCardCode']); // Search Items
    Route::post('/checkOut', [SalesOrderController::class, 'checkOut']); // Search Items
    Route::get('/pre-print', [SalesOrderController::class, 'prePrint']); // Search Items
    Route::get('/pre-order-print', [SalesOrderController::class, 'preOrderPrint']); // Search Items

// Route::get('/dinein', [DineInController::class, 'index']); // Get all users
// Route::get('/dinein', [UserController::class, 'getoccupiedtables']); // Get all users
// Route::get('/dinein', [UserController::class, 'getreservations']); // Get all users
// Route::get('/dinein/{floorID}', [UserController::class, 'gettablesbyfloor']); // Get single user
// Route::get('/dinein/{tableID,tableOrderNumber}', [UserController::class, 'getorderdetails']); // Get single user
// Route::get('/dinein/{id}', [UserController::class, 'gettablesbyfloor']); // Get single user
// Route::get('/dinein/{id}', [UserController::class, 'gettablesbyfloor']); // Get single user
// Route::get('/dinein/{id}', [UserController::class, 'gettablesbyfloor']); // Get single user
// Route::get('/dinein/{id}', [UserController::class, 'gettablesbyfloor']); // Get single user



// Route::get('/orders', [UserController::class, 'getcategories']); // Get all users
// Route::get('/orders/{searchKey}', [UserController::class, 'searchitems']); // Get single user
// Route::get('/orders/{categoryId}', [UserController::class, 'getitemsbycategory']); // Get single user

    Route::get('/maincategories', [MainCategoryController::class, 'index']);
    Route::post('/logout', [UserController::class, 'logout']);
});

