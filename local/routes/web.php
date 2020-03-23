<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::group(['middleware' => 'ChkLogin'], function(){
    Route::get('/','DashboardController@index');
});

Route::middleware(['auth'])->group(function () {
    Route::group(['middleware' => 'ChkLogin'], function(){
        //Dashboard
        Route::get('/dashboard','DashboardController@index');

        //Productimport
        Route::resource('productimport','ProductImportController');
        Route::get('deleteshipment/{id}','ProductImportController@delete');
        
        //Box
        Route::resource('box','BoxController');
        Route::post('calculate/{id}/{type}/{qty}','BoxController@calculate');
        Route::post('/autoload2','BoxController@autoload2');
        Route::post('/ShowPreParing','BoxController@ShowPreParing');
        Route::get('deletepallet/{id}/{qty}/{box}/{key}','BoxController@deletepallet');
        Route::post('loadpalletman','BoxController@loadpalletman');
        Route::get('/showpallet','BoxController@showpallet');
        Route::get('/showtable/{item}/{id}','BoxController@showtable');
        Route::get('changelock/{id}','BoxController@changelock');
        Route::get('changeunlock/{id}','BoxController@changeunlock');
        Route::get('palletmanage/{id}','BoxController@PalletManage');
        Route::get('editpallet/{id}/{pid}','BoxController@EditPallet');
        Route::post('condition_load','BoxController@condition_load');
        Route::get('select_layer/{id}','BoxController@select_layer');
        // Route::post('loadlayer','BoxController@loadlayer');

        
        //Palate
        Route::resource('palate','PalateController');
        Route::post('PalletOverview','PalateController@palletoverview');
       

        //Item
        Route::resource('item','ItemsController');
        Route::post('CreateSubItem/{id}','ItemsController@createsubitem');
        Route::get('SearchItem/{id}','ItemsController@searchitem');
        Route::get('importmaster','ItemsController@importmaster');
        Route::get('loadmaster','ItemsController@loadmaster');
        


        //Subitem
        Route::resource('subitem','SubItemsController');


        //Master
        Route::resource('master','MasterController');
        Route::post('FindItems/{id}','MasterController@finditem');

        //AutoLoading
        Route::resource('autoload','AutoLoadingController');
        Route::post('select_box','AutoLoadingController@select_box');
        Route::post('load_container','AutoLoadingController@load_container');
        Route::post('delete_container','AutoLoadingController@delete_container');
        
        //ManualLoading
        Route::resource('manualload','ManualLoadingController');
        Route::get('show_pallet/{id}','ManualLoadingController@show');
        Route::post('delete_pallet','ManualLoadingController@delete_pallet');
        Route::post('delete_box','ManualLoadingController@delete_box');
        Route::post('change_box','ManualLoadingController@change_box');

        //OutStandardLoading
        Route::resource('outstandardload','OutStandardLoadingController');

        //Container
        Route::resource('container','ContainerController');
        Route::get('ContainerLoad/{id}/{type}','ContainerController@LoadContianer');
        Route::get('ShowCustomer','MasterController@managercustomer');
        Route::post('CreateCustomer','MasterController@createcustomer');
        Route::post('DelCustomer/{id}','MasterController@delcustomer');
        Route::post('EditCustomer/{id}','MasterController@editcustomer');
        Route::put('UpdateCustomer/{id}/','MasterController@updatecustomer');

        //OutStandardLoading
        Route::resource('report','ReportController');
        Route::get('reportcontainer/{id}','ReportController@report')->name('reportcontainer');

    });
});

//member
// Route::post('checklogin','auth\LoginController@checklogin')->name('login2');
Route::post('regismember','auth\RegisterController@store')->name('regismember');



Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');
