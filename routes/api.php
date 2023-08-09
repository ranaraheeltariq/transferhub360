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

// Admin Guard Routes
Route::group(['prefix' => 'companies'], function($router) {
    Route::post('login', [Controllers\API\Authentication\AdminAuthenticationController::class, 'login']);
    Route::group(['middleware' => ['auth:sanctum']], function(){
        Route::group(['prefix' => 'users'], function(){
            Route::post('/logout',[Controllers\API\Authentication\AdminAuthenticationController::class,'logout']);
            Route::post('/reset-password',[Controllers\API\Authentication\AdminAuthenticationController::class,'passwordReset']);
            Route::post('profileupdate', [Controllers\API\Authentication\AdminAuthenticationController::class, 'update']);
            Route::get('/', [Controllers\API\UserController::class, 'index']);
            Route::post('create', [Controllers\API\UserController::class, 'store']);
            Route::get('detail/{id}', [Controllers\API\UserController::class, 'show']);
            Route::post('update/{id}', [Controllers\API\UserController::class, 'update']);
            Route::post('delete/{id}', [Controllers\API\UserController::class, 'destroy']);
        });
        Route::group(['prefix' => 'drivers'], function(){
            Route::get('/', [Controllers\API\DriverController::class, 'index']);
            Route::post('create', [Controllers\API\DriverController::class, 'store']);
            Route::get('detail/{id}', [Controllers\API\DriverController::class, 'show']);
            Route::post('update/{id}', [Controllers\API\DriverController::class, 'update']);
            Route::post('delete/{id}', [Controllers\API\DriverController::class, 'destroy']);
        });
        Route::group(['prefix' => 'cities'], function(){
            Route::get('/', [Controllers\API\UetdsCityController::class, 'index']);
            Route::post('create', [Controllers\API\UetdsCityController::class, 'store']);
            Route::get('detail/{id}', [Controllers\API\UetdsCityController::class, 'show']);
            Route::post('update/{id}', [Controllers\API\UetdsCityController::class, 'update']);
            Route::post('delete/{id}', [Controllers\API\UetdsCityController::class, 'destroy']);
        });
        Route::group(['prefix' => 'supervisors'], function(){
            Route::get('/', [Controllers\API\SupervisorController::class, 'index']);
            Route::post('create', [Controllers\API\SupervisorController::class, 'store']);
            Route::get('detail/{id}', [Controllers\API\SupervisorController::class, 'show']);
            Route::post('update/{id}', [Controllers\API\SupervisorController::class, 'update']);
            Route::post('delete/{id}', [Controllers\API\SupervisorController::class, 'destroy']);
        });
        Route::group(['prefix' => 'vehicles'], function(){
            Route::get('/', [Controllers\API\VehicleController::class, 'index']);
            Route::post('create', [Controllers\API\VehicleController::class, 'store']);
            Route::get('detail/{id}', [Controllers\API\VehicleController::class, 'show']);
            Route::post('update/{id}', [Controllers\API\VehicleController::class, 'update']);
            Route::post('delete/{id}', [Controllers\API\VehicleController::class, 'destroy']);
        });
        Route::group(['prefix' => 'customers'], function(){
            Route::get('/', [Controllers\API\CustomerController::class, 'index']);
            Route::post('create', [Controllers\API\CustomerController::class, 'store']);
            Route::get('detail/{id}', [Controllers\API\CustomerController::class, 'show']);
            Route::post('update/{id}', [Controllers\API\CustomerController::class, 'update']);
            Route::post('delete/{id}', [Controllers\API\CustomerController::class, 'destroy']);
        });
        Route::group(['prefix' => 'passengers'], function(){
            Route::get('/', [Controllers\API\PassengerController::class, 'index']);
            Route::post('create', [Controllers\API\PassengerController::class, 'store']);
            Route::get('detail/{id}', [Controllers\API\PassengerController::class, 'show']);
            Route::post('update/{id}', [Controllers\API\PassengerController::class, 'update']);
            Route::post('delete/{id}', [Controllers\API\PassengerController::class, 'destroy']);
        });
        Route::group(['prefix' => 'transfers'], function(){
            Route::get('/', [Controllers\API\TransferController::class, 'index']);
            Route::post('create', [Controllers\API\TransferController::class, 'store']);
            Route::get('detail/{id}', [Controllers\API\TransferController::class, 'show']);
            Route::post('update/{id}', [Controllers\API\TransferController::class, 'update']);
            Route::post('delete/{id}', [Controllers\API\TransferController::class, 'destroy']);
            Route::post('assigne/{id}',[Controllers\API\TransferController::class,'assigneVehicle']);
            Route::post('delete/assigne/{id}',[Controllers\API\TransferController::class,'cancelAssignedVehicle']);
            Route::post('attachPassengers',[Controllers\API\TransferController::class,'attachPassengers']);
            Route::get('uetdsfile/{id}', [Controllers\API\TransferController::class, 'generateUetdsPdf']);
            Route::get('typecount/{date}/{id?}',[Controllers\API\TransferController::class,'groupByType']);
        });
    });
});

require_once base_path('routes/admin.php');
require_once base_path('routes/driver.php');
require_once base_path('routes/supervisor.php');
require_once base_path('routes/passenger.php');