<?php

use App\Models\AppointmentNoCustomer;
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

    //
    Route::post('/logout', [Controllers\Authcontroller::class, 'logout']);
    Route::patch('/edit/email_phone', [Controllers\UserController::class, 'editEmailPhone']);
    Route::patch('/edit/password', [Controllers\UserController::class, 'editPassword']);

    Route::get('/categories', [Controllers\CategoryController::class, 'index']);

    //Customer
    Route::get('/customer/profile', [Controllers\CustomerController::class, 'getProfile']);
    Route::patch('/customer/profile/edit', [Controllers\CustomerController::class, 'editProfile']);
    Route::post('/customer/favorites/{business}', [Controllers\CustomerController::class, 'add_delete_FavoritePlace']);
    Route::get('/customer/favorites/getAll', [Controllers\CustomerController::class, 'getFavoriteBusinesses']);
    Route::post('/customer/appointments/create/{business}', [Controllers\CustomerController::class, 'createAppointment']);
    Route::get('/customer/appointments/getAll',  [Controllers\CustomerController::class, 'getAllAppointments']);
    Route::patch('/customer/appointments/cancel/{appointment}',  [Controllers\CustomerController::class, 'cancelAppointment']);
    Route::post('/customer/appointments/rate/{appointment}', [Controllers\RatingController::class, 'leaveRate']);

    // Customer Getting Business INFO
    Route::get('/getBusiness/{business}',[Controllers\BusinessController::class,'getBusiness']);
    Route::get('/getBusinessAddress/{business}', [Controllers\AddressController::class, 'getBusinessAddress']);
    Route::get('/getBusinessPictures/{business}', [Controllers\PhotoController::class, 'getPictureByBusiness']);
    Route::get('/getBusinessCategories/{business}', [Controllers\CategoryController::class, 'getCategoriesByBusiness']);
    Route::get('/getBusinessSchedule/{business}', [Controllers\WorkDayController::class, 'getScheduleByBusiness']);
    Route::get('/getAllBusinessServiceCategories/{business}', [Controllers\ServiceController::class, 'getAllServiceCategoryByBusiness']);
    Route::get('/getBusinessRating/{business}', [Controllers\RatingController::class, 'getBusinessRatingAndComments']);
    Route::get('/getAllBusinesses',[Controllers\BusinessController::class,'getAllBusinesses']);
    Route::get('getTwoWeekSchedule/{business}', [Controllers\WorkDayController::class, 'getTwoWeekSchedule']);
    Route::get('/getBusinessHoursForDay/{business}',[Controllers\WorkDayController::class, 'getBusinessHoursForDay']);

    //-------------------Business*

    //Profile
    Route::get('/business/profile/', [Controllers\BusinessController::class, 'getProfile']);
    Route::patch('/business/profile/edit', [Controllers\BusinessController::class, 'editProfile']);


    //Categories
    Route::get('/business/categories/getAll', [Controllers\CategoryController::class, 'getBusinessCategories']);
    Route::post('/business/categories/set', [Controllers\CategoryController::class, 'setCategoryToBusiness']);
    Route::patch('/business/categories/remove', [Controllers\CategoryController::class, 'deleteCategoryFromBusiness']);

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
    Route::patch('/business/services/disable/{service}', [Controllers\ServiceController::class, 'disableService']);
    Route::get('/getAllServices', [App\Http\Controllers\ServiceController::class, 'getAllServices']);
    //Group Appointment
    Route::post('/business/group_appointment/create', [Controllers\AppointmentController::class, 'createGroupAppointment']);
    Route::patch('/business/group_appointment/add_clients/{groupAppointment}', [Controllers\AppointmentController::class, 'addClientsToGroupAppointment']);
    Route::patch('/business/group_appointment/remove_clients/{groupAppointment}', [Controllers\AppointmentController::class, 'removeClientsFromGroupAppointment']);
    Route::patch('/customer/group_appointment/signup/{groupAppointment}', [Controllers\CustomerController::class, 'clientSignUpForGroupAppointment']);
    Route::patch('/customer/group_appointment/cancel/{groupAppointment}', [Controllers\CustomerController::class, 'customerCancelGroupAppointment']);

    //Business Appointment
    Route::post('/business/appointments/create/', [Controllers\AppointmentController::class, 'createAppointment']);
    Route::get('/business/appointments/getAll',  [Controllers\AppointmentController::class, 'getAllAppointments']);
    Route::delete('/business/appointments/delete/{appointment}',  [Controllers\AppointmentController::class, 'deleteAppointmentNoCustomer']);
    Route::patch('/business/appointments/cancel/{appointment}',  [Controllers\AppointmentController::class, 'cancelAppointment']);
    Route::patch('/business/appointments/edit/{appointment}',  [Controllers\AppointmentController::class, 'editAppointmentNoCustomer']);

    //Appointments
    Route::get('/appointments/get/{appointment}',  [Controllers\AppointmentController::class, 'getAppointment']);

    //Ratings
    Route::get('/business/ratings/getAll', [Controllers\RatingController::class, 'businessGetsHisRatesAndComments']);

});
