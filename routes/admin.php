<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers;

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

// Owner Guard Routes
Route::group(['prefix' => 'admin'], function($router) {
    Route::post('login', [Controllers\API\Authentication\OwnerAuthenticationController::class, 'login']);
    Route::group(['middleware' => ['auth:sanctum']], function($router){
        Route::group(['prefix' => 'users'], function($router){
            Route::post('/logout',[Controllers\API\Authentication\OwnerAuthenticationController::class,'logout']);
            Route::post('/reset-password',[Controllers\API\Authentication\OwnerAuthenticationController::class,'passwordReset']);
            Route::post('profileupdate', [Controllers\API\Authentication\OwnerAuthenticationController::class, 'update']);
            Route::get('profile',[Controllers\API\Authentication\OwnerAuthenticationController::class,'profile']);
            Route::post('device_token',[Controllers\API\Authentication\OwnerAuthenticationController::class,'deviceTokenUpdate']);
            Route::get('/', [Controllers\API\OwnerController::class, 'index'])->middleware('permission:owner list');
            Route::post('create', [Controllers\API\OwnerController::class, 'store'])->middleware('permission:owner store');
            Route::get('detail/{id}', [Controllers\API\OwnerController::class, 'show'])->middleware('permission:owner detail');
            Route::post('update/{id}', [Controllers\API\OwnerController::class, 'update'])->middleware('permission:owner update');
            Route::post('delete/{id}', [Controllers\API\OwnerController::class, 'destroy'])->middleware('permission:owner delete');
            Route::post('generatepassword/{id}', [Controllers\API\OwnerController::class, 'generatepassword'])->middleware('permission:owner password generation');
        });
        Route::group(['prefix' => 'companies'], function($router){
            Route::get('/', [Controllers\API\CompanyController::class, 'index'])->middleware('permission:company list');
            Route::post('create', [Controllers\API\CompanyController::class, 'store'])->middleware('permission:company store');
            Route::get('detail/{id}', [Controllers\API\CompanyController::class, 'show'])->middleware('permission:company detail');
            Route::post('update/{id}', [Controllers\API\CompanyController::class, 'update'])->middleware('permission:company update');
            Route::post('delete/{id}', [Controllers\API\CompanyController::class, 'destroy'])->middleware('permission:company delete');
        });
    });
});