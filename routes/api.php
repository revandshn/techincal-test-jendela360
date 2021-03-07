<?php

use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Controllers\API\Auth\UserController;
use App\Http\Controllers\API\Feature\CarController;
use App\Http\Controllers\API\Feature\PurchaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1'], function () {

    Route::post('signup', [AuthController::class, 'signup']);
    Route::post('signin', [AuthController::class, 'signin']);

    Route::middleware('auth:api')->group(function () {
        // Profile
        Route::get('signout', [AuthController::class, 'signout']);

        Route::get('get-profile', [UserController::class, 'profile']);
        Route::get('get-profile/{id}', [UserController::class, 'show']);

        // Car
        Route::resource('cars', CarController::class);

        //    Purchase
        Route::get('purchase/car', [PurchaseController::class, 'index']);
        Route::get('purchase/car/seven-days', [PurchaseController::class, 'index7days']);
        Route::get('purchase/car/{id}', [PurchaseController::class, 'show']);
        Route::post('purchase/car/{id}', [PurchaseController::class, 'store']);
    });
});
