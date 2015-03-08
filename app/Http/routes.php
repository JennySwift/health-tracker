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

Route::post('select/foodEntries', 'SelectController@foodEntries');
Route::post('select/exercises', 'SelectController@exercises');
// Route::post('select/foodList', 'SelectController@foodList');
Route::post('select/exerciseList', 'SelectController@exerciseList');
Route::post('select/recipeList', 'SelectController@recipeList');
Route::post('select/unitList', 'SelectController@unitList');
Route::post('select/allFoodsWithUnits', 'SelectController@allFoodsWithUnits');
Route::post('select/weight', 'SelectController@weight');
Route::post('select/recipeContents', 'SelectController@recipeContents');

// ========================================insert========================================

// ========================================update========================================

// ========================================delete========================================
