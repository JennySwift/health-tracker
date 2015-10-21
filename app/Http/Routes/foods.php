<?php

Route::post('select/allFoodsWithUnits', 'Foods\FoodsController@getAllFoodsWithUnits');
Route::post('insert/menuEntry', 'Foods\FoodEntriesController@insertMenuEntry');
Route::post('insert/food', 'Foods\FoodsController@insertFood');
Route::post('insert/unitInCalories', 'Foods\FoodsController@insertUnitInCalories');
Route::post('delete/unitFromCalories', 'Foods\FoodsController@deleteUnitFromCalories');
Route::post('delete/foodEntry', 'Foods\FoodEntriesController@deleteFoodEntry');
Route::post('update/defaultUnit', 'Foods\FoodsController@updateDefaultUnit');
Route::post('update/calories', 'Foods\FoodsController@updateCalories');
Route::post('insert/foodUnit', 'Units\UnitsController@insertFoodUnit');
Route::post('delete/foodUnit', 'Units\UnitsController@deleteFoodUnit');

Route::resource('foods', 'Foods\FoodsController', ['only' => ['show', 'destroy']]);