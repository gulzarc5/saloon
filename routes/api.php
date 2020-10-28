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
    Route::get('send/otp/{mobile}','CustomerController@sendOtp');
    Route::post('customer/registration','CustomerController@customerRegistration');

    // Freelancer Regitration
    Route::post('freelancer/registration', 'FreelancerController@freelancerRegistration');
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
