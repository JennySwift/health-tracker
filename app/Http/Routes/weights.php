<?php

Route::post('insert/weight', 'Weights\WeightsController@insertOrUpdateWeight');

Route::resource('weights', 'Weights\WeightsController');