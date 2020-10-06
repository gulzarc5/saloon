<?php

use Illuminate\Http\Request;

Route::group(['namespace' => 'Admin'],function(){
    Route::get('/admin/login','LoginController@index')->name('admin.login_form');    
    Route::post('login', 'LoginController@adminLogin');

    Route::group(['middleware'=>'auth:admin','prefix'=>'admin'],function(){
        Route::get('/dashboard', 'DashboardController@dashboardView')->name('admin.deshboard'); 
        Route::post('logout', 'LoginController@logout')->name('admin.logout');
    });
});