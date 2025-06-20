<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\AdminLoginController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\AdminSignUpController;
use App\Http\Controllers\CourierLoginController;
use App\Http\Controllers\AdminProductsController;
use App\Http\Controllers\CourierSignUpController;
use App\Http\Controllers\CustomerLoginController;
use App\Http\Controllers\CustomerTopUpController;
use App\Http\Controllers\CustomerSignUpController;
use App\Http\Controllers\AdminCategoriesController;
use App\Http\Controllers\CustomerViewCartController;
use App\Http\Controllers\CustomerDashboardController;
use App\Http\Controllers\AdminAddNewProductController;
use App\Http\Controllers\AdminUpdateProductController;
use App\Http\Controllers\AdminUpdateProfileController;
use App\Http\Controllers\CustomerBuyProductController;
use App\Http\Controllers\CourierUpdateProfileController;
use App\Http\Controllers\CustomerUpdateProfileController;
use App\Http\Controllers\CustomerPurchaseHistoryController;
use App\Http\Controllers\CustomerAddressController;
use App\Http\Controllers\AdminProcessingOrderController;
use App\Http\Controllers\AdminTransactionHistoryController;
use App\Http\Controllers\CourierDeliveryOrderController;
use App\Http\Controllers\ErrorController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [ProductsController::class, 'index'])->middleware('customer.access');

Route::get('/login', [CustomerLoginController::class, 'index'])->middleware('guest.access')->name('login');
Route::post('/login', [CustomerLoginController::class, 'authenticate']);
Route::post('/logout', [CustomerLoginController::class, 'logout'])->middleware('auth:customer');

Route::get('/signup', [CustomerSignUpController::class, 'index'])->middleware('guest.access');
Route::post('/signup', [CustomerSignUpController::class, 'store']);

Route::middleware(['auth:customer', 'customer.access'])->group(function () {
    Route::prefix('cart')->group(function () {
        Route::get('/', [CustomerViewCartController::class, 'index']);
        Route::post('/', [CustomerViewCartController::class, 'store'])->name('cart.store');
        Route::post('/subtotal', [CustomerViewCartController::class, 'getSubtotal'])->name('cart.subtotal');
        Route::patch('/{brand_slug}/{product_slug}/update', [CustomerViewCartController::class, 'update'])->name('cart.update');
        Route::delete('/{brand_slug}/{product_slug}/delete', [CustomerViewCartController::class, 'destroy'])->name('cart.delete');
    });

    Route::post('/checkout/process', [CustomerBuyProductController::class, 'checkout'])->name('checkout.process');
    Route::get('/checkout', [CustomerBuyProductController::class, 'index'])->name('checkout.page');
    Route::post('/checkout/payment', [CustomerBuyProductController::class, 'store'])->name('checkout.payment');

    Route::post('/address', [CustomerAddressController::class, 'store'])->name('address.store');
    Route::get('/address', [CustomerAddressController::class, 'show']);
    Route::put('address/update/{customer_address_id}', [CustomerAddressController::class, 'update'])->name('address.update');
    Route::delete('address/delete/{customer_address_id}', [CustomerAddressController::class, 'destroy'])->name('address.destroy');
    
    Route::prefix('dashboard')->group(function () {
        Route::get('/myprofile', [CustomerUpdateProfileController::class, 'show']);
        Route::put('/myprofile/update', [CustomerUpdateProfileController::class, 'update']);

        Route::get('/topup', [CustomerTopUpController::class, 'show'])->name('topup.show');
        Route::put('/topup/update', [CustomerTopUpController::class, 'update']);

        Route::get('/purchasehistory', [CustomerPurchaseHistoryController::class, 'index'])->name('purchasehistory.show');
        Route::patch('/purchasehistory/cancel-order/{transaction_id}', [CustomerPurchaseHistoryController::class, 'cancelOrder'])->name('purchasehistory.cancel');
        Route::patch('/purchasehistory/confirm-order/{transaction_id}', [CustomerPurchaseHistoryController::class, 'confirmOrder'])->name('purchasehistory.confirm');
    });
});

Route::prefix('admin')->group(function(){
    Route::get('/signup', [AdminSignUpController::class, 'index'])->middleware('guest.access');
    Route::post('/signup', [AdminSignUpController::class, 'store']);

    Route::get('/login', [AdminLoginController::class, 'index'])->middleware('guest.access')->name('admin.login');
    Route::post('/login', [AdminLoginController::class, 'authenticate']);
    Route::post('/logout', [AdminLoginController::class, 'logout'])->middleware('auth:admin');

    Route::middleware(['auth:admin', 'admin.access'])->group(function () {
        Route::get('/myprofile', [AdminUpdateProfileController::class, 'show']);
        Route::get('/', [AdminUpdateProfileController::class, 'show']);
        Route::put('/myprofile/update', [AdminUpdateProfileController::class, 'update']);

        Route::get('/productlist', [AdminProductsController::class, 'index']);

        Route::get('productlist/addnewproduct', [AdminAddNewProductController::class, 'index']);
        Route::post('productlist/addnewproduct', [AdminAddNewProductController::class, 'store']);

        Route::get('/productlist/categories/{category_slug}', [AdminCategoriesController::class, 'filterByCategory']);
        Route::get('/productlist/{product_slug}', [AdminUpdateProductController::class, 'show']);
        Route::put('/productlist/{product_slug}/update', [AdminUpdateProductController::class, 'update']); 

        Route::get('/processing-order', [AdminProcessingOrderController::class, 'index']);
        Route::post('/processing-order/confirm/{transaction_id}', [AdminProcessingOrderController::class, 'store'])->name('admin.confirm');

        Route::get('/transaction-history', [AdminTransactionHistoryController::class, 'index'])->name('admin.transaction-history');
    });
});

Route::prefix('courier')->group(function(){
    Route::get('/signup', [CourierSignUpController::class, 'index'])->middleware('guest.access');
    Route::post('/signup', [CourierSignUpController::class, 'store']);

    Route::get('/login', [CourierLoginController::class, 'index'])->middleware('guest.access')->name('courier.login');
    Route::post('/login', [CourierLoginController::class, 'authenticate']);
    Route::post('/logout', [CourierLoginController::class, 'logout'])->middleware('auth:courier');

    Route::middleware(['auth:courier', 'courier.access'])->group(function () {
        Route::get('/myprofile', [CourierUpdateProfileController::class, 'show']);
        Route::get('/', [CourierUpdateProfileController::class, 'show']);
        Route::put('/myprofile/update', [CourierUpdateProfileController::class, 'update']);

        Route::get('/delivery-order', [CourierDeliveryOrderController::class, 'show'])->name('courier.delivery');
        Route::post('/delivery-order/to-ship/{transaction_id}', [CourierDeliveryOrderController::class, 'store'])->name('courier.to-ship');
    
        Route::post('delivery-order/in-progress/{shipment_id}', [CourierDeliveryOrderController::class, 'update'])->name('courier.confirm');
        Route::post('delivery-order/in-progress/cancel/{shipment_id}', [CourierDeliveryOrderController::class, 'cancel'])->name('courier.in-progress');
    });
});

Route::get('{brand_slug}/{product_slug}', [ProductsController::class, 'show'])->middleware('customer.access');
Route::get('/{category_slug}', [CategoriesController::class, 'filterByCategory'])->middleware('customer.access');

// Route::fallback([ErrorController::class, 'notFound']);