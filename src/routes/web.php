<?php

Route::group(['namespace' => 'Dorcas\ModulesAppStore\Http\Controllers', 'middleware' => ['web', 'auth'], 'prefix' => 'map'], function() {
    Route::get('app-store-main', 'ModulesAppStoreController@index')->name('app-store-main');
    Route::get('/app-store', 'ModulesAppStoreController@search');
    Route::post('/app-store/{id}', 'ModulesAppStoreController@installApp');
    Route::delete('/app-store/{id}', 'ModulesAppStoreController@uninstallApp');
});

?>