<?php

// Pivot table
Route::post('insert/unitInCalories', 'Menu\FoodsController@insertUnitInCalories');
Route::post('delete/unitFromCalories', 'Menu\FoodsController@deleteUnitFromCalories');
Route::post('update/calories', 'Menu\FoodsController@updateCalories');

//Resource
Route::resource('foods', 'Menu\FoodsController', ['only' => ['index', 'show', 'store', 'update', 'destroy']]);