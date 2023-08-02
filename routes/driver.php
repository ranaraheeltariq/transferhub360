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

// Driver Guard Routes
Route::group(['prefix' => 'driver'], function($router) {
    Route::post('login', [Controllers\API\Authentication\DriverAuthenticationController::class, 'login']);
    Route::group(['middleware' => ['auth:sanctum']], function(){
        Route::group(['prefix' => 'users'], function(){
            Route::post('/logout',[Controllers\API\Authentication\DriverAuthenticationController::class,'logout']);
            Route::post('/reset-password',[Controllers\API\Authentication\DriverAuthenticationController::class,'passwordReset']);
            Route::post('profileupdate', [Controllers\API\Authentication\DriverAuthenticationController::class, 'update']);
        });
    });
});