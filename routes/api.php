<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers;
use \App\Models;
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

//Public routes
Route::post('/login', [Controllers\Authcontroller::class, 'login']);
Route::post('/register', [Controllers\Authcontroller::class, 'register']);
Route::post('/businessRegister', [Controllers\Authcontroller::class, 'registerBusiness']);

//Protected routes

Route::group(['middleware' => ['auth:sanctum']], function (){
    Route::post('/logout', [Controllers\Authcontroller::class, 'logout']);

    Route::get('/categories', [Controllers\CategoryController::class, 'index']);
    //Business
    //Categories
    Route::get('/business/getCategories', [Controllers\CategoryController::class, 'getBusinessCategories']);
    Route::post('/business/setCategory', [Controllers\CategoryController::class, 'setCategoryToBusiness']);
    Route::delete('/business/deleteCategory/{businessHasCategory}', [Controllers\CategoryController::class, 'deleteCategoryFromBusiness']);
    //Addresses
    Route::put('/business/editAddress', [Controllers\AddressController::class, 'editAddress']);
    Route::put('/business/getAddress', [Controllers\AddressController::class, 'getAddress']);

});
