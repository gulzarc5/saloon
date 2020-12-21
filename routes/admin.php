<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Admin'],function(){
    Route::get('/admin/login','LoginController@index')->name('admin.login_form');
    Route::post('login', 'LoginController@adminLogin');

    Route::group(['middleware'=>'auth:admin','prefix'=>'admin'],function(){
        Route::get('/dashboard', 'DashboardController@dashboardView')->name('admin.deshboard');
        Route::post('logout', 'LoginController@logout')->name('admin.logout');

        // Change Password
        Route::get('/change/password/form', 'LoginController@changePasswordForm')->name('admin.change_password_form');
        Route::post('/change/password', 'LoginController@changePassword')->name('admin.change_password');

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
        });
        // Users
        Route::group(['namespace' => 'User'], function () {
            Route::get('/customer/list', 'UsersController@customerList')->name('admin.customer_list');
            Route::get('/customer/list/ajax', 'UsersController@customerListAjax')->name('admin.customer_list_ajax');
            Route::get('/customer/edit/{id}', 'UsersController@customerEdit')->name('admin.customer_edit');
            Route::put('/customer/update/{id}', 'UsersController@customerUpdate')->name('admin.customer_update');
            Route::get('/customer/status/update/{id}/{status}', 'UsersController@updateCustomerStatus')->name('admin.customer_status_update');
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
        });

        // Orders
        Route::group(['namespace' => 'Order','prefix'=>'order'], function () {
            Route::get('/list', 'OrdersController@index')->name('admin.orders');
            Route::get('/details/{order_id}', 'OrdersController@orderDetails')->name('admin.order_details');
            Route::get('accept/{order_id}/{status}','OrdersController@acceptOrder')->name('admin.order_accept');
            Route::get('/cancel/{order_id}/{account_id}', 'OrdersController@orderCancel')->name('admin.order_cancel');

            Route::get('/refund', 'OrdersController@refund')->name('admin.refunds');
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

            Route::get('/city', 'ConfigurationController@city')->name('admin.city');
            Route::get('/city/list/ajax', 'ConfigurationController@cityListAjax')->name('admin.city_list_ajax');
            Route::get('/city/add', 'ConfigurationController@addCity')->name('admin.add_city');
            Route::post('/city/insert', 'ConfigurationController@insertCity')->name('admin.insert_city');
            Route::get('/city/edit/{id}', 'ConfigurationController@editCity')->name('admin.edit_city');
            Route::put('/city/update/{id}', 'ConfigurationController@updateCity')->name('admin.update_city');
            Route::get('/city/update/status/{id}/{status}', 'ConfigurationController@updateStatusCity')->name('admin.update_status_city');
            Route::get('/city/list/byState/{state_id}', 'ConfigurationController@cityListByState')->name('admin.city_list_by_state');


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

        
    });
});
