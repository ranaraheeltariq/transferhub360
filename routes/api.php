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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('login', [Controllers\API\Authentication\OwnerAuthenticationController::class, 'login']);
Route::group(['middleware' => ['auth:sanctum'],'prefix' => 'companies'], function ($router) {
    Route::get('/', [Controllers\API\CompanyController::class, 'index']);
    Route::post('create', [Controllers\API\CompanyController::class, 'store']);
    Route::get('detail/{id}', [Controllers\API\CompanyController::class, 'show']);
    Route::post('update/{id}', [Controllers\API\CompanyController::class, 'update']);
    Route::post('delete/{id}', [Controllers\API\CompanyController::class, 'destroy']);
});