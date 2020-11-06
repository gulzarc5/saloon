<?php

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
Route::group(['namespace'=>'Api'], function(){
    Route::get('/signup/otp/send/{mobile}/{user_type}', 'CustomerController@signUpOtp');
    Route::get('signUp/otp/verify/{mobile}/{otp}/{user_type}', 'CustomerController@signUpOtpVerify');
    Route::get('service/city/list', 'AppSettingController@serviceCity');
    Route::get('app/loade/api', 'AppSettingController@AppLoadApi');

    // Customer
    Route::post('customer/registration','CustomerController@customerRegistration');
    Route::post('customer/login','CustomerController@customerLogin');
    Route::get('/customer/forgot/otp/send/{mobile}', 'CustomerController@forgotOtp');
    Route::post('customer/forgot/password/change', 'CustomerController@forgotPasswordChange');

    Route::group(['middleware'=>'auth:customerApi','prefix' =>'customer'], function () {
        Route::get('profile/{id}','CustomerController@profileFetch');
        Route::put('profile/update/{id}','CustomerController@profileUpdate');
        Route::put('password/change/{id}','CustomerController@passwordChange');
    });

    // Client Regitration
    Route::post('client/registration', 'ClientsController@clientRegistration');
    Route::post('client/login', 'ClientsController@clientLogin');
    Route::group(['middleware' => 'auth:clientApi'], function () {
        Route::get('client/profile/{id}', 'ClientsController@clientProfile');
        Route::put('client/profile/update/{id}', 'ClientsController@clientProfileUpdate');

        Route::put('client/service/add/{client_id}','JobController@clientServiceAdd');
        Route::get('client/service/status/update/{service_id}/{status}','JobController@clientServiceStatusUpdate');
        Route::put('client/service/schedule/update/{service_id}','JobController@clientServiceScheduleUpdate');

        Route::put('client/gallery/image/add/{client_id}','ClientsController@galleryImageAdd');
        Route::get('client/gallery/image/delete/{client_id}/{image_id}','ClientsController@galleryImageDelete');
    });
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
