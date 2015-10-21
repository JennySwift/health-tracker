<?php

Route::post('select/allFoodsWithUnits', 'Foods\FoodsController@getAllFoodsWithUnits');
Route::post('insert/food', 'Foods\FoodsController@insertFood');
Route::post('update/defaultUnit', 'Foods\FoodsController@updateDefaultUnit');

// Pivot table
Route::post('insert/unitInCalories', 'Foods\FoodsController@insertUnitInCalories');
Route::post('delete/unitFromCalories', 'Foods\FoodsController@deleteUnitFromCalories');
Route::post('update/calories', 'Foods\FoodsController@updateCalories');

//Resource
Route::resource('foods', 'Foods\FoodsController', ['only' => ['show', 'destroy']]);