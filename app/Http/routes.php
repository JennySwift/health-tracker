<?php

/**
 * Views
 */

//Homepage (entries)
Route::get('/', 'EntriesController@index');

//Units
Route::get('/units', 'Units\UnitsController@index');

//Foods
Route::get('/foods', 'Foods\FoodsController@index');

//Exercises
Route::get('/exercises', 'Exercises\ExercisesController@index');

//Journal
Route::get('/journal', 'Journal\JournalController@index');

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

//selects everything (which controller??)
Route::post('select/entries', 'EntriesController@getEntries');

/**
 * Tags
 * These might need fixing up, changing to the correct controller
 */
Route::post('insert/tagsIntoRecipe', 'Tags\TagsController@insertTagsIntoRecipe');
Route::post('insert/recipeTag', 'Tags\TagsController@insertRecipeTag');
Route::post('insert/tagInExercise', 'Tags\TagsController@insertTagInExercise');
Route::post('insert/exerciseTag', 'Tags\TagsController@insertExerciseTag');
Route::post('insert/tagsInExercise', 'Tags\TagsController@insertTagsInExercise');
Route::post('delete/exerciseTag', 'Tags\TagsController@deleteExerciseTag');
Route::post('delete/recipeTag', 'Tags\TagsController@deleteRecipeTag');
Route::post('delete/tagFromExercise', 'Tags\TagsController@deleteTagFromExercise');

/**
 * Select requests
 */

//Autocomplete

//This selects rows from both foods and recipes table.
Route::post('select/autocompleteMenu', 'Search\AutocompleteController@autocompleteMenu');
Route::post('select/autocompleteExercise', 'Search\AutocompleteController@autocompleteExercise');
Route::post('select/autocompleteFood', 'Search\AutocompleteController@autocompleteFood');

//journal
Route::post('select/getJournalEntry', 'Journal\JournalController@getJournalEntry');

//exercises
Route::post('select/getExercises', 'Exercises\ExercisesController@getExercises');
Route::post('select/exerciseSeries', 'Exercises\ExercisesController@getExerciseSeries');
Route::post('select/exerciseSeriesHistory', 'Exercises\ExercisesController@getExerciseSeriesHistory');

//exercise entries
Route::post('select/specificExerciseEntries', 'Exercises\ExerciseEntriesController@getSpecificExerciseEntries');

//foods
Route::post('select/foodInfo', 'Foods\FoodsController@getFoodInfo');
Route::post('select/allFoodsWithUnits', 'Foods\FoodsController@getAllFoodsWithUnits');

//recipes
Route::post('select/filterRecipes', 'Recipes\RecipesController@filterRecipes');
Route::post('select/recipeContents', 'Recipes\RecipesController@getRecipeContents');

//units
Route::post('select/allUnits', 'Units\UnitsController@getAllUnits');

/**
 * Insert requests
 */

//weight
Route::post('insert/weight', 'Weights\WeightsController@insertOrUpdateWeight');

//foods
Route::post('insert/menuEntry', 'Foods\FoodEntriesController@insertMenuEntry');
Route::post('insert/food', 'Foods\FoodsController@insertFood');
Route::post('insert/unitInCalories', 'Foods\CaloriesController@insertUnitInCalories');

//recipes
Route::post('insert/quickRecipe', 'Recipes\RecipesController@insertQuickRecipe');
Route::post('insert/recipeMethod', 'Recipes\RecipesController@insertRecipeMethod');
Route::post('insert/recipe', 'Recipes\RecipesController@insertRecipe');
Route::post('insert/recipeEntry', 'Recipes\RecipesController@insertRecipeEntry');
Route::post('insert/foodIntoRecipe', 'Recipes\RecipesController@insertFoodIntoRecipe');

//exercises
Route::post('insert/exercise', 'Exercises\ExercisesController@insertExercise');
Route::post('insert/deleteAndInsertSeriesIntoWorkouts', 'Exercises\ExercisesController@deleteAndInsertSeriesIntoWorkouts');

//exercise entries
Route::post('insert/exerciseSet', 'Exercises\ExerciseEntriesController@insertExerciseSet');
Route::post('insert/exerciseEntry', 'Exercises\ExerciseEntriesController@insertExerciseEntry');

//units
Route::post('insert/exerciseUnit', 'Units\UnitsController@insertExerciseUnit');
Route::post('insert/foodUnit', 'Units\UnitsController@insertFoodUnit');

//exercise series
Route::post('insert/exerciseSeries', 'Exercises\ExerciseSeriesController@insertExerciseSeries');

//series workout
Route::post('insert/seriesIntoWorkout', 'Exercises\SeriesWorkoutController@insertSeriesIntoWorkout');

//journal
Route::post('insert/journalEntry', 'Journal\JournalController@insertOrUpdateJournalEntry');

/**
 * Update requests
 */

//foods
Route::post('update/defaultUnit', 'Foods\CaloriesController@updateDefaultUnit');
Route::post('update/calories', 'Foods\CaloriesController@updateCalories');

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
Route::post('delete/unitFromCalories', 'Foods\CaloriesController@deleteUnitFromCalories');
Route::post('delete/foodEntry', 'Foods\FoodEntriesController@deleteFoodEntry');

//recipes
Route::post('delete/recipe', 'Recipes\RecipesController@deleteRecipe');
Route::post('delete/foodFromRecipe', 'Recipes\RecipesController@deleteFoodFromRecipe');
Route::post('delete/recipeEntry', 'Recipes\RecipesController@deleteRecipeEntry');

//exercises
Route::post('delete/exercise', 'Exercises\ExercisesController@deleteExercise');

//exercise entries
Route::post('delete/exerciseEntry', 'Exercises\ExerciseEntriesController@deleteExerciseEntry');

//exercise series
Route::post('delete/exerciseSeries', 'Exercises\ExerciseSeriesController@deleteExerciseSeries');

//units
Route::post('delete/foodUnit', 'Units\UnitsController@deleteFoodUnit');
Route::post('delete/exerciseUnit', 'Units\UnitsController@deleteExerciseUnit');