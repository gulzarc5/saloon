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

        // Users
        Route::group(['namespace' => 'User'], function () {
            Route::get('/freelancer', 'UsersController@freelancer')->name('admin.freelancer');
            Route::get('/shop', 'UsersController@shop')->name('admin.shop');

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
        Route::group(['namespace' => 'Order'], function () {
            Route::get('/orders', 'OrdersController@index')->name('admin.orders');
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



        });

        // Enquery
        Route::group(['namespace' => 'Enquery'], function (){
            Route::get('/feedback', 'EnqueryController@feedback')->name('admin.feedback');
            Route::get('/enquery', 'EnqueryController@enquery')->name('admin.enquery');
        });
    });
});
