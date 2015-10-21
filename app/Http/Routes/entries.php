<?php

Route::post('select/entries', 'EntriesController@getEntries');

Route::post('insert/menuEntry', 'Foods\FoodEntriesController@insertMenuEntry');
Route::post('delete/foodEntry', 'Foods\FoodEntriesController@deleteFoodEntry');

Route::post('insert/recipeEntry', 'Recipes\RecipesController@insertRecipeEntry');
Route::post('delete/recipeEntry', 'Recipes\RecipesController@deleteRecipeEntry');

/**
 * @VP:
 * How to send the date for the index method?
 */
//Route::resource('entries', 'EntriesController', ['only' => ['index']]);
