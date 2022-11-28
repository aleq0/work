<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkInfoController;
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

Route::post('login', [AuthController::class, 'login']);
Route::post('refresh', [AuthController::class, 'refresh'])/*->middleware('jwt.refresh')*/;

Route::group(['middleware' => 'auth.jwt'], function () {
    Route::post('logout', [AuthController::class, 'logout']);
});

//Route::group(['middleware' => 'auth.jwt:admin'], function () {
    Route::group(['prefix' => 'users'], function () {
        Route::get('/', [UserController::class, 'all']);
        Route::post('/', [UserController::class, 'create']);
        Route::post('/{user}', [UserController::class, 'update']);
        Route::get('/{user}', [UserController::class, 'get']);
        Route::delete('/{user}', [UserController::class, 'delete']);
    });

    Route::group(['prefix' => 'info'], function() {
        Route::get('/', [WorkInfoController::class, 'all']);
        Route::get('/{id}', [WorkInfoController::class, 'get']);
    });
//});

Route::post('action', [WorkInfoController::class, 'start_stop']);
Route::post('location', [WorkInfoController::class, 'updateCurrentLocation']);
