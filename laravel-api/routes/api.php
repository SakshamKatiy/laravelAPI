<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('create-user',[UserController::class,'createUser']);
Route::get('get-user',[UserController::class,'getUser']);
Route::get('get-user-details/{id}',[UserController::class,'getUserDetails']);
Route::put('user-update/{id}',[UserController::class,'updateUser']);
Route::delete('user-delete/{id}',[UserController::class,'deleteUser']);

Route::post('login',[UserController::class,'login']);
Route::get('unauthenticate',[UserController::class,'login'])->name('unauthenticate');

// secure routes with auth middleware
Route::middleware('auth:api')->group(function(){
    Route::get('get-user',[UserController::class,'getUser']);
    Route::get('get-user-details/{id}',[UserController::class,'getUserDetails']);
    Route::post('logout',[UserController::class,'logout']);

});