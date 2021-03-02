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

Route::group(['namespace' => 'Api'], function () {
    // Send otp API by Saddam
    Route::post('send/otp', 'CustomerController@sendOtp');
    Route::post('otp/verify', 'CustomerController@customerOtpVerify');
    Route::get('service/city/list', 'AppSettingController@serviceCity');
    Route::get('app/loade/api', 'AppSettingController@AppLoadApi');
    Route::get('service/list/category', 'AppSettingController@serviceList');

    Route::get('/client/forgot/otp/send/{mobile}', 'ClientsController@forgotOtp');
    Route::post('client/forgot/password/change', 'ClientsController@forgotPasswordChange');

    Route::group(['prefix' => 'service'], function () {
        Route::post('list', 'ServiceController@serviceList');
        Route::get('details/{service_id}', 'ServiceController@serviceDetails');
        Route::get('search/{search_key}/{page}', 'ServiceController@serviceSearch');
    });

    //Customer Section
    Route::group(['middleware' => 'auth:customerApi', 'prefix' => 'customer'], function () {
        Route::group(['prefix' => 'registration'], function () {
            Route::post('update/details', 'CustomerController@updateDetailsRegistration');
            Route::post('address', 'CustomerController@updateAddressRegistration');
        });
        Route::group(['prefix' => 'profile'], function () {
            Route::get('/', 'CustomerController@profileFetch');
            Route::post('update', 'CustomerController@profileUpdate');
        });
        Route::put('password/change/{id}', 'CustomerController@passwordChange');
        Route::post('order/place', 'OrderController@orderPlace');
        Route::post('payment/verify', 'OrderController@paymentVerify');
        Route::get('order/history/{user_id}', 'CustomerController@orderHistory');
        Route::post('order/cancel', 'CustomerController@orderCancel');

        //For Push Notification
        Route::get('update/firebase_token/{id}/{token}', 'CustomerController@updateFirebaseToken');

        Route::group(['prefix' => 'bank'], function () {
            Route::group(['prefix' => 'info'], function () {
                Route::post('add', 'CustomerController@bankInfoInsert');
                Route::get('list', 'CustomerController@bankInfoList');
                Route::get('fetch/{bank_info_id}', 'CustomerController@bankInfoFetch');
                Route::put('update/{bank_info_id}', 'CustomerController@bankInfoUpdate');
            });
        });

        Route::group(['prefix' => 'address'], function () {
            Route::get('list', 'AddressController@addressList');
            Route::post('add', 'AddressController@addAddress');
            Route::get('fetch/{id}', 'AddressController@addressFetch');
            Route::put('update/{id}', 'AddressController@addressUpdate');
        });

        Route::group(['prefix' => 'service/review'], function () {
            Route::post('insert', 'ServiceController@insertReview');
        });
    });

    // Client Regitration
    Route::post('client/registration', 'ClientsController@clientRegistration');
    Route::post('client/login', 'ClientsController@clientLogin');
    Route::group(['middleware' => ['auth:clientApi']], function () {
        Route::get('client/profile/{id}', 'ClientsController@clientProfile');
        Route::post('client/profile/update', 'ClientsController@clientProfileUpdate');

        //For Push Notification
        Route::get('client/update/firebase_token/{id}/{token}', 'ClientsController@updateFirebaseToken');

        Route::get('client/service/list/{client_id}', 'JobController@clientServiceList');
        Route::get('client/service/edit/{service_list_id}', 'JobController@clientServiceEdit');
        Route::put('client/service/update/{service_list_id}', 'JobController@clientServiceUpdate');
        Route::post('client/service/add', 'JobController@clientServiceAdd');
        Route::get('client/service/status/update/{service_id}/{status}', 'JobController@clientServiceStatusUpdate');
        Route::post('client/schedule/update', 'ClientsController@clientScheduleUpdate');

        Route::post('client/gallery/image/add', 'ClientsController@galleryImageAdd');
        Route::get('client/gallery/image/delete/{client_id}/{image_id}', 'ClientsController@galleryImageDelete');
        Route::get('client/gallery/image/set/thumb/{client_id}/{image_id}', 'ClientsController@galleryImageSetThumb');

        Route::put('client/change/password/{client_id}', 'ClientsController@clientChangePassword');

        Route::get('client/order/history/{client_id}', 'ClientsController@orderHistory');

        Route::post('client/order/status', 'ClientsController@orderStatus');
    });
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
