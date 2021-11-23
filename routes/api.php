<?php

use App\Http\Controllers\BuyerAuthController;
use App\Http\Controllers\SellerAuthController;
use App\Http\Controllers\DeliveryAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/auth/buyerregister', [BuyerAuthController::class, 'buyerregister']);
Route::post('/auth/buyerlogin', [BuyerAuthController::class, 'buyerlogin']);

Route::middleware('auth:sanctum')->get(
    '/buyer',
    function (Request $request) {
        return $request->buyer();
    }
);

Route::post('/auth/sellerregister', [SellerAuthController::class, 'sellerregister']);
Route::post('/auth/sellerlogin', [SellerAuthController::class, 'sellerlogin']);
Route::middleware('auth:sanctum')->get(
    '/seller',
    function (Request $request) {
        return $request->seller();
    }
);
Route::post('/auth/deliveryregister', [DeliveryAuthController::class, 'deliveryregister']);
Route::post('/auth/deliverylogin', [DeliveryAuthController::class, 'deliverylogin']);
Route::middleware('auth:sanctum')->get(
    '/delivery',
    function (Request $request) {
        return $request->delivery();
    }
);
