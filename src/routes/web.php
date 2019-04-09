<?php

Route::group(['namespace' => 'Dorcas\ModulesAppStore\Http\Controllers', 'middleware' => ['web']], function() {
    Route::get('sales', 'ModulesAppStoreController@index')->name('sales');
});


Route::group(['middleware' => ['auth'], 'prefix' => 'app-store', 'namespace' => 'AppStore'], function () {
    Route::get('/', 'Listing@index')->name('app-store');
    Route::get('/installed', 'Installed@index')->name('app-store.installed');
});

?>