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
Route::post('/login/forgot_password', [Controllers\Authcontroller::class, 'forgotPassword']);
Route::post('/login/reset_password', [Controllers\Authcontroller::class, 'resetPassword']);
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
    Route::get('/getAllBusinessServiceCategories/{business}', [Controllers\ServiceController::class, 'getAllServiceCategoryByBusiness']);


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

    //Services Category
    Route::post('/business/serviceCategory/create', [Controllers\ServiceController::class, 'createServiceCategory']);
    Route::get('/business/serviceCategory/getAll', [Controllers\ServiceController::class, 'getAllServiceCategory']);
    Route::get('/business/serviceCategory/get/{serviceCategory}', [Controllers\ServiceController::class, 'getServiceCategory']);
    Route::patch('/business/serviceCategory/edit/{serviceCategory}', [Controllers\ServiceController::class, 'editServiceCategory']);
    Route::delete('/business/serviceCategory/delete/{serviceCategory}', [Controllers\ServiceController::class, 'deleteServiceCategory']);

   //Services
    Route::post('/business/services/create', [Controllers\ServiceController::class, 'createService']);
    Route::patch('/business/services/edit/{service}', [Controllers\ServiceController::class, 'editService']);
    Route::patch('/business/services/move/{service}', [Controllers\ServiceController::class, 'moveServiceToNewCategory']);
    Route::delete('/business/services/delete/{service}', [Controllers\ServiceController::class, 'deleteService']);

    //GroupServices
    Route::post('/business/group_services/create', [Controllers\ServiceController::class, 'createGroupService']);
    Route::patch('/business/group_services/edit/{service}', [Controllers\ServiceController::class, 'editGroupService']);
    Route::patch('/business/group_services/move/{service}', [Controllers\ServiceController::class, 'moveGroupServiceToNewCategory']);
    Route::delete('/business/group_services/delete/{service}', [Controllers\ServiceController::class, 'deleteGroupService']);


});
