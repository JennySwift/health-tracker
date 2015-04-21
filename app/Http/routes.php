<?php

/**
 * Homepage
 */

Route::get('/', 'HomeController@index');

/**
 * Authentication
 */

Route::group(['prefix' => 'auth', 'namespace' => 'Auth'], function(){

    Route::group(['middleware' => 'guest'], function(){
        // Login
        Route::get('login', ['as' => 'auth.login', 'uses' => 'AuthController@getLogin']);
        Route::post('login', ['as' => 'auth.login.store', 'before' => 'throttle:6,60', 'uses' => 'AuthController@postLogin']);

        // Register
        Route::get('register', ['as' => 'auth.register', 'uses' => 'AuthController@getRegister']);
        Route::post('register', ['as' => 'auth.register.store', 'uses' => 'AuthController@postRegister']);
    });

    Route::group(['middleware' => 'auth'], function(){
        // Logout
        Route::get('logout', ['as' => 'auth.logout', 'uses' => 'AuthController@getLogout']);
    });

});

Route::controllers([
	// 'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

/**
 * Resources
 */

Route::resource('weights', 'Weights\WeightsController');

/**
 * Ajax
 */

/**
 * Select requests
 */

//page load
Route::post('select/pageLoad', 'SelectController@pageLoad');

//everything
Route::post('select/entries', 'SelectController@entries');

//journal
Route::post('select/journalEntry', 'SelectController@journalEntry');

//exercises
Route::post('select/specificExerciseEntries', 'SelectController@specificExerciseEntries');
Route::post('select/exerciseSeries', 'SelectController@exerciseSeries');
Route::post('select/exerciseSeriesHistory', 'SelectController@exerciseSeriesHistory');

//foods
Route::post('select/foodInfo', 'SelectController@foodInfo');
Route::post('select/allFoodsWithUnits', 'SelectController@allFoodsWithUnits');

//recipes
Route::post('select/filterRecipes', 'SelectController@filterRecipes');
Route::post('select/recipeContents', 'SelectController@recipeContents');

//autocomplete
Route::post('select/autocompleteExercise', 'SelectController@autocompleteExercise');
Route::post('select/autocompleteFood', 'SelectController@autocompleteFood');
Route::post('select/autocompleteMenu', 'SelectController@autocompleteMenu');

//units
Route::post('select/unitList', 'SelectController@unitList');

/**
 * Insert requests
 */

//weight
Route::post('insert/weight', 'InsertController@weight');

//foods
Route::post('insert/food', 'InsertController@food');
Route::post('insert/unitInCalories', 'InsertController@unitInCalories');
Route::post('insert/foodUnit', 'InsertController@foodUnit');
Route::post('insert/menuEntry', 'InsertController@menuEntry');

//recipes
Route::post('insert/quickRecipe', 'InsertController@quickRecipe');
Route::post('insert/recipeMethod', 'InsertController@recipeMethod');
Route::post('insert/tagsIntoRecipe', 'InsertController@tagsIntoRecipe');
Route::post('insert/recipe', 'InsertController@recipe');
Route::post('insert/recipeEntry', 'InsertController@recipeEntry');
Route::post('insert/foodIntoRecipe', 'InsertController@foodIntoRecipe');
Route::post('insert/recipeTag', 'InsertController@recipeTag');

//exercises
Route::post('insert/exerciseEntry', 'InsertController@exerciseEntry');
Route::post('insert/tagInExercise', 'InsertController@tagInExercise');
Route::post('insert/tagsInExercise', 'InsertController@tagsInExercise');
Route::post('insert/exercise', 'InsertController@exercise');
Route::post('insert/exerciseUnit', 'InsertController@exerciseUnit');
Route::post('insert/seriesIntoWorkout', 'InsertController@seriesIntoWorkout');
Route::post('insert/deleteAndInsertSeriesIntoWorkouts', 'InsertController@deleteAndInsertSeriesIntoWorkouts');
Route::post('insert/exerciseTag', 'InsertController@exerciseTag');
Route::post('insert/exerciseSeries', 'InsertController@exerciseSeries');
Route::post('insert/exerciseSet', 'InsertController@exerciseSet');

//journal
Route::post('insert/journalEntry', 'InsertController@journalEntry');

/**
 * Update requests
 */

//foods
Route::post('update/defaultUnit', 'UpdateController@defaultUnit');
Route::post('update/calories', 'UpdateController@calories');

//recipes
Route::post('update/recipeMethod', 'UpdateController@recipeMethod');

//exercises
Route::post('update/exerciseSeries', 'UpdateController@exerciseSeries');
Route::post('update/exerciseStepNumber', 'UpdateController@exerciseStepNumber');
Route::post('update/defaultExerciseQuantity', 'UpdateController@defaultExerciseQuantity');
Route::post('update/defaultExerciseUnit', 'UpdateController@defaultExerciseUnit');

//journal
Route::post('update/journalEntry', 'UpdateController@journalEntry');

/**
 * Delete requests
 */

//foods
Route::post('delete/food', 'DeleteController@food');
Route::post('delete/unitFromCalories', 'DeleteController@unitFromCalories');
Route::post('delete/foodUnit', 'DeleteController@foodUnit');
Route::post('delete/foodEntry', 'DeleteController@foodEntry');

//recipes
Route::post('delete/recipe', 'DeleteController@recipe');
Route::post('delete/recipeTag', 'DeleteController@recipeTag');
Route::post('delete/foodFromRecipe', 'DeleteController@foodFromRecipe');
Route::post('delete/recipeEntry', 'DeleteController@recipeEntry');

//exercises
Route::post('delete/exerciseSeries', 'DeleteController@exerciseSeries');
Route::post('delete/tagFromExercise', 'DeleteController@tagFromExercise');
Route::post('delete/exerciseTag', 'DeleteController@exerciseTag');
Route::post('delete/exercise', 'DeleteController@exercise');
Route::post('delete/exerciseUnit', 'DeleteController@exerciseUnit');
Route::post('delete/exerciseEntry', 'DeleteController@exerciseEntry');
