<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Banners\BannersController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\Coupons\CouponsController;
use App\Http\Controllers\Customers\CustomersController;
use App\Http\Controllers\Permission\PermissionController;
use App\Http\Controllers\Product\ProductUnitController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Order\OrderController;
use App\Http\Controllers\Invoice\InvoiceController;
use App\Http\Controllers\Category\CategoryController;
use App\Http\Controllers\Role\RoleController;
use App\Http\Controllers\MetatagsController;
use App\Http\Controllers\Setting\SettingController;
use App\Http\Controllers\OtherCharge\OtherChargeController;
use App\Http\Controllers\Review\ReviewController;
use App\Http\Controllers\Address\AddressController;
use App\Http\Controllers\Sale\SaleController;
use App\Http\Controllers\Image\ImageController;
use App\Http\Controllers\Sizes\SizesController;
use App\Http\Controllers\Charts\ChartsController;
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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('logout', [AuthController::class, 'logout']);

    //role
    Route::get('role', [RoleController::class, 'index']);
    Route::post('role', [RoleController::class, 'store']);
    Route::get('role/{id}', [RoleController::class, 'show']);
    Route::get('role/{id}/edit', [RoleController::class, 'edit']);
    Route::put('role/{id}', [RoleController::class, 'update']);
    Route::delete('role/{id}', [RoleController::class, 'destroy']);
    Route::get('/check-permissions', [RoleController::class, 'checkPermissions']);


    //customers
    Route::get('customers', [CustomersController::class, 'index']);
    Route::get('customer/create', [CustomersController::class, 'create']);
    Route::post('customer', [CustomersController::class, 'store']);
    Route::get('customer/{id}', [CustomersController::class, 'show']);
    Route::get('customer/{id}/edit', [CustomersController::class, 'edit']);
    Route::post('updateCustomer/{id}', [CustomersController::class, 'update']);
    Route::delete('customer/{id}', [CustomersController::class, 'destroy']);

    Route::get('profile', [CustomersController::class, 'getProfile']);
    Route::put('updateProfile', [CustomersController::class, 'updateProfile']);
    Route::put('updatePassword', [CustomersController::class, 'updatePassword']);


    Route::post('/send-to-openai', [ChatController::class, 'sendToOpenAI']);
    Route::post('/send-to-openaidoc2', [ChatController::class, 'sendToOpenAIDoc2']);
    Route::get('/downloadProcessedFile', [ChatController::class, 'downloadProcessedFile']);


});
