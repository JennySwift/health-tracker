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

// ====================================================================================
// ========================================resources========================================
// ====================================================================================


Route::resource('weights', 'Weights\WeightsController');


// ====================================================================================
// ========================================ajax========================================
// ====================================================================================

// ========================================select========================================

Route::post('select/pageLoad', 'SelectController@pageLoad');
Route::post('select/journalEntry', 'SelectController@journalEntry');
Route::post('select/specificExerciseEntries', 'SelectController@specificExerciseEntries');
Route::post('select/exerciseSeriesHistory', 'SelectController@exerciseSeriesHistory');
Route::post('select/entries', 'SelectController@entries');
Route::post('select/foodInfo', 'SelectController@foodInfo');
Route::post('select/exerciseSeries', 'SelectController@exerciseSeries');
// Route::post('select/foodEntries', 'SelectController@foodEntries');
// Route::post('select/exercises', 'SelectController@exercises');
// Route::post('select/foodList', 'SelectController@foodList');
// Route::post('select/exerciseList', 'SelectController@exerciseList');
Route::post('select/filterRecipes', 'SelectController@filterRecipes');
Route::post('select/unitList', 'SelectController@unitList');
Route::post('select/allFoodsWithUnits', 'SelectController@allFoodsWithUnits');
Route::post('select/weight', 'SelectController@weight');
Route::post('select/recipeContents', 'SelectController@recipeContents');
Route::post('select/autocompleteExercise', 'SelectController@autocompleteExercise');
Route::post('select/autocompleteFood', 'SelectController@autocompleteFood');
Route::post('select/autocompleteMenu', 'SelectController@autocompleteMenu');

// ========================================insert========================================

Route::post('insert/item', 'InsertController@item');
Route::post('insert/recipeTag', 'InsertController@recipeTag');
Route::post('insert/seriesIntoWorkout', 'InsertController@seriesIntoWorkout');
Route::post('insert/deleteAndInsertSeriesIntoWorkouts', 'InsertController@deleteAndInsertSeriesIntoWorkouts');
Route::post('insert/exerciseTag', 'InsertController@exerciseTag');
Route::post('insert/exerciseSeries', 'InsertController@exerciseSeries');
Route::post('insert/exerciseSet', 'InsertController@exerciseSet');
Route::post('insert/tagInExercise', 'InsertController@tagInExercise');
Route::post('insert/tagsInExercise', 'InsertController@tagsInExercise');
Route::post('insert/tagsIntoRecipe', 'InsertController@tagsIntoRecipe');
Route::post('insert/quickRecipe', 'InsertController@quickRecipe');
Route::post('insert/recipeMethod', 'InsertController@recipeMethod');
Route::post('insert/journalEntry', 'InsertController@journalEntry');
Route::post('insert/food', 'InsertController@food');
Route::post('insert/recipe', 'InsertController@recipe');
Route::post('insert/exercise', 'InsertController@exercise');
Route::post('insert/exerciseUnit', 'InsertController@exerciseUnit');
Route::post('insert/unitInCalories', 'InsertController@unitInCalories');
Route::post('insert/foodUnit', 'InsertController@foodUnit');
Route::post('insert/menuEntry', 'InsertController@menuEntry');
Route::post('insert/recipeEntry', 'InsertController@recipeEntry');
Route::post('insert/exerciseEntry', 'InsertController@exerciseEntry');
Route::post('insert/weight', 'InsertController@weight');
Route::post('insert/foodIntoRecipe', 'InsertController@foodIntoRecipe');

// ========================================update========================================

Route::post('update/defaultUnit', 'UpdateController@defaultUnit');
Route::post('update/exerciseSeries', 'UpdateController@exerciseSeries');
Route::post('update/exerciseStepNumber', 'UpdateController@exerciseStepNumber');
Route::post('update/recipeMethod', 'UpdateController@recipeMethod');
Route::post('update/defaultExerciseQuantity', 'UpdateController@defaultExerciseQuantity');
Route::post('update/journalEntry', 'UpdateController@journalEntry');
Route::post('update/defaultExerciseUnit', 'UpdateController@defaultExerciseUnit');
Route::post('update/calories', 'UpdateController@calories');

// ========================================delete========================================

// Route::post('delete/item', 'DeleteController@item');
Route::post('delete/recipe', 'DeleteController@recipe');
Route::post('delete/recipeTag', 'DeleteController@recipeTag');
Route::post('delete/exerciseSeries', 'DeleteController@exerciseSeries');
Route::post('delete/tagFromExercise', 'DeleteController@tagFromExercise');
Route::post('delete/exerciseTag', 'DeleteController@exerciseTag');
Route::post('delete/foodFromRecipe', 'DeleteController@foodFromRecipe');
Route::post('delete/food', 'DeleteController@food');
Route::post('delete/unitFromCalories', 'DeleteController@unitFromCalories');
Route::post('delete/exercise', 'DeleteController@exercise');
Route::post('delete/exerciseUnit', 'DeleteController@exerciseUnit');
Route::post('delete/foodUnit', 'DeleteController@foodUnit');
Route::post('delete/foodEntry', 'DeleteController@foodEntry');
Route::post('delete/recipeEntry', 'DeleteController@recipeEntry');
Route::post('delete/exerciseEntry', 'DeleteController@exerciseEntry');