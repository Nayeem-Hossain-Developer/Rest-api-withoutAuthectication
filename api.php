<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserApiController;

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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::get('/show-user/{id?}',[UserApiController::class,'showUser']);
Route::post('/add-user',[UserApiController::class,'storeUser']);
Route::post('/add-multiple',[UserApiController::class,'storeMultiple']);
Route::put('/update-user/{id}',[UserApiController::class,'updateUser']);
Route::patch('/update-single-field/{id}',[UserApiController::class,'updateSingleFieldUser']);
Route::delete('/delete-user/{id}',[UserApiController::class,'deleteUser']);
Route::delete('/delete-multiple-user/{ids}',[UserApiController::class,'deleteMultipleUser']);
Route::delete('/delete-user-withJson',[UserApiController::class,'deleteUserWithJson']);
Route::delete('/delete-multiple-user-withJson',[UserApiController::class,'deleteMultipleWithJson']);
