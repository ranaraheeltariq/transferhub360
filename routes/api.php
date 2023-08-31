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
            Route::get('profile', [Controllers\API\Authentication\AdminAuthenticationController::class, 'profile']);
            Route::post('device_token',[Controllers\API\Authentication\AdminAuthenticationController::class,'deviceTokenUpdate']);
            Route::get('/', [Controllers\API\UserController::class, 'index'])->middleware('permission:user list');
            Route::post('create', [Controllers\API\UserController::class, 'store'])->middleware('permission:user store');
            Route::get('detail/{id}', [Controllers\API\UserController::class, 'show'])->middleware('permission:user detail');
            Route::post('update/{id}', [Controllers\API\UserController::class, 'update'])->middleware('permission:user update');
            Route::post('delete/{id}', [Controllers\API\UserController::class, 'destroy'])->middleware('permission:user delete');
            Route::post('generatepassword/{id}', [Controllers\API\UserController::class, 'generatepassword'])->middleware('permission:user password generation');
            Route::post('roleassign', [Controllers\API\UserController::class, 'roleAssign'])->middleware('permission:user store');
        });
        Route::group(['prefix' => 'drivers'], function(){
            Route::get('/', [Controllers\API\DriverController::class, 'index'])->middleware('permission:driver list');
            Route::post('create', [Controllers\API\DriverController::class, 'store'])->middleware('permission:driver store');
            Route::get('detail/{id}', [Controllers\API\DriverController::class, 'show'])->middleware('permission:driver detail');
            Route::post('update/{id}', [Controllers\API\DriverController::class, 'update'])->middleware('permission:driver update');
            Route::post('delete/{id}', [Controllers\API\DriverController::class, 'destroy'])->middleware('permission:driver delete');
            Route::post('generatepassword/{id}', [Controllers\API\DriverController::class, 'generatepassword'])->middleware('permission:driver password generation');
            Route::get('count', [Controllers\API\DriverController::class, 'driverCount'])->middleware('permission:driver count');
        });
        Route::group(['prefix' => 'cities'], function(){
            Route::get('/', [Controllers\API\UetdsCityController::class, 'index'])->middleware('permission:uetds city list');
            Route::post('create', [Controllers\API\UetdsCityController::class, 'store'])->middleware('permission:uetds city store');
            Route::get('/city/{city}',[Controllers\API\UetdsCityController::class,'getByCityCode'])->middleware('permission:uetds city list');
            Route::get('detail/{id}', [Controllers\API\UetdsCityController::class, 'show'])->middleware('permission:uetds city detail');
            Route::post('update/{id}', [Controllers\API\UetdsCityController::class, 'update'])->middleware('permission:uetds city update');
            Route::post('delete/{id}', [Controllers\API\UetdsCityController::class, 'destroy'])->middleware('permission:uetds city delete');
        });
        Route::group(['prefix' => 'supervisors'], function(){
            Route::get('/', [Controllers\API\SupervisorController::class, 'index'])->middleware('permission:supervisor list');
            Route::post('create', [Controllers\API\SupervisorController::class, 'store'])->middleware('permission:supervisor store');
            Route::get('detail/{id}', [Controllers\API\SupervisorController::class, 'show'])->middleware('permission:supervisor detail');
            Route::post('update/{id}', [Controllers\API\SupervisorController::class, 'update'])->middleware('permission:supervisor update');
            Route::post('delete/{id}', [Controllers\API\SupervisorController::class, 'destroy'])->middleware('permission:supervisor delete');
            Route::post('generatepassword/{id}', [Controllers\API\SupervisorController::class, 'generatepassword'])->middleware('permission:supervisor password generation');
        });
        Route::group(['prefix' => 'vehicles'], function(){
            Route::get('/', [Controllers\API\VehicleController::class, 'index'])->middleware('permission:vehicle list');
            Route::post('create', [Controllers\API\VehicleController::class, 'store'])->middleware('permission:vehicle store');
            Route::get('detail/{id}', [Controllers\API\VehicleController::class, 'show'])->middleware('permission:vehicle detail');
            Route::post('update/{id}', [Controllers\API\VehicleController::class, 'update'])->middleware('permission:vehicle update');
            Route::post('delete/{id}', [Controllers\API\VehicleController::class, 'destroy'])->middleware('permission:vehicle delete');
            Route::get('count', [Controllers\API\VehicleController::class, 'vehicleCount'])->middleware('permission:vehicle count');
        });
        Route::group(['prefix' => 'customers'], function(){
            Route::get('/', [Controllers\API\CustomerController::class, 'index'])->middleware('permission:customer list');
            Route::post('create', [Controllers\API\CustomerController::class, 'store'])->middleware('permission:customer store');
            Route::get('detail/{id}', [Controllers\API\CustomerController::class, 'show'])->middleware('permission:customer detail');
            Route::post('update/{id}', [Controllers\API\CustomerController::class, 'update'])->middleware('permission:customer update');
            Route::post('delete/{id}', [Controllers\API\CustomerController::class, 'destroy'])->middleware('permission:customer delete');
        });
        Route::group(['prefix' => 'flights'], function(){
            Route::get('/', [Controllers\API\FlightNumberController::class, 'index'])->middleware('permission:flight list');
            Route::post('create', [Controllers\API\FlightNumberController::class, 'store'])->middleware('permission:flight store');
            Route::get('detail/{id}', [Controllers\API\FlightNumberController::class, 'show'])->middleware('permission:flight detail');
            Route::post('update/{id}', [Controllers\API\FlightNumberController::class, 'update'])->middleware('permission:flight update');
            Route::post('delete/{id}', [Controllers\API\FlightNumberController::class, 'destroy'])->middleware('permission:flight delete');
        });
        Route::group(['prefix' => 'passengers'], function(){
            Route::get('/', [Controllers\API\PassengerController::class, 'index'])->middleware('permission:passenger list');
            Route::post('create', [Controllers\API\PassengerController::class, 'store'])->middleware('permission:passenger store');
            Route::get('detail/{id}', [Controllers\API\PassengerController::class, 'show'])->middleware('permission:passenger detail');
            Route::post('update/{id}', [Controllers\API\PassengerController::class, 'update'])->middleware('permission:passenger update');
            Route::post('delete/{id}', [Controllers\API\PassengerController::class, 'destroy'])->middleware('permission:passenger delete');
            Route::post('generatepassword/{id}', [Controllers\API\PassengerController::class, 'generatepassword'])->middleware('permission:passenger password generation');
        });
        Route::group(['prefix' => 'transfers'], function(){
            Route::get('/', [Controllers\API\TransferController::class, 'index'])->middleware('permission:transfer list');
            Route::post('create', [Controllers\API\TransferController::class, 'store'])->middleware('permission:transfer store');
            Route::get('detail/{id}', [Controllers\API\TransferController::class, 'show'])->middleware('permission:transfer detail');
            Route::post('update/{id}', [Controllers\API\TransferController::class, 'update'])->middleware('permission:transfer update');
            Route::post('delete/{id}', [Controllers\API\TransferController::class, 'destroy'])->middleware('permission:transfer delete');
            Route::post('assigne/{id}',[Controllers\API\TransferController::class,'assigneVehicle'])->middleware('permission:transfer assign vehicle');
            Route::post('delete/assigne/{id}',[Controllers\API\TransferController::class,'cancelAssignedVehicle'])->middleware('permission:transfer unassign vehicle');
            Route::post('attachPassengers',[Controllers\API\TransferController::class,'attachPassengers'])->middleware('permission:transfer passenger store');
            Route::get('uetdsfile/{id}', [Controllers\API\TransferController::class, 'generateUetdsPdf'])->middleware('permission:transfer uetds file');
            Route::get('typecount/{date}/{id?}',[Controllers\API\TransferController::class,'groupByType'])->middleware('permission:transfer count');
            Route::get('passengers/{id}', [Controllers\API\TransferController::class, 'passenger'])->middleware('permission:transfer passenger detail');
        });
        Route::group(['prefix' => 'hotels'], function(){
            Route::get('/', [Controllers\API\HotelController::class, 'index'])->middleware('permission:hotel list');
            Route::post('create', [Controllers\API\HotelController::class, 'store'])->middleware('permission:hotel store');
            Route::get('detail/{id}', [Controllers\API\HotelController::class, 'show'])->middleware('permission:hotel detail');
            Route::post('update/{id}', [Controllers\API\HotelController::class, 'update'])->middleware('permission:hotel update');
            Route::post('delete/{id}', [Controllers\API\HotelController::class, 'destroy'])->middleware('permission:hotel delete');
        });
        Route::group(['prefix' => 'contact-persons'], function(){
            Route::get('/', [Controllers\API\ContactPersonController::class, 'index'])->middleware('permission:contact person list');
            Route::post('create', [Controllers\API\ContactPersonController::class, 'store'])->middleware('permission:contact person store');
            Route::get('detail/{id}', [Controllers\API\ContactPersonController::class, 'show'])->middleware('permission:contact person detail');
            Route::post('update/{id}', [Controllers\API\ContactPersonController::class, 'update'])->middleware('permission:contact person update');
            Route::post('delete/{id}', [Controllers\API\ContactPersonController::class, 'destroy'])->middleware('permission:contact person delete');
        });
        Route::group(['prefix' => 'roles'], function(){
            Route::get('/', [Controllers\API\RolesAndPermissionController::class, 'roles'])->middleware('permission:roles list');
            Route::get('permissions', [Controllers\API\RolesAndPermissionController::class, 'permissions'])->middleware('permission:permissions list');
            Route::get('permissions/guard/{guard}', [Controllers\API\RolesAndPermissionController::class, 'permissionsByGuardName'])->middleware('permission:permissions list');
            Route::get('rolesnamelist', [Controllers\API\RolesAndPermissionController::class, 'rolesName'])->middleware('permission:roles list');
            Route::post('create', [Controllers\API\RolesAndPermissionController::class, 'roleCreate'])->middleware('permission:roles store');
            Route::get('detail/', [Controllers\API\RolesAndPermissionController::class, 'show']);
            Route::post('update/{role}', [Controllers\API\RolesAndPermissionController::class, 'roleUpdate'])->middleware('permission:roles update');
            Route::post('delete/{role}', [Controllers\API\RolesAndPermissionController::class, 'destroy'])->middleware('permission:roles delete');
            Route::post('permissions/assign', [Controllers\API\RolesAndPermissionController::class, 'permissionsAssignToRole'])->middleware('permission:roles store');
            Route::get('guards', [Controllers\API\RolesAndPermissionController::class, 'getGuardName'])->middleware('permission:roles list');
            Route::get('guard/{guards}', [Controllers\API\RolesAndPermissionController::class, 'getRolesByGuardName'])->middleware('permission:roles list');
            Route::get('count/{role}', [Controllers\API\RolesAndPermissionController::class, 'countByRole'])->middleware('permission:roles list');
        });
    });
});

require_once base_path('routes/admin.php');
require_once base_path('routes/driver.php');
require_once base_path('routes/supervisor.php');
require_once base_path('routes/passenger.php');