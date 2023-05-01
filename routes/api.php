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

//Public routes
Route::post('/login', [Controllers\Authcontroller::class, 'login']);
Route::post('/register', [Controllers\Authcontroller::class, 'register']);
Route::post('/business/register', [Controllers\Authcontroller::class, 'registerBusiness']);

//Protected routes

Route::group(['middleware' => ['auth:sanctum']], function (){
    Route::post('/logout', [Controllers\Authcontroller::class, 'logout']);

    Route::get('/categories', [Controllers\CategoryController::class, 'index']);
    //Business
    //Categories
    Route::get('/business/categories/getAll', [Controllers\CategoryController::class, 'getBusinessCategories']);
    Route::post('/business/categories/set', [Controllers\CategoryController::class, 'setCategoryToBusiness']);
    Route::delete('/business/categories/delete/{businessHasCategory}', [Controllers\CategoryController::class, 'deleteCategoryFromBusiness']);
    //Addresses
    Route::put('/business/address/edit', [Controllers\AddressController::class, 'editAddress']);
    Route::get('/business/address/get', [Controllers\AddressController::class, 'getAddress']);
    Route::get('/getBusinessAddress/{business}', [Controllers\AddressController::class, 'getBusinessAddress']);

    //Pictures
    Route::post('/business/picture/upload', [Controllers\PhotoController::class, 'upload']);
    Route::post('/business/picture/getAll', [Controllers\PhotoController::class, 'getAllPictures']);
    Route::get('/getBusinessPictures/{business}', [Controllers\PhotoController::class, 'getPictureByBusiness']);
    Route::delete('/business/picture/delete/{picture}',[Controllers\PhotoController::class, 'deletePictureFromBusiness']);
});
