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

    Route::get('/getBusinessAddress/{business}', [Controllers\AddressController::class, 'getBusinessAddress']);
    Route::get('/getBusinessPictures/{business}', [Controllers\PhotoController::class, 'getPictureByBusiness']);
    Route::get('/getBusinessCategories/{business}', [Controllers\CategoryController::class, 'getCategoriesByBusiness']);
    Route::get('/getBusinessSchedule/{business}', [Controllers\WorkDayController::class, 'getScheduleByBusiness']);


    //Categories
    Route::get('/business/categories/getAll', [Controllers\CategoryController::class, 'getBusinessCategories']);
    Route::post('/business/categories/set', [Controllers\CategoryController::class, 'setCategoryToBusiness']);
    Route::delete('/business/categories/delete/{businessHasCategory}', [Controllers\CategoryController::class, 'deleteCategoryFromBusiness']);

    //Addresses
    Route::put('/business/address/edit', [Controllers\AddressController::class, 'editAddress']);
    Route::get('/business/address/get', [Controllers\AddressController::class, 'getAddress']);

    //Pictures
    Route::post('/business/picture/upload', [Controllers\PhotoController::class, 'upload']);
    Route::post('/business/picture/getAll', [Controllers\PhotoController::class, 'getAllPictures']);
    Route::delete('/business/picture/delete/{picture}',[Controllers\PhotoController::class, 'deletePictureFromBusiness']);

    //Work Days
    Route::patch('/business/workday/edit/{workday}', [Controllers\WorkDayController::class, 'update']);
    Route::patch('/business/workday/setDayOff/{workday}', [Controllers\WorkDayController::class, 'setDayOff']);
    Route::post('/business/workday/customDayOff', [Controllers\WorkDayController::class, 'customDayOff']);
    Route::get('/business/workday/customDayOff/get', [Controllers\WorkDayController::class, 'getCustomDaysOff']);
    Route::delete('/business/workday/customDayOff/delete/{customDayOff}', [Controllers\WorkDayController::class, 'deleteCustomDayOff']);
    Route::get('/business/workday/schedule', [Controllers\WorkDayController::class, 'getSchedule']);
    Route::get('/business/workday/schedule/twoWeeks', [Controllers\WorkDayController::class, 'getTwoWeekSchedule']);



});
