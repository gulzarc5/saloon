<?php

use Illuminate\Http\Request;

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
    Route::get('/signup/otp/send/{mobile}', 'CustomerController@signUpOtp');
    Route::get('signUp/otp/verify/{mobile}/{otp}', 'CustomerController@signUpOtpVerify');

    Route::post('customer/registration','CustomerController@customerRegistration');
    Route::post('customer/login','CustomerController@customerLogin');

    Route::get('/customer/forgot/otp/send/{mobile}', 'CustomerController@forgotOtp');
    Route::post('customer/forgot/password/change', 'CustomerController@forgotPasswordChange');

    Route::group(['middleware'=>'auth:customerApi','prefix' =>'customer'], function () {
        Route::get('profile/{id}','CustomerController@profileFetch');
        Route::put('profile/update/{id}','CustomerController@profileUpdate');
        Route::put('password/change/{id}','CustomerController@passwordChange');
    });
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
