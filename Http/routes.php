<?php

Route::group([
    'middleware' => 'admin',
    'prefix' => 'pos',
    'namespace' => 'Modules\Pos\Http\Controllers'
], function () {
    Route::get('/', 'PosController@index');
    Route::get('/best-sellers', 'PosController@best');
    Route::get('/customers', 'PosController@customers');
    Route::post('/customer', 'PosController@addCustomer');
    Route::post('/sale', 'PosController@sale');
    Route::get('/currencies', 'PosController@currencies');
});
