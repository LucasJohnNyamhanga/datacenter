<?php

use App\Http\Controllers\Api\BalanceController;
use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\FainiController;
use App\Http\Controllers\Api\MapatoController;
use App\Http\Controllers\Api\MatumiziController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\LoanController;
use App\Http\Controllers\Api\OfficeController;
use App\Http\Controllers\Api\UploadController;
use App\Http\Controllers\Api\DhamanaController;
use App\Http\Controllers\Api\RejeshoController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\MdhaminiController;

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
    Route::get('getCustomerDetails', [CustomerController::class, 'getCustomerDetails']);
    Route::post('storeMdhamini', [MdhaminiController::class, 'storeMdhamini']);
    Route::post('deleteMdhamini', [MdhaminiController::class, 'deleteMdhamini']);
    Route::post('storeDhamana', [DhamanaController::class, 'storeDhamana']);
    Route::post('deleteDhamana', [DhamanaController::class, 'deleteDhamana']);
    Route::get('getUsers', [AuthController::class, 'getUsers']);
    Route::post('wekaKasoroMkopo', [LoanController::class, 'wekaKasoroMkopo']);
    Route::post('pitishaMkopo', [LoanController::class, 'pitishaMkopo']);
    Route::get('getKasoroCustomer', [CustomerController::class, 'getKasoroCustomer']);
    Route::get('getWateja', [CustomerController::class, 'getWateja']);
    Route::post('storeMapato', [MapatoController::class, 'storeMapato']);
    Route::get('getMapato', [MapatoController::class, 'getMapato']);
    Route::post('storeMatumizi', [MatumiziController::class, 'storeMatumizi']);
    Route::get('getMatumizi', [MatumiziController::class, 'getMatumizi']);
    Route::post('badiliAfisaMkopo', [CustomerController::class, 'badiliAfisaMkopo']);
    Route::post('getDepartmentById', [DepartmentController::class, 'getDepartmentById']);
    Route::get('tafutaWatejaWanaofanana', [CustomerController::class, 'tafutaWatejaWanaofanana']);
    Route::post('storeFaini', [FainiController::class, 'storeFaini']);
    Route::post('taifishaDhamana', [DhamanaController::class, 'taifishaDhamana']);
    Route::post('badiliTawi', [AuthController::class, 'badiliTawi']);
    Route::post('getDepartments', [DepartmentController::class, 'getDepartments']);
    Route::post('getDepartmentByUserId', [DepartmentController::class, 'getDepartmentByUserId']);
    Route::post('activateUser', [AuthController::class, 'activateUser']);
    Route::post('storeDepartment', [DepartmentController::class, 'storeDepartment']);
    Route::post('badiliMeneja', [DepartmentController::class, 'badiliMeneja']);
    Route::post('sahishaKitengo', [DepartmentController::class, 'sahishaKitengo']);
    Route::get('storeOffice', [OfficeController::class, 'storeOffice']);
    Route::get('getUsersWithOfisiId', [AuthController::class, 'getUsersWithOfisiId']);
    Route::post('getDepartmentByAfisaId', [DepartmentController::class, 'getDepartmentByAfisaId']);
    Route::post('storeHesabuKuu', [BalanceController::class, 'storeHesabuKuu']);
    Route::get('getMahesabu', [BalanceController::class, 'getMahesabu']);
    Route::get('getMahesabuWithDate', [BalanceController::class, 'getMahesabuWithDate']);
    Route::get('getMahesabuWithTwoDates', [BalanceController::class, 'getMahesabuWithTwoDates']);
    Route::post('sahihishaMatumizi', [MatumiziController::class, 'sahihishaMatumizi']);
    Route::post('futaMatumizi', [MatumiziController::class, 'futaMatumizi']);
    Route::post('sahihishaMapato', [MapatoController::class, 'sahihishaMapato']);
    Route::post('futaMapato', [MapatoController::class, 'futaMapato']);
    Route::get('getMarekebisho', [MatumiziController::class, 'getMarekebisho']);
    Route::post('futaPatoMojaKwaMoja', [MapatoController::class, 'futaPatoMojaKwaMoja']);
    Route::post('futaTumiziMojaKwaMoja', [MatumiziController::class, 'futaTumiziMojaKwaMoja']);
    Route::post('hapanaMabadilikoMapato', [MapatoController::class, 'hapanaMabadilikoMapato']);
    Route::post('hapanaMabadilikoMatumizi', [MatumiziController::class, 'hapanaMabadilikoMatumizi']);
    Route::post('rekebishaMabadilikoMapato', [MapatoController::class, 'rekebishaMabadilikoMapato']);
    Route::post('rekebishaMabadilikoMatumizi', [MatumiziController::class, 'rekebishaMabadilikoMatumizi']);
    Route::get('getMatumiziWithDate', [MatumiziController::class, 'getMatumiziWithDate']);
    Route::get('getMapatoWithDate', [MapatoController::class, 'getMapatoWithDate']);
    Route::get('getMenejaMarekebisho', [MatumiziController::class, 'getMenejaMarekebisho']);
    Route::post('badiliAdmin', [DepartmentController::class, 'badiliAdmin']);
    Route::get('getMteja', [CustomerController::class, 'getMteja']);
    Route::get('getWatejaWaliomaliza', [CustomerController::class, 'getWatejaWaliomaliza']);
    Route::get('getWatejaNjeMuda', [CustomerController::class, 'getWatejaNjeMuda']);
    Route::get('getOfficeWithBalance', [OfficeController::class, 'getOfficeWithBalance']);
    Route::post('isAdmin', [AuthController::class, 'isAdmin']);
    Route::post('activateAdmin', [AuthController::class, 'activateAdmin']);
    Route::post('deleteHesabu', [BalanceController::class, 'deleteHesabu']);
    Route::post('badiliTaarifaMkopo', [LoanController::class, 'badiliTaarifaMkopo']);
    Route::get('getBakiSalio', [RejeshoController::class, 'getBakiSalio']);
    Route::post('changePassword', [AuthController::class, 'changePassword']);
    Route::post('rekebishaCustomer', [CustomerController::class, 'rekebishaCustomer']);
    Route::get('getMatumiziWithTwoDate', [MatumiziController::class, 'getMatumiziWithTwoDate']);
    Route::get('getMapatoWithTwoDate', [MapatoController::class, 'getMapatoWithTwoDate']);
});

Route::post('login', [AuthController::class, 'login']);
Route::get('getOffice', [OfficeController::class, 'getOffice']);
Route::get('getDepartment', [DepartmentController::class, 'getDepartment']);
