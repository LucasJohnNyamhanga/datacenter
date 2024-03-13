<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\LoanController;
use App\Http\Controllers\Api\OfficeController;
use App\Http\Controllers\Api\UploadController;
use App\Http\Controllers\Api\CustomerController;


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('logout', [AuthController::class, 'logout']); 
    Route::post('storeCustomer', [CustomerController::class, 'storeCustomer']);
    Route::post('storeOffice', [OfficeController::class, 'storeOffice']);
    Route::post('storeLoan', [LoanController::class, 'storeLoan']);
    Route::post("upload", [UploadController::class, 'upload']);
    Route::post('signup', [AuthController::class, 'signup']);
    Route::get('getNewCustomer', [CustomerController::class, 'getNewCustomer']);
});

Route::post('login', [AuthController::class, 'login']);
Route::get('getOffice', [OfficeController::class, 'getOffice']);
