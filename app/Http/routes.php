<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'HomeController@index');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

// ====================================================================================
// ========================================ajax========================================
// ====================================================================================

// ========================================select========================================

Route::post('select/pageLoad', 'SelectController@pageLoad');
Route::post('select/entries', 'SelectController@entries');
Route::post('select/foodInfo', 'SelectController@foodInfo');
// Route::post('select/foodEntries', 'SelectController@foodEntries');
// Route::post('select/exercises', 'SelectController@exercises');
// Route::post('select/foodList', 'SelectController@foodList');
// Route::post('select/exerciseList', 'SelectController@exerciseList');
Route::post('select/recipeList', 'SelectController@recipeList');
Route::post('select/unitList', 'SelectController@unitList');
Route::post('select/allFoodsWithUnits', 'SelectController@allFoodsWithUnits');
Route::post('select/weight', 'SelectController@weight');
Route::post('select/recipeContents', 'SelectController@recipeContents');
Route::post('select/autocompleteExercise', 'SelectController@autocompleteExercise');
Route::post('select/autocompleteFood', 'SelectController@autocompleteFood');

// ========================================insert========================================

Route::post('insert/item', 'InsertController@item');
Route::post('insert/food', 'InsertController@food');
Route::post('insert/recipe', 'InsertController@recipe');
Route::post('insert/exercise', 'InsertController@exercise');
Route::post('insert/exerciseUnit', 'InsertController@exerciseUnit');
Route::post('insert/unitInCalories', 'InsertController@unitInCalories');
Route::post('insert/foodUnit', 'InsertController@foodUnit');
Route::post('insert/menuEntry', 'InsertController@menuEntry');
Route::post('insert/exerciseEntry', 'InsertController@exerciseEntry');
Route::post('insert/weight', 'InsertController@weight');
Route::post('insert/recipeItem', 'InsertController@recipeItem');

// ========================================update========================================

Route::post('update/defaultUnit', 'UpdateController@defaultUnit');
Route::post('update/defaultExerciseUnit', 'UpdateController@defaultExerciseUnit');
Route::post('update/calories', 'UpdateController@calories');

// ========================================delete========================================

// Route::post('delete/item', 'DeleteController@item');
Route::post('delete/recipe', 'DeleteController@recipe');
Route::post('delete/food', 'DeleteController@food');
Route::post('delete/unitFromCalories', 'DeleteController@unitFromCalories');
Route::post('delete/exercise', 'DeleteController@exercise');
Route::post('delete/exerciseUnit', 'DeleteController@exerciseUnit');
Route::post('delete/foodEntry', 'DeleteController@foodEntry');
Route::post('delete/exerciseEntry', 'DeleteController@exerciseEntry');