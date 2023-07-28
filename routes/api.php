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
        Route::get('list', [Controllers\API\OwnerController::class, 'index']);
        Route::post('create', [Controllers\API\OwnerController::class, 'store']);
        Route::get('detail/{id}', [Controllers\API\OwnerController::class, 'show']);
        Route::post('update/{id}', [Controllers\API\OwnerController::class, 'update']);
        Route::post('delete/{id}', [Controllers\API\OwnerController::class, 'destroy']);
    });
    Route::group(['middleware' => ['auth:sanctum'], 'prefix' => 'users'], function($router){
        Route::post('/logout',[Controllers\API\Authentication\OwnerAuthenticationController::class,'logout']);
        Route::post('/reset-password',[Controllers\API\Authentication\OwnerAuthenticationController::class,'passwordReset']);
        Route::post('update', [Controllers\API\Authentication\OwnerAuthenticationController::class, 'update']);
    });
    Route::group(['middleware' => ['auth:sanctum'],'prefix' => 'companies'], function($router){
        Route::get('/', [Controllers\API\CompanyController::class, 'index']);
        Route::post('create', [Controllers\API\CompanyController::class, 'store']);
        Route::get('detail/{id}', [Controllers\API\CompanyController::class, 'show']);
        Route::post('update/{id}', [Controllers\API\CompanyController::class, 'update']);
        Route::post('delete/{id}', [Controllers\API\CompanyController::class, 'destroy']);
    });
});
// Admin Guard Routes
Route::group(['prefix' => 'companies'], function($router) {
    Route::post('login', [Controllers\API\Authentication\AdminAuthenticationController::class, 'login']);
    Route::group(['middleware' => ['auth:sanctum'], 'prefix' => 'users'], function($router){
        Route::post('/logout',[Controllers\API\Authentication\AdminAuthenticationController::class,'logout']);
        Route::post('/reset-password',[Controllers\API\Authentication\AdminAuthenticationController::class,'passwordReset']);
        Route::post('profileupdate', [Controllers\API\Authentication\AdminAuthenticationController::class, 'update']);
        Route::get('/', [Controllers\API\UserController::class, 'index']);
        Route::post('create', [Controllers\API\UserController::class, 'store']);
        Route::get('detail/{id}', [Controllers\API\UserController::class, 'show']);
        Route::post('update/{id}', [Controllers\API\UserController::class, 'update']);
        Route::post('delete/{id}', [Controllers\API\UserController::class, 'destroy']);
    });
});