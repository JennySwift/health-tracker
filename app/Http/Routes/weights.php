<?php

Route::post('insert/weight', 'Weights\WeightsController@insertOrUpdateWeight');

//Index method, passing the date as a parameter
//Route::get('weights/{date}', ['as' => 'api.weights.index', 'uses' => 'Weights\WeightsController@index']);

Route::resource('weights', 'Weights\WeightsController', ['only' => 'show']);