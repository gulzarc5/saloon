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
  
    Route::get('service/city/list', 'AppSettingController@serviceCity');
    Route::post('app/loade/api', 'AppSettingController@AppLoadApi');
    Route::get('service/list/category', 'AppSettingController@serviceList');
    Route::get('sub/category/{main_category_id}', 'AppSettingController@subCategoryList');
    Route::get('third/category/{sub_category_id}', 'AppSettingController@thirdCategoryList');

    Route::get('/client/forgot/otp/send/{mobile}', 'ClientsController@forgotOtp');
    Route::post('client/forgot/password/change', 'ClientsController@forgotPasswordChange');

    Route::group(['prefix' => 'service'], function () {
        Route::post('list', 'ServiceController@serviceList');
        Route::get('details/{service_id}', 'ServiceController@serviceDetails');
        Route::post('search', 'ServiceController@serviceSearch');
    });

    Route::group(['prefix'=>'combo'],function(){
        Route::post('view/all','ComboController@comboViewAll');
    });
    Route::group(['prefix'=>'deals'],function(){
        Route::post('view/all','ClientDealController@dealsViewAll');
    });
    Route::group(['prefix'=>'top'],function(){
        Route::post('freelancer/view/all','AppSettingController@freelancerViewAll');
        Route::post('salon/view/all','AppSettingController@salonViewAll');
    });






    //////////////////////////////////////////Customer Section
    Route::post('send/otp', 'CustomerController@sendOtp');
    Route::post('otp/verify', 'CustomerController@customerOtpVerify');
    Route::post('customer/registration/update/details', 'CustomerController@updateDetailsRegistration');
    Route::group(['middleware' => 'auth:customerApi', 'prefix' => 'customer'], function () {

        Route::group(['prefix' => 'offer'],function(){
            Route::get('list','OfferController@index');
            Route::post('check','OfferController@offerCheck')->middleware('auth:customerApi');
            Route::post('coupon/check','OfferController@couponCheck')->middleware('auth:customerApi');
        });

        Route::group(['prefix' => 'registration'], function () {
            Route::post('address', 'CustomerController@updateAddressRegistration');
        });

        Route::group(['prefix' => 'profile'], function () {
            Route::get('/{id}', 'CustomerController@profileFetch');
            Route::post('update', 'CustomerController@profileUpdate');
        });

        Route::group(['prefix' => 'order'],function () {
            Route::post('place', 'OrderController@orderPlace');
            Route::get('history', 'CustomerController@orderHistory');
            Route::post('cancel', 'CustomerController@orderCancel');
            Route::get('vendor/cancel/accept/reject/{order_id}/{status}', 'CustomerController@orderVendorCancelAcceptReject');
        });

        Route::group(['prefix' =>'wish'],function(){
            Route::get('list/add/{client_id}','WishListController@add');
            Route::get('list/getData','WishListController@list');
            Route::get('list/remove/{wish_list_id}','WishListController@remove');
        });

        Route::put('password/change/{id}', 'CustomerController@passwordChange');
        Route::post('payment/verify', 'OrderController@paymentVerify');

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

        Route::group(['prefix' => 'wallet'],function () {
            Route::get('amount/check','CustomerWalletController@walletFetch');
            Route::get('history','CustomerWalletController@walletHistory');
        });

        Route::group(['prefix' => 'message'],function(){
            Route::get('list', 'MessageController@customerMesseges');
        });
    });

    // Client Regitration
    Route::post('client/registration', 'ClientsController@clientRegistration');
    Route::post('client/login', 'ClientsController@clientLogin');
    Route::group(['middleware' => ['auth:clientApi'],'prefix'=>'client'], function () {
        Route::get('profile/{id}', 'ClientsController@clientProfile');
        Route::post('profile/update', 'ClientsController@clientProfileUpdate');

        //For Push Notification
        Route::get('update/firebase_token/{id}/{token}', 'ClientsController@updateFirebaseToken');

        Route::group(['prefix'=>'service'],function(){
            Route::get('list/{client_id}', 'JobController@clientServiceList');
            Route::get('edit/{service_list_id}', 'JobController@clientServiceEdit');
            Route::put('update/{service_list_id}', 'JobController@clientServiceUpdate');
            Route::post('add', 'JobController@clientServiceAdd');
            Route::get('status/update/{service_id}/{status}', 'JobController@clientServiceStatusUpdate');

            Route::group(['prefix'=>'deal'],function(){
                Route::put('add/{service_id}','ClientDealController@add');
                Route::get('remove/{service_id}','ClientDealController@remove');
                Route::get('list/{client_id}','ClientDealController@list');
            });
            Route::group(['prefix'=>'combo'],function(){
                Route::post('add/update','ComboController@add');
                Route::get('list','ComboController@list');
            });
            Route::get('status/update/{service_id}/{status}', 'JobController@clientServiceStatusUpdate');
        });

        Route::post('schedule/update', 'ClientsController@clientScheduleUpdate');

        Route::post('gallery/image/add', 'ClientsController@galleryImageAdd');
        Route::get('gallery/image/delete/{client_id}/{image_id}', 'ClientsController@galleryImageDelete');
        Route::get('gallery/image/set/thumb/{client_id}/{image_id}', 'ClientsController@galleryImageSetThumb');

        Route::put('change/password/{client_id}', 'ClientsController@clientChangePassword');

        Route::group(['prefix' => 'order'],function(){
            Route::get('history', 'ClientsController@orderHistory');
            Route::post('status', 'ClientsController@orderStatus');
            Route::post('reschedule', 'ClientsController@orderReschedule');
        });

        Route::group(['prefix' => 'message'],function(){
            Route::get('list', 'MessageController@clientMesseges');
        });

    });
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
