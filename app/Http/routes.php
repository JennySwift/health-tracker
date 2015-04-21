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

//everything (which controller??)
Route::post('select/entries', 'SelectController@entries');

//journal
Route::post('select/journalEntry', 'Journal\JournalController@getJournalEntry');

//exercises
Route::post('select/autocompleteExercise', 'Exercises\ExercisesController@autocompleteExercise');
Route::post('select/specificExerciseEntries', 'Exercises\ExercisesController@getSpecificExerciseEntries');
Route::post('select/exerciseSeries', 'Exercises\ExercisesController@getExerciseSeries');
Route::post('select/exerciseSeriesHistory', 'Exercises\ExercisesController@getExerciseSeriesHistory');

//foods
Route::post('select/autocompleteFood', 'Foods\FoodsController@autocompleteFood');
Route::post('select/foodInfo', 'Foods\FoodsController@getFoodInfo');
Route::post('select/allFoodsWithUnits', 'Foods\FoodsController@getAllFoodsWithUnits');

//recipes
Route::post('select/filterRecipes', 'Recipes\RecipesController@filterRecipes');
Route::post('select/recipeContents', 'Recipes\RecipesController@getRecipeContents');

//which controller?
Route::post('select/autocompleteMenu', 'SelectController@autocompleteMenu');

//units
Route::post('select/unitList', 'SelectController@unitList');

/**
 * Insert requests
 */

//weight
Route::post('insert/weight', 'Weights\WeightsController@weight');

//foods
Route::post('insert/menuEntry', 'Foods\FoodsController@insertMenuEntry');
Route::post('insert/food', 'Foods\FoodsController@insertFood');
Route::post('insert/unitInCalories', 'Foods\FoodsController@insertUnitInCalories');
Route::post('insert/foodUnit', 'Foods\FoodsController@insertFoodUnit');

//recipes
Route::post('insert/quickRecipe', 'Recipes\RecipesController@insertQuickRecipe');
Route::post('insert/recipeMethod', 'Recipes\RecipesController@insertRecipeMethod');
Route::post('insert/tagsIntoRecipe', 'Recipes\RecipesController@insertTagsIntoRecipe');
Route::post('insert/recipe', 'Recipes\RecipesController@insertRecipe');
Route::post('insert/recipeEntry', 'Recipes\RecipesController@insertRecipeEntry');
Route::post('insert/foodIntoRecipe', 'Recipes\RecipesController@insertFoodIntoRecipe');
Route::post('insert/recipeTag', 'Recipes\RecipesController@insertRecipeTag');

//exercises
Route::post('insert/exerciseEntry', 'Exercises\ExercisesController@insertExerciseEntry');
Route::post('insert/tagInExercise', 'Exercises\ExercisesController@insertTagInExercise');
Route::post('insert/tagsInExercise', 'Exercises\ExercisesController@insertTagsInExercise');
Route::post('insert/exercise', 'Exercises\ExercisesController@insertExercise');
Route::post('insert/exerciseUnit', 'Exercises\ExercisesController@insertExerciseUnit');
Route::post('insert/seriesIntoWorkout', 'Exercises\ExercisesController@insertSeriesIntoWorkout');
Route::post('insert/deleteAndInsertSeriesIntoWorkouts', 'Exercises\ExercisesController@deleteAndInsertSeriesIntoWorkouts');
Route::post('insert/exerciseTag', 'Exercises\ExercisesController@insertExerciseTag');
Route::post('insert/exerciseSeries', 'Exercises\ExercisesController@insertExerciseSeries');
Route::post('insert/exerciseSet', 'Exercises\ExercisesController@insertExerciseSet');

//journal
Route::post('insert/journalEntry', 'Journal\JournalController@insertOrUpdateJournalEntry');

/**
 * Update requests
 */

//foods
Route::post('update/defaultUnit', 'Foods\FoodsController@updateDefaultUnit');
Route::post('update/calories', 'Foods\FoodsController@updateCalories');

//recipes
Route::post('update/recipeMethod', 'Recipes\RecipesController@updateRecipeMethod');

//exercises
Route::post('update/exerciseSeries', 'Exercises\ExercisesController@updateExerciseSeries');
Route::post('update/exerciseStepNumber', 'Exercises\ExercisesController@updateExerciseStepNumber');
Route::post('update/defaultExerciseQuantity', 'Exercises\ExercisesController@updateDefaultExerciseQuantity');
Route::post('update/defaultExerciseUnit', 'Exercises\ExercisesController@updateDefaultExerciseUnit');

//journal
Route::post('update/journalEntry', 'Journal\JournalController@updateJournalEntry');

/**
 * Delete requests
 */

//foods
Route::post('delete/food', 'Foods\FoodsController@deleteFood');
Route::post('delete/unitFromCalories', 'Foods\FoodsController@deleteUnitFromCalories');
Route::post('delete/foodUnit', 'Foods\FoodsController@deleteFoodUnit');
Route::post('delete/foodEntry', 'Foods\FoodsController@deleteFoodEntry');

//recipes
Route::post('delete/recipe', 'Recipes\RecipesController@deleteRecipe');
Route::post('delete/recipeTag', 'Recipes\RecipesController@deleteRecipeTag');
Route::post('delete/foodFromRecipe', 'Recipes\RecipesController@deleteFoodFromRecipe');
Route::post('delete/recipeEntry', 'Recipes\RecipesController@deleteRecipeEntry');

//exercises
Route::post('delete/exerciseSeries', 'Exercises\ExercisesController@deleteExerciseSeries');
Route::post('delete/tagFromExercise', 'Exercises\ExercisesController@deleteTagFromExercise');
Route::post('delete/exerciseTag', 'Exercises\ExercisesController@deleteExerciseTag');
Route::post('delete/exercise', 'Exercises\ExercisesController@deleteExercise');
Route::post('delete/exerciseUnit', 'Exercises\ExercisesController@deleteExerciseUnit');
Route::post('delete/exerciseEntry', 'Exercises\ExercisesController@deleteExerciseEntry');
