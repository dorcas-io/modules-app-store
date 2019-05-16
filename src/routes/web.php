<?php

Route::group(['namespace' => 'Dorcas\ModulesAppStore\Http\Controllers', 'middleware' => ['web', 'auth'], 'prefix' => 'map'], function() {
    Route::get('app-store-main', 'ModulesAppStoreController@index')->name('app-store-main');
    Route::get('/app-store', 'AppStore\AppStore@search');
    Route::post('/app-store/{id}', 'AppStore\AppStore@installApp');
    Route::delete('/app-store/{id}', 'AppStore\AppStore@uninstallApp');
});


Route::group(['middleware' => ['auth'], 'namespace' => 'Ajax'], function () {
    


});

?>