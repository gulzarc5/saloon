<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Admin'],function(){
    Route::get('/','LoginController@index')->name('admin.login_form');
    Route::post('login', 'LoginController@adminLogin');

    Route::group(['middleware'=>'auth:admin','prefix'=>'admin'],function(){
        Route::get('/dashboard', 'DashboardController@dashboardView')->name('admin.deshboard');
        Route::post('logout', 'LoginController@logout')->name('admin.logout');

        // Change Password
        Route::get('/change/password/form', 'LoginController@changePasswordForm')->name('admin.change_password_form');
        Route::post('/change/password', 'LoginController@changePassword')->name('admin.change_password');

        Route::group(['prefix' =>'coupon'],function(){
            Route::get('list','CouponController@couponList')->name('admin.coupon_list');
            Route::get('edit/{coupon_id}','CouponController@couponEdit')->name('admin.coupon_edit');
            Route::put('update/{coupon_id}','CouponController@couponUpdate')->name('admin.coupon_update');
        });

        Route::group(['prefix' =>'offer'],function(){
            Route::get('list','OfferController@offerList')->name('admin.offer_list');
            Route::get('add/form/','OfferController@addOfferForm')->name('admin.offer_add_form');
            Route::post('insert','OfferController@insertOffer')->name('admin.offer_insert');
            
            Route::get('edit/form/{offer_id}','OfferController@editOfferForm')->name('admin.offer_edit_form');
            Route::post('update','OfferController@updateOffer')->name('admin.offer_update');


            Route::get('edit/salons/{offer_id}','OfferController@editSalon')->name('admin.offer_edit_salon');
            Route::get('add/salons/{offer_id}','OfferController@addSalon')->name('admin.offer_add_salon');
            Route::get('salon/data/fetch/{mobile}','OfferController@salonDataFetch');
            Route::post('insert/salon','OfferController@insertOfferSalon')->name('admin.insert_offer_salon');
            Route::get('salon/remove/{offer_salon_id}','OfferController@removeOfferSalon')->name('admin.remove_offer_salon');
            Route::get('status/{id}','OfferController@offerStatusUpdate')->name('admin.offer_status_update');
        });
        Route::group(['prefix' =>'admin/commission'],function(){
            Route::get('list','AdminCommissionController@commissionList')->name('admin.admin_commission_list');
            Route::get('edit/{commission_id}','AdminCommissionController@commissionEdit')->name('admin.admin_commission_edit');
            Route::put('update/{commission_id}','AdminCommissionController@commissionUpdate')->name('admin.admin_commission_update');
        });

        //Password Request
        Route::get('/password/request', 'DashboardController@passwordRequest')->name('admin.password_request');
        Route::get('/password/request/ajax', 'DashboardController@passwordRequestAjax')->name('admin.password_request_ajax');

        Route::get('user/change/password/form/{user_id}/{user_type}/{request_id}', 'DashboardController@changePasswordForm')->name('admin.user_change_password_form');
        Route::post('user/change/password', 'DashboardController@changePassword')->name('admin.user_change_password');

        Route::group(['prefix' =>'client'],function(){
            Route::get('/freelancer/list', 'ClientController@freelancerList')->name('admin.freelancer_list');
            Route::get('/freelancer/list/ajax', 'ClientController@freelancerListAjax')->name('admin.freelancer_list_ajax');

            Route::get('/shop', 'ClientController@shop')->name('admin.shop_list');
            Route::get('/shop/list/ajax', 'ClientController@shopListAjax')->name('admin.shop_list_ajax');

            Route::get('/details/{client_id}', 'ClientController@clientDetails')->name('admin.client_details');
            Route::get('/edit/{client_id}', 'ClientController@clientEdit')->name('admin.client_edit');
            Route::put('/update/{client_id}', 'ClientController@clientUpdate')->name('admin.client_update');
            Route::get('/images/{client_id}', 'ClientController@clientImages')->name('admin.client_images');

            Route::get('/images/cover/{client_id}/{image_id}', 'ClientController@clientImagesCover')->name('admin.client_images_cover');
            Route::get('/images/delete/{image_id}', 'ClientController@clientImagesDelete')->name('admin.client_images_delete');

            Route::get('/services/edit/{client_id}', 'ClientController@clientServicesEdit')->name('admin.client_services_edit');
            Route::put('/services/update/{client_id}', 'ClientController@clientServicesUpdate')->name('admin.client_services_update');

            Route::get('status/update/{id}/{status}', 'ClientController@updateClientStatus')->name('admin.client_status_update');
            Route::get('verify/status/update/{id}/{status}', 'ClientController@updateClientVerifyStatus')->name('admin.client_verify_status_update');
        });
        // Users
        Route::group(['namespace' => 'User'], function () {
            Route::get('/customer/list', 'UsersController@customerList')->name('admin.customer_list');
            Route::get('/customer/list/ajax', 'UsersController@customerListAjax')->name('admin.customer_list_ajax');
            Route::get('/customer/edit/{id}', 'UsersController@customerEdit')->name('admin.customer_edit');
            Route::put('/customer/update/{id}', 'UsersController@customerUpdate')->name('admin.customer_update');
            Route::get('/customer/status/update/{id}/{status}', 'UsersController@updateCustomerStatus')->name('admin.customer_status_update');

            Route::get('/wallet/history/{user_id}', 'UsersController@walletHistory')->name('admin.customer_wallet_history');
        });

        // Service Category
        Route::group(['namespace' => 'ServiceCategory'], function () {
            Route::get('/service/category', 'ServiceCategoryController@index')->name('admin.service_category');
            Route::get('/add/service/category', 'ServiceCategoryController@addServiceCategory')->name('admin.add.service_category');
            Route::post('/store/service/category', 'ServiceCategoryController@storeServiceCategory')->name('admin.store_service_category');
            Route::get('/service/category/list', 'ServiceCategoryController@serviceCategory')->name('admin.ajax.service_category');
            Route::get('/service/category/status/{id}/{status}', 'ServiceCategoryController@status')->name('admin.service_category.status');
            Route::get('/service/category/edit/{id}', 'ServiceCategoryController@edit')->name('admin.service_category.edit');
            Route::post('/update/service/category', 'ServiceCategoryController@updateServiceCategory')->name('admin.update_service_category');

            // Sub Category
            Route::group(['prefix' => 'sub'], function () {
                Route::resource('category', 'SubCategoryController');
                Route::get('data', 'SubCategoryController@ajaxData')->name('admin.ajax.subcategory');
            });
            // Third Level Category 
            Route::group(['prefix' => 'third'], function () {
                Route::resource('third/level', 'ThirdLevelCategoryController');
                Route::post('sub/category', 'ThirdLevelCategoryController@fetchSubCategory')->name('fetch_sub_category');
                Route::post('ajax/category', 'ThirdLevelCategoryController@fetchThirdCategoryAjax')->name('admin.fetch_third_category_ajax');
                Route::get('third/category', 'ThirdLevelCategoryController@fetchThirdCategory')->name('admin.ajax.thirdcategory');
                Route::get('status/update/{id}', 'ThirdLevelCategoryController@statusUpdate')->name('admin.third_category_status_update');
                Route::get('category/edit/{id}', 'ThirdLevelCategoryController@categoryEdit')->name('admin.categoryEdit');
                Route::put('category/update/{id}', 'ThirdLevelCategoryController@categoryUpdate')->name('admin.categoryUpdate');
            });
        });
        // Orders
        Route::group(['namespace' => 'Order','prefix'=>'order'], function () {
            Route::get('/list', 'OrdersController@index')->name('admin.orders');

            Route::group(['prefix'=>'vendor'],function () {
                Route::get('cancel/list', 'OrdersController@vendorCancelOrders')->name('admin.vendor_cancel_orders');
                Route::get('change/form/{order_id}', 'OrdersController@vendorChangeForm')->name('admin.vendor_change_form');
                Route::post('check', 'OrdersController@vendorCheck')->name('admin.vendor_check');
                Route::post('change', 'OrdersController@vendorChange')->name('admin.vendor_change');
            });

            Route::get('/details/{order_id}', 'OrdersController@orderDetails')->name('admin.order_details');
            Route::get('accept/{order_id}/{status}','OrdersController@acceptOrder')->name('admin.order_accept');
            Route::get('/cancel/{order_id}/{is_refund}/{account_id?}', 'OrdersController@orderCancel')->name('admin.order_cancel');
            Route::get('/reschedule/{order_id}/{date}', 'OrdersController@orderReSchedule')->name('admin.order_re_schedule');
            Route::get('/search', 'OrdersController@orderSearch')->name('admin.order_search');

        });
        
        Route::group(['prefix'=>'refund'],function(){
            Route::get('/list', 'RefundController@refund')->name('admin.refunds');
            Route::get('/update/{refund_id}', 'RefundController@refundUpdate')->name('admin.refund_update');

        });

        // Configuartion
        Route::group(['namespace' => 'Configuration'], function (){
            Route::get('/state', 'ConfigurationController@state')->name('admin.state');
            Route::get('/state/list/ajax', 'ConfigurationController@stateListAjax')->name('admin.state_list_ajax');
            Route::get('/state/add', 'ConfigurationController@addState')->name('admin.add_state');
            Route::post('/state/insert', 'ConfigurationController@insertState')->name('admin.insert_state');
            Route::get('/state/edit/{id}', 'ConfigurationController@editState')->name('admin.edit_state');
            Route::put('/state/update/{id}', 'ConfigurationController@updateState')->name('admin.update_state');
            Route::get('/state/update/status/{id}/{status}', 'ConfigurationController@updateStatusState')->name('admin.update_status_state');

            Route::group(['prefix' => 'city'], function () {
                Route::get('', 'ConfigurationController@city')->name('admin.city');
                Route::get('list/ajax', 'ConfigurationController@cityListAjax')->name('admin.city_list_ajax');
                Route::get('add', 'ConfigurationController@addCity')->name('admin.add_city');
                Route::post('insert', 'ConfigurationController@insertCity')->name('admin.insert_city');
                Route::get('edit/{id}', 'ConfigurationController@editCity')->name('admin.edit_city');
                Route::put('update/{id}', 'ConfigurationController@updateCity')->name('admin.update_city');
                Route::get('update/status/{id}/{status}', 'ConfigurationController@updateStatusCity')->name('admin.update_status_city');
                Route::get('list/byState/{state_id}', 'ConfigurationController@cityListByState')->name('admin.city_list_by_state');
            });


            Route::group(['prefix' =>'service'],function(){
                Route::get('/city', 'ConfigurationController@serviceCity')->name('admin.serviceCity');
                Route::get('/city/add', 'ConfigurationController@addServiceCity')->name('admin.add_serviceCity');
                Route::post('/city/insert', 'ConfigurationController@insertServiceCity')->name('admin.insert_service_city');
                Route::get('/city/edit/{id}', 'ConfigurationController@editServiceCity')->name('admin.edit_service_city');
                Route::put('/city/update/{id}', 'ConfigurationController@updateServiceCity')->name('admin.update_service_city');
                Route::get('/city/update/status/{id}/{status}', 'ConfigurationController@updateStatusServiceCity')->name('admin.update_status_serviceCity');
            });

            Route::group(['prefix'=>'slider'],function(){
                Route::get('list','SliderController@appSliderList')->name('admin.app_slider_list');
                Route::get('add/form', 'SliderController@appSliderAddForm')->name('admin.app_slider_add_form');
                Route::post('insert/form', 'SliderController@insertAppSlider')->name('admin.insert_app_slider');
                Route::get('delete/{id}', 'SliderController@SliderDelete')->name('admin.slider_delete');
            });

            Route::group(['prefix'=>'setting'],function(){
                Route::get('invoice', 'ConfigurationController@invoiceForm')->name('admin.invoice_form');
                Route::post('invoice/update/', 'ConfigurationController@invoiceUpdate')->name('admin.invoiceUpdate');
            });
        });

        // Enquery
        Route::group(['namespace' => 'Enquery'], function (){
            Route::get('/feedback', 'EnqueryController@feedback')->name('admin.feedback');
            Route::get('/enquery', 'EnqueryController@enquery')->name('admin.enquery');
        });

        Route::group(['prefix'=>'message'],function(){
            Route::get('list','MessageController@list')->name('admin.message_list');
            Route::get('list/ajax','MessageController@listAjax')->name('admin.message_list_ajax');
            Route::get('send/form','MessageController@sendForm')->name('admin.message_send_form');
            Route::post('send','MessageController@sendMessage')->name('admin.message_send');
        });

        Route::group(['prefix' => 'mail'],function(){
            Route::get('list','ContactMailController@index')->name('admin.contact_mail_list');
            Route::get('list/ajax','ContactMailController@indexAjax')->name('admin.contact_mail_list_ajax');
        });
        
    });
});
