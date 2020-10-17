<?php

use Illuminate\Http\Request;

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
            Route::get('/customer', 'UsersController@customer')->name('admin.customer');
        });

        // Service Category
        Route::group(['namespace' => 'ServiceCategory'], function () {
            Route::get('/service/category', 'ServiceCategoryController@index')->name('admin.service_category');
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
        });

        // Enquery
        Route::group(['namespace' => 'Enquery'], function (){
            Route::get('/feedback', 'EnqueryController@feedback')->name('admin.feedback');
            Route::get('/enquery', 'EnqueryController@enquery')->name('admin.enquery');
        });
    });
});
